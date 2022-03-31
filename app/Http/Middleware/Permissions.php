<?php

namespace App\Http\Middleware;

use Closure;

use \App\PermisoUser;

class Permissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permissions)
    {
        if ( auth()->user()->permisos()->where('permisos.alias', $permissions)->exists() ) {
            return $next($request);
        }
        return response()->view('errors.503', [], 503);
    }
}
