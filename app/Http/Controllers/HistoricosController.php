<?php

namespace App\Http\Controllers;

use \App\Talla;
use \App\Articulo;
use \App\Empleado;
use \App\Historial;
use \App\TipoHistorial;
use \App\StatusArticulo;

use Illuminate\Http\Request;

class HistoricosController extends Controller
{
    /**
     * Save a resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function save(Request $req)
    {
        $empleado  = Empleado::find($req->empleado_id);
        $statusUrl = $empleado->status->url;

        if (! $empleado ) { return response(['msg' => 'Seleccione un cliente para continuar', 'status' => 'error'], 404); }

        $registros = json_decode($req->historial);

        if ( $registros && count($registros) ) {

            foreach( $registros as $key => $registro ) {

                $tipo     = TipoHistorial::find( $registro->tipo_historial_id );
                $articulo = Articulo::find( $registro->articulo_id );
                $talla    = Talla::find( $registro->talla_id );
                $status   = StatusArticulo::find( $registro->status_articulo_id );

                if (! $tipo ) { return response(['msg' => 'Tipo de registro no válido', 'status' => 'error'], 404); }
                if (! $articulo ) { return response(['msg' => 'Artículo no válido', 'status' => 'error'], 404); }
                if (! $talla ) { return response(['msg' => 'Tipo de talla no válido', 'status' => 'error'], 404); }
                if (! $status ) { return response(['msg' => 'Debe proporcionar un status para el artículo válido', 'status' => 'error'], 404); }

                $item = New Historial;

                $item->tipo_historial_id  = $tipo->id;
                $item->empleado_id        = $empleado->id;
                $item->articulo_id        = $articulo->id;
                $item->talla_id           = $talla->id;
                $item->color              = $registro->color;
                $item->cantidad           = $registro->cantidad;
                $item->status_articulo_id = $status->id;
                $item->fecha_entrega      = $registro->fecha_entrega;
                $item->notas              = $registro->notas;

                $item->save();

            }
        }

        $url = url("empleados?s=$statusUrl");

        return response(['msg' => 'Registro guardado correctamente', 'url' => $url, 'status' => 'success'], 200);
    }

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
