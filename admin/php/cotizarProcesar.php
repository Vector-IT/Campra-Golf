<?php
	session_start();
	
	if (!isset($_SESSION['is_logged_in'])) {
		header("Location:login.php");
		die();
	}
	
	include("conexion.php");
	
	$operacion = $_POST["operacion"];
	
	if (isset($_POST["NumeCoti"])) {
		$NumeCoti = $_POST["NumeCoti"];
	}
	if (isset($_POST["NumeAgen"])) {
		$NumeAgen = $_POST["NumeAgen"];
	}
	if (isset($_POST["NumeUsua"])) {
		$NumeUsua = $_POST["NumeUsua"];
	}
	if (isset($_POST["Codigo"])) {
		$Codigo = str_replace("'", "\'", $_POST["Codigo"]);
	}
	if (isset($_POST["NumeExpe"])) {
		$NumeExpe = $_POST["NumeExpe"];
	}
	if (isset($_POST["NumeTour"])) {
		$NumeTour = $_POST["NumeTour"];
	}
	if (isset($_POST["Nombre"])) {
		$Nombre = str_replace("'", "\'", $_POST["Nombre"]);
	}
	if (isset($_POST["Email"])) {
		$Email = str_replace("'", "\'", $_POST["Email"]);
	}
	if (isset($_POST["Telefono"])) {
		$Telefono = str_replace("'", "\'", $_POST["Telefono"]);
	}
	if (isset($_POST["NumeProv"])) {
		$NumeProv = $_POST["NumeProv"];
	}
	if (isset($_POST["Pasajero"])) {
		$Pasajero = str_replace("'", "\'", $_POST["Pasajero"]);
	}
	if (isset($_POST["FechViaj"])) {
		$FechViaj = "DATE_FORMAT('" . str_replace("'", "\'", $_POST["FechViaj"]) . "', '%Y-%m-%d')";
	}
	if (isset($_POST["Origen"])) {
		$Origen = str_replace("'", "\'", $_POST["Origen"]);
	}
	if (isset($_POST["Aereo"])) {
		$Aereo = $_POST["Aereo"];
	}
	if (isset($_POST["AdulCant"])) {
		$AdulCant = 0 + $_POST["AdulCant"];
	}
	if (isset($_POST["AdulEdad"])) {
		$AdulEdad = str_replace("'", "\'", $_POST["AdulEdad"]);
	}
	if (isset($_POST["MenoCant"])) {
		$MenoCant = 0 + $_POST["MenoCant"];
	}
	if (isset($_POST["MenoEdad"])) {
		$MenoEdad = str_replace("'", "\'", $_POST["MenoEdad"]);
	}
	if (isset($_POST["InfaCant"])) {
		$InfaCant = 0 + $_POST["InfaCant"];
	}
	if (isset($_POST["InfaEdad"])) {
		$InfaEdad = str_replace("'", "\'", $_POST["InfaEdad"]);
	}
	if (isset($_POST["Comentario"])) {
		$Comentario = str_replace("'", "\'", $_POST["Comentario"]);
	}
	if (isset($_POST["NumeEsta"])) {
		$NumeEsta = $_POST["NumeEsta"];
	}

	switch ($operacion) {
		case 0: //INSERT
			$NumeCoti = buscarDato("SELECT COALESCE(MAX(NumeCoti), 0) + 1 FROM cotizaciones");

			$strSQL = "INSERT INTO cotizaciones(NumeCoti, FechCoti, NumeAgen, NumeUsua, Codigo, NumeExpe, NumeTour, Nombre, Email, Telefono, NumeProv, Pasajero, FechViaj, Origen, Aereo, AdulCant, AdulEdad, MenoCant, MenoEdad, InfaCant, InfaEdad, Comentario, NumeEsta) ";
			$strSQL.= " VALUES ({$NumeCoti}, SYSDATE(), {$NumeAgen}, {$NumeUsua}, '{$Codigo}', {$NumeExpe}, {$NumeTour}, '{$Nombre}', '{$Email}', '{$Telefono}', {$NumeProv}, '{$Pasajero}', {$FechViaj}, '{$Origen}', {$Aereo}, {$AdulCant}, '{$AdulEdad}', {$MenoCant}, '{$MenoEdad}', {$InfaCant}, '{$InfaEdad}', '{$Comentario}', 1)";
				
			$resultado = ejecutarCMD($strSQL);
			echo $strSQL;
			if (!$resultado) {
				echo "Error-1";
				return false;
			}
			$url = 'http://'. $_SERVER['HTTP_HOST'].'/admin/php/enviarMail.php';
			$fields = array(
					//'para1' => 'jmperro@gmail.com',
					'para1' => buscarDato('SELECT Email FROM agencias WHERE NumeAgen = 1'),
					'asunto' => 'Nueva Solicitud de Cotización',
					'mensaje' => 'Usted ha recibido una nueva solicitud de cotizaci&oacute;n.',
					'mensajeAlt' => 'Usted ha recibido una nueva solicitud de cotización.'
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
			//close connection
			curl_close($handle);
			
			if (!$response) {
				echo 'Error-2';
				return false;
			}
			
			$mensaje = '';
			$mensajeAlt = '';
			
			$mensaje.= "Estimado(a),";
			$mensaje.= "<br>Agradecemos su interés en vivir la experiencia ";
			switch ($NumeExpe) {
				case "-1":
					$mensaje.= "TODAS";
					break;
				case "-2":
					$mensaje.= "RUTAS MEXICO";
					break;
				case "-3":
					$mensaje.= "OTRA";
					break;
				default:
					$mensaje.= buscarDato("SELECT NombExpe FROM experiencias WHERE NumeExpe = {$NumeExpe}") .".";
					break;
			}
			$mensaje.= "<br>Le confirmamos que hemos recibido su solicitud que está siendo procesada.";
			$mensaje.= "<br>";
			$mensaje.= "<br>Con base a los siguientes requerimientos, uno de nuestros agentes de viajes, se pondrá en contacto para dar forma a esa experiencia y presentarle una cotización de acuerdo a:";
			$mensaje.= "<br>Sus datos de contacto registrados:";
			$mensaje.= "<br>Correo: {$Email}";
			$mensaje.= "<br>Teléfono: {$Telefono}";
			switch ($NumeTour) {
				case "-1":
					$mensaje.= "<br>Tour: TODOS";
					break;
				case "-2":
					$mensaje.= "<br>Tour: OTROS";
					break;
				default:
					$mensaje.= "<br>Tour: ". buscarDato("SELECT NombTour FROM tours WHERE NumeTour = {$NumeTour}");
					break;
			}
			$mensaje.= "<br>Estado: ". buscarDato("SELECT NombProv FROM provincias WHERE NumeProv = {$NumeProv}");
			$mensaje.= "<br>Comentarios: {$Comentario}";
			$mensaje.= "<br>";
			$mensaje.= "<br>Atentamente";
			$mensaje.= '<br><img src="http://iconntravel.com.mx/admin/'.buscarDato('SELECT Imagen FROM agencias WHERE NumeAgen = 1').'" style="width: 240px;height: auto;border-right: 1px solid #C99F37;padding-right: 10px;float: left;margin-right: 10px;" />';
			$mensaje.= '<span>';
			$mensaje.= '<strong>Central de Experiencias</strong><br><br>';
			$mensaje.= '<a href="http://www.iconntravel.com.mx">www.iconntravel.com.mx</a><br><br>';
			$mensaje.= 'Tel. + 52 (55) 42 10 15 00';
			$mensaje.= '</span>';
			
			$mensajeAlt.= "Estimado(a),";
			$mensajeAlt.= "\nAgradecemos su interés en vivir la experiencia ";
			switch ($NumeExpe) {
				case "-1":
					$mensajeAlt.= "TODAS";
					break;
				case "-2":
					$mensajeAlt.= "RUTAS MEXICO";
					break;
				case "-3":
					$mensajeAlt.= "OTRA";
					break;
				default:
					$mensajeAlt.= buscarDato("SELECT NombExpe FROM experiencias WHERE NumeExpe = {$NumeExpe}") .".";
					break;
			}
			$mensajeAlt.= "\nLe confirmamos que hemos recibido su solicitud que está siendo procesada.";
			$mensajeAlt.= "\n";
			$mensajeAlt.= "\nCon base a los siguientes requerimientos, uno de nuestros agentes de viajes, se pondrá en contacto para dar forma a esa experiencia y presentarle una cotización de acuerdo a:";
			$mensajeAlt.= "\nSus datos de contacto registrados:";
			$mensajeAlt.= "\nCorreo: {$Email}";
			$mensajeAlt.= "\nTeléfono: {$Telefono}";
			switch ($NumeTour) {
				case "-1":
					$mensajeAlt.= "\nTour: TODOS";
					break;
				case "-2":
					$mensajeAlt.= "\nTour: OTROS";
					break;
				default:
					$mensajeAlt.= "\nTour: ". buscarDato("SELECT NombTour FROM tours WHERE NumeTour = {$NumeTour}");
					break;
			}
			$mensajeAlt.= "\nEstado: ". buscarDato("SELECT NombProv FROM provincias WHERE NumeProv = {$NumeProv}");
			$mensajeAlt.= "\nComentarios: {$Comentario}";
			$mensajeAlt.= "\n";
			$mensajeAlt.= "\nAtentamente";
			$mensajeAlt.= '\nCentral de Experiencias';
			$mensajeAlt.= '\nhttp://www.iconntravel.com.mx';
			$mensajeAlt.= '\nTel. + 52 (55) 42 10 15 00';
			
			$fields = array(
					'para1' => $Email,
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
			if (!$response)
				echo 'Error al enviar correo.<br>'.curl_error($handle);
			else
				echo 'Exito';
			
			curl_close($handle);
			
			break;

		case 1: //UPDATE
			$strSQL = "UPDATE cotizaciones ";
			$strSQL.= " SET NumeAgen = {$NumeAgen}";
			$strSQL.= " , NumeUsua = {$NumeUsua}";
			$strSQL.= " , Codigo = '{$Codigo}'";
			$strSQL.= " , NumeExpe = {$NumeExpe}";
			$strSQL.= " , NumeTour = {$NumeTour}";
			$strSQL.= " , Nombre = '{$Nombre}'";
			$strSQL.= " , Email = '{$Email}'";
			$strSQL.= " , Telefono = '{$Telefono}'";
			$strSQL.= " , NumeProv = {$NumeProv}";
			$strSQL.= " , Pasajero = '{$Pasajero}'";
			$strSQL.= " , FechViaj = {$FechViaj}";
			$strSQL.= " , Origen = '{$Origen}'";
			$strSQL.= " , Aereo = {$Aereo}";
			$strSQL.= " , AdulCant = {$AdulCant}";
			$strSQL.= " , AdulEdad = '{$AdulEdad}'";
			$strSQL.= " , MenoCant = {$MenoCant}";
			$strSQL.= " , MenoEdad = '{$MenoEdad}'";
			$strSQL.= " , InfaCant = {$InfaCant}";
			$strSQL.= " , InfaEdad = '{$InfaEdad}'";
			$strSQL.= " , Comentario = '{$Comentario}'";
			$strSQL.= " , NumeEsta = {$NumeEsta} ";
			$strSQL.= " WHERE NumeCoti = {$NumeCoti}";
				
			$resultado = ejecutarCMD($strSQL);
			if (!$resultado)
				echo "Error";
			else 
				echo "Exito";
			
				break;

		case 2: //DELETE
			$strSQL = "DELETE FROM cotizaciones WHERE NumeCoti = " . $NumeCoti;
			
			$resultado = ejecutarCMD($strSQL);
			if (!$resultado)
				echo "Error al borrar la cotizaci&oacute;n.";
			else 
				echo "Cotizaci&oacute;n eliminada.";
			
			break;
			
		case 10: //LISTAR
			$strSQL = "SELECT c.NumeCoti, c.FechCoti, c.NumeAgen, a.NombAgen, c.NumeUsua, c.Nombre, c.Codigo, c.NumeExpe, e.NombExpe, ";
			$strSQL.= " c.NumeTour, t.NombTour, c.Nombre, c.Email, c.Telefono, c.NumeProv, p.NombProv, c.Pasajero, c.FechViaj, c.Origen, ";
			$strSQL.= " c.Aereo, c.AdulCant, c.AdulEdad, c.MenoCant, c.MenoEdad, c.InfaCant, c.InfaEdad, c.Comentario, c.NumeEsta ";
			$strSQL.= " FROM cotizaciones c";
			$strSQL.= " LEFT JOIN agencias a ON c.NumeAgen = a.NumeAgen";
			//$strSQL.= " LEFT JOIN usuarios u ON c.NumeUsua = u.NumeUsua";
			$strSQL.= " LEFT JOIN experiencias e ON c.NumeExpe = e.NumeExpe";
			$strSQL.= " LEFT JOIN tours t ON c.NumeTour = t.NumeTour";
			$strSQL.= " LEFT JOIN provincias p ON c.NumeProv = p.NumeProv";
			$strSQL.= " ORDER BY c.NumeCoti desc";
				
			$tabla = cargarTabla($strSQL);

			$salida = '';

			if (mysqli_num_rows($tabla) > 0) {
				$salida.= $crlf.'<table class="table table-striped table-condensed">';
				$salida.= $crlf.'<tr>';
				$salida.= $crlf.'<th>Numero</th>';
				$salida.= $crlf.'<th>Fecha</th>';
				$salida.= $crlf.'<th>Agencia</th>';
				$salida.= $crlf.'<th>Usuario</th>';
				$salida.= $crlf.'<th>Email</th>';
				$salida.= $crlf.'<th>C&oacute;digo</th>';
				$salida.= $crlf.'<th>Experiencia</th>';
				$salida.= $crlf.'<th>Tour</th>';				
				$salida.= $crlf.'<th>Status</th>';
				$salida.= $crlf.'<th></th>';
				$salida.= $crlf.'<th></th>';
				$salida.= $crlf.'</tr>';
								 
	    		while ($fila = $tabla->fetch_array()) {
    				$salida.= $crlf.'<tr>';
	    					 
					//Numero
					$salida.= $crlf.'<td id="NumeCoti'.$fila[0].'">'.$fila[0];
					$salida.= $crlf.'<input type="hidden" id="NumeAgen'.$fila[0].'" value="'.$fila["NumeAgen"].'" />';
					$salida.= $crlf.'<input type="hidden" id="NumeUsua'.$fila[0].'" value="'.$fila["NumeUsua"].'" />';
					$salida.= $crlf.'<input type="hidden" id="NumeExpe'.$fila[0].'" value="'.$fila["NumeExpe"].'" />';
					$salida.= $crlf.'<input type="hidden" id="NumeTour'.$fila[0].'" value="'.$fila["NumeTour"].'" />';
					$salida.= $crlf.'<input type="hidden" id="NumeProv'.$fila[0].'" value="'.$fila["NumeProv"].'" />';
					$salida.= $crlf.'<input type="hidden" id="Telefono'.$fila[0].'" value="'.str_replace("\"", "&quot;", $fila["Telefono"]).'" />';
					$salida.= $crlf.'<input type="hidden" id="Pasajero'.$fila[0].'" value="'.str_replace("\"", "&quot;", $fila["Pasajero"]).'" />';
					$salida.= $crlf.'<input type="hidden" id="FechViaj'.$fila[0].'" value="'.$fila["FechViaj"].'" />';
					$salida.= $crlf.'<input type="hidden" id="Origen'.$fila[0].'" value="'.str_replace("\"", "&quot;", $fila["Origen"]).'" />';
					$salida.= $crlf.'<input type="hidden" id="Aereo'.$fila[0].'" value="'.$fila["Aereo"].'" />';
					$salida.= $crlf.'<input type="hidden" id="AdulCant'.$fila[0].'" value="'.$fila["AdulCant"].'" />';
					$salida.= $crlf.'<input type="hidden" id="AdulEdad'.$fila[0].'" value="'.str_replace("\"", "&quot;", $fila["AdulEdad"]).'" />';
					$salida.= $crlf.'<input type="hidden" id="MenoCant'.$fila[0].'" value="'.$fila["MenoCant"].'" />';
					$salida.= $crlf.'<input type="hidden" id="MenoEdad'.$fila[0].'" value="'.str_replace("\"", "&quot;", $fila["MenoEdad"]).'" />';
					$salida.= $crlf.'<input type="hidden" id="InfaCant'.$fila[0].'" value="'.$fila["InfaCant"].'" />';
					$salida.= $crlf.'<input type="hidden" id="InfaEdad'.$fila[0].'" value="'.str_replace("\"", "&quot;", $fila["InfaEdad"]).'" />';
					$salida.= $crlf.'<input type="hidden" id="Comentario'.$fila[0].'" value="'.str_replace("\"", "&quot;", $fila["Comentario"]).'" />';
					$salida.= $crlf.'<input type="hidden" id="NumeEsta'.$fila[0].'" value="'.$fila["NumeEsta"].'" />';
					$salida.= $crlf.'</td>';
					//Fecha carga
					$salida.= $crlf.'<td id="FechCoti'.$fila[0].'">'.$fila["FechCoti"].'</td>';
					//Agencia
					$salida.= $crlf.'<td id="NombAgen'.$fila[0].'">'.$fila["NombAgen"].'</td>';
					//Usuario
					$salida.= $crlf.'<td id="Nombre'.$fila[0].'">'.$fila["Nombre"].'</td>';
					//Email
					$salida.= $crlf.'<td id="Email'.$fila[0].'">'.$fila["Email"].'</td>';
					//Codigo
					$salida.= $crlf.'<td id="Codigo'.$fila[0].'">'.$fila["Codigo"].'</td>';
					//Experiencia
					$salida.= $crlf.'<td>'.$fila["NombExpe"].'</td>';
					//Tour
					$salida.= $crlf.'<td>'.$fila["NombTour"].'</td>';
					//Estado
					switch ($fila["NumeEsta"]) {
						case "1":
							$salida.= $crlf.'<td>Activa</td>';
							break;
						case "2":
							$salida.= $crlf.'<td>Cancelada</td>';
							break;
						case "3":
							$salida.= $crlf.'<td>A Solicitud</td>';
							break;
						case "4":
							$salida.= $crlf.'<td>Venta Concretada</td>';
							break;
					}
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