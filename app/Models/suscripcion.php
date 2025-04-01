<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suscripcion extends Model
{
    protected $table = 'suscripciones';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = ['fecha_inicio', 'fecha_fin', 'fecha_pago', 'id_cliente', 'id_plan', 'id_estado'];

    public function cliente()
    {
        return $this->belongsTo(Usuario::class, 'id', 'id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'id', 'id');
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'id', 'id');
    }

    public function reservaciones()
    {
        return $this->hasMany(Reservacion::class, 'id', 'id');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'id', 'id');
    }
}