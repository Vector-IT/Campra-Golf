<?php
	session_start();
	
	if (!isset($_SESSION['is_logged_in'])) {
		header("Location:login.php");
		die();
	}
	
	include("conexion.php");
	require("phpmailer/PHPMailerAutoload.php");
	
	$operacion = $_POST["operacion"];
	
	if (isset($_POST["NumeItin"]))
		$NumeItin = $_POST["NumeItin"];
	
	if (isset($_POST["NumeTour"]))
		$NumeTour = $_POST["NumeTour"];
	
	if (isset($_POST["NombItin"]))
		$NombItin = str_replace("'", "\'", $_POST["NombItin"]);
	
	if (isset($_POST["NombDia"]))
		$NombDia = explode("@#@", $_POST["NombDia"]);
	
	if (isset($_POST["DescDia"]))
		$DescDia = explode("@#@", $_POST["DescDia"]);
	
	$conn = new mysqli($dbhost, $dbuser, $dbpass, $db) or die("Problemas al conectar con la BD");

	switch ($operacion) {
		case 0: //INSERT
			$strSQL = "SELECT COALESCE(MAX(NumeItin), 0) + 1 NumeItin FROM itinerarios";
			$tabla = $conn->query($strSQL);
			$fila = $tabla->fetch_array();

			//INSERT
			$strSQL = "INSERT INTO itinerarios(NumeItin, NumeTour, NombItin)";
			$strSQL.= " VALUES({$fila["NumeItin"]}, {$NumeTour}, '{$NombItin}')";

			if (!$conn->query($strSQL))
				echo "Error al crear itinerario:<br />(" . $conn->errno . ") " . $conn->error . "<br />" . $strSQL;
			else {
				$error = "";
				
				for ($I = 0; $I < count($NombDia); $I++) {
					$strSQL = "INSERT INTO itinerariosdetalles(NumeItin, NumeDia, NombDia, DescDia)";
					$strSQL.= "VALUES({$fila["NumeItin"]}, ". ($I + 1) . ", '{$NombDia[$I]}', '{$DescDia[$I]}')";
					
					if (!$conn->query($strSQL)) {
						$error = "Error al crear itinerario:<br />(" . $conn->errno . ") " . $conn->error . "<br />" . $strSQL;
						break;
					}
				}
				
				if ($error == "")				
					echo "Itinerario Creado!<br>";
				else
					echo $error;
			}

			$tabla->free();
			break;

		case 1: //UPDATE
			$strSQL = "UPDATE itinerarios";
			$strSQL.= " SET NombItin = '{$NombItin}'";
			$strSQL.= " WHERE NumeItin = " . $NumeItin;

			if (!$conn->query($strSQL))
				echo "Error al modificar itinerario:<br />(" . $conn->errno . ") " . $conn->error . "<br />";
			else {
				$strSQL = "DELETE FROM itinerariosdetalles WHERE NumeItin = " . $NumeItin;
				
				if (!$conn->query($strSQL))
					echo "Error al modificar itineriario:<br />(" . $conn->errno . ") " . $conn->error . "<br />" . $strSQL;
				else {
					$error = "";
					
					for ($I = 0; $I < count($NombDia); $I++) {
						$strSQL = "INSERT INTO itinerariosdetalles(NumeItin, NumeDia, NombDia, DescDia)";
						$strSQL.= "VALUES({$NumeItin}, ". ($I + 1) . ", '{$NombDia[$I]}', '{$DescDia[$I]}')";
							
						if (!$conn->query($strSQL)) {
							$error = "Error al crear itinerario:<br />(" . $conn->errno . ") " . $conn->error . "<br />" . $strSQL;
							break;
						}
					}
					
					if ($error == "")
						echo "Itinerario Modificado!<br>";
					else
						echo $error;
				}
			}
			
			break;

		case 2: //DELETE
			$strSQL = "DELETE FROM itinerariosdetalles WHERE NumeItin = " . $NumeItin;
			
			if (!$conn->query($strSQL))
				echo "Error al borrar itineriario:<br />(" . $conn->errno . ") " . $conn->error . "<br />" . $strSQL;
			else {
				$strSQL = "DELETE FROM itinerarios WHERE NumeItin = " . $NumeItin;
				if (!$conn->query($strSQL))
					echo "Error al borrar itineriario:<br />(" . $conn->errno . ") " . $conn->error . "<br />" . $strSQL;
				else
					echo "Itinerario borrado!";
			}
		break;

		case 3: //Enviar por correo
			$para = $_POST["NombMail"];
				
			$titulo = "Envío de Itinerario - Iconn Travel";
			$mensaje = $_POST["mensajeHTML"];
			
			$url = 'http://'. $_SERVER['HTTP_HOST'].'/admin/php/enviarMail.php';
			$fields = array(
					'para1' => $para,
					'asunto' => $titulo,
					'mensaje' => $mensaje,
					'mensajeAlt' => "Si no puede visualizar este mensaje ingrese a nuestra Web http://iconntravel.com.mx"
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
			$strSQL = "SELECT NumeItin, NombItin";
			$strSQL.= " FROM itinerarios";
			$strSQL.= " WHERE NumeTour = " . $NumeTour;
			$strSQL.= " ORDER BY NumeItin";

			$tabla = cargarTabla($strSQL);

			$salida = '';

			if (mysqli_num_rows($tabla) > 0) {
				$salida.= $crlf.'<table class="table table-striped table-condensed">';
				$salida.= $crlf.'<tr>';
				$salida.= $crlf.'<th>Nombre</th>';
				$salida.= $crlf.'<th>Cant. de d&iacute;as</th>';
				$salida.= $crlf.'<th></th>';
				$salida.= $crlf.'<th></th>';
				$salida.= $crlf.'</tr>';
								 
	    		while ($fila = $tabla->fetch_array()) {
					$strSQL = "SELECT NombDia, DescDia FROM itinerariosdetalles WHERE NumeItin = {$fila["NumeItin"]} ORDER BY NumeDia";
					$tabla2 = cargarTabla($strSQL);
					
					$cantDias = mysqli_num_rows($tabla2);
					$nombDias = "";
					$descDias = "";
					
					while ($fila2 = $tabla2->fetch_array()) {
						if ($nombDias != "")
							$nombDias.= "@#@";
						
						$nombDias.= $fila2["NombDia"];
						
						if ($descDias != "")
							$descDias.= "@#@";
						
						$descDias.= $fila2["DescDia"];
					}
					if (isset($tabla2))
						$tabla2->free();
	    			
	    			$salida.= $crlf.'<tr>';
	    					 
					//Nombre
					$salida.= $crlf.'<td id="NombItin'.$fila["NumeItin"].'">'.$fila["NombItin"].'</td>';
					
					//Cantidad de dias
					$salida.= $crlf.'<td id="CantDias'.$fila["NumeItin"].'">'.$cantDias.'</td>';
					//Editar
					$salida.= $crlf.'<td style="text-align: center;"><input type="button" value="Editar" onclick="editar(\''.$fila["NumeItin"].'\')" class="btn btn-info" />';
					$salida.= $crlf.'<input type="hidden" id="NombDias'.$fila["NumeItin"].'" value="'.$nombDias.'" />';
					$salida.= $crlf.'<input type="hidden" id="DescDias'.$fila["NumeItin"].'" value="'.$descDias.'" />';
					$salida.= $crlf.'</td>';
					//Borrar
					$salida.= $crlf.'<td style="text-align: center;"><input type="button" value="Borrar" onclick="borrar(\''.$fila["NumeItin"].'\')" class="btn btn-danger" /></td>';
					
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
	
	$conn->close();

?>