@extends('layouts.main')

@section('content')
<section class="admin-content">
    <div class="bg-dark m-b-30 bg-stars">
        <div class="container">
            <div class="row">
                <div class="col-md-6 m-auto text-white p-t-20 p-b-90">
                    <h1>Pagos</h1>
                </div>
                <div class="col-md-6 m-auto text-white p-t-20 p-b-90">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-b-0 bg-transparent ol-breadcrum float-right">
                            <li class="breadcrumb-item active" aria-current="page"><a href="{{url('pagos')}}"></a>Formulario</li>
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
                        <h2 class="">Complete el formulario de pagos</h2>
                    </div>
                    <div class="card-body">
                        <form id="form-data" action="{{url('pagos/'.($item ? 'update' : 'save'))}}" onsubmit="return false;" enctype="multipart/form-data" method="POST" autocomplete="off" data-ajax-type="ajax-form" data-column="0" data-refresh="" data-redirect="1" data-table_id="example3" data-container_id="table-container">
                            <div class="row">
                                <div class="form-group floating-label" style="display: none;">
                                    <label>ID</label>
                                    <input type="text" class="form-control" name="id" value="{{$item ? $item->id : ''}}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="control-label" for="type">Cliente</label>
                                    <select id="cliente_id" name="cliente_id" class="form-control not-empty select2" data-msg="Cliente">
                                        <option value="" selected>Seleccione una opción</option>
                                        @if ( $item )
                                            @foreach($clientes as $cliente)
                                                <option value="{{$cliente->id}}" {{$item->cliente_id == $cliente->id ? 'selected' : ''}}>{{$cliente->nombre}}</option>
                                            @endforeach
                                        @else
                                            @foreach($clientes as $cliente)
                                                <option value="{{$cliente->id}}">{{$cliente->nombre}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group col-md-6 div-factura">
                                    <label class="control-label" for="type">Número de factura</label>
                                    <select id="factura_id" name="factura_id" class="form-control not-empty select2" data-msg="Número de factura">
                                        <option value="" selected>Seleccione una opción</option>
                                        @if ( $item )
                                            @foreach($facturas as $factura)
                                                <option value="{{$factura->id}}" {{$item->factura_id == $factura->id ? 'selected' : ''}}>Folio: {{$factura->numero_factura}} ( ${{$factura->importe}} mxn)</option>
                                            @endforeach
                                        @else
                                            @foreach($facturas as $factura)
                                                <option value="{{$factura->id}}">Folio: {{$factura->numero_factura}} ( ${{$factura->importe}} mxn)</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Número de pago*</label>
                                    <input type="text" class="form-control not-empty" value="{{$item ? $item->folio : ''}}" name="folio" data-msg="Número de pago">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Importe*</label>
                                    <input type="text" class="form-control not-empty numeric" value="{{$item ? $item->importe : ''}}" name="importe" data-msg="Importe">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Fecha de pago*</label>
                                    <input type="text" class="form-control not-empty date-picker" value="{{$item ? $item->fecha_pago : ''}}" name="fecha_pago" data-msg="Fecha de pago">
                                </div>
                                <div class="form-group col-md-12 m-t-15">
                                    <a href="{{url('pagos')}}"><button type="button" class="btn btn-primary">Regresar</button></a>
                                    <button type="submit" class="btn btn-success save">Guardar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
    //Recargará la oficina cada que se seleccione una franquicia distinta
    $('select[name=cliente_id]').change(function() {
        var cliente_id = $(this).val();
        var parent = 'div-factura';
        var select = 'select[name=factura_id]';
        if ( cliente_id ) {
            config = {
                "cliente_id"     : cliente_id,
                "only_data"     : true,
                "route"         : '{{url('facturas/filter')}}',
                "parent"        : parent,
                "select"        : select,
                "select_2"      : true,
                "pagada"        : 0,
                "first_item"    : 'Seleccione una opción',
                "callback"      : 'fillSelect',
            }
            ajaxSimple(config);
            blockElement(parent);
        }

    });

    //Helper to fill a select
    function fillSelect(data, config = null) {
        items = data.data;
        target = config.select;
        textFirst = config.first_item;
        disabled = config.first_disabled;
        select2 = config.select_2;

        if ( select2 ) {
            $(target).select2('destroy');
        }

        $ ( target ).children('option').remove();

        $( target ).append('<option value="" '+(disabled ? 'disabled' : '')+'>'+(textFirst ? textFirst : 'Seleccione una opción')+'</option>');
        
        items.forEach(function ( option ) {
            $( target ).append('<option value="'+option.id+'">Folio: ' + option.numero_factura + (' ( $' + option.importe + ' mxn )') +'</option>');
        });

        if ( select2 ) {
            $( target ).select2();
        }

        unBlockElement(config.parent);
        //$('.counter').text(items.length);
    }
</script>
@endsection