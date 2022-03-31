<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Datos generales</title>
        <style>
            html{
                font-family: 'Roboto', sans-serif;
                margin-right: 0mm;
                margin-left: 0mm;
                font-size: 14px;
                margin-top: 0mm;
                padding-top: 0mm;
            }
            body {
                width: 100%;
                height: 100%;
            }

            table.seccion{
            	color: black; 
            	padding-top: 3.5mm; 
            	padding-left: 10mm; 
            	padding-right: 10mm;
            	width: 100%; 
                border-collapse: collapse;
            	/* border: 1px solid black!important; */
            	/*font-size: 11px; */
            }

            table.seccion td {
                padding: 2mm!important;
            	vertical-align: top!important;
            	border: 1px solid black!important;
            }

            .border-b{
            	border-bottom: 1px solid black!important;
            }

            .no-border {
                border: none!important;
            }

            tr.bg-gray td{
                background-color: #d2ddec;
            }

            div.text-center{
                text-align: center;
            }

            .title {
                font-size: 18mm !important;
            }

            .mayus {
                text-transform: uppercase !important;
            }
        </style>
    </head>
    <body class="mayus">
        <div class="mayus text-center" style="margin-top: 5mm;">
            <span class="title">Datos generales</span>
        </div>
        
        <div style="margin-top: 1mm;">
            <table class="seccion">
                <tr class="bg-gray">
                    <td style="width:100%;">Razón social</td>
                </tr>
                <tr>
                    <td style="width:100%;">{{$cliente->razon_social ? $cliente->razon_social->nombre : 'N/A'}}&nbsp;</td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 1mm;">
            <table class="seccion">
                <tr class="bg-gray">
                    <td style="width:100%;">Nombre comercial</td>
                </tr>
                <tr>
                    <td style="width:100%;">{{$cliente->generales ? $cliente->generales->nombre_comercial : 'N/A'}}&nbsp;</td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 1mm;">
            <table class="seccion">
                <tr class="bg-gray">
                    <td style="width:15%;">No. Cliente</td>
                    <td style="width:35%;">Fecha inicio de servicio</td>
                    <td style="width:50%;">Registro patronal</td>
                </tr>
                <tr>
                    <td style="width:15%;">{{$cliente->generales ? $cliente->generales->num_cliente : 'N/A'}}&nbsp;</td>
                    <td style="width:35%;">{{$cliente->generales ? $cliente->generales->fecha_inicio_servicio : 'N/A'}}&nbsp;</td>
                    <td style="width:50%;">{{$cliente->generales ? $cliente->generales->registro_patronal_nss : 'N/A'}}&nbsp;</td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 5mm;">
            <table class="seccion">
                <tr class="bg-gray">
                    <td style="width:30%;">Fecha terminación servicio</td>
                    <td style="width:70%;">Motivo de baja</td>
                </tr>
                <tr>
                    <td style="width:30%;">{{$cliente->generales ? $cliente->generales->fecha_terminacion_servicio : 'N/A'}}&nbsp;</td>
                    <td style="width:70%;">{{$cliente->generales ? $cliente->generales->motivo_baja : 'N/A'}}&nbsp;</td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 5mm;">
            <table class="seccion">
                <tr class="bg-gray">
                    <td colspan="3" style="width:100%;">Envío de documentos mensuales</td>
                </tr>
                <tr>
                    <td style="width:33%;" class="no-border">
                        <input type="radio" name="pago_imss" {{$cliente->generales && $cliente->generales->pago_imss == 1 ? 'checked' : ''}}>
                        <label>Pago de IMSS</label><br>
                        
                        <input type="radio" name="opinion_cumplimiento_imss" {{$cliente->generales && $cliente->generales->opinion_cumplimiento_imss == 1 ? 'checked' : ''}}>
                        <label>Opinión cumplimiento IMSS</label><br>
                        
                        <input type="radio" name="nominas" {{$cliente->generales && $cliente->generales->nominas == 1 ? 'checked' : ''}}>
                        <label>Nóminas</label>
                    </td>
                    <td style="width:33%;" class="no-border">
                        <input type="radio" name="opinion_sat" {{$cliente->generales && $cliente->generales->opinion_sat == 1 ? 'checked' : ''}}>
                        <label>Opinión SAT</label><br>

                        <input type="radio" name="declaracion_provisional_sat" {{$cliente->generales && $cliente->generales->declaracion_provisional_sat == 1 ? 'checked' : ''}}>
                        <label>Declaración provisional SAT</label><br>

                        <input type="radio" name="repse" {{$cliente->generales && $cliente->generales->repse == 1 ? 'checked' : ''}}>
                        <label>Repse</label>
                    </td>
                    <td style="width:33%;" class="no-border">
                        <input type="radio" name="2_sobre_nomina" {{$cliente->generales && $cliente->generales->dos_sobre_nomina == 1 ? 'checked' : ''}}>
                        <label>2% sobre nómina</label><br>

                        <input type="radio" name="constancia_situacion_infonavit" {{$cliente->generales && $cliente->generales->constancia_situacion_infonavit == 1 ? 'checked' : ''}}>
                        <label>Constancia situación infonavit</label>
                    </td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 5mm;">
            <table class="seccion">
                <tr class="bg-gray">
                    <td style="width:100%;">Observaciones</td>
                </tr>
                <tr>
                    <td style="width:100%;">{{$cliente->generales ? $cliente->generales->observaciones : 'N/A'}}&nbsp;</td>
                </tr>
            </table>
        </div>
    </body>
</html>