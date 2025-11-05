<?php

class integracion {
	public function getElementos($datos){
		$url = "https://nexo.emcali.com.co:50003/RESTAdapter/ConsultaActivos";
		$usuario = "PODCERTIFICADOS";
		$password = "PODn@c5KB5MG";

		$data = [
    		"Num_Personal" => "121229"
		];		

		$ch = curl_init($url);
		
		// Configurar la autenticación Basic Auth
		curl_setopt($ch, CURLOPT_USERPWD, "$usuario:$password");
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

		// Configurar la petición como POST
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		// Configurar el body como JSON
		curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

		/*curl_setopt($curl, CURLOPT_URL, $apiUrl);
		//curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		//curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		//curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
		//curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);*/
		//curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		
		$response = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if (curl_errno($ch)){
			$response = '{"statusCode":500, "message":"'.curl_errno($ch).':'.curl_error($ch).'"}';
		}
		curl_close($ch);
		echo "Código de respuesta: $httpCode\n";
		echo "Respuesta del servidor: $response\n";
	}
}