<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Historial extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'historial';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tipo_recibo_id', 'empleado_id', 'articulo_id', 'status_articulo_id', 
        'talla_id', 'color', 'cantidad', 'fecha_entrega', 'notas'
    ];

    /**
     * Get the type related to the record
     *
     */
    public function tipo()
    {
        return $this->belongsTo('App\TipoRecibo', 'tipo_recibo_id');
    }

    /**
     * Get the employee related to the record
     *
     */
    public function empleado()
    {
        return $this->belongsTo('App\Empleado', 'empleado_id');
    }

    /**
     * Get the article related to the record
     *
     */
    public function articulo()
    {
        return $this->belongsTo('App\Empleado', 'articulo_id');
    }

    /**
     * Get the status related to the record
     *
     */
    public function status()
    {
        return $this->belongsTo('App\StatusArticulo', 'status_articulo_id');
    }

    /**
     * Get the size related to the record
     *
     */
    public function talla()
    {
        return $this->belongsTo('App\Talla', 'talla_id');
    }

    /**
     * Filter rows using current user.
     */
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['user'] ?? null, function($query, $user) use($filters) {
            $query->whereHas('empleado', function($query) use($user) {
                $query->whereIn('razon_social_id', $user->razones->pluck('id'));
            });
        })
        ->when($filters['tipo_id'] ?? null, function($query, $tipo_id) {
            $query->where('tipo_id', $tipo_id);
        })
        ->when($filters['empleado_id'] ?? null, function($query, $empleado_id) {
            $query->where('empleado_id', $empleado_id);
        })
        ->when($filters['articulo_id'] ?? null, function($query, $articulo_id) {
            $query->where('articulo_id', $articulo_id);
        })
        ->when($filters['status_id'] ?? null, function($query, $status_id) {
            $query->where('status_id', $status_id);
        })
        ->when($filters['talla_id'] ?? null, function($query, $talla_id) {
            $query->where('talla_id', $talla_id);
        })
        ->when($filters['fecha_inicio'] ?? null, function($query, $fecha_inicio) {
            $query->where('fecha_entrega', '>=', $fecha_inicio.' 00:00:00');
        })
        ->when($filters['fecha_fin'] ?? null, function($query, $fecha_fin) {
            $query->where('fecha_entrega', '<=', $fecha_fin.' 23:59:59');
        })
        ->when($filters['limit'] ?? null, function($query, $limit) {
            $query->limit($limit);
        })
        ->orderBy('id', 'desc');
    }
}