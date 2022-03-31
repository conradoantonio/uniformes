<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Facturas</title>
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
                font-size: 13mm !important;
            }

            .mayus {
                text-transform: uppercase !important;
            }
        </style>
    </head>
    <body class="mayus">
        <div class="mayus text-center" style="margin-top: 1mm;">
            <span class="title">Facturas</span>
        </div>

        <div style="margin-top: 1mm;">
            <table class="seccion">
                <tr class="bg-gray">
                    <td style="width:100%;">Nombre razón social</td>
                </tr>
                <tr>
                    <td style="width:100%;">{{$cliente->nombre}}&nbsp;</td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 1mm;">
            <table class="seccion">
                <tr class="bg-gray">
                    <td style="width:100%;">Domicilio fiscal</td>
                </tr>
                <tr>
                    <td style="width:100%;">{{$cliente->domicilio}}&nbsp;</td>
                </tr>
            </table>
        </div>
        
        <div style="margin-top: 1mm;">
            <table class="seccion">
                <tr class="bg-gray">
                    <td style="width:35%;">RFC</td>
                    <td style="width:30%;">No. Elementos</td>
                    <td style="width:35%;">Costo unitario</td>
                </tr>
                <tr>
                    <td style="width:35%;">{{$cliente->factura ? $cliente->factura->rfc : 'N/A'}}&nbsp;</td>
                    <td style="width:30%;">{{$cliente->factura ? $cliente->factura->no_elementos : 'N/A'}}&nbsp;</td>
                    <td style="width:35%;">{{$cliente->factura ? $cliente->factura->costo_unitario : 'N/A'}}&nbsp;</td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 1mm;">
            <table class="seccion">
                <tr class="bg-gray">
                    <td style="width:50%;">Precio total mensual sin IVA</td>
                    <td style="width:50%;">Correo para envío de factura</td>
                </tr>
                <tr>
                    <td style="width:50%;">{{$cliente->factura ? $cliente->factura->precio_total_mensual_sin_iva : 'N/A'}}&nbsp;</td>
                    <td style="width:50%;">{{$cliente->factura ? $cliente->factura->correo_para_envio_factura : 'N/A'}}&nbsp;</td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 1mm;">
            <table class="seccion">
                <tr class="bg-gray">
                    <td style="width:50%;">Nombre del contacto</td>
                    <td style="width:50%;">Fecha envío factura</td>
                </tr>
                <tr>
                    <td style="width:50%;">{{$cliente->factura ? $cliente->factura->nombre_contacto : 'N/A'}}&nbsp;</td>
                    <td style="width:50%;">{{$cliente->factura ? $cliente->factura->fecha_envio_factura : 'N/A'}}&nbsp;</td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 1mm;">
            <table class="seccion">
                <tr class="bg-gray">
                    <td style="width:40%;">Se cobran día festivos</td>
                    <td style="width:60%;">Horario</td>
                </tr>
                <tr>
                    <td style="width:40%;">{{$cliente->factura && $cliente->factura->cobran_dias_festivos == 1 ? 'Si' : 'N/A'}}&nbsp;</td>
                    <td style="width:50%;">{{$cliente->factura ? $cliente->factura->horario : 'N/A'}}&nbsp;</td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 1mm;">
            <table class="seccion">
                <tr class="bg-gray">
                    <td style="width:100%;">Observaciones sobre la factura</td>
                </tr>
                <tr>
                    <td style="width:100%;">{{$cliente->factura ? $cliente->factura->observaciones_sobre_factura : 'N/A'}}&nbsp;</td>
                </tr>
            </table>
        </div>
    </body>
</html>