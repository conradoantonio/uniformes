@extends('layouts.main')

@section('content')
<section class="admin-content">
    <div class=" bg-dark m-b-30 bg-stars">
        <div class="container">
            <div class="row">
                <div class="col-md-6 m-auto text-white p-t-40 p-b-90">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-b-0 bg-transparent ol-breadcrum">
                            <li class="breadcrumb-item"><a href="javascript:;">Empleados</a></li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-6 m-auto text-white p-t-40 p-b-90">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-b-0 bg-transparent ol-breadcrum float-right">
                            <li class="breadcrumb-item active" aria-current="page">Asignar uniforme</li>
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
                        <h2 class="">Ingresa los datos del registro</h2>
                    </div>
                    <div class="card-body">
                        <form id="form-data" action="{{url('historicos/save')}}" onsubmit="return false;" enctype="multipart/form-data" method="POST" autocomplete="off" data-custom-function="agregarRegistro" data-refresh="" data-redirect="1">
                            <div class="form-row">
                                <div class="form-group d-none">
                                    <label>ID</label>
                                    <input type="text" class="form-control" name="empleado_id" value="{{$empleado ? $empleado->id : ''}}">
                                </div>
                            </div>
                            
                            <div class="form-row m-t-15">
                                <div class="form-group col-md-6">
                                    <label>Empleado</label>
                                    <input type="text" class="form-control" readonly name="empleado_name" value="{{$empleado ? $empleado->nombre : ''}}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="control-label" for="type">Tipo de registro*</label>
                                    <select id="tipo_historial_id" name="tipo_historial_id" class="form-control not-empty" data-msg="Tipo de registro">
                                        <option value="1">En ruta</option>
                                        <option value="2">Entregado</option>
                                        <option value="3">Devuelto</option>
                                       
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="control-label" for="type">Artículo*</label>
                                    <select id="articulo_id" name="articulo_id" class="form-control not-empty" data-msg="Artículo">
                                        <option value="" selected>Seleccione una opción</option>
                                        @foreach($articulos as $articulo)
                                            <option value="{{$articulo->id}}" data-obj="{{$articulo}}" >{{$articulo->nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="control-label" for="type">Talla</label>
                                    <input type="text" class="form-control" name="talla" value="" data-msg="Talla">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Color</label>
                                    <input type="text" class="form-control" name="color" value="" data-msg="Color">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Cantidad*</label>
                                    <input type="text" class="form-control not-empty" name="cantidad" value="" data-msg="Cantidad">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Fecha entrega</label>
                                    <input type="text" class="form-control not-empty date-picker" name="fecha_entrega" value="" data-msg="Fecha de entrega">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Supervisor</label>
                                    <input type="text" class="form-control" name="supervisor" value="" data-msg="Supervisor">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Servicio del guardia</label>
                                    <input type="text" class="form-control" name="servicio_guardia" value="" data-msg="Servicio del guardia">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Notas</label>
                                    <textarea name="notas" class="form-control" rows="3" data-msg="Notas"></textarea>
                                </div>
                                
                            </div>

                            <div class="form-group m-t-15">
                                <div class="form-group col-md-12 text-center">
                                    <button type="button" class="btn btn-info save agregar-historial">Agregar registro</button>
                                </div>
                            </div>

                            <div class="form-group m-t-15">
                                <div class="table-responsive">
                                    <table class="table table-hover table-sm historico text-center">
                                        <thead>
                                            <th class="align-middle">Tipo</th>
                                            <th class="align-middle">Artículo</th>
                                            <th class="align-middle">Status</th>
                                            <th class="align-middle">Talla</th>
                                            <th class="align-middle">Color</th>
                                            <th class="align-middle">Cantidad</th>
                                            <th class="align-middle">Fecha entrega</th>
                                            <th class="align-middle">Notas</th>
                                            <th class="align-middle">Acciones</th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="9">Sin registros</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="form-group m-t-15">
                                @if( $empleado )
                                <a href="{{url('empleados?s='.$empleado->status->url)}}"><button type="button" class="btn btn-primary">Regresar</button></a>
                                @else
                                <a href="{{url('empleados?s=activos')}}"><button type="button" class="btn btn-primary">Regresar</button></a>
                                @endif
                                <button type="button" class="btn btn-success" onclick="guardarRegistro()">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
    // Al seleccionar un artículo, se llenarán los campos talla y color
    $('#articulo_id').on('change', function() {
        let obj = $(this).children(':selected').data('obj');
        
        $('[name="talla"], [name="color"]').val('');

        if ( obj ) {
            $('[name="talla"]').val(obj.talla);
            $('[name="color"]').val(obj.color);
        }
    });

    // Valida el agregar un registro de uniformes a la tabla
    function agregarRegistro() { 
        let timerId = Date.now();
        let registro = {
            timer_id           : timerId,
            empleado_id        : $('input[name="empleado_id"]').val(),
            tipo_historial     : $('select[name="tipo_historial_id"]').children(':selected').text(),
            tipo_historial_id  : $('select[name="tipo_historial_id"]').val(),
            articulo           : $('select[name="articulo_id"]').children(':selected').text(),
            articulo_id        : $('select[name="articulo_id"]').val(),
            talla              : $('input[name="talla"]').val(),
            color              : $('input[name="color"]').val(),
            cantidad           : $('input[name="cantidad"]').val(),
            fecha_entrega      : $('input[name="fecha_entrega"]').val(),
            supervisor         : $('input[name="supervisor"]').val(),
            servicio_guardia   : $('input[name="servicio_guardia"]').val(),
            notas              : $('textarea[name="notas"]').val() 
        }

        console.log('Registro: ', registro);

        let searchTr = $('table.historico > tbody > tr[data-row-id="'+timerId+'"]');

        // Si es el primer uniforme agregado se quita el mensaje de uniformes sin agregar
        if ( $('table.historico').children('tbody').children('tr.row-item').length == 0 ) {
            $('table.historico').children('tbody').children('tr').remove();
        }

        // if ( searchTr.length ) {// Se verifica si el artículo fue previamente registrado y se edita el row

        //     searchTr.data('row', metodoObj);
        //     searchTr.children('td').siblings("td:nth-child(2)").text(folio ? folio : 'No aplica');
        //     searchTr.children('td').siblings("td:nth-child(3)").data('total', total);
        //     searchTr.children('td').siblings("td:nth-child(3)").text('$'+total+' mxn');
        //     console.log(metodoObj);
            
        // } else {// Se llena la información del item
            $(".historico tbody").append(
                '<tr data-row-id='+timerId+' class="row-item" data-row=' + "'" + JSON.stringify(registro) + "'" + '>' +
                    '<td>'+ ( registro.tipo_historial ) +'</td>'+
                    '<td>'+ ( registro.articulo ) +'</td>'+
                    // '<td>'+ ( registro.status_articulo ) +'</td>'+
                    '<td>'+ ( registro.talla ) +'</td>'+
                    '<td>'+ ( registro.color ) +'</td>'+
                    '<td>'+ ( registro.cantidad ) +'</td>'+
                    '<td>'+ ( registro.fecha_entrega ) +'</td>'+
                    '<td>'+ ( registro.supervisor ) +'</td>'+
                    '<td>'+ ( registro.servicio_guardia ) +'</td>'+
                    '<td>'+ ( registro.notas ) +'</td>'+
                    '<td class="text-center">'+
                        '<button class="btn btn-sm btn-danger delete-row-historial" data-table-ref=".historico" data-row-id='+timerId+'> <i class="mdi mdi-trash-can"></i> </button>'+
                    '</td>'+
                '</tr>'
            );

        // }
    };
    
    // Método para guardar el registro de uniformes
    function guardarRegistro() {

        let historialArr = [];
        let config = {};
        let route = document.getElementById('form-data').action;

        $('table.historico tbody tr.row-item').each(function(i,e) {
            historialArr.push( $(this).data('row') );
        });

        if ( historialArr.length == 0 ) {
            swal('Error', 'Debe agregar al menos 1 registro de uniformes para continuar.', 'error');
            return;
        }

        // // Se setea cada input del formulario
        let formData = new FormData($("form#form-data")[0]);
        formData.append('historial', JSON.stringify( historialArr ));

        config["route"]    = route;
        config["redirect"] = true;
        config["method"]   = 'POST';

        console.log('config', config);
        loadingMessage('Enviando información, espere...');
        ajaxFormCustom(formData, config);
    }

    // Elimina un registro de la tabla
    $('body').delegate('.delete-row-historial','click', function() {
        let row   = $(this).parent().parent().data('row');
        let table = $(this).data('table-ref');
        let id    = ( row && row.timer_id ? row.timer_id : 0 );

        swal({
            title: '¿Desea eliminar el registro seleccionado?',
            icon: 'warning',
            buttons:["Cancelar", "Aceptar"],
            dangerMode: true,
        }).then((accept) => {
            if ( accept ) {
                $(table).children('tbody').children('tr[data-row-id="'+id+'"]').remove();

                // Si no hay más uniformes, se coloca el mensaje por default
                if (! $(table).children('tbody').children('tr.row-item').length ) {
                    $(table).append(
                        '<tr>'+
                            '<td colspan="9">Sin registros</td>'+
                        '</tr>'
                    )
                }
            }
        }).catch(swal.noop);
    });
</script>
@endsection