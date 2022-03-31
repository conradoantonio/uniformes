@extends('layouts.main')

@section('content')
<section class="admin-content">
    <div class=" bg-dark m-b-30 bg-stars">
        <div class="container">
            <div class="row">
                <div class="col-md-6 m-auto text-white p-t-40 p-b-90">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-b-0 bg-transparent ol-breadcrum">
                            <li class="breadcrumb-item"><a href="javascript:;">Usuarios</a></li>
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
                        <h2 class="">Ingresa los datos del usuario</h2>
                    </div>
                    {{-- @if( $item )
                        <div class="text-center">
                            <img class="profile-img-form" src="{{asset($item->photo)}}">
                        </div>
                    @endif --}}
                    <div class="card-body">
                        <form id="form-data" action="{{url('usuarios/'.($item ? 'update' : 'save'))}}" onsubmit="return false;" enctype="multipart/form-data" method="POST" autocomplete="off" data-ajax-type="ajax-form" data-column="0" data-refresh="" data-redirect="1" data-table_id="example3" data-container_id="table-container">
                            <div class="text-center">
                                <label class="avatar-input">
                                    <span class="avatar avatar-xxl">
                                        <img src="{{asset($item ? $item->photo : 'img/users/default.jpg')}}" alt="..." class="avatar-img avatar-profile-img rounded-circle">
                                        <span class="avatar-input-icon rounded-circle"><i class="mdi mdi-upload mdi-24px"></i></span>
                                    </span>
                                    <input type="file" name="photo" class="avatar-file-picker file image" data-target="avatar-profile-img" data-msg="Foto de perfil">
                                </label>
                            </div>
                            <div class="form-row">
                                <div class="form-group d-none">
                                    <label>ID</label>
                                    <input type="text" class="form-control" name="id" value="{{$item ? $item->id : ''}}">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Nombre</label>
                                    <input type="text" class="form-control not-empty" name="fullname" value="{{$item ? $item->fullname : ''}}" placeholder="Nombre completo del usuario" data-msg="Nombre completo">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Correo</label>
                                    <input type="email" class="form-control not-empty" name="email" value="{{$item ? $item->email : ''}}" placeholder="Correo" data-msg="Correo">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Contraseña</label>
                                    <input type="text" class="form-control pass-font {{$item ? '' : 'not-empty'}}" name="password" placeholder="Contraseña" data-msg="Contraseña">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Teléfono</label>
                                    <input type="text" class="form-control" name="telefono" value="{{$item ? $item->telefono : ''}}" placeholder="Teléfono" data-msg="Teléfono">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="razones_sociales_ids" class="control-label">Razones sociales</label>
                                    <select class="select2 form-control not-empty" data-msg="Razones sociales" style="width: 100%;" name="razones_sociales_ids[]" data-live-search="true" multiple>
                                        <option value="" disabled>Seleccione una opción</option>
                                        @if( $item && $item->razones->count() )
                                            @foreach( $razones as $razon )
                                                <option value="{{$razon->id}}" @foreach($item->razones as $rs) {{($rs->id == $razon->id ? 'selected' : '')}} @endforeach >{{$razon->nombre}}</option>
                                            @endforeach
                                        @else
                                            @foreach( $razones as $razon )
                                                <option value="{{$razon->id}}">{{$razon->nombre}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                {{-- <div class="form-group show-label col-md-6">
                                    <label for="type">Razón social</label>
                                    <select id="razon_social_id" name="razon_social_id" class="form-control select2 not-empty" data-msg="Razón social">
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
                                </div> --}}
                            </div>
                            <hr>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <h2>Permisos</h2>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="permisos_ids" class="control-label">Permisos</label>
                                    <select class="select2 form-control not-empty" data-msg="Permisos" style="width: 100%;" name="permisos_ids[]" data-live-search="true" multiple>
                                        <option value="" disabled>Seleccione una opción</option>
                                        @if( $item && $item->permisos->count() )
                                            @foreach( $permisos as $permiso )
                                                <option value="{{$permiso->id}}" @foreach($item->permisos as $per) {{($per->id == $permiso->id ? 'selected' : '')}} @endforeach >{{$permiso->nombre}}</option>
                                            @endforeach
                                        @else
                                            @foreach( $permisos as $permiso )
                                                <option value="{{$permiso->id}}">{{$permiso->nombre}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group m-t-15">
                                <a href="{{url('usuarios')}}"><button type="button" class="btn btn-primary">Regresar</button></a>
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