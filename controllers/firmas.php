<?php

class firmas{
	public function guardar($datos){
		if (isset($datos['firma'])) {
    		$data = $datos['firma'];

    		// Eliminar el prefijo 'data:image/png;base64,' del base64
    		$data = str_replace('data:image/png;base64,', '', $data);
    		$data = str_replace(' ', '+', $data);

    		// Decodificar el base64 a binario
    		$fileData = base64_decode($data);

    		// Guardar la imagen
    		$filePath = 'firmas/'.$datos['id'].'.png';
    		// Guardar la imagen en el directorio especificado
    		if (file_put_contents($filePath, $fileData)) {
    			return [
					'ejecuto' => true,
					'mensaje' => 'Se guardo correctamente'
				];
    		}else{
    			return [
					'ejecuto' => false,
					'mensajeError' => 'Error al guardar la firma'
				];
    		}
		}else{
			return [
					'ejecuto' => false,
					'mensajeError' => 'No se recibió ninguna firma'
				];
		}
	}
}