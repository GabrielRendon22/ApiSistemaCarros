<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $table = 'Plan';
    protected $primaryKey = 'id_plan';
    public $timestamps = false;

    protected $fillable = ['nombre_plan', 'descripcion', 'precio_mensual', 'limite_km', 'id_categoria'];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'categoria_id');
    }

    public function suscripciones()
    {
        return $this->hasMany(Suscripcion::class, 'id_plan', 'id_plan');
    }
}