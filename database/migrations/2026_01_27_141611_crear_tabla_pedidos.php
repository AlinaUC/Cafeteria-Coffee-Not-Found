<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->string('numero_pedido', 20)->unique();
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->decimal('monto_total', 8, 2)->default(0);
            $table->enum('estado', ['pendiente', 'confirmado', 'preparando', 'listo', 'completado', 'cancelado'])
                  ->default('pendiente');
            $table->enum('estado_pago', ['pendiente', 'pagado', 'reembolsado'])->default('pendiente');
            $table->string('metodo_pago', 50)->nullable();
            $table->string('stripe_payment_intent')->nullable();
            $table->text('comprobante_pago')->nullable();
            $table->timestamp('comprobante_subido_en')->nullable();
            $table->dateTime('programado_para')->nullable();
            $table->text('notas_especiales')->nullable();
            // NUEVO: posición en la cola FIFO
            $table->unsignedInteger('posicion_cola')->nullable();
            $table->timestamps();
        });

        // NUEVO: tabla de configuración global de la cafetería
        Schema::create('configuracion_cafeteria', function (Blueprint $table) {
            $table->id();
            $table->string('clave')->unique();
            $table->string('valor');
            $table->timestamps();
        });

        // Valor inicial: pedidos activos
        DB::table('configuracion_cafeteria')->insert([
            ['clave' => 'pedidos_pausados', 'valor' => '0', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('pedidos');
        Schema::dropIfExists('configuracion_cafeteria');
    }
};