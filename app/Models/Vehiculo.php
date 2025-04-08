<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    protected $table = 'vehiculos';
    protected $primaryKey = 'id_vehiculo';

    protected $fillable = ['marca', 'modelo', 'anio', 'placa', 'id_estado', 'id_categoria', 'foto']; // Agregar 'foto'

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'id_estado', 'id_estado');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria');
    }

    public function reservaciones()
    {
        return $this->hasMany(Reservacion::class, 'id_vehiculo', 'id_vehiculo');
    }
}