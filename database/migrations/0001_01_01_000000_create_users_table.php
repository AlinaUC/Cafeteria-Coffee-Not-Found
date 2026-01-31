<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50);
            $table->string('email', 50)->unique();
            $table->timestamp('email_verificado_en')->nullable();
            $table->string('password');
            $table->string('telefono', 20)->nullable();
            $table->string('codigo_estudiante', 20)->nullable();
            $table->text('avatar')->nullable();
            $table->string('google_id')->nullable()->unique();
            $table->enum('estado', ['activo', 'inactivo', 'suspendido'])->default('activo');
            $table->timestamp('ultimo_acceso')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};