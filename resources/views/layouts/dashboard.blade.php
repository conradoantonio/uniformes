@extends('layouts.main')
@section('content')
<style type="text/css">
    .apexcharts-canvas .apexcharts-title-text,
    .apexcharts-canvas .apexcharts-legend-text,
    .apexcharts-canvas .apexcharts-legend-series,
    .apexcharts-canvas .apexcharts-xaxis-label,
    .apexcharts-canvas .apexcharts-yaxis-label,
    .apexcharts-canvas .apexcharts-yaxis-title,
    .apexcharts-canvas .apexcharts-xaxis-title,
    .apexcharts-canvas text
    {
        fill: #fff !important;
    }
</style>
<section class="admin-content">
    <div class="container d-none">
        <div class="row">
            <div class="col-md-12 m-auto text-white p-t-40 p-b-90">
                <h1>Estadísticas</h1>
                <p class="opacity-75">
                    Utilice los filtros abajo proporcionados para obtener información personalizada.
                </p>
            </div>
            <div class="col-md-4 m-auto text-white general-info" data-url="{{url("dashboard")}}" data-refresh="table" data-el-loader="card">
               
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 m-t-15">
                <div class="row">
                    <div class="col-md-12 text-left m-t-15">
                        <h4>Bienvenido {{auth()->user()->fullname}}.</h4>
                    </div>
                    
                    <div class="col-md-12 text-center">
                        <div class="card">
                            <div class="card-header">
                                <div class="col-md-12">
                                    <a href="javascript:;" class="btn btn-dark filter-rows" data-callback="fillDashboardData"> <i class="mdi mdi-filter-variant"></i> Filtrar</a>
                                    <a href="javascript:;" class="btn btn-info export-rows" data-url="{{url('dashboard/excel/export?')}}"> <i class="mdi mdi-file-excel"></i> Exportar histórico artículos</a>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 my-auto filter-section">
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <div class="no-pad" style="text-align: left;">
                                                <select id="razon_social_id" name="razon_social_id" class="form-control not-empty select2" data-msg="Razón social">
                                                    <option value="" selected>Razón social (Cualquiera)</option>
                                                    @foreach($razones as $razon)
                                                        <option value="{{$razon->id}}">{{$razon->nombre}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="no-pad">
                                                <input type="text" class="date-picker form-control" name="fecha_promesa_inicio" autocomplete="off" placeholder="Fecha promesa inicio">
                                            </div>
                                            <div class="no-pad">
                                                <input type="text" class="date-picker form-control" name="fecha_promesa_fin" autocomplete="off" placeholder="Fecha promesa fin">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                            <div class="text-overline text-muted opacity-75">Total de empleados</div>
                            <h1 class="text-success total-clientes">#{{number_format($data->totalEmpleados, 0)}}</h1>
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
                            <div class="text-overline text-muted opacity-75">Empleados activos</div>
                            <h1 class="text-info total-facturas-pagadas-mes">#{{number_format($data->totalEmpleadosActivos, 0)}}</h1>
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
                            <div class="text-overline text-muted opacity-75">Empleados inactivos</div>
                            <h1 class="text-info total-facturas-no-pagadas-mes">#{{number_format($data->totalEmpleadosInactivos, 0)}}</h1>
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
                            <div class="text-overline text-muted opacity-75">Artículos entregados</div>
                            <h1 class="text-info total-notas-credito-mes">#{{number_format($data->totalArticulosEntregados, 0)}}</h1>
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
                            <div class="text-overline text-muted opacity-75">Artículos recibidos</div>
                            <h1 class="text-info total-pagos-mes">#{{number_format($data->totalArticulosRecibidos, 0)}}</h1>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="col m-b-30">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center p-t-30 p-b-20">
                            <div class="text-overline text-muted opacity-75">Monto de facturas NO pagadas</div>
                            <h1 class="text-info total-facturas-no-pagadas">${{number_format($data->totalFacturasNOPagadas, 2)}}</h1>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
</section>
<script type="text/javascript">
    function fillDashboardData(response, config) {
        $("div.tabla-facturas table tbody").children().remove();

        var general  = response.data.data;
        var facturas = response.data.facturasPrometidasSemana;

        console.warn(general);

        const opt = { style: 'currency', currency: 'USD', maximumFractionDigits: 2 };
        const numFormat = new Intl.NumberFormat('en-US', opt);

        $('.total-empleados').text('#'+general.totalClientes);
        $('.total-facturas-pagadas-mes').text(numFormat.format( general.totalFacturasPagadasMes ) );
        $('.total-facturas-no-pagadas-mes').text(numFormat.format( general.totalFacturasNOPagadasMes ) );
        $('.total-notas-credito-mes').text(numFormat.format( general.totalNotasCreditoMes ) );
        $('.total-pagos-mes').text(numFormat.format( general.totalPagosMes ) );
        $('.total-facturas-no-pagadas').text(numFormat.format( general.totalFacturasNOPagadas ) );
        $('.total-facturas-normales').text(general.totalFacturasNormales);
        $('.total-facturas-morosas').text(general.totalFacturasMorosas);
        $('.total-facturas-prelegales').text(general.totalFacturasPrelegales);
        $('.total-facturas-legales').text(general.totalFacturasLegales);

        if ( facturas.length ) {
            $('ul.ul-facturas').removeClass('d-none');
            for ( var key in facturas ) {
                if ( facturas.hasOwnProperty( key ) ) {
                    // tiempo = facturas[key].pivot.tiempo_acumulado.substring(0, 5);

                    $("div.tabla-facturas table tbody").append(
                        '<tr>'+
                            '<td class="border-left border-strong border-success">'+facturas[key].cliente.nombre+'</td>'+
                            '<td>$'+facturas[key].importe+' mxn</td>'+
                            '<td>'+facturas[key].fecha_promesa_formateada+'</td>'+
                        '</tr>'
                    );
                }
            }
            //$("table#table-facturas-rate").parent('div').addClass('table-responsive');
        } else {
            $("div.tabla-facturas table tbody").append(
                '<tr style="text-align: center;"><td class="align-middle" colspan="3">No hay facturas por mostrar</td></tr>'
            );
        }
    }
</script>
@endsection