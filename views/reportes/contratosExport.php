<?php
error_reporting(E_ERROR | E_PARSE);
session_start();
require_once "controllers/elementos.php";
require_once "vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$obj = new elementos();
$datos = [	
	'contrato' => $parametros[0]
];
$respuesta = $obj->getElementosContrato($datos);

/*print_r($respuesta);
exit();*/

$excel = new Spreadsheet();
$hojaActiva = $excel->getActiveSheet();
$hojaActiva->setTitle("Elementos");

//Columnas
$hojaActiva->setCellValue('A1', 'Código');
$hojaActiva->setCellValue('B1', 'Tipo');
$hojaActiva->setCellValue('C1', 'Clase');
$hojaActiva->setCellValue('D1', 'subCclase');
$hojaActiva->setCellValue('E1', 'Descripción');
$hojaActiva->setCellValue('F1', 'Serie');
$hojaActiva->setCellValue('G1', 'Gerencia');
$hojaActiva->setCellValue('H1', 'Dependencia');
$hojaActiva->setCellValue('I1', 'Unidad');
$hojaActiva->setCellValue('J1', 'Responsable');
$hojaActiva->setCellValue('K1', 'Registro');
$hojaActiva->setCellValue('L1', 'Usuario');
$hojaActiva->setCellValue('M1', 'Solicitud');
$hojaActiva->setCellValue('N1', 'Receptor pendiente');
$hojaActiva->setCellValue('O1', 'Registro pendiente');
$hojaActiva->setCellValue('P1', 'Correo pendiente');

//LLenar datos
$tipos = ['', 'Activo', 'Controlado', 'Arrendamiento Operativo'];
$clases = ['', '', 'Maquinaria y equipo', 'Muebles y enseres', 'Equipos de computo', 'Equipos de comunicaciones', 'Vehículos', 'Equipos de laboratorio'];
$subClases = ['', '', 'Escritorio (Alta)', 'Escritorio (Normal)', 'Portátil (Alta)', 'Portátil (Normal)', 'Workstation', 'Dos en Uno', 'Monitor', 'Combo Teclado/Mouse'];
for($i = 0; $i < count($respuesta['data']); $i++){
	$hojaActiva->setCellValue('A'.($i+2), $respuesta['data'][$i]['idElemento']);
	$hojaActiva->setCellValue('B'.($i+2), $tipos[$respuesta['data'][$i]['fk_tipos']]);
	$hojaActiva->setCellValue('C'.($i+2), $clases[$respuesta['data'][$i]['fk_clases']]);
	$hojaActiva->setCellValue('D'.($i+2), $subClases[$respuesta['data'][$i]['fk_subclases']]);
	$hojaActiva->setCellValue('E'.($i+2), $respuesta['data'][$i]['elemento']);
	$hojaActiva->setCellValue('F'.($i+2), $respuesta['data'][$i]['serie']);
	$hojaActiva->setCellValue('G'.($i+2), $respuesta['data'][$i]['gerencia']);
	$hojaActiva->setCellValue('H'.($i+2), $respuesta['data'][$i]['dependencia']);
	$hojaActiva->setCellValue('I'.($i+2), $respuesta['data'][$i]['unidad']);
	$hojaActiva->setCellValue('J'.($i+2), $respuesta['data'][$i]['receptorAsignado']);
	$hojaActiva->setCellValue('K'.($i+2), $respuesta['data'][$i]['registroAsignado']);
	$hojaActiva->setCellValue('L'.($i+2), $respuesta['data'][$i]['loginAsignado']);
	$hojaActiva->setCellValue('M'.($i+2), $respuesta['data'][$i]['idSolicitud']);
	$hojaActiva->setCellValue('N'.($i+2), $respuesta['data'][$i]['receptorPendiente']);
	$hojaActiva->setCellValue('O'.($i+2), $respuesta['data'][$i]['registroPendiente']);
	$hojaActiva->setCellValue('P'.($i+2), $respuesta['data'][$i]['correoPendiente']);
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="Elementos.xlsx"');
$writer = new Xlsx($excel);
$writer->save('php://output');