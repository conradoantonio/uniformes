<?php

namespace App\Http\Controllers;

use PDF;
use Excel;

use \App\User;
use \App\Cliente;
use \App\Historial;
use \App\RazonSocial;
use \App\ClienteVenta;
use \App\ClienteNomina;
use \App\StatusCliente;
use \App\ClienteFactura;
use \App\ClienteContrato;
use \App\ClienteCobranza;
use \App\ClienteDatosGenerales;

use Illuminate\Http\Request;

class ClientesController extends Controller
{
    /**
     * Show active customers.
     *
     */
    public function index(Request $req)
    {
        $st = $req->s;
        $status = StatusCliente::where('url', $st)->first();
        if (! $status ) { return view('errors.404'); }

        $menu   = "Clientes";
        $title  = "Clientes $status->url";
        $filters = [
            'status_cliente_id' => $status->id, 
            'user'              => auth()->user(), 
            'limit'             => 100,
        ];

        $items = Cliente::filter( $filters )->get();
        $razones = RazonSocial::filter( $filters )->get();
        $statusCliente = StatusCliente::whereNotIn('id', [$status->id])->get();

        if ( $req->ajax() ) {
            return view('clientes.table', compact('items'));
        }
        return view('clientes.index', compact('items', 'razones', 'statusCliente', 'status', 'menu', 'title'));
    }

    /**
     * Filter user customer acording to the filters given by user.
     *
     */
    public function filter(Request $req)
    {
        $req->request->add([ 'user' => auth()->user() ]);

        $items = Cliente::filter( $req->all() )->get();

        if ( $req->only_data ) {
            return response(['msg' => 'Clientes enlistados a continuación', 'status' => 'success', 'data' => $items, 'total' => count($items)], 200);
        }

        return view('clientes.table', compact(['items']));
    }

    /**
     * Show the detail of customer.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $req)
    {
        $item = Cliente::with(['razon_social', 'cobranza', 'contrato', 'generales', 'factura', 'nomina', 'venta'])
        ->where('id', $req->id)
        ->first();

        if (! $item ) { return response(['msg' => 'Cliente no encontrado', 'status' => 'error'], 404); }

        return response(['msg' => 'Cliente mostrado a continuación', 'status' => 'success', 'data' => $item], 200);
    }
	
	/**
     * Show the form for creating/editing a user customer.
     *
     * @return \Illuminate\Http\Response
     */
    public function form(Request $req, $id = 0)
    {
        $title = "Formulario de cliente";
        $menu = "Clientes";
        $item = null;
        $filters = [
            'user' => auth()->user(), 
        ];
        $razones = RazonSocial::filter( $filters )->get();

        if ( $id ) {
            $item = Cliente::find($id);
        }
        return view('clientes.form', compact(['item', 'razones', 'menu', 'title']));
    }

    /**
     * Save a resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function save(Request $req)
    {
        $razonSocial = RazonSocial::find($req->razon_social_id);

        if (! $razonSocial ) { return response(['msg' => 'Seleccione una razón social para crear al cliente', 'status' => 'error'], 404); }

        $item = New Cliente;
        
        $item->razon_social_id = $razonSocial->id;
        $item->status_cliente_id = 1;
        $item->nombre = $req->nombre;// Nombre comercial (Facturas)
        $item->correo = $req->correo;// Correo para envío de facturas (Facturas)
        $item->telefono = $req->telefono;
        $item->nombre_encargado = $req->nombre_encargado;
        $item->dias_credito = (int)$req->dias_credito;
        $item->domicilio = $req->domicilio;// Domicilio fiscal (Facturas)

        $item->save();

        // Cobranza
        $cobranza = New ClienteCobranza;

        $cobranza->cliente_id = $item->id;
        // $cobranza->persona_pagos = $req->persona_pagos;
        // $cobranza->telefono = $req->telefono;
        // $cobranza->correo_electronico = $req->correo_electronico;
        // $cobranza->dias_de_credito = $req->dias_de_credito;
        $cobranza->dias_de_pago = $req->dias_de_pago;
        $cobranza->transferencia = $req->transferencia ? 1 : 0;
        $cobranza->deposito = $req->deposito ? 1 : 0;
        $cobranza->cheque = $req->cheque ? 1 : 0;
        $cobranza->efectivo = $req->efectivo ? 1 : 0;
        $cobranza->otro = $req->otro ? 1 : 0;
        $cobranza->otro_opcion = $req->otro ? $req->otro_opcion : null;
        $cobranza->nombre_banco_cliente = $req->nombre_banco_cliente;
        $cobranza->cuenta_pago_cliente = $req->cuenta_pago_cliente;
        $cobranza->clabe_pago_cliente = $req->clabe_pago_cliente;
        $cobranza->observaciones = $req->cobranza_observaciones;

        $cobranza->save();

        // Contrato
        $contrato = New ClienteContrato;

        $contrato->cliente_id = $item->id;
        $contrato->contrato_original_expediente = $req->contrato_original_expediente;
        $contrato->firmado = $req->firmado;
        $contrato->escaneado = $req->escaneado;
        $contrato->tipo_contrato = $req->tipo_contrato;
        $contrato->nombre_representante_legal = $req->nombre_representante_legal;
        $contrato->cargo = $req->cargo;
        $contrato->nombre_testigo = $req->nombre_testigo;
        $contrato->vigencia = $req->vigencia;
        $contrato->acta_constitutiva = $req->acta_constitutiva ? 1 : 0;
        $contrato->ine_representante = $req->ine_representante ? 1 : 0;
        $contrato->ine_testigo = $req->ine_testigo ? 1 : 0;
        $contrato->cedula_fiscal = $req->cedula_fiscal ? 1 : 0;
        $contrato->comprobante_domicilio_fiscal = $req->comprobante_domicilio_fiscal ? 1 : 0;
        $contrato->comprobante_domicilio_servicio = $req->comprobante_domicilio_servicio ? 1 : 0;
        $contrato->cotizacion_ventas = $req->cotizacion_ventas ? 1 : 0;
        $contrato->contrato = $req->contrato ? 1 : 0;
        $contrato->orden_compra = $req->orden_compra ? 1 : 0;
        $contrato->observaciones = $req->contrato_observaciones;

        $contrato->save();

        // Datos generales
        $generales = New ClienteDatosGenerales;

        $generales->cliente_id = $item->id;
        $generales->num_cliente = $req->num_cliente;
        $generales->nombre_comercial = $req->nombre_comercial;
        $generales->fecha_inicio_servicio = $req->fecha_inicio_servicio;
        $generales->registro_patronal_nss = $req->registro_patronal_nss;
        $generales->fecha_terminacion_servicio = $req->fecha_terminacion_servicio;
        $generales->motivo_baja = $req->motivo_baja;
        $generales->pago_imss = $req->pago_imss ? 1 : 0;
        $generales->opinion_sat = $req->opinion_sat ? 1 : 0;
        $generales->dos_sobre_nomina = $req->dos_sobre_nomina ? 1 : 0;
        $generales->opinion_cumplimiento_imss = $req->opinion_cumplimiento_imss ? 1 : 0;
        $generales->declaracion_provisional_sat = $req->declaracion_provisional_sat ? 1 : 0;
        $generales->constancia_situacion_infonavit = $req->constancia_situacion_infonavit ? 1 : 0;
        $generales->nominas = $req->nominas ? 1 : 0;
        $generales->repse = $req->repse ? 1 : 0;
        $generales->observaciones = $req->generales_observaciones;

        $generales->save();

        // Facturas
        $factura = New ClienteFactura;

        $factura->cliente_id = $item->id;
        // $factura->nombre_comercial = $req->nombre_comercial;
        // $factura->domicilio_fiscal = $req->domicilio_fiscal;
        $factura->rfc = $req->rfc;
        $factura->no_elementos = $req->no_elementos;
        $factura->costo_unitario = $req->costo_unitario;
        $factura->precio_total_mensual_sin_iva = $req->precio_total_mensual_sin_iva;
        $factura->correo_para_envio_factura = $req->correo_para_envio_factura;
        $factura->nombre_contacto = $req->nombre_contacto;
        $factura->fecha_envio_factura = $req->fecha_envio_factura;
        $factura->cobran_dias_festivos = $req->cobran_dias_festivos;
        $factura->horario = $req->horario;
        $factura->observaciones_sobre_factura = $req->observaciones_sobre_factura;

        $factura->save();

        // Nóminas
        $nomina = New ClienteNomina;
        
        $nomina->cliente_id = $item->id;
        $nomina->domicilio_servicio = $req->domicilio_servicio;
        $nomina->telefono_servicio = $req->telefono_servicio;
        $nomina->numero_elementos = $req->numero_elementos;
        $nomina->tipo_servicio_1 = $req->tipo_servicio_1;
        $nomina->no_elementos_1 = $req->no_elementos_1;
        $nomina->salario_diario_1 = $req->salario_diario_1;
        $nomina->salario_mensual_1 = $req->salario_mensual_1;
        $nomina->horario_servicio_1 = $req->horario_servicio_1;
        $nomina->tipo_pago_1 = $req->tipo_pago_1;
        $nomina->tipo_servicio_2 = $req->tipo_servicio_2;
        $nomina->no_elementos_2 = $req->no_elementos_2;
        $nomina->salario_diario_2 = $req->salario_diario_2;
        $nomina->salario_mensual_2 = $req->salario_mensual_2;
        $nomina->horario_servicio_2 = $req->horario_servicio_2;
        $nomina->tipo_pago_2 = $req->tipo_pago_2;
        $nomina->tipo_servicio_3 = $req->tipo_servicio_3;
        $nomina->no_elementos_3 = $req->no_elementos_3;
        $nomina->salario_diario_3 = $req->salario_diario_3;
        $nomina->salario_mensual_3 = $req->salario_mensual_3;
        $nomina->horario_servicio_3 = $req->horario_servicio_3;
        $nomina->tipo_pago_3 = $req->tipo_pago_3;
        $nomina->tipo_servicio_4 = $req->tipo_servicio_4;
        $nomina->no_elementos_4 = $req->no_elementos_4;
        $nomina->salario_diario_4 = $req->salario_diario_4;
        $nomina->salario_mensual_4 = $req->salario_mensual_4;
        $nomina->horario_servicio_4 = $req->horario_servicio_4;
        $nomina->tipo_pago_4 = $req->tipo_pago_4;
        $nomina->tipo_servicio_5 = $req->tipo_servicio_5;
        $nomina->no_elementos_5 = $req->no_elementos_5;
        $nomina->salario_diario_5 = $req->salario_diario_5;
        $nomina->salario_mensual_5 = $req->salario_mensual_5;
        $nomina->horario_servicio_5 = $req->horario_servicio_5;
        $nomina->tipo_pago_5 = $req->tipo_pago_5;
        $nomina->tipo_servicio_6 = $req->tipo_servicio_6;
        $nomina->no_elementos_6 = $req->no_elementos_6;
        $nomina->salario_diario_6 = $req->salario_diario_6;
        $nomina->salario_mensual_6 = $req->salario_mensual_6;
        $nomina->horario_servicio_6 = $req->horario_servicio_6;
        $nomina->tipo_pago_6 = $req->tipo_pago_6;

        $nomina->observaciones = $req->nomina_observaciones;

        $nomina->save();

        // Ventas
        $venta = New ClienteVenta;
        
        $venta->cliente_id = $item->id;
        $venta->nombre_contacto_inicial = $req->nombre_contacto_inicial;
        $venta->telefono_contacto_inicial = $req->telefono_contacto_inicial;
        $venta->correo_contacto_inicial = $req->correo_contacto_inicial;
        $venta->cotizacion_firmada_autorizada = $req->cotizacion_firmada_autorizada;
        $venta->forma_en_que_se_concreto = $req->forma_en_que_se_concreto;
        $venta->fecha_cita = $req->fecha_cita;
        $venta->asistencia_cita = $req->asistencia_cita;
        $venta->quien_asistio_a_cita = $req->quien_asistio_a_cita;
        $venta->observaciones = $req->venta_observaciones;

        $venta->save();

        return response(['msg' => 'Registro guardado correctamente', 'url' => url('clientes/reload?s=activos'), 'status' => 'success'], 200);
    }

    /**
     * Edit a resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req)
    {
        $item = Cliente::find($req->id);

        if (! $item ) { return response(['msg' => 'No se encontró el registro a editar', 'status' => 'error'], 404); }

        $razonSocial = RazonSocial::find($req->razon_social_id);

        if (! $razonSocial ) { return response(['msg' => 'Seleccione una razón social para crear al cliente', 'status' => 'error'], 404); }

        // $photo = $this->upload_file($req->file('photo'), 'img/users/clientes', true);

        $item->razon_social_id = $razonSocial->id;
        $item->nombre = $req->nombre;
        $item->correo = $req->correo;
        $item->telefono = $req->telefono;
        $item->nombre_encargado = $req->nombre_encargado;
        $item->dias_credito = (int)$req->dias_credito;
        $item->domicilio = $req->domicilio;
        // $photo ? $item->photo = $photo : '';

        $item->save();

        // Cobranza
        $cobranza = ClienteCobranza::firstOrNew([ 'cliente_id' => $item->id]);

        // $cobranza->cliente_id = $item->id;
        // $cobranza->persona_pagos = $req->persona_pagos;
        // $cobranza->telefono = $req->telefono;
        // $cobranza->correo_electronico = $req->correo_electronico;
        // $cobranza->dias_de_credito = $req->dias_de_credito;
        $cobranza->dias_de_pago = $req->dias_de_pago;
        $cobranza->transferencia = $req->transferencia ? 1 : 0;
        $cobranza->deposito = $req->deposito ? 1 : 0;
        $cobranza->cheque = $req->cheque ? 1 : 0;
        $cobranza->efectivo = $req->efectivo ? 1 : 0;
        $cobranza->otro = $req->otro ? 1 : 0;
        $cobranza->otro_opcion = $req->otro ? $req->otro_opcion : null;
        $cobranza->nombre_banco_cliente = $req->nombre_banco_cliente;
        $cobranza->cuenta_pago_cliente = $req->cuenta_pago_cliente;
        $cobranza->clabe_pago_cliente = $req->clabe_pago_cliente;
        $cobranza->observaciones = $req->cobranza_observaciones;

        $cobranza->save();

        // Contrato
        $contrato = ClienteContrato::firstOrNew([ 'cliente_id' => $item->id]);

        $contrato->cliente_id = $item->id;
        $contrato->contrato_original_expediente = $req->contrato_original_expediente;
        $contrato->firmado = $req->firmado;
        $contrato->escaneado = $req->escaneado;
        $contrato->tipo_contrato = $req->tipo_contrato;
        $contrato->nombre_representante_legal = $req->nombre_representante_legal;
        $contrato->cargo = $req->cargo;
        $contrato->nombre_testigo = $req->nombre_testigo;
        $contrato->vigencia = $req->vigencia;
        $contrato->acta_constitutiva = $req->acta_constitutiva ? 1 : 0;
        $contrato->ine_representante = $req->ine_representante ? 1 : 0;
        $contrato->ine_testigo = $req->ine_testigo ? 1 : 0;
        $contrato->cedula_fiscal = $req->cedula_fiscal ? 1 : 0;
        $contrato->comprobante_domicilio_fiscal = $req->comprobante_domicilio_fiscal ? 1 : 0;
        $contrato->comprobante_domicilio_servicio = $req->comprobante_domicilio_servicio ? 1 : 0;
        $contrato->cotizacion_ventas = $req->cotizacion_ventas ? 1 : 0;
        $contrato->contrato = $req->contrato ? 1 : 0;
        $contrato->orden_compra = $req->orden_compra ? 1 : 0;
        $contrato->observaciones = $req->contrato_observaciones;

        $contrato->save();

        // Datos generales
        $generales = ClienteDatosGenerales::firstOrNew([ 'cliente_id' => $item->id]);

        $generales->cliente_id = $item->id;
        $generales->num_cliente = $req->num_cliente;
        $generales->nombre_comercial = $req->nombre_comercial;
        $generales->fecha_inicio_servicio = $req->fecha_inicio_servicio;
        $generales->registro_patronal_nss = $req->registro_patronal_nss;
        $generales->fecha_terminacion_servicio = $req->fecha_terminacion_servicio;
        $generales->motivo_baja = $req->motivo_baja;
        $generales->pago_imss = $req->pago_imss ? 1 : 0;
        $generales->opinion_sat = $req->opinion_sat ? 1 : 0;
        $generales->dos_sobre_nomina = $req->dos_sobre_nomina ? 1 : 0;
        $generales->opinion_cumplimiento_imss = $req->opinion_cumplimiento_imss ? 1 : 0;
        $generales->declaracion_provisional_sat = $req->declaracion_provisional_sat ? 1 : 0;
        $generales->constancia_situacion_infonavit = $req->constancia_situacion_infonavit ? 1 : 0;
        $generales->nominas = $req->nominas ? 1 : 0;
        $generales->repse = $req->repse ? 1 : 0;
        $generales->observaciones = $req->generales_observaciones;

        $generales->save();

        // Facturas
        $factura = ClienteFactura::firstOrNew([ 'cliente_id' => $item->id]);

        $factura->cliente_id = $item->id;
        // $factura->nombre_comercial = $req->nombre_comercial;
        // $factura->domicilio_fiscal = $req->domicilio_fiscal;
        $factura->rfc = $req->rfc;
        $factura->no_elementos = $req->no_elementos;
        $factura->costo_unitario = $req->costo_unitario;
        $factura->precio_total_mensual_sin_iva = $req->precio_total_mensual_sin_iva;
        $factura->correo_para_envio_factura = $req->correo_para_envio_factura;
        $factura->nombre_contacto = $req->nombre_contacto;
        $factura->fecha_envio_factura = $req->fecha_envio_factura;
        $factura->cobran_dias_festivos = $req->cobran_dias_festivos;
        $factura->horario = $req->horario;
        $factura->observaciones_sobre_factura = $req->observaciones_sobre_factura;

        $factura->save();

        // Nóminas
        $nomina = ClienteNomina::firstOrNew([ 'cliente_id' => $item->id]);
        
        $nomina->cliente_id = $item->id;
        $nomina->domicilio_servicio = $req->domicilio_servicio;
        $nomina->telefono_servicio = $req->telefono_servicio;
        $nomina->numero_elementos = $req->numero_elementos;
        $nomina->tipo_servicio_1 = $req->tipo_servicio_1;
        $nomina->no_elementos_1 = $req->no_elementos_1;
        $nomina->salario_diario_1 = $req->salario_diario_1;
        $nomina->salario_mensual_1 = $req->salario_mensual_1;
        $nomina->horario_servicio_1 = $req->horario_servicio_1;
        $nomina->tipo_pago_1 = $req->tipo_pago_1;
        $nomina->tipo_servicio_2 = $req->tipo_servicio_2;
        $nomina->no_elementos_2 = $req->no_elementos_2;
        $nomina->salario_diario_2 = $req->salario_diario_2;
        $nomina->salario_mensual_2 = $req->salario_mensual_2;
        $nomina->horario_servicio_2 = $req->horario_servicio_2;
        $nomina->tipo_pago_2 = $req->tipo_pago_2;
        $nomina->tipo_servicio_3 = $req->tipo_servicio_3;
        $nomina->no_elementos_3 = $req->no_elementos_3;
        $nomina->salario_diario_3 = $req->salario_diario_3;
        $nomina->salario_mensual_3 = $req->salario_mensual_3;
        $nomina->horario_servicio_3 = $req->horario_servicio_3;
        $nomina->tipo_pago_3 = $req->tipo_pago_3;
        $nomina->tipo_servicio_4 = $req->tipo_servicio_4;
        $nomina->no_elementos_4 = $req->no_elementos_4;
        $nomina->salario_diario_4 = $req->salario_diario_4;
        $nomina->salario_mensual_4 = $req->salario_mensual_4;
        $nomina->horario_servicio_4 = $req->horario_servicio_4;
        $nomina->tipo_pago_4 = $req->tipo_pago_4;
        $nomina->tipo_servicio_5 = $req->tipo_servicio_5;
        $nomina->no_elementos_5 = $req->no_elementos_5;
        $nomina->salario_diario_5 = $req->salario_diario_5;
        $nomina->salario_mensual_5 = $req->salario_mensual_5;
        $nomina->horario_servicio_5 = $req->horario_servicio_5;
        $nomina->tipo_pago_5 = $req->tipo_pago_5;
        $nomina->tipo_servicio_6 = $req->tipo_servicio_6;
        $nomina->no_elementos_6 = $req->no_elementos_6;
        $nomina->salario_diario_6 = $req->salario_diario_6;
        $nomina->salario_mensual_6 = $req->salario_mensual_6;
        $nomina->horario_servicio_6 = $req->horario_servicio_6;
        $nomina->tipo_pago_6 = $req->tipo_pago_6;
        $nomina->observaciones = $req->nomina_observaciones;

        $nomina->save();

        // Ventas
        $venta = ClienteVenta::firstOrNew([ 'cliente_id' => $item->id]);
        
        $venta->cliente_id = $item->id;
        $venta->nombre_contacto_inicial = $req->nombre_contacto_inicial;
        $venta->telefono_contacto_inicial = $req->telefono_contacto_inicial;
        $venta->correo_contacto_inicial = $req->correo_contacto_inicial;
        $venta->cotizacion_firmada_autorizada = $req->cotizacion_firmada_autorizada;
        $venta->forma_en_que_se_concreto = $req->forma_en_que_se_concreto;
        $venta->fecha_cita = $req->fecha_cita;
        $venta->asistencia_cita = $req->asistencia_cita;
        $venta->quien_asistio_a_cita = $req->quien_asistio_a_cita;
        $venta->observaciones = $req->venta_observaciones;

        $venta->save();
        
        $url = url('clientes?s=activos');
        
        if( $item->status_cliente_id == 1 ) { $url = url('clientes?s=activos'); }
        elseif( $item->status_cliente_id == 2 ) { $url = url('clientes?s=inactivos'); }
        elseif( $item->status_cliente_id == 3 ) { $url = url('clientes?s=legales'); }
        elseif( $item->status_cliente_id == 4 ) { $url = url('clientes?s=venados'); }

        return response(['msg' => 'Registro actualizado correctamente', 'url' => $url, 'status' => 'success'], 200);
    }

    /**
     * Removes permanently a resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $req)
    {
        $item = Cliente::whereIn('id', $req->ids)->first();

        if (! $item ) { return response(['msg' => 'Error al cambiar el status de '.$msg, 'status' => 'error', 'url' => url('clientes/reload')], 404); }

        $url = url('clientes?s=activos');
        
        if( $item->status_cliente_id == 1 ) { $url = url('clientes?s=activos'); }
        elseif( $item->status_cliente_id == 2 ) { $url = url('clientes?s=inactivos'); }
        elseif( $item->status_cliente_id == 3 ) { $url = url('clientes?s=legales'); }
        elseif( $item->status_cliente_id == 4 ) { $url = url('clientes?s=venados'); }

        $item->delete();

        return response(['msg' => 'Éxito eliminando el registro', 'url' => $url, 'status' => 'success'], 200);
    }

    /**
     * Change the status of the specified resource.
     *
     */
    public function changeStatus(Request $req)
    {
        $user = Cliente::filter([ 'user' => auth()->user(), 'cliente_id' => $req->cliente_id ])->first();
        if (! $user ) { return response(['msg' => 'Usuario inválido', 'status' => 'error'], 404); }

        $status = StatusCliente::where('id', $req->status_cliente_id)->first();
        if (! $status ) { return response(['msg' => 'Seleccione un status válido para continuar', 'status' => 'error'], 404); }

        if( $user->status_cliente_id == 1 ) { $url = url('clientes/reload?s=activos'); }
        elseif( $user->status_cliente_id == 2 ) { $url = url('clientes/reload?s=inactivos'); }
        elseif( $user->status_cliente_id == 3 ) { $url = url('clientes/reload?s=legales'); }

        $user->status_cliente_id = $status->id;

        $user->save();
        
        return response(['url' => $url, 'status' => 'success', 'msg' => 'Éxito cambiando el status del usuario'], 200);
    }

    /**
     * Genera un pdf con los datos del cliente.
     *
     * @return \Illuminate\Http\Response
     */
    public function generarPDF($id, $vista)
    {
        $cliente = Cliente::find($id);

        if(! $cliente ) { return view('errors.404'); }

        $arrayVistas = [
            'pdfs.clientes.generales', 'pdfs.clientes.nominas', 'pdfs.clientes.facturas', 
            'pdfs.clientes.ventas', 'pdfs.clientes.contratos', 'pdfs.clientes.cobranza'
        ];

        $vistaRuta = 'pdfs.clientes.'.$vista;

        if(! in_array($vistaRuta, $arrayVistas) ) { return view('errors.404'); }

        $pdf = PDF::loadView($vistaRuta, ['cliente' => $cliente])->setPaper('a4', 'landscape');
        
        // Nombre del pdf
        return $pdf->stream('doc.pdf');
    }

    /**
     * Use Excel instance to generate an state account.
     *
     * @return \Illuminate\Http\Response
     */
    public function generarEstadoCuenta(Request $req)
    {
        $req->request->add([ 'user' => auth()->user(), 'status' => 1 ]);

        $rows = array();
        $cliente = Cliente::find($req->cliente_id);
        // $items = Historial::filter_rows(auth()->user(), null, $req->cliente_id, null, 1, $req->fecha_inicio, $req->fecha_fin);

        $items = Historial::filter( $req->all() )->get();
        $totalFacturas = 0;
        $totalPagosCreditos = 0;
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

            $tipo = $num = $comentario = $fechaFac = $fechaPago = $fechaFormateada = '';
            $importe = 0;

            // Factura
            if ( $item->historiable_type == 'App\Factura' ) { 
                $tipo = 'Factura';
                $comentario = $item->historiable->comentarios_adicionales;
                $num = $item->historiable->numero_factura;
                $fechaFac = $item->historiable->fecha_facturacion;
                $fechaPago = '';
                $fechaFormateada = strftime('%d', strtotime($fechaFac)).' de '.strftime('%B', strtotime($fechaFac)). ' del '.strftime('%Y', strtotime($fechaFac));

                $totalFacturas += $item->historiable->importe;
            }
            // Nota de crédito o pagos
            elseif ( $item->historiable_type == 'App\Recibo' ) { 
                $tipo = $item->historiable->tipo_recibo->nombre;
                // $num = $item->historiable->folio;
                $num = $item->historiable->factura->numero_factura;
                $fechaFac = $item->historiable->factura->fecha_facturacion;
                $fechaPago = strftime('%d', strtotime($item->historiable->fecha_pago)).' de '.strftime('%B', strtotime($item->historiable->fecha_pago)). ' del '.strftime('%Y', strtotime($item->historiable->fecha_pago));
                $fechaFormateada = strftime('%d', strtotime($fechaFac)).' de '.strftime('%B', strtotime($fechaFac)). ' del '.strftime('%Y', strtotime($fechaFac));

                $totalPagosCreditos += $item->historiable->importe;
            }

            $importe = $item->historiable->importe;

            $rows [] = [
                'Cliente'              => $item->cliente->nombre,
                'Tipo de movimiento'   => $tipo,
                'Número / Folio'       => $num,
                'Importe'              => $importe,
                'Fecha de facturación' => $fechaFormateada,
                'Fecha de pago'        => $fechaPago,
                'Comentario'           => $comentario,
            ];
        }

        Excel::create('Estado de cuenta', function($excel) use ($rows, $totalFacturas, $totalPagosCreditos, $cliente, $periodo) {
            $excel->sheet('Hoja 1', function($sheet) use($rows, $totalFacturas, $totalPagosCreditos, $cliente, $periodo) {

                $sheet->cell('A1', function($cell) use ($cliente) {
                    if ( $cliente ) {
                        $cell->setFontWeight('bold');
                        $cell->setFontSize(12);
                        $cell->setValue('Razón social: '.$cliente->razon_social->nombre);
                    }
                });

                $sheet->cell('A2', function($cell) use($periodo) {
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(12);
                    $cell->setValue('Periodo: '.$periodo);
                });

                $sheet->cell('A3', function($cell) use ($totalFacturas, $totalPagosCreditos) {
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(12);
                    $cell->setValue('Balance: $'.number_format( $totalFacturas - $totalPagosCreditos, 2));
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
    
    /**
     * Import multiple resources by an excel instance.
     *
     * @return \Illuminate\Http\Response
     */
    public function import(Request $req)
    {
        if ($req->hasFile('archivo-excel')) {
            $path = $req->file('archivo-excel')->getRealPath();
            $extension = $req->file('archivo-excel')->getClientOriginalExtension();

            $data = Excel::load($path, function($reader) {
                $reader->setDateFormat('Y-m-d');
            })->get();


            if (!empty($data) && $data->count()) {
                foreach ($data as $value) {
                    // dd($value);
                    // if (! $value->people ) continue ;// If people is not defined...
                    
                    $razon = RazonSocial::where('nombre', $value->razon_social)->first();

                    if (! $razon ) continue ;#Declinar registro si no hay razón social...

                    $cliente = Cliente::updateOrCreate(
                        ['razon_social_id' => $razon->id, 'nombre' => $value->nombre, 'telefono' => $value->telefono],
                        [
                            'razon_social_id'  => $razon->id,
                            'nombre'           => $value->nombre,
                            'domicilio'        => $value->domicilio,
                            'telefono'         => $value->telefono,
                            'correo'           => $value->correo,
                            'nombre_encargado' => $value->nombre_encargado,
                            'dias_credito'     => (int)$value->dias_credito,
                        ]
                    );
                    // $cliente = Cliente::insert(
                    //     [
                    //         'razon_social_id'  => $razon->id,
                    //         'nombre'           => $value->nombre,
                    //         'domicilio'        => $value->domicilio,
                    //         'telefono'         => $value->telefono,
                    //         'correo'           => $value->correo,
                    //         'nombre_encargado' => $value->nombre_encargado,
                    //         'dias_credito'     => (int)$value->dias_credito,
                    //     ]
                    // );
                }
            } else {
                return ['status' => false, 'msg' => 'El excel esta vacío'];
            }
            return ['status' => true, 'msg' => 'Se han importado los registros del excel'];
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

        $req->request->add([ 'user' => auth()->user() ]);

        $items = Cliente::filter( $req->all() )->get();

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
