<table class="table table-hover table-sm data-table">
    <thead>
        <tr>
            <th class="d-none">ID</th>
            <th>Artículo</th>
            <th>Descripción</th>
            <th>Creado en</th>
            @if( auth()->user()->permisos()->where('permisos.alias', 'articulos_editar')->exists() )
            <th class="text-center">Acciones</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
            <tr>
                <td class="align-middle d-none">{{$item->id}}</td>
                <td class="align-middle">
                    <span class="ml-2">{{$item->nombre}}</span>
                </td>
                <td class="align-middle">{{$item->descripcion}}</td>
                <td class="align-middle">{{strftime('%d', strtotime($item->created_at)).' de '.strftime('%B', strtotime($item->created_at)). ' del '.strftime('%Y', strtotime($item->created_at))}}</td>
                @if( auth()->user()->permisos()->where('permisos.alias', 'articulos_editar')->exists() )
                <td class="text-center align-middle">
                    <a class="btn btn-dark btn-sm" href="{{url('articulos/form/'.$item->id)}}" data-toggle="tooltip" data-placement="top" title="Editar"><i class="mdi mdi-square-edit-outline"></i></a>
                    <button class="btn btn-danger btn-sm delete-row" data-row-id="{{$item->id}}" data-toggle="tooltip" data-placement="top" title="Eliminar"><i class="mdi mdi-trash-can"></i></button>
                </td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>