<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'email',
        'password',
        'telefono',
        'codigo_estudiante',
        'avatar',
        'google_id',
        'estado',
        'ultimo_acceso',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verificado_en' => 'datetime',
            'password' => 'hashed',
            'ultimo_acceso' => 'datetime',
        ];
    }

    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'usuario_roles', 'usuario_id', 'rol_id')
                    ->withTimestamps();
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'usuario_id');
    }

    public function tieneRol($nombreRol)
    {
        return $this->roles()->where('nombre', $nombreRol)->exists();
    }

    public function esEstudiante()
    {
        return $this->tieneRol('estudiante');
    }

    public function esAdmin()
    {
        return $this->tieneRol('administrador');
    }

    public function esCocina()
    {
        return $this->tieneRol('cocina');
    }
}