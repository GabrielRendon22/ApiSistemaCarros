<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id';

    protected $fillable = ['nombres', 'email', 'telefono', 'dui', 'id_rol'];

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id', 'id');
    }

    public function contrasenas()
    {
        return $this->hasMany(Contrasena::class, 'id', 'id');
    }

    public function suscripciones()
    {
        return $this->hasMany(Suscripcion::class, 'id', 'id');
    }
}