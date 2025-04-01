<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    protected $table = 'Estado';
    protected $primaryKey = 'estado_id';
    public $timestamps = false;

    protected $fillable = ['descripcion', 'es_suscripcion'];

    public function suscripciones()
    {
        return $this->hasMany(Suscripcion::class, 'id_estado', 'estado_id');
    }

    public function vehiculos()
    {
        return $this->hasMany(Vehiculo::class, 'id_estado', 'estado_id');
    }
}