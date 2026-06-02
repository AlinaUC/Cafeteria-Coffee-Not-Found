<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Rol;
use Illuminate\Support\Facades\Hash;

class UsuarioAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Crear usuario administrador
        $admin = User::create([
            'nombre' => 'Administrador',
            'email' => 'admin@upds.edu.bo',
            'password' => Hash::make('password'),
            'estado' => 'activo',
            'email_verificado_en' => now(),
        ]);

        $rolAdmin = Rol::where('nombre', 'administrador')->first();
        $admin->roles()->attach($rolAdmin->id);

        // Crear usuario de cocina
        $cocina = User::create([
            'nombre' => 'Personal Cocina',
            'email' => 'cocina@upds.edu.bo',
            'password' => Hash::make('password'),
            'estado' => 'activo',
            'email_verificado_en' => now(),
        ]);

        $rolCocina = Rol::where('nombre', 'cocina')->first();
        $cocina->roles()->attach($rolCocina->id);

        // Crear usuario estudiante
        $estudiante = User::create([
            'nombre' => 'Estudiante Prueba',
            'email' => 'estudiante@upds.edu.bo',
            'password' => Hash::make('password'),
            'codigo_estudiante' => 'EST2024001',
            'estado' => 'activo',
            'email_verificado_en' => now(),
        ]);

        $rolEstudiante = Rol::where('nombre', 'estudiante')->first();
        $estudiante->roles()->attach($rolEstudiante->id);
    }
}