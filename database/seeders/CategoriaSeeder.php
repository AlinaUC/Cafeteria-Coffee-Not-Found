<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            [
                'nombre' => 'Desayunos',
                'descripcion' => 'Opciones para comenzar el día',
                'activo' => true,
            ],
            [
                'nombre' => 'Almuerzos',
                'descripcion' => 'Platos principales para el mediodía',
                'activo' => true,
            ],
            [
                'nombre' => 'Bebidas',
                'descripcion' => 'Bebidas calientes y frías',
                'activo' => true,
            ],
            [
                'nombre' => 'Snacks',
                'descripcion' => 'Bocadillos y refrigerios',
                'activo' => true,
            ],
            [
                'nombre' => 'Postres',
                'descripcion' => 'Dulces y postres',
                'activo' => true,
            ],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}