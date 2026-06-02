<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\CocinaController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\ChatbotController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| RUTA PRINCIPAL
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| GOOGLE OAUTH
|--------------------------------------------------------------------------
*/
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');

/*
|--------------------------------------------------------------------------
| CHATBOT
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->post('/chatbot/mensaje', [ChatbotController::class, 'responder'])->name('chatbot.responder');


/*
|--------------------------------------------------------------------------
| RUTAS AUTENTICADAS
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | PERFIL
    |--------------------------------------------------------------------------
    */
    // Vista principal del perfil (reemplaza la ruta anterior)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    
    // Actualizar información básica del perfil
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Gestión de avatar
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');
    
    // Cambio de contraseña
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    
    // Eliminar cuenta (mantén esta si la usas)
    Route::delete('/profile/destroy', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | MENÚ
    |--------------------------------------------------------------------------
    */
    Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
    Route::get('/menu/{id}', [MenuController::class, 'show'])->name('menu.show');

    /*
    |--------------------------------------------------------------------------
    | CARRITO
    |--------------------------------------------------------------------------
    */
    Route::get('/carrito', [CarritoController::class, 'index'])->name('carrito.index');
    Route::post('/carrito/agregar', [CarritoController::class, 'agregar'])->name('carrito.agregar');
    Route::patch('/carrito/actualizar/{id}', [CarritoController::class, 'actualizar'])->name('carrito.actualizar');
    Route::delete('/carrito/eliminar/{id}', [CarritoController::class, 'eliminar'])->name('carrito.eliminar');
    Route::delete('/carrito/vaciar', [CarritoController::class, 'vaciar'])->name('carrito.vaciar');

    /*
    |--------------------------------------------------------------------------
    | PEDIDOS
    |--------------------------------------------------------------------------
    */
    Route::get('/pedidos', [PedidoController::class, 'index'])->name('pedidos.index');
    Route::post('/pedidos/crear', [PedidoController::class, 'crear'])->name('pedidos.crear');
    Route::get('/pedidos/seleccionar/pago', [PedidoController::class, 'seleccionarPago'])->name('pedidos.seleccionar.pago');
    
    // Pago QR
    Route::get('/pedidos/pago/qr', [PedidoController::class, 'mostrarPagoQR'])->name('pedidos.pago.qr');
    Route::post('/pedidos/pago/qr/confirmar', [PedidoController::class, 'confirmarPagoQR'])->name('pedidos.pago.qr.confirmar');
    Route::get('/pedidos/pago/qr/exito/{pedido_id}', [PedidoController::class, 'pagoExitoQR'])->name('pedidos.pago.exito.qr');
    
    // Comprobantes usuario
    Route::get('/pedidos/{id}/subir-comprobante', [PedidoController::class, 'mostrarSubirComprobante'])->name('pedidos.subir.comprobante');
    Route::post('/pedidos/{id}/subir-comprobante', [PedidoController::class, 'subirComprobante'])->name('pedidos.comprobante.subir');
    Route::get('/pedidos/{id}/comprobante', [PedidoController::class, 'verComprobante'])->name('pedidos.comprobante.ver');
    Route::get('/pedidos/{id}', [PedidoController::class, 'show'])->name('pedidos.show');
    Route::post('/pedidos/{id}/cancelar', [PedidoController::class, 'cancelar'])->name('pedidos.cancelar');
    Route::post('/pedidos/pagar', [PedidoController::class, 'procesarPago'])->name('pedidos.pagar');
    Route::get('/pedidos/pago/exito', [PedidoController::class, 'pagoExito'])->name('pedidos.pago.exito');
    Route::get('/pedidos/pago/cancelado', [PedidoController::class, 'pagoCancelado'])->name('pedidos.pago.cancelado');

    /*
    |--------------------------------------------------------------------------
    | COCINA (ROL COCINA O ADMIN)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:cocina,administrador')->group(function () {
        Route::get('/cocina', [CocinaController::class, 'index'])->name('cocina.index');
        Route::post('/cocina/pedido/{id}/confirmar', [CocinaController::class, 'confirmar'])->name('cocina.confirmar');
        Route::post('/cocina/pedido/{id}/listo', [CocinaController::class, 'marcarListo'])->name('cocina.listo');
        Route::post('/cocina/pedido/{id}/entregado', [CocinaController::class, 'marcarEntregado'])->name('cocina.entregado');
        Route::post('/cocina/producto/{id}/disponibilidad', [CocinaController::class, 'cambiarDisponibilidad'])->name('cocina.disponibilidad');

        // NUEVO
        Route::post('/cocina/toggle-pausa', [CocinaController::class, 'togglePausa'])->name('cocina.toggle_pausa');
    });

    /*
    |--------------------------------------------------------------------------
    | ADMINISTRADOR (ROL ADMIN)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:administrador')->prefix('admin')->group(function () {
        // Dashboard
        Route::get('/', [AdminController::class, 'index'])->name('admin.index');
        
        // Usuarios
        Route::get('/usuarios', [AdminController::class, 'usuarios'])->name('admin.usuarios');
        Route::get('/usuarios/{id}', [AdminController::class, 'verUsuario'])->name('admin.usuario.ver');
        Route::post('/usuarios/{id}/rol/asignar', [AdminController::class, 'asignarRol'])->name('admin.usuario.rol.asignar');
        Route::delete('/usuarios/{id}/rol/remover', [AdminController::class, 'removerRol'])->name('admin.usuario.rol.remover');
        
        // Comprobantes
        Route::get('/comprobantes', [AdminController::class, 'comprobantes'])->name('admin.comprobantes');
        Route::get('/comprobantes/{id}', [AdminController::class, 'verDetalleComprobante'])->name('admin.comprobantes.detalle');
        Route::post('/comprobantes/{id}/aprobar', [AdminController::class, 'aprobarComprobante'])->name('admin.comprobantes.aprobar');
        Route::post('/comprobantes/{id}/rechazar', [AdminController::class, 'rechazarComprobante'])->name('admin.comprobantes.rechazar');
        
        // Inventario
        Route::get('/inventario', [InventarioController::class, 'index'])->name('admin.inventario');
        Route::post('/inventario/{id}/actualizar', [InventarioController::class, 'actualizar'])->name('admin.inventario.actualizar');
        Route::get('/inventario/movimientos', [InventarioController::class, 'movimientos'])->name('admin.inventario.movimientos');
        
        // Productos
        Route::get('/productos', [AdminController::class, 'productos'])->name('admin.productos');
        Route::get('/productos/crear', [AdminController::class, 'crearProducto'])->name('admin.productos.crear');
        Route::post('/productos', [AdminController::class, 'guardarProducto'])->name('admin.productos.guardar');
        Route::get('/productos/{id}/editar', [AdminController::class, 'editarProducto'])->name('admin.productos.editar');
        Route::put('/productos/{id}', [AdminController::class, 'actualizarProducto'])->name('admin.productos.actualizar');
        Route::delete('/productos/{id}', [AdminController::class, 'eliminarProducto'])->name('admin.productos.eliminar');
    });
});

require __DIR__.'/auth.php';