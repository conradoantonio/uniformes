<?php

namespace App;

use DB;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_id', 'fullname', 'email', 'password', 'photo', 
        'telefono', 'remember_token', 'status', 
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the rol of the model.
     *
     */
    public function role()
    {
        return $this->belongsTo('App\Role');
    }

    /**
     * Get the permissions related to the record
     *
     */
    public function permisos()
    {
        return $this->belongsToMany(Permiso::class, 'permiso_user', 'user_id', 'permiso_id');
    }

    /**
     * Get the companies related to the record
     *
     */
    public function razones()
    {
        return $this->belongsToMany(RazonSocial::class, 'razones_user', 'user_id', 'razon_social_id');
    }

    /**
     * Check the permission of the current user.
     *
     */
    public function checkPermission($permissions)
    {
        foreach ( $permissions as $permission ) {

            if ( $this->permisos()->where('permisos.nombre', $permission)->exists() ) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check the role of the current user.
     *
     */
    public function checkRole($roles)
    {
        foreach ( $roles as $role ) {
            if ( $this->role->descripcion == $role ) {
                return true;
            }
        }
        return false;
    }

    /**
     * Search an user by his id.
     */
    public static function user_by_id($id)
    {
        return User::where('id', $id)->first();
    }

    /**
     * Filter rows using current user.
     */
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['user'] ?? null, function($query, $user) {
            $query->whereHas('razones', function($query) use($user) {
                $query->whereIn('id', $user->razones->pluck('id'));
            });
        })
        ->when($filters['razon_social_id'] ?? null, function($query, $razon_social_id) {
            $query->where('razon_social_id', $razon_social_id);
        })
        ->when($filters['status_cliente_id'] ?? null, function($query, $status_cliente_id) {
            $query->where('status_cliente_id', $status_cliente_id);
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

    /**
     * Get the users filtered by the given values.
     */
    static function getTopTen($roles = [], $status = null, $verify_player_id = null)
    {
        $ids = Pedido::select(DB::raw('*, SUM(total) AS "total_paid"' ))->groupBy('id_users')->orderBy('total_paid', 'desc')->limit(10)->pluck('id_users');
        
        $rows = User::whereIn('id', $ids);

        if ( count($roles) ) {
            $rows->whereIn('role_id', $roles);
        }

        /*if ( $status !== null ) {
            $rows = $rows->where('status', $status);
        }*/

        if ( $status !== null ) {
            $rows = $rows->withTrashed();
        }

        if ( $verify_player_id !== null ) {
            $rows = $rows->whereNotNull('player_id');
        }
        $rows = $rows->withTrashed();
        
        return $rows->get();
    }
}
