<?php
	session_start();
	
	if (!isset($_SESSION['is_logged_in'])) {
		header("Location:login.php");
		die();
	}
	
	include("conexion.php");
	include("upload_file.php");
	require("phpmailer/PHPMailerAutoload.php");
	
	$operacion = $_POST["operacion"];
	
	if (isset($_POST["NumeFlyer"]))
		$NumeFlyer = $_POST["NumeFlyer"];
	
	if (isset($_POST["NumeTour"]))
		$NumeTour = $_POST["NumeTour"];
	
	if (isset($_POST["NombFlyer"]))
		$NombFlyer = str_replace("'", "\'", $_POST["NombFlyer"]);
	
	switch ($operacion) {
		case 0: //INSERT
			$NumeFlyer = buscarDato("SELECT COALESCE(MAX(NumeFlyer), 0) + 1 NumeFlyer FROM flyers");

			if (!empty($_FILES["Imagen"])) {
				$temp = explode(".", $_FILES["Imagen"]["name"]);
				$extension = end($temp);
			
				$archivo = $NumeFlyer . "." . $extension;
				$Imagen = "imgFlyers/" . $archivo;
					
				subir_archivo($_FILES["Imagen"], "../imgFlyers", $archivo);
			
				$strSQL = "INSERT INTO flyers(NumeFlyer, NombFlyer, Imagen, NumeTour)";
				$strSQL.= " VALUES({$NumeFlyer}, '{$NombFlyer}', '{$Imagen}', {$NumeTour})";
			
				$resultado = ejecutarCMD($strSQL);
				if (!$resultado)
					echo "Error al crear flyer:<br />" . $resultado;
				else
					echo "Flyer Creado!<br>";
			}
			else
				echo "Error! Falta imagen";

			break;

		case 1: //UPDATE
			if (!empty($_FILES["Imagen"])) {
				$Imagen = buscarDato("SELECT Imagen FROM flyers WHERE NumeFlyer = " . $NumeFlyer);
				
				unlink("../" . $Imagen);
				
				$temp = explode(".", $_FILES["Imagen"]["name"]);
				$extension = end($temp);

				$archivo = $NumeFlyer . "." . $extension;
				$Imagen = "imgFlyers/" . $archivo;
				 
				subir_archivo($_FILES["Imagen"], "../imgFlyers", $archivo);
			}				
			
			$strSQL = "UPDATE flyers";
			$strSQL.= " SET NombFlyer = '{$NombFlyer}'";
			$strSQL.= ", NumeTour = '{$NumeTour}'";
			
			if (!empty($_FILES["Imagen"]))
				$strSQL.= ", Imagen = '{$Imagen}'";
			
			$strSQL.= " WHERE NumeFlyer = " . $NumeFlyer;
			
			$resultado = ejecutarCMD($strSQL);
			if (!$resultado)
				echo "Error al modificar flyer:<br />" . $resultado;
			else
				echo "Flyer modificado!";
			
			break;

		case 2: //DELETE
			$Imagen = buscarDato("SELECT Imagen FROM flyers WHERE NumeFlyer = " . $NumeFlyer);
			
			unlink("../" . $Imagen);
			
			if (isset($tabla))
				$tabla->free();
			
			$strSQL = "DELETE FROM flyers WHERE NumeFlyer = {$NumeFlyer}";
			
			$resultado = ejecutarCMD($strSQL);
			
			if (!$resultado)
				echo "Error al borrar flyer:<br />" . $resultado;
			else
				echo "Flyer borrado!";

			break;
			
		case 3: //Enviar por correo
			$Imagen = "../temp/". $_SESSION["NumeAgen"] .' - '. $NumeFlyer .'.png';
			
			$para = $_POST["NombMail"];
			
			$titulo = "Envío de Flyer - Iconn Travel";
			
			$mensajeHtml = "Este es un mensaje autom&aacute;tico. Por favor no lo responda.";
			$mensajeHtml.= "<br><br>";
			$mensajeHtml.= "Enviamos como adjunto la imagen de flyer solicitada.";
			$mensajeHtml.= "<br><br>";
			$mensajeHtml.= "Muchas gracias.";
			
			$mensaje = "Este es un mensaje automático. Por favor no lo responda.";
			$mensaje.= "\n\n";
			$mensaje.= "Enviamos como adjunto la imagen de flyer solicitada.";
			$mensaje.= "\n\n";
			$mensaje.= $crlf."Muchas gracias.";
			
			$url = 'http://'. $_SERVER['HTTP_HOST'].'/admin/php/enviarMail.php';
			$fields = array(
					'para1' => $para,
					'asunto' => $titulo,
					'mensaje' => $mensajeHtml,
					'mensajeAlt' => $mensaje,
					'adjunto' => $Imagen
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
			//close connection
			curl_close($handle);
				
			if (strripos($response, "error") === false) {
				echo 'Correo enviado.';
			}
			else {
				echo $response;
			}
			break;

		case 10: //LISTAR
			$strSQL = "SELECT f.NumeFlyer, f.NombFlyer, f.Imagen, f.NumeTour, COALESCE(t.NombTour, 'TODOS') NombTour";
			$strSQL.= " FROM flyers f";
			$strSQL.= " LEFT JOIN tours t ON f.NumeTour = t.NumeTour";
			$strSQL.= " ORDER BY f.NumeFlyer";

			$tabla = cargarTabla($strSQL);

			$salida = '';

			if (mysqli_num_rows($tabla) > 0) {
				$salida.= $crlf.'<table class="table table-striped table-condensed">';
				$salida.= $crlf.'<tr>';
				$salida.= $crlf.'<th>Imagen</th>';
				$salida.= $crlf.'<th>Nombre</th>';
				$salida.= $crlf.'<th>Experiencia</th>';
				$salida.= $crlf.'<th></th>';
				$salida.= $crlf.'<th></th>';
				$salida.= $crlf.'</tr>';
								 
	    		while ($fila = $tabla->fetch_array()) {
	    			$salida.= $crlf.'<tr>';
	    					 
					//Imagen
					$salida.= $crlf.'<td>';
					$salida.= $crlf.'<img id="Imagen'.$fila["NumeFlyer"].'" src="'.$fila["Imagen"].'" style="width: 100px; height: auto;" />';
					$salida.= $crlf.'<input type="hidden" id="NumeTour'.$fila["NumeFlyer"].'" value="'. $fila["NumeTour"] .'" />';
					$salida.= $crlf.'</td>';
	    			//Nombre
	    			$salida.= $crlf.'<td id="NombFlyer'.$fila["NumeFlyer"].'">'.$fila["NombFlyer"].'</td>';
	    			//Tour
	    			$salida.= $crlf.'<td>'.$fila["NombTour"].'</td>';
					//Editar
					$salida.= $crlf.'<td style="text-align: center;"><input type="button" value="Editar" onclick="editar(\''.$fila[0].'\')" class="btn btn-info" /></td>';
					//Borrar
					$salida.= $crlf.'<td style="text-align: center;"><input type="button" value="Borrar" onclick="borrar(\''.$fila[0].'\')" class="btn btn-danger" /></td>';
					
					$salida.= $crlf.'</tr>';
				}
				
				$salida.= $crlf.'</table>';
	    	}
	    	else {
	    		$salida.= "<h3>Sin datos para mostrar</h3>";
	    	}
	    	$tabla->free();
	    	
	    	echo $salida;
	
	    	break;
	}
	
?>