<?php

namespace App\Http\Controllers;

use Excel;

use \App\Status;
use \App\Recibo;
use \App\Cliente;
use \App\Factura;
use \App\Historial;
use \App\RazonSocial;

use Illuminate\Http\Request;

class PagosController extends Controller
{
    /**
     * Show the main view.
     *
     */
    public function index(Request $req)
    {
        $title = $menu = "Pagos";

        $filters = [
            'user'           => auth()->user(), 
            'pagadas'        => 0,
            'tipo_recibo_id' => 2,
            'limit'          => 300,
        ];

        $items = Recibo::filter( $filters )->get();

        $clientes = [];
        $razones = RazonSocial::filter( $filters )->get();

        if ( $req->ajax() ) {
            return view('pagos.table', compact('items'));
        }
        return view('pagos.index', compact('items', 'clientes', 'razones', 'menu', 'title'));
    }

    /**
     * Filter user franchise acording to the filters given by user.
     *
     */
    public function filter(Request $req)
    {
        $req->request->add([ 'user' => auth()->user(), 'tipo_recibo_id' => 2 ]);

        $items = Recibo::filter( $req->all() )->get();

        return view('pagos.table', compact(['items']));
    }
    
    /**
     * Show the form for creating/editing a user franchise.
     *
     * @return \Illuminate\Http\Response
     */
    public function form($id = 0)
    {
        $title = "Formulario de pagos";
        $menu = "Pagos";
        $item = null;

        $clientes = Cliente::filter( ['user' => auth()->user()] )->get();
        $facturas = [];

        if ( $id ) {
            $item = Recibo::find($id);
            if ( $item ) {
                $facturas = Factura::filter( ['user' => auth()->user()] )
                ->where('cliente_id', $item->cliente_id)
                ->whereDoesntHave('cancelacion')->get();
            }
        }
        return view('pagos.form', compact(['item', 'clientes', 'facturas', 'menu', 'title']));
    }

    /**
     * Save a resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function save(Request $req)
    {
        $cliente = Cliente::find($req->cliente_id); 

        if (! $cliente ) { return response(['msg' => 'Debe seleccionar un cliente para guardar una nota de crédito', 'status' => 'error'], 404); }

        $factura = Factura::find($req->factura_id); 

        if (! $factura ) { return response(['msg' => 'Debe seleccionar una factura para guardar una nota de crédito', 'status' => 'error'], 404); }

        $item = New Recibo;

        $item->tipo_recibo_id = 2;
        $item->cliente_id = $cliente->id;
        $item->factura_id = $factura->id;
        $item->folio = $req->folio;
        $item->importe = $req->importe;
        $item->fecha_pago = $req->fecha_pago;

        $item->save();

        $this->guardarHistorial($cliente, $item, Recibo::class);

        return response(['msg' => 'Registro guardado correctamente', 'url' => url('pagos'), 'status' => 'success'], 200);
    }

    /**
     * Edit a resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req)
    {
        $item = Recibo::where('id', $req->id)->where('tipo_recibo_id', 2)->first();

        if (! $item ) { return response(['msg' => 'No se encontró el registro a editar', 'status' => 'error'], 404); }

        $cliente = Cliente::find($req->cliente_id); 

        if (! $cliente ) { return response(['msg' => 'Debe seleccionar un cliente para guardar una nota de crédito', 'status' => 'error'], 404); }

        $factura = Factura::find($req->factura_id); 

        if (! $factura ) { return response(['msg' => 'Debe seleccionar una factura para guardar una nota de crédito', 'status' => 'error'], 404); }

        $this->editarHistorial($item->historial, $cliente, $item, Recibo::class, $req->fecha_pago);
        
        // $item->tipo_recibo_id = 2;
        $item->cliente_id = $cliente->id;
        $item->factura_id = $factura->id;
        $item->folio = $req->folio;
        $item->importe = $req->importe;
        $item->fecha_pago = $req->fecha_pago;

        $item->save();

        return response(['msg' => 'Registro actualizado correctamente', 'url' => url('pagos'), 'status' => 'success'], 200);
    }

    /**
     * Change the status of the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $req)
    {
        $msg = count($req->ids) > 1 ? 'los registros' : 'el registro';
        $items = Recibo::whereIn('id', $req->ids)->where('tipo_recibo_id', 2)
        ->delete();

        // Se elimina el registro de su historial
        Historial::where('historiable_type', 'App\Recibo')->whereIn('historiable_id', $req->ids)->delete();

        if ( $items ) {
            return response(['msg' => 'Éxito eliminando '.$msg, 'url' => url('pagos'), 'status' => 'success'], 200);
        } else {
            return response(['msg' => 'Error al cambiar el status de '.$msg, 'status' => 'error', 'url' => url('pagos')], 404);
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
        $req->request->add([ 'user' => auth()->user(), 'tipo_recibo_id' => 2 ]);

        $items = Recibo::filter( $req->all() )->get();

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
            $total += $item->importe;
            $num_factura = $item->factura ? $item->factura->numero_factura : '';
            $fecha = $item->factura ? strftime('%d', strtotime($item->factura->fecha_facturacion)).' de '.strftime('%B', strtotime($item->factura->fecha_facturacion)). ' del '.strftime('%Y', strtotime($item->factura->fecha_facturacion)) : '';

            $rows [] = [
                'Cliente'              => $item->cliente ? $item->cliente->nombre : 'N/A',
                'Razón social'         => $item->cliente && $item->cliente->razon_social ? $item->cliente->razon_social->nombre : 'N/A',
                'Tipo de movimiento'   => 'Pago',
                'Factura'              => $num_factura,
                'Importe'              => $item->importe,
                'Fecha de facturación' => $fecha,
            ];
        }

        Excel::create('Listado de pagos', function($excel) use ($rows, $total, $periodo) {
            $excel->sheet('Hoja 1', function($sheet) use($rows, $total, $periodo) {

                $sheet->cell('A2', function($cell) use($periodo) {
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(12);
                    $cell->setValue('Periodo: '.$periodo);
                });

                $sheet->cell('A3', function($cell) use ($total) {
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(12);
                    $cell->setValue('Total: $'.number_format( $total, 2));
                });

                $sheet->cells('A:I', function($cells) {
                    $cells->setAlignment('left');
                    $cells->setValignment('center');
                });

                $sheet->cells('A5:F5', function($cells) {
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
