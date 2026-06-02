<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
        'posicion_cola',          // NUEVO: posición en la cola FIFO
    ];

    protected $casts = [
        'monto_total'          => 'decimal:2',
        'programado_para'      => 'datetime',
        'comprobante_subido_en'=> 'datetime',
    ];

    // ─── Relaciones ───────────────────────────────────────────────────────────

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function items()
    {
        return $this->hasMany(ItemPedido::class, 'pedido_id');
    }

    // ─── Helpers de cola ──────────────────────────────────────────────────────

    /**
     * Asigna al pedido la siguiente posición disponible en la cola.
     * Se llama al crear un pedido nuevo.
     */
    public function asignarPosicionCola(): void
    {
        $ultima = self::whereNotIn('estado', ['completado', 'cancelado'])
                      ->max('posicion_cola');

        $this->posicion_cola = ($ultima ?? 0) + 1;
        $this->save();
    }

    /**
     * Devuelve el conteo de pedidos activos en cola
     * (pendientes + confirmados + preparando + listos).
     */
    public static function totalEnCola(): int
    {
        return self::whereNotIn('estado', ['completado', 'cancelado'])->count();
    }

    // ─── Helpers de pausa ─────────────────────────────────────────────────────

    /** Devuelve true si los pedidos están pausados. */
    public static function estanPausados(): bool
    {
        $config = DB::table('configuracion_cafeteria')
                    ->where('clave', 'pedidos_pausados')
                    ->value('valor');

        return $config === '1';
    }

    /** Activa o desactiva la pausa de pedidos. */
    public static function setPausa(bool $pausar): void
    {
        DB::table('configuracion_cafeteria')
          ->where('clave', 'pedidos_pausados')
          ->update(['valor' => $pausar ? '1' : '0', 'updated_at' => now()]);
    }

    // ─── Generador de número de pedido ────────────────────────────────────────

    public static function generarNumeroPedido(): string
    {
        $fecha   = now()->format('ymd');
        $ultimo  = self::whereDate('created_at', today())->latest()->first();
        $secuencia = $ultimo ? intval(substr($ultimo->numero_pedido, -4)) + 1 : 1;

        return 'UPDS-' . $fecha . '-' . str_pad($secuencia, 4, '0', STR_PAD_LEFT);
    }
}