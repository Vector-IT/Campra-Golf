<?php
	session_start();
	
	if (!isset($_SESSION['is_logged_in'])) {
		header("Location:../../index.php");
		die();
	}
	
	include("conexion.php");
	include("upload_file.php");
	
	$operacion = $_POST["operacion"];
	
	if (isset($_POST["NumeTour"]))
		$NumeTour = $_POST["NumeTour"];
	
	if (isset($_POST["NombTour"]))
		$NombTour = str_replace("'", "\'", $_POST["NombTour"]);

	if (isset($_POST["Dominio"]))
		$Dominio = str_replace(" ", "-", trim(str_replace("'", "\'", $_POST["Dominio"])));
	
	if (isset($_POST["Lugares"]))
		$Lugares = str_replace("'", "\'", $_POST["Lugares"]);

	if (isset($_POST["Copete"]))
		$Copete = str_replace("'", "\'", $_POST["Copete"]);
	
	if (isset($_POST["Subtitulo"]))
		$Subtitulo = str_replace("'", "\'", $_POST["Subtitulo"]);
	
	if (isset($_POST["Duracion"]))
		$Duracion = str_replace("'", "\'", $_POST["Duracion"]);
	
	if (isset($_POST["NumeExpe"]))
		$NumeExpe = str_replace("'", "\'", $_POST["NumeExpe"]);
	
	if (isset($_POST["DescTour"]))
		$DescTour = str_replace("'", "\'", $_POST["DescTour"]);
	
	if (isset($_POST["Precio"]))
		$Precio = str_replace("'", "\'", $_POST["Precio"]);
	
	if (isset($_POST["Vigencia"]))
		$Vigencia = str_replace("'", "\'", $_POST["Vigencia"]);
	
	if (isset($_POST["EnPromo"]))
		$EnPromo = $_POST["EnPromo"];
	
	if (isset($_POST["AbrirLink"]))
		$AbrirLink = $_POST["AbrirLink"];
	
	if (isset($_POST["Posicion"]))
		$Posicion = str_replace("'", "\'", $_POST["Posicion"]);
	
	if (isset($_POST["NumeEsta"]))
		$NumeEsta = $_POST["NumeEsta"];
	
	if (isset($_POST["Codigo"]))
		$Codigo = str_replace("'", "\'", $_POST["Codigo"]);
	
	$conn = new mysqli($dbhost, $dbuser, $dbpass, $db) or die("Problemas al conectar con la BD");

	switch ($operacion) {
		case 0: //INSERT
			$strSQL = "SELECT COALESCE(MAX(NumeTour), 0) + 1 NumeTour FROM tours";
			$tabla = $conn->query($strSQL);
			$fila = $tabla->fetch_array();

			if (empty($_FILES["Imagen"])) {
				echo "Error! Falta imagen de banner";
				die();
			}
			if (empty($_FILES["Portada"])) {
				echo "Error! Falta imagen de portada";
				die();
			}
			
			//Articulo
			if (!empty($_FILES["Articulo"])) {
				$temp = explode(".", $_FILES["Articulo"]["name"]);
				$extension = end($temp);
				
				$archivo = $fila["NumeTour"] . " - articulo." . $extension;
				$Articulo = "imgTours/" . $archivo;
				
				subir_archivo($_FILES["Articulo"], "../imgTours", $archivo);
			}
			else 
				$Articulo = "";
				
			//Imagen
			$temp = explode(".", $_FILES["Imagen"]["name"]);
			$extension = end($temp);

			$archivo = $fila["NumeTour"] . " - imagen." . $extension;
			$Imagen = "imgTours/" . $archivo;
			 
			subir_archivo($_FILES["Imagen"], "../imgTours", $archivo);
			
			//Portada
			$temp = explode(".", $_FILES["Portada"]["name"]);
			$extension = end($temp);

			$archivo = $fila["NumeTour"] . " - portada." . $extension;
			$Portada = "imgTours/" . $archivo;
			 
			subir_archivo($_FILES["Portada"], "../imgTours", $archivo);
			
			//INSERT
			$strSQL = "INSERT INTO tours(NumeTour, NombTour, Dominio, AbrirLink, Lugares, Copete, Subtitulo, Duracion, NumeExpe, DescTour, Precio, Vigencia, Articulo, Imagen, Portada, Posicion, EnPromo, NumeEsta, Codigo)";
			$strSQL.= " VALUES({$fila["NumeTour"]}, '{$NombTour}', '{$Dominio}', {$AbrirLink}, '{$Lugares}', '{$Copete}', '{$Subtitulo}', '{$Duracion}', {$NumeExpe}, '{$DescTour}', '{$Precio}', '{$Vigencia}', '{$Articulo}', '{$Imagen}', '{$Portada}', '{$Posicion}', {$EnPromo}, {$NumeEsta}, '{$Codigo}')";

			if (!$conn->query($strSQL))
				echo "Error al crear producto:<br />(" . $conn->errno . ") " . $conn->error . "<br />" . $strSQL;
			else
				echo "Producto creado!<br>";

			break;

		case 1: //UPDATE
			$strSQL = "SELECT Articulo, Imagen, Portada FROM tours WHERE NumeTour = " . $NumeTour;
			$tabla = cargarTabla($strSQL);
			$fila = $tabla->fetch_array();

			if (!empty($_FILES["Articulo"])) {
				unlink("../" . $fila["Articulo"]);
				
				$temp = explode(".", $_FILES["Articulo"]["name"]);
				$extension = end($temp);

				$archivo = $NumeTour . " - articulo." . $extension;
				$Articulo = "imgTours/" . $archivo;
				 
				subir_archivo($_FILES["Articulo"], "../imgTours", $archivo);
			}
			
			if (!empty($_FILES["Imagen"])) {
				unlink("../" . $fila["Imagen"]);
			
				$temp = explode(".", $_FILES["Imagen"]["name"]);
				$extension = end($temp);
			
				$archivo = $NumeTour . " - imagen." . $extension;
				$Imagen = "imgTours/" . $archivo;
					
				subir_archivo($_FILES["Imagen"], "../imgTours", $archivo);
			}
				
			if (!empty($_FILES["Portada"])) {
				unlink("../" . $fila["Portada"]);
				
				$temp = explode(".", $_FILES["Portada"]["name"]);
				$extension = end($temp);

				$archivo = $NumeTour . " - portada." . $extension;
				$Portada = "imgTours/" . $archivo;
				 
				subir_archivo($_FILES["Portada"], "../imgTours", $archivo);
			}
			
			$strSQL = "UPDATE tours";
			$strSQL.= " SET NombTour = '{$NombTour}'";
			$strSQL.= ", Dominio = '{$Dominio}'";
			$strSQL.= ", Lugares = '{$Lugares}'";
			$strSQL.= ", Copete = '{$Copete}'";
			$strSQL.= ", Subtitulo = '{$Subtitulo}'";
			$strSQL.= ", Duracion = '{$Duracion}'";
			$strSQL.= ", NumeExpe = {$NumeExpe}";
			$strSQL.= ", DescTour = '{$DescTour}'";
			$strSQL.= ", Precio = '{$Precio}'";
			$strSQL.= ", Vigencia = '{$Vigencia}'";
			$strSQL.= ", Posicion = '{$Posicion}'";
			$strSQL.= ", EnPromo = {$EnPromo}";
			$strSQL.= ", AbrirLink = {$AbrirLink}";
			$strSQL.= ", NumeEsta = {$NumeEsta}";
			$strSQL.= ", Codigo = '{$Codigo}'";
				
			if (!empty($_FILES["Articulo"]))
				$strSQL.= ", Articulo = '{$Articulo}'";

			if (!empty($_FILES["Imagen"]))
				$strSQL.= ", Imagen = '{$Imagen}'";
				
			if (!empty($_FILES["Portada"]))
				$strSQL.= ", Portada = '{$Portada}'";
			
			$strSQL.= " WHERE NumeTour = " . $NumeTour;

			if (!$conn->query($strSQL))
				echo "Error al modificar producto:<br />(" . $conn->errno . ") " . $conn->error . "<br />";
			else
				echo "Producto modificado!";
			
			break;

		case 2: //DELETE
			$strSQL = "SELECT Articulo, Imagen, Portada FROM tours WHERE NumeTour = " . $NumeTour;
			$tabla = cargarTabla($strSQL);
			$fila = $tabla->fetch_array();
			
			unlink("../" . $fila["Imagen"]);
			unlink("../" . $fila["Portada"]);
			if ($fila["Articulo"] != "")
				unlink("../" . $fila["Articulo"]);
			
			$strSQL = "DELETE FROM tourscomentarios WHERE NumeTour = " . $NumeTour;
			$conn->query($strSQL);
			
			$strSQL = "DELETE FROM toursdocumentacion WHERE NumeTour = " . $NumeTour;
			$conn->query($strSQL);
			
			$strSQL = "DELETE FROM toursincluye WHERE NumeTour = " . $NumeTour;
			$conn->query($strSQL);
			
			$strSQL = "SELECT Imagen FROM toursgaleria WHERE NumeTour = " . $NumeTour;
			$tabla = cargarTabla($strSQL);
			while ($fila = $tabla->fetch_array()) {
				unlink("../" . $fila["Imagen"]);
			}
			
			$strSQL = "DELETE FROM toursgaleria WHERE NumeTour = " . $NumeTour;
			$conn->query($strSQL);
			
			$strSQL = "DELETE FROM tours WHERE NumeTour = " . $NumeTour;
			
			if (!$conn->query($strSQL))
				echo "Error al borrar tour:<br />(" . $conn->errno . ") " . $conn->error . "<br />" . $strSQL;
			else
				echo "Tour borrado!";

			break;

		case 10: //LISTAR
			$strSQL = "SELECT t.NumeTour, t.NombTour, e.NombExpe, t.Dominio, t.AbrirLink, t.Lugares, t.Copete, t.Subtitulo, t.DescTour, t.Duracion, t.NumeExpe, t.Precio, t.Vigencia, t.Articulo, t.Imagen, t.Portada, t.Posicion, t.EnPromo, t.NumeEsta, t.Codigo";
			$strSQL.= " FROM tours t";
			$strSQL.= " LEFT JOIN experiencias e ON t.NumeExpe = e.NumeExpe";
			$strSQL.= " ORDER BY t.NumeTour";

			$tabla = cargarTabla($strSQL);

			$salida = '';

			if (mysqli_num_rows($tabla) > 0) {
				$salida.= $crlf.'<table class="table table-striped table-condensed">';
				$salida.= $crlf.'<tr>';
				$salida.= $crlf.'<th>Numero</th>';
				$salida.= $crlf.'<th>Nombre</th>';
				$salida.= $crlf.'<th>Experiencia</th>';
				$salida.= $crlf.'<th>Imagen</th>';
				$salida.= $crlf.'<th>Estado</th>';
				$salida.= $crlf.'<th></th>';
				$salida.= $crlf.'</tr>';
								 
	    		while ($fila = $tabla->fetch_array()) {
    				$salida.= $crlf.'<tr>';
	    					 
					//Numero
					$salida.= $crlf.'<td id="NumeTour'.$fila["NumeTour"].'">'.$fila["NumeTour"];
					$salida.= $crlf.'<input type="hidden" id="Dominio'.$fila["NumeTour"].'" value="'.str_replace("\"", "&quot;", $fila["Dominio"]).'" />';
					$salida.= $crlf.'<input type="hidden" id="Lugares'.$fila["NumeTour"].'" value="'.str_replace("\"", "&quot;", $fila["Lugares"]).'" />';
					$salida.= $crlf.'<input type="hidden" id="Copete'.$fila["NumeTour"].'" value="'.str_replace("\"", "&quot;", $fila["Copete"]).'" />';
					$salida.= $crlf.'<input type="hidden" id="Subtitulo'.$fila["NumeTour"].'" value="'.str_replace("\"", "&quot;", $fila["Subtitulo"]).'" />';
					$salida.= $crlf.'<input type="hidden" id="DescTour'.$fila["NumeTour"].'" value="'.str_replace("\"", "&quot;", $fila["DescTour"]).'" />';
					$salida.= $crlf.'<input type="hidden" id="Duracion'.$fila["NumeTour"].'" value="'.str_replace("\"", "&quot;", $fila["Duracion"]).'" />';
					$salida.= $crlf.'<input type="hidden" id="NumeExpe'.$fila["NumeTour"].'" value="'.$fila["NumeExpe"].'" />';
					$salida.= $crlf.'<input type="hidden" id="Precio'.$fila["NumeTour"].'" value="'.str_replace("\"", "&quot;", $fila["Precio"]).'" />';
					$salida.= $crlf.'<input type="hidden" id="Vigencia'.$fila["NumeTour"].'" value="'.str_replace("\"", "&quot;", $fila["Vigencia"]).'" />';
					$salida.= $crlf.'<input type="hidden" id="Portada'.$fila["NumeTour"].'" value="'.$fila["Portada"].'" />';
					$salida.= $crlf.'<input type="hidden" id="Articulo'.$fila["NumeTour"].'" value="'.$fila["Articulo"].'" />';
					$salida.= $crlf.'<input type="hidden" id="Posicion'.$fila["NumeTour"].'" value="'.$fila["Posicion"].'" />';
					$salida.= $crlf.'<input type="hidden" id="EnPromo'.$fila["NumeTour"].'" value="'.$fila["EnPromo"].'" />';
					$salida.= $crlf.'<input type="hidden" id="AbrirLink'.$fila["NumeTour"].'" value="'.$fila["AbrirLink"].'" />';
					$salida.= $crlf.'<input type="hidden" id="NumeEsta'.$fila["NumeTour"].'" value="'.$fila["NumeEsta"].'" />';
					$salida.= $crlf.'<input type="hidden" id="Codigo'.$fila["NumeTour"].'" value="'.str_replace("\"", "&quot;", $fila["Codigo"]).'" />';
					$salida.= $crlf.'</td>';
					//Nombre
					$salida.= $crlf.'<td id="NombTour'.$fila["NumeTour"].'">'.$fila["NombTour"].'</td>';
					//Experiencia
					$salida.= $crlf.'<td>'.$fila["NombExpe"].'</td>';
					//Imagen Banner
					$salida.= $crlf.'<td><img id="Imagen'.$fila["NumeTour"].'" src="'.$fila["Imagen"].'" style="width: 100px; height: auto;" /></td>';
					//Estado
					if ($fila["NumeEsta"] == 1)
						$salida.= $crlf.'<td>Activo</td>';
					else
						$salida.= $crlf.'<td>Inactivo</td>';
					//Itineriario
					$salida.= $crlf.'<td style="text-align: center;">';
					$salida.= $crlf.'<input type="button" value="Itinerario" onclick="location.href=\'itinerario.php?tour='.$fila["NumeTour"].'\'" class="btn btn-default btn-sm" />';
					//Galeria
					$salida.= $crlf.'<input type="button" value="Galeria" onclick="location.href=\'galeriaProducto.php?tour='.$fila["NumeTour"].'\'" class="btn btn-default btn-sm" />';
					//Incluye
					$salida.= $crlf.'<input type="button" value="Incluye / No incluye" onclick="location.href=\'incluye.php?tour='.$fila["NumeTour"].'\'" class="btn btn-default btn-sm" />';
					//Documentacion
					$salida.= $crlf.'<input type="button" value="Documentaci&oacute;n" onclick="location.href=\'documentacion.php?tour='.$fila["NumeTour"].'\'" class="btn btn-default btn-sm" />';
					//Comentarios
					$salida.= $crlf.'<input type="button" value="Comentarios" onclick="location.href=\'productocomentarios.php?tour='.$fila["NumeTour"].'\'" class="btn btn-default btn-sm" />';
					//Editar
					$salida.= $crlf.'<input type="button" value="Editar" onclick="editar(\''.$fila["NumeTour"].'\')" class="btn btn-info btn-sm" />';
					//Borrar
					$salida.= $crlf.'<input type="button" value="Borrar" onclick="borrar(\''.$fila["NumeTour"].'\')" class="btn btn-danger btn-sm" /></td>';
					
					$salida.= $crlf.'</tr>';
				}
				
				$salida.= $crlf.'</table>';
	    	}
	    	else {
	    		$salida.= "<h3>Sin datos para mostrar</h3>";
	    	}
	    	
	    	echo $salida;
	
	    	break;
	    	
		case 20: //Cargar combo
			$strSQL = "SELECT NumeTour, NombTour FROM tours";
			$strSQL.= " WHERE NumeEsta = 1 AND EnPromo = 0";
			if (isset($NumeExpe) && ($NumeExpe > 0))
				$strSQL.= " AND NumeExpe = {$NumeExpe}";
			$strSQL.= " ORDER BY NombTour";
			
			$tabla = cargarTabla($strSQL);
			
			$strSalida = "";
			$strSalida.= $crlf.'<option value="-1">Todos</option>';
			
			while ($fila = $tabla->fetch_array()) {
				if (strcmp($fila[0], $NumeTour) != "0")
					$strSalida.= $crlf.'<option value="'.$fila[0].'">'.$fila[1].'</option>';
				else
					$strSalida.= $crlf.'<option value="'.$fila[0].'" selected>'.$fila[1].'</option>';
			}
			$strSalida.= $crlf.'<option value="-2">Otros</option>';
			
			echo $strSalida;
					
			break;
			
	}
	if (isset($tabla))
		$tabla->free();
	
	$conn->close();

?>