<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contrasena extends Model
{
    protected $table = 'contrasenas';
    protected $primaryKey = 'id';

    protected $fillable = ['contrasena', 'es_activa', 'id_usuario'];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id', 'id');
    }
}