<?php
require_once "controllers/integracion.php";

$obj = new integracion();
$datos = [
	'nombre' => 'victor',
	'apellido' => 'hernandez'
];
$resultado = $obj->getElementos($datos);

print_r($resultado);