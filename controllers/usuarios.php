<?php
require_once "libs/baseCrud.php";
require_once "logIngresos.php";

class usuarios extends baseCrud{
	protected $tabla = 'usuarios';

	public function login($datos){
		if($datos['info']['password'] == ''){
			$datos['info']['password'] = 'xxxxx';
		}
		$autentica = 1;
		$filtro2 = [
			'info' => [
				'login' => $datos['info']['login'],
				'is_autentica' => 0
			]
		];
		$res = parent::select($filtro2);
		if(count($res['data']) == 1){
			$autentica = 0;
		}
		$usuario = [];
		$respuestaIDM = $this->getIDM($datos,$autentica);
		if($respuestaIDM->statusCode == 201){
			//Como fue autenticado entonces lo primero de verificar si ya esta
			$filtro['info']['login'] = $datos['info']['login'];
			$resultado = parent::select($filtro);
			if(count($resultado['data']) == 0) {
				$registro = 0;
				if($respuestaIDM->data->employeeType == 'O'){
					$registro = $respuestaIDM->data->employeeNumber;
					if($registro < 100000){
						$registro = $registro + 100000;
					}
				}else{
					$registro = $respuestaIDM->data->employeeID;
				}
				$db = new database();				
				$sql = "INSERT INTO
							usuarios
						SET
							tipo = '".$respuestaIDM->data->employeeType."',
							nombre = '".$respuestaIDM->data->displayName."',
							registro = $registro,
							cedula = ".$respuestaIDM->data->employeeID.",
							login = '".$datos['info']['login']."',
							correo = '".$datos['info']['login'].'@emcali.com.co'."',
							ultimo_acceso = '".date("Y-m-d H:i:s")."',
							creado_por = 1,
							fecha_creacion = NOW()";
       			$respuesta = $db->ejecutarConsulta($sql);
				if($respuesta['ejecuto']){
					$usuario = [
						'id' => $respuesta['insertId'],
						'rol' => 'Trabajador',
						'login' => $datos['info']['login'],
						'clase' => 1
					];
				}
			}else{
				$usuario = [
					'id' => $resultado['data'][0]['id'],
					'rol' => $resultado['data'][0]['rol'],
					'login' => $resultado['data'][0]['login'],
					'clase' => $resultado['data'][0]['clase']
				];
			}
			//Guardar variables en sesión
			$_SESSION['usuario'] = $usuario;

			//registrar fecha de utimo acceso
			$acceso = [
				'info' => [
					'ultimo_acceso' => date("Y-m-d H:i:s")
				],
				'id' => $usuario['id']
			];
			parent::update($acceso);

			//Guardar log de ingreso
			$objLog = new logIngresos();
			$respuesta = $objLog->insert([
				'info' => [
					'fk_usuarios' => $usuario['id']
				]
			]);
			if($respuesta['ejecuto']){
				return [
					'ejecuto' => true,
					'data' => $_SESSION
				];
			}
		}else{
			if($respuestaIDM->statusCode == 401){
				return [
					'ejecuto' => false,
					'mensajeError' => 'La contraseña no coincide con la Intranet'
				];
			}
			return [
				'ejecuto' => false,
				'mensajeError' => $respuestaIDM->message
			];
		}
	}

	private function getIDM($datos, $autentica){
		if($autentica == 0){
			return json_decode('{"statusCode":201}');
		}
		$headers = [
    		"Content-Type: application/json"
		];

		$params = json_encode([
    		'username'=>$datos['info']['login'],
    		'password'=>$datos['info']['password'],
    		'token'=>'9868b572-5e21-460b-93cf-4ffabba2e72d',
    		'attributes'=>'employeeNumber,employeeID,cn,employeeType,displayName'
		]);
		
		//$apiUrl = "http://172.18.32.117:5006/users/auth";
		$apiUrl = "https://serviciosapppdn.emcali.com.co:5006/users/auth";

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $apiUrl);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);		
		curl_setopt($curl, CURLOPT_TIMEOUT, 5);
		
		$response = curl_exec($curl);
		if (curl_errno($curl)){
			$response = '{"statusCode":500, "message":"'.curl_errno($curl).':'.curl_error($curl).'"}';
		}
		curl_close($curl);
		return json_decode($response);
	}

	public function getUsuario($datos){
		$sql = "SELECT
					usu.*,
					dep.gerencia,
					dep.dependencia,
					dep.unidad
				FROM
					usuarios usu INNER JOIN dependencias dep ON usu.fk_dependencias = dep.id
				WHERE";
		foreach ($datos['info'] as $key => $value) {
			$sql .= " usu.$key = '$value'";
		}
		$db = new database();
       	return $db->ejecutarConsulta($sql);
	}
}