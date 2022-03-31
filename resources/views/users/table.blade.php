<table class="table table-hover table-sm data-table">
    <thead>
        <tr>
            <th class="d-none">ID</th>
            <th>Nombre</th>
            <th>Razones sociales</th>
            <th>Correo</th>
            <th>Status</th>
            <th>Fecha de creación</th>
            @if( auth()->user()->permisos()->where('permisos.alias', 'usuarios_editar')->exists() )
            <th class="text-center">Acciones</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
            <tr>
                <td class="d-none">{{$item->id}}</td>
                <td class="align-middle">
                    <div class="avatar avatar-sm">
                        <img src="{{ asset($item->photo)}}" class="avatar-img avatar-sm rounded-circle" alt="user-image">
                    </div>
                    <span class="ml-2">{{$item->fullname}}</span>
                </td>
                <td class="align-middle">
                    @forelse($item->razones as $razonUser)
                        <span class="badge badge-info">{{$razonUser->nombre}}</span>
                    @empty
                        <span class="badge badge-danger">Sin razones sociales asignadas</span>
                    @endforelse
                </td>
                <td class="align-middle"><span class="badge badge-primary">{{$item->email}}</span></td>
                <td class="align-middle">{!! $item->deleted_at ? '<span class="badge badge-danger">Dado de baja</span>' : '<span class="badge badge-success">Activo</span>' !!}</td>
                <td class="align-middle">{{strftime('%d', strtotime($item->created_at)).' de '.strftime('%B', strtotime($item->created_at)). ' del '.strftime('%Y', strtotime($item->created_at))}}</td>
                @if( auth()->user()->permisos()->where('permisos.alias', 'usuarios_editar')->exists() )
                <td class="text-center align-middle">
                    <a class="btn btn-dark btn-sm" href="{{url('usuarios/form/'.$item->id)}}" data-toggle="tooltip" data-placement="top" title="Editar"><i class="mdi mdi-square-edit-outline"></i></a>
                    <button class="btn btn-danger btn-sm delete-row" data-row-id="{{$item->id}}" data-toggle="tooltip" data-placement="top" title="Eliminar"><i class="mdi mdi-close-circle"></i></button>
                </td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>