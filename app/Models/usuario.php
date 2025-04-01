<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'Usuario';
    protected $primaryKey = 'usuario_id';

    protected $fillable = ['nombres', 'email', 'telefono', 'dui', 'id_rol'];

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'rol_id');
    }

    public function contrasenas()
    {
        return $this->hasMany(Contrasena::class, 'id_usuario', 'usuario_id');
    }

    public function suscripciones()
    {
        return $this->hasMany(Suscripcion::class, 'id_cliente', 'usuario_id');
    }
}