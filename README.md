# Coffee Not Found - Guía de Instalación

## 1. Clonar el repositorio

```bash
git clone https://github.com/AlinaUC/Cafeteria-Coffee-Not-Found.git
cd Cafeteria-Coffee-Not-Found
```

## 2. Instalar dependencias PHP

```bash
composer install
```

## 3. Instalar dependencias Node.js

```bash
npm install
```

## 4. Crear archivo de configuración

Copiar el archivo de ejemplo:

```bash
cp .env.example .env
```

Si usan Windows:

```cmd
copy .env.example .env
```

## 5. Configurar la base de datos

Crear una base de datos MySQL llamada:

```text
coffee_not_found
```

Modificar en el archivo `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=coffee_not_found
DB_USERNAME=root
DB_PASSWORD=
```

## 6. Generar la clave de Laravel

```bash
php artisan key:generate
```

## 7. Ejecutar migraciones

```bash
php artisan migrate
```

Si existen seeders:

```bash
php artisan db:seed
```

o

```bash
php artisan migrate:fresh --seed
```

## 8. Crear las tablas para caché, colas y broadcasting

```bash
php artisan queue:table
php artisan cache:table
php artisan migrate
```

## 9. Compilar recursos

```bash
npm run build
```

Para desarrollo:

```bash
npm run dev
```

## 10. Ejecutar el servidor

```bash
php artisan serve
```

Abrir:

```text
http://127.0.0.1:8000
```

## Requisitos

* PHP 8.2 o superior
* Composer
* Node.js 18+
* MySQL
* Git

## Servicios externos

El proyecto utiliza:

* Google OAuth
* Stripe
* Pusher

## Ejecución del Proyecto

Una vez completada la instalación y configuración, abrir varias terminales dentro de la carpeta del proyecto.

### Terminal 1 - Servidor Laravel

```bash
php artisan serve
```

Esto iniciará el servidor web en:

```text
http://127.0.0.1:8000
```

### Terminal 2 - Vite (CSS, JavaScript y tiempo real)

```bash
npm run dev
```

Este comando compila y actualiza automáticamente los recursos frontend.

### Terminal 3 - Cola de trabajos (Queue)

```bash
php artisan queue:work
```

Este proceso es necesario para ejecutar tareas en segundo plano como notificaciones, procesamiento de pedidos y otras funcionalidades que utilizan colas.

### Verificar que todo funciona

Abrir en el navegador:

```text
http://127.0.0.1:8000
```

Si el sistema carga correctamente y no aparecen errores en las terminales, el proyecto está listo para utilizarse.
