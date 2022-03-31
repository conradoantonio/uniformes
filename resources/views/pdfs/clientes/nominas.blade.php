<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Nóminas</title>
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

            span.subtitle{
                /* margin: 0;
                padding: 0; */
                padding-left: 10mm; 
            	padding-right: 10mm;
            	width: 100%; 
                font-weight: bolder;
            }

            table.seccion td {
                padding: 1mm!important;
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
                font-size: 13mm !important;
            }

            .mayus {
                text-transform: uppercase !important;
            }

            .page-break { 
                page-break-before: always; 
            }
        </style>
    </head>
    <body class="mayus">
        <div class="mayus text-center" style="margin-top: 1mm;">
            <span class="title">Nóminas</span>
        </div>

        <div style="margin-top: 1mm;">
            <table class="seccion">
                <tr class="bg-gray">
                    <td style="width:100%;">Domicilio del servicio</td>
                </tr>
                <tr>
                    <td style="width:100%;">{{$cliente->nomina ? $cliente->nomina->domicilio_servicio : 'N/A'}}&nbsp;</td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 1mm;">
            <table class="seccion">
                <tr class="bg-gray">
                    <td style="width:50%;">Teléfono del servicio</td>
                    <td style="width:50%;">Número de elementos</td>
                </tr>
                <tr>
                    <td style="width:50%;">{{$cliente->nomina ? $cliente->nomina->telefono_servicio : 'N/A'}}&nbsp;</td>
                    <td style="width:50%;">{{$cliente->nomina ? $cliente->nomina->numero_elementos : 'N/A'}}&nbsp;</td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 1mm;">
            <span class="subtitle">Servicio 1</span>
            <table class="seccion">
                <tr class="bg-gray">
                    <td style="width:40%;">Tipo de servicio</td>
                    <td style="width:20%;">No. de elementos</td>
                    <td style="width:20%;">Salario diario</td>
                    <td style="width:20%;">Salario mensual</td>
                </tr>
                <tr>
                    <td style="width:40%;">{{$cliente->nomina ? $cliente->nomina->tipo_servicio_1 : 'N/A'}}&nbsp;</td>
                    <td style="width:20%;">{{$cliente->nomina ? $cliente->nomina->no_elementos_1 : 'N/A'}}&nbsp;</td>
                    <td style="width:20%;">${{$cliente->nomina ? $cliente->nomina->salario_diario_1 : 'N/A'}}&nbsp;</td>
                    <td style="width:20%;">${{$cliente->nomina ? $cliente->nomina->salario_mensual_1 : 'N/A'}}&nbsp;</td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 1mm;">
            <table class="seccion">
                <tr class="bg-gray">
                    <td style="width:70%;">Horario de servicio</td>
                    <td style="width:30%;">Tipo de pago</td>
                </tr>
                <tr>
                    <td style="width:70%;">{{$cliente->nomina ? $cliente->nomina->horario_servicio_1 : 'N/A'}}&nbsp;</td>
                    <td style="width:30%;">{{$cliente->nomina ? $cliente->nomina->tipo_pago_1 : 'N/A'}}&nbsp;</td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 1mm;">
            <span class="subtitle">Servicio 2</span>
            <table class="seccion">
                <tr class="bg-gray">
                    <td style="width:40%;">Tipo de servicio</td>
                    <td style="width:20%;">No. de elementos</td>
                    <td style="width:20%;">Salario diario</td>
                    <td style="width:20%;">Salario mensual</td>
                </tr>
                <tr>
                    <td style="width:40%;">{{$cliente->nomina ? $cliente->nomina->tipo_servicio_2 : 'N/A'}}&nbsp;</td>
                    <td style="width:20%;">{{$cliente->nomina ? $cliente->nomina->no_elementos_2 : 'N/A'}}&nbsp;</td>
                    <td style="width:20%;">${{$cliente->nomina ? $cliente->nomina->salario_diario_2 : 'N/A'}}&nbsp;</td>
                    <td style="width:20%;">${{$cliente->nomina ? $cliente->nomina->salario_mensual_2 : 'N/A'}}&nbsp;</td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 1mm;">
            <table class="seccion">
                <tr class="bg-gray">
                    <td style="width:70%;">Horario de servicio</td>
                    <td style="width:30%;">Tipo de pago</td>
                </tr>
                <tr>
                    <td style="width:70%;">{{$cliente->nomina ? $cliente->nomina->horario_servicio_2 : 'N/A'}}&nbsp;</td>
                    <td style="width:30%;">{{$cliente->nomina ? $cliente->nomina->tipo_pago_2 : 'N/A'}}&nbsp;</td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 1mm;">
            <span class="subtitle">Servicio 3</span>
            <table class="seccion">
                <tr class="bg-gray">
                    <td style="width:40%;">Tipo de servicio</td>
                    <td style="width:20%;">No. de elementos</td>
                    <td style="width:20%;">Salario diario</td>
                    <td style="width:20%;">Salario mensual</td>
                </tr>
                <tr>
                    <td style="width:40%;">{{$cliente->nomina ? $cliente->nomina->tipo_servicio_3 : 'N/A'}}&nbsp;</td>
                    <td style="width:20%;">{{$cliente->nomina ? $cliente->nomina->no_elementos_3 : 'N/A'}}&nbsp;</td>
                    <td style="width:20%;">${{$cliente->nomina ? $cliente->nomina->salario_diario_3 : 'N/A'}}&nbsp;</td>
                    <td style="width:20%;">${{$cliente->nomina ? $cliente->nomina->salario_mensual_3 : 'N/A'}}&nbsp;</td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 1mm;">
            <table class="seccion">
                <tr class="bg-gray">
                    <td style="width:70%;">Horario de servicio</td>
                    <td style="width:30%;">Tipo de pago</td>
                </tr>
                <tr>
                    <td style="width:70%;">{{$cliente->nomina ? $cliente->nomina->horario_servicio_3 : 'N/A'}}&nbsp;</td>
                    <td style="width:30%;">{{$cliente->nomina ? $cliente->nomina->tipo_pago_3 : 'N/A'}}&nbsp;</td>
                </tr>
            </table>
        </div>

        <div class="page-break"></div>

        <div class="mayus text-center" style="margin-top: 1mm;">
            <span class="title">Nóminas</span>
        </div>

        <div style="margin-top: 5mm;">
            <span class="subtitle">Servicio 4</span>
            <table class="seccion">
                <tr class="bg-gray">
                    <td style="width:40%;">Tipo de servicio</td>
                    <td style="width:20%;">No. de elementos</td>
                    <td style="width:20%;">Salario diario</td>
                    <td style="width:20%;">Salario mensual</td>
                </tr>
                <tr>
                    <td style="width:40%;">{{$cliente->nomina ? $cliente->nomina->tipo_servicio_4 : 'N/A'}}&nbsp;</td>
                    <td style="width:20%;">{{$cliente->nomina ? $cliente->nomina->no_elementos_4 : 'N/A'}}&nbsp;</td>
                    <td style="width:20%;">${{$cliente->nomina ? $cliente->nomina->salario_diario_4 : 'N/A'}}&nbsp;</td>
                    <td style="width:20%;">${{$cliente->nomina ? $cliente->nomina->salario_mensual_4 : 'N/A'}}&nbsp;</td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 1mm;">
            <table class="seccion">
                <tr class="bg-gray">
                    <td style="width:70%;">Horario de servicio</td>
                    <td style="width:30%;">Tipo de pago</td>
                </tr>
                <tr>
                    <td style="width:70%;">{{$cliente->nomina ? $cliente->nomina->horario_servicio_4 : 'N/A'}}&nbsp;</td>
                    <td style="width:30%;">{{$cliente->nomina ? $cliente->nomina->tipo_pago_4 : 'N/A'}}&nbsp;</td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 1mm;">
            <span class="subtitle">Servicio 5</span>
            <table class="seccion">
                <tr class="bg-gray">
                    <td style="width:40%;">Tipo de servicio</td>
                    <td style="width:20%;">No. de elementos</td>
                    <td style="width:20%;">Salario diario</td>
                    <td style="width:20%;">Salario mensual</td>
                </tr>
                <tr>
                    <td style="width:40%;">{{$cliente->nomina ? $cliente->nomina->tipo_servicio_5 : 'N/A'}}&nbsp;</td>
                    <td style="width:20%;">{{$cliente->nomina ? $cliente->nomina->no_elementos_5 : 'N/A'}}&nbsp;</td>
                    <td style="width:20%;">${{$cliente->nomina ? $cliente->nomina->salario_diario_5 : 'N/A'}}&nbsp;</td>
                    <td style="width:20%;">${{$cliente->nomina ? $cliente->nomina->salario_mensual_5 : 'N/A'}}&nbsp;</td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 1mm;">
            <table class="seccion">
                <tr class="bg-gray">
                    <td style="width:70%;">Horario de servicio</td>
                    <td style="width:30%;">Tipo de pago</td>
                </tr>
                <tr>
                    <td style="width:70%;">{{$cliente->nomina ? $cliente->nomina->horario_servicio_5 : 'N/A'}}&nbsp;</td>
                    <td style="width:30%;">{{$cliente->nomina ? $cliente->nomina->tipo_pago_5 : 'N/A'}}&nbsp;</td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 1mm;">
            <span class="subtitle">Servicio 6</span>
            <table class="seccion">
                <tr class="bg-gray">
                    <td style="width:40%;">Tipo de servicio</td>
                    <td style="width:20%;">No. de elementos</td>
                    <td style="width:20%;">Salario diario</td>
                    <td style="width:20%;">Salario mensual</td>
                </tr>
                <tr>
                    <td style="width:40%;">{{$cliente->nomina ? $cliente->nomina->tipo_servicio_6 : 'N/A'}}&nbsp;</td>
                    <td style="width:20%;">{{$cliente->nomina ? $cliente->nomina->no_elementos_6 : 'N/A'}}&nbsp;</td>
                    <td style="width:20%;">${{$cliente->nomina ? $cliente->nomina->salario_diario_6 : 'N/A'}}&nbsp;</td>
                    <td style="width:20%;">${{$cliente->nomina ? $cliente->nomina->salario_mensual_6 : 'N/A'}}&nbsp;</td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 1mm;">
            <table class="seccion">
                <tr class="bg-gray">
                    <td style="width:70%;">Horario de servicio</td>
                    <td style="width:30%;">Tipo de pago</td>
                </tr>
                <tr>
                    <td style="width:70%;">{{$cliente->nomina ? $cliente->nomina->horario_servicio_6 : 'N/A'}}&nbsp;</td>
                    <td style="width:30%;">{{$cliente->nomina ? $cliente->nomina->tipo_pago_6 : 'N/A'}}&nbsp;</td>
                </tr>
            </table>
        </div>
        
        <div style="margin-top: 1mm;">
            <table class="seccion">
                <tr class="bg-gray">
                    <td style="width:100%;">Observaciones</td>
                </tr>
                <tr>
                    <td style="width:100%;">{{$cliente->nomina ? $cliente->nomina->observaciones : 'N/A'}}&nbsp;</td>
                </tr>
            </table>
        </div>
    </body>
</html>