<div class="modal fade" id="modal-excel" tabindex="-1" role="dialog" aria-labelledby="BottomRightLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form id="form-import" method="POST" action="" onsubmit="return false" enctype="multipart/form-data" autocomplete="off" data-ajax-type="ajax-form-modal" data-column="0" data-refresh="table" data-redirect="0" data-table_id="" data-container_id="rows-container">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="BottomRightLabel">Importar desde excel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="alert alert-border-info alert-dismissible fade show" role="alert">
                                <div class="d-flex">
                                    <div class="icon">
                                        <i class="icon mdi mdi-alert-circle-outline"></i>
                                    </div>
                                    <div class="content">
                                        <strong>Instrucciones de uso: </strong><br>
                                        Para importar registros a través de Excel, los datos deben estar acomodados como se describe a continuación: <br>
                                        Los campos de la primera fila de la hoja de excel deben de ir los campos llamados 
                                        <strong id="fields">""</strong>, posteriormente, debajo de cada uno de estos campos deberán de ir los datos correspondientes de los registros.
                                        <br><strong>Nota: </strong>
                                        <br>- Favor de <span style="text-decoration: underline;"> NO incluir caracteres especiales como @, -, /, {}, [], ~ </span> etc, ya que el excel podría generar registros dañados y el código qr podría NO ser generado y/o descargado correctamente.
                                        <br>- No genere rutas ni días de visita de forma manual, permita que el excel genere esta información automáticamente.
                                        <br>- Colocar los días de visita en minúscula y completos con acentos incluidos, y si lleva más de un día de visita, separarlos únicamente por un espacio, por ejemplo:<span style="font-weight: bold;"> lunes miércoles </span>
                                        <br>- Solo se aceptan archivos con extensión <kbd>xls y xlsx</kbd> {{-- y los registros repetidos en el excel no serán creados. --}}
                                        <br>- Esta acción puede llevar varios minutos dependiendo de la cantidad de información, por favor espere y permanezca en esta ventana hasta que un mensaje sea mostrado en su pantalla.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="excel_file">Archivo Excel</label>
                                <input class="form-control not-empty file excel" type="file" id="excel_file" name="excel_file" data-msg="Archivo Excel">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary save">Importar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </form>
    </div>
</div>
