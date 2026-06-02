<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\ProductoMenu;
use Illuminate\Http\Request;
use App\Events\PedidoActualizado;

class CocinaController extends Controller
{
    // ─── Dashboard principal ──────────────────────────────────────────────────

    public function index()
    {
        // FIFO: los pedidos se muestran por posición en cola, luego por fecha
        $pedidosPendientes = Pedido::with(['usuario', 'items.producto'])
            ->where('estado', 'pendiente')
            ->orderBy('posicion_cola')
            ->orderBy('created_at')
            ->get();

        $pedidosPreparando = Pedido::with(['usuario', 'items.producto'])
            ->whereIn('estado', ['confirmado', 'preparando'])
            ->orderBy('posicion_cola')
            ->orderBy('created_at')
            ->get();

        $pedidosListos = Pedido::with(['usuario', 'items.producto'])
            ->where('estado', 'listo')
            ->orderBy('updated_at', 'desc')
            ->get();

        $estadisticas = [
            'pendientes'      => $pedidosPendientes->count(),
            'preparando'      => $pedidosPreparando->count(),
            'listos'          => $pedidosListos->count(),
            'completados_hoy' => Pedido::where('estado', 'completado')
                                       ->whereDate('updated_at', today())
                                       ->count(),
            // NUEVO: total en cola para el contador FIFO
            'total_en_cola'   => Pedido::totalEnCola(),
        ];

        // NUEVO: estado actual de la pausa
        $pedidosPausados = Pedido::estanPausados();

        return view('cocina.index', compact(
            'pedidosPendientes',
            'pedidosPreparando',
            'pedidosListos',
            'estadisticas',
            'pedidosPausados'   // NUEVO
        ));
    }

    // ─── Confirmar pedido ─────────────────────────────────────────────────────

    public function confirmar($id)
    {
        $pedido = Pedido::findOrFail($id);

        if ($pedido->estado !== 'pendiente') {
            return back()->with('error', 'El pedido no puede ser confirmado');
        }

        $pedido->update(['estado' => 'preparando']);

        event(new PedidoActualizado($pedido, '¡Tu pedido está siendo preparado! 👨‍🍳'));

        return back()->with('success', 'Pedido confirmado y en preparación');
    }

    // ─── Marcar como listo ────────────────────────────────────────────────────

    public function marcarListo($id)
    {
        $pedido = Pedido::findOrFail($id);

        if (!in_array($pedido->estado, ['confirmado', 'preparando'])) {
            return back()->with('error', 'El pedido no está en preparación');
        }

        $pedido->update(['estado' => 'listo']);

        event(new PedidoActualizado($pedido, '¡Tu pedido está listo para recoger! ✅'));

        return back()->with('success', 'Pedido marcado como listo');
    }

    // ─── Marcar como entregado ────────────────────────────────────────────────

    public function marcarEntregado($id)
    {
        $pedido = Pedido::findOrFail($id);

        if ($pedido->estado !== 'listo') {
            return back()->with('error', 'El pedido no está listo');
        }

        $pedido->update(['estado' => 'completado']);

        event(new PedidoActualizado($pedido, '¡Que disfrutes tu pedido! 🎉'));

        return back()->with('success', 'Pedido entregado exitosamente');
    }

    // ─── Cambiar disponibilidad de producto ───────────────────────────────────

    public function cambiarDisponibilidad($id)
    {
        $producto = ProductoMenu::findOrFail($id);
        $producto->update(['disponible' => !$producto->disponible]);

        $estado = $producto->disponible ? 'disponible' : 'no disponible';

        return back()->with('success', "Producto marcado como {$estado}");
    }

    // ─── NUEVO: Pausar / reanudar recepción de pedidos ────────────────────────

    public function togglePausa()
    {
        $estadoActual = Pedido::estanPausados();
        Pedido::setPausa(!$estadoActual);

        $mensaje = !$estadoActual
            ? '⏸️ Recepción de pedidos pausada'
            : '▶️ Recepción de pedidos reanudada';

        return back()->with('success', $mensaje);
    }
}