<?php
require_once "libs/baseCrud.php";

class elementosHistorico extends baseCrud{
	protected $tabla = 'elementos_historico';
	
	public function getHistorico($datos){
		$sql = "SELECT 
					eh.id,
					eh.fk_elementos,
					eh.informacion,
					eh.creado_por,
					eh.fecha_creacion,
					usu.nombre,
					usu.registro
				FROM 
					elementos_historico eh
					INNER JOIN usuarios usu ON usu.id = eh.creado_por
				WHERE 
					eh.fk_elementos = {$datos['fk_elementos']}
				ORDER BY 
					eh.fecha_creacion DESC";
		$db = new database();
		return $db->ejecutarConsulta($sql);
	}
}