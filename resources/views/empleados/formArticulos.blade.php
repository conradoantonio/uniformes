@extends('layouts.main')

@section('content')
<section class="admin-content">
    <div class=" bg-dark m-b-30 bg-stars">
        <div class="container">
            <div class="row">
                <div class="col-md-6 m-auto text-white p-t-40 p-b-90">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-b-0 bg-transparent ol-breadcrum">
                            <li class="breadcrumb-item"><a href="javascript:;">Clientes</a></li>
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
                        <form id="form-data" action="{{url('historicos/save')}}" onsubmit="return false;" enctype="multipart/form-data" method="POST" autocomplete="off" data-ajax-type="ajax-form" data-column="0" data-refresh="" data-redirect="1" data-table_id="example3" data-container_id="table-container">
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
                                        <option value="" selected>Seleccione una opción</option>
                                        <option value="1">Entrega</option>
                                        <option value="2">Recibo</option>
                                       
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="control-label" for="type">Artículo*</label>
                                    <select id="articulo_id" name="articulo_id" class="form-control not-empty" data-msg="Artículo">
                                        <option value="" selected>Seleccione una opción</option>
                                        @foreach($articulos as $articulo)
                                            <option value="{{$articulo->id}}">{{$articulo->nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="control-label" for="type">Talla*</label>
                                    <select id="talla_id" name="talla_id" class="form-control not-empty" data-msg="Talla">
                                        <option value="" selected>Seleccione una opción</option>
                                        @foreach($tallas as $talla)
                                            <option value="{{$talla->id}}">{{$talla->nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="control-label" for="type">Status del artículo*</label>
                                    <select id="status_articulo_id" name="status_articulo_id" class="form-control not-empty" data-msg="Status">
                                        <option value="" selected>Seleccione una opción</option>
                                        @foreach($status as $st)
                                            <option value="{{$st->id}}">{{$st->nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Color*</label>
                                    <input type="text" class="form-control not-empty" name="color" value="" data-msg="Color">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Cantidad*</label>
                                    <input type="text" class="form-control not-empty" name="cantidad" value="" data-msg="Cantidad">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Fecha entrega</label>
                                    <input type="text" class="form-control date-picker" name="fecha_entrega" value="">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Notas</label>
                                    <textarea name="notas" class="form-control" rows="3"></textarea>
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
                                <button type="submit" class="btn btn-success save">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection