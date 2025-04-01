<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    protected $table = 'Vehiculo';
    protected $primaryKey = 'vehiculo_id';

    protected $fillable = ['marca', 'modelo', 'anio', 'placa', 'id_estado', 'id_categoria'];

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'id_estado', 'estado_id');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'categoria_id');
    }

    public function reservaciones()
    {
        return $this->hasMany(Reservacion::class, 'id_vehiculo', 'vehiculo_id');
    }
}