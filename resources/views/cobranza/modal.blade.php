<div class="modal fade data-fill" role="dialog" data-keyboard="false" aria-labelledby="label-title" id="comentarios-modal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title" id="label-title">Comentarios</h2>
            </div>
            <form id="form-data-comentarios" action="{{url('cobranza/comentarios/save')}}" method="POST" class="" onsubmit="return false;" enctype="multipart/form-data" data-ajax-type="ajax-form-modal" data-callback="agregarComentarioTabla" autocomplete="off" data-keep_modal="true" data-table_id="data-table" data-container_class="printable-area">
                <div class="modal-body">
                    <div class="form-group d-none">
                        <input type="text" class="form-control" name="row_id">
                    </div>
                    <div class="row text-left details-content">
                        <div class="col-md-12">
                            <ul class="list-group d-none">
                                <li class="list-group-item active">Datos generales</li>
                                <li class="list-group-item fill-container"><span class="label_show">ID de factura: <span class="factura_id"></span></span></li>
                                <li class="list-group-item fill-container"><span class="label_show">Número de factura: <span class="factura_numero_factura"></span></span></li>
                                <li class="list-group-item fill-container"><span class="label_show">Importe: $<span class="factura_importe"></span></span></li>
                                <li class="list-group-item fill-container"><span class="label_show">Fecha de facturación: <span class="factura_fecha_facturacion"></span></span></li>
                                <li class="list-group-item fill-container"><span class="label_show">Fecha promesa de pago: <span class="factura_fecha_promesa_pago"></span></span></li>
                            </ul>

                            <ul class="list-group ul-comentarios">
                                <li class="list-group-item active">Comentario</li>
                                
                                @if( auth()->user()->permisos()->where('permisos.alias', 'cobranza_editar')->exists() )
                                    <li class="list-group-item">
                                        <div class="row form-factura">
                                            <div class="form-group col-md-12 d-none">
                                                <label>Factura ID</label>
                                                <input type="text" class="form-control" name="factura_id" value="">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label class="control-label" for="tipo_contacto_id">Tipo</label>
                                                <select id="tipo_contacto_id" name="tipo_contacto_id" class="form-control not-empty" data-msg="Tipo">
                                                    <option value="">Seleccione una opción</option>
                                                    <option value="1">Teléfono</option>
                                                    <option value="2">WhatsApp</option>
                                                    <option value="3">Correo</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>Contacto*</label>
                                                <input type="text" class="form-control not-empty" name="contacto" value="" data-msg="Contacto">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>Fecha*</label>
                                                <input type="text" class="form-control date-picker not-empty" name="fecha" value="" data-msg="Fecha">
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label>Comentario*</label>
                                                <textarea type="text" class="form-control not-empty" name="comentario" data-msg="Comentario"></textarea>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <button type="button" class="btn btn-success save agregar-comentario">Añadir comentario</button>
                                            </div>
                                        </div>
                                    </li>
                                @endif

                                <li class="list-group-item">
                                    <table class="table tabla-comentarios table-hover table-sm">
                                        <thead>
                                            <th class="d-nones">ID</th>
                                            <th>Tipo</th>
                                            <th>Dato</th>
                                            <th>Fecha</th>
                                            <th>Comentario</th>
                                            <th>Acciones</th>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    {{-- <button type="button" class="btn btn-success save send-rate" data-dismiss="modal">Guardar fechas</button> --}}
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->