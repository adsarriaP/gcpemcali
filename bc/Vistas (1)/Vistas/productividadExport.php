<?php
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="Productividad_'.$parametros[0].'.csv"');
echo "\xEF\xBB\xBF";

session_start();
require_once "controllers/dashboard.php";

$obj = new dashboard();
$datos = [
    'vigencia' => $parametros[0]
];
$respuesta = $obj->productividadExport($datos);

//print_r($respuesta);

$output = fopen('php://output', 'w');

// Escribimos encabezados desde las claves del primer elemento
fputcsv($output, ['solicitud','fecha_creacion','tipo','codigo','descripcion','serie','nombre','registro','estado','estadoTexto','fk_solicitudes', 'transacciones', 'Jefe que aprobo', 'Fecha en que aprobo', 'Tecnico que atendio','Fecha en la que atendio tecnico'], ';');

// Escribimos cada fila
foreach ($respuesta['data'] as $fila) {
    fputcsv($output, $fila, ';');
}
fclose($output);
exit;