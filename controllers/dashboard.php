<?php
require_once "libs/database.php";

class dashboard{
	//Principal
	public function conteoTramite($datos){
		$sql = "SELECT 
					tra.nombre,
					COUNT(1) AS cantidad
				FROM
					solicitudes sol INNER JOIN tramites tra ON sol.fk_tramites = tra.id
				WHERE
					DATE_FORMAT(sol.fecha_creacion, '%Y%m') = $datos[vigencia]
					AND sol.id != 1
				GROUP BY
					tra.nombre";
		$db = new database();
       	return $db->ejecutarConsulta($sql);
	}

	public function conteoEstado($datos){
		$sql = "SELECT 
					est.nombre,
					COUNT(1) AS cantidad
				FROM
					solicitudes sol INNER JOIN estados est ON sol.estado = est.id
				WHERE
					DATE_FORMAT(sol.fecha_creacion, '%Y%m') = $datos[vigencia]
					AND sol.id != 1
				GROUP BY
					est.nombre";
		$db = new database();
       	return $db->ejecutarConsulta($sql);
	}

	public function solicitudesDia($datos){
		$sql = "SELECT
					'Solicitudes' AS gerencia,
					DAY(sol.fecha_creacion) AS dia,
					COUNT(1) AS cantidad
				FROM 
					solicitudes sol
				WHERE
					DATE_FORMAT(sol.fecha_creacion, '%Y%m') = $datos[vigencia]
					AND sol.id != 1
				GROUP BY					
					dia
				ORDER BY
					dia";
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
					DATE_FORMAT(sol.fecha_creacion, '%Y%m') = $datos[vigencia]
				ORDER BY
					sol.id";
		$db = new database();
       	return $db->ejecutarConsulta($sql);
	}
}