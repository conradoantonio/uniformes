//Genera el estado de cuenta
$('body').delegate('.ver-detalles', 'click', function() {
    id = $(this).data('row-id');
    url = $('div.general-info').data('url')+'/show';

    config = {
        "id"        : id,
        "route"     : url,
        "method"    : "POST",
        "keepModal" : true,
        "callback"  : 'fillModalDetails',
    }

    loadingMessage('Espere un momento...');
    ajaxSimple(config);

});

// Set modal
function fillModalDetails(response, config) {
    target    = "#modal-ver-detalles";
    cliente   = response.data;
    generales = response.data.generales;
    cobranza  = response.data.cobranza;
    contrato  = response.data.contrato;
    factura   = response.data.factura;
    nomina    = response.data.nomina;
    venta     = response.data.venta;

    $( ".descargar-pdf" ).each( function( index ) {
        $(this).attr('href', baseUrl.concat('/clientes/'+id+'/exportar/pdf/'+$(this).data('vista')));
    });

    if ( cliente ) { 
        setForm(cliente, target, 'cliente_'); 
        $('[name=cliente_razon_social_nombre]').val(cliente.razon_social ? cliente.razon_social.nombre : '');
    }

    if ( generales ) { 
        setForm(generales, target, 'generales_');
        $('[name=generales_pago_imss]').attr('checked', generales.pago_imss == 1 ? 'checked' : false);
        $('[name=generales_opinion_sat]').attr('checked', generales.opinion_sat == 1 ? 'checked' : false);
        $('[name=generales_dos_sobre_nomina]').attr('checked', generales.dos_sobre_nomina == 1 ? 'checked' : false);
        $('[name=generales_opinion_cumplimiento_imss]').attr('checked', generales.opinion_cumplimiento_imss == 1 ? 'checked' : false);
        $('[name=generales_declaracion_provisional_sat]').attr('checked', generales.declaracion_provisional_sat == 1 ? 'checked' : false);
        $('[name=generales_constancia_situacion_infonavit]').attr('checked', generales.constancia_situacion_infonavit == 1 ? 'checked' : false);
        $('[name=generales_nominas]').attr('checked', generales.nominas == 1 ? 'checked' : false);
        $('[name=generales_repse]').attr('checked', generales.repse == 1 ? 'checked' : false);
    }

    if ( cobranza ) { 
        setForm(cobranza, target, 'cobranza_'); 
        $('[name=cobranza_transferencia]').attr('checked', cobranza.transferencia == 1 ? 'checked' : false);
        $('[name=cobranza_deposito]').attr('checked', cobranza.deposito == 1 ? 'checked' : false);
        $('[name=cobranza_cheque]').attr('checked', cobranza.cheque == 1 ? 'checked' : false);
        $('[name=cobranza_efectivo]').attr('checked', cobranza.efectivo == 1 ? 'checked' : false);
        $('[name=cobranza_otro]').attr('checked', cobranza.otro == 1 ? 'checked' : false);
    }

    if ( contrato ) { 
        setForm(contrato, target, 'contrato_'); 
        $('[name=contrato_acta_constitutiva]').attr('checked', contrato.acta_constitutiva == 1 ? 'checked' : false);
        $('[name=contrato_ine_representante]').attr('checked', contrato.ine_representante == 1 ? 'checked' : false);
        $('[name=contrato_ine_testigo]').attr('checked', contrato.ine_testigo == 1 ? 'checked' : false);
        $('[name=contrato_cedula_fiscal]').attr('checked', contrato.cedula_fiscal == 1 ? 'checked' : false);
        $('[name=contrato_comprobante_domicilio_fiscal]').attr('checked', contrato.comprobante_domicilio_fiscal == 1 ? 'checked' : false);
        $('[name=contrato_comprobante_domicilio_servicio]').attr('checked', contrato.comprobante_domicilio_servicio == 1 ? 'checked' : false);
        $('[name=contrato_cotizacion_ventas]').attr('checked', contrato.cotizacion_ventas == 1 ? 'checked' : false);
        $('[name=contrato_contrato]').attr('checked', contrato.contrato == 1 ? 'checked' : false);
        $('[name=contrato_orden_compra]').attr('checked', contrato.orden_compra == 1 ? 'checked' : false);
    }

    if ( factura ) { 
        setForm(factura, target, 'factura_'); 
        $('[name=factura_cobran_dias_festivos]').val(factura.cobran_dias_festivos == 1 ? 'Si' : ( factura.cobran_dias_festivos == 0 ? 'No' : ''));
    }

    if ( nomina ) { 
        setForm(nomina, target, 'nomina_'); 
    }

    if ( venta ) { 
        setForm(venta, target, 'venta_'); 
        $('[name=venta_asistencia_cita]').val(venta.asistencia_cita == 1 ? 'Si' : ( venta.asistencia_cita == 0 ? 'No' : ''));
    }
    
    $('div#modal-ver-detalles').modal();
}