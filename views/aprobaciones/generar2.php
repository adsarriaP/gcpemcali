<?php
require_once "controllers/solicitudes.php";
require_once "controllers/solicitudesHistorico.php";
require_once "vendor/fpdf186/fpdf.php";
session_start();

//Datos de la solicitud
$obj = new solicitudes();
$datos = [	
			'criterio' => 'solicitud',
			'id' => $parametros[0]
		];
$solicitud = $obj->getSolicitudAll($datos);

//Aprobaciones
$obj = new solicitudesHistorico();
$datos = [			
			'solicitud' => $parametros[0]
		];
$aprobaciones = $obj->getPDF($datos);

/*print_r($respuesta);
exit();*/

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
$pdf->Cell(90,10,'Comprobante de autorizaciones',0,1,'C');
$pdf->Cell(190,5,'','T',1);

$tramites = ['','Asignación','','Traspaso','Reintegro'];

//texto de ancabezado
$pdf->MultiCell(190,5,utf8_decode('Que el funcionario '.$respuesta['data'][0]['funcionario'].' identificado con la C.C. No. '.$respuesta['data'][0]['cedula'].' y registro laboral No. '.$respuesta['data'][0]['registro'].', para la solicitud #'.$parametros[0].' que contiene el trámite de '.$tramites[$respuesta['data'][0]['tramite']].' para el siguiente activo:'),0,1);
$pdf->Ln();

$pdf->Cell(25,5,'TIPO',1,0,'C');
$pdf->Cell(25,5,utf8_decode('CÓDIGO'),1,0,'C');
$pdf->Cell(140,5,utf8_decode('DESCRIPCIÓN'),1,1,'C');

$pdf->SetFont('Arial','',8);
$tipo = ['', 'Activo', 'Controlado', 'AO'];
$pdf->Cell(25,5,$tipo[$respuesta['data'][0]['fk_tipos']],1,0);
$pdf->Cell(25,5,$respuesta['data'][0]['codigo'],1,0);
$pdf->MultiCell(140,5,utf8_decode($respuesta['data'][0]['elemento']),1,1);
$pdf->Ln();

$pdf->SetFont('Arial','B',10);
$pdf->MultiCell(190,5,utf8_decode('Presenta el siguiente histórico de aprobaciones:'),0,1);

$temp = [];
$estado = 0;
$estadosHistorico = [
						'',
						['','Creo solicitud','Acepto asignación','','','','','Entrego elemento','Entrego elemento o actualizó SAP','','Actualizó carpeta','Anuló'],
						'',
						['','Creo solicitud','','Acepto asignación','Aprobó solicitud','Realizó inspección','Aprobó inspección','Aprobó solicitud','Actualizó SAP','','Actualizó carpeta','Anuló'],
                        ['','','','Creo solicitud','Aprobó solicitud','Realizó inspección','Aprobó solicitud o Aprobó inspección','Recibio en almacen','Actualizó SAP','','Actualizó carpeta','Anuló']
					];
if(count($respuesta['data']) != 0){
	//Encabezado de tabla
	$pdf->Cell(80,5,'FUNCIONARIO',1,0,'C');
	$pdf->Cell(50,5,utf8_decode('ACCIÓN'),1,0,'C');
	$pdf->Cell(60,5,'FECHA',1,1,'C');

	$pdf->SetFont('Arial','',8);

	for ($i = 0; $i < count($respuesta['data']); $i++) {
		$temp = json_decode($respuesta['data'][$i]['informacion']);
		$estado = $temp->estado;
		$pdf->Cell(80,5,utf8_decode($respuesta['data'][$i]['nombre']),1);
		$pdf->Cell(50,5,utf8_decode($estadosHistorico[$respuesta['data'][0]['tramite']][$estado]),1);
		$pdf->Cell(60,5,$respuesta['data'][$i]['fecha_creacion'],1,1,'C');
	}
}

$pdf->Output();
?>