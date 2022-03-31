@extends('layouts.main')

@section('content')
@include('facturas.modal')
<section class="admin-content">
    <div class=" bg-dark m-b-30 bg-stars">
        <div class="container">
            <div class="row">
                <div class="col-md-8 m-auto text-white p-t-40 p-b-90">
                    <h1>Facturas</h1>
                    <p class="opacity-75">
                        Aquí podrá visualizar y modificar las facturas creadas.
                    </p>
                </div>
                <div class="col-md-4 m-auto text-white p-t-40 p-b-90 general-info" data-url="{{url("facturas")}}" data-refresh="table" data-el-loader="card">
                    
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
                            <a href="javascript:;" class="btn btn-info export-rows"> <i class="mdi mdi-file-excel"></i> Exportar</a>
                            @if( auth()->user()->permisos()->where('permisos.alias', 'facturas_editar')->exists() )
                                @if( $statusCliente->nombre == 'activo' )
                                    <a href="{{url('facturas/form')}}"><button class="btn btn-success" type="button"> <i class="mdi mdi-open-in-new"></i> Nueva factura</button></a>
                                @endif
                            @endif
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
                                    <div class="no-pad col-md-4 d-none">
                                        <input type="text" class="form-control" name="status_cliente_id" value="{{$statusCliente->id}}" autocomplete="off" placeholder="Status cliente">
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
                            @include('facturas.table')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
    //For refund an order
    $('body').delegate('.cancel-row', 'click', function() {
        id = $(this).data('row-id');
        numero = $(this).data('row-numero');
        url = $('div.general-info').data('url');
        $('div#modal-cancelar-factura input[name=factura_id]').val(id);
        $('div#modal-cancelar-factura input[name=factura_numero]').val(numero);
        $('div#modal-cancelar-factura').modal();
    });

    //Abre el modal para enviar plantillas
    $('body').delegate('.show-detail-row', 'click', function() {
        var id = $(this).data('row-id');

        config = {
            'factura_id'        : id,
            'keepModal' : true,
            'route'     : "{{url('facturas/get-details')}}",
            'method'    : 'POST',
            'callback'  : 'fill_bill_details',
        }
        
        loadingMessage('Espere un momento porfavor...');

        ajaxSimple(config);
    });

    //Recargará la oficina cada que se seleccione una franquicia distinta
    $('select[name=razon_social_id]').change(function() {
        var razon_social_id = $(this).val();
        var status_cliente_id = $('input[name=status_cliente_id]').val();
        var parent = '.div-clientes';
        var select = 'select[name=cliente_id]';
        if ( razon_social_id ) {
            config = {
                "razon_social_id"  : razon_social_id,
                "status_cliente_id": status_cliente_id,
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
</script>
@endsection