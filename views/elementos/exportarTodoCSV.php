<?php
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="Elementos.csv"');
echo "\xEF\xBB\xBF";

session_start();
require_once "controllers/dashboard.php";

$obj = new dashboard();
$datos = [
    'vigencia' => $parametros[0]
];
$respuesta = $obj->exportarTodo($datos);

$output = fopen('php://output', 'w');

// Escribimos encabezados desde las claves del primer elemento
fputcsv($output, ['Código GCP','Código externo','SN','Tipo','Clase','Descripción','Serie','Valor','Trabajador','Registro','Gerencia','Dependencia','Unidad','Planta'], ';');

// Escribimos cada fila
$tipos = ['', 'Activo', 'Controlado', 'Arrendamiento Operativo'];
$clases = ['', '', 'Maquinaria y equipo', 'Muebles y enseres', 'Equipos de computo', 'Equipos de comunicaciones', 'Vehículos', 'Equipos de laboratorio', 'Sillas'];
foreach ($respuesta['data'] as $fila) {    
    $fila['fk_tipos'] = $tipos[$fila['fk_tipos']];
    $fila['fk_clases'] = $clases[$fila['fk_clases']];
    fputcsv($output, $fila, ';');
}

fclose($output);
exit;