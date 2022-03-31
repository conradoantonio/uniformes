<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'modulos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre'];

    /**
     * Get the users related to the record
     *
     */
    public function permisos()
    {
        return $this->hasMany(Permiso::class, 'modulo_id');
    }
}
