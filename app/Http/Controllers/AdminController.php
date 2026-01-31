<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pedido;
use App\Models\ProductoMenu;
use App\Models\ItemPedido;
use App\Models\Rol;
use App\Models\Categoria;
use App\Models\Inventario;
use App\Models\MovimientoInventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /* ===================== DASHBOARD ===================== */
    public function index()
    {
        $estadisticas = [
            'total_pedidos' => Pedido::count(),
            'pedidos_pendientes' => Pedido::whereIn('estado', ['pendiente','confirmado','preparando'])->count(),
            'total_estudiantes' => User::whereHas('roles', fn($q) => $q->where('nombre','estudiante'))->count(),
            'ingresos_totales' => Pedido::where('estado_pago','pagado')->sum('monto_total'),
            'ingresos_hoy' => Pedido::where('estado_pago','pagado')
                ->whereDate('created_at', today())
                ->sum('monto_total'),
            'pedidos_hoy' => Pedido::whereDate('created_at', today())->count(),
            'comprobantes_pendientes' => Pedido::where('metodo_pago','qr')
                ->where('estado_pago','pendiente')
                ->whereNotNull('comprobante_pago')
                ->count(),
        ];

        return view('admin.index', compact('estadisticas'));
    }

    /* ===================== USUARIOS ===================== */
    public function usuarios()
    {
        $usuarios = User::with('roles')->paginate(20);
        return view('admin.usuarios', compact('usuarios'));
    }

    public function verUsuario($id)
    {
        $usuario = User::with(['roles','pedidos'])->findOrFail($id);
        $roles = Rol::where('activo', true)->get();

        $estadisticasUsuario = [
            'total_pedidos' => $usuario->pedidos->count(),
            'total_gastado' => $usuario->pedidos->where('estado_pago','pagado')->sum('monto_total'),
            'pedido_promedio' => $usuario->pedidos->where('estado_pago','pagado')->avg('monto_total'),
        ];

        return view('admin.usuario-detalle', compact(
            'usuario','roles','estadisticasUsuario'
        ));
    }

    public function asignarRol(Request $request, $id)
    {
        $request->validate(['rol_id' => 'required|exists:roles,id']);

        $usuario = User::findOrFail($id);

        if ($usuario->roles()->where('rol_id', $request->rol_id)->exists()) {
            return back()->with('error','El usuario ya tiene este rol');
        }

        $usuario->roles()->attach($request->rol_id, [
            'asignado_por' => auth()->id()
        ]);

        return back()->with('success','Rol asignado correctamente');
    }

    public function removerRol(Request $request, $id)
    {
        $request->validate(['rol_id' => 'required|exists:roles,id']);

        $usuario = User::findOrFail($id);

        if ($usuario->roles()->count() <= 1) {
            return back()->with('error','No se puede quitar el único rol');
        }

        $usuario->roles()->detach($request->rol_id);
        return back()->with('success','Rol removido');
    }

    /* ===================== COMPROBANTES ===================== */
    public function comprobantes()
    {
        $comprobantesPendientes = Pedido::with(['usuario','items.producto'])
            ->where('metodo_pago','qr')
            ->where('estado_pago','pendiente')
            ->whereNotNull('comprobante_pago')
            ->paginate(10);

        $comprobantesAprobados = Pedido::with('usuario')
            ->where('metodo_pago','qr')
            ->where('estado_pago','pagado')
            ->limit(10)
            ->get();

        return view('admin.comprobantes.index', compact(
            'comprobantesPendientes','comprobantesAprobados'
        ));
    }

    public function aprobarComprobante($id)
    {
        $pedido = Pedido::findOrFail($id);

        $pedido->update(['estado_pago' => 'pagado']);

        return back()->with('success','Comprobante aprobado');
    }

    public function rechazarComprobante(Request $request, $id)
    {
        $request->validate(['motivo' => 'required|string|max:500']);

        $pedido = Pedido::findOrFail($id);

        if ($pedido->comprobante_pago &&
            Storage::disk('public')->exists($pedido->comprobante_pago)) {
            Storage::disk('public')->delete($pedido->comprobante_pago);
        }

        $pedido->update([
            'comprobante_pago' => null,
            'estado_pago' => 'pendiente',
        ]);

        return back()->with('success','Comprobante rechazado');
    }

    /* ===================== INVENTARIO ===================== */
    public function inventario()
    {
        $inventarios = Inventario::with('producto.categoria')
            ->paginate(15);

        $alertasBajoStock = Inventario::with('producto')
            ->whereColumn('cantidad_actual','<=','cantidad_minima')
            ->get();

        return view('admin.inventario.index', compact(
            'inventarios','alertasBajoStock'
        ));
    }

    public function movimientosInventario()
    {
        $movimientos = MovimientoInventario::with(['producto','usuario'])
            ->orderBy('created_at','desc')
            ->paginate(20);

        return view('admin.inventario.movimientos', compact('movimientos'));
    }

    public function actualizarInventario(Request $request, $id)
    {
        $request->validate([
            'tipo' => 'required|in:entrada,salida,ajuste',
            'cantidad' => 'required|integer|min:1',
            'motivo' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $id) {
            $inventario = Inventario::findOrFail($id);
            $anterior = $inventario->cantidad_actual;

            if ($request->tipo === 'entrada') {
                $nueva = $anterior + $request->cantidad;
            } elseif ($request->tipo === 'salida') {
                $nueva = max(0, $anterior - $request->cantidad);
            } else {
                $nueva = $request->cantidad;
            }

            $inventario->update(['cantidad_actual' => $nueva]);

            MovimientoInventario::create([
                'producto_id' => $inventario->producto_id,
                'tipo' => $request->tipo,
                'cantidad' => $request->cantidad,
                'cantidad_anterior' => $anterior,
                'cantidad_nueva' => $nueva,
                'motivo' => $request->motivo,
                'usuario_id' => auth()->id(),
            ]);
        });

        return back()->with('success','Inventario actualizado');
    }

    /* ===================== PRODUCTOS ===================== */
    public function productos()
    {
        $productos = ProductoMenu::with('categoria')->paginate(20);
        return view('admin.productos.index', compact('productos'));
    }

    public function crearProducto()
    {
        $categorias = Categoria::all();
        return view('admin.productos.crear', compact('categorias'));
    }

    public function guardarProducto(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:200',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric',
            'categoria_id' => 'required|exists:categorias,id',
            'cantidad_disponible' => 'required|integer|min:1',
            'tiempo_preparacion' => 'required|integer|min:1',
            'imagen' => 'nullable|image|max:2048',
        ]);

        DB::transaction(function () use ($request) {
            $data = $request->only([
                'nombre','descripcion','precio',
                'categoria_id','cantidad_disponible',
                'tiempo_preparacion'
            ]);

            if ($request->hasFile('imagen')) {
                $data['imagen'] = $request->file('imagen')
                    ->store('productos','public');
            }

            $producto = ProductoMenu::create($data);

            Inventario::create([
                'producto_id' => $producto->id,
                'cantidad_actual' => $request->cantidad_disponible, // Corregido: guarda la cantidad enviada
                'cantidad_minima' => 5,
                'ubicacion' => 'Almacén Principal',
            ]);
        });

        return redirect()->route('admin.productos')
            ->with('success','Producto creado y agregado al inventario');
    }
    public function editarProducto($id)
    {
        $producto = ProductoMenu::findOrFail($id);
        $categorias = Categoria::all();

        return view('admin.productos.editar', compact('producto', 'categorias'));
    }

    public function actualizarProducto(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:200',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric',
            'categoria_id' => 'required|exists:categorias,id',
            'tiempo_preparacion' => 'required|integer|min:1',
            'imagen' => 'nullable|image|max:2048',
        ]);

        $producto = ProductoMenu::findOrFail($id);

        $data = $request->only([
            'nombre',
            'descripcion',
            'precio',
            'categoria_id',
            'tiempo_preparacion'
        ]);

        if ($request->hasFile('imagen')) {
            // borrar anterior
            if ($producto->imagen &&
                Storage::disk('public')->exists($producto->imagen)) {
                Storage::disk('public')->delete($producto->imagen);
            }

            $data['imagen'] = $request->file('imagen')
                ->store('productos', 'public');
        }

        $producto->update($data);

        return redirect()->route('admin.productos')
            ->with('success', 'Producto actualizado correctamente');
    }


    public function eliminarProducto($id)
    {
        $producto = ProductoMenu::findOrFail($id);

        if ($producto->imagen &&
            Storage::disk('public')->exists($producto->imagen)) {
            Storage::disk('public')->delete($producto->imagen);
        }

        $producto->delete();
        return back()->with('success','Producto eliminado');
    }
}
