<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="label-title" id="modal-excel-historial">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="label-title">Generar reporte de uniformes</h4>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group col-md-12 d-none">
                        <label>ID Razón social</label>
                        <input type="text" class="form-control" name="razon_social_id" data-msg="ID de razón social">
                    </div>

                    <div class="form-group col-md-12">
                        <label>Razón social</label>
                        <input type="text" class="form-control" disabled name="razon_social_nombre" data-msg="Razón social">
                    </div>
                    <div class="form-group col-md-12" style="text-align: left;">
                        <label>Status</label>
                        <select id="status_empleado_id" name="status_empleado_id" class="form-control" data-msg="Status empleado">
                            <option value="" selected>Status (Cualquiera)</option>
                            @foreach($statusEmpleado as $status)
                                <option value="{{$status->id}}">{{$status->nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-12 div-empleados" style="text-align: left;">
                        <label>Empleados</label>
                        <select id="empleado_id" name="empleado_id" class="form-control not-empty select2" style="width: 100%;" data-msg="Empleados">
                            <option value="" selected>Empleados (Cualquiera)</option>
                        </select>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="required" for="date">Fecha inicio</label>
                        <input type="text" class="form-control not-empty date-picker" name="fecha_inicio" autocomplete="off" data-msg="Fecha inicio">
                    </div>

                    <div class="form-group col-md-12">
                        <label class="required" for="date">Fecha fin</label>
                        <input type="text" class="form-control not-empty date-picker" name="fecha_fin" autocomplete="off" data-msg="Fecha fin">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success btn-generar-excel">Generar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="label-title" id="modal-global-excel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="label-title">Generar reporte global de uniformes</h4>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group col-md-12" style="text-align: left;">
                        <label>Status de empleados</label>
                        <select name="status_empleado_id" class="form-control" data-msg="Status empleado">
                            <option value="" selected>Status (Cualquiera)</option>
                            @foreach($statusEmpleado as $status)
                                <option value="{{$status->id}}">{{$status->nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success btn-generar-excel-global">Generar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->