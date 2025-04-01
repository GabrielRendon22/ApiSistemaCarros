<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;  // Agregar importación de JWTSubject

class Usuario extends Authenticatable implements JWTSubject  // Implementar la interfaz JWTSubject
{
    use HasApiTokens, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';

    protected $fillable = ['nombres', 'email', 'telefono', 'dui', 'fecha_registro', 'id_rol', 'password'];

    protected $hidden = ['password', 'remember_token'];

    // Relación con roles
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }

    // Relación con contraseñas (si es una tabla separada)
    public function contrasenas()
    {
        return $this->hasMany(Contrasena::class, 'id_usuario', 'id_usuario');
    }

    // Relación con suscripciones
    public function suscripciones()
    {
        return $this->hasMany(Suscripcion::class, 'id_cliente', 'id_usuario');
    }

    // Mutador para encriptar la contraseña automáticamente
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * Get the identifier that will be stored in the JWT payload.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        // Retornar el identificador único, en este caso id_usuario
        return $this->getKey();
    }

    /**
     * Get custom claims to add to the JWT payload.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        // Devolver un array vacío si no tienes datos adicionales que agregar
        return [];
    }
}
