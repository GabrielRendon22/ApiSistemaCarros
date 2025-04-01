<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suscripcion extends Model
{
    protected $table = 'Suscripcion';
    protected $primaryKey = 'suscripcion_id';
    public $timestamps = false;

    protected $fillable = ['fecha_inicio', 'fecha_fin', 'fecha_pago', 'id_cliente', 'id_plan', 'id_estado'];

    public function cliente()
    {
        return $this->belongsTo(Usuario::class, 'id_cliente', 'usuario_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'id_plan', 'id_plan');
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'id_estado', 'estado_id');
    }

    public function reservaciones()
    {
        return $this->hasMany(Reservacion::class, 'id_suscripcion', 'suscripcion_id');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'id_suscripcion', 'suscripcion_id');
    }
}