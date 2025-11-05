<?php
require_once "libs/baseCrud.php";

class logIngresos extends baseCrud{
	protected $tabla = 'log_ingresos';

	public function ingresosDia($datos){
		$sql = "SELECT
					DAY(log.fecha_creacion) AS dia,
					COUNT(1) AS cantidad
				FROM 
					log_ingresos log
				WHERE
					DATE_FORMAT(log.fecha_creacion, '%Y%m') = $datos[vigencia]
				GROUP BY
					dia
				ORDER BY
					dia";
		$db = new database();
       	return $db->ejecutarConsulta($sql);	
	}

	public function ingresosDiferentesDia($datos){
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
							DATE_FORMAT(log.fecha_creacion, '%Y%m') = $datos[vigencia]) AS tabla
				GROUP BY
					dia
				ORDER BY
					dia";
		$db = new database();
       	return $db->ejecutarConsulta($sql);	
	}
}