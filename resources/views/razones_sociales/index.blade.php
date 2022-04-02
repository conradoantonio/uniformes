@extends('layouts.main')

@section('content')
@include('razones_sociales.modal')
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
                    <h1>Razones sociales</h1>
                    <p class="opacity-75">
                        Aquí podrá visualizar y modificar las razones sociales disponibles del sistema web.
                    </p>
                </div>
                <div class="col-md-4 m-auto text-white p-t-40 p-b-90 general-info" data-url="{{url("razones-sociales")}}" data-refresh="table" data-el-loader="refreshable">
                    
                </div>
            </div>
        </div>
    </div>

    <div class="container pull-up">
        <div class="row">
            <div class="col-lg-12 m-b-30">
                <div class="card refreshable">
                    <div class="card-header">
                        <h2 class="no-color">&nbsp;</h2>
                        <div class="card-controls">
                            @if( auth()->user()->permisos()->where('permisos.alias', 'razones_editar')->exists() )
                            <a href="{{url('razones-sociales/form')}}" class="btn btn-success"> <i class="mdi mdi-open-in-new"></i> Nuevo registro</a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive rows-container">
                            @include('razones_sociales.table')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
    $(function() {
        // $('#cliente_id').select2({
        //     dropdownParent: $('#modal-excel-facturas-rs')
        // });
    });

    // Abre el modal para filtrar el adeudo de los empleados de cada razón social
    $('body').delegate('.generar-excel-facturas', 'click', function() {
        id = $(this).data('row-id');
        nombre = $(this).data('row-name');
        url = $('div.general-info').data('url');
        $('div#modal-excel-facturas-rs input[name=razon_social_id]').val(id);
        $('div#modal-excel-facturas-rs input[name=razon_social_nombre]').val(nombre);

        var parent = '.div-empleados';
        var select = 'select[name=cliente_id]';
        config = {
            "razon_social_id"  : id,
            "only_data"        : true,
            "route"            : '{{url('empleados/filter')}}',
            "parent"           : parent,
            "select"           : select,
            "dropdownParent"   : '#modal-excel-facturas-rs',
            "con_razon_social" : true,
            "select_2"         : true,
            "keepModal"        : true,
            "with_company"     : {{auth()->user()->role_id == 1 ? 1 : 0}} ,
            "first_item"       : 'Clientes (Cualquiera)',
            "callback"         : 'fillCustomerSelect',
        }
        ajaxSimple(config);
        blockElement(parent);

        $('select[name=status_cliente_id]').val("");

        $('div#modal-excel-facturas-rs').modal();
    });

    // Evento para click de generar excel por razón social
    $('body').delegate('.btn-generar-excel', 'click', function() {
        url = baseUrl.concat('/razones-sociales/excel/export/bills');

        razon_social_id   = $('div#modal-excel-facturas-rs input[name=razon_social_id]').val();
        cliente_id        = $('div#modal-excel-facturas-rs select[name=cliente_id]').val();
        status_cliente_id = $('div#modal-excel-facturas-rs select[name=status_cliente_id]').val();
        fecha_inicio      = $('div#modal-excel-facturas-rs input[name=fecha_inicio]').val();
        fecha_fin         = $('div#modal-excel-facturas-rs input[name=fecha_fin]').val();

        url = url.concat('?razon_social_id='+razon_social_id);
        url = url.concat('&cliente_id='+cliente_id);
        url = url.concat('&status_cliente_id='+status_cliente_id);
        url = url.concat('&fecha_inicio='+fecha_inicio);
        url = url.concat('&fecha_fin='+fecha_fin);
        // console.log(url);
        window.location.href = url;
    });

    // Abre el modal para filtrar el adeudo global de cada razón social
    $('body').delegate('.export-global-bill', 'click', function() {
        $('select[name=status_cliente_id]').val("");

        $('div#modal-global-excel-facturas').modal();
    });

    // Evento click para generar el adeudo global de cada razón social
    $('body').delegate('.btn-generar-excel-global-facturas', 'click', function() {
        url = baseUrl.concat('/razones-sociales/excel/export');

        status_cliente_id = $('div#modal-global-excel-facturas select[name=status_cliente_id]').val();
        // fecha_inicio      = $('div#modal-global-excel-facturas input[name=fecha_inicio]').val();
        // fecha_fin         = $('div#modal-global-excel-facturas input[name=fecha_fin]').val();

        url = url.concat('?status_cliente_id='+status_cliente_id);
        // url = url.concat('&fecha_inicio='+fecha_inicio);
        // url = url.concat('&fecha_fin='+fecha_fin);

        window.location.href = url;
    });

    //Recargará los empleados dependiendo de su status y razón social
    $('select[name=razon_social_id], select[name=status_cliente_id]').change(function() {
        console.log('hola');
        var razon_social_id = $('input[name=razon_social_id]').val();
        var status_cliente_id = $('select[name=status_cliente_id]').val();
        var parent = '.div-empleados';
        var select = 'select[name=cliente_id]';
        if ( razon_social_id ) {
            config = {
                "razon_social_id"   : razon_social_id,
                "status_cliente_id" : status_cliente_id,
                "only_data"         : true,
                "route"             : '{{url('empleados/filter')}}',
                "parent"            : parent,
                "select"            : select,
                "keepModal"         : true,
                "con_razon_social"  : true,
                "select_2"          : true,
                "with_company"      : {{auth()->user()->role_id == 1 ? 1 : 0}} ,
                "first_item"        : 'Clientes (Cualquiera)',
                "callback"          : 'fillCustomerSelect',
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
</script>
@endsection