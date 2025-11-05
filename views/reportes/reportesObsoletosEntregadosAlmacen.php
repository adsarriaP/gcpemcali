<?php
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="ObsoletosEntregadosAlmacen.csv"');
echo "\xEF\xBB\xBF";

session_start();
require_once "controllers/reportes.php";

$obj = new reportes();
$datos = [
    'vigencia' => 1
];
$respuesta = $obj->reportesObsoletosEntregadosAlmacen($datos);

$output = fopen('php://output', 'w');

// Escribimos encabezados desde las claves del primer elemento
fputcsv($output, ['Solicitud','Código externo','Descripción','Serie','Gerencia','Dependencia','Unidad','Trabajador','Registro','Correo','Telefono','Planta','Estado'], ';');

// Escribimos cada fila
$estados = ['','Aceptar elemento', 'Recoger almacen','Aprobar jefe','Realizar inspección','Aprobar inspección','Llevar a almacen','Actualizar SAP','Actualizar carpeta','Reposición','Ejecutada','Anulada'];
foreach ($respuesta['data'] as $fila) {
    $fila['estado'] = $estados[$fila['estado']];
    fputcsv($output, $fila, ';');
}

fclose($output);
exit;