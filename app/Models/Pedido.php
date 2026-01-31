<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $table = 'pedidos';

    protected $fillable = [
        'numero_pedido',
        'usuario_id',
        'monto_total',
        'estado',
        'estado_pago',
        'metodo_pago',
        'stripe_payment_intent',
        'comprobante_pago',
        'comprobante_subido_en',  
        'programado_para',
        'notas_especiales',
    ];

    protected $casts = [
        'monto_total' => 'decimal:2',
        'programado_para' => 'datetime',
        'comprobante_subido_en' => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function items()
    {
        return $this->hasMany(ItemPedido::class, 'pedido_id');
    }

    public static function generarNumeroPedido()
    {
        $fecha = now()->format('ymd');
        $ultimo = self::whereDate('created_at', today())
                     ->latest()
                     ->first();
        
        $secuencia = $ultimo ? intval(substr($ultimo->numero_pedido, -4)) + 1 : 1;
        
        return 'UPDS-' . $fecha . '-' . str_pad($secuencia, 4, '0', STR_PAD_LEFT);
    }
}