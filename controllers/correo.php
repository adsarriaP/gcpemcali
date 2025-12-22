<?php
require_once "libs/database.php";
require_once "vendor/autoload.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class correo{
	public function sendMail($datos){
		//consulto los datos de la solicitud
		$sql = "SELECT
					ide.id,
					ide.titulo,
					usu.nombre,
					usu.registro,
					usu.correo,
					DATE_FORMAT(ide.fecha_creacion, '%d-%m-%Y') as fecha_creacion
				FROM
					ideas ide INNER JOIN usuarios usu ON ide.creado_por = usu.id
				WHERE
					ide.id = $datos[idea]";
		$db = new database();
       	$resultado = $db->ejecutarConsulta($sql);
       	if($resultado['ejecuto']){
       		$mail = new PHPMailer(true);
			try {
				$mail->isSMTP();
				$mail->Host = 'relay.emcali.com.co';
				$mail->Port = 25;
				//Recipients
				$mail->setFrom('no-replay@emcali.com.co', 'EmNOVA');
				$mail->addAddress($resultado['data'][0]['correo'], $resultado['data'][0]['nombre']);
				$mail->addCC('adsarria@emcali.com.co');
				//$mail->addCC($datos['jefe']['correo']);
				//Content
				$mail->isHTML(true);
				$mail->Subject = "Nueva idea código # I-".str_pad($datos['idea'], 3, "0", STR_PAD_LEFT);
				$mail->Body = "Hola, <b>".$resultado['data'][0]['nombre']."</b>
								<br>
								<br>
								Se registro correctamente su idea con titulo <b>".$resultado['data'][0]['titulo']."</b> el código asignado es <b># I-".str_pad($datos['idea'], 3, "0", STR_PAD_LEFT)."</b>.
								<br>
								<br>								
								<b>Fecha de registro:</b> ".$resultado['data'][0]['fecha_creacion']."
								<br>
								<b>Proponente:</b> ".$resultado['data'][0]['nombre']."
								</br>
								<b>Registro:</b> ".$resultado['data'][0]['registro']."
								</br>
								</br>
								Pronto sera contactado por el equipo de innovación para iniciar el proceso.";
				$mail->CharSet = 'UTF-8';
				$mail->send();
				return [
					'ejecuto' => true,
					'mensaje' => 'Correo enviado correctamente'
				];
			} catch (Exception $e) {
				return [
					'ejecuto' => false,
					'mensaje' => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"
				];
			}
       	}
	}

	public function enviarCorreoSolicitud($datos){
		// IMPORTANTE: Esta función NUNCA debe lanzar excepciones
		// Maneja todos los errores internamente para no afectar la creación de solicitudes
		
		try {
			// Crear una nueva conexión independiente para el correo
			$db = new database();
			
			// Consultar datos del receptor (sin cerrar la conexión)
			$sqlReceptor = "SELECT 
								usu.id,
								usu.nombre,
								usu.registro,
								usu.correo
							FROM 
								usuarios usu
							WHERE 
								usu.id = {$datos['receptor']}";
			
			$resultadoReceptor = $db->ejecutarConsulta($sqlReceptor, false); // false = no cerrar
		
		if(!$resultadoReceptor['ejecuto'] || empty($resultadoReceptor['data'])){
			$db->close(); // Cerrar manualmente antes de retornar
			return [
				'ejecuto' => false,
				'mensaje' => 'No se encontró el receptor'
			];
		}
		
		// Consultar datos de la solicitud y elemento (ahora SÍ cerramos)
		$sqlSolicitud = "SELECT
							sol.id,
							sol.fk_tramites,
							sol.fk_elementos,
							ele.codigo,
							ele.elemento,
							ele.inventario,
							ele.serie,
							tra.nombre as tramite,
							usu_sol.nombre as solicitante_nombre,
							usu_sol.registro as solicitante_registro,
							DATE_FORMAT(sol.fecha_creacion, '%d-%m-%Y %H:%i') as fecha_creacion
						FROM
							solicitudes sol
							INNER JOIN elementos ele ON sol.fk_elementos = ele.id
							INNER JOIN tramites tra ON sol.fk_tramites = tra.id
							INNER JOIN usuarios usu_sol ON sol.solicitante = usu_sol.id
						WHERE
							sol.id = {$datos['id_solicitud']}";
		
		$resultadoSolicitud = $db->ejecutarConsulta($sqlSolicitud, true); // true = cerrar
		
		if(!$resultadoSolicitud['ejecuto'] || empty($resultadoSolicitud['data'])){
			return [
				'ejecuto' => false,
				'mensaje' => 'No se encontró la solicitud'
			];
		}
		
		$receptor = $resultadoReceptor['data'][0];
		$solicitud = $resultadoSolicitud['data'][0];
		
		// Validar que el receptor tenga correo
		if(empty($receptor['correo'])){
			return [
				'ejecuto' => false,
				'mensaje' => 'El receptor no tiene correo registrado'
			];
		}
		
		// Enviar correo
		$mail = new PHPMailer(true);
		try {
			$mail->isSMTP();
			$mail->Host = 'relay.emcali.com.co';
			$mail->Port = 25;
			
			// Remitente
			$mail->setFrom('no-replay@emcali.com.co', 'GCP - Gestión y Control Patrimonial');
			
			// Destinatario
			$mail->addAddress($receptor['correo'], $receptor['nombre']);
			
			// Copia (opcional - puedes descomentar si necesitas)
			// $mail->addCC('adsarria@emcali.com.co');
			
			// Contenido
			$mail->isHTML(true);
			$mail->CharSet = 'UTF-8';
			$mail->Subject = "Nueva Solicitud #{$solicitud['id']} - {$solicitud['tramite']}";
			
			$mail->Body = "
				<div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
					<h2 style='color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px;'>
						Nueva Solicitud de {$solicitud['tramite']}
					</h2>
					
					<p>Hola, <b>{$receptor['nombre']}</b></p>
					
					<p>Se ha registrado una nueva solicitud en el sistema GCP que requiere tu atención:</p>
					
					<div style='background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;'>
						<h3 style='color: #007bff; margin-top: 0;'>Detalles de la Solicitud</h3>
						<table style='width: 100%; border-collapse: collapse;'>
							<tr>
								<td style='padding: 8px 0; font-weight: bold; width: 180px;'>Solicitud #:</td>
								<td style='padding: 8px 0;'>{$solicitud['id']}</td>
							</tr>
							<tr>
								<td style='padding: 8px 0; font-weight: bold;'>Tipo de Trámite:</td>
								<td style='padding: 8px 0;'>{$solicitud['tramite']}</td>
							</tr>
							<tr>
								<td style='padding: 8px 0; font-weight: bold;'>Fecha de Creación:</td>
								<td style='padding: 8px 0;'>{$solicitud['fecha_creacion']}</td>
							</tr>
						</table>
					</div>
					
					<div style='background-color: #e7f3ff; padding: 15px; border-radius: 5px; margin: 20px 0;'>
						<h3 style='color: #007bff; margin-top: 0;'>Información del Elemento</h3>
						<table style='width: 100%; border-collapse: collapse;'>
							<tr>
								<td style='padding: 8px 0; font-weight: bold; width: 180px;'>Código GCP:</td>
								<td style='padding: 8px 0;'>{$solicitud['codigo']}</td>
							</tr>
							<tr>
								<td style='padding: 8px 0; font-weight: bold;'>Elemento:</td>
								<td style='padding: 8px 0;'>{$solicitud['elemento']}</td>
							</tr>
							<tr>
								<td style='padding: 8px 0; font-weight: bold;'>Inventario:</td>
								<td style='padding: 8px 0;'>{$solicitud['inventario']}</td>
							</tr>
							<tr>
								<td style='padding: 8px 0; font-weight: bold;'>Serie:</td>
								<td style='padding: 8px 0;'>{$solicitud['serie']}</td>
							</tr>
						</table>
					</div>
					
					<div style='background-color: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0;'>
						<h3 style='color: #856404; margin-top: 0;'>Solicitante</h3>
						<p style='margin: 5px 0;'><b>Nombre:</b> {$solicitud['solicitante_nombre']}</p>
						<p style='margin: 5px 0;'><b>Registro:</b> {$solicitud['solicitante_registro']}</p>
					</div>
					
					<p style='margin-top: 30px;'>Por favor, ingresa al sistema GCP para revisar y gestionar esta solicitud.</p>
					
					<hr style='border: none; border-top: 1px solid #dee2e6; margin: 30px 0;'>
					
					<p style='font-size: 12px; color: #6c757d; text-align: center;'>
						Este es un correo automático, por favor no responder.<br>
						Sistema GCP - Gestión y Control Patrimonial EMCALI
					</p>
				</div>
			";
			
			$mail->send();
			
			return [
				'ejecuto' => true,
				'mensaje' => 'Correo enviado correctamente a ' . $receptor['correo']
			];
			
		} catch (Exception $e) {
			return [
				'ejecuto' => false,
				'mensaje' => "No se pudo enviar el correo. Error: {$mail->ErrorInfo}"
			];
		}
		
		} catch (Exception $e) {
			// Captura cualquier error general (BD, conexión, etc.)
			return [
				'ejecuto' => false,
				'mensaje' => "Error general al procesar el envío: {$e->getMessage()}"
			];
		}
	}

	public function pruebaUnitaria($datos){
		$mail = new PHPMailer(true);
		try {
			$mail->isSMTP();
			$mail->Host = 'relay.emcali.com.co';
			$mail->Port = 25;
			//Recipients
			$mail->setFrom('no-replay@emcali.com.co', 'ASIMATI');
			$mail->addAddress("vhhernandez@emcali.com.co", "Víctor Hugo Hernández");
			//Content
			$mail->isHTML(true);
			$mail->Subject = "Correo de prueba";
			$mail->Body = "Hola, <b>Víctor Hugo Hernández</b>
							<br>
							<br>
							Luego de revisar la información registrada en la solicitud";
			$mail->CharSet = 'UTF-8';
			$mail->send();
			return [
				'ejecuto' => true,
				'mensaje' => 'Correo enviado correctamente'
			];
		} catch (Exception $e) {
			return [
				'ejecuto' => false,
				'mensaje' => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"
			];
		}
	}

	public function pruebaSolicitud($datos){
		// Método de prueba para enviar correo de solicitud
		// Usar desde el navegador o Postman: correo/pruebaSolicitud
		// Parámetros: receptor (id del usuario), id_solicitud (id de la solicitud)
		
		if(!isset($datos['receptor']) || !isset($datos['id_solicitud'])){
			return [
				'ejecuto' => false,
				'mensaje' => 'Faltan parámetros: receptor e id_solicitud son requeridos'
			];
		}
		
		return $this->enviarCorreoSolicitud($datos);
	}
}