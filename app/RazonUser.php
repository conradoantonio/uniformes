<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RazonUser extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'razones_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['razon_social_id', 'user_id'];

    /**
     * Get the company related to the record
     *
     */
    public function razon()
    {
        return $this->belongsTo('App\RazonSocial', 'razon_social_id');
    }

    /**
     * Get the user related to the record
     *
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}

