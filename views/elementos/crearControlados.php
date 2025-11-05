<?php

require_once "libs/database.php";

$db = new database();
$sql = "SELECT
            *
        FROM
            elementospivote
        WHERE
            1";
$respuesta = $db->ejecutarConsulta($sql,false);

//print_r($respuesta);

$contador = 1;
for ($i = 0; $i < count($respuesta['data']); $i++) {
	for($j = 0; $j < $respuesta['data'][$i]['cantidad']; $j++){
		$sql = "INSERT INTO 
					elementos
				SET
					fk_tipos = 2,
					fk_clases = ".$respuesta['data'][$i]['fk_clases'].",
					clase_sap = ".$respuesta['data'][$i]['clase_sap'].",
					codigo = '".$respuesta['data'][$i]['codigo']."',
					descripcion =\"". $respuesta['data'][$i]['descripcion']."\",
					inventario = '".$respuesta['data'][$i]['inventario']."',
					serie = '".$respuesta['data'][$i]['serie']."',
					valor = ".$respuesta['data'][$i]['valor'].",
					responsable2 = ".$respuesta['data'][$i]['responsable2'].",
					creado_por=1,fecha_creacion=NOW()";
		$db->ejecutarConsulta($sql,false);
		echo $contador.") ".$sql."<br>";
		$contador++;
	}
}
?>