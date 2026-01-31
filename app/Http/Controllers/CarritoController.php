<?php

namespace App\Http\Controllers;

use App\Models\ProductoMenu;
use Illuminate\Http\Request;

class CarritoController extends Controller
{
    public function index()
    {
        $carrito = session()->get('carrito', []);
        $total = 0;
        
        foreach ($carrito as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }
        
        return view('carrito.index', compact('carrito', 'total'));
    }

    public function agregar(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos_menu,id',
            'cantidad' => 'required|integer|min:1',
        ]);

        $producto = ProductoMenu::findOrFail($request->producto_id);
        
        // Verificar disponibilidad
        if ($producto->cantidad_disponible < $request->cantidad) {
            return back()->with('error', 'No hay suficiente stock disponible');
        }

        $carrito = session()->get('carrito', []);
        
        if (isset($carrito[$producto->id])) {
            $nuevaCantidad = $carrito[$producto->id]['cantidad'] + $request->cantidad;
            
            if ($producto->cantidad_disponible < $nuevaCantidad) {
                return back()->with('error', 'No hay suficiente stock disponible');
            }
            
            $carrito[$producto->id]['cantidad'] = $nuevaCantidad;
        } else {
            $carrito[$producto->id] = [
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'precio' => $producto->precio,
                'cantidad' => $request->cantidad,
                'imagen' => $producto->imagen,
                'tiempo_preparacion' => $producto->tiempo_preparacion,
            ];
        }
        
        session()->put('carrito', $carrito);
        
        return back()->with('success', 'Producto agregado al carrito');
    }

    public function actualizar(Request $request, $id)
    {
        $request->validate([
            'cantidad' => 'required|integer|min:1',
        ]);

        $carrito = session()->get('carrito', []);
        
        if (isset($carrito[$id])) {
            $producto = ProductoMenu::findOrFail($id);
            
            if ($producto->cantidad_disponible < $request->cantidad) {
                return back()->with('error', 'No hay suficiente stock disponible');
            }
            
            $carrito[$id]['cantidad'] = $request->cantidad;
            session()->put('carrito', $carrito);
            
            return back()->with('success', 'Cantidad actualizada');
        }
        
        return back()->with('error', 'Producto no encontrado en el carrito');
    }

    public function eliminar($id)
    {
        $carrito = session()->get('carrito', []);
        
        if (isset($carrito[$id])) {
            unset($carrito[$id]);
            session()->put('carrito', $carrito);
            
            return back()->with('success', 'Producto eliminado del carrito');
        }
        
        return back()->with('error', 'Producto no encontrado');
    }

    public function vaciar()
    {
        session()->forget('carrito');
        
        return back()->with('success', 'Carrito vaciado');
    }
}