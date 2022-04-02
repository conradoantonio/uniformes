@extends('layouts.main')

@section('content')
@include('empleados.modal')
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
                    <h1>{{$title}}</h1>
                    <p class="opacity-75">
                        Aquí podrá visualizar y modificar los empleados del sistema web.
                    </p>
                </div>
                <div class="col-md-4 m-auto text-white p-t-40 p-b-90 general-info" data-url="{{url("empleados")}}" data-refresh="table" data-el-loader="refreshable">
                    
                </div>
            </div>
        </div>
    </div>

    <div class="container pull-up">
        <div class="row">
            {{-- Table --}}
            <div class="col-lg-12 m-b-30">
                <div class="card refreshable">
                    <div class="card-header">
                        <h2 class="no-color">&nbsp;</h2>
                        <div class="card-controls">
                            <a href="javascript:;" class="icon refresh-content"><i class="mdi mdi-refresh"></i> </a>
                            <a href="javascript:;" class="btn btn-dark filter-rows"> <i class="mdi mdi-filter-variant"></i> Filtrar</a>
                            @if( auth()->user()->permisos()->where('permisos.alias', 'empleados_editar')->exists() && $status->id == 1 )
                            <a href="{{url('empleados/form')}}" class="btn btn-success"> <i class="mdi mdi-open-in-new"></i> Nuevo registro</a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row m-b-20">
                            <div class="col-md-3 my-auto">
                                <h4 class="m-0">Filtros</h4>
                            </div>
                            <div class="col-md-9 text-right my-auto filter-section">
                                <div class="btn-group row" role="group" aria-label="Basic example">
                                    <div class="no-pad col-md-4" style="text-align: left;">
                                        <select id="razon_social_id" name="razon_social_id" class="form-control not-empty select2" data-msg="Razón social">
                                            <option value="" selected>Razón social (Cualquiera)</option>
                                            @foreach($razones as $razon)
                                                <option value="{{$razon->id}}">{{$razon->nombre}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="no-pad col-md-4 div-empleados" style="text-align: left;">
                                        <select id="empleado_id" name="empleado_id" class="form-control not-empty select2" data-msg="empleados">
                                            <option value="" selected>empleados (Cualquiera)</option>
                                        </select>
                                    </div>
                                    <div class="no-pad col-md-4 d-none">
                                        <input type="text" class="form-control" name="status_empleado_id" value="{{$status->id}}" autocomplete="off" placeholder="Status empleado">
                                    </div>
                                    <div class="no-pad col-md-4">
                                        <input type="text" class="form-control" name="num_empleado" autocomplete="off" placeholder="Número de empleado">
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
                        <div class="table-responsive rows-container">
                            @include('empleados.table')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
    //Genera el estado de cuenta
    $('body').delegate('.generar-historico', 'click', function() {
        id = $(this).data('row-id');
        nombre = $(this).data('row-name');
        url = $('div.general-info').data('url');
        $('div#modal-historico input[name=empleado_id]').val(id);
        $('div#modal-historico input[name=empleado_name]').val(nombre);
        $('div#modal-historico').modal();
    });

     //Cambiar status del empleado
     $('body').delegate('.cambiar-status', 'click', function() {
        id = $(this).data('row-id');
        nombre = $(this).data('row-name');
        url = $('div.general-info').data('url');
        $('div#modal-change-status input[name=empleado_id]').val(id);
        $('div#modal-change-status input[name=empleado_nombre]').val(nombre);
        $('div#modal-change-status').modal();
    });

    // Genera el estado de cuenta para un empleado
    $('body').delegate('.btn-generar-historico', 'click', function() {
        url = baseUrl.concat('/empleados/generar-historico');

        empleado_id = $('div#modal-historico input[name=empleado_id]').val();
        fecha_inicio = $('div#modal-historico input[name=fecha_inicio]').val();
        fecha_fin = $('div#modal-historico input[name=fecha_fin]').val();

        url = url.concat('?empleado_id='+empleado_id);
        url = url.concat('&fecha_inicio='+fecha_inicio);
        url = url.concat('&fecha_fin='+fecha_fin);
        // console.log(url);
        window.location.href = url;
    });

    //Recargará la lista de empleados cada que se seleccione una razón social distinta
    $('select[name=razon_social_id]').change(function() {
        var razon_social_id = $(this).val();
        var status_empleado_id = $('input[name=status_empleado_id]').val();
        var parent = '.div-empleados';
        var select = 'select[name=empleado_id]';
        if ( razon_social_id ) {
            config = {
                "razon_social_id"  : razon_social_id,
                "status_empleado_id": status_empleado_id,
                "only_data"        : true,
                "route"            : '{{url('empleados/filter')}}',
                "parent"           : parent,
                "select"           : select,
                "con_razon_social" : true,
                "select_2"         : true,
                "with_company"     : {{auth()->user()->role_id == 1 ? 1 : 0}} ,
                "first_item"       : 'empleados (Cualquiera)',
                "callback"         : 'fillCustomerSelect',
            }
            ajaxSimple(config);
            blockElement(parent);
        } else {
            $( select ).children('option').remove();
            $( select ).append('<option value="">empleados (Cualquiera)</option>');
            $( select ).select2('destroy');
            $( select ).select2();
        }
    });
</script>
@endsection