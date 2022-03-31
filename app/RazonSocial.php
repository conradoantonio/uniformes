<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RazonSocial extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'razones_sociales';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre'];

    /**
     * Get the customers related to the record
     *
     */
    public function empleados()
    {
        return $this->hasMany('App\Empleado');
    }

    /**
     * Get the users related to the record
     *
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'razones_user', 'razon_social_id', 'user_id');
    }

    /**
     * Filter rows using current user.
     */
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['user'] ?? null, function($query, $user) {
            $query->whereIn('id', $user->razones->pluck('id'));
        })
        ->orderBy('id', 'desc');
    }
}
