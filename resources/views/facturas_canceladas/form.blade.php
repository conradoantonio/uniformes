@extends('layouts.main')

@section('content')
<section class="admin-content">
    <div class="bg-dark m-b-30 bg-stars">
        <div class="container">
            <div class="row">
                <div class="col-md-6 m-auto text-white p-t-20 p-b-90">
                    <h1>Factura</h1>
                </div>
                <div class="col-md-6 m-auto text-white p-t-20 p-b-90">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-b-0 bg-transparent ol-breadcrum float-right">
                            <li class="breadcrumb-item active" aria-current="page"><a href="{{url('facturas')}}"></a>Formulario</li>
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
                        <h2 class="">Complete el formulario de factura</h2>
                    </div>
                    <div class="card-body">
                        <form id="form-data" action="{{url('facturas/'.($item ? 'update' : 'save'))}}" onsubmit="return false;" enctype="multipart/form-data" method="POST" autocomplete="off" data-ajax-type="ajax-form" data-column="0" data-refresh="" data-redirect="1" data-table_id="example3" data-container_id="table-container">
                            <div class="row">
                                <div class="form-group floating-label" style="display: none;">
                                    <label>ID</label>
                                    <input type="text" class="form-control" name="id" value="{{$item ? $item->id : ''}}">
                                </div>
                                <div class="col-md-12">
                                    <div class="alert alert-border-info alert-dismissible fade show" role="alert">
                                        <div class="d-flex">
                                            <div class="icon">
                                                <i class="icon mdi mdi-alert-circle-outline"></i>
                                            </div>
                                            <div class="content">
                                                <strong>Nota:</strong> <br>
                                                - Al especificar la fecha de promesa de pago, se calculará el status de la factura día con día (Morosas, prelegales, legales).<br>
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
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
                                <div class="form-group col-md-6">
                                    <label>Número de factura*</label>
                                    <input type="text" class="form-control not-empty" value="{{$item ? $item->numero_factura : ''}}" name="numero_factura" data-msg="Número de factura">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Importe*</label>
                                    <input type="text" class="form-control not-empty numeric" value="{{$item ? $item->importe : ''}}" name="importe" data-msg="Importe">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Fecha facturación*</label>
                                    <input type="text" class="form-control not-empty date-picker" value="{{$item ? $item->fecha_facturacion : ''}}" name="fecha_facturacion" data-msg="Fecha de facturación">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Fecha de promesa de pago*</label>
                                    <input type="text" class="form-control not-empty date-picker" value="{{$item ? $item->fecha_promesa_pago : ''}}" name="fecha_promesa_pago" data-msg="Fecha de facturación">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Comentarios adicionales</label>
                                    <textarea type="text" class="form-control" name="comentarios_adicionales" placeholder="" data-msg="Comentarios adicionales">{{$item ? $item->comentarios_adicionales : ''}}</textarea>
                                </div>
                                @if( $item )
                                    <div class="form-group col-md-12">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="pagada" name="pagada">
                                            <label class="custom-control-label" for="pagada">Marcar como pagada</label>
                                        </div>
                                    </div>
                                @endif
                                <div class="form-group col-md-12 m-t-15">
                                    <a href="{{url('facturas')}}"><button type="button" class="btn btn-primary">Regresar</button></a>
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
@endsection