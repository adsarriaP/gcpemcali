<?php
session_start();
require_once "controllers/dashboard.php";

// Determinar si son 1 o 2 parámetros (vigencia o fechas)
if(count($parametros) == 2) {
    // Modo rango de fechas
    $datos = [
        'fechaInicio' => $parametros[0],
        'fechaFin' => $parametros[1]
    ];
    $nombreArchivo = "Solicitudes_{$parametros[0]}_a_{$parametros[1]}.csv";
} else {
    // Modo vigencia (mes)
    $datos = [
        'vigencia' => $parametros[0]
    ];
    $nombreArchivo = "Solicitudes_{$parametros[0]}.csv";
}

$obj = new dashboard();
$respuesta = $obj->solicitudesExport($datos);

// Verificar si hubo error en la consulta
if(!$respuesta['ejecuto']) {
    header('Content-Type: text/html; charset=utf-8');
    echo "<h2>Error en la consulta</h2>";
    echo "<p><strong>Código de error:</strong> " . ($respuesta['codigoError'] ?? 'N/A') . "</p>";
    echo "<p><strong>Mensaje:</strong> " . ($respuesta['mensajeError'] ?? 'N/A') . "</p>";
    echo "<p><strong>Mensaje real:</strong> " . ($respuesta['mensajeReal'] ?? 'N/A') . "</p>";
    echo "<br><p><strong>Datos enviados:</strong></p>";
    echo "<pre>" . print_r($datos, true) . "</pre>";
    exit;
}

// Verificar si hay datos
if(!isset($respuesta['data']) || count($respuesta['data']) == 0) {
    header('Content-Type: text/html; charset=utf-8');
    echo "<h2>No se encontraron registros</h2>";
    echo "<p>No hay datos para exportar con los parámetros especificados.</p>";
    echo "<p><strong>Datos enviados:</strong></p>";
    echo "<pre>" . print_r($datos, true) . "</pre>";
    exit;
}

header('Content-Type: text/csv; charset=utf-8');
header("Content-Disposition: attachment; filename=\"$nombreArchivo\"");
echo "\xEF\xBB\xBF";

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