<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservacion extends Model
{
    protected $table = 'Reservacion';
    protected $primaryKey = 'reservacion_id';

    protected $fillable = ['fecha_desde', 'fecha_hasta', 'id_suscripcion', 'id_vehiculo'];

    public function suscripcion()
    {
        return $this->belongsTo(Suscripcion::class, 'id_suscripcion', 'suscripcion_id');
    }

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'id_vehiculo', 'vehiculo_id');
    }
}