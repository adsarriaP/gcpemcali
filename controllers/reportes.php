<?php
require_once "libs/database.php";

class reportes{
	public function reportesObsoletosPendienteAlmacen($datos){
		$sql = "SELECT
					sol.id,
					ele.codigo,
					ele.descripcion,
					ele.serie,
					dep.gerencia,
					dep.dependencia,
					dep.unidad,
					usu.nombre,
					usu.registro,
					usu.correo,
					usu.telefono,
					pla.nombre AS planta
				FROM
					(solicitudes sol INNER JOIN ((usuarios usu INNER JOIN dependencias dep ON usu.fk_dependencias = dep.id) INNER JOIN plantas pla ON usu.fk_plantas = pla.id) ON sol.solicitante = usu.id) INNER JOIN elementos ele ON sol.fk_elementos = ele.id
				WHERE
					sol.fk_tramites = 4
					AND ele.devolver = 1
					AND sol.estado = 6";
		$db = new database();
       	return $db->ejecutarConsulta($sql);
	}

	public function reportesObsoletosEntregadosAlmacen($datos){
		$sql = "SELECT
					sol.id,
					ele.codigo,
					ele.descripcion,
					ele.serie,
					dep.gerencia,
					dep.dependencia,
					dep.unidad,
					usu.nombre,
					usu.registro,
					usu.correo,
					usu.telefono,
					pla.nombre AS planta,
					sol.estado
				FROM
					(solicitudes sol INNER JOIN ((usuarios usu INNER JOIN dependencias dep ON usu.fk_dependencias = dep.id) INNER JOIN plantas pla ON usu.fk_plantas = pla.id) ON sol.solicitante = usu.id) INNER JOIN elementos ele ON sol.fk_elementos = ele.id
				WHERE
					sol.fk_tramites = 4
					AND ele.devolver = 1
					AND sol.estado IN (7,8,10)";
		$db = new database();
       	return $db->ejecutarConsulta($sql);
	}

	public function reportesObsoletosSinSolicitud($datos){
		$sql = "SELECT
					ele.codigo,
					ele.descripcion,
					ele.serie,
					dep.gerencia,
					dep.dependencia,
					dep.unidad,
					usu.nombre,
					usu.registro,
					usu.correo,
					usu.telefono,
					pla.nombre AS planta
				FROM
					elementos ele INNER JOIN ((usuarios usu INNER JOIN plantas pla ON usu.fk_plantas = pla.id) INNER JOIN dependencias dep ON usu.fk_dependencias = dep.id) ON ele.responsable = usu.id
				WHERE
					ele.responsable != 1
					AND ele.en_tramite = 0
					AND ele.devolver = 1";
		$db = new database();
       	return $db->ejecutarConsulta($sql);
	}
}