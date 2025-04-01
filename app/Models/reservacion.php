<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservacion extends Model
{
    protected $table = 'reservaciones';
    protected $primaryKey = 'id_reservacion';

    protected $fillable = ['fecha_desde', 'fecha_hasta', 'id_suscripcion', 'id_vehiculo'];

    public function suscripcion()
    {
        return $this->belongsTo(Suscripcion::class, 'id_suscripcion', 'id_suscripcion');
    }

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'id_vehiculo', 'id_vehiculo');
    }
}