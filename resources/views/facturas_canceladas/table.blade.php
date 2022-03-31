<table class="table table-hover table-sm data-table">
    <thead>
        <tr>
            <th class="d-none">ID</th>
            <th>Cliente</th>
            {{-- <th>Status</th> --}}
            <th>Número de factura</th>
            <th>Importe</th>
            {{-- <th>Balance</th> --}}
            {{-- <th>¿Pagada?</th> --}}
            <th>Razón de cancelación</th>
            <th>Fecha de facturación</th>
            <th>Creado en</th>
            @if( auth()->user()->permisos()->where('permisos.alias', 'facturas_canceladas_editar')->exists() )
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
                {{-- <td class="align-middle">{!! '<span class="badge badge-'.$item->status->clase.'">'.$item->status->nombre.'</span>' !!}</td> --}}
                <td class="align-middle">{{$item->numero_factura}}</td>
                <td class="align-middle">${{number_format($item->importe)}} mxn</td>
                {{-- <td class="align-middle">
                    ${{ number_format( $item->importe - ( $item->notas_credito->sum('importe') + $item->pagos->sum('importe') ) ) }} mxn
                </td> --}}
                {{-- <td class="align-middle">{!! $item->pagada == 1 ? '<span class="badge badge-success">Si</span>' : '<span class="badge badge-dark">No</span>' !!}</td> --}}
                <td class="align-middle">
                    {!! $item->cancelacion ? '<span class="badge badge-danger">'.$item->cancelacion->razon.'</span>' : 'N/A'!!}
                </td>
                <td class="align-middle">{{strftime('%d', strtotime($item->fecha_facturacion)).' de '.strftime('%B', strtotime($item->fecha_facturacion)). ' del '.strftime('%Y', strtotime($item->fecha_facturacion))}}</td>
                <td class="align-middle">{{strftime('%d', strtotime($item->created_at)).' de '.strftime('%B', strtotime($item->created_at)). ' del '.strftime('%Y', strtotime($item->created_at))}}</td>
                @if( auth()->user()->permisos()->where('permisos.alias', 'facturas_canceladas_editar')->exists() )
                <td class="text-center align-middle">
                    <button class="btn btn-danger btn-sm delete-row" data-row-id="{{$item->id}}" data-toggle="tooltip" data-placement="top" title="Eliminar"><i class="mdi mdi-trash-can"></i></button>
                </td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>