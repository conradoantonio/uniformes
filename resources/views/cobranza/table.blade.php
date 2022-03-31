<table class="table table-hover table-sm data-table">
    <thead>
        <tr>
            <th class="d-none">ID</th>
            <th>Cliente</th>
            <th>Razón social</th>
            <th>Status</th>
            <th>Número de factura</th>
            <th>Importe</th>
            <th>Balance</th>
            <th>¿Pagada?</th>
            <th>Fecha de facturación</th>
            <th>Fecha promesa de pago</th>
            <th class="text-center">Acciones</th>
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
                <td class="align-middle">{!! '<span class="badge badge-'.$item->status->clase.'">'.$item->status->nombre.'</span>' !!}</td>
                <td class="align-middle">{{$item->numero_factura}}</td>
                <td class="align-middle">${{number_format($item->importe, 2)}} mxn</td>
                <td class="align-middle">
                    ${{ number_format( $item->importe - ( $item->notas_credito->sum('importe') + $item->pagos->sum('importe') ), 2 ) }} mxn
                </td>
                <td class="align-middle">{!! $item->pagada == 1 ? '<span class="badge badge-success">Si</span>' : '<span class="badge badge-dark">No</span>' !!}</td>
                <td class="align-middle">{{strftime('%d', strtotime($item->fecha_facturacion)).' de '.strftime('%B', strtotime($item->fecha_facturacion)). ' del '.strftime('%Y', strtotime($item->fecha_facturacion))}}</td>
                <td class="align-middle">{{strftime('%d', strtotime($item->fecha_promesa_pago)).' de '.strftime('%B', strtotime($item->fecha_promesa_pago)). ' del '.strftime('%Y', strtotime($item->fecha_promesa_pago))}}</td>
                <td class="text-center align-middle">
                    <button class="btn btn-secondary btn-sm ver-comentarios" data-row-name="{{$item->nombre}}" data-row-id="{{$item->id}}" data-toggle="tooltip" data-placement="top" title="Ver comentarios"><i class="mdi mdi-comment-text-multiple"></i></button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>