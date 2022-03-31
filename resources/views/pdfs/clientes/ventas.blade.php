<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Ventas</title>
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
            <span class="title">Ventas</span>
        </div>
        
        <div style="margin-top: 1mm;">
            <table class="seccion">
                <tr class="bg-gray">
                    <td style="width:60%;">Nombre contacto inicial</td>
                    <td style="width:40%;">Teléfono contacto inicial</td>
                </tr>
                <tr>
                    <td style="width:60%;">{{$cliente->venta ? $cliente->venta->nombre_contacto_inicial : 'N/A'}}&nbsp;</td>
                    <td style="width:40%;">{{$cliente->venta ? $cliente->venta->telefono_contacto_inicial : 'N/A'}}&nbsp;</td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 1mm;">
            <table class="seccion">
                <tr class="bg-gray">
                    <td style="width:60%;">Correo contacto inicial</td>
                    <td style="width:40%;">Cotización firmada y autorizada</td>
                </tr>
                <tr>
                    <td style="width:60%;">{{$cliente->venta ? $cliente->venta->correo_contacto_inicial : 'N/A'}}&nbsp;</td>
                    <td style="width:40%;">{{$cliente->venta ? $cliente->venta->cotizacion_firmada_autorizada : 'N/A'}}&nbsp;</td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 1mm;">
            <table class="seccion">
                <tr class="bg-gray">
                    <td style="width:100%;">Forma en que se concretó</td>
                </tr>
                <tr>
                    <td style="width:100%;">{{$cliente->venta ? $cliente->venta->forma_en_que_se_concreto : 'N/A'}}&nbsp;</td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 1mm;">
            <table class="seccion">
                <tr class="bg-gray">
                    <td style="width:20%;">Fecha cita</td>
                    <td style="width:30%;">Asistencia cita</td>
                    <td style="width:50%;">Quien asistió a la cita</td>
                </tr>
                <tr>
                    <td style="width:20%;">{{$cliente->venta ? $cliente->venta->fecha_cita : 'N/A'}}&nbsp;</td>
                    <td style="width:30%;">{{( $cliente->venta && $cliente->venta->asistencia_cita == 1 ? 'Si' : ( $cliente->venta && $cliente->venta->asistencia_cita == 0 ? 'No' : 'N/A' ) )}}&nbsp;</td>
                    <td style="width:50%;">{{$cliente->venta ? $cliente->venta->quien_asistio_a_cita : 'N/A'}}&nbsp;</td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 1mm;">
            <table class="seccion">
                <tr class="bg-gray">
                    <td style="width:100%;">Observaciones</td>
                </tr>
                <tr>
                    <td style="width:100%;">{{$cliente->venta ? $cliente->venta->observaciones : 'N/A'}}&nbsp;</td>
                </tr>
            </table>
        </div>
    </body>
</html>