<?php
require_once "libs/baseCrud.php";

class olxPublicaciones extends baseCrud{
	protected $tabla = 'olx_publicaciones';

	public function subirFoto($datos){
		//$filename = $_FILES['file']['name'];
		if(move_uploaded_file($_FILES['file']['tmp_name'],'fotos/foto_'.$datos['id'].'.jpg')){
			return [
				'ejecuto' => true,
				'msg' => 'La foto se cargo correctamente'
			];
		}else{
			return [
				'ejecuto' => false,
				'msg' => 'Error al cargar la foto'
			];
		}
	}
}