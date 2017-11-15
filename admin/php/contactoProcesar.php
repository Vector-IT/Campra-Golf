<?php
	session_start();
	include("conexion.php");
	
	$url = 'http://'. $_SERVER['HTTP_HOST'].'/admin/php/enviarMail.php';
	$fields = array(
			'para1' => $_POST['para1'],
			'asunto' => $_POST['asunto'],
			'mensaje' => $_POST['mensaje'],
			'mensajeAlt' => $_POST['mensajeAlt']
	);
	$datos = http_build_query($fields);
		
	//open connection
	$handle = curl_init();
	curl_setopt($handle, CURLOPT_URL, $url);
	curl_setopt($handle, CURLOPT_POST, true);
	curl_setopt($handle, CURLOPT_POSTFIELDS, $fields);
	curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
	
	//execute post
	$response = curl_exec($handle);
	if (!$response) {
		echo 'Error al enviar correo.<br>'.curl_error($handle);
	}
	else {
		$mensaje = '';
		$mensajeAlt = '';
		
		$mensaje.= 'Estimado(a),';
		$mensaje.= '<br>Agradecemos su preferencia y nos permitimos informarle que su mensaje fue recibido satisfactoriamente. En breve uno de nuestros asesores se pondrá en contacto con usted.';
		$mensaje.= '<br>Saludos cordiales.';
		$mensaje.= "<br>";
		$mensaje.= '<br><img src="http://iconntravel.com.mx/admin/'.buscarDato('SELECT Imagen FROM agencias WHERE NumeAgen = 1').'" style="width: 240px;height: auto;border-right: 1px solid #C99F37;padding-right: 10px;float: left;margin-right: 10px;" />';
		$mensaje.= '<span>';
		$mensaje.= '<strong>Iconn Travel</strong><br><br>';
		$mensaje.= '<a href="http://www.iconntravel.com.mx">www.iconntravel.com.mx</a><br><br>';
		$mensaje.= 'Tel. + 52 (55) 42 10 15 00';
		$mensaje.= '</span>';

		$mensajeAlt.= 'Estimado(a),';
		$mensajeAlt.= '\nAgradecemos su preferencia y nos permitimos informarle que su mensaje fue recibido satisfactoriamente. En breve uno de nuestros asesores se pondrá en contacto con usted.';
		$mensajeAlt.= '\nSaludos cordiales.';
		$mensajeAlt.= "\n";
		$mensajeAlt.= '\nIconn Travel';
		$mensajeAlt.= '\nhttp://www.iconntravel.com.mx';
		$mensajeAlt.= '\nTel. + 52 (55) 42 10 15 00';
		
		curl_close($handle);
		
		$fields = array(
				'para1' => $_POST['email'],
				'asunto' => '¡Hemos recibido su mensaje!',
				'mensaje' => $mensaje,
				'mensajeAlt' => $mensajeAlt
		);
		$datos = http_build_query($fields);
		
		//open connection
		$handle = curl_init();
		curl_setopt($handle, CURLOPT_URL, $url);
		curl_setopt($handle, CURLOPT_POST, true);
		curl_setopt($handle, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
		
		//execute post
		$response = curl_exec($handle);
		if (!$response) {
			echo 'Error al enviar correo.<br>'.curl_error($handle);
		}
		else
			echo "Exito";
		
	}
	//close connection
	curl_close($handle);
	
?>