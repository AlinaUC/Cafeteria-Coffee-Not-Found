<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\ProductoMenu;
use Illuminate\Http\Request;
use App\Events\PedidoActualizado; // 🔔 NUEVO: Importar el evento

class CocinaController extends Controller
{
    public function index()
    {
        $pedidosPendientes = Pedido::with(['usuario', 'items.producto'])
                                   ->where('estado', 'pendiente')
                                   ->orderBy('created_at')
                                   ->get();

        $pedidosPreparando = Pedido::with(['usuario', 'items.producto'])
                                   ->whereIn('estado', ['confirmado', 'preparando'])
                                   ->orderBy('created_at')
                                   ->get();

        $pedidosListos = Pedido::with(['usuario', 'items.producto'])
                               ->where('estado', 'listo')
                               ->orderBy('updated_at', 'desc')
                               ->get();

        $estadisticas = [
            'pendientes' => $pedidosPendientes->count(),
            'preparando' => $pedidosPreparando->count(),
            'listos' => $pedidosListos->count(),
            'completados_hoy' => Pedido::where('estado', 'completado')
                                      ->whereDate('updated_at', today())
                                      ->count(),
        ];

        return view('cocina.index', compact(
            'pedidosPendientes',
            'pedidosPreparando',
            'pedidosListos',
            'estadisticas'
        ));
    }

    public function confirmar($id)
    {
        $pedido = Pedido::findOrFail($id);

        if ($pedido->estado !== 'pendiente') {
            return back()->with('error', 'El pedido no puede ser confirmado');
        }

        $pedido->update([
            'estado' => 'preparando',
        ]);

        // 🔔 NUEVO: Disparar notificación
        event(new PedidoActualizado(
            $pedido, 
            '¡Tu pedido está siendo preparado! 👨‍🍳'
        ));

        return back()->with('success', 'Pedido confirmado y en preparación');
    }

    public function marcarListo($id)
    {
        $pedido = Pedido::findOrFail($id);

        if (!in_array($pedido->estado, ['confirmado', 'preparando'])) {
            return back()->with('error', 'El pedido no está en preparación');
        }

        $pedido->update([
            'estado' => 'listo',
        ]);

        // 🔔 NUEVO: Disparar notificación
        event(new PedidoActualizado(
            $pedido, 
            '¡Tu pedido está listo para recoger! ✅'
        ));

        return back()->with('success', 'Pedido marcado como listo');
    }

    public function marcarEntregado($id)
    {
        $pedido = Pedido::findOrFail($id);

        if ($pedido->estado !== 'listo') {
            return back()->with('error', 'El pedido no está listo');
        }

        $pedido->update([
            'estado' => 'completado',
        ]);

        // 🔔 NUEVO: Disparar notificación
        event(new PedidoActualizado(
            $pedido, 
            '¡Que disfrutes tu pedido! 🎉'
        ));

        return back()->with('success', 'Pedido entregado exitosamente');
    }

    public function cambiarDisponibilidad($id)
    {
        $producto = ProductoMenu::findOrFail($id);

        $producto->update([
            'disponible' => !$producto->disponible,
        ]);

        $estado = $producto->disponible ? 'disponible' : 'no disponible';

        return back()->with('success', "Producto marcado como {$estado}");
    }
}