<?php
	session_start();
/*	
	if (!isset($_SESSION['is_logged_in'])) {
		header("Location:login.php");
		die();
	}
*/	
	include("conexion.php");
	include("upload_file.php");
	
	$operacion = $_POST["operacion"];
	
	if (isset($_POST["NumeAgen"]))
		$NumeAgen = $_POST["NumeAgen"];
	
	if (isset($_POST["NombAgen"]))
		$NombAgen = str_replace("'", "\'", $_POST["NombAgen"]);
	
	if (isset($_POST["Dominio"]))
		$Dominio = str_replace(" ", "-", trim(str_replace("'", "\'", $_POST["Dominio"])));
	
	//Datos adicionales
	if (isset($_POST["Email"]))
		$Email = str_replace("'", "\'", $_POST["Email"]);
	else
		$Email = "";
	
	if (isset($_POST["Facebook"]))
		$Facebook = str_replace("'", "\'", $_POST["Facebook"]);
	else
		$Facebook = "";
	
	if (isset($_POST["Twitter"]))
		$Twitter = str_replace("'", "\'", $_POST["Twitter"]);
	else
		$Twitter = "";

	if (isset($_POST["Instagram"]))
		$Instagram = str_replace("'", "\'", $_POST["Instagram"]);
	else
		$Instagram = "";
	
	if (isset($_POST["Ocultar"])) {
		$Ocultar = $_POST["Ocultar"];
	}
	else {
		$Ocultar = 0;
	}
	
	if (isset($_POST["Posicion"]))
		$Posicion = str_replace("'", "\'", $_POST["Posicion"]);
	else
		$Posicion = "";
	
	if (isset($_POST["NumeAgenRegi"]))
		$NumeAgenRegi = $_POST["NumeAgenRegi"];
	
	if (isset($_POST["NombUsua"]))
		$User = str_replace("'", "\'", $_POST["NombUsua"]);
	
	if (isset($_POST["NombPass"]))
		$Pass = str_replace("'", "\'", $_POST["NombPass"]);
	
	//Datos agencias en sitio
	if (isset($_POST["NombComercial"]))
		$NombComercial = str_replace("'", "\'", $_POST["NombComercial"]);
	
	if (isset($_POST["Provincia"]))
		$Provincia = $_POST["Provincia"];
	
	if (isset($_POST["Direccion"]))
		$Direccion = str_replace("'", "\'", $_POST["Direccion"]);
	else 
		$Direccion = "";
	
	if (isset($_POST["Telefono"]))
		$Telefono = str_replace("'", "\'", $_POST["Telefono"]);
	else 
		$Telefono = "";
	
	if (isset($_POST["PaginaWeb"]))
		$PaginaWeb = str_replace("'", "\'", $_POST["PaginaWeb"]);
	
	if (isset($_POST["RazonSocial"]))
		$RazonSocial = str_replace("'", "\'", $_POST["RazonSocial"]);
	
	if (isset($_POST["IATA"]))
		$IATA = str_replace("'", "\'", $_POST["IATA"]);
	
	if (isset($_POST["SECTUR"]))
		$SECTUR = str_replace("'", "\'", $_POST["SECTUR"]);

	if (isset($_POST["RFC"]))
		$RFC = str_replace("'", "\'", $_POST["RFC"]);
	
	if (isset($_POST["NombAdmin"]))
		$NombAdmin = str_replace("'", "\'", $_POST["NombAdmin"]);
	
	if (isset($_POST["TeleAdmin"]))
		$TeleAdmin = str_replace("'", "\'", $_POST["TeleAdmin"]);
	
	if (isset($_POST["MailAdmin"]))
		$MailAdmin = str_replace("'", "\'", $_POST["MailAdmin"]);
	
	if (isset($_POST["NombVent"]))
		$NombVent = str_replace("'", "\'", $_POST["NombVent"]);
	
	if (isset($_POST["TeleVent"]))
		$TeleVent = str_replace("'", "\'", $_POST["TeleVent"]);
	
	if (isset($_POST["MailVent"]))
		$MailVent = str_replace("'", "\'", $_POST["MailVent"]);
	
	if (isset($_POST["NumeEsta"]))
		$NumeEsta = $_POST["NumeEsta"];
	
	$conn = new mysqli($dbhost, $dbuser, $dbpass, $db) or die("Problemas al conectar con la BD");

	switch ($operacion) {
		case 0: //INSERT
			$usuario = buscarDato("SELECT NumeUsua FROM usuarios WHERE NombUsua = '{$User}'");
			if ($usuario != "") {
				echo "Error! Usuario ya registrado!";
				die();
			}
			
			$usuario = buscarDato("SELECT NumeUsua FROM usuarios WHERE NombMail = '{$Email}'");
			if ($usuario != "") {
				echo "Error! Mail ya registrado!";
				die();
			}
				
			$NumeAgen = buscarDato("SELECT COALESCE(MAX(NumeAgen), 0) + 1 NumeAgen FROM agencias");

			if (!empty($_FILES["Imagen"])) {
				$temp = explode(".", $_FILES["Imagen"]["name"]);
				$extension = end($temp);

				$archivo = $NumeAgen . "." . $extension;
				$Imagen = "imgAgencias/" . $archivo;
				 
				subir_archivo($_FILES["Imagen"], "../imgAgencias", $archivo);
			}	
			else 
				$Imagen = "";
			
			if (!empty($_FILES["ImagenFlyer"])) {
				$temp = explode(".", $_FILES["ImagenFlyer"]["name"]);
				$extension = end($temp);

				$archivo = $NumeAgen . " - flyer." . $extension;
				$ImagenFlyer = "imgAgencias/" . $archivo;
				 
				subir_archivo($_FILES["ImagenFlyer"], "../imgAgencias", $archivo);
			}	
			else 
				$ImagenFlyer = "";
			
			$strSQL = "INSERT INTO agencias(NumeAgen, NombAgen, Imagen, Dominio, Direccion, Telefono, Email, Facebook, Twitter, Instagram, Posicion, NumeAgenRegi, ImagenFlyer, Ocultar)";
			$strSQL.= " VALUES({$NumeAgen}, '{$NombAgen}', '{$Imagen}', '{$Dominio}', '{$Direccion}', '{$Telefono}', '{$Email}', '{$Facebook}', '{$Twitter}', '{$Instagram}', '{$Posicion}', {$NumeAgenRegi}, '{$ImagenFlyer}', {$Ocultar})";

			if (!$conn->query($strSQL))
				echo "Error al crear agencia:<br />(" . $conn->errno . ") " . $conn->error . "<br />" . $strSQL;
			else {
				procesarRuteo();
				
				$url = 'http://'. $_SERVER['HTTP_HOST'].'/admin/php/usuariosProcesar.php';
				$fields = array(
						'operacion' => 0,
						'NumeUsua' => '',
						'NombComp' => $NombAgen,
						'NombMail' => $Email,
						'NombUsua' => $User,
						'NombPass' => $Pass,
						'TipoUsua' => 2,
						'NumeEsta' => 1,
						'NumeAgen' => $NumeAgen
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
				if (!$response)
					echo curl_error($handle);
				
				//close connection
				curl_close($handle);
				
				if (strripos($response, "error") === false)
					echo "Agencia Creada!<br>";
				else 
					echo $response;
			}
			$tabla->free();
			break;

		case 1: //UPDATE
			if (!empty($_FILES["Imagen"])) {
				$strSQL = "SELECT Imagen FROM agencias WHERE NumeAgen = " . $NumeAgen;
				$tabla = cargarTabla($strSQL);
				$fila = $tabla->fetch_array();
				
				unlink("../" . $fila["Imagen"]);
				
				$temp = explode(".", $_FILES["Imagen"]["name"]);
				$extension = end($temp);

				$archivo = $NumeAgen . "." . $extension;
				$Imagen = "imgAgencias/" . $archivo;
				 
				subir_archivo($_FILES["Imagen"], "../imgAgencias", $archivo);
			}
			
			if (!empty($_FILES["ImagenFlyer"])) {
				$strSQL = "SELECT ImagenFlyer FROM agencias WHERE NumeAgen = " . $NumeAgen;
				$tabla = cargarTabla($strSQL);
				$fila = $tabla->fetch_array();
			
				unlink("../" . $fila["ImagenFlyer"]);
			
				$temp = explode(".", $_FILES["ImagenFlyer"]["name"]);
				$extension = end($temp);
			
				$archivo = $NumeAgen . " - flyer." . $extension;
				$ImagenFlyer = "imgAgencias/" . $archivo;
					
				subir_archivo($_FILES["ImagenFlyer"], "../imgAgencias", $archivo);
			}
			
			$strSQL = "UPDATE agencias";
			$strSQL.= " SET NombAgen = '{$NombAgen}'";
			$strSQL.= ", Dominio = '{$Dominio}'";
			$strSQL.= ", Direccion = '{$Direccion}'";
			$strSQL.= ", Telefono = '{$Telefono}'";
			$strSQL.= ", Email = '{$Email}'";
			$strSQL.= ", Facebook = '{$Facebook}'";
			$strSQL.= ", Twitter = '{$Twitter}'";
			$strSQL.= ", Instagram = '{$Instagram}'";
			$strSQL.= ", Posicion = '{$Posicion}'";
			$strSQL.= ", NumeAgenRegi = '{$NumeAgenRegi}'";
			$strSQL.= ", Ocultar = {$Ocultar}";
			
			if (!empty($_FILES["Imagen"]))
				$strSQL.= ", Imagen = '{$Imagen}'";
			
			if (!empty($_FILES["ImagenFlyer"]))
				$strSQL.= ", ImagenFlyer = '{$ImagenFlyer}'";
			
			$strSQL.= " WHERE NumeAgen = " . $NumeAgen;

			if (!$conn->query($strSQL))
				echo "Error al modificar agencia:<br />(" . $conn->errno . ") " . $conn->error . "<br />";
			else {
				procesarRuteo();
				echo "Agencia modificada!";
			}
			
			break;

		case 2: //DELETE
			$strSQL = "SELECT Imagen FROM agencias WHERE NumeAgen = " . $NumeAgen;
			$tabla = $conn->query($strSQL);
			$fila = $tabla->fetch_array();
				
			if ($fila["Imagen"] != "")
				unlink("../" . $fila["Imagen"]);
			
			$tabla->free();
			
			$strSQL = "DELETE FROM agencias WHERE NumeAgen = " . $NumeAgen;
			
			if (!$conn->query($strSQL))
				echo "Error al borrar agencia:<br />(" . $conn->errno . ") " . $conn->error . "<br />" . $strSQL;
			else {
				procesarRuteo();
				echo "Agencia borrada!";
			}

			break;
			
		case 3: //REGISTRO EN SITIO
			$strSQL = "SELECT COALESCE(MAX(NumeAgen), 0) + 1 NumeAgen FROM agenciasregistradas";
			$tabla = $conn->query($strSQL);
			$fila = $tabla->fetch_array();
			
			$strSQL = "INSERT INTO agenciasregistradas(NumeAgen, NombComercial, Direccion, Telefono, PaginaWeb, RazonSocial, IATA, SECTUR, RFC, NombAdmin, TeleAdmin, MailAdmin, NombVent, TeleVent, MailVent, NumeProv, NumeEsta, FechAlta)";
			$strSQL.= " VALUES({$fila["NumeAgen"]}, '{$NombComercial}', '{$Direccion}', '{$Telefono}', '{$PaginaWeb}', '{$RazonSocial}', '{$IATA}', '{$SECTUR}', '{$RFC}', '{$NombAdmin}', '{$TeleAdmin}', '{$MailAdmin}', '{$NombVent}', '{$TeleVent}', '{$MailVent}', {$Provincia}, 1, SYSDATE())";
			
			if (!$conn->query($strSQL))
				echo "Error al crear agencia.";
			else {
				$titulo = "ICONN TRAVEL ¡Hemos recibido su solicitud de registro como Agencia!";
				
				$mensajeHtml = '<span style="font-family: Calibri;">';
				$mensajeHtml.= "Estimado(a) {$NombAdmin},<br>{$NombComercial}";
				$mensajeHtml.= "<br><br>";
				$mensajeHtml.= "Agradecemos su preferencia y nos permitimos informarle que el registro ";
				$mensajeHtml.= "de su agencia ha sido completado satisfactoriamente. En breve uno de ";
				$mensajeHtml.= "nuestros asesores se pondrá en contacto con usted, para continuar con ";
				$mensajeHtml.= "el proceso de registro, para que su agencia aparezca en nuestra página web ";
				$mensajeHtml.= "y los clientes vean su agencia dentro de nuestro directorio de agencias afiliadas.";
				$mensajeHtml.= "<br><br>";
				$mensajeHtml.= "Saludos cordiales.";
				$mensajeHtml.= "<br>";
				$mensajeHtml.= '<br><img src="http://iconntravel.com.mx/admin/'.buscarDato('SELECT Imagen FROM agencias WHERE NumeAgen = 1').'" style="width: 240px;height: auto;border-right: 1px solid #C99F37;padding-right: 10px;float: left;margin-right: 10px;" />';
				$mensajeHtml.= '<span>';
				$mensajeHtml.= '<strong>Registro Agencias</strong><br><br>';
				$mensajeHtml.= '<a href="http://www.iconntravel.com.mx">www.iconntravel.com.mx</a><br><br>';
				$mensajeHtml.= 'Tel. + 52 (55) 42 10 15 00';
				$mensajeHtml.= '</span>';
				$mensajeHtml.= "</span>";
				
				$mensaje = "Estimado(a) {$NombAdmin}, {$NombComercial}";
				$mensaje.= $crlf.$crlf;
				$mensaje.= "Agradecemos su preferencia y nos permitimos informarle que el registro de su agencia ";
				$mensaje.= "ha sido completado satisfactoriamente. En breve uno de nuestros asesores se pondrá en ";
				$mensaje.= "contacto con usted, para continuar con el proceso de registro, para que su agencia ";
				$mensaje.= "aparezca en nuestra página web y los clientes vean su agencia dentro de nuestro ";
				$mensaje.= "directorio de agencias afiliadas.";
				$mensaje.= $crlf;
				$mensaje.= $crlf."Saludos cordiales.";
				$mensaje.= $crlf;
				$mensaje.= $crlf."Registro Agencias";
				$mensaje.= $crlf."iconntravel@iconnservices.com.mx";
				$mensaje.= $crlf."Contacto 42 10 15 00";
				
				$url = 'http://'. $_SERVER['HTTP_HOST'].'/admin/php/enviarMail.php';
				$fields = array(
						'cantDest' => "2",
						'para1' => $MailAdmin,
						'para2' => $MailVent,
						'cco'=> buscarDato("SELECT Email FROM agencias WHERE NumeAgen = 1"),
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
					echo 'Agencia Registrada!';
				}
				else {
					echo $response;
				}
			}
			$tabla->free();
			break;
			
		case 4: //MODIFICAR AGENCIA REGISTRADA EN SITIO
			$strSQL = "UPDATE agenciasregistradas ";
			$strSQL.= " SET NombComercial = '{$NombComercial}', ";
			$strSQL.= " Direccion = '{$Direccion}', ";
			$strSQL.= " Telefono = '{$Telefono}', ";
			$strSQL.= " PaginaWeb = '{$PaginaWeb}', ";
			$strSQL.= " RazonSocial = '{$RazonSocial}', ";
			$strSQL.= " IATA = '{$IATA}', ";
			$strSQL.= " SECTUR = '{$SECTUR}', ";
			$strSQL.= " RFC = '{$RFC}', ";
			$strSQL.= " NombAdmin = '{$NombAdmin}', ";
			$strSQL.= " TeleAdmin = '{$TeleAdmin}', ";
			$strSQL.= " MailAdmin = '{$MailAdmin}', ";
			$strSQL.= " NombVent = '{$NombVent}', ";
			$strSQL.= " TeleVent = '{$TeleVent}', ";
			$strSQL.= " MailVent = '{$MailVent}', ";
			$strSQL.= " NumeProv = {$Provincia}, ";
			$strSQL.= " NumeEsta = 1 ";
			$strSQL.= " WHERE NumeAgen = " . $NumeAgen;

			if (!$conn->query($strSQL))
				echo "Error al modificar agencia:<br />(" . $conn->errno . ") " . $conn->error . "<br />";
			else
				echo "Agencia modificada!";
				
			break;

		case 10: //LISTAR
			$strSQL = "SELECT NumeAgen, NombAgen, Imagen, ImagenFlyer, Dominio, Direccion, Telefono, Email, Facebook, Twitter, Instagram, Posicion, NumeAgenRegi, Ocultar";
			$strSQL.= " FROM agencias";
			$strSQL.= " ORDER BY NumeAgen";

			$tabla = cargarTabla($strSQL);

			$salida = '';

			if (mysqli_num_rows($tabla) > 0) {
				$salida.= $crlf.'<table class="table table-striped table-condensed">';
				$salida.= $crlf.'<tr>';
				$salida.= $crlf.'<th>Numero</th>';
				$salida.= $crlf.'<th>Nombre</th>';
				$salida.= $crlf.'<th>Dominio</th>';
				$salida.= $crlf.'<th>Imagen</th>';
				$salida.= $crlf.'<th></th>';
				$salida.= $crlf.'<th></th>';
				$salida.= $crlf.'</tr>';
								 
	    		while ($fila = $tabla->fetch_array()) {
    				$salida.= $crlf.'<tr>';
	    					 
					//Numero
					$salida.= $crlf.'<td id="NumeAgen'.$fila["NumeAgen"].'">'.$fila["NumeAgen"];
					$salida.= $crlf.'<input type="hidden" id="Direccion'.$fila["NumeAgen"].'" value="'.str_replace("\"", "&quot;", $fila["Direccion"]).'" />';
					$salida.= $crlf.'<input type="hidden" id="Telefono'.$fila["NumeAgen"].'" value="'.str_replace("\"", "&quot;", $fila["Telefono"]).'" />';
					$salida.= $crlf.'<input type="hidden" id="Email'.$fila["NumeAgen"].'" value="'.$fila["Email"].'" />';
					$salida.= $crlf.'<input type="hidden" id="Facebook'.$fila["NumeAgen"].'" value="'.str_replace("\"", "&quot;", $fila["Facebook"]).'" />';
					$salida.= $crlf.'<input type="hidden" id="Twitter'.$fila["NumeAgen"].'" value="'.str_replace("\"", "&quot;", $fila["Twitter"]).'" />';
					$salida.= $crlf.'<input type="hidden" id="Instagram'.$fila["NumeAgen"].'" value="'.str_replace("\"", "&quot;", $fila["Instagram"]).'" />';
					$salida.= $crlf.'<input type="hidden" id="Ocultar'.$fila["NumeAgen"].'" value="'.str_replace("\"", "&quot;", $fila["Ocultar"]).'" />';
					$salida.= $crlf.'<input type="hidden" id="Posicion'.$fila["NumeAgen"].'" value="'.str_replace("\"", "&quot;", $fila["Posicion"]).'" />';
					$salida.= $crlf.'<input type="hidden" id="NumeAgenRegi'.$fila["NumeAgen"].'" value="'.$fila["NumeAgenRegi"].'" />';
					$salida.= $crlf.'<input type="hidden" id="ImagenFlyer'.$fila["NumeAgen"].'" value="'.$fila["ImagenFlyer"].'" />';
					$salida.= $crlf.'</td>';
					//Nombre
					$salida.= $crlf.'<td id="NombAgen'.$fila["NumeAgen"].'">'.$fila["NombAgen"].'</td>';
					//Dominio
					$salida.= $crlf.'<td id="Dominio'.$fila["NumeAgen"].'">'.$fila["Dominio"].'</td>';
					//Imagen
					$salida.= $crlf.'<td><img id="Imagen'.$fila["NumeAgen"].'" src="'.$fila["Imagen"].'" style="width: 100px; height: auto;" /></td>';
					//Editar
					$salida.= $crlf.'<td style="text-align: center;"><input type="button" value="Editar" onclick="editar(\''.$fila["NumeAgen"].'\')" class="btn btn-info" /></td>';
					//Borrar
					$salida.= $crlf.'<td style="text-align: center;"><input type="button" value="Borrar" onclick="borrar(\''.$fila["NumeAgen"].'\')" class="btn btn-danger" /></td>';
					
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
	    
		case 12: //BORRAR AGENCIA REGISTRADA
			$strSQL = "DELETE FROM agenciasregistradas WHERE NumeAgen = " . $NumeAgen;
			
			if (!$conn->query($strSQL))
				echo "Error al borrar agencia:<br />(" . $conn->errno . ") " . $conn->error . "<br />" . $strSQL;
			else {
				procesarRuteo();
				echo "Agencia borrada!";
			}

			break;
	    
		case 20://LISTAR AGENCIAS REGISTRADAS
			$strSQL = "SELECT a.NumeAgen, a.NombComercial, a.Direccion, a.Telefono, a.PaginaWeb, a.RazonSocial,";
			$strSQL.= " a.IATA, a.SECTUR, a.RFC, a.NombAdmin, a.TeleAdmin, a.MailAdmin, a.NombVent, a.TeleVent,";
			$strSQL.= " a.MailVent, a.NumeProv, p.NombProv, a.NumeEsta, a.FechAlta";
			$strSQL.= " FROM agenciasregistradas a";
			$strSQL.= " LEFT JOIN provincias p ON a.NumeProv = p.NumeProv";
			$strSQL.= " ORDER BY NumeAgen";

			$tabla = cargarTabla($strSQL);

			$salida = '';

			if (mysqli_num_rows($tabla) > 0) {
				$salida.= $crlf.'<table class="table table-striped table-condensed">';
				$salida.= $crlf.'<tr>';
				$salida.= $crlf.'<th>Numero</th>';
				$salida.= $crlf.'<th>Fecha Alta</th>';
				$salida.= $crlf.'<th>Nombre</th>';
				$salida.= $crlf.'<th>Estado</th>';
				$salida.= $crlf.'<th>Direccion</th>';
				$salida.= $crlf.'<th>Telefono</th>';
				$salida.= $crlf.'<th>P&aacute;gina Web</th>';
				$salida.= $crlf.'<th></th>';
				$salida.= $crlf.'<th></th>';
				$salida.= $crlf.'</tr>';
								 
	    		while ($fila = $tabla->fetch_array()) {
    				$salida.= $crlf.'<tr>';
	    					 
					//Numero
					$salida.= $crlf.'<td id="NumeAgen'.$fila["NumeAgen"].'">'.$fila["NumeAgen"];
					
					$salida.= $crlf.'<input type="hidden" id="PaginaWeb'.$fila["NumeAgen"].'" value="'.str_replace("\"", "&quot;", $fila["PaginaWeb"]).'" />';
					$salida.= $crlf.'<input type="hidden" id="RazonSocial'.$fila["NumeAgen"].'" value="'.str_replace("\"", "&quot;", $fila["RazonSocial"]).'" />';
					$salida.= $crlf.'<input type="hidden" id="IATA'.$fila["NumeAgen"].'" value="'.str_replace("\"", "&quot;", $fila["IATA"]).'" />';
					$salida.= $crlf.'<input type="hidden" id="SECTUR'.$fila["NumeAgen"].'" value="'.str_replace("\"", "&quot;", $fila["SECTUR"]).'" />';
					$salida.= $crlf.'<input type="hidden" id="RFC'.$fila["NumeAgen"].'" value="'.str_replace("\"", "&quot;", $fila["RFC"]).'" />';
					$salida.= $crlf.'<input type="hidden" id="NombAdmin'.$fila["NumeAgen"].'" value="'.str_replace("\"", "&quot;", $fila["NombAdmin"]).'" />';
					$salida.= $crlf.'<input type="hidden" id="TeleAdmin'.$fila["NumeAgen"].'" value="'.str_replace("\"", "&quot;", $fila["TeleAdmin"]).'" />';
					$salida.= $crlf.'<input type="hidden" id="MailAdmin'.$fila["NumeAgen"].'" value="'.str_replace("\"", "&quot;", $fila["MailAdmin"]).'" />';
					$salida.= $crlf.'<input type="hidden" id="NombVent'.$fila["NumeAgen"].'" value="'.str_replace("\"", "&quot;", $fila["NombVent"]).'" />';
					$salida.= $crlf.'<input type="hidden" id="TeleVent'.$fila["NumeAgen"].'" value="'.str_replace("\"", "&quot;", $fila["TeleVent"]).'" />';
					$salida.= $crlf.'<input type="hidden" id="MailVent'.$fila["NumeAgen"].'" value="'.str_replace("\"", "&quot;", $fila["MailVent"]).'" />';
					$salida.= $crlf.'<input type="hidden" id="Provincia'.$fila["NumeAgen"].'" value="'.$fila["NumeProv"].'" />';
					
					$salida.= $crlf.'</td>';
					//Fecha
					$salida.= $crlf.'<td id="FechAlta'.$fila["NumeAgen"].'">'.$fila["FechAlta"].'</td>';
					//Nombre
					$salida.= $crlf.'<td id="NombComercial'.$fila["NumeAgen"].'">'.$fila["NombComercial"].'</td>';
					//Estado
					$salida.= $crlf.'<td>'.htmlentities($fila["NombProv"]).'</td>';
					//Direccion
					$salida.= $crlf.'<td id="Direccion'.$fila["NumeAgen"].'">'.$fila["Direccion"].'</td>';
					//Telefono
					$salida.= $crlf.'<td id="Telefono'.$fila["NumeAgen"].'">'.$fila["Telefono"].'</td>';
					//Pagina Web
					$salida.= $crlf.'<td><a href="http://'.$fila["PaginaWeb"].'" target="_blank">'.$fila["PaginaWeb"].'</a></td>';
					//Editar
					$salida.= $crlf.'<td style="text-align: center;"><input type="button" value="Ver Datos / Editar" onclick="editar(\''.$fila["NumeAgen"].'\')" class="btn btn-info" /></td>';
					//Borrar
					$salida.= $crlf.'<td style="text-align: center;"><input type="button" value="Borrar" onclick="borrar(\''.$fila["NumeAgen"].'\')" class="btn btn-danger" /></td>';
					
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