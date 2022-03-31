<?php

namespace App\Http\Controllers;

use Excel;

use \App\Status;
use \App\Cliente;
use \App\Factura;
use \App\Historial;
use \App\RazonSocial;
use \App\StatusCliente;
use \App\CancelacionFactura;

use Carbon\Carbon;

use Illuminate\Http\Request;

class FacturasController extends Controller
{
    /**
     * Show the main view.
     *
     */
    public function index(Request $req)
    {
        $st = $req->s;
        $statusCliente = StatusCliente::where('url', $st)->first();
        if (! $statusCliente ) { return view('errors.404'); }

        $title = "Facturas - ".$statusCliente->url;
        $menu = "Facturas";

        $filters = [
            'user'              => auth()->user(), 
            'status_cliente_id' => $statusCliente->id,
            'no_canceladas'     => true,
            'limit'             => 100, 
        ];

        $items = Factura::filter( $filters )->orderBy('id', 'desc')->get();
        
        $status = Status::all();
        $clientes = [];
        $razones = RazonSocial::filter( $filters )->get();

        if ( $req->ajax() ) {
            return view('facturas.table', compact('items'));
        }
        return view('facturas.index', compact('items', 'status', 'statusCliente', 'clientes', 'razones', 'menu', 'title'));
    }

    /**
     * Filter user franchise acording to the filters given by user.
     *
     */
    public function filter(Request $req)
    {
        $req->request->add([ 'user' => auth()->user(), 'no_canceladas' => true ]);

        $items = Factura::filter( $req->all() )->orderBy('id', 'desc')->get();

        if ( $req->only_data ) {
            return response(['msg' => 'Facturas enlistadas a continuación', 'status' => 'success', 'data' => $items, 'total' => count($items)], 200);
        }

        return view('facturas.table', compact(['items']));
    }
    
    /**
     * Show the form for creating/editing a user franchise.
     *
     * @return \Illuminate\Http\Response
     */
    public function form($id = 0)
    {
        $title = "Formulario de facturas";
        $menu = "Facturas";
        $item = null;

        $clientes = Cliente::filter( ['user' => auth()->user(), 'status_cliente_id' => 1] );

        if ( $id ) {
            $item = Factura::whereDoesntHave('cancelacion')->where('id', $id)->first();
            // Obtenemos al cliente 
            if ( $item ) { $clientes->orWhere('id', $item->cliente_id); }
        }

        $clientes = $clientes->get();
        return view('facturas.form', compact(['item', 'clientes', 'menu', 'title']));
    }

    /**
     * Get the credit notes and payments of a bill
     *
     * @return \Illuminate\Http\Response
     */
    public function getDetails(Request $req)
    {
        $item = Factura::with(['notas_credito', 'pagos'])->where('id', $req->factura_id)->whereDoesntHave('cancelacion')->first();

        if (! $item ) { return response(['msg' => 'No se encontró la factura solicitada', 'status' => 'error'], 404); }

        foreach ($item->notas_credito as $nota) {
            $nota->fecha_formateada = strftime('%d', strtotime($nota->created_at)).' de '.strftime('%B', strtotime($nota->created_at)). ' del '.strftime('%Y', strtotime($nota->created_at));
        }

        foreach ($item->pagos as $nota) {
            $nota->fecha_formateada = strftime('%d', strtotime($pago->created_at)).' de '.strftime('%B', strtotime($pago->created_at)). ' del '.strftime('%Y', strtotime($pago->created_at));
        }

        return response(['msg' => 'Información de factura enlistada a continuación', 'status' => 'success', 'data' => $item], 200);
    }

    /**
     * Save a resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function save(Request $req)
    {
        $cliente = Cliente::find($req->cliente_id); 

        if (! $cliente ) { return response(['msg' => 'Debe seleccionar un cliente para guardar una factura', 'status' => 'error'], 404); }

        $item = New Factura;

        // $photo = $this->upload_file($req->file('photo'), 'img/facturas', true);

        $item->status_id = 1;
        $item->cliente_id = $cliente->id;
        $item->numero_factura = $req->numero_factura;
        $item->importe = $req->importe;
        $item->fecha_facturacion = $req->fecha_facturacion;
        $fecha_promesa_pago = Carbon::parse($item->fecha_facturacion)->addDays($item->cliente->dias_credito);
        $item->fecha_promesa_pago = $fecha_promesa_pago->format('Y-m-d');
        $item->comentarios_adicionales = $req->comentarios_adicionales;
        // $photo ? $item->photo = $photo : '';

        $item->save();

        $this->guardarHistorial($cliente, $item, Factura::class);

        return response(['msg' => 'Registro guardado correctamente', 'url' => url('facturas?s=activos'), 'status' => 'success'], 200);
    }

    /**
     * Edit a resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req)
    {
        $item = Factura::find($req->id);

        if (! $item ) { return response(['msg' => 'No se encontró el registro a editar', 'status' => 'error'], 404); }

        $cliente = Cliente::find($req->cliente_id); 

        if (! $cliente ) { return response(['msg' => 'Debe seleccionar un cliente para guardar una factura', 'status' => 'error'], 404); }

        $this->editarHistorial($item->historial, $cliente, $item, Factura::class, $req->fecha_facturacion);

        // $item->status_id = 1;
        $item->cliente_id = $cliente->id;
        $item->numero_factura = $req->numero_factura;
        $item->importe = $req->importe;
        $item->fecha_facturacion = $req->fecha_facturacion;
        $fecha_promesa_pago = Carbon::parse($item->fecha_facturacion)->addDays($item->cliente->dias_credito);
        $item->fecha_promesa_pago = $fecha_promesa_pago->format('Y-m-d');
        $item->comentarios_adicionales = $req->comentarios_adicionales;
        $item->pagada = $req->pagada ? 1 : 0;
        
        // Marcada como pagada se debe modificar el status a normal
        if ( $req->pagada ) {
            $item->status_id = 1;// Normal
        }

        $item->save();

        $url = url('facturas?s=activos');
        
        if( $item->status_cliente_id == 1 ) { $url = url('facturas?s=activos'); }
        elseif( $item->status_cliente_id == 2 ) { $url = url('facturas?s=inactivos'); }
        elseif( $item->status_cliente_id == 3 ) { $url = url('facturas?s=legales'); }
        elseif( $item->status_cliente_id == 4 ) { $url = url('facturas?s=venados'); }

        return response(['msg' => 'Registro actualizado correctamente', 'url' => $url, 'status' => 'success'], 200);
    }

    /**
     * Change the status of the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $req)
    {
        $msg = count($req->ids) > 1 ? 'los registros' : 'el registro';
        $items = Factura::whereIn('id', $req->ids)
        ->get();

        foreach($items as $factura) {
            // Se elimina el historial de factura
            Historial::where('historiable_id', $factura->id)->where('historiable_type', 'App\Factura')->delete();

            // Se elimina el historial de notas de crédito de la factura
            foreach ($factura->notas_credito as $nota) {
                Historial::where('historiable_id', $nota->id)->where('historiable_type', 'App\Recibo')->delete();
                $nota->delete();
            }

            // Se elimina el historial de pagos de la factura
            foreach ($factura->pagos as $pago) {
                Historial::where('historiable_id', $pago->id)->where('historiable_type', 'App\Recibo')->delete();
                $pago->delete();
            }

            $factura->delete();
        }

        if ( $items ) {
            return response(['msg' => 'Éxito eliminando '.$msg, 'url' => url('facturas'), 'status' => 'success'], 200);
        } else {
            return response(['msg' => 'Error al cambiar el status de '.$msg, 'status' => 'error', 'url' => url('facturas')], 404);
        }
    }

    /**
     * Cancell a bill
     *
     */
    public function cancell(Request $req)
    {
        $factura = Factura::whereDoesntHave('cancelacion')
        ->where('id', $req->factura_id)
        ->first();

        if (! $factura ) { return response(['status' => 'error', 'msg' => 'Esta factura no puede ser cancelada'], 404); } 

        // Se elimina el historial de factura
        Historial::where('historiable_id', $factura->id)->where('historiable_type', 'App\Factura')->delete();

        // Se elimina el historial de notas de crédito de la factura
        foreach ($factura->notas_credito as $nota) {
            Historial::where('historiable_id', $nota->id)->where('historiable_type', 'App\Recibo')->delete();
            $nota->delete();
        }

        // Se elimina el historial de pagos de la factura
        foreach ($factura->pagos as $pago) {
            Historial::where('historiable_id', $pago->id)->where('historiable_type', 'App\Recibo')->delete();
            $pago->delete();
        }

        // Se genera el registro de cancelación
        $cancelacion = New CancelacionFactura;

        $cancelacion->factura_id = $factura->id;
        $cancelacion->razon = $req->razon;

        $cancelacion->save();

        return response(['msg' => 'Cancelación generada exitósamente', 'status' => 'success', 'url' => url('facturas')], 200);
    }

    /**
     * Import multiple resources by an excel instance.
     *
     * @return \Illuminate\Http\Response
     */
    public function import(Request $req)
    {
        $invalidos = $validos = 0;
        if ($req->hasFile('archivo-excel')) {
            $path = $req->file('archivo-excel')->getRealPath();
            $extension = $req->file('archivo-excel')->getClientOriginalExtension();

            $data = Excel::load($path, function($reader) {
                $reader->setDateFormat('Y-m-d');
            })->get();


            if (!empty($data) && $data->count()) {
                foreach ($data as $value) {
                    if (! $value->cliente ) continue ;#Declinar registro si no hay cliente...
                    
                    $cliente = Cliente::where('nombre', trim($value->cliente))->first();

                    if (! $cliente ) { 
                        if (! in_array( trim($value->cliente), ['NUEVA AUTOMOTRIZ OCCIDENTE SA DE CV', 'INSTITUTO RENACIMIENTO DE GUANAJUATO AC', 'SISTEMAS AL APOYO LOGISTICO COMERCIAL INTERNACIONAL'] ) ) {
                            dd(trim($value->cliente));
                        }
                        $invalidos ++; 
                    }

                    if (! $cliente ) continue ;#Declinar registro si no hay cliente...

                    $factura = Factura::updateOrCreate(
                        ['cliente_id' => $cliente->id, 'numero_factura' => $value->numero_factura, 'importe' => $value->importe],
                        [
                            'cliente_id'              => $cliente->id,
                            'status_id'               => 1,
                            'numero_factura'          => $value->numero_factura,
                            'importe'                 => round($value->importe, 2),
                            'fecha_facturacion'       => $value->fecha_facturacion,
                            'fecha_promesa_pago'      => $value->fecha_promesa_de_pago,
                            'comentarios_adicionales' => $value->comentarios,
                            'pagada' => 0,
                            'created_at'              => $value->fecha_facturacion,
                        ]
                    );

                    $validos ++;
                    // $factura = Factura::insert(
                    //     [
                    //         'cliente_id'              => $cliente->id,
                    //         'status_id'               => 1,
                    //         'numero_factura'          => $value->numero_factura,
                    //         'importe'                 => round($value->importe, 2),
                    //         'fecha_facturacion'       => $value->correo,
                    //         'fecha_promesa_pago'      => $value->nombre_encargado,
                    //         'comentarios_adicionales' => (int)$value->dias_credito,
                    //         'pagada' => 0,
                    //     ]
                    // );
                    // dd($factura);
                }
            } else {
                return ['status' => false, 'msg' => 'El excel esta vacío'];
            }
            return ['status' => true, 'msg' => 'Se han importado los registros del excel ('.$invalidos.' Invalidos) ('.$validos.' Válidos)'];
        }
        else {
            return ['status' => false, 'msg' => "Ocurrió un problema para leer el excel, contacte al administrador"];
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
        $req->request->add([ 'user' => auth()->user(), 'no_canceladas' => true ]);
        $items = Factura::filter( $req->all() )->orderBy('id', 'desc')->get();

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
            $fecha_factura_formateada = strftime('%d', strtotime($item->fecha_facturacion)).' de '.strftime('%B', strtotime($item->fecha_facturacion)). ' del '.strftime('%Y', strtotime($item->fecha_facturacion));
            $fecha_creacion_formateada = strftime('%d', strtotime($item->created_at)).' de '.strftime('%B', strtotime($item->created_at)). ' del '.strftime('%Y', strtotime($item->created_at));
            $total += $item->importe;

            $rows [] = [
                'Número de factura'  => $item->numero_factura,
                'Importe'            => $item->importe,
                'Status'             => $item->status->nombre,
                'Tipo de movimiento' => 'Factura',
                'Cliente'            => $item->cliente ? $item->cliente->nombre : 'N/A',
                'Razón social'       => $item->cliente && $item->cliente->razon_social ? $item->cliente->razon_social->nombre : 'N/A',
                'Fecha facturación'  => $fecha_factura_formateada,
                // 'Fecha de creación'  => $fecha_creacion_formateada,
            ];
        }

        Excel::create('Listado de facturas', function($excel) use ($rows, $total, $periodo) {
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
