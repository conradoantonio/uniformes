<?php

namespace App\Http\Controllers;

use Excel;

use \App\Status;
use \App\Cliente;
use \App\Factura;
use \App\Historial;
use \App\Comentario;
use \App\RazonSocial;
use \App\TipoContacto;
use \App\CancelacionFactura;

use Carbon\Carbon;

use Illuminate\Http\Request;

class CobranzasController extends Controller
{
    /**
     * Show the main view.
     *
     */
    public function index(Request $req)
    {
        $title = $menu = "Cobranza";

        $filters = [
            'user'          => auth()->user(), 
            'limit'         => 100, 
            'no_canceladas' => true,
            'pagada'        => 0,
        ];

        $items = Factura::filter( $filters )->orderBy('fecha_promesa_pago', 'asc')->get();
        
        $status = Status::all();
        $clientes = [];
        $razones = RazonSocial::filter( $filters )->get();

        if ( $req->ajax() ) {
            return view('cobranza.table', compact('items'));
        }
        return view('cobranza.index', compact('items', 'status', 'clientes', 'razones', 'menu', 'title'));
    }

    /**
     * Filter user franchise acording to the filters given by user.
     *
     */
    public function filter(Request $req)
    {
        $req->request->add([ 'user' => auth()->user(), 'no_canceladas' => true, 'pagada' => 0 ]);

        $items = Factura::filter( $req->all() )->orderBy('fecha_promesa_pago', 'asc')->get();

        if ( $req->only_data ) {
            return response(['msg' => 'Facturas enlistadas a continuación', 'status' => 'success', 'data' => $items, 'total' => count($items)], 200);
        }

        return view('cobranza.table', compact(['items']));
    }

    /**
     * Get comments of bills
     *
     */
    public function getComments(Request $req)
    {
        $filters = [ 'user' => auth()->user(), 'no_canceladas' => true, 'pagada' => 0 ];

        $item = Factura::filter( $filters )->with(['comentarios.tipo'])->where('id', $req->id)->first();

        return response(['msg' => 'Comentarios enlistados a continuación', 'status' => 'success', 'data' => $item], 200);
    }

    /**
     * Save a resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function save(Request $req)
    {
        $factura = Factura::find($req->factura_id); 

        if (! $factura ) { return response(['msg' => 'Debe seleccionar una factura para continuar', 'status' => 'error'], 404); }

        $tipo = TipoContacto::find($req->tipo_contacto_id); 

        if (! $tipo ) { return response(['msg' => 'Debe seleccionar un tipo de contacto para continuar', 'status' => 'error'], 404); }

        $item = New Comentario;

        $item->factura_id = $factura->id;
        $item->tipo_contacto_id = $tipo->id;
        $item->contacto = $req->contacto;
        $item->fecha = $req->fecha;
        $item->comentario = $req->comentario;

        $item->save();

        return response(['msg' => 'Registro guardado correctamente', 'status' => 'success', 'data' => $item->load(['tipo'])], 200);
    }

    /**
     * Change the status of the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $req)
    {
        $msg = count($req->ids) > 1 ? 'los registros' : 'el registro';
        $items = Comentario::whereIn('id', $req->ids)
        ->delete();

        if ( $items ) {
            return response(['msg' => 'Éxito eliminando '.$msg, 'status' => 'success'], 200);
        } else {
            return response(['msg' => 'Error al eliminar '.$msg, 'status' => 'error'], 404);
        }
    }

    /**
     * Use Excel instance to export all items at once.
     *
     * @return \Illuminate\Http\Response
     */
    public function export(Request $req)
    {
        $rows = array();
        $req->request->add([ 'user' => auth()->user(), 'no_canceladas' => true, 'pagada' => 0 ]);
        $items = Factura::filter( $req->all() )->get();

        $totalFacturas = $totalPagado = 0;
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
            $fecha_factura_formateada = strftime('%d', strtotime($item->fecha_facturacion)).' de '.strftime('%B', strtotime($item->fecha_facturacion)). ' del '.strftime('%Y', strtotime($item->fecha_facturacion));
            $fecha_creacion_formateada = strftime('%d', strtotime($item->created_at)).' de '.strftime('%B', strtotime($item->created_at)). ' del '.strftime('%Y', strtotime($item->created_at));
            $balance = $item->notas_credito->sum('importe') + $item->pagos->sum('importe');
            $totalFacturas += $item->importe;
            $totalPagado += $balance;

            $rows [] = [
                'Número de factura'  => $item->numero_factura,
                'Importe'            => number_format( $item->importe , 2 ),
                'Balance'            => number_format( $item->importe - ( $balance ), 2 ),
                'Status'             => $item->status->nombre,
                'Tipo de movimiento' => 'Factura',
                'Cliente'            => $item->cliente ? $item->cliente->nombre : 'N/A',
                'Razón social'       => $item->cliente && $item->cliente->razon_social ? $item->cliente->razon_social->nombre : 'N/A',
                'Fecha facturación'  => $fecha_factura_formateada,
                // 'Fecha de creación'  => $fecha_creacion_formateada,
            ];
        }

        Excel::create('Listado de facturas', function($excel) use ($rows, $totalFacturas, $totalPagado, $periodo) {
            $excel->sheet('Hoja 1', function($sheet) use($rows, $totalFacturas, $totalPagado, $periodo) {

                $sheet->cell('A2', function($cell) use($periodo) {
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(12);
                    $cell->setValue('Periodo: '.$periodo);
                });

                $sheet->cell('A3', function($cell) use ($totalFacturas) {
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(12);
                    $cell->setValue('Total facturado: $'.number_format( $totalFacturas, 2));
                });

                $sheet->cell('A4', function($cell) use ($totalPagado) {
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(12);
                    $cell->setValue('Total pagado: $'.number_format( $totalPagado, 2));
                });

                $sheet->cell('A5', function($cell) use ($totalFacturas, $totalPagado) {
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(12);
                    $cell->setValue('Total deuda: $'.number_format( $totalFacturas - $totalPagado, 2));
                });

                $sheet->cells('A:H', function($cells) {
                    $cells->setAlignment('left');
                    $cells->setValignment('center');
                });

                $sheet->cells('A7:H7', function($cells) {
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                    $cells->setFontWeight('bold');
                    $cells->setFontSize(12);
                });

                $sheet->fromArray($rows, null, 'A7', true);
            });
        })->export('xlsx');
    }

    /**
     * Use Excel instance to export comments from bills at once.
     *
     * @return \Illuminate\Http\Response
     */
    public function exportComments(Request $req)
    {
        $rows = array();
        $req->request->add([ 'user' => auth()->user(), 'no_canceladas' => true, 'pagada' => 0 ]);
        $ids = Factura::filter( $req->all() )->pluck('id');
        $items = Comentario::whereIn('factura_id', $ids)->get();

        $total = 0;
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
            $fechaFormateada = strftime('%d', strtotime($item->fecha)).' de '.strftime('%B', strtotime($item->fecha)). ' del '.strftime('%Y', strtotime($item->fecha));
            $total ++;

            $rows [] = [
                'Comentario'        => $item->comentario,
                'Tipo de contacto'  => $item->tipo ? $item->tipo->nombre : 'N/A',
                'Contacto'          => $item->contacto,
                'Número de factura' => $item->factura->numero_factura,
                'Importe'           => $item->factura->importe,
                'Status'            => $item->factura->status->nombre,
                'Fecha'             => $fechaFormateada,
            ];
        }

        Excel::create('Listado de comentarios', function($excel) use ($rows, $total, $periodo) {
            $excel->sheet('Hoja 1', function($sheet) use($rows, $total, $periodo) {

                $sheet->cell('A2', function($cell) use($periodo) {
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(12);
                    $cell->setValue('Periodo: '.$periodo);
                });

                $sheet->cell('A3', function($cell) use ($total) {
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(12);
                    $cell->setValue('Total de comentarios: #'.$total);
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
            });
        })->export('xlsx');
    }
}
