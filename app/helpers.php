<?php
if (! function_exists('convertir_decimales_a_horas_minutos') ) {
    function convertir_decimales_a_horas_minutos($decimales) {
        $decimales = number_format ($decimales, 2);#Necesario para que php tome los decimales como debe de ser

        $decimalArray = explode('.', $decimales);

        return $decimalArray[0].'h '.round((($decimalArray[1] / 100) * 60), 0, PHP_ROUND_HALF_UP).'min';
    }
}