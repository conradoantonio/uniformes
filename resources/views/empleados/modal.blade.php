<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="label-title" id="modal-historico">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="label-title">Generar histórico</h4>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group col-md-12 d-none">
                        <label>ID empleado</label>
                        <input type="text" class="form-control" name="empleado_id" data-msg="ID de empleado">
                    </div>

                    <div class="form-group col-md-12">
                        <label>empleado</label>
                        <input type="text" class="form-control" disabled name="empleado_name" data-msg="empleado">
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
                <button type="submit" class="btn btn-success btn-generar-historico" data-target-id="form-historico">Generar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="label-title" id="modal-change-status">
    <div class="modal-dialog" role="document">
        <form id="form-change-status" action="{{url('empleados/change-status')}}" onsubmit="return false;" enctype="multipart/form-data" method="POST" autocomplete="off" data-ajax-type="ajax-form" data-column="0" data-refresh="table" data-redirect="" data-table_id="" data-container_id="" data-keep_modal="false">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="label-title">Cambiar status de empleado</h4>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-12 d-none">
                            <label>ID empleado</label>
                            <input type="text" class="form-control" name="empleado_id" data-msg="ID de empleado">
                        </div>

                        <div class="form-group col-md-12">
                            <label>empleado</label>
                            <input type="text" class="form-control" disabled name="empleado_nombre" data-msg="Nombre de empleado">
                        </div>
                        
                        <div class="form-group col-md-12">
                            <label class="required" for="date">Status</label>
                            <select class="form-control not-empty" name="status_empleado_id" data-msg="Status">
                                <option value="0" selected>Selecciona una opción</option>
                                @foreach( $statusEmpleado as $st )
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
