<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    use HasFactory;

    protected $table = 'inventario';

    protected $fillable = [
        'producto_id',
        'cantidad_actual',
        'cantidad_minima',
        'ubicacion',
    ];

    public function producto()
    {
        return $this->belongsTo(ProductoMenu::class, 'producto_id');
    }

    public function movimientos()
    {
        return $this->hasMany(MovimientoInventario::class, 'producto_id', 'producto_id');
    }

    public function necesitaReabastecimiento()
    {
        return $this->cantidad_actual <= $this->cantidad_minima;
    }
}