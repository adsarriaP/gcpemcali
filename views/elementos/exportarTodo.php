<?php
ini_set('memory_limit', '512M');

error_reporting(E_ERROR | E_PARSE);
session_start();
require_once "controllers/dashboard.php";
require_once "vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$obj = new dashboard();
$datos = [
	'vigencia' => $parametros[0]
];
$respuesta = $obj->exportarTodo($datos);

//print_r($respuesta);

$excel = new Spreadsheet();
$hojaActiva = $excel->getActiveSheet();
$hojaActiva->setTitle("Elementos");

//Columnas
$hojaActiva->setCellValue('A1', 'Código GCP');
$hojaActiva->setCellValue('B1', 'Código externo');
$hojaActiva->setCellValue('C1', 'sn');
$hojaActiva->setCellValue('D1', 'Tipo');
$hojaActiva->setCellValue('E1', 'Clase');
$hojaActiva->setCellValue('F1', 'Descripción');
$hojaActiva->setCellValue('G1', 'Serie');
$hojaActiva->setCellValue('H1', 'Valor');
$hojaActiva->setCellValue('I1', 'Trabajador');
$hojaActiva->setCellValue('J1', 'Registro');
$hojaActiva->setCellValue('K1', 'Gerencia');
$hojaActiva->setCellValue('L1', 'Dependencia');
$hojaActiva->setCellValue('M1', 'Unidad');
$hojaActiva->setCellValue('N1', 'Planta');

$tipos = ['', 'Activo', 'Controlado', 'Arrendamiento Operativo'];
$clases = ['', '', 'Maquinaria y equipo', 'Muebles y enseres', 'Equipos de computo', 'Equipos de comunicaciones', 'Vehículos', 'Equipos de laboratorio', 'Sillas'];
for($i = 0; $i < count($respuesta['data']); $i++){
	//Datos del trabajador
	$hojaActiva->setCellValue('A'.($i+2), $respuesta['data'][$i]['id']);
	$hojaActiva->setCellValue('B'.($i+2), $respuesta['data'][$i]['codigo']);
	$hojaActiva->setCellValue('C'.($i+2), $respuesta['data'][$i]['sn']);
	$hojaActiva->setCellValue('D'.($i+2), $tipos[$respuesta['data'][$i]['fk_tipos']]);
	$hojaActiva->setCellValue('E'.($i+2), $clases[$respuesta['data'][$i]['fk_clases']]);
	$hojaActiva->setCellValue('F'.($i+2), $respuesta['data'][$i]['descripcion']);
	$hojaActiva->setCellValue('G'.($i+2), $respuesta['data'][$i]['serie']);
	$hojaActiva->setCellValue('H'.($i+2), $respuesta['data'][$i]['valor']);
	$hojaActiva->setCellValue('I'.($i+2), $respuesta['data'][$i]['trabajador']);
	$hojaActiva->setCellValue('J'.($i+2), $respuesta['data'][$i]['registro']);
	$hojaActiva->setCellValue('K'.($i+2), $respuesta['data'][$i]['gerencia']);
	$hojaActiva->setCellValue('L'.($i+2), $respuesta['data'][$i]['dependencia']);
	$hojaActiva->setCellValue('M'.($i+2), $respuesta['data'][$i]['unidad']);
	$hojaActiva->setCellValue('N'.($i+2), $respuesta['data'][$i]['planta']);
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="Elementos.xlsx"');
$writer = new Xlsx($excel);
$writer->save('php://output');