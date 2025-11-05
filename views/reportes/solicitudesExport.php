<?php
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="Solicitudes_'.$parametros[0].'.csv"');
echo "\xEF\xBB\xBF";

session_start();
require_once "controllers/dashboard.php";

$obj = new dashboard();
$datos = [
    'vigencia' => $parametros[0]
];
$respuesta = $obj->solicitudesExport($datos);

//print_r($respuesta);

$output = fopen('php://output', 'w');

// Escribimos encabezados desde las claves del primer elemento
fputcsv($output, ['Solicitud','Trámite','Código GCP','Tipo','Clase','Descripción','Serie','Estado','Solicitante','Registro','Fecha creación'], ';');

// Escribimos cada fila
$tipos = ['', 'Activo', 'Controlado', 'Arrendamiento Operativo'];
$clases = ['', '', 'Maquinaria y equipo', 'Muebles y enseres', 'Equipos de computo', 'Equipos de comunicaciones', 'Vehículos', 'Equipos de laboratorio', 'Sillas'];
$tramites = ['', 'Asignación', 'Reasignación', 'Traspaso', 'Reintegro'];
$estados = ['','Aceptar elemento', 'Recoger almacen','Aprobar jefe','Realizar inspección','Aprobar inspección','Llevar a almacen','Actualizar SAP','Actualizar carpeta','Reposición','Ejecutada','Anulada'];
foreach ($respuesta['data'] as $fila) {
    $fila['fk_tramites'] = $tramites[$fila['fk_tramites']];
    $fila['fk_tipos'] = $tipos[$fila['fk_tipos']];
    $fila['fk_clases'] = $clases[$fila['fk_clases']];    
    $fila['estado'] = $estados[$fila['estado']];
    fputcsv($output, $fila, ';');
}
fclose($output);
exit;