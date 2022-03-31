<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PermisoUser extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'permiso_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['permiso_id', 'user_id'];

    /**
     * Get the permission of the model.
     *
     */
    public function permiso()
    {
        return $this->belongsTo('App\Permiso', 'permiso_id');
    }

    /**
     * Get the user of the model.
     *
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
