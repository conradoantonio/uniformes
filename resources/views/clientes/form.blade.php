@extends('layouts.main')

@section('content')
<section class="admin-content">
    <div class=" bg-dark m-b-30 bg-stars">
        <div class="container">
            <div class="row">
                <div class="col-md-6 m-auto text-white p-t-40 p-b-90">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-b-0 bg-transparent ol-breadcrum">
                            <li class="breadcrumb-item"><a href="javascript:;">Clientes</a></li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-6 m-auto text-white p-t-40 p-b-90">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-b-0 bg-transparent ol-breadcrum float-right">
                            <li class="breadcrumb-item active" aria-current="page">Formulario</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="container pull-up">
        <div class="row">
            <div class="col-lg-12 m-b-30">
                <div class="card">
                    <div class="card-header">
                        <h2 class="">Ingresa los datos del cliente</h2>
                    </div>
                    <div class="card-body">
                        <form id="form-data" action="{{url('clientes/'.($item ? 'update' : 'save'))}}" onsubmit="return false;" enctype="multipart/form-data" method="POST" autocomplete="off" data-ajax-type="ajax-form" data-column="0" data-refresh="" data-redirect="1" data-table_id="example3" data-container_id="table-container">
                            <div class="form-row">
                                <div class="form-group" style="display: none;">
                                    <label>ID</label>
                                    <input type="text" class="form-control" name="id" value="{{$item ? $item->id : ''}}">
                                </div>
                            </div>
                            <ul class="nav nav-pills nav-justified" id="myTab3" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="datos-generales-tab" data-toggle="tab" href="#datos-generales" role="tab" aria-controls="datos generales" aria-selected="false">Datos generales</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="facturas-tab" data-toggle="tab" href="#facturas" role="tab" aria-controls="facturas" aria-selected="false">Facturas</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="contratos-tab" data-toggle="tab" href="#contratos" role="tab" aria-controls="contratos" aria-selected="false">Contratos</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="ventas-tab" data-toggle="tab" href="#ventas" role="tab" aria-controls="ventas" aria-selected="false">Ventas</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="cobranza-tab" data-toggle="tab" href="#cobranza" role="tab" aria-controls="cobranza" aria-selected="true">Cobranza</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="nomina-tab" data-toggle="tab" href="#nomina" role="tab" aria-controls="nomina" aria-selected="false">Nóminas</a>
                                </li>
                                
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade active show" id="datos-generales" role="tabpanel" aria-labelledby="datos-generales-tab">
                                    
                                    <div class="form-row m-t-15">
                                        @if( $item )
                                            <div class="form-group col-md-12 text-center">
                                                <a href="{{url('clientes/'.$item->id.'/exportar/pdf/generales')}}" class="btn btn-dark btn-sm descargar-pdf" target="_blank" data-toggle="tooltip" data-placement="top" title="Descargar pdf"><i class="mdi mdi-file-pdf"> Descargar pdf</i></a>
                                            </div>
                                        @endif
                                        <div class="form-group col-md-12">
                                            <label class="control-label" for="type">Razón social*</label>
                                            <select id="razon_social_id" name="razon_social_id" class="form-control not-empty" data-msg="Razón social">
                                                <option value="" selected>Seleccione una opción</option>
                                                @if ( $item )
                                                    @foreach($razones as $razon)
                                                        <option value="{{$razon->id}}" {{$item->razon_social_id == $razon->id ? 'selected' : ''}}>{{$razon->nombre}}</option>
                                                    @endforeach
                                                @else
                                                    @foreach($razones as $razon)
                                                        <option value="{{$razon->id}}">{{$razon->nombre}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label>Nombre comercial*</label>
                                            <input type="text" class="form-control not-empty" name="nombre_comercial" value="{{$item && $item->generales ? $item->generales->nombre_comercial : ''}}" data-msg="Nombre comercial">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>No. Cliente</label>
                                            <input type="text" class="form-control" name="num_cliente" value="{{$item && $item->generales ? $item->generales->num_cliente : ''}}" data-msg="No. Cliente">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Fecha inicio de servicio</label>
                                            <input type="text" class="form-control date-picker" name="fecha_inicio_servicio" value="{{$item && $item->generales ? $item->generales->fecha_inicio_servicio : ''}}">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Registro patronal</label>
                                            <input type="text" class="form-control" name="registro_patronal_nss" value="{{$item && $item->generales ? $item->generales->registro_patronal_nss : ''}}">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>Fecha terminación de servicio</label>
                                            <input type="text" class="form-control date-picker" name="fecha_terminacion_servicio" value="{{$item && $item->generales ? $item->generales->fecha_terminacion_servicio : ''}}">
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label>Motivo de baja</label>
                                            <textarea name="motivo_baja" class="form-control" rows="4">{{$item && $item->generales ? $item->generales->motivo_baja : ''}}</textarea>
                                        </div>
                                        
                                        <div class="form-group col-md-12">
                                            <label>Envío de documentos mensuales</label>
                                        </div>
                                        <hr>
                                        <div class="form-group col-md-4">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" {{$item && $item->generales && $item->generales->pago_imss ? 'checked' : ''}} id="pago_imss" name="pago_imss">
                                                <label class="custom-control-label" for="pago_imss">Pago de IMSS</label>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" {{$item && $item->generales && $item->generales->opinion_sat ? 'checked' : ''}} id="opinion_sat" name="opinion_sat">
                                                <label class="custom-control-label" for="opinion_sat">Opinión SAT</label>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" {{$item && $item->generales && $item->generales->dos_sobre_nomina ? 'checked' : ''}} id="dos_sobre_nomina" name="dos_sobre_nomina">
                                                <label class="custom-control-label" for="dos_sobre_nomina">2% sobre nómina</label>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" {{$item && $item->generales && $item->generales->opinion_cumplimiento_imss ? 'checked' : ''}} id="opinion_cumplimiento_imss" name="opinion_cumplimiento_imss">
                                                <label class="custom-control-label" for="opinion_cumplimiento_imss">Opinión cumplimiento IMSS</label>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" {{$item && $item->generales && $item->generales->declaracion_provisional_sat ? 'checked' : ''}} id="declaracion_provisional_sat" name="declaracion_provisional_sat">
                                                <label class="custom-control-label" for="declaracion_provisional_sat">Declaración provisional SAT</label>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" {{$item && $item->generales && $item->generales->constancia_situacion_infonavit ? 'checked' : ''}} id="constancia_situacion_infonavit" name="constancia_situacion_infonavit">
                                                <label class="custom-control-label" for="constancia_situacion_infonavit">Constancia situación infonavit</label>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" {{$item && $item->generales && $item->generales->nominas ? 'checked' : ''}} id="nominas" name="nominas">
                                                <label class="custom-control-label" for="nominas">Nóminas</label>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" {{$item && $item->generales && $item->generales->repse ? 'checked' : ''}} id="repse" name="repse">
                                                <label class="custom-control-label" for="repse">Repse</label>
                                            </div>
                                        </div>
                                        

                                        <div class="form-group col-md-12">
                                            <label>Observaciones</label>
                                            <textarea name="generales_observaciones" class="form-control" rows="4">{{$item && $item->generales ? $item->generales->observaciones : ''}}</textarea>
                                        </div>
                                        
                                    </div>

                                </div>
                                <div class="tab-pane fade" id="facturas" role="tabpanel" aria-labelledby="facturas-tab">
                                    
                                    <div class="form-row m-t-15">
                                        @if( $item )
                                            <div class="form-group col-md-12 text-center">
                                                <a href="{{url('clientes/'.$item->id.'/exportar/pdf/facturas')}}" class="btn btn-dark btn-sm descargar-pdf" target="_blank" data-toggle="tooltip" data-placement="top" title="Descargar pdf"><i class="mdi mdi-file-pdf"> Descargar pdf</i></a>
                                            </div>
                                        @endif

                                        <div class="form-group col-md-12">
                                            <label>Nombre razon social</label>
                                            <input type="text" class="form-control" name="nombre" value="{{$item ? $item->nombre : ''}}" data-msg="Nombre razon social">
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label>Domicilio fiscal</label>
                                            <textarea type="text" class="form-control" name="domicilio" placeholder="" data-msg="Domicilio">{{$item ? $item->domicilio : ''}}</textarea>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>RFC</label>
                                            <input type="text" class="form-control" name="rfc" maxlength="13" value="{{$item && $item->factura ? $item->factura->rfc : ''}}">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>No. Elementos</label>
                                            <input type="text" class="form-control" name="no_elementos" value="{{$item && $item->factura ? $item->factura->no_elementos : ''}}">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Costo unitario</label>
                                            <input type="text" class="form-control" name="costo_unitario" value="{{$item && $item->factura ? $item->factura->costo_unitario : ''}}">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Precio total mensual sin IVA</label>
                                            <input type="text" class="form-control" name="precio_total_mensual_sin_iva" value="{{$item && $item->factura ? $item->factura->precio_total_mensual_sin_iva : ''}}">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Correo para envío de factura</label>
                                            <input type="text" class="form-control" name="correo_para_envio_factura" value="{{$item && $item->factura ? $item->factura->correo_para_envio_factura : ''}}">
                                        </div>
                                        <div class="form-group col-md-8">
                                            <label>Nombre del contacto</label>
                                            <input type="text" class="form-control" name="nombre_contacto" value="{{$item && $item->factura ? $item->factura->nombre_contacto : ''}}">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Fecha envío factura</label>
                                            <input type="text" class="form-control" name="fecha_envio_factura" value="{{$item && $item->factura ? $item->factura->fecha_envio_factura : ''}}">
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label class="control-label" for="type">Se cobran días festivos</label>
                                            <select name="cobran_dias_festivos" class="form-control" data-msg="Se cobran días festivos">
                                                <option value="" selected>Seleccione una opción</option>
                                                <option value="1" {{$item && $item->factura && $item->factura->cobran_dias_festivos == 1 ? 'selected' : ''}}>Si</option>
                                                <option value="0" {{$item && $item->factura && $item->factura->cobran_dias_festivos == 0 ? 'selected' : ''}}>No</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label>Horario</label>
                                            <textarea name="horario" class="form-control" rows="4">{{$item && $item->factura ? $item->factura->horario : ''}}</textarea>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label>Observaciones</label>
                                            <textarea name="observaciones_sobre_factura" class="form-control" rows="4">{{$item && $item->factura ? $item->factura->observaciones_sobre_factura : ''}}</textarea>
                                        </div>
                                        
                                    </div>

                                </div>
                                <div class="tab-pane fade" id="contratos" role="tabpanel" aria-labelledby="contratos-tab">

                                    <div class="form-row m-t-15">
                                        @if( $item )
                                            <div class="form-group col-md-12 text-center">
                                                <a href="{{url('clientes/'.$item->id.'/exportar/pdf/contratos')}}" class="btn btn-dark btn-sm descargar-pdf" target="_blank" data-toggle="tooltip" data-placement="top" title="Descargar pdf"><i class="mdi mdi-file-pdf"> Descargar pdf</i></a>
                                            </div>
                                        @endif

                                        <div class="form-group col-md-6">
                                            <label class="control-label" for="type">Contrato original en expediente</label>
                                            <select name="contrato_original_expediente" class="form-control" data-msg="Contrato original en expediente">
                                                <option value="" selected>Seleccione una opción</option>
                                                <option value="Si" {{$item && $item->contrato && $item->contrato->contrato_original_expediente == 'Si' ? 'selected' : ''}}>Si</option>
                                                <option value="No" {{$item && $item->contrato && $item->contrato->contrato_original_expediente == 'No' ? 'selected' : ''}}>No</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="control-label" for="type">Firmado</label>
                                            <select name="firmado" class="form-control" data-msg="Firmado">
                                                <option value="" selected>Seleccione una opción</option>
                                                <option value="Si" {{$item && $item->contrato && $item->contrato->firmado == 'Si' ? 'selected' : ''}}>Si</option>
                                                <option value="No" {{$item && $item->contrato && $item->contrato->firmado == 'No' ? 'selected' : ''}}>No</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="control-label" for="type">Escaneado</label>
                                            <select name="escaneado" class="form-control" data-msg="Escaneado">
                                                <option value="" selected>Seleccione una opción</option>
                                                <option value="Si" {{$item && $item->contrato && $item->contrato->escaneado == 'Si' ? 'selected' : ''}}>Si</option>
                                                <option value="No" {{$item && $item->contrato && $item->contrato->escaneado == 'No' ? 'selected' : ''}}>No</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="control-label" for="type">Tipo de contrato</label>
                                            <select name="tipo_contrato" class="form-control" data-msg="Tipo de contrato">
                                                <option value="" selected>Seleccione una opción</option>
                                                <option value="Definido" {{$item && $item->contrato && $item->contrato->tipo_contrato == 'Definido' ? 'selected' : ''}}>Definido</option>
                                                <option value="Indefinido" {{$item && $item->contrato && $item->contrato->tipo_contrato == 'Indefinido' ? 'selected' : ''}}>Indefinido</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-8">
                                            <label>Nombre del representante legal</label>
                                            <input type="text" class="form-control" name="nombre_representante_legal" value="{{$item && $item->contrato ? $item->contrato->nombre_representante_legal : ''}}">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Cargo</label>
                                            <input type="text" class="form-control" name="cargo" value="{{$item && $item->contrato ? $item->contrato->cargo : ''}}">
                                        </div>
                                        <div class="form-group col-md-8">
                                            <label>Nombre del testigo</label>
                                            <input type="text" class="form-control" name="nombre_testigo" value="{{$item && $item->contrato ? $item->contrato->nombre_testigo : ''}}">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Vigencia</label>
                                            <input type="text" class="form-control" name="vigencia" value="{{$item && $item->contrato ? $item->contrato->vigencia : ''}}">
                                        </div>
                                        
                                        <div class="form-group col-md-12">
                                            <label>Indicador de documentos escaneados</label>
                                        </div>
                                        <hr>
                                        <div class="form-group col-md-4">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" {{$item && $item->contrato&& $item->contrato->acta_constitutiva ? 'checked' : ''}} id="acta_constitutiva" name="acta_constitutiva">
                                                <label class="custom-control-label" for="acta_constitutiva">Acta constitutiva</label>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" {{$item && $item->contrato&& $item->contrato->ine_representante ? 'checked' : ''}} id="ine_representante" name="ine_representante">
                                                <label class="custom-control-label" for="ine_representante">INE representante</label>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" {{$item && $item->contrato && $item->contrato->ine_testigo ? 'checked' : ''}} id="ine_testigo" name="ine_testigo">
                                                <label class="custom-control-label" for="ine_testigo">INE testigo</label>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" {{$item && $item->contrato && $item->contrato->cedula_fiscal ? 'checked' : ''}} id="cedula_fiscal" name="cedula_fiscal">
                                                <label class="custom-control-label" for="cedula_fiscal">Cédula fiscal</label>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" {{$item && $item->contrato && $item->contrato->comprobante_domicilio_fiscal ? 'checked' : ''}} id="comprobante_domicilio_fiscal" name="comprobante_domicilio_fiscal">
                                                <label class="custom-control-label" for="comprobante_domicilio_fiscal">Comprobante de domicilio fiscal</label>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" {{$item && $item->contrato && $item->contrato->comprobante_domicilio_servicio ? 'checked' : ''}} id="comprobante_domicilio_servicio" name="comprobante_domicilio_servicio">
                                                <label class="custom-control-label" for="comprobante_domicilio_servicio">Comprobante de domicilio de servicio</label>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" {{$item && $item->contrato && $item->contrato->cotizacion_ventas ? 'checked' : ''}} id="cotizacion_ventas" name="cotizacion_ventas">
                                                <label class="custom-control-label" for="cotizacion_ventas">Cotización de ventas</label>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" {{$item && $item->contrato && $item->contrato->contrato ? 'checked' : ''}} id="contrato" name="contrato">
                                                <label class="custom-control-label" for="contrato">Contrato</label>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" {{$item && $item->contrato && $item->contrato->orden_compra ? 'checked' : ''}} id="orden_compra" name="orden_compra">
                                                <label class="custom-control-label" for="orden_compra">Orden de compra</label>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label>Observaciones</label>
                                            <textarea name="contrato_observaciones" class="form-control" rows="4">{{$item && $item->contrato ? $item->contrato->observaciones : ''}}</textarea>
                                        </div>
                                        
                                    </div>

                                </div>
                                <div class="tab-pane fade" id="ventas" role="tabpanel" aria-labelledby="ventas-tab">
                                    
                                    <div class="form-row m-t-15">
                                        @if( $item )
                                            <div class="form-group col-md-12 text-center">
                                                <a href="{{url('clientes/'.$item->id.'/exportar/pdf/ventas')}}" class="btn btn-dark btn-sm descargar-pdf" target="_blank" data-toggle="tooltip" data-placement="top" title="Descargar pdf"><i class="mdi mdi-file-pdf"> Descargar pdf</i></a>
                                            </div>
                                        @endif

                                        <div class="form-group col-md-6">
                                            <label>Nombre contacto inicial</label>
                                            <input type="text" class="form-control" name="nombre_contacto_inicial" value="{{$item && $item->venta ? $item->venta->nombre_contacto_inicial : ''}}">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Teléfono contacto inicial</label>
                                            <input type="text" class="form-control" name="telefono_contacto_inicial" value="{{$item && $item->venta ? $item->venta->telefono_contacto_inicial : ''}}">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Correo contacto inicial</label>
                                            <input type="text" class="form-control" name="correo_contacto_inicial" value="{{$item && $item->venta ? $item->venta->correo_contacto_inicial : ''}}">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Cotización firmada y autorizada</label>
                                            <input type="text" class="form-control" name="cotizacion_firmada_autorizada" value="{{$item && $item->venta ? $item->venta->cotizacion_firmada_autorizada : ''}}">
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label>Forma en que se concreto</label>
                                            <input type="text" class="form-control" name="forma_en_que_se_concreto" value="{{$item && $item->venta ? $item->venta->forma_en_que_se_concreto : ''}}">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Fecha cita</label>
                                            <input type="text" class="form-control date-picker" name="fecha_cita" value="{{$item && $item->venta ? $item->venta->fecha_cita : ''}}">
                                        </div>
                                       
                                        <div class="form-group col-md-3">
                                            <label class="control-label" for="type">Asistencia a cita</label>
                                            <select name="asistencia_cita" class="form-control" data-msg="Tipo de pago">
                                                <option value="" selected>Seleccione una opción</option>
                                                <option value="1" {{$item && $item->venta && $item->venta->asistencia_cita == 1 ? 'selected' : ''}}>Si</option>
                                                <option value="0" {{$item && $item->venta && $item->venta->asistencia_cita == 0 ? 'selected' : ''}}>No</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>¿Quién asistió a la cita?</label>
                                            <input type="text" class="form-control" name="quien_asistio_a_cita" value="{{$item && $item->venta ? $item->venta->quien_asistio_a_cita : ''}}">
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label>Observaciones</label>
                                            <textarea name="venta_observaciones" class="form-control" rows="4">{{$item && $item->venta ? $item->venta->observaciones : ''}}</textarea>
                                        </div>
                                        
                                    </div>

                                </div>
                                <div class="tab-pane fade" id="cobranza" role="tabpanel" aria-labelledby="cobranza-tab">
                                    
                                    <div class="form-row m-t-15">
                                        @if( $item )
                                            <div class="form-group col-md-12 text-center">
                                                <a href="{{url('clientes/'.$item->id.'/exportar/pdf/cobranza')}}" class="btn btn-dark btn-sm descargar-pdf" target="_blank" data-vista="cobranza" href="" data-toggle="tooltip" data-placement="top" title="Descargar pdf"><i class="mdi mdi-file-pdf"> Descargar pdf</i></a>
                                            </div>
                                        @endif
                                        <div class="form-group col-md-12">
                                            <label>Persona de pagos</label>
                                            <input type="text" class="form-control" name="nombre_encargado" value="{{$item ? $item->nombre_encargado : ''}}" data-msg="Persona de pagos">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Teléfono</label>
                                            <input type="text" class="form-control" name="telefono" value="{{$item ? $item->telefono : ''}}" data-msg="Teléfono">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Correo</label>
                                            <input type="text" class="form-control" name="correo" value="{{$item ? $item->correo : ''}}" data-msg="Correo">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Días de crédito</label>
                                            <input type="text" class="form-control" name="dias_credito" value="{{$item ? $item->dias_credito : ''}}" data-msg="Días de crédito">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Días de pago</label>
                                            <input type="text" class="form-control" name="dias_de_pago" value="{{$item && $item->cobranza ? $item->cobranza->dias_de_pago : ''}}" data-msg="Días de pago">
                                        </div>
                                        
                                        <div class="form-group col-md-12">
                                            <label>Forma de pago</label>
                                        </div>
                                        <hr>
                                        <div class="form-group col-md-4">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" {{$item && $item->cobranza && $item->cobranza->transferencia ? 'checked' : ''}} id="transferencia" name="transferencia">
                                                <label class="custom-control-label" for="transferencia">Transferencia</label>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" {{$item && $item->cobranza && $item->cobranza->deposito ? 'checked' : ''}} id="deposito" name="deposito">
                                                <label class="custom-control-label" for="deposito">Depósito</label>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" {{$item && $item->cobranza && $item->cobranza->cheque ? 'checked' : ''}} id="cheque" name="cheque">
                                                <label class="custom-control-label" for="cheque">Cheque</label>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" {{$item && $item->cobranza && $item->cobranza->efectivo ? 'checked' : ''}} id="efectivo" name="efectivo">
                                                <label class="custom-control-label" for="efectivo">Efectivo</label>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-1">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" {{$item && $item->cobranza && $item->cobranza->otro ? 'checked' : ''}} id="otro" name="otro">
                                                <label class="custom-control-label" for="otro">Otro</label>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-7">
                                            {{-- <label>Forma de pago adicional</label> --}}
                                            <input type="text" class="form-control {{$item && $item->cobranza && $item->cobranza->otro == 1 ? '' : 'd-none' }}" name="otro_opcion" value="{{$item && $item->cobranza ? $item->cobranza->otro_opcion : ''}}">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Nombre del banco del cliente</label>
                                            <input type="text" class="form-control" name="nombre_banco_cliente" value="{{$item && $item->cobranza ? $item->cobranza->nombre_banco_cliente : ''}}">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Cuenta de pago del cliente</label>
                                            <input type="text" class="form-control" name="cuenta_pago_cliente" value="{{$item && $item->cobranza ? $item->cobranza->cuenta_pago_cliente : ''}}">
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label>Clabe de pago del cliente</label>
                                            <input type="text" class="form-control" name="clabe_pago_cliente" value="{{$item && $item->cobranza ? $item->cobranza->clabe_pago_cliente : ''}}">
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label>Observaciones</label>
                                            <textarea name="cobranza_observaciones" class="form-control" rows="4">{{$item && $item->cobranza ? $item->cobranza->observaciones : ''}}</textarea>
                                        </div>
                                        
                                    </div>

                                </div>
                                <div class="tab-pane fade" id="nomina" role="tabpanel" aria-labelledby="nomina-tab">
                                    
                                    <div class="form-row m-t-15">
                                        @if( $item )
                                            <div class="form-group col-md-12 text-center">
                                                <a href="{{url('clientes/'.$item->id.'/exportar/pdf/nominas')}}" class="btn btn-dark btn-sm descargar-pdf" target="_blank" data-toggle="tooltip" data-placement="top" title="Descargar pdf"><i class="mdi mdi-file-pdf"> Descargar pdf</i></a>
                                            </div>
                                        @endif

                                        <div class="form-group col-md-12">
                                            <label>Domicilio del servicio</label>
                                            <input type="text" class="form-control" name="domicilio_servicio" value="{{$item && $item->nomina ? $item->nomina->domicilio_servicio : ''}}">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Teléfono del servicio</label>
                                            <input type="text" class="form-control" name="telefono_servicio" value="{{$item && $item->nomina ? $item->nomina->telefono_servicio : ''}}">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Número de elementos</label>
                                            <input type="text" class="form-control" name="numero_elementos" value="{{$item && $item->nomina ? $item->nomina->numero_elementos : ''}}">
                                        </div>
                                        
                                        <div class="col-md-12 m-t-5" style="font-weight: bolder;">
                                            <label>Servicio 1</label>
                                        </div>
                                        <hr>
                                        <div class="form-group col-md-3">
                                            <label>Tipo de servicio</label>
                                            <input type="text" class="form-control" name="tipo_servicio_1" value="{{$item && $item->nomina ? $item->nomina->tipo_servicio_1 : ''}}">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>No. de elementos</label>
                                            <input type="text" class="form-control" name="no_elementos_1" value="{{$item && $item->nomina ? $item->nomina->no_elementos_1 : ''}}">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Salario diario</label>
                                            <input type="text" class="form-control" name="salario_diario_1" value="{{$item && $item->nomina ? $item->nomina->salario_diario_1 : ''}}">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Salario mensual</label>
                                            <input type="text" class="form-control" name="salario_mensual_1" value="{{$item && $item->nomina ? $item->nomina->salario_mensual_1 : ''}}">
                                        </div>
                                        <div class="form-group col-md-8">
                                            <label>Horario servicio</label>
                                            <input type="text" class="form-control" name="horario_servicio_1" value="{{$item && $item->nomina ? $item->nomina->horario_servicio_1 : ''}}">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="control-label" for="type">Tipo de pago</label>
                                            <select name="tipo_pago_1" class="form-control" data-msg="Tipo de pago">
                                                <option value="" selected>Seleccione una opción</option>
                                                <option value="Semanal" {{$item && $item->nomina && $item->nomina->tipo_pago_1 == 'Semanal' ? 'selected' : ''}}>Semanal</option>
                                                <option value="Quincenal" {{$item && $item->nomina && $item->nomina->tipo_pago_1 == 'Quincenal' ? 'selected' : ''}}>Quincenal</option>
                                            </select>
                                        </div>

                                        <div class="col-md-12 m-t-5" style="font-weight: bolder;">
                                            <label>Servicio 2</label>
                                        </div>
                                        <hr>
                                        <div class="form-group col-md-3">
                                            <label>Tipo de servicio</label>
                                            <input type="text" class="form-control" name="tipo_servicio_2" value="{{$item && $item->nomina ? $item->nomina->tipo_servicio_2 : ''}}">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>No. de elementos</label>
                                            <input type="text" class="form-control" name="no_elementos_2" value="{{$item && $item->nomina ? $item->nomina->no_elementos_2 : ''}}">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Salario diario</label>
                                            <input type="text" class="form-control" name="salario_diario_2" value="{{$item && $item->nomina ? $item->nomina->salario_diario_2 : ''}}">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Salario mensual</label>
                                            <input type="text" class="form-control" name="salario_mensual_2" value="{{$item && $item->nomina ? $item->nomina->salario_mensual_2 : ''}}">
                                        </div>
                                        <div class="form-group col-md-8">
                                            <label>Horario servicio</label>
                                            <input type="text" class="form-control" name="horario_servicio_2" value="{{$item && $item->nomina ? $item->nomina->horario_servicio_2 : ''}}">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="control-label" for="type">Tipo de pago</label>
                                            <select name="tipo_pago_2" class="form-control" data-msg="Tipo de pago">
                                                <option value="" selected>Seleccione una opción</option>
                                                <option value="Semanal" {{$item && $item->nomina && $item->nomina->tipo_pago_2 == 'Semanal' ? 'selected' : ''}}>Semanal</option>
                                                <option value="Quincenal" {{$item && $item->nomina && $item->nomina->tipo_pago_2 == 'Quincenal' ? 'selected' : ''}}>Quincenal</option>
                                            </select>
                                        </div>

                                        <div class="col-md-12 m-t-5" style="font-weight: bolder;">
                                            <label>Servicio 3</label>
                                        </div>
                                        <hr>
                                        <div class="form-group col-md-3">
                                            <label>Tipo de servicio</label>
                                            <input type="text" class="form-control" name="tipo_servicio_3" value="{{$item && $item->nomina ? $item->nomina->tipo_servicio_3 : ''}}">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>No. de elementos</label>
                                            <input type="text" class="form-control" name="no_elementos_3" value="{{$item && $item->nomina ? $item->nomina->no_elementos_3 : ''}}">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Salario diario</label>
                                            <input type="text" class="form-control" name="salario_diario_3" value="{{$item && $item->nomina ? $item->nomina->salario_diario_3 : ''}}">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Salario mensual</label>
                                            <input type="text" class="form-control" name="salario_mensual_3" value="{{$item && $item->nomina ? $item->nomina->salario_mensual_3 : ''}}">
                                        </div>
                                        <div class="form-group col-md-8">
                                            <label>Horario servicio</label>
                                            <input type="text" class="form-control" name="horario_servicio_3" value="{{$item && $item->nomina ? $item->nomina->horario_servicio_3 : ''}}">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="control-label" for="type">Tipo de pago</label>
                                            <select name="tipo_pago_3" class="form-control" data-msg="Tipo de pago">
                                                <option value="" selected>Seleccione una opción</option>
                                                <option value="Semanal" {{$item && $item->nomina && $item->nomina->tipo_pago_3 == 'Semanal' ? 'selected' : ''}}>Semanal</option>
                                                <option value="Quincenal" {{$item && $item->nomina && $item->nomina->tipo_pago_3 == 'Quincenal' ? 'selected' : ''}}>Quincenal</option>
                                            </select>
                                        </div>

                                        <div class="col-md-12 m-t-5" style="font-weight: bolder;">
                                            <label>Servicio 4</label>
                                        </div>
                                        <hr>
                                        <div class="form-group col-md-3">
                                            <label>Tipo de servicio</label>
                                            <input type="text" class="form-control" name="tipo_servicio_4" value="{{$item && $item->nomina ? $item->nomina->tipo_servicio_4 : ''}}">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>No. de elementos</label>
                                            <input type="text" class="form-control" name="no_elementos_4" value="{{$item && $item->nomina ? $item->nomina->no_elementos_4 : ''}}">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Salario diario</label>
                                            <input type="text" class="form-control" name="salario_diario_4" value="{{$item && $item->nomina ? $item->nomina->salario_diario_4 : ''}}">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Salario mensual</label>
                                            <input type="text" class="form-control" name="salario_mensual_4" value="{{$item && $item->nomina ? $item->nomina->salario_mensual_4 : ''}}">
                                        </div>
                                        <div class="form-group col-md-8">
                                            <label>Horario servicio</label>
                                            <input type="text" class="form-control" name="horario_servicio_4" value="{{$item && $item->nomina ? $item->nomina->horario_servicio_4 : ''}}">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="control-label" for="type">Tipo de pago</label>
                                            <select name="tipo_pago_4" class="form-control" data-msg="Tipo de pago">
                                                <option value="" selected>Seleccione una opción</option>
                                                <option value="Semanal" {{$item && $item->nomina && $item->nomina->tipo_pago_4 == 'Semanal' ? 'selected' : ''}}>Semanal</option>
                                                <option value="Quincenal" {{$item && $item->nomina && $item->nomina->tipo_pago_4 == 'Quincenal' ? 'selected' : ''}}>Quincenal</option>
                                            </select>
                                        </div>

                                        <div class="col-md-12 m-t-5" style="font-weight: bolder;">
                                            <label>Servicio 5</label>
                                        </div>
                                        <hr>
                                        <div class="form-group col-md-3">
                                            <label>Tipo de servicio</label>
                                            <input type="text" class="form-control" name="tipo_servicio_5" value="{{$item && $item->nomina ? $item->nomina->tipo_servicio_5 : ''}}">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>No. de elementos</label>
                                            <input type="text" class="form-control" name="no_elementos_5" value="{{$item && $item->nomina ? $item->nomina->no_elementos_5 : ''}}">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Salario diario</label>
                                            <input type="text" class="form-control" name="salario_diario_5" value="{{$item && $item->nomina ? $item->nomina->salario_diario_5 : ''}}">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Salario mensual</label>
                                            <input type="text" class="form-control" name="salario_mensual_5" value="{{$item && $item->nomina ? $item->nomina->salario_mensual_5 : ''}}">
                                        </div>
                                        <div class="form-group col-md-8">
                                            <label>Horario servicio</label>
                                            <input type="text" class="form-control" name="horario_servicio_5" value="{{$item && $item->nomina ? $item->nomina->horario_servicio_5 : ''}}">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="control-label" for="type">Tipo de pago</label>
                                            <select name="tipo_pago_5" class="form-control" data-msg="Tipo de pago">
                                                <option value="" selected>Seleccione una opción</option>
                                                <option value="Semanal" {{$item && $item->nomina && $item->nomina->tipo_pago_5 == 'Semanal' ? 'selected' : ''}}>Semanal</option>
                                                <option value="Quincenal" {{$item && $item->nomina && $item->nomina->tipo_pago_5 == 'Quincenal' ? 'selected' : ''}}>Quincenal</option>
                                            </select>
                                        </div>

                                        <div class="col-md-12 m-t-5" style="font-weight: bolder;">
                                            <label>Servicio 6</label>
                                        </div>
                                        <hr>
                                        <div class="form-group col-md-3">
                                            <label>Tipo de servicio</label>
                                            <input type="text" class="form-control" name="tipo_servicio_6" value="{{$item && $item->nomina ? $item->nomina->tipo_servicio_6 : ''}}">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>No. de elementos</label>
                                            <input type="text" class="form-control" name="no_elementos_6" value="{{$item && $item->nomina ? $item->nomina->no_elementos_6 : ''}}">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Salario diario</label>
                                            <input type="text" class="form-control" name="salario_diario_6" value="{{$item && $item->nomina ? $item->nomina->salario_diario_6 : ''}}">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Salario mensual</label>
                                            <input type="text" class="form-control" name="salario_mensual_6" value="{{$item && $item->nomina ? $item->nomina->salario_mensual_6 : ''}}">
                                        </div>
                                        <div class="form-group col-md-8">
                                            <label>Horario servicio</label>
                                            <input type="text" class="form-control" name="horario_servicio_6" value="{{$item && $item->nomina ? $item->nomina->horario_servicio_6 : ''}}">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="control-label" for="type">Tipo de pago</label>
                                            <select name="tipo_pago_6" class="form-control" data-msg="Tipo de pago">
                                                <option value="" selected>Seleccione una opción</option>
                                                <option value="Semanal" {{$item && $item->nomina && $item->nomina->tipo_pago_6 == 'Semanal' ? 'selected' : ''}}>Semanal</option>
                                                <option value="Quincenal" {{$item && $item->nomina && $item->nomina->tipo_pago_6 == 'Quincenal' ? 'selected' : ''}}>Quincenal</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label>Observaciones</label>
                                            <textarea name="nomina_observaciones" class="form-control" rows="4">{{$item && $item->nomina ? $item->nomina->observaciones : ''}}</textarea>
                                        </div>
                                        
                                    </div>

                                </div>
                            </div>
                            <div class="form-group m-t-15">
                                @if( $item )
                                <a href="{{url('clientes?s='.$item->status->url)}}"><button type="button" class="btn btn-primary">Regresar</button></a>
                                @else
                                <a href="{{url('clientes?s=activos')}}"><button type="button" class="btn btn-primary">Regresar</button></a>
                                @endif
                                <button type="submit" class="btn btn-success save">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $('#otro').change(function() {
        if( $(this).is(":checked") ) {
            $('[name="otro_opcion"]').removeClass('d-none');
        } else {
            $('[name="otro_opcion"]').addClass('d-none');
        }
    });
</script>
@endsection