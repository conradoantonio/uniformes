<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    use SoftDeletes;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'articulos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre', 'talla', 'color', 'status_articulo_id', 'descripcion'];

    /**
     * Get the status related to the record
     *
     */
    public function status()
    {
        return $this->belongsTo('App\StatusArticulo', 'status_articulo_id');
    }
    
    /**
     * Get the bill related to the record
     *
     */
    public function entregados()
    {
        return $this->hasMany('App\Historial')->where('tipo_recibo_id', 1);
    }

    /**
     * Get the bill related to the record
     *
     */
    public function recibidos()
    {
        return $this->hasMany('App\Historial')->where('tipo_recibo_id', 2);
    }
}
