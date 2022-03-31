<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Cobranza</title>
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
            <span class="title">Cobranza</span>
        </div>
        
        <div style="margin-top: 1mm;">
            <table class="seccion">
                <tr class="bg-gray">
                    <td style="width:70%;">Persona de pagos</td>
                    <td style="width:30%;">Teléfono</td>
                </tr>
                <tr>
                    <td style="width:70%;">{{$cliente->nombre_encargado}}&nbsp;</td>
                    <td style="width:30%;">{{$cliente->telefono}}&nbsp;</td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 1mm;">
            <table class="seccion">
                <tr class="bg-gray">
                    <td style="width:70%;">Correo electrónico</td>
                    <td style="width:30%;">Días de crédito</td>
                </tr>
                <tr>
                    <td style="width:70%;">{{$cliente->correo}}&nbsp;</td>
                    <td style="width:30%;">{{$cliente->dias_credito}}&nbsp;</td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 1mm;">
            <table class="seccion">
                <tr class="bg-gray">
                    <td style="width:100%;">Días de pago</td>
                </tr>
                <tr>
                    <td style="width:100%;">{{$cliente->cobranza ? $cliente->cobranza->dias_de_pago : ''}}&nbsp;</td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 1mm;">
            <table class="seccion">
                <tr class="bg-gray">
                    <td style="width:100%;">Indicador de documentos escaneados</td>
                </tr>
                <tr>
                    <td style="width:100%;" class="no-border">
                        <input type="radio" name="transferencia" {{$cliente->cobranza && $cliente->cobranza->transferencia == 1 ? 'checked' : ''}}>
                        <label>Transferencia</label>
                        
                        <input type="radio" name="deposito" {{$cliente->cobranza && $cliente->cobranza->deposito == 1 ? 'checked' : ''}}>
                        <label>Depósito</label>
                        
                        <input type="radio" name="cheque" {{$cliente->cobranza && $cliente->cobranza->cheque == 1 ? 'checked' : ''}}>
                        <label>Cheque</label>
                        
                        <input type="radio" name="efectivo" {{$cliente->cobranza && $cliente->cobranza->efectivo == 1 ? 'checked' : ''}}>
                        <label>Efectivo</label>

                        <input type="radio" name="otro" {{$cliente->cobranza && $cliente->cobranza->otro == 1 ? 'checked' : ''}}>
                        <label>Otro <span class="" style="text-decoration: underline;">{{$cliente->cobranza ? $cliente->cobranza->otro_opcion : '____________'}}</span></label>
                    </td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 1mm;">
            <table class="seccion">
                <tr class="bg-gray">
                    <td style="width:50%;">Nombre de banco del cliente</td>
                    <td style="width:50%;">Cuenta de pago del cliente</td>
                </tr>
                <tr>
                    <td style="width:50%;">{{$cliente->cobranza ? $cliente->cobranza->nombre_banco_cliente : ''}}&nbsp;</td>
                    <td style="width:50%;">{{$cliente->cobranza ? $cliente->cobranza->cuenta_pago_cliente : ''}}&nbsp;</td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 1mm;">
            <table class="seccion">
                <tr class="bg-gray">
                    <td style="width:100%;">Clabe de pago de cliente</td>
                </tr>
                <tr>
                    <td style="width:100%;">{{$cliente->cobranza ? $cliente->cobranza->clabe_pago_cliente : ''}}&nbsp;</td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 1mm;">
            <table class="seccion">
                <tr class="bg-gray">
                    <td style="width:100%;">Observaciones</td>
                </tr>
                <tr>
                    <td style="width:100%;">{{$cliente->cobranza ? $cliente->cobranza->observaciones : ''}}&nbsp;</td>
                </tr>
            </table>
        </div>
    </body>
</html>