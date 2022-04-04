<?php

namespace App\Http\Controllers;

use \App\Empleado;
use \App\Historial;

use Illuminate\Http\Request;

class HistoricosController extends Controller
{
    /**
     * Change the status of the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $req)
    {
        $item = Historial::find($req->id);

        if ( $item ) {

            // $item->delete();

            return response(['msg' => 'Éxito eliminando el registro', 'status' => 'success'], 200);
        } else {
            return response(['msg' => 'Error al eliminar el registro', 'status' => 'error'], 404);
        }
    }
}
