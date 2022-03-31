<?php

namespace App\Http\Controllers;

use Excel;

use \App\Recibo;
use \App\Cliente;
use \App\Factura;
use \App\RazonSocial;
use \App\StatusCliente;

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
        $clientes = Cliente::filter( $filters )->get();
        $statusCliente = StatusCliente::all();

        if ( $req->ajax() ) {
            return view('razones_sociales.table', compact('items'));
        }
        return view('razones_sociales.index', compact('items', 'clientes', 'statusCliente', 'menu', 'title'));
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
        $rows = $clientes = array();
        $req->request->add([ 'user' => auth()->user() ]);
        $razones = RazonSocial::filter( $req->all() )->orderBy('id', 'desc')->get();
        $totalFacturas = $totalPagos = 0;

        foreach ( $razones as $razon ) {
            $montoFacturas = $pagosRealizados = 0;
            
            if( $req->status_cliente_id ) {
                $clientes = $razon->clientes->where('status_cliente_id', $req->status_cliente_id);
            } else {
                $clientes = $razon->clientes;
            }

            foreach( $clientes as $cliente ) {
                $montoFacturas += $cliente->facturas->where('pagada', 0)->sum('importe');
                $ids = Factura::where('cliente_id', $cliente->id)->where('pagada', 0)->pluck('id');
                $pagosRealizados += Recibo::whereIn('factura_id', $ids)->sum('importe');
            }

            $rows [] = [
                'Razón social'     => $razon->nombre,
                'Suma de facturas' => $montoFacturas,
                'Suma de pagos'    => $pagosRealizados,
                'Balance'          => $montoFacturas - $pagosRealizados,
            ];

            $totalFacturas += $montoFacturas;
            $totalPagos += $pagosRealizados;
        }

        Excel::create('Adeudo por razón social', function($excel) use ($rows, $totalFacturas, $totalPagos) {
            $excel->sheet('Hoja 1', function($sheet) use($rows, $totalFacturas, $totalPagos) {

                $sheet->cell('A1', function($cell) use ($totalFacturas) {
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(12);
                    $cell->setValue('Total de facturas: $'.number_format( $totalFacturas, 2));
                });

                $sheet->cell('A2', function($cell) use ($totalPagos) {
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(12);
                    $cell->setValue('Total de pagos: $'.number_format( $totalPagos, 2));
                });

                $sheet->cell('A3', function($cell) use ($totalFacturas, $totalPagos) {
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(12);
                    $cell->setValue('Balance: $'.number_format( $totalFacturas - $totalPagos, 2));
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
     * Use Excel instance to export all bills with his balance at once.
     *
     * @return \Illuminate\Http\Response
     */
    public function exportBills(Request $req)
    {
        $rows = array();
        $req->request->add([ 'user' => auth()->user(), 'pagada' => 0, 'no_canceladas' => true ]);
        $facturas = Factura::filter( $req->all() )->orderBy('fecha_facturacion', 'desc')->get();

        $totalFacturas = $totalPagos = 0;
        $fechaInicioFormateada = 'N/A';
        $fechaFinFormateada = 'N/A'; 

        if ( $req->fecha_inicio ) {
            $fechaInicioFormateada = strftime('%d', strtotime($req->fecha_inicio)).' de '.strftime('%B', strtotime($req->fecha_inicio)). ' del '.strftime('%Y', strtotime($req->fecha_inicio));
        }

        if ( $req->fecha_fin ) {
            $fechaFinFormateada = strftime('%d', strtotime($req->fecha_fin)).' de '.strftime('%B', strtotime($req->fecha_fin)). ' del '.strftime('%Y', strtotime($req->fecha_fin));
        }

        $periodo = $fechaInicioFormateada.' - '.$fechaFinFormateada;

        foreach ( $facturas as $factura ) {
            $fechaFormateada = 'N/A';
            $pagos = 0;

            $pagos = Recibo::where('factura_id', $factura->id)->sum('importe');
            $totalFacturas += $factura->importe;
            $totalPagos += $pagos;
            $fechaFormateada = $factura->fecha_facturacion ? strftime('%d', strtotime($factura->fecha_facturacion)).' de '.strftime('%B', strtotime($factura->fecha_facturacion)). ' del '.strftime('%Y', strtotime($factura->fecha_facturacion)) : '';

            $rows [] = [
                'Cliente'              => $factura->cliente->nombre.' ('.$factura->cliente->razon_social->nombre.')',
                'Número / Folio'       => $factura->numero_factura,
                'Importe'              => $factura->importe,
                'Pagos adjuntados'     => $pagos,
                'Balance'              => $factura->importe - $pagos,
                'Fecha de facturación' => $fechaFormateada,
                'Comentario'           => $factura->comentarios_adicionales,
            ];
        }

        Excel::create('Listado de facturas', function($excel) use ($rows, $totalFacturas, $totalPagos, $periodo) {
            $excel->sheet('Hoja 1', function($sheet) use($rows, $totalFacturas, $totalPagos, $periodo) {

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

                $sheet->cell('A2', function($cell) use ($totalFacturas, $totalPagos) {
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(12);
                    $cell->setValue('Balance: $'.number_format( $totalFacturas - $totalPagos, 2));
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
