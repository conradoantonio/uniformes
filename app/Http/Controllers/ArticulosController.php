<?php

namespace App\Http\Controllers;

use \App\Articulo;
use \App\StatusArticulo;

use Illuminate\Http\Request;

class ArticulosController extends Controller
{
    /**
     * Show the main view.
     *
     */
    public function index(Request $req)
    {
        $menu = $title = "Artículos";

        $items = Articulo::all();

        if ( $req->ajax() ) {
            return view('articulos.table', compact('items'));
        }
        return view('articulos.index', compact('items', 'menu', 'title'));
    }
    
    /**
     * Show the form for creating/editing a user franchise.
     *
     * @return \Illuminate\Http\Response
     */
    public function form($id = 0)
    {
        $title = "Formulario de artículo";
        $menu = "Artículos";
        $item = null;
        $status = StatusArticulo::all();
        
        if ( $id ) {
            $item = Articulo::where('id', $id)->first();
        }
        return view('articulos.form', compact(['item', 'menu', 'status', 'title']));
    }

    /**
     * Save a resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function save(Request $req)
    {
        $status = StatusArticulo::find( $req->status_articulo_id );

        if (! $status ) { return response(['msg' => 'Seleccione un status válido', 'status' => 'error'], 404); }

        $item = New Articulo;

        $item->nombre = $req->nombre;
        $item->talla = $req->talla;
        $item->color = $req->color;
        $item->status_articulo_id = $status->id;
        $item->descripcion = $req->descripcion;

        $item->save();

        return response(['msg' => 'Registro guardado correctamente', 'url' => url('articulos'), 'status' => 'success'], 200);
    }

    /**
     * Edit a resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req)
    {
        $status = StatusArticulo::find( $req->status_articulo_id );

        if (! $status ) { return response(['msg' => 'Seleccione un status válido', 'status' => 'error'], 404); }
        
        $item = Articulo::find($req->id);

        if (! $item ) { return response(['msg' => 'No se encontró el registro a editar', 'status' => 'error'], 404); }

        $item->nombre = $req->nombre;
        $item->talla = $req->talla;
        $item->color = $req->color;
        $item->status_articulo_id = $status->id;
        $item->descripcion = $req->descripcion;

        $item->save();

        return response(['msg' => 'Registro actualizado correctamente', 'url' => url('articulos'), 'status' => 'success'], 200);
    }

    /**
     * Change the status of the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $req)
    {
        $msg = count($req->ids) > 1 ? 'los registros' : 'el registro';
        $items = Articulo::whereIn('id', $req->ids)
        ->delete();

        if ( $items ) {
            return response(['msg' => 'Éxito eliminando '.$msg, 'url' => url('articulos'), 'status' => 'success'], 200);
        } else {
            return response(['msg' => 'Error al cambiar el status de '.$msg, 'status' => 'error', 'url' => url('articulos')], 404);
        }
    }
}
