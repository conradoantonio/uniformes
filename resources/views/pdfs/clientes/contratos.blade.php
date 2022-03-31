<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Contratos</title>
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
            <span class="title">Contratos</span>
        </div>
        
        <div style="margin-top: 1mm;">
            <table class="seccion">
                <tr class="bg-gray">
                    <td style="width:35%;">Contrato original en expediente</td>
                    <td style="width:20%;">Firmado</td>
                    <td style="width:20%;">Escaneado</td>
                    <td style="width:25%;">Tipo de contrato</td>
                </tr>
                <tr>
                    <td style="width:35%;">{{$cliente->contrato ? $cliente->contrato->contrato_original_expediente : 'N/A'}}&nbsp;</td>
                    <td style="width:20%;">{{$cliente->contrato ? $cliente->contrato->firmado : 'N/A'}}&nbsp;</td>
                    <td style="width:20%;">{{$cliente->contrato ? $cliente->contrato->escaneado : 'N/A'}}&nbsp;</td>
                    <td style="width:25%;">{{$cliente->contrato ? $cliente->contrato->tipo_contrato : 'N/A'}}&nbsp;</td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 3mm;">
            <table class="seccion">
                <tr class="bg-gray">
                    <td style="width:70%;">Nombre del representante legal</td>
                    <td style="width:30%;">Cargo</td>
                </tr>
                <tr>
                    <td style="width:70%;">{{$cliente->contrato ? $cliente->contrato->nombre_representante_legal : 'N/A'}}&nbsp;</td>
                    <td style="width:30%;">{{$cliente->contrato ? $cliente->contrato->cargo : 'N/A'}}&nbsp;</td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 3mm;">
            <table class="seccion">
                <tr class="bg-gray">
                    <td style="width:100%;">Nombre del testigo</td>
                </tr>
                <tr>
                    <td style="width:100%;">{{$cliente->contrato ? $cliente->contrato->nombre_testigo : 'N/A'}}&nbsp;</td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 3mm;">
            <table class="seccion">
                <tr class="bg-gray">
                    <td style="width:100%;">Vigencia</td>
                </tr>
                <tr>
                    <td style="width:100%;">{{$cliente->contrato ? $cliente->contrato->vigencia : 'N/A'}}&nbsp;</td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 3mm;">
            <table class="seccion">
                <tr class="bg-gray">
                    <td colspan="3" style="width:100%;">Indicador de documentos escaneados</td>
                </tr>
                <tr>
                    <td style="width:40%;" class="no-border">
                        <input type="radio" name="actividad_constitutiva" {{$cliente->contrato && $cliente->contrato->acta_constitutiva == 1 ? 'checked' : ''}}>
                        <label>Acta constitutiva</label><br>
                        
                        <input type="radio" name="ine_testigo" {{$cliente->contrato && $cliente->contrato->ine_testigo == 1 ? 'checked' : ''}}>
                        <label>INE Testigo</label><br>
                        
                        <input type="radio" name="comprobante_domicilio_fiscal" {{$cliente->contrato && $cliente->contrato->comprobante_domicilio_fiscal == 1 ? 'checked' : ''}}>
                        <label>Comprobante de domicilio fiscal</label><br>
                        
                        <input type="radio" name="cotizacion_ventas" {{$cliente->contrato && $cliente->contrato->cotizacion_ventas == 1 ? 'checked' : ''}}>
                        <label>Cotización de ventas</label><br>
                    </td>
                    <td style="width:40%;" class="no-border">
                        <input type="radio" name="ine_representante" {{$cliente->contrato && $cliente->contrato->ine_representante == 1 ? 'checked' : ''}}>
                        <label>INE representante</label><br>

                        <input type="radio" name="cedula_fiscal" {{$cliente->contrato && $cliente->contrato->cedula_fiscal == 1 ? 'checked' : ''}}>
                        <label>Cédula fiscal</label><br>

                        <input type="radio" name="comprobante_domicilio_servicio" {{$cliente->contrato && $cliente->contrato->comprobante_domicilio_servicio == 1 ? 'checked' : ''}}>
                        <label>Comprobante del domicilio del servicio</label><br>

                        <input type="radio" name="contrato" {{$cliente->contrato && $cliente->contrato->contrato == 1 ? 'checked' : ''}}>
                        <label>Contrato</label><br>
                    </td>
                    <td style="width:20%;" class="no-border">
                        <input type="radio" name="orden_compra" {{$cliente->contrato && $cliente->contrato->orden_compra == 1 ? 'checked' : ''}}>
                        <label>Orden de compra</label>
                    </td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 3mm;">
            <table class="seccion">
                <tr class="bg-gray">
                    <td style="width:100%;">Observaciones</td>
                </tr>
                <tr>
                    <td style="width:100%;">{{$cliente->contrato ? $cliente->contrato->observaciones : 'N/A'}}&nbsp;</td>
                </tr>
            </table>
        </div>
    </body>
</html>