<?php

namespace App\Http\Controllers;

use Excel;

use \App\Empleado;
use \App\Articulo;
use \App\Historial;
use \App\RazonSocial;
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
        $item->numero_empleado = $req->numero_empleado;
        $item->ine = $req->ine;
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
        $item->ine = $req->ine;
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
        $items = Empleado::whereIn('id', $req->ids)
        ->delete();

        if ( $items ) {
            return response(['msg' => 'Éxito eliminando '.$msg, 'url' => url('empleados'), 'status' => 'success'], 200);
        } else {
            return response(['msg' => 'Error al cambiar el status de '.$msg, 'status' => 'error', 'url' => url('empleados')], 404);
        }
    }

    /**
     * Use Excel instance to export all bills with his balance at once.
     *
     * @return \Illuminate\Http\Response
     */
    public function generarHistorico(Request $req)
    {
        $rows = array();
        $req->request->add([ 'user' => auth()->user(), 'pagada' => 0, 'no_canceladas' => true ]);
        $Articulos = Articulo::filter( $req->all() )->orderBy('fecha_Articulocion', 'desc')->get();

        $totalArticulos = $totalPagos = 0;
        $fechaInicioFormateada = 'N/A';
        $fechaFinFormateada = 'N/A'; 

        if ( $req->fecha_inicio ) {
            $fechaInicioFormateada = strftime('%d', strtotime($req->fecha_inicio)).' de '.strftime('%B', strtotime($req->fecha_inicio)). ' del '.strftime('%Y', strtotime($req->fecha_inicio));
        }

        if ( $req->fecha_fin ) {
            $fechaFinFormateada = strftime('%d', strtotime($req->fecha_fin)).' de '.strftime('%B', strtotime($req->fecha_fin)). ' del '.strftime('%Y', strtotime($req->fecha_fin));
        }

        $periodo = $fechaInicioFormateada.' - '.$fechaFinFormateada;

        foreach ( $Articulos as $Articulo ) {
            $fechaFormateada = 'N/A';
            $pagos = 0;

            $pagos = Recibo::where('Articulo_id', $Articulo->id)->sum('importe');
            $totalArticulos += $Articulo->importe;
            $totalPagos += $pagos;
            $fechaFormateada = $Articulo->fecha_Articulocion ? strftime('%d', strtotime($Articulo->fecha_Articulocion)).' de '.strftime('%B', strtotime($Articulo->fecha_Articulocion)). ' del '.strftime('%Y', strtotime($Articulo->fecha_Articulocion)) : '';

            $rows [] = [
                'Empleado'             => $Articulo->cliente->nombre.' ('.$Articulo->cliente->razon_social->nombre.')',
                'Número / Folio'       => $Articulo->numero_Articulo,
                'Importe'              => $Articulo->importe,
                'Pagos adjuntados'     => $pagos,
                'Balance'              => $Articulo->importe - $pagos,
                'Fecha de Articuloción' => $fechaFormateada,
                'Comentario'           => $Articulo->comentarios_adicionales,
            ];
        }

        Excel::create('Listado de Articulos', function($excel) use ($rows, $totalArticulos, $totalPagos, $periodo) {
            $excel->sheet('Hoja 1', function($sheet) use($rows, $totalArticulos, $totalPagos, $periodo) {

                // $sheet->cell('A1', function($cell) use ($cliente) {
                //     if ( $cliente ) {
                //         $cell->setFontWeight('bold');
                //         $cell->setFontSize(12);
                //         $cell->setValue('Razón social: '.$cliente->razon_social->nombre);
                //     }
                // });

                $sheet->cell('A1', function($cell) use($periodo) {
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(12);
                    $cell->setValue('Periodo: '.$periodo);
                });

                $sheet->cell('A2', function($cell) use ($totalArticulos, $totalPagos) {
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(12);
                    $cell->setValue('Balance: $'.number_format( $totalArticulos - $totalPagos, 2));
                });

                $sheet->cells('A:G', function($cells) {
                    $cells->setAlignment('left');
                    $cells->setValignment('center');
                });

                $sheet->cells('A5:G5', function($cells) {
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                    $cells->setFontWeight('bold');
                    $cells->setFontSize(12);
                });

                $sheet->fromArray($rows, null, 'A5', true);
                // $sheet->setAutoFilter('A5:E5');
            });
        })->export('xlsx');
    }
}
