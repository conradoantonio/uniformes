<table class="table table-hover table-sm data-table">
    <thead>
        <tr>
            <th class="d-none">ID</th>
            <th>Cliente</th>
            <th>Razón social</th>
            <th>Folio</th>
            <th>Número de factura</th>
            <th>Importe</th>
            <th>Fecha de pago</th>
            <th>Creado en</th>
            @if( auth()->user()->permisos()->where('permisos.alias', 'pagos_editar')->exists() )
            <th class="text-center">Acciones</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
            <tr>
                <td class="align-middle d-none">{{$item->id}}</td>
                <td class="align-middle">
                    <span class="ml-2">{{$item->cliente->nombre}}</span>
                </td>
                <td class="align-middle">{!! $item->cliente && $item->cliente->razon_social ? '<span class="badge badge-info">'.$item->cliente->razon_social->nombre.'</span>' : '<span class="badge badge-dark">N/A</span>' !!}</td>
                <td class="align-middle">{{$item->folio}}</td>
                <td class="align-middle">{{$item->factura->numero_factura}}</td>
                <td class="align-middle">${{$item->importe}} mxn</td>
                <td class="align-middle">{{strftime('%d', strtotime($item->fecha_pago)).' de '.strftime('%B', strtotime($item->fecha_pago)). ' del '.strftime('%Y', strtotime($item->fecha_pago))}}</td>
                <td class="align-middle">{{strftime('%d', strtotime($item->created_at)).' de '.strftime('%B', strtotime($item->created_at)). ' del '.strftime('%Y', strtotime($item->created_at))}}</td>
                @if( auth()->user()->permisos()->where('permisos.alias', 'pagos_editar')->exists() )
                <td class="text-center align-middle">
                    <a class="btn btn-dark btn-sm" href="{{url('pagos/form/'.$item->id)}}" data-toggle="tooltip" data-placement="top" title="Editar"><i class="mdi mdi-square-edit-outline"></i></a>
                    <button class="btn btn-danger btn-sm delete-row" data-row-id="{{$item->id}}" data-toggle="tooltip" data-placement="top" title="Eliminar"><i class="mdi mdi-trash-can"></i></button>
                </td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>