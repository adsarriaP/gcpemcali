<?php
require_once "libs/baseCrud.php";

class logIngresos extends baseCrud{
	protected $tabla = 'log_ingresos';

	// public function ingresosDia($datos){
	// 	$sql = "SELECT
	// 				DAY(log.fecha_creacion) AS dia,
	// 				COUNT(1) AS cantidad
	// 			FROM 
	// 				log_ingresos log
	// 			WHERE
	// 				DATE_FORMAT(log.fecha_creacion, '%Y%m') = $datos[vigencia]
	// 			GROUP BY
	// 				dia
	// 			ORDER BY
	// 				dia";
	// 	$db = new database();
    //    	return $db->ejecutarConsulta($sql);	
	// }

	// public function ingresosDiferentesDia($datos){
	// 	$sql = "SELECT 
	// 				tabla.dia,
	// 				COUNT(1) AS cantidad 
	// 			FROM (
	// 					SELECT
	// 						DISTINCT fk_usuarios,					
	// 						DAY(log.fecha_creacion) AS dia
	// 					FROM 
	// 						log_ingresos log
	// 					WHERE
	// 						DATE_FORMAT(log.fecha_creacion, '%Y%m') = $datos[vigencia]) AS tabla
	// 			GROUP BY
	// 				dia
	// 			ORDER BY
	// 				dia";
	// 	$db = new database();
    //    	return $db->ejecutarConsulta($sql);	
	// }

	public function ingresosDia($datos) {
		if (isset($datos['vigencia'])) {
			$sql = "SELECT
						DAY(log.fecha_creacion) AS dia,
						COUNT(1) AS cantidad
					FROM 
						log_ingresos log
					WHERE
						DATE_FORMAT(log.fecha_creacion, '%Y%m') = '{$datos['vigencia']}'
					GROUP BY
						dia
					ORDER BY
						dia";
		} else {
			$fechaInicio = $datos['fechaInicio'] ?? null;
			$fechaFin = $datos['fechaFin'] ?? null;
			
			$sql = "SELECT
						DATE(log.fecha_creacion) AS fecha,
						COUNT(1) AS cantidad
					FROM 
						log_ingresos log
					WHERE
						log.fecha_creacion BETWEEN '$fechaInicio' AND '$fechaFin 23:59:59'
					GROUP BY
						fecha
					ORDER BY
						fecha";
		}
		
		$db = new database();
		return $db->ejecutarConsulta($sql);
	}

	public function ingresosDiferentesDia($datos) {
		if (isset($datos['vigencia'])) {
			$sql = "SELECT 
						tabla.dia,
						COUNT(1) AS cantidad 
					FROM (
							SELECT
								DISTINCT fk_usuarios,					
								DAY(log.fecha_creacion) AS dia
							FROM 
								log_ingresos log
							WHERE
								DATE_FORMAT(log.fecha_creacion, '%Y%m') = '{$datos['vigencia']}') AS tabla
					GROUP BY
						dia
					ORDER BY
						dia";
		} else {
			$fechaInicio = $datos['fechaInicio'] ?? null;
			$fechaFin = $datos['fechaFin'] ?? null;
			
			$sql = "SELECT 
						tabla.fecha,
						COUNT(1) AS cantidad 
					FROM (
							SELECT
								DISTINCT fk_usuarios,					
								DATE(log.fecha_creacion) AS fecha
							FROM 
								log_ingresos log
							WHERE
								log.fecha_creacion BETWEEN '$fechaInicio' AND '$fechaFin 23:59:59') AS tabla
					GROUP BY
						fecha
					ORDER BY
						fecha";
		}
		
		$db = new database();
		return $db->ejecutarConsulta($sql);
	}
}