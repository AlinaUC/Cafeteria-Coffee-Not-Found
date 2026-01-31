<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\ItemPedido;
use App\Models\ProductoMenu;
use App\Models\Inventario;
use App\Models\MovimientoInventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PedidoController extends Controller
{
    public function index()
    {
        $pedidos = Pedido::where('usuario_id', auth()->id())
                        ->with('items.producto')
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);
        
        return view('pedidos.index', compact('pedidos'));
    }

    public function show($id)
    {
        $pedido = Pedido::where('usuario_id', auth()->id())
                       ->with('items.producto')
                       ->findOrFail($id);
        
        return view('pedidos.show', compact('pedido'));
    }

    public function crear(Request $request)
    {
        $request->validate([
            'notas_especiales' => 'nullable|string|max:500',
            'programado_para' => 'nullable|date|after:now',
        ]);

        $carrito = session()->get('carrito', []);
        
        if (empty($carrito)) {
            return back()->with('error', 'El carrito está vacío');
        }

        DB::beginTransaction();
        
        try {
            // Crear pedido
            $pedido = Pedido::create([
                'numero_pedido' => Pedido::generarNumeroPedido(),
                'usuario_id' => auth()->id(),
                'monto_total' => 0,
                'estado' => 'pendiente',
                'estado_pago' => 'pendiente',
                'programado_para' => $request->programado_para,
                'notas_especiales' => $request->notas_especiales,
            ]);

            $montoTotal = 0;

            // Crear items del pedido
            foreach ($carrito as $item) {
                $producto = ProductoMenu::findOrFail($item['id']);
                
                // Verificar disponibilidad
                if ($producto->cantidad_disponible < $item['cantidad']) {
                    throw new \Exception("No hay suficiente stock de {$producto->nombre}");
                }

                $precioTotal = $producto->precio * $item['cantidad'];
                $montoTotal += $precioTotal;

                ItemPedido::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $producto->id,
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $producto->precio,
                    'precio_total' => $precioTotal,
                ]);

                // Actualizar inventario
                $inventario = Inventario::where('producto_id', $producto->id)->first();
                $cantidadAnterior = $inventario->cantidad_actual;
                $cantidadNueva = $cantidadAnterior - $item['cantidad'];
                
                $inventario->update([
                    'cantidad_actual' => $cantidadNueva,
                ]);

                // Registrar movimiento de inventario
                MovimientoInventario::create([
                    'producto_id' => $producto->id,
                    'tipo' => 'salida',
                    'cantidad' => $item['cantidad'],
                    'cantidad_anterior' => $cantidadAnterior,
                    'cantidad_nueva' => $cantidadNueva,
                    'motivo' => 'Pedido #' . $pedido->numero_pedido,
                    'usuario_id' => auth()->id(),
                ]);

                // Actualizar cantidad disponible en producto
                $producto->update([
                    'cantidad_disponible' => $cantidadNueva,
                ]);
            }

            // Actualizar monto total del pedido
            $pedido->update([
                'monto_total' => $montoTotal,
            ]);

            DB::commit();
            
            // Limpiar carrito
            session()->forget('carrito');
            
            // Guardar pedido_id en sesión para el pago
            session()->put('pedido_pago_id', $pedido->id);
            
            // Redirigir a la página de selección de método de pago
            return redirect()->route('pedidos.seleccionar.pago')
                           ->with('success', 'Pedido creado exitosamente. Selecciona tu método de pago.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear el pedido: ' . $e->getMessage());
        }
    }

    // Mostrar página de selección de método de pago
    public function seleccionarPago()
    {
        $pedidoId = session()->get('pedido_pago_id');
        
        if (!$pedidoId) {
            return redirect()->route('menu.index')
                           ->with('error', 'No hay pedido pendiente de pago');
        }

        $pedido = Pedido::with('items.producto')->findOrFail($pedidoId);

        return view('pedidos.seleccionar-pago', compact('pedido'));
    }

    // Procesar pago según método seleccionado
    public function procesarPago(Request $request)
    {
        $request->validate([
            'metodo_pago' => 'required|in:tarjeta,efectivo,qr',
        ]);

        $pedidoId = session()->get('pedido_pago_id');
        
        if (!$pedidoId) {
            return redirect()->route('menu.index')
                           ->with('error', 'No hay pedido pendiente de pago');
        }

        $pedido = Pedido::findOrFail($pedidoId);

        // Método de pago: EFECTIVO
        if ($request->metodo_pago == 'efectivo') {
            $pedido->update([
                'metodo_pago' => 'efectivo',
                'estado_pago' => 'pendiente',
            ]);

            session()->forget('pedido_pago_id');

            return redirect()->route('pedidos.show', $pedido->id)
                           ->with('success', '¡Pedido creado exitosamente! Paga en efectivo al recoger tu pedido en la cafetería.');
        }

        // Método de pago: QR - Redirigir a pantalla de QR
        if ($request->metodo_pago == 'qr') {
            return redirect()->route('pedidos.pago.qr');
        }

        // Método de pago: TARJETA (Stripe)
        if ($request->metodo_pago == 'tarjeta') {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            try {
                $session = Session::create([
                    'payment_method_types' => ['card'],
                    'line_items' => [[
                        'price_data' => [
                            'currency' => 'usd',
                            'product_data' => [
                                'name' => 'Pedido ' . $pedido->numero_pedido,
                                'description' => 'Coffee Not Found - Cafetería UPDS',
                            ],
                            'unit_amount' => round($pedido->monto_total * 100),
                        ],
                        'quantity' => 1,
                    ]],
                    'mode' => 'payment',
                    'success_url' => route('pedidos.pago.exito') . '?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => route('pedidos.pago.cancelado'),
                    'metadata' => [
                        'pedido_id' => $pedido->id,
                    ],
                ]);

                return redirect($session->url);

            } catch (\Exception $e) {
                return back()->with('error', 'Error al procesar el pago con tarjeta: ' . $e->getMessage());
            }
        }

        return back()->with('error', 'Método de pago no válido');
    }

    // ==========================
    // MÉTODOS DE PAGO QR
    // ==========================

    // Mostrar pantalla de pago QR
    public function mostrarPagoQR()
    {
        $pedidoId = session()->get('pedido_pago_id');
        
        if (!$pedidoId) {
            return redirect()->route('menu.index')
                           ->with('error', 'No hay pedido pendiente de pago');
        }

        $pedido = Pedido::with('items.producto')->findOrFail($pedidoId);

        return view('pedidos.pago-qr', compact('pedido'));
    }

    // Confirmar pago QR (redirige a subir comprobante)
    public function confirmarPagoQR(Request $request)
    {
        $pedidoId = session()->get('pedido_pago_id');
        
        if (!$pedidoId) {
            return redirect()->route('menu.index')
                           ->with('error', 'No hay pedido pendiente de pago');
        }

        $pedido = Pedido::findOrFail($pedidoId);

        // Marcar como QR pero pendiente de confirmación
        $pedido->update([
            'metodo_pago' => 'qr',
            'estado_pago' => 'pendiente',
        ]);

        session()->forget('pedido_pago_id');

        // Redirigir a página para subir comprobante
        return redirect()->route('pedidos.subir.comprobante', $pedido->id)
                       ->with('success', 'Por favor, sube tu comprobante de pago para verificar la transacción.');
    }

    // Página de éxito para pago QR (después de subir comprobante)
    public function pagoExitoQR($pedido_id)
    {
        $pedido = Pedido::where('usuario_id', auth()->id())
                       ->with('items.producto')
                       ->findOrFail($pedido_id);

        return view('pedidos.pago-exito-qr', compact('pedido'));
    }

    // ==========================
    // GESTIÓN DE COMPROBANTES
    // ==========================

    // Mostrar página para subir comprobante
    public function mostrarSubirComprobante($id)
    {
        $pedido = Pedido::where('usuario_id', auth()->id())
                       ->with('items.producto')
                       ->findOrFail($id);

        return view('pedidos.subir-comprobante', compact('pedido'));
    }

    // Subir comprobante de pago
    public function subirComprobante(Request $request, $id)
    {
        $request->validate([
            'comprobante' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120', // 5MB máximo
        ], [
            'comprobante.required' => 'Debes seleccionar un archivo',
            'comprobante.file' => 'El archivo debe ser una imagen o PDF',
            'comprobante.mimes' => 'Solo se permiten archivos JPG, PNG o PDF',
            'comprobante.max' => 'El archivo no debe superar los 5MB',
        ]);

        $pedido = Pedido::where('usuario_id', auth()->id())
                       ->where('id', $id)
                       ->firstOrFail();

        try {
            // Eliminar comprobante anterior si existe
            if ($pedido->comprobante_pago && file_exists(storage_path('app/public/' . $pedido->comprobante_pago))) {
                unlink(storage_path('app/public/' . $pedido->comprobante_pago));
            }

            // Guardar nuevo comprobante
            $archivo = $request->file('comprobante');
            $nombreArchivo = 'comprobante_' . $pedido->numero_pedido . '_' . time() . '.' . $archivo->getClientOriginalExtension();
            $ruta = $archivo->storeAs('comprobantes', $nombreArchivo, 'public');

            // Actualizar pedido
            $pedido->update([
                'comprobante_pago' => $ruta,
                'comprobante_subido_en' => now(),
                'estado_pago' => 'pendiente', // Pendiente hasta que admin confirme
            ]);

            // Redirigir a página de éxito QR
            return redirect()->route('pedidos.pago.exito.qr', $pedido->id)
                           ->with('success', 'Comprobante subido exitosamente. Estamos verificando tu pago.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al subir el comprobante: ' . $e->getMessage());
        }
    }

    // Ver comprobante
    public function verComprobante($id)
    {
        $pedido = Pedido::findOrFail($id);
        
        // Verificar permisos: solo el dueño del pedido o admin pueden ver
        if (!auth()->user()->esAdmin() && $pedido->usuario_id !== auth()->id()) {
            abort(403, 'No tienes permiso para ver este comprobante');
        }

        if (!$pedido->comprobante_pago) {
            abort(404, 'No hay comprobante disponible');
        }

        $rutaCompleta = storage_path('app/public/' . $pedido->comprobante_pago);

        if (!file_exists($rutaCompleta)) {
            abort(404, 'Archivo no encontrado');
        }

        return response()->file($rutaCompleta);
    }

    // ==========================
    // STRIPE PAYMENT
    // ==========================

    public function pagoExito(Request $request)
    {
        $sessionId = $request->get('session_id');
        
        if (!$sessionId) {
            return redirect()->route('menu.index')
                           ->with('error', 'Sesión de pago inválida');
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $session = Session::retrieve($sessionId);
            
            $pedidoId = $session->metadata->pedido_id;
            $pedido = Pedido::findOrFail($pedidoId);

            $pedido->update([
                'estado_pago' => 'pagado',
                'metodo_pago' => 'tarjeta',
                'stripe_payment_intent' => $session->payment_intent,
            ]);

            session()->forget('pedido_pago_id');

            return redirect()->route('pedidos.show', $pedido->id)
                           ->with('success', '¡Pago realizado exitosamente! Tu pedido está siendo procesado por la cocina.');

        } catch (\Exception $e) {
            return redirect()->route('menu.index')
                           ->with('error', 'Error al verificar el pago: ' . $e->getMessage());
        }
    }

    public function pagoCancelado()
    {
        $pedidoId = session()->get('pedido_pago_id');
        
        if ($pedidoId) {
            return redirect()->route('pedidos.seleccionar.pago')
                           ->with('error', 'Pago cancelado. Puedes seleccionar otro método de pago o cancelar el pedido.');
        }
        
        return redirect()->route('menu.index')
                       ->with('error', 'Pago cancelado.');
    }

    // ==========================
    // CANCELAR PEDIDO
    // ==========================

    public function cancelar($id)
    {
        $pedido = Pedido::where('usuario_id', auth()->id())
                       ->where('id', $id)
                       ->where('estado', 'pendiente')
                       ->firstOrFail();

        DB::beginTransaction();

        try {
            // Restaurar inventario
            foreach ($pedido->items as $item) {
                $inventario = Inventario::where('producto_id', $item->producto_id)->first();
                $cantidadAnterior = $inventario->cantidad_actual;
                $cantidadNueva = $cantidadAnterior + $item->cantidad;
                
                $inventario->update([
                    'cantidad_actual' => $cantidadNueva,
                ]);

                MovimientoInventario::create([
                    'producto_id' => $item->producto_id,
                    'tipo' => 'entrada',
                    'cantidad' => $item->cantidad,
                    'cantidad_anterior' => $cantidadAnterior,
                    'cantidad_nueva' => $cantidadNueva,
                    'motivo' => 'Cancelación de pedido #' . $pedido->numero_pedido,
                    'usuario_id' => auth()->id(),
                ]);

                $item->producto->update([
                    'cantidad_disponible' => $cantidadNueva,
                ]);
            }

            // Eliminar comprobante si existe
            if ($pedido->comprobante_pago && file_exists(storage_path('app/public/' . $pedido->comprobante_pago))) {
                unlink(storage_path('app/public/' . $pedido->comprobante_pago));
            }

            $pedido->update([
                'estado' => 'cancelado',
                'estado_pago' => 'reembolsado',
            ]);

            DB::commit();

            return back()->with('success', 'Pedido cancelado exitosamente. El inventario ha sido restaurado.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al cancelar el pedido: ' . $e->getMessage());
        }
    }
}