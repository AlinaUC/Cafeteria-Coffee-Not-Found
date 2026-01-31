<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Aquí registramos los canales de broadcasting de la aplicación
|
*/

// Canal privado para notificaciones de pedidos de un usuario específico
Broadcast::channel('pedido.{usuarioId}', function ($user, $usuarioId) {
    // El usuario solo puede escuchar sus propias notificaciones
    return (int) $user->id === (int) $usuarioId;
});

// Canal privado para el panel de cocina (todos los cocineros y admins)
Broadcast::channel('cocina', function ($user) {
    return $user->esCocina() || $user->esAdmin();
});