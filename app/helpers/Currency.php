<?php


if (!function_exists('convertCurrencyToBRL')) {
    function convertCurrencyToBRL($value)
    {
        $value = floatval($value);
        $formattedValue = number_format($value, 2, ',', '.');
        return 'R$ ' . $formattedValue;
    }
}
