<?php
require_once "libs/baseCrud.php";
require_once "usuarios.php";
require_once "elementos.php";
require_once "solicitudesHistorico.php";

class solicitudes extends baseCrud{
	protected $tabla = 'solicitudes';

	public function crear($datos){
		//primero verifico que ese elemento no este en trámite, especialmente para que no genere duplicados
		$objElementos = new elementos();
		$resultado = $objElementos->select([
			'info' => [
				'id' => $datos['fk_elementos']
			]
		]);		
		if($resultado['data'][0]['en_tramite'] == 1){
			return [
				'ejecuto' => false,
				'mensajeError' => 'El elemento ya tiene una solicitud en proceso'
			];
		}

		$datos['estado'] = 1;
		if($datos['fk_tramites'] == 4){
			$datos['estado'] = 3;
		}

		//Completar solicitante, esto pensando en hacer a nombre de
		$datos['solicitante'] = $_SESSION['usuario']['id'];

		//Incluir fecha de modificación para que inicie el conteo
		$datos['fecha_modificacion'] = date("Y-m-d H:i:s");

		$resultado = parent::insert([
			'info' => $datos
		]);
				
		//Guardo historico y poner en tramite el activo
		if($resultado['ejecuto']){
			$info = [
				'info'=>[
					'fk_solicitudes' => $resultado['insertId'],
					'informacion' => json_encode($datos)
				]
			];
			$objHistorico = new solicitudesHistorico();
			$respuesta = $objHistorico->insert($info);
			if($respuesta['ejecuto']){
				//Cambio el estado de tramite del activo
				$respuesta = $objElementos->update([
					'info' => [
						'en_tramite' => 1
					],
					'id' => $datos['fk_elementos']
				]);
				if($respuesta['ejecuto']){
					return $resultado;
				}
			}
		}
	}

	public function getSolicitudes($datos){
		$filtro = 0;
		switch ($datos['criterio']) {
			case 'id':
				$filtro = "sol.id = ".$datos['id'];
				break;
			case 'solicitante':
				$filtro = "sol.solicitante = ".$_SESSION['usuario']['id'];
				break;
			case 'receptor':
				$filtro = "sol.receptor = ".$_SESSION['usuario']['id'];
				break;
			case 'jefeGrabado':
				$filtro = "sol.jefe = ".$_SESSION['usuario']['id'];
				break;
			case 'superior':
				$filtro = "((ele.fk_dependencias IN ($datos[dependencia]) AND ele.responsable != ".$_SESSION['usuario']['id'].") OR ele.responsable IN ($datos[jefes]))";
				break;
			case 'jefe':
				//$filtro = "ele.fk_dependencias IN ($datos[dependencia]) AND ele.responsable != ".$_SESSION['usuario']['id'];
				$filtro = "ele.fk_dependencias IN ($datos[dependencia])";
				break;
			case 'inspeccionar':
				if($_SESSION['usuario']['rol'] == 'Administrador'){
					$filtro = 1;	
				}else{
					$filtro = "ele.fk_clases = ".$_SESSION['usuario']['clase'];
				}
				break;
			case 'todas':
				$filtro = 1;
				break;
			default:
				$filtro = 0;
				break;
		}
		if(isset($datos['estado'])){
			$filtro .= " AND sol.estado = ".$datos['estado'];
		}
		$sql = "SELECT
					sol.id,
					sol.fk_tramites AS tramite,
					sol.receptor AS idReceptor,
					ele.id AS idElemento,
					ele.fk_tipos AS tipo,
					ele.fk_clases AS clase,
					ele.codigo,
					ele.descripcion AS elemento,
					ele.serie,
					usu.nombre,
					usu.registro,
					pla.nombre AS planta,
					CONCAT('$',FORMAT(ele.valor,0,'es_CO')) AS valor,
					ele.fk_dependencias AS dependencia,
					DATEDIFF(NOW(),sol.fecha_modificacion) AS tiempo,
					sol.estado
				FROM
					(solicitudes sol INNER JOIN (usuarios usu INNER JOIN plantas pla ON usu.fk_plantas = pla.id) ON sol.solicitante = usu.id) INNER JOIN elementos ele ON sol.fk_elementos = ele.id
				WHERE					
					$filtro";
		$db = new database();
       	return $db->ejecutarConsulta($sql);
	}

	public function getQuienLoTiene($datos){
		$filtro = 0;
		switch ($datos['estado']) {
			case 1:
				$filtro = "usu.id = ".$datos['receptor'];
				break;
			case 2:
				$filtro = "usu.rol = 'Almacen'";
				break;
			case 4:
				$filtro = "usu.rol = 'Inspeccion' AND usu.clase = ".$datos['clase'];
				break;
			case 5:
				$filtro = "usu.rol IN ('Jefe','Validador') AND usu.clase = ".$datos['clase'];
				break;
			case 6:
				$filtro = "rol = 'Activos'";
				break;
			case 7:
				$filtro = "rol = 'Activos'";
				break;
			case 8:
				$filtro = "rol = 'Almacen'";
				break;
			default:
				$filtro = 0;
				break;
		}
		if($datos['estado'] == 3){
			$sql = "SELECT
						jef.nombre,
						jef.login,
						jef.telefono
					FROM
						dependencias dep INNER JOIN usuarios jef ON dep.jefe = jef.id
					WHERE
						dep.id = ".$datos['dependencia'];
		}else{
			$sql = "SELECT
						usu.nombre,
						usu.login,
						usu.telefono
					FROM
						usuarios usu
					WHERE
						$filtro";
		}
		$db = new database();
       	return $db->ejecutarConsulta($sql);
	}

	public function setEstado($datos){
		//Actualizo la solicitud
		$resultado = parent::update($datos);
		//Guardo historico
		if($resultado['ejecuto']){
			$info = [
				'info'=>[
					'fk_solicitudes'=>$datos['id'],
					'informacion'=>json_encode($datos['info'])
				]
			];
			$sh = new solicitudesHistorico();
			$historico = $sh->insert($info);
			if($historico['ejecuto']){				
				return $resultado;
			}
		}
	}

	public function asignar($datos){
		//Traigo la dependencia del receptor para pegarla al elemento
		$objUsuarios = new usuarios();
		$receptor = $objUsuarios->select([
			'info' => [
				'id' => $datos['receptor']
			]
		]);
		//Actualizo el elemento
		$objElementos = new elementos();
		$respuesta = $objElementos->update([
			'info' => [
				'fk_dependencias' => $receptor['data'][0]['fk_dependencias'],
				'responsable' => $datos['receptor'],
				'en_tramite' => 0
			],
			'id' => $datos['elemento']
		]);
		if($respuesta['ejecuto']){
			//Actualizo la solicitud
			return $this->setEstado([
				'info' => [
					'estado' => $datos['estado']
				],
				'id' => $datos['solicitud']
			]);
		}
	}

	public function cancelar($datos){
		//Actualizo el elemento
		$objElementos = new elementos();
		$respuesta = $objElementos->update([
			'info' => [				
				'en_tramite' => 0
			],
			'id' => $datos['elemento']
		]);
		if($respuesta['ejecuto']){
			//Actualizo la solicitud
			return $this->setEstado($datos);
		}
	}

	public function descargar($datos){
		//Actualizo el elemento		
		$objElementos = new elementos();
		$respuesta = $objElementos->update([
			'info' => [
				'fk_dependencias' => 1,
				'responsable' => 1,
				'en_tramite' => 0,
				'estado' => 'Reintegrado'
			],
			'id' => $datos['elemento']
		]);
		if($respuesta['ejecuto']){
			//Actualizo la solicitud
			return $this->setEstado($datos);
		}
	}

	public function getSolicitudAll($datos){
		$filtro = 0;
		switch ($datos['criterio']) {
			case 'solicitud':
				$filtro = "sol.id = ".$datos['id'];
				break;
			case 'elemento':
				$filtro = "ele.id = ".$datos['id'];
				break;
			case 'solicitante':
				$filtro = "slc.registro = ".$datos['id'];
				break;
			case 'receptor':
				$filtro = "rec.registro = ".$datos['id'];
				break;
			default:
				$filtro = 0;
				break;
		}
		$sql = "SELECT
					sol.id,
					sol.fk_tramites AS tramite,
					sol.receptor AS idReceptor,
					ele.id AS idElemento,
					ele.fk_tipos AS tipo,
					ele.fk_clases AS clase,
					ele.codigo,
					ele.descripcion AS elemento,
					ele.serie,
					ele.fk_dependencias AS dependencia,
					slc.id AS idSolicitante,
					slc.nombre AS solicitante,
					slc.registro AS solicitanteRegistro,
					slc.cedula AS solicitanteCedula,
					slc.telefono AS solicitanteTelefono,
					rec.nombre AS receptor,
					rec.registro AS receptorRegistro,
					jef.nombre AS jefe,
					sol.estado,
					sol.fecha_creacion
				FROM
					(((solicitudes sol INNER JOIN usuarios slc ON sol.solicitante = slc.id) 
						INNER JOIN usuarios jef ON sol.jefe = jef.id) 
						INNER JOIN usuarios rec ON sol.receptor = rec.id) 
						INNER JOIN elementos ele ON sol.fk_elementos = ele.id
				WHERE
					$filtro";
		$db = new database();
       	return $db->ejecutarConsulta($sql);
	}
}