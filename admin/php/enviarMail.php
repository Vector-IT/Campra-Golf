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
		$mensajeAlt = $_POST['mensajeAlt'];
		
		if (isset($_POST['cco']))
			$cco = $_POST['cco'];
		
		if (isset($_POST['adjunto']))
			$adjunto = $_POST['adjunto'];
				
		require("phpmailer/PHPMailerAutoload.php");
		$mail = new PHPMailer;
		
		$mail->CharSet = 'UTF-8';
		$mail->setLanguage('es', 'language');
		$mail->isSMTP();                                      // Set mailer to use SMTP
		//$mail->Host = 'server.iconntravel.com.mx';            // Specify main and backup server
		$mail->Host = 'smtp.mailanyone.net';            // Specify main and backup server
		//$mail->Port = 25;                                    //Set the SMTP port number - 587 for authenticated TLS
		$mail->Port = 587;                                    //Set the SMTP port number - 587 for authenticated TLS
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		//$mail->Username = 'consultasweb@iconntravel.com.mx';  // SMTP username
		$mail->Username = 'consultasweb@iconnservices.com.mx';  // SMTP username
		//$mail->Password = 'Vectorit23';               		  // SMTP password
		$mail->Password = 'C0nsult@s-16';               		  // SMTP password
		//$mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted
		$mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted
		//$mail->setFrom('consultasweb@iconntravel.com.mx', 'Iconn Travel');     //Set who the message is to be sent from
		$mail->setFrom('consultasweb@iconnservices.com.mx', 'Iconn Travel');     //Set who the message is to be sent from
		
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
