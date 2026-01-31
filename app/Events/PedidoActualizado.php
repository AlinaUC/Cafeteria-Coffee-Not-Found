<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Pedido;

class PedidoActualizado implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $pedido;
    public $mensaje;

    /**
     * Create a new event instance.
     */
    public function __construct(Pedido $pedido, $mensaje)
    {
        $this->pedido = $pedido;
        $this->mensaje = $mensaje;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        // Canal privado para el usuario específico
        return [
            new PrivateChannel('pedido.' . $this->pedido->usuario_id),
        ];
    }

    /**
     * Datos que se enviarán con la notificación
     */
    public function broadcastWith(): array
    {
        return [
            'pedido_id' => $this->pedido->id,
            'estado' => $this->pedido->estado,
            'mensaje' => $this->mensaje,
            'tiempo' => now()->format('H:i'),
        ];
    }

    /**
     * Nombre del evento que escuchará el frontend
     */
    public function broadcastAs(): string
    {
        return 'pedido.actualizado';
    }
}