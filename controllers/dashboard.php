<?php
require_once "libs/database.php";

class dashboard{
	//Principal
	// public function conteoTramite($datos){
	// 	$sql = "SELECT 
	// 				tra.nombre,
	// 				COUNT(1) AS cantidad
	// 			FROM
	// 				solicitudes sol INNER JOIN tramites tra ON sol.fk_tramites = tra.id
	// 			WHERE
	// 				DATE_FORMAT(sol.fecha_creacion, '%Y%m') = $datos[vigencia]
	// 				AND sol.id != 1
	// 			GROUP BY
	// 				tra.nombre";
	// 	$db = new database();
    //    	return $db->ejecutarConsulta($sql);
	// }
	
	public function conteoTramite($datos) {
		// Detectar si viene vigencia o rango de fechas
		if (isset($datos['vigencia'])) {
			// Modo vigencia (mes específico)
			$sql = "SELECT 
						tra.nombre,
						COUNT(1) AS cantidad
					FROM
						solicitudes sol 
						INNER JOIN tramites tra ON sol.fk_tramites = tra.id
					WHERE
						DATE_FORMAT(sol.fecha_creacion, '%Y%m') = '{$datos['vigencia']}'
						AND sol.id != 1
					GROUP BY
						tra.nombre
					ORDER BY cantidad DESC";
		} else {
			// Modo rango de fechas
			$fechaInicio = $datos['fechaInicio'] ?? null;
			$fechaFin = $datos['fechaFin'] ?? null;
			
			$sql = "SELECT 
						tra.nombre,
						COUNT(1) AS cantidad
					FROM
						solicitudes sol 
						INNER JOIN tramites tra ON sol.fk_tramites = tra.id
					WHERE
						sol.fecha_creacion BETWEEN '$fechaInicio' AND '$fechaFin 23:59:59'
						AND sol.id != 1
					GROUP BY
						tra.nombre
					ORDER BY cantidad DESC";
		}
		
		$db = new database();
		return $db->ejecutarConsulta($sql);
	}

	// public function conteoEstado($datos){
	// 	$sql = "SELECT 
	// 				est.nombre,
	// 				COUNT(1) AS cantidad
	// 			FROM
	// 				solicitudes sol INNER JOIN estados est ON sol.estado = est.id
	// 			WHERE
	// 				DATE_FORMAT(sol.fecha_creacion, '%Y%m') = $datos[vigencia]
	// 				AND sol.id != 1
	// 			GROUP BY
	// 				est.nombre";
	// 	$db = new database();
    //    	return $db->ejecutarConsulta($sql);
	// }

	public function conteoEstado($datos) {
		if (isset($datos['vigencia'])) {
			$sql = "SELECT 
						est.nombre,
						COUNT(1) AS cantidad
					FROM
						solicitudes sol 
						INNER JOIN estados est ON sol.estado = est.id
					WHERE
						DATE_FORMAT(sol.fecha_creacion, '%Y%m') = '{$datos['vigencia']}'
						AND sol.id != 1
					GROUP BY
						est.nombre
					ORDER BY cantidad DESC";
		} else {
			$fechaInicio = $datos['fechaInicio'] ?? null;
			$fechaFin = $datos['fechaFin'] ?? null;
			
			$sql = "SELECT 
						est.nombre,
						COUNT(1) AS cantidad
					FROM
						solicitudes sol 
						INNER JOIN estados est ON sol.estado = est.id
					WHERE
						sol.fecha_creacion BETWEEN '$fechaInicio' AND '$fechaFin 23:59:59'
						AND sol.id != 1
					GROUP BY
						est.nombre
					ORDER BY cantidad DESC";
		}
		
		$db = new database();
		return $db->ejecutarConsulta($sql);
	}

	// public function solicitudesDia($datos){
	// 	$sql = "SELECT
	// 				'Solicitudes' AS gerencia,
	// 				DAY(sol.fecha_creacion) AS dia,
	// 				COUNT(1) AS cantidad
	// 			FROM 
	// 				solicitudes sol
	// 			WHERE
	// 				DATE_FORMAT(sol.fecha_creacion, '%Y%m') = $datos[vigencia]
	// 				AND sol.id != 1
	// 			GROUP BY					
	// 				dia
	// 			ORDER BY
	// 				dia";
	// 	$db = new database();
    //    	return $db->ejecutarConsulta($sql);	
	// }

	public function solicitudesDia($datos) {
		if (isset($datos['vigencia'])) {
			// Modo vigencia: retorna DIA
			$sql = "SELECT
						'Solicitudes' AS gerencia,
						DAY(sol.fecha_creacion) AS dia,
						COUNT(1) AS cantidad
					FROM 
						solicitudes sol
					WHERE
						DATE_FORMAT(sol.fecha_creacion, '%Y%m') = '{$datos['vigencia']}'
						AND sol.id != 1
					GROUP BY					
						dia
					ORDER BY
						dia";
		} else {
			// Modo rango: retorna FECHA
			$fechaInicio = $datos['fechaInicio'] ?? null;
			$fechaFin = $datos['fechaFin'] ?? null;
			
			$sql = "SELECT
						'Solicitudes' AS gerencia,
						DATE(sol.fecha_creacion) AS fecha,
						COUNT(1) AS cantidad
					FROM 
						solicitudes sol
					WHERE
						sol.fecha_creacion BETWEEN '$fechaInicio' AND '$fechaFin 23:59:59'
						AND sol.id != 1
					GROUP BY					
						fecha
					ORDER BY
						fecha";
		}
		
		$db = new database();
		return $db->ejecutarConsulta($sql);
	}

	//Contratos
	public function getBase($datos){
		$sql = "SELECT
					dep.gerencia,
					dep.unidad,	
					sub.id AS idSubclase,
					sub.nombre AS subclase,
					ele.responsable,
					COUNT(1) AS cantidad
				FROM
					(elementos ele INNER JOIN dependencias dep ON ele.fk_dependencias = dep.id) INNER JOIn subclases sub ON ele.fk_subclases = sub.id
				WHERE
					ele.fk_contratos = $datos[contrato]
				GROUP BY
					gerencia,
					unidad,
					idSubclase,
					responsable
				ORDER BY
					gerencia";
		$db = new database();
       	return $db->ejecutarConsulta($sql);
	}

	//Equipos sin soporte
	public function equiposSinsoporte($datos){
		$sql = "SELECT
					gerencia,
					COUNT(1) As cantidad
				FROM
					elementos ele INNER JOIN (usuarios usu INNER JOIN dependencias dep ON usu.fk_dependencias = dep.id) ON ele.responsable = usu.id
				WHERE
					ele.responsable != 1
					AND ele.devolver = 1
				GROUP BY
					gerencia";
		$db = new database();
       	return $db->ejecutarConsulta($sql);
	}

	//Exportar todo
	public function exportarTodo($datos){
		$sql = "SELECT
					ele.id,
					ele.codigo,
					ele.sn,
					ele.fk_tipos,
					ele.fk_clases,
					ele.descripcion,
					ele.serie,
					ele.valor,
					usu.nombre AS trabajador,
					usu.registro,
					dep.gerencia,
					dep.dependencia,
					dep.unidad,
					pla.nombre AS planta
				FROM
					elementos ele INNER JOIN ((usuarios usu INNER JOIN plantas pla ON usu.fk_plantas = pla.id) INNER JOIN dependencias dep ON usu.fk_dependencias = dep.id) ON ele.responsable = usu.id
				WHERE
					ele.id != 1
				ORDER BY
					ele.id";
		$db = new database();
       	return $db->ejecutarConsulta($sql);
	}

	//Exportar solicitudes
	public function solicitudesExport($datos){
		// Determinar si se usa vigencia o rango de fechas
		if(isset($datos['fechaInicio']) && isset($datos['fechaFin'])){
			$condicionFecha = "DATE(sol.fecha_creacion) BETWEEN '{$datos['fechaInicio']}' AND '{$datos['fechaFin']} 23:59:59'";
		} else {
			$condicionFecha = "DATE_FORMAT(sol.fecha_creacion, '%Y%m') = '{$datos['vigencia']}'";
		}
		
		$sql = "SELECT
					sol.id,
					sol.fk_tramites,					
					ele.id AS codigoGCP,
					ele.fk_tipos,
					ele.fk_clases,
					ele.descripcion,
					ele.serie,					
					sol.estado,
					usu.nombre,
					usu.registro,
					sol.fecha_creacion
				FROM
					((solicitudes sol INNER JOIN usuarios usu ON sol.solicitante = usu.id) INNER JOIN elementos ele ON sol.fk_elementos = ele.id)
				WHERE
					$condicionFecha
				ORDER BY
					sol.id";
		$db = new database();
       	return $db->ejecutarConsulta($sql);
	}

	public function productividadExport($datos){
		// Determinar si se usa vigencia o rango de fechas
		if(isset($datos['fechaInicio']) && isset($datos['fechaFin'])){
			$condicionFecha = "DATE(sol.fecha_creacion) BETWEEN '{$datos['fechaInicio']}' AND '{$datos['fechaFin']} 23:59:59'";
		} else {
			$condicionFecha = "DATE_FORMAT(sol.fecha_creacion, '%Y%m') = '{$datos['vigencia']}'";
		}
		
		$sql = "SELECT
			base.*,
			historico.fk_solicitudes,
			historico.fechas AS transacciones,
			SUBSTRING_INDEX(historico.fechas, ';', 1) AS jefeaprobo,
			SUBSTRING_INDEX(SUBSTRING_INDEX(historico.fechas, ';', 2), ';', -1) AS jefeaprobofecha,
			SUBSTRING_INDEX(SUBSTRING_INDEX(historico.fechas, ';', 3), ';', -1) AS tecnicoaprobo,
			SUBSTRING_INDEX(SUBSTRING_INDEX(historico.fechas, ';', 4), ';', -1) AS tecnicoaprobofecha
		FROM
			(
				SELECT
					sol.id AS solicitud,
					sol.fecha_creacion,
					CASE
						WHEN ele.fk_tipos = 1 THEN 'Activo'
						WHEN ele.fk_tipos = 2 THEN 'Controlado'
						WHEN ele.fk_tipos = 3 THEN 'AO'
					END AS tipo,
					ele.codigo,
					ele.descripcion,
					ele.serie,
					usu.nombre,
					usu.registro,
					sol.estado,
					CASE
						WHEN sol.estado = 4 THEN 'Realizar inspección'
						WHEN sol.estado = 5 THEN 'Aprobar inspección'
						WHEN sol.estado = 6 THEN 'Llevar a almacen'
						WHEN sol.estado = 7 THEN 'Actualizar SAP'
						WHEN sol.estado = 8 THEN 'Actualizar carpeta'
						WHEN sol.estado = 9 THEN 'Reposición'
						WHEN sol.estado = 10 THEN 'Ejecutada'
					END AS estadoTexto
				FROM
					elementos ele
					INNER JOIN (
						solicitudes sol
						INNER JOIN usuarios usu ON sol.solicitante = usu.id
					) ON ele.id = sol.fk_elementos
				WHERE
					ele.fk_clases = 4
					AND sol.fk_tramites = 4
					AND sol.estado IN (4,5,6,7,8,9,10)
					AND $condicionFecha
			) AS base
			LEFT JOIN (
				SELECT
					sh.fk_solicitudes,
					GROUP_CONCAT(CONCAT(usu.nombre,';',sh.fecha_creacion) SEPARATOR ';') AS fechas
				FROM
					solicitudes_historico sh
					INNER JOIN usuarios usu ON sh.creado_por = usu.id
				WHERE
					JSON_EXTRACT(sh.informacion, '$.estado') IN ('4','5')
				GROUP BY
					sh.fk_solicitudes
				ORDER BY
					fk_solicitudes
			) AS historico
			ON base.solicitud = historico.fk_solicitudes
		ORDER BY base.solicitud";
		
		$db = new database();
       	return $db->ejecutarConsulta($sql);
	}

	// public function productividadExport($datos){
	// 	// Determinar si se usa vigencia o rango de fechas
	// 	if(isset($datos['fechaInicio']) && isset($datos['fechaFin'])){
	// 		$condicionFecha = "DATE(sol.fecha_creacion) BETWEEN '{$datos['fechaInicio']}' AND '{$datos['fechaFin']} 23:59:59'";
	// 	} else {
	// 		$condicionFecha = "DATE_FORMAT(sol.fecha_creacion, '%Y%m') = '{$datos['vigencia']}'";
	// 	}
		
	// 	$sql = "SELECT
	// 		base.*,
	// 		historico.fk_solicitudes,
	// 		historico.fechas AS transacciones,
	// 		SUBSTRING_INDEX(historico.fechas, ';', 1) AS jefeaprobo,
	// 		SUBSTRING_INDEX(SUBSTRING_INDEX(historico.fechas, ';', 2), ';', -1) AS jefeaprobofecha,
	// 		SUBSTRING_INDEX(SUBSTRING_INDEX(historico.fechas, ';', 3), ';', -1) AS tecnicoaprobo,
	// 		SUBSTRING_INDEX(SUBSTRING_INDEX(historico.fechas, ';', 4), ';', -1) AS tecnicoaprobofecha
	// 	FROM
	// 		(
	// 			SELECT
	// 				sol.id AS solicitud,
	// 				sol.fecha_creacion,
	// 				CASE
	// 					WHEN ele.fk_tipos = 1 THEN 'Activo'
	// 					WHEN ele.fk_tipos = 2 THEN 'Controlado'
	// 					WHEN ele.fk_tipos = 3 THEN 'AO'
	// 				END AS tipo,
	// 				ele.codigo,
	// 				ele.descripcion,
	// 				ele.serie,
	// 				usu.nombre,
	// 				usu.registro,
	// 				sol.estado,
	// 				CASE
	// 					WHEN sol.estado = 4 THEN 'Realizar inspección'
	// 					WHEN sol.estado = 5 THEN 'Aprobar inspección'
	// 					WHEN sol.estado = 6 THEN 'Llevar a almacen'
	// 					WHEN sol.estado = 7 THEN 'Actualizar SAP'
	// 					WHEN sol.estado = 8 THEN 'Actualizar carpeta'
	// 					WHEN sol.estado = 9 THEN 'Reposición'
	// 					WHEN sol.estado = 10 THEN 'Ejecutada'
	// 				END AS estadoTexto
	// 			FROM
	// 				elementos ele
	// 				INNER JOIN (
	// 					solicitudes sol
	// 					INNER JOIN usuarios usu ON sol.solicitante = usu.id
	// 				) ON ele.id = sol.fk_elementos
	// 			WHERE
	// 				ele.fk_clases = 4
	// 				AND sol.fk_tramites = 4
	// 				AND sol.estado IN (4,5,6,7,8,9,10)
	// 				AND $condicionFecha
	// 		) AS base
	// 		LEFT JOIN (
	// 			SELECT
	// 				sh.fk_solicitudes,
	// 				GROUP_CONCAT(CONCAT(usu.nombre,';',sh.fecha_creacion) SEPARATOR ';') AS fechas
	// 			FROM
	// 				solicitudes_historico sh
	// 				INNER JOIN usuarios usu ON sh.creado_por = usu.id
	// 			WHERE
	// 				JSON_UNQUOTE(sh.informacion->'$.estado') IN ('4','5')
	// 			GROUP BY
	// 				sh.fk_solicitudes
	// 			ORDER BY
	// 				fk_solicitudes
	// 		) AS historico
	// 		ON base.solicitud = historico.fk_solicitudes
	// 	ORDER BY base.solicitud";
		
	// 	$db = new database();
	// 	return $db->ejecutarConsulta($sql);
	// }
}