<?php

namespace App\Http\Controllers;

use Hash;

use \App\User;
use \App\Modulo;
use \App\Permiso;
use \App\RazonSocial;

use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Show the main view.
     *
     */
    public function index(Request $req)
    {
        $title = "Usuarios";
        $menu = "Usuarios";

        $items = User::where('id', '!=', $this->current_user->id)
        ->get();

        if ( $req->ajax() ) {
            return view('users.table', compact('items'));
        }
        return view('users.index', compact(['items', 'menu', 'title']));
    }

    /**
     * Show the form for creating/editing a resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function form($id = 0)
    {
        $menu = 'Usuarios';
        $title = 'Formulario de usuario';
        $filters = [
            'user' => auth()->user(), 
        ];
        $razones = RazonSocial::filter( $filters )->get();
        $modulos = Modulo::all();
        $permisos = Permiso::all();

        $item = null;
        if ( $id ) {
            $item = User::where('id', $id)->where('id', '!=', $this->current_user->id)->first();
        }
        return view('users.form', compact('item', 'razones', 'modulos', 'permisos', 'menu', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request $req)
    {
        $exist = User::where('email', $req->email)->first();

        if ( $exist ) { return response(['msg' => 'Este correo ya está siendo utilizado por otro usuario, elija uno distinto', 'status' => 'error'], 400); }

        $img = $this->upload_file($req->file('photo'), 'img/users', true);

        $user = new User;
        
        $user->role_id = 1;
        $user->fullname = $req->fullname;
        $user->email = $req->email;
        $user->password = bcrypt($req->password);
        $user->photo = $img ? $img : 'img/users/default.jpg';
        $user->telefono = $req->telefono;

        if ( $user->save() ) {
            $user->permisos()->attach($req->permisos_ids);
            $user->razones()->attach($req->razones_sociales_ids);
            return response(['msg' => 'Usuario creado correctamente', 'status' => 'success', 'url' => url('usuarios'), 200]);
        } else {
            return response(['msg' => 'Usuario no creado, asegúrese que los datos proporcionados sean correctos', 'status' => 'error'], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req)
    {
        $user = User::where('id', $req->id)->where('role_id', 1)->first();

        if (! $user ) { return response(['msg' => 'Usuario no encontrado', 'status' => 'error'], 404); }

        $img = $this->upload_file($req->file('photo'), 'img/users', true);

        $user->fullname = $req->fullname;
        $user->email = $req->email;
        $req->password ? $user->password = bcrypt($req->password) : '';
        $img ? $user->photo = $img : '';
        $user->telefono = $req->telefono;

        // Permisos
        $previous_permission_ids = $user->permisos()->pluck('permiso_id');
        $diff_permissions = array_diff( $previous_permission_ids->toArray(), $req->permisos_ids ?? [] );
        $user->permisos()->sync($req->permisos_ids, false);
        if ( count( $diff_permissions ) ) { $user->permisos()->detach( $diff_permissions ); }

        // Razones sociales
        $razones_ids = $user->razones()->pluck('razon_social_id');
        $diff_razones = array_diff( $razones_ids->toArray(), $req->razones_sociales_ids ?? [] );
        $user->razones()->sync($req->razones_sociales_ids, false);
        if ( count( $diff_razones ) ) { $user->razones()->detach( $diff_razones ); }

        if ( $user->save() ) {
            return response(['msg' => 'Usuario actualizado correctamente', 'status' => 'success', 'url' => url('usuarios'), 200]);
        } else {
            return response(['msg' => 'Error al actualizar el usuario, asegúrese que los datos proporcionados sean correctos', 'status' => 'error'], 400);
        }
    }

    /**
     * Change the status of the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $req)
    {
        $msg = count($req->ids) > 1 ? 'los usuarios selecciondos' : 'el usuario';
        $users = User::whereIn('id', $req->ids)
        ->delete();
        //->update(['status' => $req->status]);

        if ( $users ) {
            $url = $req->reload_url ? : url('usuarios');

            return response(['url' => $url, 'status' => 'success', 'msg' => 'Éxito eliminando '. $msg], 200);
        } else {
            return response(['msg' => 'Error al cambiar el status de '.$msg, 'status' => 'error'], 404);
        }
    }

    /**
     * Change the status of the specified resource.
     *
     */
    public function change_status(Request $req)
    {
        $users = User::whereIn('id', $req->ids)
        ->update(['status' => $req->change_to]);
        //delete();
        if ( $users ) {
            $url = $req->reload_url ? : url('usuarios');

            return response(['url' => $url, 'status' => 'success', 'msg' => 'Éxito cambiando el status del usuario'], 200);
        } else {
            return response(['msg' => 'Usuario no encontrado o inválido', 'status' => 'error'], 404);
        }
    }
}
