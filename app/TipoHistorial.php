<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoHistorial extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tipo_historial';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre', 'descripcion', 'clase'];

    /**
     * Get the bills related to the record
     *
     */
    public function entregas()
    {
        return $this->hasMany('App\Historial')->where('tipo_recibo_id', 1);
    }

    /**
     * Get the bills related to the record
     *
     */
    public function recibos()
    {
        return $this->hasMany('App\Historial')->where('tipo_recibo_id', 2);
    }

    /**
     * Get the types related to the record
     *
     */
    public function historiales()
    {
        return $this->belongsToMany(Historial::class, 'historial_tipo', 'tipo_historial_id', 'historial_id');
    }
}
