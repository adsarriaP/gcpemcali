<?php
require_once "controllers/usuarios.php";
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
$aprobaciones = $obj->getHistorico($datos);

//Extraer datos del solicitante
$obj = new usuarios();
$datos = [
            'info' => [
                'id' => $solicitud['data'][0]['idSolicitante']
            ]
        ];
$solicitante = $obj->getUsuario($datos);

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
$tipos = ['', 'Activo', 'Controlado', 'Arrendamiento Operativo'];
$clases = ['', '', 'Maquinaria y equipo', 'Muebles y enseres', 'Equipos de computo', 'Equipos de comunicaciones', 'Vehículos', 'Equipos de laboratorio'];

$pdf->SetFont('Arial','',10);

//texto de ancabezado
$pdf->MultiCell(190,5,utf8_decode('Que el trabajador '.$solicitud['data'][0]['solicitante'].' identificado con la C.C. No. '.$solicitud['data'][0]['solicitanteCedula'].' y registro laboral No. '.$solicitud['data'][0]['solicitanteRegistro'].' inicio trámite de la siguiente solicitud:'),0,'J');
$pdf->Ln();

$pdf->Cell(50,7,'Solicitud',1);
$pdf->Cell(140,7,$solicitud['data'][0]['id'],1,1);
$pdf->Cell(50,7,utf8_decode('Trámite'),1);
$pdf->Cell(140,7,utf8_decode($tramites[$solicitud['data'][0]['tramite']]),1,1);
$pdf->Cell(50,7,'Tipo',1);
$pdf->Cell(140,7,$tipos[$solicitud['data'][0]['tipo']],1,1);
$pdf->Cell(50,7,'Clase',1);
$pdf->Cell(140,7,utf8_decode($clases[$solicitud['data'][0]['clase']]),1,1);
$pdf->Cell(50,7,utf8_decode('Código del elemento'),1);
$pdf->Cell(140,7,$solicitud['data'][0]['codigo'],1,1);
$pdf->Cell(50,7,utf8_decode('Descripción'),'L');
$pdf->MultiCell(140,5,utf8_decode($solicitud['data'][0]['elemento']),1,'J');
$pdf->Cell(50,7,'Solicitante','LT');
$pdf->MultiCell(140,7,utf8_decode($solicitud['data'][0]['solicitante']."\nRegistro laboral: ".$solicitud['data'][0]['solicitanteRegistro']."\nGerencia: ".$solicitante['data'][0]['gerencia']."\nUnidad: ".$solicitante['data'][0]['unidad']),1);
/*$pdf->Cell(50,7,'Solicitante',1,0);
$pdf->Cell(140,7,$solicitud['data'][0]['solicitante'],1,1);
$pdf->Cell(50,7,'Registro laboral',1,0);
$pdf->Cell(140,7,$solicitud['data'][0]['solicitanteRegistro'],1,1);
$pdf->Cell(50,7,'Gerencia',1,0);
$pdf->Cell(140,7,$solicitante['data'][0]['gerencia'],1,1);
$pdf->Cell(50,7,'Unidad',1,0);
$pdf->Cell(140,7,$solicitante['data'][0]['unidad'],1,1);*/
if($solicitud['data'][0]['tramite'] == 1 or $solicitud['data'][0]['tramite'] == 3){
	$pdf->Cell(50,7,'Receptor','LT');
	$pdf->MultiCell(140,7,utf8_decode($solicitud['data'][0]['receptor']."\nRegistro laboral: ".$solicitud['data'][0]['receptorRegistro']),1);
}
$pdf->Cell(50,7,utf8_decode('Fecha de creación'),1);
$pdf->Cell(140,7,$solicitud['data'][0]['fecha_creacion'],1,1);
$pdf->Ln();

$pdf->MultiCell(190,5,utf8_decode('Con el siguiente histórico de aprobaciones:'),0,1);

$temp = [];
$estado = 0;
$estadosHistorico = [
        '',
        [//Asignación
            '',
            [//Activo
                '',
                '',                
                ['','Creo solicitud','Aceptó elemento','','','','','Entregó elemento','Actualizó SAP','','Actualizó carpeta','Anuló'],//maquinaria y equipo
                ['','Creo solicitud','Aceptó elemento','','','','','Entregó elemento','Actualizó SAP','','Actualizó carpeta','Anuló'],//muebles
                ['','Creo solicitud','Aceptó elemento','','','','','Entregó elemento','Actualizó SAP','','Actualizó carpeta','Anuló'],//equipos de computo
                ['','Creo solicitud','Aceptó elemento','','','','','Entregó elemento','Actualizó SAP','','Actualizó carpeta','Anuló'],//Equipos de comunicaciones
                ['','Creo solicitud','Aceptó elemento','','','','','Entregó elemento','Actualizó SAP','','Actualizó carpeta','Anuló'],//Vehiculos
                ['','Creo solicitud','Aceptó elemento','','','','','Entregó elemento','Actualizó SAP','','Actualizó carpeta','Anuló']//Equipos de laboratorio
            ],
            [//Controlado
                '',
                '',
                ['','Creo solicitud','Aceptó elemento','','','','','','Entregó elemento','','Actualizó carpeta','Anuló'],//maquinaria y equipo
                ['','Creo solicitud','Aceptó elemento','','','','','','Entregó elemento','','Actualizó carpeta','Anuló'],//muebles
                ['','Creo solicitud','Aceptó elemento','','','','','','Entregó elemento','','Actualizó carpeta','Anuló'],//equipos de computo
                ['','Creo solicitud','Aceptó elemento','','','','','','Entregó elemento','','Actualizó carpeta','Anuló'],//Equipos de comunicaciones
                ['','Creo solicitud','Aceptó elemento','','','','','','Entregó elemento','','Actualizó carpeta','Anuló'],//Vehiculos
                ['','Creo solicitud','Aceptó elemento','','','','','','Entregó elemento','','Actualizó carpeta','Anuló']//Equipos de laboratorio
            ],
            [//AO
                '',
                '',
                ['','Creo solicitud','Aceptó elemento','','','','','','Entregó elemento','','Actualizó carpeta','Anuló'],//maquinaria y equipo
                ['','Creo solicitud','Aceptó elemento','','','','','','Entregó elemento','','Actualizó carpeta','Anuló'],//muebles
                ['','Creo solicitud','Aceptó elemento','','','','','','Entregó elemento','','Actualizó carpeta','Anuló'],//equipos de computo
                ['','Creo solicitud','Aceptó elemento','','','','','','Entregó elemento','','Actualizó carpeta','Anuló'],//Equipos de comunicaciones
                ['','Creo solicitud','Aceptó elemento','','','','','','Entregó elemento','','Actualizó carpeta','Anuló'],//Vehiculos
                ['','Creo solicitud','Aceptó elemento','','','','','','Entregó elemento','','Actualizó carpeta','Anuló']//Equipos de laboratorio
            ]
        ],
        '',
        [//Traspaso
            '',
            [//Activo
                '',
                '',
                ['','Creo solicitud','','Aceptó elemento','','','','Aprobó jefe','Actualizó SAP','','Actualizó carpeta','Anuló'],//maquinaria y equipo
                ['','Creo solicitud','','Aceptó elemento','','','','Aprobó jefe','Actualizó SAP','','Actualizó carpeta','Anuló'],//muebles
                ['','Creo solicitud','','Aceptó elemento','','','','Aprobó jefe','Actualizó SAP','','Actualizó carpeta','Anuló'],//equipos de computo
                ['','Creo solicitud','','Aceptó elemento','','','','Aprobó jefe','Actualizó SAP','','Actualizó carpeta','Anuló'],//Equipos de comunicaciones
                ['','Creo solicitud','','Aceptó elemento','Aprobó jefe','Realizó inspección','','Aprobó inspección','Actualizó SAP','','Actualizó carpeta','Anuló'],//Vehiculos
                ['','Creo solicitud','','Aceptó elemento','','','','Aprobó jefe','Actualizó SAP','','Actualizó carpeta','Anuló']//Equipos de laboratorio
            ],
            [//Controlado
                '',
                '',
                ['','Creo solicitud','','Aceptó elemento','','','','','Aprobó jefe','','Actualizó carpeta','Anuló'],//maquinaria y equipo
                ['','Creo solicitud','','Aceptó elemento','','','','','Aprobó jefe','','Actualizó carpeta','Anuló'],//muebles
                ['','Creo solicitud','','Aceptó elemento','','','','','Aprobó jefe','','Actualizó carpeta','Anuló'],//equipos de computo
                ['','Creo solicitud','','Aceptó elemento','','','','','Aprobó jefe','','Actualizó carpeta','Anuló'],//Equipos de comunicaciones
                ['','Creo solicitud','','Aceptó elemento','','','','','Aprobó jefe','','Actualizó carpeta','Anuló'],//Vehiculos
                ['','Creo solicitud','','Aceptó elemento','','','','','Aprobó jefe','','Actualizó carpeta','Anuló']//Equipos de laboratorio
            ],
            [//AO
                '',
                '',
                ['','Creo solicitud','','Aceptó elemento','','','','','Aprobó jefe','','Actualizó carpeta','Anuló'],//maquinaria y equipo
                ['','Creo solicitud','','Aceptó elemento','','','','','Aprobó jefe','','Actualizó carpeta','Anuló'],//muebles
                ['','Creo solicitud','','Aceptó elemento','','','','','Aprobó jefe','','Actualizó carpeta','Anuló'],//equipos de computo
                ['','Creo solicitud','','Aceptó elemento','','','','','Aprobó jefe','','Actualizó carpeta','Anuló'],//Equipos de comunicaciones
                ['','Creo solicitud','','Aceptó elemento','','','','','Aprobó jefe','','Actualizó carpeta','Anuló'],//Vehiculos
                ['','Creo solicitud','','Aceptó elemento','','','','','Aprobó jefe','','Actualizó carpeta','Anuló']//Equipos de laboratorio
            ]
        ],
        [//Reintegro
            '',
            [//Activo
                '',
                '',
                ['','','','Creo solicitud','','','Aprobó jefe','Recibió en almacén','Actualizó SAP','','Actualizó carpeta','Anuló'],//maquinaria y equipo
                ['','','','Creo solicitud','Aprobó jefe','Realizó inspección','Aprobó inspección','Recibió en almacén','Actualizó SAP','','Actualizó carpeta','Anuló'],//muebles
                ['','','','Creo solicitud','Aprobó jefe','Realizó inspección','Aprobó inspección','Recibió en almacén','Actualizó SAP','','Actualizó carpeta','Anuló'],//equipos de computo
                ['','','','Creo solicitud','Aprobó jefe','Realizó inspección','Aprobó inspección','Recibió en almacén','Actualizó SAP','','Actualizó carpeta','Anuló'],//Equipos de comunicaciones
                ['','','','Creo solicitud','Aprobó jefe','Realizó inspección','Aprobó inspección','Recibió en almacén','Actualizó SAP','','Actualizó carpeta','Anuló'],//Vehiculos
                ['','','','Creo solicitud','','','Aprobó jefe','Recibió en almacén','Actualizó SAP','','Actualizó carpeta','Anuló']//Equipos de laboratorio
            ],
            [//Controlado
                '',
                '',
                ['','','','Creo solicitud','','','Aprobó jefe','','Recibió en almacén','','Actualizó carpeta','Anuló'],//maquinaria y equipo
                ['','','','Creo solicitud','','','Aprobó jefe','','Recibió en almacén','','Actualizó carpeta','Anuló'],//muebles
                ['','','','Creo solicitud','Aprobó jefe','Realizó inspección','Aprobó inspección','','Recibió en almacén','','Actualizó carpeta','Anuló'],//equipos de computo
                ['','','','Creo solicitud','','','Aprobó jefe','','Recibió en almacén','','Actualizó carpeta','Anuló'],//Equipos de comunicaciones
                ['','','','Creo solicitud','Aprobó jefe','Realizó inspección','Aprobó inspección','','Recibió en almacén','','Actualizó carpeta','Anuló'],//Vehiculos
                ['','','','Creo solicitud','','','Aprobó jefe','','Recibió en almacén','','Actualizó carpeta','Anuló']//Equipos de laboratorio
            ],
            [//AO
                '',
                '',
                ['','','','Creo solicitud','','','','','','Aprobó jefe','Cambio elemento','Anuló'],//maquinaria y equipo
                ['','','','Creo solicitud','','','','','','Aprobó jefe','Cambio elemento','Anuló'],//muebles
                ['','','','Creo solicitud','Aprobó jefe','Realizó inspección','','','','Aprobó inspección','Cambio elemento','Anuló'],//equipos de computo
                ['','','','Creo solicitud','','','','','','Aprobó jefe','Cambio elemento','Anuló'],//Equipos de comunicaciones
                ['','','','Creo solicitud','Aprobó jefe','Realizó inspección','','','','Aprobó inspección','Cambio elemento','Anuló'],//Vehiculos
                ['','','','Creo solicitud','','','','','','Aprobó jefe','Cambio elemento','Anuló']//Equipos de laboratorio
            ]
        ]
    ];
if(count($aprobaciones['data']) != 0){
	//Encabezado de tabla	
	$pdf->Cell(80,7,'Trabjador',1,0,'C');
	$pdf->Cell(50,7,utf8_decode('Acción'),1,0,'C');
	$pdf->Cell(60,7,'Fecha',1,1,'C');	

	for ($i = 0; $i < count($aprobaciones['data']); $i++) {
		$temp = json_decode($aprobaciones['data'][$i]['informacion']);
		$estado = $temp->estado;
		$pdf->Cell(80,7,utf8_decode($aprobaciones['data'][$i]['nombre']),1);
		$pdf->Cell(50,7,utf8_decode($estadosHistorico[$solicitud['data'][0]['tramite']][$solicitud['data'][0]['tipo']][$solicitud['data'][0]['clase']][$estado]),1);
		$pdf->Cell(60,7,$aprobaciones['data'][$i]['fecha_creacion'],1,1,'C');
	}
}

$pdf->Output();
?>