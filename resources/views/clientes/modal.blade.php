<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="label-title" id="modal-estado-cuenta">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="label-title">Generar estado de cuenta</h4>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group col-md-12 d-none">
                        <label>ID Cliente</label>
                        <input type="text" class="form-control" name="cliente_id" data-msg="ID de cliente">
                    </div>

                    <div class="form-group col-md-12">
                        <label>Cliente</label>
                        <input type="text" class="form-control" disabled name="cliente_name" data-msg="Cliente">
                    </div>
                    
                    <div class="form-group col-md-12">
                        <label class="required" for="date">Fecha inicio de movimientos</label>
                        <input type="text" class="form-control not-empty date-picker" name="fecha_inicio" autocomplete="off" data-msg="Fecha del movimiento">
                    </div>

                    <div class="form-group col-md-12">
                        <label class="required" for="date">Fecha fin de movimientos</label>
                        <input type="text" class="form-control not-empty date-picker" name="fecha_fin" autocomplete="off" data-msg="Fecha del movimiento">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success btn-generar-estado-cuenta" data-target-id="form-estado-cuenta">Generar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="label-title" id="modal-change-status">
    <div class="modal-dialog" role="document">
        <form id="form-change-status" action="{{url('clientes/change-status')}}" onsubmit="return false;" enctype="multipart/form-data" method="POST" autocomplete="off" data-ajax-type="ajax-form" data-column="0" data-refresh="table" data-redirect="" data-table_id="" data-container_id="" data-keep_modal="false">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="label-title">Cambiar status de cliente</h4>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-12 d-none">
                            <label>ID cliente</label>
                            <input type="text" class="form-control" name="cliente_id" data-msg="ID de cliente">
                        </div>

                        <div class="form-group col-md-12">
                            <label>Cliente</label>
                            <input type="text" class="form-control" disabled name="cliente_nombre" data-msg="Nombre de cliente">
                        </div>
                        
                        <div class="form-group col-md-12">
                            <label class="required" for="date">Status</label>
                            <select class="form-control not-empty" name="status_cliente_id" data-msg="Status">
                                <option value="0" selected>Selecciona una opción</option>
                                @foreach( $statusCliente as $st )
                                    <option value="{{$st->id}}">{{$st->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success save" data-target-id="form-change-status">Cambiar status</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="label-title" id="modal-ver-detalles">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="label-title">Información de cliente</h4>
            </div>
            <div class="modal-body">
                <ul class="nav nav-pills nav-justified" id="myTab3" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="datos-generales-tab" data-toggle="tab" href="#datos-generales" role="tab" aria-controls="datos generales" aria-selected="false">Generales</a>
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
                        <a class="nav-link" id="nominas-tab" data-toggle="tab" href="#nominas" role="tab" aria-controls="nominas" aria-selected="false">Nóminas</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade active show" id="datos-generales" role="tabpanel" aria-labelledby="datos-generales-tab">
                        <div class="form-row text-center m-t-15">
                            <div class="form-group col-md-12">
                                <a class="btn btn-dark btn-sm descargar-pdf" target="_blank" data-vista="generales" href="" data-toggle="tooltip" data-placement="top" title="Descargar pdf"><i class="mdi mdi-file-pdf"> Descargar pdf</i></a>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label>Razón social</label>
                                <input type="text" class="form-control" disabled name="cliente_razon_social_nombre">
                            </div>
                            <div class="form-group col-md-12">
                                <label>Nombre comercial</label>
                                <input type="text" class="form-control" disabled name="generales_nombre_comercial">
                            </div>
                            <div class="form-group col-md-12">
                                <label>No. Cliente</label>
                                <input type="text" class="form-control" disabled name="generales_num_cliente">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Fecha inicio servicio</label>
                                <input type="text" class="form-control" disabled name="generales_fecha_inicio_servicio">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Registro patronal</label>
                                <input type="text" class="form-control" disabled name="generales_registro_patronal_nss">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Fecha terminación servicio</label>
                                <input type="text" class="form-control" disabled name="generales_fecha_terminacion_servicio">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Motivo de baja</label>
                                <input type="text" class="form-control" disabled name="generales_motivo_baja">
                            </div>
                            
                            <div class="form-group col-md-12">
                                <label>Envío de documentos mensuales</label>
                            </div>
                            <hr>
                            <div class="form-group col-md-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="generales_pago_imss" id="generales_pago_imss" disabled>
                                    <label for="generales_pago_imss" class="custom-control-label">Pago de IMSS</label>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="generales_opinion_sat" id="generales_opinion_sat" disabled>
                                    <label for="generales_opinion_sat" class="custom-control-label">Opinión SAT</label>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="generales_dos_sobre_nomina" id="generales_dos_sobre_nomina" disabled>
                                    <label for="generales_dos_sobre_nomina" class="custom-control-label">2% sobre nómina</label>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="generales_opinion_cumplimiento_imss" id="generales_opinion_cumplimiento_imss" disabled>
                                    <label for="generales_opinion_cumplimiento_imss" class="custom-control-label">Opinión cumplimiento IMSS</label>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="generales_declaracion_provisional_sat" id="generales_declaracion_provisional_sat" disabled>
                                    <label for="generales_declaracion_provisional_sat" class="custom-control-label">Declaración provisional SAT</label>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="generales_constancia_situacion_infonavit" id="generales_constancia_situacion_infonavit" disabled>
                                    <label for="generales_constancia_situacion_infonavit" class="custom-control-label">Constancia situación infonavit</label>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="generales_nominas" id="generales_nominas" disabled>
                                    <label for="generales_nominas" class="custom-control-label">Nóminas</label>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="generales_repse" id="generales_repse" disabled>
                                    <label for="generales_repse" class="custom-control-label">Repse</label>
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                <label>Observaciones</label>
                                <textarea class="form-control" name="generales_observaciones" disabled rows="3"></textarea>
                            </div>
                        </div>

                    </div>
                    <div class="tab-pane fade" id="facturas" role="tabpanel" aria-labelledby="facturas-tab">
                        <div class="form-row text-center m-t-15">
                            <div class="form-group col-md-12">
                                <a class="btn btn-dark btn-sm descargar-pdf" target="_blank" data-vista="facturas" href="" data-toggle="tooltip" data-placement="top" title="Descargar pdf"><i class="mdi mdi-file-pdf"> Descargar pdf</i></a>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label>Nombre razón social</label>
                                <input type="text" class="form-control" disabled name="cliente_nombre">
                            </div>
                            <div class="form-group col-md-12">
                                <label>Domicilio fiscal</label>
                                <input type="text" class="form-control" disabled name="cliente_domicilio">
                            </div>
                            <div class="form-group col-md-4">
                                <label>RFC</label>
                                <input type="text" class="form-control" disabled name="factura_rfc">
                            </div>
                            <div class="form-group col-md-4">
                                <label>No. Elementos</label>
                                <input type="text" class="form-control" disabled name="factura_no_elementos">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Costo unitario</label>
                                <input type="text" class="form-control" disabled name="factura_costo_unitario">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Precio total mensual sin IVA</label>
                                <input type="text" class="form-control" disabled name="factura_precio_total_mensual_sin_iva">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Correo para envío de factura</label>
                                <input type="text" class="form-control" disabled name="factura_correo_para_envio_factura">
                            </div>
                            <div class="form-group col-md-8">
                                <label>Nombre del contacto</label>
                                <input type="text" class="form-control" disabled name="factura_nombre_contacto">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Fecha envío factura</label>
                                <input type="text" class="form-control" disabled name="factura_fecha_envio_factura">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Cobran días festivos</label>
                                <input type="text" class="form-control" disabled name="factura_cobran_dias_festivos">
                            </div>
                            <div class="form-group col-md-8">
                                <label>Horario</label>
                                <input type="text" class="form-control" disabled name="factura_horario">
                            </div>
                            
                            <div class="form-group col-md-12">
                                <label>Observaciones sobre factura</label>
                                <textarea class="form-control" disabled name="factura_observaciones_sobre_factura" rows="3"></textarea>
                            </div>

                        </div>

                    </div>
                    <div class="tab-pane fade" id="contratos" role="tabpanel" aria-labelledby="contratos-tab">
                        <div class="form-row text-center m-t-15">
                            <div class="form-group col-md-12">
                                <a class="btn btn-dark btn-sm descargar-pdf" target="_blank" data-vista="contratos" href="" data-toggle="tooltip" data-placement="top" title="Descargar pdf"><i class="mdi mdi-file-pdf"> Descargar pdf</i></a>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label>Contrato original expediente</label>
                                <input type="text" class="form-control" disabled name="contrato_contrato_original_expediente">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Firmado</label>
                                <input type="text" class="form-control" disabled name="contrato_firmado">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Escaneado</label>
                                <input type="text" class="form-control" disabled name="contrato_escaneado">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Tipo de contrato</label>
                                <input type="text" class="form-control" disabled name="contrato_tipo_contrato">
                            </div>
                            <div class="form-group col-md-9">
                                <label>Nombre del representante legal</label>
                                <input type="text" class="form-control" disabled name="contrato_nombre_representante_legal">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Cargo</label>
                                <input type="text" class="form-control" disabled name="contrato_cargo">
                            </div>
                            <div class="form-group col-md-12">
                                <label>Nombre del testigo</label>
                                <input type="text" class="form-control" disabled name="contrato_nombre_testigo">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Vigencia</label>
                                <input type="text" class="form-control" disabled name="contrato_vigencia">
                            </div>
                            <div class="form-group col-md-12">
                                <label>Indicador de documentos escaneados</label>
                            </div>
                            <hr>
                            <div class="form-group col-md-4">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="contrato_acta_constitutiva" id="contrato_acta_constitutiva" disabled>
                                    <label for="contrato_acta_constitutiva" class="custom-control-label">Acta constitutiva</label>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="contrato_ine_representante" id="contrato_ine_representante" disabled>
                                    <label for="contrato_ine_representante" class="custom-control-label">INE representante</label>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="contrato_ine_testigo" id="contrato_ine_testigo" disabled>
                                    <label for="contrato_ine_testigo" class="custom-control-label">INE testigo</label>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="contrato_cedula_fiscal" id="contrato_cedula_fiscal" disabled>
                                    <label for="contrato_cedula_fiscal" class="custom-control-label">Cédula fiscal</label>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="contrato_comprobante_domicilio_fiscal" id="contrato_comprobante_domicilio_fiscal" disabled>
                                    <label for="contrato_comprobante_domicilio_fiscal" class="custom-control-label">Comprobante de domicilio fiscal</label>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="contrato_comprobante_domicilio_servicio" id="contrato_comprobante_domicilio_servicio" disabled>
                                    <label for="contrato_comprobante_domicilio_servicio" class="custom-control-label">Comprobante del domicilio del servicio</label>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="contrato_cotizacion_ventas" id="contrato_cotizacion_ventas" disabled>
                                    <label for="contrato_cotizacion_ventas" class="custom-control-label">Cotización ventas</label>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="contrato_contrato" id="contrato_contrato" disabled>
                                    <label for="contrato_contrato" class="custom-control-label">Contrato</label>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="contrato_orden_compra" id="contrato_orden_compra" disabled>
                                    <label for="contrato_orden_compra" class="custom-control-label">Orden de compra</label>
                                </div>
                            </div>
                            
                            
                            <div class="form-group col-md-12">
                                <label>Observaciones</label>
                                <textarea class="form-control" disabled name="contrato_observaciones" rows="3"></textarea>
                            </div>

                        </div>
                    </div>
                    <div class="tab-pane fade" id="ventas" role="tabpanel" aria-labelledby="ventas-tab">
                        <div class="form-row text-center m-t-15">
                            <div class="form-group col-md-12">
                                <a class="btn btn-dark btn-sm descargar-pdf" target="_blank" data-vista="ventas" href="" data-toggle="tooltip" data-placement="top" title="Descargar pdf"><i class="mdi mdi-file-pdf"> Descargar pdf</i></a>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Nombre contacto inicial</label>
                                <input type="text" class="form-control" disabled name="venta_nombre_contacto_inicial">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Teléfono contacto inicial</label>
                                <input type="text" class="form-control" disabled name="venta_telefono_contacto_inicial">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Correo contacto inicial</label>
                                <input type="text" class="form-control" disabled name="venta_correo_contacto_inicial">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Cotización firmada y autorizada</label>
                                <input type="text" class="form-control" disabled name="venta_cotizacion_firmada_autorizada">
                            </div>
                            <div class="form-group col-md-12">
                                <label>Forma en que se concreto</label>
                                <input type="text" class="form-control" disabled name="venta_forma_en_que_se_concreto">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Fecha cita</label>
                                <input type="text" class="form-control" disabled name="venta_fecha_cita">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Asistencia a cita</label>
                                <input type="text" class="form-control" disabled name="venta_asistencia_cita">
                            </div>
                            <div class="form-group col-md-6">
                                <label>¿Quién asistió a la cita?</label>
                                <input type="text" class="form-control" disabled name="venta_quien_asistio_a_cita">
                            </div>
                            <div class="form-group col-md-12">
                                <label>Observaciones</label>
                                <textarea class="form-control" disabled name="venta_observaciones" rows="3"></textarea>
                            </div>

                        </div>
                    </div>
                    <div class="tab-pane fade" id="cobranza" role="tabpanel" aria-labelledby="cobranza-tab">
                        <div class="form-row text-center m-t-15">
                            <div class="form-group col-md-12">
                                <a class="btn btn-dark btn-sm descargar-pdf" target="_blank" data-vista="cobranza" href="" data-toggle="tooltip" data-placement="top" title="Descargar pdf"><i class="mdi mdi-file-pdf"> Descargar pdf</i></a>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group col-md-9">
                                <label>Persona de pagos</label>
                                <input type="text" class="form-control" disabled name="cliente_nombre_encargado">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Teléfono</label>
                                <input type="text" class="form-control" disabled name="cliente_telefono">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Correo electrónico</label>
                                <input type="text" class="form-control" disabled name="cliente_correo">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Días de crédito</label>
                                <input type="text" class="form-control" disabled name="cliente_dias_credito">
                            </div>
                            <div class="form-group col-md-12">
                                <label>Días de pago</label>
                                <input type="text" class="form-control" disabled name="cobranza_dias_de_pago">
                            </div>
                            <div class="form-group col-md-12">
                                <label>Forma de pago</label>
                            </div>
                            <hr>
                            <div class="form-group col-md-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="cobranza_transferencia" id="cobranza_transferencia" disabled>
                                    <label for="cobranza_transferencia" class="custom-control-label">Transferencia</label>
                                </div>
                            </div>
                            <div class="form-group col-md-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="cobranza_deposito" id="cobranza_deposito" disabled>
                                    <label for="cobranza_deposito" class="custom-control-label">Depósito</label>
                                </div>
                            </div>
                            <div class="form-group col-md-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="cobranza_deposito" id="cobranza_deposito" disabled>
                                    <label for="cobranza_deposito" class="custom-control-label">Cheque</label>
                                </div>
                            </div>
                            <div class="form-group col-md-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="cobranza_efectivo" id="cobranza_efectivo" disabled>
                                    <label for="cobranza_efectivo" class="custom-control-label">Efectivo</label>
                                </div>
                            </div>
                            <div class="form-group col-md-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="cobranza_otro" id="cobranza_otro" disabled>
                                    <label for="cobranza_otro" class="custom-control-label">Otro</label>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label>Otro pago</label>
                                <input type="text" class="form-control" disabled name="cobranza_otro_opcion">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Nombre de banco del cliente</label>
                                <input type="text" class="form-control" disabled name="cobranza_nombre_banco_cliente">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Cuenta de pago del cliente</label>
                                <input type="text" class="form-control" disabled name="cobranza_cuenta_pago_cliente">
                            </div>
                            <div class="form-group col-md-12">
                                <label>Clabe de pago de cliente</label>
                                <input type="text" class="form-control" disabled name="cobranza_clabe_pago_cliente">
                            </div>
                            <div class="form-group col-md-12">
                                <label>Observaciones</label>
                                <textarea class="form-control" disabled name="cobranza_observaciones" rows="3"></textarea>
                            </div>

                        </div>
                    </div>
                    <div class="tab-pane fade" id="nominas" role="tabpanel" aria-labelledby="nominas-tab">
                        <div class="form-row text-center m-t-15">
                            <div class="form-group col-md-12">
                                <a class="btn btn-dark btn-sm descargar-pdf" target="_blank" data-vista="nominas" href="" data-toggle="tooltip" data-placement="top" title="Descargar pdf"><i class="mdi mdi-file-pdf"> Descargar pdf</i></a>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label>Domicilio del servicio</label>
                                <input type="text" class="form-control" disabled name="nomina_domicilio_servicio">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Teléfono del servicio</label>
                                <input type="text" class="form-control" disabled name="nomina_telefono_servicio">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Número de elementos</label>
                                <input type="text" class="form-control" disabled name="nomina_numero_elementos">
                            </div>

                            <div class="col-md-12 m-t-5" style="font-weight: bolder;">
                                <label>Servicio 1</label>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Tipo de servicio</label>
                                <input type="text" class="form-control" disabled name="nomina_tipo_servicio_1">
                            </div>
                            <div class="form-group col-md-2">
                                <label>No. de elementos</label>
                                <input type="text" class="form-control" disabled name="nomina_no_elementos_1">
                            </div>
                            <div class="form-group col-md-2">
                                <label>Salario diario</label>
                                <input type="text" class="form-control" disabled name="nomina_salario_diario_1" data-prefix="$">
                            </div>
                            <div class="form-group col-md-2">
                                <label>Salario mensual</label>
                                <input type="text" class="form-control" disabled name="nomina_salario_mensual_1" data-prefix="$">
                            </div>
                            <div class="form-group col-md-8">
                                <label>Horario</label>
                                <input type="text" class="form-control" disabled name="nomina_horario_servicio_1">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Tipo de pago</label>
                                <input type="text" class="form-control" disabled name="nomina_tipo_pago_1">
                            </div>

                            <div class="col-md-12 m-t-5" style="font-weight: bolder;">
                                <label>Servicio 2</label>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Tipo de servicio</label>
                                <input type="text" class="form-control" disabled name="nomina_tipo_servicio_2">
                            </div>
                            <div class="form-group col-md-2">
                                <label>No. de elementos</label>
                                <input type="text" class="form-control" disabled name="nomina_no_elementos_2">
                            </div>
                            <div class="form-group col-md-2">
                                <label>Salario diario</label>
                                <input type="text" class="form-control" disabled name="nomina_salario_diario_2" data-prefix="$">
                            </div>
                            <div class="form-group col-md-2">
                                <label>Salario mensual</label>
                                <input type="text" class="form-control" disabled name="nomina_salario_mensual_2" data-prefix="$">
                            </div>
                            <div class="form-group col-md-8">
                                <label>Horario</label>
                                <input type="text" class="form-control" disabled name="nomina_horario_servicio_2">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Tipo de pago</label>
                                <input type="text" class="form-control" disabled name="nomina_tipo_pago_2">
                            </div>

                            <div class="col-md-12 m-t-5" style="font-weight: bolder;">
                                <label>Servicio 3</label>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Tipo de servicio</label>
                                <input type="text" class="form-control" disabled name="nomina_tipo_servicio_3">
                            </div>
                            <div class="form-group col-md-2">
                                <label>No. de elementos</label>
                                <input type="text" class="form-control" disabled name="nomina_no_elementos_3">
                            </div>
                            <div class="form-group col-md-2">
                                <label>Salario diario</label>
                                <input type="text" class="form-control" disabled name="nomina_salario_diario_3" data-prefix="$">
                            </div>
                            <div class="form-group col-md-2">
                                <label>Salario mensual</label>
                                <input type="text" class="form-control" disabled name="nomina_salario_mensual_3" data-prefix="$">
                            </div>
                            <div class="form-group col-md-8">
                                <label>Horario</label>
                                <input type="text" class="form-control" disabled name="nomina_horario_servicio_3">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Tipo de pago</label>
                                <input type="text" class="form-control" disabled name="nomina_tipo_pago_3">
                            </div>

                            <div class="col-md-12 m-t-5" style="font-weight: bolder;">
                                <label>Servicio 4</label>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Tipo de servicio</label>
                                <input type="text" class="form-control" disabled name="nomina_tipo_servicio_4">
                            </div>
                            <div class="form-group col-md-2">
                                <label>No. de elementos</label>
                                <input type="text" class="form-control" disabled name="nomina_no_elementos_4">
                            </div>
                            <div class="form-group col-md-2">
                                <label>Salario diario</label>
                                <input type="text" class="form-control" disabled name="nomina_salario_diario_4" data-prefix="$">
                            </div>
                            <div class="form-group col-md-2">
                                <label>Salario mensual</label>
                                <input type="text" class="form-control" disabled name="nomina_salario_mensual_4" data-prefix="$">
                            </div>
                            <div class="form-group col-md-8">
                                <label>Horario</label>
                                <input type="text" class="form-control" disabled name="nomina_horario_servicio_4">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Tipo de pago</label>
                                <input type="text" class="form-control" disabled name="nomina_tipo_pago_4">
                            </div>

                            <div class="col-md-12 m-t-5" style="font-weight: bolder;">
                                <label>Servicio 5</label>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Tipo de servicio</label>
                                <input type="text" class="form-control" disabled name="nomina_tipo_servicio_5">
                            </div>
                            <div class="form-group col-md-2">
                                <label>No. de elementos</label>
                                <input type="text" class="form-control" disabled name="nomina_no_elementos_5">
                            </div>
                            <div class="form-group col-md-2">
                                <label>Salario diario</label>
                                <input type="text" class="form-control" disabled name="nomina_salario_diario_5" data-prefix="$">
                            </div>
                            <div class="form-group col-md-2">
                                <label>Salario mensual</label>
                                <input type="text" class="form-control" disabled name="nomina_salario_mensual_5" data-prefix="$">
                            </div>
                            <div class="form-group col-md-8">
                                <label>Horario</label>
                                <input type="text" class="form-control" disabled name="nomina_horario_servicio_5">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Tipo de pago</label>
                                <input type="text" class="form-control" disabled name="nomina_tipo_pago_5">
                            </div>

                            <div class="col-md-12 m-t-5" style="font-weight: bolder;">
                                <label>Servicio 6</label>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Tipo de servicio</label>
                                <input type="text" class="form-control" disabled name="nomina_tipo_servicio_6">
                            </div>
                            <div class="form-group col-md-2">
                                <label>No. de elementos</label>
                                <input type="text" class="form-control" disabled name="nomina_no_elementos_6">
                            </div>
                            <div class="form-group col-md-2">
                                <label>Salario diario</label>
                                <input type="text" class="form-control" disabled name="nomina_salario_diario_6" data-prefix="$">
                            </div>
                            <div class="form-group col-md-2">
                                <label>Salario mensual</label>
                                <input type="text" class="form-control" disabled name="nomina_salario_mensual_6" data-prefix="$">
                            </div>
                            <div class="form-group col-md-8">
                                <label>Horario</label>
                                <input type="text" class="form-control" disabled name="nomina_horario_servicio_6">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Tipo de pago</label>
                                <input type="text" class="form-control" disabled name="nomina_tipo_pago_6">
                            </div>
                            
                            <div class="form-group col-md-12">
                                <label>Observaciones</label>
                                <textarea class="form-control" disabled name="nomina_observaciones" rows="3"></textarea>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->