<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rol;

class RolSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'nombre' => 'estudiante',
                'descripcion' => 'Usuario estudiante que puede realizar pedidos',
                'activo' => true,
            ],
            [
                'nombre' => 'cocina',
                'descripcion' => 'Personal de cocina que gestiona preparación de pedidos',
                'activo' => true,
            ],
            [
                'nombre' => 'administrador',
                'descripcion' => 'Administrador con acceso completo al sistema',
                'activo' => true,
            ],
        ];

        foreach ($roles as $rol) {
            Rol::create($rol);
        }
    }
}