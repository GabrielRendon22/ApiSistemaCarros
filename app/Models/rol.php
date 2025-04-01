<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'Rol';
    protected $primaryKey = 'rol_id';
    public $timestamps = false;

    protected $fillable = ['nombre_rol'];

    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'id_rol', 'rol_id');
    }
}