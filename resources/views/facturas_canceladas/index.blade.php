@extends('layouts.main')

@section('content')
@include('facturas_canceladas.modal')
<section class="admin-content">
    <div class=" bg-dark m-b-30 bg-stars">
        <div class="container">
            <div class="row">
                <div class="col-md-8 m-auto text-white p-t-40 p-b-90">
                    <h1>Facturas canceladas</h1>
                    <p class="opacity-75">
                        Aquí podrá visualizar y modificar las facturas canceladas.
                    </p>
                </div>
                <div class="col-md-4 m-auto text-white p-t-40 p-b-90 general-info" data-url="{{url("facturas/canceladas")}}" data-refresh="table" data-el-loader="card">
                    
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
                        </div>
                        <div class="row m-b-20">
                            <div class="col-md-3 my-auto">
                                <h4 class="m-0">Filtros</h4>
                            </div>
                            <div class="col-md-9 text-right my-auto filter-section">
                                <div class="btn-group row" role="group" aria-label="Basic example">
                                    {{-- <div class="no-pad" style="text-align: left;">
                                        <select id="status_id" name="status_id" class="form-control not-empty select2" data-msg="Tipo">
                                            <option value="" selected>Status (Cualquiera)</option>
                                            @foreach($status as $st)
                                                <option value="{{$st->id}}">{{$st->nombre}}</option>
                                            @endforeach
                                        </select>
                                    </div> --}}
                                    <div class="no-pad col-md-4" style="text-align: left;">
                                        <select id="cliente_id" name="cliente_id" class="form-control not-empty select2" data-msg="Clientes">
                                            <option value="" selected>Clientes (Cualquiera)</option>
                                            @foreach($clientes as $cliente)
                                                <option value="{{$cliente->id}}">{{$cliente->nombre}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="no-pad col-md-4 d-none">
                                        <input type="text" class="date-picker form-control" name="pagada" value="" autocomplete="off" placeholder="Fecha fin">
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
                            @include('facturas_canceladas.table')
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
</script>
@endsection