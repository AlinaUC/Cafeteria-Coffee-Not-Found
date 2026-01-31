<?php

namespace App\Http\Controllers;

use App\Models\ProductoMenu;
use App\Models\Categoria;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $categoriaSeleccionada = $request->get('categoria');
        
        $categorias = Categoria::where('activo', true)
                              ->withCount('productos')
                              ->get();
        
        $query = ProductoMenu::with('categoria')
                            ->where('disponible', true);
        
        if ($categoriaSeleccionada) {
            $query->where('categoria_id', $categoriaSeleccionada);
        }
        
        $productos = $query->orderBy('categoria_id')
                          ->orderBy('nombre')
                          ->get();
        
        return view('menu.index', compact('productos', 'categorias', 'categoriaSeleccionada'));
    }

    public function show($id)
    {
        $producto = ProductoMenu::with('categoria', 'inventario')->findOrFail($id);
        
        return view('menu.show', compact('producto'));
    }
}