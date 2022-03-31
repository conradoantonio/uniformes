<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatusArticulo extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'status_articulo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre', 'descripcion', 'clase'];

    /**
     * Get the articles related to the record
     *
     */
    public function articulos()
    {
        return $this->hasMany('App\Historial', 'status_articulo_id');
    }
}
