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
        'tipo_historial_id', 'empleado_id', 'articulo_id', 'color', 'talla', 
        'cantidad', 'status', 'fecha_entrega', 'notas', 'servicio_guardia', 
        'supervisor'
    ];

    /**
     * Get the type related to the record
     *
     */
    public function tipo()
    {
        return $this->belongsTo('App\TipoHistorial', 'tipo_historial_id');
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
        return $this->belongsTo('App\Articulo', 'articulo_id')->withTrashed();
    }

    /**
     * Get the types related to the record
     *
     */
    public function tipos()
    {
        return $this->belongsToMany(TipoHistorial::class, 'historial_tipo', 'historial_id', 'tipo_historial_id')->withPivot('fecha');
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
        ->when($filters['razon_social_id'] ?? null, function($query, $razon_social_id) {
            $query->whereHas('empleado', function($query) use($razon_social_id) {
                $query->where('razon_social_id', $razon_social_id);
            });
        })
        ->when($filters['status_empleado_id'] ?? null, function($query, $status_empleado_id) {
            $query->whereHas('empleado', function($query) use($status_empleado_id) {
                $query->where('status_empleado_id', $status_empleado_id);
            });
        })
        ->when($filters['tipo_historial_id'] ?? null, function($query, $tipo_historial_id) {
            $query->whereHas('tipos', function($query) use($tipo_historial_id) {
                $query->where('tipo_historial_id', $tipo_historial_id);
            });
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
        ->when($filters['fecha_inicio'] ?? null, function($query, $fecha) {
            $query->whereHas('tipos', function($query) use($fecha) {
                $query->where('fecha', '>=', $fecha.' 00:00:00');
            });
        })
        ->when($filters['fecha_fin'] ?? null, function($query, $fecha) {
            $query->whereHas('tipos', function($query) use($fecha) {
                $query->where('fecha', '<=', $fecha.' 00:00:00');
            });
        })
        ->when($filters['limit'] ?? null, function($query, $limit) {
            $query->limit($limit);
        })
        ->orderBy('id', 'desc');
    }
}
