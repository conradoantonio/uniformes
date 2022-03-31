<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'permisos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['modulo_id', 'nombre'];

    /**
     * Get the users related to the record
     *
     */
    public function modulo()
    {
        return $this->belongsTo(Modulo::class, 'modulo_id');
    }
    
    /**
     * Get the users related to the record
     *
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'permiso_user', 'permiso_id', 'user_id');
    }
}
