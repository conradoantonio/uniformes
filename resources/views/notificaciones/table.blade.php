<table class="table table-hover table-sm data-table">
    <thead>
        <tr>
            <th class="d-none">ID</th>
            <th>Titulo</th>
            <th>Tipo</th>
            <th>Vendedor</th>
            <th>Reporte</th>
            <th>Creado en</th>
            <th class="text-center">Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
            <tr>
                <td class="align-middle d-none">{{$item->id}}</td>
                <td class="align-middle">{{$item->titulo}}</td>
                <td class="align-middle">{!! '<span class="badge badge-'.$item->tipo->clase.'">'.$item->tipo->nombre.'</span>' !!}</td>
                <td class="align-middle">
                    <div class="avatar avatar-sm">
                        <img src="{{ asset($item->user->photo)}}" class="avatar-img avatar-sm rounded-circle" alt="user-image">
                    </div>
                    <span class="ml-2">{{$item->user->fullname}}</span>
                </td>
                <td class="align-middle">{!! $item->reporte ? '<span class="badge badge-success">Si</span>' : '<span class="badge badge-warning">No</span>' !!}</td>
                <td class="align-middle">{{strftime('%d', strtotime($item->created_at)).' de '.strftime('%B', strtotime($item->created_at)). ' del '.strftime('%Y', strtotime($item->created_at))}}</td>
                <td class="text-center align-middle">
                    <button class="btn btn-info btn-sm view-details" data-toggle="tooltip" data-placement="top" title="Ver detalles"><i class="mdi mdi-eye"></i></button>
                    <button class="btn btn-danger btn-sm delete-row" data-row-id="{{$item->id}}" data-toggle="tooltip" data-placement="top" title="Eliminar"><i class="mdi mdi-trash-can"></i></button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>