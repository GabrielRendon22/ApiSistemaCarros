<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suscripcion extends Model
{
    protected $table = 'suscripciones';
    protected $primaryKey = 'id_suscripcion';
    public $timestamps = false;

    protected $fillable = ['fecha_inicio', 'fecha_fin', 'fecha_pago', 'id_usuario', 'id_plan', 'id_estado'];

    public function usuarios()
    {
        return $this->belongsTo(usuarios::class, 'id_usuario', 'id_usuario');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'id_plan', 'id_plan');
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'id_estado', 'id_estado');
    }

    public function reservaciones()
    {
        return $this->hasMany(Reservacion::class, 'id_suscripcion', 'id_suscripcion');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'id_suscripcion', 'id_suscripcion');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            \Log::channel('suscripciones')->debug('Intentando crear suscripción', $model->toArray());
        });

        static::created(function($model) {
            \Log::channel('suscripciones')->info('Suscripción creada en BD', [
                'id' => $model->id_suscripcion,
                'datos' => $model->toArray()
            ]);
        });

        static::saving(function($model) {
            \Log::channel('suscripciones')->debug('Validando suscripción antes de guardar', $model->toArray());
        });
    }
}