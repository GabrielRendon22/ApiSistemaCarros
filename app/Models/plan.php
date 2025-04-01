<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $table = 'planes';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = ['nombre_plan', 'descripcion', 'precio_mensual', 'limite_km', 'id'];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id', 'id');
    }

    public function suscripciones()
    {
        return $this->hasMany(Suscripcion::class, 'id', 'id');
    }
}