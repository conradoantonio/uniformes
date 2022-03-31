@extends('layouts.main')

@section('content')
@include('notificaciones.modal')
<section class="admin-content">
    <div class=" bg-dark m-b-30 bg-stars">
        <div class="container">
            <div class="row">
                <div class="col-md-8 m-auto text-white p-t-40 p-b-90">
                    <h1>Notificaciones</h1>
                    <p class="opacity-75">
                        Aquí podrá visualizar y modificar las notificaciones disponibles en el sistema para el registro y validación de visitas de los vendedores.
                    </p>
                </div>
                <div class="col-md-4 m-auto text-white p-t-40 p-b-90 general-info" data-url="{{url("notificaciones")}}" data-refresh="table" data-el-loader="card">
                    
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
                            <a href="javascript:;" class="btn btn-dark filter-rows">Filtrar</a>
                        </div>
                        <div class="row m-b-20">
                            <div class="col-md-3 my-auto">
                                <h4 class="m-0">Filtros</h4>
                            </div>
                            <div class="col-md-9 text-right my-auto filter-section">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <div class="no-pad" style="text-align: left;">
                                        <select id="tipo_notificacion_id" name="tipo_notificacion_id" class="form-control not-empty select2" data-msg="Tipo">
                                            <option value="" selected>Tipo (Cualquiera)</option>
                                            @foreach($tipos as $tipo)
                                                <option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="no-pad" style="text-align: left;">
                                        <select id="vendedor_id" name="vendedor_id" class="form-control not-empty select2" data-msg="Vendedor">
                                            <option value="" selected>Vendedor (Cualquiera)</option>
                                            @foreach($users as $user)
                                                <option value="{{$user->id}}">{{$user->fullname}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="no-pad">
                                        <input type="text" class="date-picker form-control" name="fecha_inicio" autocomplete="off" placeholder="Fecha inicio">
                                    </div>
                                    <div class="no-pad">
                                        <input type="text" class="date-picker form-control" name="fecha_fin" autocomplete="off" placeholder="Fecha fin">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive rows-container">
                            @include('notificaciones.table')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
    $('body').delegate('.view-details', 'click', function() {
        var id = $(this).parent().siblings("td:nth-child(1)").text();

        config = {
            'id'        : id,
            'keepModal' : true,
            'route'     : "{{url('notificaciones/get-info')}}",
            'method'    : 'POST',
            'callback'  : 'display_details',
        }

        loadingMessage('Espere un momento...');

        ajaxSimple(config);
        /*Remover este open modal de aquí*/
    });
//Display the details data of the application
    function display_details(data) {
        var b_url = $('meta[name="base-url"]').attr('content');
        var user = data.user;
        var reporte = data.reporte;
        var tipo_notificacion = data.tipo;// Tipo de notificación
        var productos = [];
        // productos = data.reporte.productos;
        
        $('ul.ul-user').addClass('d-none');
        $('ul.ul-reporte').addClass('d-none');
        $("ul.destination-map").addClass('d-none');
        $("span.tipo_notificacion").parent().parent().addClass('d-none');
        $("span.tipo_notificacion").children().remove();
        $("span.reporte_tipo_nombre").parent().parent().addClass('d-none');
        $("span.reporte_tipo_nombre").children().remove();

        $("table#table-participantes tbody").children().remove();
        
        fill_text(data, null);

        if ( user ) {
            $('ul.ul-user').removeClass('d-none');
            fill_text(user, null, 'user_', false);//Creador
            $('li.user-foto img').attr('src', b_url.concat('/'+user.photo));
        }

        if ( reporte ) {
            $('ul.ul-reporte').removeClass('d-none');
            fill_text(reporte, null, 'reporte_', false);//Creador
            if ( reporte.tipo ) {
                $("span.reporte_tipo_nombre").parent().parent().removeClass('d-none');
                $("span.reporte_tipo_nombre").append('<span class="badge badge-'+reporte.tipo.clase+'">'+reporte.tipo.nombre+'</span>');
            }
        }

        if ( tipo_notificacion ) {
            $("span.tipo_notificacion").parent().parent().removeClass('d-none');
            $("span.tipo_notificacion").append('<span class="badge badge-'+tipo_notificacion.clase+'">'+tipo_notificacion.nombre+'</span>');
        }

        $('div#detalle-notificacion').modal();
    }
</script>
@endsection