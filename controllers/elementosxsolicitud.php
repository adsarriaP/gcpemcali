<?php
require_once "libs/baseCrud.php";

class elementosxsolicitud extends baseCrud{
	protected $tabla = 'elementosxsolicitud';

	public function getBySolicitud($datos){
		$sql = "SELECT
					*
				FROM
					elementosxsolicitud
				WHERE
					fk_solicitudes = ".(int)$datos['fk_solicitudes'];
		$db = new database();
       	return $db->ejecutarConsulta($sql);
	}

	public function updateBySolicitud($datos){
		$db = new database();
		$sql = "UPDATE elementosxsolicitud SET ";
		foreach ($datos['info'] as $key => $value) {
			$sql .= "$key = '".$db->real_escape_string($value)."',";
		}
		$sql .= "modificado_por = ".(int)$_SESSION['usuario']['id'].", fecha_modificacion=NOW()";
		$sql .= " WHERE fk_solicitudes = ".(int)$datos['fk_solicitudes'];
		return $db->ejecutarConsulta($sql);
	}

	public function deleteBySolicitud($datos){
		$db = new database();
		$sql = "DELETE FROM elementosxsolicitud WHERE fk_solicitudes = ".(int)$datos['fk_solicitudes'];
		return $db->ejecutarConsulta($sql);
	}
}
