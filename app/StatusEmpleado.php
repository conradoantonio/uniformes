<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatusEmpleado extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'status_empleado';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre', 'url', 'descripcion', 'clase'];

    /**
     * Get the employees related to the record
     *
     */
    public function empleados()
    {
        return $this->hasMany('App\Empleado', 'status_empleado_id');
    }
}
