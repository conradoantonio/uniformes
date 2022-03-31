<div class="modal fade data-fill" role="dialog" data-keyboard="false" aria-labelledby="label-title" id="detalle-notificacion">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title" id="label-title">Detalles de la notificación</h2>
            </div>
            <div class="modal-body">
                <div class="row text-left details-content">
                    <div class="col-md-12">
                        <ul class="list-group">
                            <li class="list-group-item active">Datos generales</li>
                            <li class="list-group-item fill-container"><span class="label_show">ID de notificación: <span class="id"> </span></span></li>
                            <li class="list-group-item fill-container"><span class="label_show">Fecha de creación: <span class="fecha_formateada"> </span></span></li>
                            <li class="list-group-item fill-container"><span class="label_show">Título: <span class="titulo">Inicio de recorrido</span></span></li>
                            <li class="list-group-item fill-container"><span class="label_show">Tipo: <span class="tipo_notificacion"> </span></span></li>
                            <li class="list-group-item fill-container"><span class="label_show">Descripción: <span class="descripcion"></span> </span></li>
                        </ul>

                        <ul class="list-group ul-user">
                            <li class="list-group-item active">Datos del vendedor</li>
                            <li class="list-group-item text-center user-foto">
                                <img width="100px;" src="" id="user-photo">
                            </li>
                            <li class="list-group-item fill-container"><span class="label_show">Nombre completo: <span class="user_fullname"> </span></span></li>
                            <li class="list-group-item fill-container"><span class="label_show">Correo: <span class="user_email"></span> </span></li>
                            <li class="list-group-item fill-container"><span class="label_show">Teléfono: <span class="user_telefono"></span> </span></li>
                        </ul>

                        <ul class="list-group ul-reporte">
                            <li class="list-group-item active">Detalles del reporte</li>
                            <li class="list-group-item fill-container"><span class="label_show">Mensaje de reporte: <span class="reporte_contenido"> </span> </span></li>
                            <li class="list-group-item fill-container"><span class="label_show">Tipo: <span class="reporte_tipo_nombre"></span> </span> </li>
                        </ul>

                        {{-- <ul class="list-group ul-productos d-none">
                            <li class="list-group-item active">Productos</li>
                            <li class="list-group-item">
                                <table id="table-productos" class="table table-hover table-sm">
                                    <thead>
                                        <th>ID</th>
                                        <th>Producto</th>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </li>
                        </ul> --}}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->