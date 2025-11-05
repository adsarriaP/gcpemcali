<?php
require_once "libs/baseCrud.php";
require_once "elementosHistorico.php";
require_once 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

class elementos extends baseCrud{
	protected $tabla = 'elementos';

	public function updateHistorico($datos){
		$resultado = parent::update($datos);				
		//Guardo historico
		if($resultado['ejecuto']){
			$info = [
				'info'=>[
					'fk_elementos' => $datos['id'],
					'informacion' => json_encode($datos['info'])
				]
			];
			$objHistorico = new elementosHistorico();
			$respuesta = $objHistorico->insert($info);
			if($respuesta['ejecuto']){
				return $resultado;
			}
		}
	}

	public function getPDF($datos){
		$sql = "SELECT					
					ele.fk_tipos,
					ele.codigo,
					ele.sn,
					SUBSTR(ele.descripcion,1,40) AS elemento,
					ele.inventario,
					ele.serie,
					ele.cantidad,
					CONCAT('$',FORMAT(ele.valor,2,'es_CO')) AS valor
				FROM
					elementos ele
				WHERE
					ele.responsable = $datos[trabajador]";
		$db = new database();
       	return $db->ejecutarConsulta($sql);
	}

	public function getElementos($datos){
		$filtro = 0;
		switch ($datos['criterio']) {
			case 'id':
				$filtro = "ele.id = $datos[valor]";
				break;
			case 'codigo':
				$filtro = "ele.codigo = '$datos[valor]'";
				break;
			case 'serie':
				$filtro = "ele.serie = '$datos[valor]'";
				break;
			case 'inventario':
				$filtro = "ele.inventario = '$datos[valor]'";
				break;
			case 'responsable':
				$filtro = "ele.responsable = {$_SESSION['usuario']['id']}";
				break;
			case 'registro':
				$filtro = "usu.registro = '$datos[valor]'";
				break;
			case 'migracion':
				$filtro = "ele.responsable2 = '$datos[valor]'";
				break;
			case 'serie':
				$filtro = "ele.serie = '$datos[valor]'";
				break;
			case 'dependencia':
				$filtro = "ele.fk_dependencias = '$datos[valor]'";
				break;
			case 'todos':
				//Busqueda de libres
				$filtro = "ele.fk_dependencias = $datos[dependencia]";
				break;
			case 'sinResp':
				//Busqueda de libres
				$filtro = "ele.fk_dependencias = $datos[dependencia] AND ele.responsable = 1";
				break;
			case 'asignados':
				//Busqueda de asignados				
				$filtro = "ele.fk_dependencias = $datos[dependencia] AND ele.responsable != 1";
				break;
			case 'contratoTodos':
				//Busqueda de asignados				
				$filtro = "ele.fk_contratos = $datos[contrato]";
				break;
			case 'contratoSinDep':
				//Busqueda de asignados				
				$filtro = "ele.fk_contratos = $datos[contrato] AND ele.fk_dependencias = 1";
				break;
			case 'contratoSinResp':
				//Busqueda de asignados				
				$filtro = "ele.fk_contratos = $datos[contrato] AND ele.responsable = 1";
				break;
			case 'contratoAsignados':
				//Busqueda de asignados				
				$filtro = "ele.fk_contratos = $datos[contrato] AND ele.responsable != 1";
				break;
			case 'sinRespDM':
				//Busqueda de libres
				$filtro = "ele.id != 1 AND ele.fk_contratos = 1 AND ele.responsable = 1 AND ele.responsable2 != 1 AND ele.estado = 'Activo'";
				break;
			case 'sinRespDMNew':
				//Busqueda de libres
				$filtro = "ele.id != 1 AND ele.fk_contratos = 1 AND ele.responsable = 1 AND ele.responsable2 = 1 AND ele.estado = 'Activo'";
				break;
			default:
				// code...
				break;
		}
		$sql = "SELECT
					ele.id,
					ele.fk_tipos,
					ele.fk_clases,
					ele.fk_subclases,
					ele.codigo,
					ele.descripcion AS elemento,
					ele.inventario,
					ele.serie,
					ele.valor,
					ele.responsable,
					ele.responsable2,
					ele.en_tramite,
					ele.devolver,
					ele.estado,
					IFNULL(ele.uso, '') AS uso,
					dep.id AS idDep,
					dep.gerencia,
					dep.dependencia,
					dep.unidad,					
					usu.nombre AS trabajador,
					usu.registro,
					usu.login
				FROM
					(elementos ele 
						INNER JOIN dependencias dep ON ele.fk_dependencias = dep.id)
						INNER JOIN usuarios usu ON ele.responsable = usu.id
				WHERE
					$filtro";
		$db = new database();
       	return $db->ejecutarConsulta($sql);
	}

	public function getElementosContrato($datos){
		$sql = "SELECT
					*
				FROM
					(SELECT
						ele.id AS idElemento,
						ele.fk_tipos,
						ele.fk_clases,
						ele.fk_subclases,
						ele.descripcion AS elemento,
						ele.serie,
						dep.gerencia,
						dep.dependencia,
						dep.unidad,
						usu.nombre AS receptorAsignado,
						usu.registro AS registroAsignado,
						usu.login AS loginAsignado
					FROM
						(elementos ele INNER JOIN dependencias dep ON ele.fk_dependencias = dep.id) INNER JOIN usuarios usu ON ele.responsable = usu.id
					WHERE
						ele.fk_contratos = ".$datos['contrato'].") AS elementos
				LEFT JOIN
					(SELECT
						sol.id AS idSolicitud,
						sol.fk_elementos,
						usu.nombre AS receptorPendiente,
						usu.registro AS registroPendiente,
						usu.correo AS correoPendiente
					FROM
						solicitudes sol INNER JOIN usuarios usu ON sol.receptor = usu.id
					WHERE
						sol.fk_tramites = 1
						AND sol.estado IN (1,2)) AS solicitudes
				ON
					elementos.idElemento = solicitudes.fk_elementos";
		$db = new database();
       	return $db->ejecutarConsulta($sql);
	}

	public function getElementosSinSoporte($datos){
		$sql = "SELECT
					dep.gerencia,
					dep.dependencia,
					dep.unidad,
					usu.nombre AS trabajador,
					usu.registro,
					usu.correo,
					ele.id,
					ele.fk_tipos,
					ele.codigo,
					ele.descripcion,
					ele.serie
				FROM
					elementos ele INNER JOIN (usuarios usu INNER JOIN dependencias dep ON usu.fk_dependencias = dep.id) ON ele.responsable = usu.id
				WHERE
					ele.responsable != 1
					AND ele.devolver = 1";
		$db = new database();
       	return $db->ejecutarConsulta($sql);
	}

	public function cargar($datos){
		$correctos = 0;
		$fallidos = 0;
		$documento = IOFactory::load($_FILES['archivo']['tmp_name']);
		$hojaActual = $documento->getSheet(0);
		$registros = $hojaActual->getHighestDataRow();
		//Si todo esta bien, cargar y actualizar estado de periodo
		$db = new database();
		for($i = 2; $i <= $registros; $i++){
			if($hojaActual->getCell('A'.$i)->getCalculatedValue() != 0){
				$sql = "INSERT INTO
							elementos
						SET
							fk_tipos = ".$datos['tipo'].",
							codigo = '".$hojaActual->getCell('A'.$i)->getCalculatedValue()."',
							sn = '".$hojaActual->getCell('B'.$i)->getCalculatedValue()."',
							descripcion = '".$hojaActual->getCell('C'.$i)->getCalculatedValue()."',
							inventario = '".$hojaActual->getCell('D'.$i)->getCalculatedValue()."',
							serie = '".$hojaActual->getCell('E'.$i)->getCalculatedValue()."',
							fk_clases = ".$hojaActual->getCell('F'.$i)->getCalculatedValue().",
							fk_subclases = ".$hojaActual->getCell('G'.$i)->getCalculatedValue().",
							valor = ".$hojaActual->getCell('H'.$i)->getCalculatedValue().",
							fk_contratos = ".$datos['contrato'].",
							fk_dependencias = ".$hojaActual->getCell('I'.$i)->getCalculatedValue().",
							creado_por = ".$_SESSION['usuario']['id'].",
							fecha_creacion = NOW()";
				$resultado = $db->ejecutarConsulta($sql, false);
				if($resultado['ejecuto']){
					$correctos++;
				}else{
					$fallidos++;
				}
			}else{
				break;
			}
		}
		$db->close();
		if($resultado['ejecuto']){
			return [
				'ejecuto' => true,
				'mensajeError' => 'Registros correctos: '.$correctos.', Registros fallidos: '.$fallidos
			];
		}else{
			return [
				'ejecuto' => false,
				'mensajeError' => 'Error cargando datos'
			];
		}
	}
}