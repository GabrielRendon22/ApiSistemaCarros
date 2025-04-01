<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class categoria extends Model
{
    protected $table = 'categorias';
    protected $primaryKey = 'categoria_id';
    public $timestamps = false;

    protected $fillable = ['nombre_categoria', 'descripcion'];

    public function planes()
    {
        return $this->hasMany(Plan::class, 'id_categoria', 'categoria_id');
    }

    public function vehiculos()
    {
        return $this->hasMany(Vehiculo::class, 'id_categoria', 'categoria_id');
    }
}