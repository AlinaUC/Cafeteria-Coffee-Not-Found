<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductoMenu;
use App\Models\Inventario;

class ProductoMenuSeeder extends Seeder
{
    public function run(): void
    {
        $productos = [
            // Desayunos
            [
                'nombre' => 'Desayuno UPDS Completo',
                'descripcion' => 'Huevos fritos o revueltos, pan tostado, queso fresco y mermelada casera',
                'precio' => 25.00,
                'imagen' => 'almuerzo_ejecutivo.png',
                'categoria_id' => 1,
                'tiempo_preparacion' => 15,
                'cantidad_disponible' => 30,
            ],
            [
                'nombre' => 'Café con Leche y Tostadas',
                'descripcion' => 'Café recién molido, tostadas crujientes con mantequilla',
                'precio' => 15.00,
                'categoria_id' => 1,
                'tiempo_preparacion' => 5,
                'cantidad_disponible' => 50,
            ],
            
            // Almuerzos
            [
                'nombre' => 'Almuerzo Ejecutivo',
                'descripcion' => 'Sopa del día, segundo con arroz, pollo o carne, ensalada y refresco',
                'precio' => 35.00,
                'categoria_id' => 2,
                'tiempo_preparacion' => 20,
                'cantidad_disponible' => 40,
            ],
            [
                'nombre' => 'Hamburguesa UPDS',
                'descripcion' => 'Hamburguesa de 150g, lechuga, tomate, queso y papas fritas',
                'precio' => 30.00,
                'categoria_id' => 2,
                'tiempo_preparacion' => 15,
                'cantidad_disponible' => 25,
            ],
            [
                'nombre' => 'Plato Vegetariano',
                'descripcion' => 'Quinoa orgánica, verduras salteadas y aguacate fresco',
                'precio' => 28.00,
                'categoria_id' => 2,
                'tiempo_preparacion' => 15,
                'cantidad_disponible' => 20,
                'es_vegetariano' => true,
                'es_vegano' => true,
            ],
            
            // Bebidas
            [
                'nombre' => 'Jugo Natural',
                'descripcion' => 'Jugos de frutas frescas de temporada',
                'precio' => 12.00,
                'categoria_id' => 3,
                'tiempo_preparacion' => 5,
                'cantidad_disponible' => 60,
            ],
            [
                'nombre' => 'Café Americano',
                'descripcion' => 'Café de grano boliviano de altura',
                'precio' => 8.00,
                'categoria_id' => 3,
                'tiempo_preparacion' => 3,
                'cantidad_disponible' => 100,
            ],
            [
                'nombre' => 'Té de Coca',
                'descripcion' => 'Infusión tradicional boliviana',
                'precio' => 6.00,
                'categoria_id' => 3,
                'tiempo_preparacion' => 3,
                'cantidad_disponible' => 80,
            ],
            
            // Snacks
            [
                'nombre' => 'Empanadas Salteñas',
                'descripcion' => '2 empanadas tradicionales con relleno jugoso',
                'precio' => 20.00,
                'categoria_id' => 4,
                'tiempo_preparacion' => 10,
                'cantidad_disponible' => 35,
            ],
            [
                'nombre' => 'Sándwich Mixto',
                'descripcion' => 'Pan francés, jamón, queso, lechuga y tomate',
                'precio' => 18.00,
                'categoria_id' => 4,
                'tiempo_preparacion' => 8,
                'cantidad_disponible' => 30,
            ],
            
            // Postres
            [
                'nombre' => 'Helado de Canela',
                'descripcion' => 'Helado tradicional boliviano artesanal',
                'precio' => 10.00,
                'categoria_id' => 5,
                'tiempo_preparacion' => 2,
                'cantidad_disponible' => 40,
            ],
            [
                'nombre' => 'Torta de Chocolate',
                'descripcion' => 'Porción generosa con cobertura de chocolate',
                'precio' => 15.00,
                'categoria_id' => 5,
                'tiempo_preparacion' => 5,
                'cantidad_disponible' => 25,
            ],
        ];

        foreach ($productos as $productoData) {
            $producto = ProductoMenu::create($productoData);
            
            // Crear inventario automáticamente
            Inventario::create([
                'producto_id' => $producto->id,
                'cantidad_actual' => $productoData['cantidad_disponible'],
                'cantidad_minima' => 5,
                'ubicacion' => 'Almacén Principal',
            ]);
        }
    }
}