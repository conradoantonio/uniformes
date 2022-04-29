@extends('layouts.main')

@section('content')
<section class="admin-content">
    <div class="bg-dark m-b-30 bg-stars">
        <div class="container">
            <div class="row">
                <div class="col-md-6 m-auto text-white p-t-20 p-b-90">
                    <h1>Artículo</h1>
                </div>
                <div class="col-md-6 m-auto text-white p-t-20 p-b-90">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-b-0 bg-transparent ol-breadcrum float-right">
                            <li class="breadcrumb-item active" aria-current="page"><a href="{{url('articulos')}}"></a>Formulario</li>
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
                        <h2 class="">Complete el formulario de artículo</h2>
                    </div>
                    <div class="card-body">
                        <form id="form-data" action="{{url('articulos/'.($item ? 'update' : 'save'))}}" onsubmit="return false;" enctype="multipart/form-data" method="POST" autocomplete="off" data-ajax-type="ajax-form" data-column="0" data-refresh="" data-redirect="1" data-table_id="example3" data-container_id="table-container">
                            <div class="row">
                                <div class="form-group floating-label d-none">
                                    <label>ID</label>
                                    <input type="text" class="form-control" name="id" value="{{$item ? $item->id : ''}}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Artículo*</label>
                                    <input type="text" class="form-control not-empty" value="{{$item ? $item->nombre : ''}}" name="nombre" data-msg="Artículo">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Talla</label>
                                    <input type="text" class="form-control" value="{{$item ? $item->talla : ''}}" name="talla" data-msg="Talla">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Color*</label>
                                    <input type="text" class="form-control not-empty" value="{{$item ? $item->color : ''}}" name="color" data-msg="Color">
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="control-label" for="type">Status del artículo*</label>
                                    <select id="status_articulo_id" name="status_articulo_id" class="form-control not-empty" data-msg="Status">
                                        <option value="" selected>Seleccione una opción</option>
                                        @if ( $item )
                                            @foreach($status as $st)
                                                <option value="{{$st->id}}" {{$item->status_articulo_id == $st->id ? 'selected' : ''}}>{{$st->nombre}}</option>
                                            @endforeach
                                        @else
                                            @foreach($status as $st)
                                                <option value="{{$st->id}}">{{$st->nombre}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Descripción</label>
                                    <textarea type="text" class="form-control" name="descripcion" placeholder="" data-msg="Descripción">{{$item ? $item->descripcion : ''}}</textarea>
                                </div>
                                <div class="form-group col-md-12 m-t-15">
                                    <a href="{{url('articulos')}}"><button type="button" class="btn btn-primary">Regresar</button></a>
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