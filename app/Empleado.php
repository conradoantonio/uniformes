<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'empleados';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'razon_social_id', 'status_empleado_id', 'nombre', 'numero_empleado', 
        'ife', 'domicilio', 'fecha_ingreso', 'fecha_baja', 
    ];

    /**
     * Get the company related to the record
     *
     */
    public function razon_social()
    {
        return $this->belongsTo('App\RazonSocial');
    }

    /**
     * Get status related to the record
     *
     */
    public function status()
    {
        return $this->belongsTo('App\StatusCliente', 'status_empleado_id');
    }

    /**
     * Get the articles given related to the record
     *
     */
    public function uniformes_entregados()
    {
        return $this->hasMany('App\Historial')->where('tipo_recibo_id', 1);
    }

    /**
     * Get the articles given related to the record
     *
     */
    public function uniformes_recibidos()
    {
        return $this->hasMany('App\Historial')->where('tipo_recibo_id', 2);
    }

    /**
     * Filter rows using current user.
     */
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['user'] ?? null, function($query, $user) {
            $query->whereIn('razon_social_id', $user->razones->pluck('id'));
        })
        ->when($filters['razon_social_id'] ?? null, function($query, $razon_social_id) {
            $query->where('razon_social_id', $razon_social_id);
        })
        ->when($filters['status_cliente_id'] ?? null, function($query, $status_cliente_id) {
            $query->where('status_cliente_id', $status_cliente_id);
        })
        ->when($filters['empleado_id'] ?? null, function($query, $empleado_id) {
            $query->where('id', $empleado_id);
        })
        ->when($filters['num_empleado'] ?? null, function($query, $num_empleado) {
            $query->where('numero_empleado', $num_empleado);
        })
        ->when($filters['nombre'] ?? null, function($query, $nombre) {
            $query->where('nombre', 'like', '%'.$nombre.'%');
        })
        ->when($filters['fecha_inicio'] ?? null, function($query, $fecha_inicio) {
            $query->where('created_at', '>=', $fecha_inicio.' 00:00:00');
        })
        ->when($filters['fecha_fin'] ?? null, function($query, $fecha_fin) {
            $query->where('created_at', '<=', $fecha_fin.' 23:59:59');
        })
        ->when($filters['limit'] ?? null, function($query, $limit) {
            $query->limit($limit);
        })
        ->orderBy('id', 'desc');
    }
}
