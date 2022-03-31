<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
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
    protected $fillable = ['nombre', 'descripcion'];

    /**
     * Get the bill related to the record
     *
     */
    public function factura()
    {
        return $this->belongsTo('App\Factura');
    }
}
