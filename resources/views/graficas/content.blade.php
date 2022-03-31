@extends('layouts.main')
@section('content')
<section class="admin-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 m-t-15">
                <div class="row">
                    <div class="col-md-12 text-center m-t-15">
                        <h3>Datos genrales del sistema</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card m-b-30">
                    <div class="">
                        <div class="table-responsive">
                            <table class="table table-borderless table-hover">
                                <thead>
                                <tr>
                                    <th scope="col">Nombre del cliente</th>
                                    <th scope="col">Monto de factura</th>
                                    <th scope="col">Fecha promesa de pago</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @if( count ( $facturasPrometidasSemana ) )
                                        @foreach( $facturasPrometidasSemana as $factura )
                                            <tr>
                                                <td class="border-left border-strong border-success">{{$factura->cliente->nombre}}</td>
                                                <td>
                                                   ${{ number_format($factura->importe) }}mxn
                                                </td>
                                                <td>
                                                    {{strftime('%d', strtotime($factura->fecha_promesa_pago)).' de '.strftime('%B', strtotime($factura->fecha_promesa_pago)). ' del '.strftime('%Y', strtotime($factura->fecha_promesa_pago))}}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr class="text-center">
                                            <td colspan="3">No hay facturas pendientes de pago para esta semana</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row {{-- pull-up --}} d-lg-flex">
            <div class="col m-b-30">
                <div class="card">
                    <div class="card-body">
                        {{-- <div class="card-controls">
                            <a href="#" class="badge badge-soft-success"> <i class="mdi mdi-arrow-down"></i> 12 %</a>
                        </div> --}}
                        <div class="text-center p-t-30 p-b-20">
                            <div class="text-overline text-muted opacity-75">Total de clientes</div>
                            <h1 class="text-success">{{number_format($data->totalClientes)}}</h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col m-b-30">
                <div class="card">
                    <div class="card-body">
                        {{-- <div class="card-controls">
                            <a href="#" class="badge badge-soft-success"> <i class="mdi mdi-arrow-up"></i> 32 %</a>
                        </div> --}}
                        <div class="text-center p-t-30 p-b-20">
                            <div class="text-overline text-muted opacity-75">Facturas pagadas del mes</div>
                            <h1 class="text-success">{{number_format($data->totalFacturasPagadasMes)}}</h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col m-b-30">
                <div class="card">
                    <div class="card-body">
                        {{-- <div class="card-controls">
                            <a href="#" class="badge badge-soft-info"> <i class="mdi mdi-arrow-down"></i> 10 %</a>
                        </div> --}}
                        <div class="text-center p-t-30 p-b-20">
                            <div class="text-overline text-muted opacity-75">Facturas NO pagadas del mes</div>
                            <h1 class="text-info">{{number_format($data->totalFacturasNOPagadasMes)}}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row d-lg-flex">
            <div class="col m-b-30">
                <div class="card">
                    <div class="card-body">
                        {{-- <div class="card-controls">
                            <a href="#" class="badge badge-soft-info"> <i class="mdi mdi-arrow-down"></i> 10 %</a>
                        </div> --}}
                        <div class="text-center p-t-30 p-b-20">
                            <div class="text-overline text-muted opacity-75">Notas de crédito del mes</div>
                            <h1 class="text-info">{{number_format($data->totalNotasCreditoMes)}}</h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col m-b-30">
                <div class="card">
                    <div class="card-body">
                        {{-- <div class="card-controls">
                            <a href="#" class="badge badge-soft-info"> <i class="mdi mdi-arrow-down"></i> 10 %</a>
                        </div> --}}
                        <div class="text-center p-t-30 p-b-20">
                            <div class="text-overline text-muted opacity-75">Pagos del mes</div>
                            <h1 class="text-info">{{number_format($data->totalPagosMes)}}</h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col m-b-30">
                <div class="card">
                    <div class="card-body">
                        {{-- <div class="card-controls">
                            <a href="#" class="badge badge-soft-info"> <i class="mdi mdi-arrow-down"></i> 10 %</a>
                        </div> --}}
                        <div class="text-center p-t-30 p-b-20">
                            <div class="text-overline text-muted opacity-75">Monto de facturas NO pagadas</div>
                            <h1 class="text-info">{{number_format($data->totalFacturasNOPagadas)}}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row d-lg-flex">
            <div class="col m-b-30">
                <div class="card">
                    <div class="card-body">
                        {{-- <div class="card-controls">
                            <a href="#" class="badge badge-soft-info"> <i class="mdi mdi-arrow-down"></i> 10 %</a>
                        </div> --}}
                        <div class="text-center p-t-30 p-b-20">
                            <div class="text-overline text-muted opacity-75">Facturas normales</div>
                            <h1 class="text-info">{{number_format($data->totalFacturasNormales)}}</h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col m-b-30">
                <div class="card">
                    <div class="card-body">
                        {{-- <div class="card-controls">
                            <a href="#" class="badge badge-soft-info"> <i class="mdi mdi-arrow-down"></i> 10 %</a>
                        </div> --}}
                        <div class="text-center p-t-30 p-b-20">
                            <div class="text-overline text-muted opacity-75">Facturas morosas</div>
                            <h1 class="text-info">{{number_format($data->totalFacturasMorosas)}}</h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col m-b-30">
                <div class="card">
                    <div class="card-body">
                        {{-- <div class="card-controls">
                            <a href="#" class="badge badge-soft-info"> <i class="mdi mdi-arrow-down"></i> 10 %</a>
                        </div> --}}
                        <div class="text-center p-t-30 p-b-20">
                            <div class="text-overline text-muted opacity-75">Facturas prelegales</div>
                            <h1 class="text-info">{{number_format($data->totalFacturasPrelegales)}}</h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col m-b-30">
                <div class="card">
                    <div class="card-body">
                        {{-- <div class="card-controls">
                            <a href="#" class="badge badge-soft-info"> <i class="mdi mdi-arrow-down"></i> 10 %</a>
                        </div> --}}
                        <div class="text-center p-t-30 p-b-20">
                            <div class="text-overline text-muted opacity-75">Facturas legales</div>
                            <h1 class="text-info">{{number_format($data->totalFacturasLegales)}}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection