<?php
	session_start();
	include("conexion.php");
	
	$operacion = $_POST["operacion"];
	
	if (isset($_POST["NumeUsua"]))
		$NumeUsua = $_POST["NumeUsua"];
	
	if (isset($_POST["NumeUsuaRefe"]))
		$NumeUsuaRefe = $_POST["NumeUsuaRefe"];
	else 
		if (isset($_SESSION["NumeUsua"]))
			$NumeUsuaRefe = $_SESSION["NumeUsua"];
		else
			$NumeUsuaRefe = 1;
	
	if (isset($_POST["NombComp"]))
		$NombComp = str_replace("'", "\'", $_POST["NombComp"]);

	if (isset($_POST["NombMail"]))
		$NombMail = trim(str_replace("'", "\'", $_POST["NombMail"]));
	
	if (isset($_POST["NombUsua"]))
		$NombUsua = trim(str_replace("'", "\'", $_POST["NombUsua"]));
	else 
		$NombUsua = '';
	
	if (isset($_POST["NombPass"]))
		$NombPass = str_replace("'", "\'", $_POST["NombPass"]);
	else 
		$NombPass = '';
	
	if (isset($_POST["NombPassNew"]))
		$NombPassNew = str_replace("'", "\'", $_POST["NombPassNew"]);
	else
		$NombPassNew = '';
	
	if (isset($_POST["TipoUsua"]))
		$TipoUsua = $_POST["TipoUsua"];
	
	if (isset($_POST["NumeEsta"]))
		$NumeEsta = $_POST["NumeEsta"];
	
	if (isset($_POST["NumeAgen"]))
		$NumeAgen = $_POST["NumeAgen"];
	
	if (isset($_POST["chkBanners"])) {
		$chkBanners = $_POST["chkBanners"];
	}
	else {
		$chkBanners = 0;
	}
	
	if (isset($_POST["chkAgencias"])) {
		$chkAgencias = $_POST["chkAgencias"];
	}
	else {
		$chkAgencias = 0;
	}

	if (isset($_POST["chkAgRegistros"])) {
		$chkAgRegistros = $_POST["chkAgRegistros"];
	}
	else {
		$chkAgRegistros = 0;
	}

	if (isset($_POST["chkExperiencias"])) {
		$chkExperiencias = $_POST["chkExperiencias"];
	}
	else {
		$chkExperiencias = 0;
	}

	if (isset($_POST["chkTours"])) {
		$chkTours = $_POST["chkTours"];
	}
	else {
		$chkTours = 0;
	}

	if (isset($_POST["chkBlog"])) {
		$chkBlog = $_POST["chkBlog"];
	}
	else {
		$chkBlog = 0;
	}

	if (isset($_POST["chkFlyers"])) {
		$chkFlyers = $_POST["chkFlyers"];
	}
	else {
		$chkFlyers = 0;
	}

	if (isset($_POST["chkCotizaciones"])) {
		$chkCotizaciones = $_POST["chkCotizaciones"];
	}
	else {
		$chkCotizaciones = 0;
	}

	if (isset($_POST["chkUsuarios"])) {
		$chkUsuarios = $_POST["chkUsuarios"];
	}
	else {
		$chkUsuarios = 0;
	}
	
	$conn = new mysqli($dbhost, $dbuser, $dbpass, $db) or die("Problemas al conectar con la BD");

	switch ($operacion) {
		case 0: //INSERT
			if ($TipoUsua != "4") {
				$usuario = buscarDato("SELECT NumeUsua FROM usuarios WHERE NombUsua = '{$NombUsua}'");
				if ($usuario != "") {
					echo "Error! Usuario ya registrado!";
					die();
				}
			}
/*
			$usuario = buscarDato("SELECT NumeUsua FROM usuarios WHERE NombMail = '{$NombMail}'");
			if ($usuario != "") {
				echo "Error! Mail ya registrado!";
				die();
			}
*/				
			$NumeUsua = buscarDato("SELECT COALESCE(MAX(NumeUsua), 0) + 1 NumeUsua FROM usuarios");

			//INSERT
			if (($TipoUsua == "3") || ($TipoUsua == "4"))
				$NumeEsta = "0";
			
			$strSQL = "INSERT INTO usuarios(NumeUsua, NombComp, NombMail, NombUsua, NombPass, TipoUsua, NumeEsta, FechAlta, NumeUsuaRefe, NumeAgen, chkBanners, chkAgencias, chkAgRegistros, chkExperiencias, chkTours, chkBlog, chkFlyers, chkCotizaciones, chkUsuarios)";
			$strSQL.= " VALUES({$NumeUsua}, '{$NombComp}', '{$NombMail}', '{$NombUsua}', '{$NombPass}', {$TipoUsua}, {$NumeEsta}, SYSDATE(), {$NumeUsuaRefe}, {$NumeAgen}, {$chkBanners}, {$chkAgencias}, {$chkAgRegistros}, {$chkExperiencias}, {$chkTours}, {$chkBlog}, {$chkFlyers}, {$chkCotizaciones}, {$chkUsuarios})";
			
			if ($NumeEsta == "0") {
				$para = $NombMail;
				
				$titulo = "Confirmar usuario";
				
				$mensajeHtml = "Este es un mensaje autom&aacute;tico. Por favor no lo responda.";
				$mensajeHtml.= "<br><br>";
				$mensajeHtml.= "Para confirmar su cuenta de usuario ingrese a este link.";
				$mensajeHtml.= '<br>http://www.iconntravel.com.mx/admin/php/confirmarUsuario.php?user='.$NumeUsua;
				$mensajeHtml.= "<br><br>";
				$mensajeHtml.= "Muchas gracias.";
				
				$mensaje = "Este es un mensaje automático. Por favor no lo responda.";
				$mensaje.= "\n\n";
				$mensaje.= "Para confirmar su cuenta de usuario ingrese a este link.";
				$mensaje.= '\nhttp://www.iconntravel.com.mx/admin/php/confirmarUsuario.php?user='.$NumeUsua;
				$mensaje.= "\n\n";
				$mensaje.= $crlf."Muchas gracias.";
				
				$url = 'http://'. $_SERVER['HTTP_HOST'].'/admin/php/enviarMail.php';
				$fields = array(
						'para1' => $para,
						'asunto' => $titulo,
						'mensaje' => $mensajeHtml,
						'mensajeAlt' => $mensaje
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
			}

			if (!$conn->query($strSQL))
				echo "Error al crear usuario";
			else 				
				echo "Usuario Creado!";

			break;

		case 1: //UPDATE
			$strSQL = "UPDATE usuarios";
			$strSQL.= " SET NombComp = '{$NombComp}'";
			$strSQL.= ", NombMail = '{$NombMail}'";
			$strSQL.= ", NombUsua = '{$NombUsua}'";
			if ($NombPass != "****")
				$strSQL.= ", NombPass = '{$NombPass}'";
			$strSQL.= ", TipoUsua = {$TipoUsua}";
			$strSQL.= ", NumeEsta = {$NumeEsta}";
			$strSQL.= ", NumeAgen = {$NumeAgen}";
			
			$strSQL.= ", chkBanners = {$chkBanners}";
			$strSQL.= ", chkAgencias = {$chkAgencias}";
			$strSQL.= ", chkAgRegistros = {$chkAgRegistros}";
			$strSQL.= ", chkExperiencias = {$chkExperiencias}";
			$strSQL.= ", chkTours = {$chkTours}";
			$strSQL.= ", chkBlog = {$chkBlog}";
			$strSQL.= ", chkFlyers = {$chkFlyers}";
			$strSQL.= ", chkCotizaciones = {$chkCotizaciones}";
			$strSQL.= ", chkUsuarios = {$chkUsuarios}";
			
			$strSQL.= " WHERE NumeUsua = " . $NumeUsua;

			if (!$conn->query($strSQL))
				echo "Error al modificar usuario:<br />(" . $conn->errno . ") " . $conn->error . "<br />";
			else 
				echo "Usuario Modificado!<br>";
			
			break;

		case 2: //DELETE
			$strSQL = "DELETE FROM usuarios WHERE NumeUsua = {$NumeUsua}";
			
			if (!$conn->query($strSQL))
				echo "Error al borrar usuario:<br />(" . $conn->errno . ") " . $conn->error . "<br />" . $strSQL;
			else
				echo "Usuario borrado!";

			break;
		
		case 3: //RECUPERAR CONTRASEÑA
			$para = $_POST['email'];
			$usuario = buscarDato("SELECT NumeUsua FROM usuarios WHERE NombMail = '{$para}'");
			
			if ($usuario != "") {
				$passNew = get_random_string("abcdefghijklmnopqrstuvwxyz1234567890", 5);
				$strSQL = "UPDATE usuarios SET NombPass = '{$passNew}' WHERE NumeUsua = {$usuario}";
				
				$titulo = "Recuperar Contraseña";
				$mensajeHtml = "Este es un mensaje autom&aacute;tico. Por favor no lo responda.";
				$mensajeHtml.= "<br>Usted solicit&oacute; restablecer la contrase&ntilde;a en IconnTravel.com.mx";
				$mensajeHtml.= "<br><br>";
				$mensajeHtml.= "Su nueva contrase&ntilde;a es <strong>{$passNew}</strong>";
				$mensajeHtml.= "<br><br>";
				$mensajeHtml.= $crlf."Muchas gracias.";
				
				$mensaje = "Este es un mensaje automatico.";
				$mensaje.= "\nUsted solicito restablecer la contrasena en IconnTravel.com.mx";
				$mensaje.= "\n\n";
				$mensaje.= "\nSu nueva contraseña es " . $passNew;
				$mensaje.= "\n\n";
				$mensaje.= "\nMuchas gracias.";
				
				$url = 'http://'. $_SERVER['HTTP_HOST'].'/admin/php/enviarMail.php';
				$fields = array(
						'para1' => $para,
						'asunto' => $titulo,
						'mensaje' => $mensajeHtml,
						'mensajeAlt' => $mensaje
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
					if ($conn->query($strSQL))
						echo "Exito";
					else
						echo "Error";
				}
				else {
					echo "Error";
				}
			}
			else
				echo "Error";
			break;
			
		case 4: //Modificar contraseña
			$aux = buscarDato("SELECT COUNT(*) FROM usuarios WHERE NumeUsua = {$NumeUsua} AND NombPass = '{$NombPass}'");
			
			if ($aux == 1) {
				$strSQL = "UPDATE usuarios";
				$strSQL.= " SET NombPass = '{$NombPassNew}'";
				$strSQL.= " WHERE NumeUsua = " . $NumeUsua;
	
				if ($conn->query($strSQL))
					echo "Exito";
				else 
					echo "Error";
			}
			else
				echo "Error";
			break;

		case 10: //LISTAR
			$strSQL = "SELECT u.NumeUsua, u.FechAlta, u.NombComp, u.NombMail, u.NombUsua, u.TipoUsua, u.NumeEsta, ur.NombComp NombCompRefe, u.NumeAgen, a.NombAgen,";
			$strSQL.= " u.chkBanners, u.chkAgencias, u.chkAgRegistros, u.chkExperiencias, u.chkTours, u.chkBlog, u.chkFlyers, u.chkCotizaciones, u.chkUsuarios";
			$strSQL.= " FROM usuarios u";
			$strSQL.= " LEFT JOIN usuarios ur ON u.NumeUsuaRefe = ur.NumeUsua";
			$strSQL.= " LEFT JOIN agencias a ON u.NumeAgen = a.NumeAgen";
			$strSQL.= " ORDER BY u.TipoUsua, u.NumeUsua";

			$tabla = cargarTabla($strSQL);

			$salida = '';

			if (mysqli_num_rows($tabla) > 0) {
				$salida.= $crlf.'<table class="table table-striped table-condensed">';
				$salida.= $crlf.'<tr>';
				$salida.= $crlf.'<th>N&uacute;mero</th>';
				$salida.= $crlf.'<th>Nombre</th>';
				$salida.= $crlf.'<th>Usuario</th>';
				$salida.= $crlf.'<th>Mail</th>';
				$salida.= $crlf.'<th>Tipo de usuario</th>';
				$salida.= $crlf.'<th>Agencia</th>';
				$salida.= $crlf.'<th>Fecha de registro</th>';
				$salida.= $crlf.'<th>Usuario que lo cre&oacute;</th>';
				$salida.= $crlf.'<th>Estado</th>';
				$salida.= $crlf.'<th></th>';
				$salida.= $crlf.'<th></th>';
				$salida.= $crlf.'</tr>';
								 
	    		while ($fila = $tabla->fetch_array()) {
	    			$salida.= $crlf.'<tr>';
	    					 
					//Numero
					$salida.= $crlf.'<td id="NumeUsua'.$fila["NumeUsua"].'">'.$fila["NumeUsua"];
					$salida.= $crlf.'<input type="hidden" id="TipoUsua'.$fila["NumeUsua"].'" value="'.$fila["TipoUsua"].'" />';
					$salida.= $crlf.'<input type="hidden" id="NumeEsta'.$fila["NumeUsua"].'" value="'.$fila["NumeEsta"].'" />';
					$salida.= $crlf.'<input type="hidden" id="NumeAgen'.$fila["NumeUsua"].'" value="'.$fila["NumeAgen"].'" />';
					$salida.= $crlf.'<input type="hidden" id="NombMail'.$fila["NumeUsua"].'" value="'.$fila["NombMail"].'" />';
					
					$salida.= $crlf.'<input type="hidden" id="chkBanners'.$fila["NumeUsua"].'" value="'.$fila["chkBanners"].'" />';
					$salida.= $crlf.'<input type="hidden" id="chkAgencias'.$fila["NumeUsua"].'" value="'.$fila["chkAgencias"].'" />';
					$salida.= $crlf.'<input type="hidden" id="chkAgRegistros'.$fila["NumeUsua"].'" value="'.$fila["chkAgRegistros"].'" />';
					$salida.= $crlf.'<input type="hidden" id="chkExperiencias'.$fila["NumeUsua"].'" value="'.$fila["chkExperiencias"].'" />';
					$salida.= $crlf.'<input type="hidden" id="chkTours'.$fila["NumeUsua"].'" value="'.$fila["chkTours"].'" />';
					$salida.= $crlf.'<input type="hidden" id="chkBlog'.$fila["NumeUsua"].'" value="'.$fila["chkBlog"].'" />';
					$salida.= $crlf.'<input type="hidden" id="chkFlyers'.$fila["NumeUsua"].'" value="'.$fila["chkFlyers"].'" />';
					$salida.= $crlf.'<input type="hidden" id="chkCotizaciones'.$fila["NumeUsua"].'" value="'.$fila["chkCotizaciones"].'" />';
					$salida.= $crlf.'<input type="hidden" id="chkUsuarios'.$fila["NumeUsua"].'" value="'.$fila["chkUsuarios"].'" />';
					
					$salida.= $crlf.'</td>';
					//Nombre
					$salida.= $crlf.'<td id="NombComp'.$fila["NumeUsua"].'">'.$fila["NombComp"].'</td>';
					//Usuario
					$salida.= $crlf.'<td id="NombUsua'.$fila["NumeUsua"].'">'.$fila["NombUsua"].'</td>';
					//Mail
					$salida.= $crlf.'<td>'.$fila["NombMail"].'</td>';
					//Tipo
					switch ($fila["TipoUsua"]) {
						case 1:
							$salida.= $crlf.'<td>Administrador</td>';
							break;
							
						case 2:
							$salida.= $crlf.'<td>Agencia de viajes</td>';
							break;
							
						case 3:
							$salida.= $crlf.'<td>Usuario de p&aacute;gina</td>';
							break;
							
						case 4:
							$salida.= $crlf.'<td>Usuario de newsletter</td>';
							break;
					}
					//Agencia
					$salida.= $crlf.'<td>'.$fila["NombAgen"].'</td>';
					//Fecha alta
					$salida.= $crlf.'<td>'.$fila["FechAlta"].'</td>';
					//Usuario referencia
					$salida.= $crlf.'<td>'.$fila["NombCompRefe"].'</td>';
					//Estado
					if ($fila["NumeEsta"] == 1)
						$salida.= $crlf.'<td>Activo</td>';
					else
						$salida.= $crlf.'<td>Inactivo</td>';
					
					//Editar
					$salida.= $crlf.'<td style="text-align: center;"><input type="button" value="Editar" onclick="editar(\''.$fila["NumeUsua"].'\')" class="btn btn-info" /></td>';
					//Borrar
					$salida.= $crlf.'<td style="text-align: center;"><input type="button" value="Borrar" onclick="borrar(\''.$fila["NumeUsua"].'\')" class="btn btn-danger" /></td>';
					
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