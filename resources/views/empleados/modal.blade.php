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
                        <label>Empleado</label>
                        <input type="text" class="form-control" disabled name="empleado_name" data-msg="Empleado">
                    </div>

                    <div class="form-group col-md-12">
                        <label>Tipo de registro</label>
                        <select class="form-control" name="tipo_historial_id" data-msg="Tipo de recibo">
                            <option value="">Cualquiera</option>
                            <option value="1">En ruta</option>
                            <option value="2">Entregado</option>
                            <option value="3">Devuelto</option>
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
                <button type="submit" class="btn btn-success btn-generar-historico" data-target-id="form-historico">Generar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="label-title" id="modal-add-historic-move">
    <div class="modal-dialog" role="document">
        <form id="form-add-historic-move" action="{{url('historicos/types/save')}}" onsubmit="return false;" enctype="multipart/form-data" method="POST" autocomplete="off" data-ajax-type="ajax-form" data-custom-function="guardarMovimiento" data-keep_modal="true">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="label-title">Agregar/Editar tipo de movimiento del artículo</h4>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-12 d-none">
                            <label>ID historial</label>
                            <input type="text" class="form-control" name="historial_id" data-msg="ID de historial">
                        </div>

                        <div class="form-group col-md-12">
                            <label>Artículo</label>
                            <input type="text" class="form-control" disabled name="articulo_nombre" data-msg="Nombre de artículo">
                        </div>

                        <div class="form-group col-md-12">
                            <label>Fecha de movimiento</label>
                            <input type="text" class="form-control not-empty date-picker" name="fecha_movimiento" data-msg="Fecha de movimiento">
                        </div>
                        
                        <div class="form-group col-md-12">
                            <label class="required" for="date">Movimiento</label>
                            <select class="form-control not-empty" name="tipo_historial_id" data-msg="Movimiento">
                                <option value="" selected>Selecciona una opción</option>
                                <option value="1">En ruta</option>
                                <option value="2">Entregado</option>
                                <option value="3">Devuelto</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success save" data-target-id="form-add-historic-move">Cambiar status</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

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

<div class="modal fade mostrar-historico" tabindex="-1" role="dialog" aria-labelledby="label-title" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="label-title">Histórico de artículos</h4>
            </div>
            <div class="modal-body">
                <div class="row text-left">
                    <div class="col-md-12">
                        <ul class="list-group">
                            <li class="list-group-item active">Registro</li>
                            <li class="list-group-item">
                                <div class="table-responsive">
                                    <table class="table table-hover table-sm historico text-center">
                                        <thead>
                                            {{-- <th class="align-middle">Tipo</th> --}}
                                            <th class="align-middle">Artículo</th>
                                            <th class="align-middle">Status</th>
                                            <th class="align-middle">Talla</th>
                                            <th class="align-middle">Color</th>
                                            <th class="align-middle">Cantidad</th>
                                            <th class="align-middle">Servicio guardia</th>
                                            <th class="align-middle">Supervisor</th>
                                            {{-- <th class="align-middle">Fecha entrega</th> --}}
                                            <th class="align-middle">Notas</th>
                                            <th class="align-middle">Movimientos</th>
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