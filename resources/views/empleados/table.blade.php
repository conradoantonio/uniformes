<table class="table table-hover table-sm data-table">
    <thead>
        <tr>
            <th class="d-none">ID</th>
            <th>Nombre</th>
            <th>Razón social</th>
            <th>Num. empleado</th>
            <th>Fecha de creación</th>
            <th class="text-center">Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
            <tr>
                <td class="d-none">{{$item->id}}</td>
                <td class="align-middle">
                    {{-- <div class="avatar avatar-sm">
                        <img src="{{ asset($item->photo)}}" class="avatar-img avatar-sm rounded-circle" alt="user-image">
                    </div> --}}
                    <span class="ml-2">{{$item->nombre}}</span>
                </td>
                <td class="align-middle">{!! $item->razon_social ? '<span class="badge badge-success">'.$item->razon_social->nombre.'</span>' : '<span class="badge badge-danger">N/A</span>' !!}</td>
                <td class="align-middle">{{$item->numero_empleado}}</td>
                <td class="align-middle">{{strftime('%d', strtotime($item->created_at)).' de '.strftime('%B', strtotime($item->created_at)). ' del '.strftime('%Y', strtotime($item->created_at))}}</td>
                <td class="text-center align-middle">
                    @if( auth()->user()->permisos()->where('permisos.alias', 'empleados_editar')->exists() )
                        <a class="btn btn-dark btn-sm" href="{{url('empleados/form/'.$item->id)}}" data-toggle="tooltip" data-placement="top" title="Editar"><i class="mdi mdi-square-edit-outline"></i></a>
                        {{-- <button class="btn btn-danger btn-sm delete-row" data-row-id="{{$item->id}}" data-toggle="tooltip" data-placement="top" title="Deshabilitar"><i class="mdi mdi-close-circle"></i></button> --}}
                    @endif
                    <button class="btn btn-info btn-sm generar-historico" data-row-name="{{$item->nombre}}" data-row-id="{{$item->id}}" data-toggle="tooltip" data-placement="top" title="Generar estado de cuenta"><i class="mdi mdi-file-excel"></i></button>
                    <button class="btn btn-warning btn-sm cambiar-status" data-row-name="{{$item->nombre}}" data-row-id="{{$item->id}}" data-toggle="tooltip" data-placement="top" title="Cambiar status"><i class="mdi mdi-format-list-checkbox"></i></button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>