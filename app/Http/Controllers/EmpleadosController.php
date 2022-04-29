<?php

namespace App\Http\Controllers;

use Excel;

use \App\Talla;
use \App\Empleado;
use \App\Articulo;
use \App\Historial;
use \App\RazonSocial;
use \App\HistorialTipo;
use \App\StatusArticulo;
use \App\StatusEmpleado;

use Illuminate\Http\Request;

class EmpleadosController extends Controller
{
    /**
     * Show the main view.
     *
     */
    public function index(Request $req)
    {
        $st = $req->s;
        $status = StatusEmpleado::where('url', $st)->first();
        if (! $status ) { return view('errors.404'); }

        $menu  = "Empleados";
        $title = "Empleados $status->url";

        $filters = [
            'status_empleado_id' => $status->id, 
            'user'               => auth()->user(), 
            'limit'              => 100,
        ];
        $items = Empleado::filter( $filters )->get();
        $razones = RazonSocial::filter( $filters )->get();
        $statusEmpleado = StatusEmpleado::whereNotIn('id', [$status->id])->get();

        if ( $req->ajax() ) {
            return view('empleados.table', compact('items'));
        }
        return view('empleados.index', compact('items', 'razones', 'status', 'statusEmpleado', 'menu', 'title'));
    }

    /**
     * Filter user franchise acording to the filters given by user.
     *
     */
    public function filter(Request $req)
    {
        $req->request->add([ 'user' => auth()->user() ]);

        $items = Empleado::filter( $req->all() )->orderBy('id', 'desc')->get();

        if ( $req->only_data ) {
            return response(['msg' => 'Empleados enlistados a continuación', 'status' => 'success', 'data' => $items, 'total' => count($items)], 200);
        }

        return view('empleados.table', compact(['items']));
    }
    
    /**
     * Show the form for creating/editing a user franchise.
     *
     * @return \Illuminate\Http\Response
     */
    public function form($id = 0)
    {
        $title = "Formulario de empleado";
        $menu = "Empleados";
        $item = null;
        $filters = [
            'user' => auth()->user(), 
        ];
        
        $razones = RazonSocial::filter( $filters )->get();

        if ( $id ) {
            $item = Empleado::filter( $filters )->where('id', $id)->first();
        }
        return view('empleados.form', compact(['item', 'razones', 'menu', 'title']));
    }

    /**
     * Show the form for creating/editing a user franchise.
     *
     * @return \Illuminate\Http\Response
     */
    public function formUniformes($id = 0)
    {
        $title = "Formulario de asignación de uniformes";
        $menu = "Empleados";
        $empleado = null;
        $filters = [
            'user' => auth()->user(), 
        ];
        
        $tallas = Talla::all();
        $articulos = Articulo::all();
        $status = StatusArticulo::all();

        if ( $id ) {
            $empleado = Empleado::filter( $filters )->where('id', $id)->first();
        }
        return view('empleados.formArticulos', compact(['empleado', 'articulos', 'tallas', 'status', 'menu', 'title']));
    }

    /**
     * Shows historic
     *
     * @return \Illuminate\Http\Response
     */
    public function verHistorico(Request $req)
    {
        $items = Historial::with(['tipos', 'articulo'])
        ->filter( $req->all() )
        ->get();
        // ->where('id', $req->empleado_id)

        foreach ($items as $item) {
            foreach($item->tipos as $tipo) {
                $tipo->fechaFormateada = strftime('%d', strtotime($tipo->pivot->fecha)).' de '.strftime('%B', strtotime($tipo->pivot->fecha)). ' del '.strftime('%Y', strtotime($tipo->pivot->fecha));
            }
        }

        return response(['msg' => 'Información de historial enlistada a continuación', 'status' => 'success', 'data' => $items], 200);
    }

    /**
     * Save a resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function save(Request $req)
    {
        $razonSocial = RazonSocial::find($req->razon_social_id);

        if (! $razonSocial ) { return response(['msg' => 'Seleccione una razón social para crear al empleado', 'status' => 'error'], 404); }

        $item = New Empleado;

        $item->nombre = $req->nombre;
        $item->razon_social_id = $razonSocial->id;
        $item->status_empleado_id = 1;
        $item->numero_empleado = $req->numero_empleado;
        $item->fecha_ingreso = $req->fecha_ingreso;
        $item->fecha_baja = $req->fecha_baja;
        $item->observaciones = $req->observaciones;

        $item->save();

        return response(['msg' => 'Registro guardado correctamente', 'url' => url('empleados?s=activos'), 'status' => 'success'], 200);
    }

    /**
     * Edit a resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req)
    {
        $item = Empleado::find($req->id);

        if (! $item ) { return response(['msg' => 'No se encontró el registro a editar', 'status' => 'error'], 404); }

        $razonSocial = RazonSocial::find($req->razon_social_id);

        if (! $razonSocial ) { return response(['msg' => 'Seleccione una razón social para crear al empleado', 'status' => 'error'], 404); }

        $item->nombre = $req->nombre;
        $item->razon_social_id = $razonSocial->id;
        $item->numero_empleado = $req->numero_empleado;
        $item->fecha_ingreso = $req->fecha_ingreso;
        $item->fecha_baja = $req->fecha_baja;
        $item->observaciones = $req->observaciones;

        $item->save();

        $url = url('empleados?s=activos');
        
        if( $item->status_empleado_id == 1 ) { $url = url('empleados?s=activos'); }
        elseif( $item->status_empleado_id == 2 ) { $url = url('empleados?s=inactivos'); }
        elseif( $item->status_empleado_id == 3 ) { $url = url('empleados?s=pendientes'); }

        return response(['msg' => 'Registro actualizado correctamente', 'url' => $url, 'status' => 'success'], 200);
    }

    /**
     * Change the status of the specified resource.
     *
     */
    public function changeStatus(Request $req)
    {
        $user = Empleado::filter([ 'user' => auth()->user(), 'empleado_id' => $req->empleado_id ])->first();
        if (! $user ) { return response(['msg' => 'Empleado inválido', 'status' => 'error'], 404); }

        $status = StatusEmpleado::where('id', $req->status_empleado_id)->first();
        if (! $status ) { return response(['msg' => 'Seleccione un status válido para continuar', 'status' => 'error'], 404); }

        if( $user->status_empleado_id == 1 ) { $url = url('empleados/reload?s=activos'); }
        elseif( $user->status_empleado_id == 2 ) { $url = url('empleados/reload?s=inactivos'); }
        elseif( $user->status_empleado_id == 3 ) { $url = url('empleados/reload?s=pendientes'); }

        $user->status_empleado_id = $status->id;

        $user->save();
        
        return response(['url' => $url, 'status' => 'success', 'msg' => 'Éxito cambiando el status del empleado'], 200);
    }

    /**
     * Change the status of the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $req)
    {
        $msg = count($req->ids) > 1 ? 'los registros' : 'el registro';
        $item = Empleado::whereIn('id', $req->ids)
        ->first();

        if ( $item ) {
            $item->delete();

            $url = url('empleados?s=activos');
        
            if( $item->status_empleado_id == 1 ) { $url = url('empleados?s=activos'); }
            elseif( $item->status_empleado_id == 2 ) { $url = url('empleados?s=inactivos'); }
            elseif( $item->status_empleado_id == 3 ) { $url = url('empleados?s=pendientes'); }

            return response(['msg' => 'Éxito eliminando '.$msg, 'url' => $url, 'status' => 'success'], 200);
        } else {
            return response(['msg' => 'Error al cambiar el status de '.$msg, 'status' => 'error'], 404);
        }
    }

    /**
     * Use Excel instance to export all bills with his balance at once.
     *
     * @return \Illuminate\Http\Response
     */
    public function generarHistorico(Request $req)
    {
        $req->request->add([ 'user' => auth()->user() ]);

        $rows = array();
        $empleado = Empleado::find($req->empleado_id);
        // dd($req->all());
        $items = Historial::filter( $req->all() )->get();
        // dd($items);
        $totalEntregados = $totalRecibidos = 0;
        $fechaInicioFormateada = 'N/A';
        $fechaFinFormateada = 'N/A'; 
        $movimientos = '';

        if ( $req->fecha_inicio ) {
            $fechaInicioFormateada = strftime('%d', strtotime($req->fecha_inicio)).' de '.strftime('%B', strtotime($req->fecha_inicio)). ' del '.strftime('%Y', strtotime($req->fecha_inicio));
        }

        if ( $req->fecha_fin ) {
            $fechaFinFormateada = strftime('%d', strtotime($req->fecha_fin)).' de '.strftime('%B', strtotime($req->fecha_fin)). ' del '.strftime('%Y', strtotime($req->fecha_fin));
        }

        $periodo = $fechaInicioFormateada.' - '.$fechaFinFormateada;

        foreach ( $items as $item ) {

            $fechaEntrega = $fechaFormateada = '';

            $entregados = HistorialTipo::where('historial_id', $item->id)->whereIn('tipo_historial_id', [1,2])->count();
            $recibidos  = HistorialTipo::where('historial_id', $item->id)->whereIn('tipo_historial_id', [3])->count();

            if ( $entregados > 0 ) { $totalEntregados += $item->cantidad; }
            if ( $recibidos > 0 ) { $totalRecibidos += $item->cantidad; }

            foreach( $item->tipos as $move ) {
                $fechaEntrega = $move->pivot->fecha;
                $fechaFormateada = strftime('%d', strtotime($fechaEntrega)).' de '.strftime('%B', strtotime($fechaEntrega)). ' del '.strftime('%Y', strtotime($fechaEntrega));
                $movimientos .= ( $move->nombre.' - '.$fechaFormateada.' / ' );
            }
            

            $rows [] = [
                'Empleado'          => $empleado->nombre,
                'No. empleado'      => $empleado->numero_empleado,
                'Artículo'          => $item->articulo->nombre,
                'Status artículo'   => $item->status ? $item->status : 'N/A',
                'Talla'             => $item->talla ? $item->talla : 'N/A',
                'Color'             => $item->color ?? 'N/A',
                'Cantidad'          => $item->cantidad ?? 'N/A',
                'Movimientos'       => $movimientos ?? 'N/A',
                'Servicio guardia'  => $item->servicio_guardia ?? 'N/A',
                'Supervisor'        => $item->supervisor ?? 'N/A',
                'Notas adicionales' => $item->notas,
            ];
        }

        Excel::create('Histórico', function($excel) use ($rows, $totalEntregados, $totalRecibidos, $empleado, $periodo) {
            $excel->sheet('Hoja 1', function($sheet) use($rows, $totalEntregados, $totalRecibidos, $empleado, $periodo) {

                $sheet->cell('A1', function($cell) use ($empleado) {
                    if ( $empleado ) {
                        $cell->setFontWeight('bold');
                        $cell->setFontSize(12);
                        $cell->setValue('Empleado: '.$empleado->nombre);
                    }
                });

                $sheet->cell('A2', function($cell) use($periodo) {
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(12);
                    $cell->setValue('Periodo: '.$periodo);
                });

                $sheet->cell('A3', function($cell) use ($totalEntregados) {
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(12);
                    $cell->setValue('Artículos entregados: #'.number_format( $totalEntregados, 0));
                });

                $sheet->cell('A4', function($cell) use ($totalRecibidos) {
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(12);
                    $cell->setValue('Artículos recibidos: #'.number_format( $totalRecibidos, 0));
                });

                $sheet->cells('A:K', function($cells) {
                    $cells->setAlignment('left');
                    $cells->setValignment('center');
                });

                $sheet->cells('A6:K6', function($cells) {
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                    $cells->setFontWeight('bold');
                    $cells->setFontSize(12);
                });

                $sheet->fromArray($rows, null, 'A6', true);
            });
        })->export('xlsx');
    }
}
