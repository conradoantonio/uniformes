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
                            <li class="breadcrumb-item active" aria-current="page">Formulario</li>
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
                        <h2 class="">Ingresa los datos del empleado</h2>
                    </div>
                    <div class="card-body">
                        <form id="form-data" action="{{url('empleados/'.($item ? 'update' : 'save'))}}" onsubmit="return false;" enctype="multipart/form-data" method="POST" autocomplete="off" data-ajax-type="ajax-form" data-column="0" data-refresh="" data-redirect="1" data-table_id="example3" data-container_id="table-container">
                            <div class="form-row">
                                <div class="form-group" style="display: none;">
                                    <label>ID</label>
                                    <input type="text" class="form-control" name="id" value="{{$item ? $item->id : ''}}">
                                </div>
                            </div>
                            
                            <div class="form-row m-t-15">
                                <div class="form-group col-md-12">
                                    <label class="control-label" for="type">Razón social*</label>
                                    <select id="razon_social_id" name="razon_social_id" class="form-control not-empty" data-msg="Razón social">
                                        <option value="" selected>Seleccione una opción</option>
                                        @if ( $item )
                                            @foreach($razones as $razon)
                                                <option value="{{$razon->id}}" {{$item->razon_social_id == $razon->id ? 'selected' : ''}}>{{$razon->nombre}}</option>
                                            @endforeach
                                        @else
                                            @foreach($razones as $razon)
                                                <option value="{{$razon->id}}">{{$razon->nombre}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Nombre completo*</label>
                                    <input type="text" class="form-control not-empty" name="nombre" value="{{$item ? $item->nombre : ''}}" data-msg="Nombre completo">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Número de empleado</label>
                                    <input type="text" class="form-control" name="numero_empleado" value="{{$item ? $item->numero_empleado : ''}}" data-msg="Número empleado">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Fecha ingreso</label>
                                    <input type="text" class="form-control date-picker" name="fecha_ingreso" value="{{$item ? $item->fecha_ingreso : ''}}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Fecha baja</label>
                                    <input type="text" class="form-control date-picker" name="fecha_baja" value="{{$item ? $item->fecha_baja : ''}}">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Observaciones</label>
                                    <textarea name="observaciones" class="form-control" rows="4">{{$item ? $item->observaciones : ''}}</textarea>
                                </div>
                                
                            </div>
                                
                            <div class="form-group m-t-15">
                                @if( $item )
                                <a href="{{url('empleados?s='.$item->status->url)}}"><button type="button" class="btn btn-primary">Regresar</button></a>
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