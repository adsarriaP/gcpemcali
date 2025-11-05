<?php
require_once "controllers/usuarios.php";
require_once "controllers/elementos.php";
require_once "vendor/fpdf186/fpdf.php";
session_start();

//Bloqueo por si intenta suplantar
if($_SESSION['usuario']['rol'] != 'Administrador' and $_SESSION['usuario']['rol'] != 'FacilitadorArea' and $_SESSION['usuario']['rol'] != 'Jefe'){
	if($_SESSION['usuario']['id'] != $parametros[0]){
		echo "Sin permisos...";
		exit();
	}
}

//Usuario
$obj = new usuarios();
$datos = [
			'info' => [
				'id' => $parametros[0]
			]
		];
$usuario = $obj->select($datos);

//Elementos
$obj = new elementos();
$datos = [
			'trabajador' => $parametros[0]
		];
$elementos = $obj->getPDF($datos);

$activos = [];
$controlados = [];
$ao = [];
for ($i = 0; $i < count($elementos['data']); $i++) {
	switch ($elementos['data'][$i]['fk_tipos']) {
		case '1':
			array_push($activos, $elementos['data'][$i]);
			break;
		case '2':
			array_push($controlados, $elementos['data'][$i]);
			break;
		default:
			array_push($ao, $elementos['data'][$i]);
			break;
	}
}

class PDF extends FPDF
{
    function Footer()
    {
        // Go to 1.5 cm from bottom
        $this->SetY(-15);
        // Select Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Print centered page number
        $this->Cell(40,5,utf8_decode('Generó:'));
		$this->Cell(90,5,$_SESSION['usuario']['login'],0,1);
		$this->Cell(40,3,utf8_decode('Fecha de generación:'));
		$this->Cell(90,3,date("Y-m-d H:i:s"),0,1);
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);

//Encabezado
$pdf->Image('dist/img/logoEmcali.png', 10, 12, -200);
$pdf->Cell(50,20,'');
$pdf->Cell(90,10,'EMCALI EICE ESP',0,1,'C');
$pdf->SetX(60);
$pdf->Cell(90,10,'Estado de cuenta',0,1,'C');
$pdf->Cell(190,5,'','B',1);
$pdf->Ln();

//texto de ancabezado
$pdf->MultiCell(190,5,utf8_decode('Que el(la) funcionario(a) '.$usuario['data'][0]['nombre'].' identificado(a) con la C.C. No. '.$usuario['data'][0]['cedula'].', tiene a la fecha los siguientes elementos a su cargo:'),0,'J');
$pdf->Cell(40,7,utf8_decode('Código empleado:'));
$pdf->Cell(90,7,$usuario['data'][0]['registro'],0,1);
$pdf->Cell(40,7,'Segmento:');
$pdf->Cell(90,7,'200',0,1);

//Activos
if(count($activos) != 0){
	//Encabezado de tabla
	$pdf->Cell(190,5,'ACTIVOS',1,1,'C');
	$pdf->Cell(20,5,utf8_decode('CÓDIGO'),1,0,'C');
	$pdf->Cell(10,5,'SUB',1,0,'C');
	$pdf->Cell(55,5,utf8_decode('DESCRIPCIÓN'),1,0,'C');
	$pdf->Cell(15,5,'UBICAC',1,0,'C');
	$pdf->Cell(35,5,'INVENTARIO',1,0,'C');
	$pdf->Cell(20,5,'SERIE',1,0,'C');
	$pdf->Cell(10,5,'CANT',1,0,'C');
	$pdf->Cell(25,5,'VALOR',1,1,'C');

	$pdf->SetFont('Arial','',7);
	for ($i = 0; $i < count($activos); $i++) {
		$pdf->Cell(20,5,$activos[$i]['codigo'],1,0,'C');
		$pdf->Cell(10,5,$activos[$i]['sn'],1,0,'C');
		$pdf->SetFont('Arial','',6);
		$pdf->Cell(55,5,utf8_decode($activos[$i]['elemento']),1);
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(15,5,'500',1,0,'C');
		$pdf->Cell(35,5,$activos[$i]['inventario'],1);
		$pdf->SetFont('Arial','',6);
		$pdf->Cell(20,5,$activos[$i]['serie'],1);
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(10,5,$activos[$i]['cantidad'],1,0,'C');
		$pdf->Cell(25,5,$activos[$i]['valor'],1,1,'R');
	}
}

$pdf->Ln();

//Controlados
$pdf->SetFont('Arial','B',8);
if(count($controlados) != 0){
	//Encabezado de tabla
	$pdf->Cell(190,5,'CONTROLADOS',1,1,'C');
	$pdf->Cell(20,5,utf8_decode('CÓDIGO'),1,0,'C');
	$pdf->Cell(10,5,'SUB',1,0,'C');
	$pdf->Cell(55,5,utf8_decode('DESCRICPIÓN'),1,0,'C');
	$pdf->Cell(15,5,'UBICAC',1,0,'C');
	$pdf->Cell(35,5,'INVENTARIO',1,0,'C');
	$pdf->Cell(20,5,'SERIE',1,0,'C');
	$pdf->Cell(10,5,'CANT',1,0,'C');
	$pdf->Cell(25,5,'VALOR',1,1,'C');

	$pdf->SetFont('Arial','',8);
	for ($i = 0; $i < count($controlados); $i++) {
		$pdf->Cell(20,5,$controlados[$i]['codigo'],1,0,'C');
		$pdf->Cell(10,5,$controlados[$i]['sn'],1,0,'C');
		$pdf->SetFont('Arial','',6);
		$pdf->Cell(55,5,utf8_decode($controlados[$i]['elemento']),1);
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(15,5,'500',1,0,'C');
		$pdf->Cell(35,5,$controlados[$i]['inventario'],1);
		$pdf->SetFont('Arial','',6);
		$pdf->Cell(20,5,$controlados[$i]['serie'],1);
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(10,5,$controlados[$i]['cantidad'],1,0,'C');
		$pdf->Cell(25,5,$controlados[$i]['valor'],1,1,'R');
	}
}

$pdf->Ln();

//AO
$pdf->SetFont('Arial','B',8);
if(count($ao) != 0){
	//Encabezado de tabla
	$pdf->Cell(190,5,'ARRENDAMIENTOS OPERATIVOS',1,1,'C');
	$pdf->Cell(20,5,utf8_decode('CÓDIGO'),1,0,'C');
	$pdf->Cell(10,5,'SUB',1,0,'C');
	$pdf->Cell(55,5,utf8_decode('DESCRICPIÓN'),1,0,'C');
	$pdf->Cell(15,5,'UBICAC',1,0,'C');
	$pdf->Cell(35,5,'INVENTARIO',1,0,'C');
	$pdf->Cell(20,5,'SERIE',1,0,'C');
	$pdf->Cell(10,5,'CANT',1,0,'C');
	$pdf->Cell(25,5,'VALOR',1,1,'C');

	$pdf->SetFont('Arial','',8);
	for ($i = 0; $i < count($ao); $i++) {
		$pdf->Cell(20,5,$ao[$i]['codigo'],1,0,'C');
		$pdf->Cell(10,5,$ao[$i]['sn'],1,0,'C');
		$pdf->SetFont('Arial','',6);
		$pdf->Cell(55,5,utf8_decode($ao[$i]['elemento']),1);
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(15,5,'500',1,0,'C');
		$pdf->Cell(35,5,$ao[$i]['inventario'],1);
		$pdf->SetFont('Arial','',6);
		$pdf->Cell(20,5,$ao[$i]['serie'],1);
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(10,5,$ao[$i]['cantidad'],1,0,'C');
		$pdf->Cell(25,5,$ao[$i]['valor'],1,1,'R');
	}
}

$pdf->Output();
?>