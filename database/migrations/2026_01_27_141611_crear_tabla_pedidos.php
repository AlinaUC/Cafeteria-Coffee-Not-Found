<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
