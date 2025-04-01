<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    protected $table = 'estados';
    protected $primaryKey = 'id_estado';
    public $timestamps = false;

    protected $fillable = ['descripcion', 'es_suscripcion'];

    public function suscripciones()
    {
        return $this->hasMany(Suscripcion::class, 'id_estado', 'id_estado');
    }

    public function vehiculos()
    {
        return $this->hasMany(Vehiculo::class, 'id_estado', 'id_estado');
    }
}