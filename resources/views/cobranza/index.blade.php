@extends('layouts.main')

@section('content')
@include('cobranza.modal')
<style type="text/css">
    .datepicker {
      z-index: 1600 !important; /* has to be larger than 1050 */
    }
</style>
<section class="admin-content">
    <div class=" bg-dark m-b-30 bg-stars">
        <div class="container">
            <div class="row">
                <div class="col-md-8 m-auto text-white p-t-40 p-b-90">
                    <h1>Cobranza</h1>
                    <p class="opacity-75">
                        Aquí podrá visualizar las facturas no pagadas por los clientes para dar seguimiento a los mismos.
                    </p>
                </div>
                <div class="col-md-4 m-auto text-white p-t-40 p-b-90 general-info" data-url="{{url("cobranza")}}" data-refresh="table" data-el-loader="card">
                    
                </div>
            </div>
        </div>
    </div>

    <div class="container pull-up">
        <div class="row">
            {{-- Table --}}
            <div class="col-lg-12 m-b-30">
                <div class="card">
                    <div class="card-header">
                        <h2 class="no-color">&nbsp;</h2>
                        <div class="card-controls">
                            <a href="javascript:;" class="btn btn-dark filter-rows"> <i class="mdi mdi-filter-variant"></i> Filtrar</a>
                            <a href="javascript:;" class="btn btn-info export-rows" data-url="{{url('cobranza/excel/export?')}}"> <i class="mdi mdi-file-excel"></i> Exportar facturas</a>
                            <a href="javascript:;" class="btn btn-secondary export-rows" data-url="{{url('cobranza/comentarios/excel/export?')}}"> <i class="mdi mdi-comment-text-multiple"></i> Exportar comentarios</a>
                        </div>
                        <div class="row m-b-20">
                            <div class="col-md-3 my-auto">
                                <h4 class="m-0">Filtros</h4>
                            </div>
                            <div class="col-md-9 text-right my-auto filter-section">
                                <div class="btn-group row" role="group" aria-label="Basic example">
                                    <div class="no-pad col-md-4" style="text-align: left;">
                                        <select id="status_id" name="status_id" class="form-control not-empty select2" data-msg="Tipo">
                                            <option value="" selected>Status (Cualquiera)</option>
                                            @foreach($status as $st)
                                                <option value="{{$st->id}}">{{$st->nombre}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="no-pad col-md-4" style="text-align: left;">
                                        <select id="razon_social_id" name="razon_social_id" class="form-control not-empty select2" data-msg="Razón social">
                                            <option value="" selected>Razón social (Cualquiera)</option>
                                            @foreach($razones as $razon)
                                                <option value="{{$razon->id}}">{{$razon->nombre}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="no-pad col-md-4 div-clientes" style="text-align: left;">
                                        <select id="cliente_id" name="cliente_id" class="form-control not-empty select2" data-msg="Clientes">
                                            <option value="" selected>Clientes (Cualquiera)</option>
                                            @foreach($clientes as $cliente)
                                                <option value="{{$cliente->id}}">{{$cliente->nombre}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="no-pad col-md-4">
                                        <input type="text" class="date-picker form-control" name="fecha_inicio" autocomplete="off" placeholder="Fecha inicio">
                                    </div>
                                    <div class="no-pad col-md-4">
                                        <input type="text" class="date-picker form-control" name="fecha_fin" autocomplete="off" placeholder="Fecha fin">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive rows-container">
                            @include('cobranza.table')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
    // Visualizar comentarios
    $('body').delegate('.ver-comentarios', 'click', function() {
        var id = $(this).parent().siblings("td:nth-child(1)").text();

        config = {
            'id'        : id,
            'keepModal' : true,
            'route'     : baseUrl.concat('/cobranza/comentarios'),
            'method'    : 'POST',
            'callback'  : 'showComments',
        }

        loadingMessage('Espere un momento...');

        ajaxSimple(config);
    });

    //Recargará los clientes cada que se seleccione una razón social distinta
    $('select[name=razon_social_id]').change(function() {
        var razon_social_id = $(this).val();
        var parent = '.div-clientes';
        var select = 'select[name=cliente_id]';
        if ( razon_social_id ) {
            config = {
                "razon_social_id"  : razon_social_id,
                "only_data"        : true,
                "route"            : '{{url('clientes/filter')}}',
                "parent"           : parent,
                "select"           : select,
                "con_razon_social" : true,
                "select_2"         : true,
                "with_company"     : {{auth()->user()->role_id == 1 ? 1 : 0}} ,
                "first_item"       : 'Clientes (Cualquiera)',
                "callback"         : 'fillCustomerSelect',
            }
            ajaxSimple(config);
            blockElement(parent);
        } else {
            $( select ).children('option').remove();
            $( select ).append('<option value="">Clientes (Cualquiera)</option>');
            $( select ).select2('destroy');
            $( select ).select2();
        }
    });

    //Send a request for a single delete
    $('body').delegate('.delete-comment-row','click', function() {
        var route = baseUrl.concat('/cobranza/comentarios/delete');
        var callback = 'removeDom';
        var ids_array = [];
        var factura_id = $('input[name="factura_id"]').val();
        var row_id = $(this).hasClass('special-row') ? $(this).data('row-id') : $(this).parent().siblings("td:nth-child(1)").text();
        ids_array.push(row_id);

        swal({
            title: 'Se dará de baja el registro con el ID '+row_id+', ¿Está seguro de continuar?',
            icon: 'warning',
            buttons:["Cancelar", "Aceptar"],
            dangerMode: true,
        }).then((accept) => {
            if (accept){
                config = {
                    'route'      : route,
                    'keepModal'  : true,
                    'factura_id' : factura_id,
                    'ids'        : ids_array,
                    'dom'        : ".comentario-tr-"+row_id,
                    'callback'   : callback,
                }
                loadingMessage();
                ajaxSimple(config);
            }
        }).catch(swal.noop);
    });

    // Agrega el comentario a la tabla
    function agregarComentarioTabla(response, config) {
        var comentario = response.data;
        $("table.tabla-comentarios tbody").append(
            '<tr class="comentario-tr-'+comentario.id+'">'+
                '<td class="align-middle d-nones">'+comentario.id+'</td>'+
                '<td class="align-middle">'+comentario.tipo.nombre+'</td>'+
                '<td class="align-middle">'+comentario.contacto+'</td>'+
                '<td class="align-middle">'+comentario.fecha+'</td>'+
                '<td class="align-middle">'+comentario.comentario+'</td>'+
                '<td class="align-middle">'+
                    '<button class="btn btn-danger btn-sm special-row delete-comment-row" data-row-id="'+comentario.id+'" data-toggle="tooltip" data-placement="top" title="Eliminar">'+
                        '<i class="mdi mdi-trash-can"></i>'+
                    '</button>'+
                '</td>'+
            '</tr>'
        );
    }

    function showComments(response, config) {
        item         = response.data;
        comentarios  = response.data.comentarios;

        $(".ul-comentarios table.tabla-comentarios tbody").children().remove();
        
        // Llena la información del pedido
        if ( item ) {
            fill_text(item, null, 'factura_', true);
            $('input[name="factura_id"]').val(item.id);
        }

        if ( comentarios.length ) {
            $('.ul-comentarios').removeClass('d-none');
            for ( var key in comentarios ) {
                if ( comentarios.hasOwnProperty( key ) ) {
                    var comentario = comentarios[key];

                    $("table.tabla-comentarios tbody").append(
                        '<tr class="comentario-tr-'+comentario.id+'">'+
                            '<td class="align-middle d-nones">'+comentario.id+'</td>'+
                            '<td class="align-middle">'+comentario.tipo.nombre+'</td>'+
                            '<td class="align-middle">'+comentario.contacto+'</td>'+
                            '<td class="align-middle">'+comentario.fecha+'</td>'+
                            '<td class="align-middle">'+comentario.comentario+'</td>'+
                            '<td class="align-middle">'+
                                '<button class="btn btn-danger btn-sm special-row delete-comment-row" data-row-id="'+comentario.id+'" data-toggle="tooltip" data-placement="top" title="Eliminar">'+
                                    '<i class="mdi mdi-trash-can"></i>'+
                                '</button>'+
                            '</td>'+
                        '</tr>'
                    );
                }
            }
            //$("table#tabla-comentarios-rate").parent('div').addClass('table-responsive');
        } else {
            // $("table.tabla-comentarios tbody").append(
            //     '<tr style="text-align: center;"><td class="align-middle" colspan="6">No hay fechas disponibles para este evento</td></tr>'
            // );
        }

        $('div#comentarios-modal').modal("show");
    }

    // Remove a dom element
    function removeDom(response, config) {
        $(config.dom).remove();
    }
</script>
@endsection