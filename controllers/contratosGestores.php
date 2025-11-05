<?php
require_once "libs/baseCrud.php";
require_once "usuarios.php";

class contratosGestores extends baseCrud{
	protected $tabla = 'contratos_gestores';

	public function getGestores($datos){
        $filtro = 0;
        switch ($datos['criterio']) {
            case 'id':
                $filtro = "cg.id = $datos[valor]";
                break;
            case 'contrato':
                $filtro = "cg.fk_contratos = $datos[valor]";
                break;
            case 'gestor':
                $filtro = "cg.gestor = $datos[valor]";
                break;
            default:
                $filtro = 0;
                break;
        }
        if(isset($datos['estado'])){
            $filtro .= " AND cg.estado = '$datos[estado]'";
        }
        $sql = "SELECT
                    con.id AS idContrato,
                    con.contrato,
        			cg.id,
                    ges.nombre,
                    ges.registro,
                    cg.rol,
                    cg.estado
                FROM
                    contratos con 
                        INNER JOIN (contratos_gestores cg 
                        INNER JOIN usuarios ges ON cg.gestor = ges.id) ON con.id = cg.fk_contratos
                WHERE                    
                    $filtro";
        $db = new database();
        return $db->ejecutarConsulta($sql);
    }

    public function guardarGestor($datos){        
        $resultado = parent::insert($datos);
        if($resultado['ejecuto']){
            //Cambio el estado de tramite del activo
            $objUsuarios = new usuarios();
            $respuesta = $objUsuarios->update([
                'info' => [
                    'rol' => 'GestorContrato'
                ],
                'id' => $datos['info']['gestor']
            ]);
            if($respuesta['ejecuto']){
                return $resultado;
            }
        }
    }
}