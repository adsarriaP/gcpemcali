<?php
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="ObsoletosPendienteLlevarAlmacen.csv"');
echo "\xEF\xBB\xBF";

session_start();
require_once "controllers/reportes.php";

$obj = new reportes();
$datos = [
    'vigencia' => 1
];
$respuesta = $obj->reportesObsoletosPendienteAlmacen($datos);

$output = fopen('php://output', 'w');

// Escribimos encabezados desde las claves del primer elemento
fputcsv($output, ['Solicitud','Código externo','Descripción','Serie','Gerencia','Dependencia','Unidad','Trabajador','Registro','Correo','Telefono','Planta'], ';');

// Escribimos cada fila
foreach ($respuesta['data'] as $fila) {
    fputcsv($output, $fila, ';');
}

fclose($output);
exit;