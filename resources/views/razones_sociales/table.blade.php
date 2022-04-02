<table class="table table-hover table-sm data-table">
    <thead>
        <tr>
            <th class="d-none">ID</th>
            <th>Nombre</th>
            <th>Número de empleados asignados</th>
            <th>Fecha de creación</th>
            @if( auth()->user()->permisos()->where('permisos.alias', 'razones_editar')->exists() )
            <th class="text-center">Acciones</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
            <tr>
                <td class="d-none">{{$item->id}}</td>
                <td class="align-middle">{{$item->nombre}}</td>
                <td class="align-middle">{{$item->empleados->count()}}</td>
                {{-- <td class="align-middle">{{$item->created_at->toFormattedDateString()}}</td> --}}
                <td class="align-middle">{{strftime('%d', strtotime($item->created_at)).' de '.strftime('%B', strtotime($item->created_at)). ' del '.strftime('%Y', strtotime($item->created_at))}}</td>
                @if( in_array(auth()->user()->role->descripcion, ['Administrador', 'Escritura']) )
                <td class="text-center align-middle">
                    {{-- <button class="btn btn-info btn-sm generar-excel-historial" data-row-name="{{$item->nombre}}" data-row-id="{{$item->id}}" data-toggle="tooltip" data-placement="top" title="Descargar excel de facturas de clientes"><i class="mdi mdi-format-list-checkbox"></i></button> --}}
                    @if( auth()->user()->permisos()->where('permisos.alias', 'razones_editar')->exists() )
                        @if(! $item->deleted_at )
                            <a class="btn btn-dark btn-sm" href="{{url('razones-sociales/form/'.$item->id)}}" data-toggle="tooltip" data-placement="top" title="Editar"><i class="mdi mdi-square-edit-outline"></i></a>
                            <button class="btn btn-danger btn-sm delete-row" data-row-id="{{$item->id}}" data-toggle="tooltip" data-placement="top" title="Deshabilitar"><i class="mdi mdi-close-circle"></i></button>
                        @else
                            <button class="btn btn-success btn-sm enable-row" data-row-id="{{$item->id}}" data-change-to="1" data-toggle="tooltip" data-placement="top" title="Habilitar"><i class="mdi mdi-check"></i></button>
                        @endif
                    @endif
                </td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>