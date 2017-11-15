<?php
	session_start();
	
	if (!isset($_SESSION['is_logged_in'])) {
		header("Location:login.php");
		die();
	}
	
	include("conexion.php");
	include("upload_file.php");
	
	$operacion = $_POST["operacion"];
	
	if (isset($_POST["NumeImag"]))
		$NumeImag = $_POST["NumeImag"];
	
	if (isset($_POST["NumeOrde"])) {
		$NumeOrde = $_POST["NumeOrde"];
	}
	
	if (isset($_POST["NombImag"]))
		$NombImag = str_replace("'", "\'", $_POST["NombImag"]);
	
	if (isset($_POST["DescImag"]))
		$DescImag = str_replace("'", "\'", $_POST["DescImag"]);
	
	if (isset($_POST["Link"]))
		$Link = str_replace("'", "\'", $_POST["Link"]);
		
	$conn = new mysqli($dbhost, $dbuser, $dbpass, $db) or die("Problemas al conectar con la BD");

	switch ($operacion) {
		case 0: //INSERT
			$NumeOrde = buscarDato("SELECT COALESCE(MAX(NumeOrde), 0) + 1 FROM bannersimagenes");
			$strSQL = "SELECT COALESCE(MAX(NumeImag), 0) + 1 NumeImag FROM bannerimagenes";
			$tabla = $conn->query($strSQL);
			$fila = $tabla->fetch_array();

			if (!empty($_FILES["Imagen"])) {
				$temp = explode(".", $_FILES["Imagen"]["name"]);
				$extension = end($temp);

				$archivo = $fila["NumeImag"] . "." . $extension;
				$Imagen = "imgBanner/" . $archivo;
				 
				subir_archivo($_FILES["Imagen"], "../imgBanner", $archivo);
				
				$strSQL = "INSERT INTO bannerimagenes(NumeImag, NumeOrde, NombImag, Imagen, DescImag, Link)";
				$strSQL.= " VALUES({$fila["NumeImag"]}, {$NumeOrde}, '{$NombImag}', '{$Imagen}', '{$DescImag}', '{$Link}')";
	
				if (!$conn->query($strSQL))
					echo "Error al crear imagen:<br />(" . $conn->errno . ") " . $conn->error . "<br />" . $strSQL;
				else 
					echo "Imagen Creada!<br>";
			}
			else
				echo "Error! Falta imagen";

			$tabla->free();
			break;

		case 1: //UPDATE
			if (!empty($_FILES["Imagen"])) {
				$strSQL = "SELECT Imagen FROM bannerimagenes WHERE NumeImag = " . $NumeImag;
				$tabla = cargarTabla($strSQL);
				$fila = $tabla->fetch_array();
				
				unlink("../" . $fila["Imagen"]);
				
				if (isset($tabla))
					$tabla->free();
				
				$temp = explode(".", $_FILES["Imagen"]["name"]);
				$extension = end($temp);

				$archivo = $NumeImag . "." . $extension;
				$Imagen = "imgBanner/" . $archivo;
				 
				subir_archivo($_FILES["Imagen"], "../imgBanner", $archivo);
			}
			$strSQL = "UPDATE bannerimagenes";
			$strSQL.= " SET NombImag = '{$NombImag}'";
			$strSQL.= ", DescImag = '{$DescImag}'";
			$strSQL.= ", Link = '{$Link}'";
			
			if (!empty($_FILES["Imagen"])) 
				$strSQL.= ", Imagen = '{$Imagen}'";
			
			$strSQL.= " WHERE NumeImag = " . $NumeImag;
			
			if (!$conn->query($strSQL))
				echo "Error al modificar imagen:<br />(" . $conn->errno . ") " . $conn->error . "<br />";
			else 
				echo "Imagen modificada!";
			break;

		case 2: //DELETE
			$strSQL = "SELECT Imagen FROM bannerimagenes WHERE NumeImag = " . $NumeImag;
			$tabla = $conn->query($strSQL);
			$fila = $tabla->fetch_array();
				
			unlink("../" . $fila["Imagen"]);
			
			if (isset($tabla))
				$tabla->free();
			
			$strSQL = "DELETE FROM bannerimagenes WHERE NumeImag = " . $NumeImag;
			
			if (!$conn->query($strSQL))
				echo "Error al borrar imagen:<br />(" . $conn->errno . ") " . $conn->error . "<br />" . $strSQL;
			else
				echo "Imagen borrada!";

			break;

		case 3: //SUBIR ORDEN
		case 4: //BAJAR ORDEN
			$strSQL = "SELECT NumeImag";
			$strSQL.= " FROM bannerimagenes";
			$strSQL.= " WHERE NumeOrde = " . $NumeOrde;
			
			$NumeImagOld = buscarDato($strSQL);

			if ($operacion == 3) {
				//Bajo la imagen anterior
				ejecutarCMD("UPDATE bannerimagenes SET NumeOrde = " . ($NumeOrde + 1) . " WHERE NumeImag = " . $NumeImagOld);
			}
			else {
				//Subo la imagen anterior
				ejecutarCMD("UPDATE bannerimagenes SET NumeOrde = " . ($NumeOrde - 1) . " WHERE NumeImag = " . $NumeImagOld);
			}
			
			//Subo la imagen actual
			ejecutarCMD("UPDATE bannerimagenes SET NumeOrde = {$NumeOrde} WHERE NumeImag = " . $NumeImag);
			
			echo "Imagen modificada!";
			break;
			
		case 10: //LISTAR
			$strSQL = "SELECT NumeImag, NumeOrde, NombImag, DescImag, Imagen, Link";
			$strSQL.= " FROM bannerimagenes";
			$strSQL.= " ORDER BY NumeOrde";

			$tabla = cargarTabla($strSQL);

			$salida = '';

			if (mysqli_num_rows($tabla) > 0) {
				$salida.= $crlf.'<table class="table table-striped table-condensed">';
				$salida.= $crlf.'<tr>';
				$salida.= $crlf.'<th>Numero</th>';
				$salida.= $crlf.'<th>T&iacute;tulo</th>';
				$salida.= $crlf.'<th>Imagen</th>';
				$salida.= $crlf.'<th></th>';
				$salida.= $crlf.'<th></th>';
				$salida.= $crlf.'<th></th>';
				$salida.= $crlf.'</tr>';
								 
	    		while ($fila = $tabla->fetch_array()) {
    				$salida.= $crlf.'<tr>';
	    					 
					//Numero
					$salida.= $crlf.'<td id="NumeOrde'.$fila["NumeImag"].'">'.$fila["NumeOrde"].'</td>';
					//Nombre
					$salida.= $crlf.'<td id="NombImag'.$fila["NumeImag"].'">'.$fila["NombImag"].'</td>';
					//Imagen
					$salida.= $crlf.'<td><img class="thumbs" id="Imagen'.$fila["NumeImag"].'" src="'.$fila["Imagen"].'" style="width: 100px; height: auto;" /></td>';
					//Subir
					$salida.= $crlf.'<td style="text-align: center;"><button type="button" title="Subir" class="btn btn-default" onclick="subir('.$fila[0].');"><span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span></button>';
					//Bajar
					$salida.= $crlf.'<button type="button" title="Bajar" class="btn btn-default" onclick="bajar('.$fila[0].');"><span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span></button>';
					//Valores extra
					$salida.= $crlf.'<input type="hidden" id="DescImag'.$fila["NumeImag"].'" value="'.str_replace("\"", "&quot;", $fila["DescImag"]).'" />';
					$salida.= $crlf.'<input type="hidden" id="Link'.$fila["NumeImag"].'" value="'.str_replace("\"", "&quot;", $fila["Link"]).'" />';
					$salida.= $crlf.'</td>';
					//Editar
					$salida.= $crlf.'<td style="text-align: center;"><input type="button" value="Editar" onclick="editar(\''.$fila["NumeImag"].'\')" class="btn btn-info" /></td>';
					//Borrar
					$salida.= $crlf.'<td style="text-align: center;"><input type="button" value="Borrar" onclick="borrar(\''.$fila["NumeImag"].'\')" class="btn btn-danger" /></td>';
					
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