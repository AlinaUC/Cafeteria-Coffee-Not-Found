<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoMenu extends Model
{
    use HasFactory;

    protected $table = 'productos_menu';

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'imagen',
        'cantidad_disponible',
        'disponible',
        'es_vegetariano',
        'es_vegano',
        'tiempo_preparacion',
        'categoria_id',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'disponible' => 'boolean',
        'es_vegetariano' => 'boolean',
        'es_vegano' => 'boolean',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function inventario()
    {
        return $this->hasOne(Inventario::class, 'producto_id');
    }

    public function itemsPedido()
    {
        return $this->hasMany(ItemPedido::class, 'producto_id');
    }
}