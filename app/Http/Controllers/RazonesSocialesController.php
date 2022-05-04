<?php

namespace App\Http\Controllers;

use Excel;

use \App\Recibo;
use \App\Empleado;
use \App\Historial;
use \App\RazonSocial;
use \App\HistorialTipo;
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
        $totalEntregadosGlobal = $totalDevueltosGlobal = 0;

        foreach ( $razones as $razon ) {
            $totalEntregados = $totalDevueltos = 0;
            
            if( $req->status_empleado_id ) {
                $empleados = $razon->empleados->where('status_empleado_id', $req->status_empleado_id);
            } else {
                $empleados = $razon->empleados;
            }

            // Suma la cantidad de uniformes entregados y recibidos por razón social
            foreach( $empleados as $empleado ) {

                foreach ( $empleado->uniformes as $uniformeHist ) {

                    $movimientos = $fechaEntrega = $fechaFormateada = '';

                    $entregados = $uniformeHist->tipos()->whereIn('tipo_historial_id', [1,2])->exists();          
                    $recibidos = $uniformeHist->tipos()->where('tipo_historial_id', 3)->exists();          

                    if ( $recibidos > 0 ) { $totalDevueltos += $uniformeHist->cantidad; }
                    elseif ( $entregados > 0 ) { $totalEntregados += $uniformeHist->cantidad; }
            }

                // $totalEntregados += $empleado->uniformes_entregados->sum('cantidad');
                // $totalDevueltos  += $empleado->uniformes_recibidos->sum('cantidad');
            }

            $rows [] = [
                'Razón social'           => $razon->nombre,
                'Artículos entregados'   => $totalEntregados,
                'Artículos devueltos'    => $totalDevueltos,
                'Artículos por devolver' => $totalEntregados - $totalDevueltos,
            ];

            $totalEntregadosGlobal += $totalEntregados;
            $totalDevueltosGlobal  += $totalDevueltos;
        }

        Excel::create('Histórico por razón social', function($excel) use ($rows, $totalEntregadosGlobal, $totalDevueltosGlobal) {
            $excel->sheet('Hoja 1', function($sheet) use($rows, $totalEntregadosGlobal, $totalDevueltosGlobal) {

                $sheet->cell('A1', function($cell) use ($totalEntregadosGlobal) {
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(12);
                    $cell->setValue('Total de uniformes entregados al empleado: '.number_format( $totalEntregadosGlobal, 0));
                });

                $sheet->cell('A2', function($cell) use ($totalDevueltosGlobal) {
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(12);
                    $cell->setValue('Total de uniformes devueltos por empleado: '.number_format( $totalDevueltosGlobal, 0));
                });

                $sheet->cell('A3', function($cell) use ($totalEntregadosGlobal, $totalDevueltosGlobal) {
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(12);
                    $cell->setValue('Artículos por devolver: '.number_format( $totalEntregadosGlobal - $totalDevueltosGlobal, 0));
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
        $razonSocial = RazonSocial::find($req->razon_social_id);
        $items = Historial::filter( $req->all() )->get();

        $totalEntregados = $totalDevueltos = 0;
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
            $movimientos = $fechaEntrega = $fechaFormateada = '';
            
            // $entregados = HistorialTipo::where('historial_id', $item->id)->whereIn('tipo_historial_id', [1,2])->count();
            // $recibidos  = HistorialTipo::where('historial_id', $item->id)->whereIn('tipo_historial_id', [3])->count();
            $entregados = $item->tipos()->whereIn('tipo_historial_id', [1,2])->exists();          
            $recibidos = $item->tipos()->where('tipo_historial_id', 3)->exists();          

            if ( $recibidos > 0 ) { $totalDevueltos += $item->cantidad; }
            elseif ( $entregados > 0 ) { $totalEntregados += $item->cantidad; }
            
            foreach( $item->tipos as $move ) {
                $fechaEntrega = $move->pivot->fecha;
                $fechaFormateada = strftime('%d', strtotime($fechaEntrega)).' de '.strftime('%B', strtotime($fechaEntrega)). ' del '.strftime('%Y', strtotime($fechaEntrega));
                $movimientos .= ( $move->nombre.' - '.$fechaFormateada.' / ' );
            }
            
            $rows [] = [
                'Empleado'          => $item->empleado->nombre,
                'No. empleado'      => $item->empleado->numero_empleado,
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

        Excel::create('Histórico por razón social', function($excel) use ($rows, $totalEntregados, $totalDevueltos, $razonSocial, $periodo) {
            $excel->sheet('Hoja 1', function($sheet) use($rows, $totalEntregados, $totalDevueltos, $razonSocial, $periodo) {

                $sheet->cell('A1', function($cell) use ($razonSocial) {
                    if ( $razonSocial ) {
                        $cell->setFontWeight('bold');
                        $cell->setFontSize(12);
                        $cell->setValue('Razón social: '.$razonSocial->nombre);
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

                $sheet->cell('A4', function($cell) use ($totalDevueltos) {
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(12);
                    $cell->setValue('Artículos devueltos: #'.number_format( $totalDevueltos, 0));
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
