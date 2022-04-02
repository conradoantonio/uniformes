@extends('layouts.main')

@section('content')
<section class="admin-content">
    <div class=" bg-dark m-b-30 bg-stars">
        <div class="container">
            <div class="row">
                <div class="col-md-8 m-auto text-white p-t-40 p-b-90">
                    <h1>articulos</h1>
                    <p class="opacity-75">
                        Aquí podrá visualizar y modificar las articulos creadas.
                    </p>
                </div>
                <div class="col-md-4 m-auto text-white p-t-40 p-b-90 general-info" data-url="{{url("articulos")}}" data-refresh="table" data-el-loader="card">
                    
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
                            {{-- <a href="javascript:;" class="btn btn-info export-rows d-none"> <i class="mdi mdi-file-excel"></i> Exportar</a> --}}
                            @if( auth()->user()->permisos()->where('permisos.alias', 'articulos_editar')->exists() )
                                <a href="{{url('articulos/form')}}"><button class="btn btn-success" type="button"> <i class="mdi mdi-open-in-new"></i> Nueva artículo</button></a>
                            @endif
                        </div>
                        <div class="row m-b-20 d-none">
                            <div class="col-md-3 my-auto">
                                <h4 class="m-0">Filtros</h4>
                            </div>
                            <div class="col-md-9 text-right my-auto filter-section">
                                <div class="btn-group row" role="group" aria-label="Basic example">
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
                            @include('articulos.table')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection