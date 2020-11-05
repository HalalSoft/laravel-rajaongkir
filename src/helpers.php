<?php

if (!function_exists('rajaongkir')) {
    function rajaongkir($table = null, $column = null, $keyword = null)
    {
        $result = app('halalsoft.laravel-rajaongkir');
        
//        $result->config = [];
        return $result;
    }
}
