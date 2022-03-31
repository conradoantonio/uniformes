<?php

namespace App\Http\Controllers;

use Excel;

use \App\Status;
use \App\Cliente;
use \App\Factura;
use \App\Historial;
use \App\CancelacionFactura;

use Illuminate\Http\Request;

class FacturasCanceladasController extends Controller
{
    /**
     * Show the main view.
     *
     */
    public function index(Request $req)
    {
        $title = $menu = "Facturas canceladas";

        $filters = [
            'user'  => auth()->user(), 
            'limit' => 100, 
            'canceladas' => true
        ];
        $items = Factura::filter( $filters )->get();

        $status = Status::all();
        $clientes = Cliente::filter( ['user' => auth()->user()] )->get();

        if ( $req->ajax() ) {
            return view('facturas_canceladas.table', compact('items'));
        }
        return view('facturas_canceladas.index', compact('items', 'status', 'clientes', 'menu', 'title'));
    }

    /**
     * Filter user franchise acording to the filters given by user.
     *
     */
    public function filter(Request $req)
    {
        $req->request->add([ 'user' => auth()->user(), 'canceladas' => true ]);

        $items = Factura::filter( $req->all() )->get();

        if ( $req->only_data ) {
            return response(['msg' => 'Facturas enlistadas a continuación', 'status' => 'success', 'data' => $items], 200);
        }

        return view('facturas_canceladas.table', compact(['items']));
    }
    
    /**
     * Show the form for creating/editing a user franchise.
     *
     * @return \Illuminate\Http\Response
     */
    public function form($id = 0)
    {
        $title = "Formulario de facturas";
        $menu = "Facturas canceladas";
        $item = null;

        $clientes = Cliente::filter( ['user' => auth()->user()] )->get();

        if ( $id ) {
            $item = Factura::find($id);
        }
        return view('facturas_canceladas.form', compact(['item', 'clientes', 'menu', 'title']));
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
            return response(['msg' => 'Éxito eliminando '.$msg, 'url' => url('facturas/canceladas'), 'status' => 'success'], 200);
        } else {
            return response(['msg' => 'Error al cambiar el status de '.$msg, 'status' => 'error', 'url' => url('facturas/canceladas')], 404);
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
        $req->request->add([ 'user' => auth()->user(), 'canceladas' => true ]);

        $items = Factura::filter( $req->all() )->get();

        foreach ( $items as $item ) {
            $photo = explode('/', $item->photo);
            $path = null;
            
            if ( $photo ) {
                $path = @$photo[2];
            }

            $rows [] = 
                [
                    'Nombre completo' => $item->fullname,
                    'Total de pedidos' => (count($item->pedidos) ? $item->pedidos->count() : 0),
                    'Gasto total' => $item->pedidos->sum('total') ? '$'.$item->pedidos->sum('total') : '$0',
                    'Correo' => $item->email,
                    'Teléfono' => $item->phone,
                    'ID para notificaciones' => $item->player_id ? $item->player_id : 'No asignado',
                    'Status' => $item->status == 1 ? 'Activo' : 'Deshabilitado',
                    'foto' => $path,
                ];
        }

        Excel::create('Lista de clientes', function( $excel ) use ( $rows ) {
            $excel->sheet('Hoja 1', function( $sheet ) use ( $rows ) {
                $sheet->cells('A:H', function( $cells ) {
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                });
                
                $sheet->cells('A1:H1', function( $cells ) {
                    $cells->setFontWeight('bold');
                });

                $sheet->fromArray( $rows );
            });
        })->export('xlsx');
    }
}
