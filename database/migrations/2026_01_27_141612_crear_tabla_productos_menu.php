<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos_menu', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50);
            $table->text('descripcion');
            $table->decimal('precio', 8, 2);
            $table->text('imagen')->nullable();
            $table->integer('cantidad_disponible')->default(0);
            $table->boolean('disponible')->default(true);
            $table->boolean('es_vegetariano')->default(false);
            $table->boolean('es_vegano')->default(false);
            $table->integer('tiempo_preparacion')->default(15);
            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos_menu');
    }
};