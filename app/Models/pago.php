<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pagos';
    protected $primaryKey = 'id_pago';

    protected $fillable = ['id_suscripcion'];

    public function suscripcion()
    {
        return $this->belongsTo(Suscripcion::class, 'id_suscripcion', 'id_suscripcion');
    }
}