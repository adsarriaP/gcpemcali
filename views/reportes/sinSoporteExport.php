<?php
error_reporting(E_ERROR | E_PARSE);
session_start();
require_once "controllers/elementos.php";
require_once "vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$obj = new elementos();
$datos = [	
	'contrato' => 1
];
$respuesta = $obj->getElementosSinSoporte($datos);

/*print_r($respuesta);
exit();*/

$excel = new Spreadsheet();
$hojaActiva = $excel->getActiveSheet();
$hojaActiva->setTitle("Elementos sin soporte");

//Columnas
$hojaActiva->setCellValue('A1', 'Gerencia');
$hojaActiva->setCellValue('B1', 'Dependencia');
$hojaActiva->setCellValue('C1', 'Unidad');
$hojaActiva->setCellValue('D1', 'Trabajador');
$hojaActiva->setCellValue('E1', 'Registro');
$hojaActiva->setCellValue('F1', 'Correo');
$hojaActiva->setCellValue('G1', 'Código GCP');
$hojaActiva->setCellValue('H1', 'Código SAP');
$hojaActiva->setCellValue('I1', 'Tipo');
$hojaActiva->setCellValue('J1', 'Descricpción');
$hojaActiva->setCellValue('K1', 'Serie');

//LLenar datos
$tipos = ['', 'Activo', 'Controlado', 'Arrendamiento Operativo'];
for($i = 0; $i < count($respuesta['data']); $i++){
	$hojaActiva->setCellValue('A'.($i+2), $respuesta['data'][$i]['gerencia']);
	$hojaActiva->setCellValue('B'.($i+2), $respuesta['data'][$i]['dependencia']);
	$hojaActiva->setCellValue('C'.($i+2), $respuesta['data'][$i]['unidad']);
	$hojaActiva->setCellValue('D'.($i+2), $respuesta['data'][$i]['trabajador']);
	$hojaActiva->setCellValue('E'.($i+2), $respuesta['data'][$i]['registro']);
	$hojaActiva->setCellValue('F'.($i+2), $respuesta['data'][$i]['correo']);
	$hojaActiva->setCellValue('G'.($i+2), $respuesta['data'][$i]['id']);
	$hojaActiva->setCellValue('H'.($i+2), $respuesta['data'][$i]['codigo']);
	$hojaActiva->setCellValue('I'.($i+2), $tipos[$respuesta['data'][$i]['fk_tipos']]);
	$hojaActiva->setCellValue('J'.($i+2), $respuesta['data'][$i]['descripcion']);
	$hojaActiva->setCellValue('K'.($i+2), $respuesta['data'][$i]['serie']);
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="ElementosSinSoporte.xlsx"');
$writer = new Xlsx($excel);
$writer->save('php://output');