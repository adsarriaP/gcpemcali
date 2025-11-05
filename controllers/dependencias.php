<?php
require_once "libs/baseCrud.php";

class dependencias extends baseCrud{
	protected $tabla = 'dependencias';

	public function getDependencias($datos){
		$filtro = 1;
		if(isset($datos['id'])){
			$filtro = "dep.id = $datos[id]";
		}
		$sql = "SELECT
					dep.id,
					dep.gerencia,
					dep.dependencia,
					dep.unidad,
					usu.nombre AS jefe,
					usu.registro
				FROM
					dependencias dep INNER JOIN usuarios usu ON dep.jefe = usu.id
				WHERE
					$filtro
					AND dep.estado = 'Activo'";
		$db = new database();
       	return $db->ejecutarConsulta($sql);
	}

	public function getGerencias($datos){
		$sql = "SELECT
					dep.gerencia
				FROM
					dependencias dep
				WHERE
					dep.estado = 'Activo'
				GROUP BY
					dep.gerencia";
		$db = new database();
       	return $db->ejecutarConsulta($sql);
	}

	public function getDep($datos){
		$sql = "SELECT					
					dep.dependencia
				FROM
					dependencias dep
				WHERE
					dep.gerencia = '$datos[gerencia]'
					AND dep.estado = 'Activo'
				GROUP BY
					dep.dependencia";
		$db = new database();
       	return $db->ejecutarConsulta($sql);
	}

	public function getUnidades($datos){
		$sql = "SELECT
					dep.id,
					dep.unidad
				FROM
					dependencias dep
				WHERE
					dep.gerencia = '$datos[gerencia]'
					AND dep.dependencia = '$datos[dep]'
					AND dep.estado = 'Activo'";
		$db = new database();
       	return $db->ejecutarConsulta($sql);
	}

	public function getInferiores($datos){
		$sql = "SELECT
					GROUP_CONCAT(jefe ORDER BY jefe SEPARATOR ',') AS jefes
				FROM
					dependencias dep
				WHERE
					id IN ($datos[dependencias])";
		$db = new database();
       	return $db->ejecutarConsulta($sql);
	}
}