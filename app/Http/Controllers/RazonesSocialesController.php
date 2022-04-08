<?php

namespace App\Http\Controllers;

use Excel;

use \App\Recibo;
use \App\Empleado;
use \App\Historial;
use \App\RazonSocial;
use \App\StatusEmpleado;

use Illuminate\Http\Request;

class RazonesSocialesController extends Controller
{
    /**
     * Show the main view.
     *
     */
    public function index(Request $req)
    {
        $title = $menu = "Razones sociales";

        $filters = [
            'user' => auth()->user(), 
        ];
        $items = RazonSocial::filter( $filters )->get();
        $clientes = Empleado::filter( $filters )->get();
        $statusEmpleado = StatusEmpleado::all();

        if ( $req->ajax() ) {
            return view('razones_sociales.table', compact('items'));
        }
        return view('razones_sociales.index', compact('items', 'clientes', 'statusEmpleado', 'menu', 'title'));
    }
    
    /**
     * Show the form for creating/editing a user franchise.
     *
     * @return \Illuminate\Http\Response
     */
    public function form($id = 0)
    {
        $title = "Formulario de razón social";
        $menu = "Razones sociales";
        $item = null;
        $filters = [
            'user' => auth()->user(), 
        ];

        if ( $id ) {
            $item = RazonSocial::filter( $filters )->where('id', $id)->first();
        }
        return view('razones_sociales.form', compact(['item', 'menu', 'title']));
    }

    /**
     * Save a resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function save(Request $req)
    {
        $item = New RazonSocial;

        $item->nombre = $req->nombre;

        $item->save();

        return response(['msg' => 'Registro guardado correctamente', 'url' => url('razones-sociales'), 'status' => 'success'], 200);
    }

    /**
     * Edit a resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req)
    {
        $item = RazonSocial::find($req->id);

        if (! $item ) { return response(['msg' => 'No se encontró el registro a editar', 'status' => 'error'], 404); }

        $item->nombre = $req->nombre;

        $item->save();

        return response(['msg' => 'Registro actualizado correctamente', 'url' => url('razones-sociales'), 'status' => 'success'], 200);
    }

    /**
     * Change the status of the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $req)
    {
        $msg = count($req->ids) > 1 ? 'los registros' : 'el registro';
        $items = RazonSocial::whereIn('id', $req->ids)
        ->delete();

        if ( $items ) {
            return response(['msg' => 'Éxito eliminando '.$msg, 'url' => url('razones-sociales'), 'status' => 'success'], 200);
        } else {
            return response(['msg' => 'Error al cambiar el status de '.$msg, 'status' => 'error', 'url' => url('razones-sociales')], 404);
        }
    }

    /**
     * Use Excel instance to export all items at once.
     *
     * @return \Illuminate\Http\Response
     */
    public function export(Request $req)
    {
        $rows = $empleados = array();
        $req->request->add([ 'user' => auth()->user() ]);
        $razones = RazonSocial::filter( $req->all() )->orderBy('id', 'desc')->get();
        $totalEntregadosGlobal = $totalRecibidosGlobal = 0;

        foreach ( $razones as $razon ) {
            $totalEntregados = $totalRecibidos = 0;
            
            if( $req->status_empleado_id ) {
                $empleados = $razon->empleados->where('status_empleado_id', $req->status_empleado_id);
            } else {
                $empleados = $razon->empleados;
            }

            // Suma la cantidad de uniformes entregados y recibidos por razón social
            foreach( $empleados as $empleado ) {
                $totalEntregados += $empleado->uniformes_entregados->sum('cantidad');
                $totalRecibidos  += $empleado->uniformes_recibidos->sum('cantidad');
            }

            $rows [] = [
                'Razón social'           => $razon->nombre,
                'Artículos entregados'   => $totalEntregados,
                'Artículos devueltos'    => $totalRecibidos,
                'Artículos por devolver' => $totalEntregados - $totalRecibidos,
            ];

            $totalEntregadosGlobal += $totalEntregados;
            $totalRecibidosGlobal  += $totalRecibidos;
        }

        Excel::create('Histórico por razón social', function($excel) use ($rows, $totalEntregadosGlobal, $totalRecibidosGlobal) {
            $excel->sheet('Hoja 1', function($sheet) use($rows, $totalEntregadosGlobal, $totalRecibidosGlobal) {

                $sheet->cell('A1', function($cell) use ($totalEntregadosGlobal) {
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(12);
                    $cell->setValue('Total de uniformes entregados al empleado: '.number_format( $totalEntregadosGlobal, 0));
                });

                $sheet->cell('A2', function($cell) use ($totalRecibidosGlobal) {
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(12);
                    $cell->setValue('Total de uniformes devueltos por empleado: '.number_format( $totalRecibidosGlobal, 0));
                });

                $sheet->cell('A3', function($cell) use ($totalEntregadosGlobal, $totalRecibidosGlobal) {
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(12);
                    $cell->setValue('Artículos por devolver: '.number_format( $totalEntregadosGlobal - $totalRecibidosGlobal, 0));
                });

                $sheet->cells('A:D', function($cells) {
                    $cells->setAlignment('left');
                    $cells->setValignment('center');
                });

                $sheet->cells('A5:D5', function($cells) {
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                    $cells->setFontWeight('bold');
                    $cells->setFontSize(12);
                });

                $sheet->fromArray($rows, null, 'A5', true);
            });
        })->export('xlsx');
    }

    /**
     * Use Excel instance to export all records according to parameters given by the user.
     *
     * @return \Illuminate\Http\Response
     */
    public function exportHistoric(Request $req)
    {
        $rows = array();
        $req->request->add([ 'user' => auth()->user() ]);
        $items = Historial::filter( $req->all() )->orderBy('fecha_entrega', 'desc')->get();

        $totalEntregados = $totalRecibidos = 0;
        $fechaInicioFormateada = 'N/A';
        $fechaFinFormateada = 'N/A'; 

        if ( $req->fecha_inicio ) {
            $fechaInicioFormateada = strftime('%d', strtotime($req->fecha_inicio)).' de '.strftime('%B', strtotime($req->fecha_inicio)). ' del '.strftime('%Y', strtotime($req->fecha_inicio));
        }

        if ( $req->fecha_fin ) {
            $fechaFinFormateada = strftime('%d', strtotime($req->fecha_fin)).' de '.strftime('%B', strtotime($req->fecha_fin)). ' del '.strftime('%Y', strtotime($req->fecha_fin));
        }

        $periodo = $fechaInicioFormateada.' - '.$fechaFinFormateada;

        foreach ( $items as $item ) {
            $fechaFormateada = 'N/A';
            $pagos = 0;

            if ( $item->tipo->id == 1 ) { // Entregados
                $totalEntregados += $item->cantidad;
            } else if ( $item->tipo->id == 2 ) { // Recibidos
                $totalRecibidos += $item->cantidad;
            }

            $fechaFormateada = $item->fecha_entrega ? strftime('%d', strtotime($item->fecha_entrega)).' de '.strftime('%B', strtotime($item->fecha_entrega)). ' del '.strftime('%Y', strtotime($item->fecha_entrega)) : '';

            $rows [] = [
                'Empleado'        => $item->empleado->nombre.' ('.$item->empleado->razon_social->nombre.')',
                'Tipo'            => $item->tipo->descripcion,
                'Artículo'        => $item->articulo->nombre,
                'Status artículo' => $item->status->nombre,
                'Talla'           => $item->talla->nombre,
                'Color'           => $item->color,
                'Cantidad'        => $item->cantidad,
                'Fecha'           => $fechaFormateada,
                'Notas'           => $item->notas,
            ];
        }

        Excel::create('Listado de historial de uniformes', function($excel) use ($rows, $totalEntregados, $totalRecibidos, $periodo) {
            $excel->sheet('Hoja 1', function($sheet) use($rows, $totalEntregados, $totalRecibidos, $periodo) {

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

                $sheet->cell('A1', function($cell) use ($totalEntregados) {
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(12);
                    $cell->setValue('Total de uniformes entregados al empleado: '.number_format( $totalEntregados, 0));
                });

                $sheet->cell('A2', function($cell) use ($totalRecibidos) {
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(12);
                    $cell->setValue('Total de uniformes devueltos por empleado: '.number_format( $totalRecibidos, 0));
                });

                $sheet->cell('A3', function($cell) use ($totalEntregados, $totalRecibidos) {
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(12);
                    $cell->setValue('Artículos por devolver: '.number_format( $totalEntregados - $totalRecibidos, 0));
                });

                $sheet->cells('A:I', function($cells) {
                    $cells->setAlignment('left');
                    $cells->setValignment('center');
                });

                $sheet->cells('A7:I7', function($cells) {
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                    $cells->setFontWeight('bold');
                    $cells->setFontSize(12);
                });

                $sheet->fromArray($rows, null, 'A7', true);
                // $sheet->setAutoFilter('A5:E5');
            });
        })->export('xlsx');
    }
}
