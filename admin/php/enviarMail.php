<?php
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		
		if (isset($_POST['cantDest'])) {
			$cantDest = $_POST['cantDest'];
		}
		else {
			$cantDest = 1;
		}
	
		$titulo = $_POST['asunto'];
		$mensaje = $_POST['mensaje'];
		$mensajeAlt = (isset($_POST['mensajeAlt'])? $_POST['mensajeAlt']: '');
		
		if (isset($_POST['cco']))
			$cco = $_POST['cco'];
		
		if (isset($_POST['adjunto']))
			$adjunto = $_POST['adjunto'];
				
		require("phpmailer/PHPMailerAutoload.php");
		$mail = new PHPMailer;
		
		$mail->CharSet = 'UTF-8';
		$mail->setLanguage('es', 'language');
		$mail->isSMTP();                                      // Set mailer to use SMTP

		$mail->Host = 'smtp.gmail.com';
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = 'tls';
		$mail->Port = 587;
		$mail->Username = 'info@campragolf.com';
		$mail->Password = 'Montevideo635';
		$mail->setFrom('info@campragolf.com', 'Campra Golf');
		$mail->addReplyTo("info@campragolf.com","Campra Golf");
		
		for ($I = 1; $I <= $cantDest; $I++) {
			//${'para'.$I} = $_POST['para'.$I];
			$mail->addAddress($_POST['para'.$I]);
		}
		
		//$mail->addAddress($para);  // Add a recipient
		
		if (isset($cco))
			$mail->addBCC($cco);
		
		if (isset($adjunto))
			$mail->addAttachment($adjunto);

		$mail->isHTML(true);                                  // Set email format to HTML
		
		$mail->Subject = $titulo;
		$mail->Body    = $mensaje;
		$mail->AltBody = $mensajeAlt;
		
		if($mail->send()) {
			echo "Exito";
		} 
		else {
			echo "Error<br>" . $mail->ErrorInfo;
		}
	} 
	else {
		header('HTTP/1.1 403 Forbidden');
		header('Status: 403 Forbidden');
	}	
?>
