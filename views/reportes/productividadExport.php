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
    $nombreArchivo = "Productividad_{$parametros[0]}_a_{$parametros[1]}.csv";
} else {
    // Modo vigencia (mes)
    $datos = [
        'vigencia' => $parametros[0]
    ];
    $nombreArchivo = "Productividad_{$parametros[0]}.csv";
}

$obj = new dashboard();
$respuesta = $obj->productividadExport($datos);

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

// Escribimos encabezados
fputcsv($output, [
    'solicitud',
    'fecha_creacion',
    'tipo',
    'codigo',
    'descripcion',
    'serie',
    'nombre',
    'registro',
    'estado',
    'estadoTexto',
    'fk_solicitudes',
    'transacciones',
    'Jefe que aprobo',
    'Fecha en que aprobo',
    'Tecnico que atendio',
    'Fecha en la que atendio tecnico'
], ';');

// Escribimos cada fila
foreach ($respuesta['data'] as $fila) {
    fputcsv($output, $fila, ';');
}

fclose($output);
exit;