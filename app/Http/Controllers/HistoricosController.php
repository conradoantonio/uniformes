<?php

namespace App\Http\Controllers;

use \App\Talla;
use \App\Articulo;
use \App\Empleado;
use \App\Historial;
use \App\TipoHistorial;
use \App\HistorialTipo;
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

        if (! $empleado ) { return response(['msg' => 'Seleccione un empleado para continuar', 'status' => 'error'], 404); }

        $registros = json_decode($req->historial);

        if ( $registros && count($registros) ) {

            foreach( $registros as $key => $registro ) {

                $tipo     = TipoHistorial::find( $registro->tipo_historial_id );
                $articulo = Articulo::find( $registro->articulo_id );

                if (! $tipo ) { return response(['msg' => 'Tipo de registro no válido', 'status' => 'error'], 404); }
                if (! $articulo ) { return response(['msg' => 'Artículo no válido', 'status' => 'error'], 404); }

                $item = New Historial;

                $item->tipo_historial_id  = $tipo->id;
                $item->empleado_id        = $empleado->id;
                $item->articulo_id        = $articulo->id;
                $item->color              = $registro->color;
                $item->talla              = $registro->talla;
                $item->cantidad           = $registro->cantidad;
                $item->status             = $articulo->status ? $articulo->status->nombre : 'N/A';
                // $item->status_articulo_id = $status->id;
                $item->fecha_entrega      = $registro->fecha_entrega;
                $item->servicio_guardia   = $registro->servicio_guardia;
                $item->supervisor         = $registro->supervisor;
                $item->notas              = $registro->notas;

                $item->save();

                // Se coloca el historial del cartículo
                $pivot = New HistorialTipo;

                $pivot->historial_id      = $item->id;
                $pivot->tipo_historial_id = $tipo->id;
                $pivot->fecha             = $registro->fecha_entrega;

                $pivot->save();
            }
        }

        $url = url("empleados?s=$statusUrl");

        return response(['msg' => 'Registro guardado correctamente', 'url' => $url, 'status' => 'success'], 200);
    }

    /**
     * Insert or update a movement.
     *
     * @return \Illuminate\Http\Response
     */
    public function saveMove(Request $req)
    {
        $historial = Historial::find($req->id);
        if (! $historial ) { return response(['msg' => 'Seleccione un registro válido', 'status' => 'error'], 404); }

        $tipo = TipoHistorial::find($req->tipo_historial_id);
        if (! $tipo ) { return response(['msg' => 'Seleccione un tipo de registro válido', 'status' => 'error'], 404); }

        $move = HistorialTipo::updateOrCreate(
            [ 'historial_id' => $req->id, 'tipo_historial_id' => $tipo->id ],
            [ 'fecha' => $req->fecha  ]
        );

        $historial->tipo_historial_id = $req->tipo_historial_id;
        $historial->fecha_entrega     = $req->fecha;

        $historial->save();

        $data = $historial->load(['tipos', 'tipo']);

        $data->fechaFormateada = strftime('%d', strtotime($data->fecha_entrega)).' de '.strftime('%B', strtotime($data->fecha_entrega)). ' del '.strftime('%Y', strtotime($data->fecha_entrega));

        foreach( $data->tipos as $tipo ) {
            $tipo->fechaFormateada = strftime('%d', strtotime($tipo->pivot->fecha)).' de '.strftime('%B', strtotime($tipo->pivot->fecha)). ' del '.strftime('%Y', strtotime($tipo->pivot->fecha));
        }

        return response(['msg' => 'Registro guardado correctamente', 'status' => 'success', 'data' => $data], 200);
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

            $item->delete();

            return response(['msg' => 'Éxito eliminando el registro', 'status' => 'success'], 200);
        } else {
            return response(['msg' => 'Error al eliminar el registro', 'status' => 'error'], 404);
        }
    }
}
