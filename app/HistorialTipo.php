<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistorialTipo extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'historial_tipo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['historial_id', 'tipo_historial_id', 'fecha'];

    /**
     * Get the articles related to the record
     *
     */
    public function historial()
    {
        return $this->belongsTo('App\Historial', 'historial_id');
    }

    /**
     * Get the articles related to the record
     *
     */
    public function tipo()
    {
        return $this->belongsTo('App\TipoHistorial', 'tipo_historial_id');
    }
}
