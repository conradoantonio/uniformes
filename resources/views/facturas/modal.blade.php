<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="label-title" id="modal-cancelar-factura">
    <div class="modal-dialog" role="document">
        <form id="form-cancelar-factura" action="{{url('facturas/cancell')}}" onsubmit="return false;" enctype="multipart/form-data" method="POST" autocomplete="off" data-ajax-type="ajax-form" data-column="0" data-refresh="table" data-redirect="" data-table_id="" data-container_id="" data-keep_modal="false">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="label-title">Cancelar factura</h4>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-12 d-none">
                            <label>ID Factura</label>
                            <input type="text" class="form-control" name="factura_id" data-msg="ID de cliente">
                        </div>

                        <div class="form-group col-md-12">
                            <label>Número de factura</label>
                            <input type="text" class="form-control" disabled name="factura_numero" data-msg="Número de factura">
                        </div>
                        
                        <div class="form-group col-md-12">
                            <label class="required" for="date">Razón de cancelación</label>
                            <textarea class="form-control not-empty" name="razon" data-msg="Razón de cancelación"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success save" data-target-id="form-cancelar-factura">Generar cancelación</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade data-show-details" tabindex="-1" role="dialog" aria-labelledby="label-title" id="bill-details">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="label-title">Archivos</h4>
            </div>
            <div class="modal-body">
                <div class="row text-left">
                    <div class="col-md-12">
                        <ul class="list-group">
                            <li class="list-group-item active">Pagos generados</li>
                            <li class="list-group-item">
                                <div class="table-responsive">
                                    <table class="table table-hover table-sm files-contract">
                                        <thead>
                                            <th class="align-middle">No.</th>
                                            <th class="align-middle">Archivo</th>
                                            <th class="align-middle">Acciones</th>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->