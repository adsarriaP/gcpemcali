<?php
require_once "controllers/usuarios.php";
require_once "controllers/solicitudes.php";
require_once "vendor/fpdf186/fpdf.php";
session_start();

$obj = new solicitudes();
$datos = [
			'criterio' => 'id',
			'id' => $parametros[0]
		];
$solicitud = $obj->getSolicitudes($datos);

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
$pdf->Cell(90,10,'Comprobante de entrega',0,1,'C');
$pdf->Image('dist/img/logo.png', 160, 15, -150);
$pdf->Cell(190,5,'','B',1);
$pdf->Ln();

$pdf->SetFont('Arial','',10);

//Fecha
$pdf->Cell(15,5,'Fecha:');
$pdf->Cell(35,5,'','B',1);
$pdf->Ln();

//texto de ancabezado
$pdf->MultiCell(190,5,utf8_decode('EMCALI EICE ESP a través del presente documento realiza la entrega formal del elemento asignado para el cumplimiento de las actividades laborales del funcionario responsable. El funcionario declara haber recibido el equipo en buen estado y se compromete a cuidar los recursos y a utilizarlos exclusivamente para los fines establecidos.'),0,'J');
$pdf->Ln();

//Extraer datos del funcionario
$obj = new usuarios();
$datos = [
			'info' => [
				'id' => $solicitud['data'][0]['idReceptor']
			]
		];
$receptor = $obj->getUsuario($datos);

$pdf->Cell(190,5,'Funcionario responsable',0,1);
$pdf->Cell(50,5,'Nombre',1,0);
$pdf->Cell(140,5,utf8_decode($receptor['data'][0]['nombre']),1,1);
$pdf->Cell(50,5,'Registro',1,0);
$pdf->Cell(140,5,$receptor['data'][0]['registro'],1,1);
$pdf->Cell(50,5,'Gerencia',1,0);
$pdf->Cell(140,5,$receptor['data'][0]['gerencia'],1,1);
$pdf->Cell(50,5,'Unidad',1,0);
$pdf->Cell(140,5,$receptor['data'][0]['unidad'],1,1);
$pdf->Cell(50,5,utf8_decode('Télefono'),1,0);
$pdf->Cell(140,5,$receptor['data'][0]['telefono'],1,1);
$pdf->Cell(50,5,'Correo',1,0);
$pdf->Cell(140,5,$receptor['data'][0]['correo'],1,1);
$pdf->Ln();

$tipos = ['', 'Activo', 'Controlado', 'Arrendamiento Operativo'];

$pdf->Cell(190,5,'Elemento asignado',0,1);
$pdf->Cell(50,5,'Tipo',1,0);
$pdf->Cell(140,5,$tipos[$solicitud['data'][0]['tipo']],1,1);
$pdf->Cell(50,5,utf8_decode('Código GCP'),1,0);
$pdf->Cell(140,5,$solicitud['data'][0]['idElemento'],1,1);
$pdf->Cell(50,5,utf8_decode('Código externo'),1,0);
$pdf->Cell(140,5,$solicitud['data'][0]['codigo'],1,1);
$pdf->Cell(50,5,'Nombre','L',0);
$pdf->MultiCell(140,5,utf8_decode($solicitud['data'][0]['elemento']),1,'J');
$pdf->Cell(50,5,'Serie',1,0);
$pdf->Cell(140,5,$solicitud['data'][0]['serie'],1,1);
$pdf->Cell(50,5,'Valor',1,0);
$pdf->Cell(140,5,$solicitud['data'][0]['valor'],1,1);
$pdf->Ln();

$pdf->Cell(190,5,'Funcionario quien recibe',0,1);
$pdf->Cell(140,20,'',0,1);
$pdf->Cell(100,5,'Firma','T',1);

$pdf->Output();
?>