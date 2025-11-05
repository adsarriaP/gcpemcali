<?php
require_once "libs/baseCrud.php";

class solicitudesHistorico extends baseCrud{
	protected $tabla = 'solicitudes_historico';

	public function getHistorico($datos){
		$sql = "SELECT
					usu.nombre,
					soh.informacion,
					soh.fecha_creacion
				FROM
					solicitudes_historico soh INNER JOIN usuarios usu ON soh.creado_por = usu.id
				WHERE
					soh.fk_solicitudes = $datos[solicitud]
				ORDER BY
					soh.fecha_creacion";
		$db = new database();
       	return $db->ejecutarConsulta($sql);
    }
}