<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Models\ProductoMenu;
use App\Models\MovimientoInventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventarioController extends Controller
{
    public function index()
    {
        $inventarios = Inventario::with('producto.categoria')
                                 ->orderBy('cantidad_actual')
                                 ->paginate(20);

        $alertasBajoStock = Inventario::with('producto')
                                      ->whereColumn('cantidad_actual', '<=', 'cantidad_minima')
                                      ->get();

        return view('admin.inventario.index', compact('inventarios', 'alertasBajoStock'));
    }

    public function actualizar(Request $request, $id)
    {
        $request->validate([
            'tipo' => 'required|in:entrada,salida,ajuste',
            'cantidad' => 'required|integer|min:1',
            'motivo' => 'nullable|string|max:500',
        ]);

        $inventario = Inventario::with('producto')->findOrFail($id);
        
        DB::beginTransaction();

        try {
            $cantidadAnterior = $inventario->cantidad_actual;
            
            switch ($request->tipo) {
                case 'entrada':
                    $cantidadNueva = $cantidadAnterior + $request->cantidad;
                    break;
                case 'salida':
                    $cantidadNueva = $cantidadAnterior - $request->cantidad;
                    if ($cantidadNueva < 0) {
                        throw new \Exception('No hay suficiente stock para realizar esta operación');
                    }
                    break;
                case 'ajuste':
                    $cantidadNueva = $request->cantidad;
                    break;
                default:
                    throw new \Exception('Tipo de movimiento inválido');
            }

            $inventario->update([
                'cantidad_actual' => $cantidadNueva,
            ]);

            // Actualizar también el producto
            $inventario->producto->update([
                'cantidad_disponible' => $cantidadNueva,
            ]);

            MovimientoInventario::create([
                'producto_id' => $inventario->producto_id,
                'tipo' => $request->tipo,
                'cantidad' => $request->cantidad,
                'cantidad_anterior' => $cantidadAnterior,
                'cantidad_nueva' => $cantidadNueva,
                'motivo' => $request->motivo ?? 'Actualización manual',
                'usuario_id' => auth()->id(),
            ]);

            DB::commit();

            return back()->with('success', 'Inventario actualizado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar inventario: ' . $e->getMessage());
        }
    }

    public function movimientos()
    {
        $movimientos = MovimientoInventario::with(['producto', 'usuario'])
                                           ->orderBy('created_at', 'desc')
                                           ->paginate(50);

        return view('admin.inventario.movimientos', compact('movimientos'));
    }
}