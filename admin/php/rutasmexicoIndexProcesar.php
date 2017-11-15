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
	
	switch ($operacion) {
		case 0: //INSERT
			$NumeOrde = buscarDato("SELECT COALESCE(MAX(NumeOrde), 0) + 1 FROM rutasmexicobanner");
			$NumeImag = buscarDato("SELECT COALESCE(MAX(NumeImag), 0) + 1 NumeImag FROM rutasmexicobanner");

			if (!empty($_FILES["Imagen"])) {
				$temp = explode(".", $_FILES["Imagen"]["name"]);
				$extension = end($temp);

				$archivo = $NumeImag . "." . $extension;
				$Imagen = "imgRutasMexicoBanner/" . $archivo;
				
				if (!is_dir("../imgRutasMexicoBanner")) {
					mkdir("../imgRutasMexicoBanner");
				}
				
				subir_archivo($_FILES["Imagen"], "../imgRutasMexicoBanner", $archivo);
				
				$strSQL = "INSERT INTO rutasmexicobanner(NumeImag, NumeOrde, Imagen)";
				$strSQL.= " VALUES({$NumeImag}, {$NumeOrde}, '{$Imagen}')";
	
				$resultado = ejecutarCMD($strSQL);
				if (!$resultado)
					echo "Error al crear imagen:<br />" . $resultado . "<br />" . $strSQL;
				else 
					echo "Imagen Creada!<br>";
			}
			else
				echo "Error! Falta imagen";

			break;

		case 1: //UPDATE
			if (!empty($_FILES["Imagen"])) {
				$Imagen = buscarDato("SELECT Imagen FROM rutasmexicobanner WHERE NumeImag = " . $NumeImag);
				
				unlink("../" . $Imagen);
				
				$temp = explode(".", $_FILES["Imagen"]["name"]);
				$extension = end($temp);

				$archivo = $NumeImag . "." . $extension;
				$Imagen = "imgRutasMexicoBanner/" . $archivo;
				 
				subir_archivo($_FILES["Imagen"], "../imgRutasMexicoBanner", $archivo);

				$strSQL = "UPDATE rutasmexicobanner";
				$strSQL.= " SET Imagen = '{$Imagen}'";
				$strSQL.= " WHERE NumeImag = " . $NumeImag;
				
				$resultado = ejecutarCMD($strSQL);
				if (!$resultado)
					echo "Error al modificar imagen:<br />" . $resultado . "<br />" . $strSQL;
				else 
					echo "Imagen Modificada!<br>";
			}
			else {
				echo "Imagen Modificada!<br>";
			}
			break;

		case 2: //DELETE
			$Imagen = buscarDato("SELECT Imagen FROM rutasmexicobanner WHERE NumeImag = " . $NumeImag);
				
			unlink("../" . $Imagen);
			
			$strSQL = "DELETE FROM rutasmexicobanner WHERE NumeImag = " . $NumeImag;
			
			$resultado = ejecutarCMD($strSQL);
			if (!$resultado)
				echo "Error al borrar imagen:<br />(" . $resultado . "<br />" . $strSQL;
			else
				echo "Imagen borrada!";

			break;

		case 3: //SUBIR ORDEN
		case 4: //BAJAR ORDEN
			$strSQL = "SELECT NumeImag";
			$strSQL.= " FROM rutasmexicobanner";
			$strSQL.= " WHERE NumeOrde = " . $NumeOrde;
			
			$NumeImagOld = buscarDato($strSQL);

			if ($operacion == 3) {
				//Bajo la imagen anterior
				ejecutarCMD("UPDATE rutasmexicobanner SET NumeOrde = " . ($NumeOrde + 1) . " WHERE NumeImag = " . $NumeImagOld);
			}
			else {
				//Subo la imagen anterior
				ejecutarCMD("UPDATE rutasmexicobanner SET NumeOrde = " . ($NumeOrde - 1) . " WHERE NumeImag = " . $NumeImagOld);
			}
			
			//Subo la imagen actual
			ejecutarCMD("UPDATE rutasmexicobanner SET NumeOrde = {$NumeOrde} WHERE NumeImag = " . $NumeImag);
			
			echo "Imagen modificada!";
			break;
			
		case 10: //LISTAR
			$strSQL = "SELECT NumeImag, NumeOrde, Imagen";
			$strSQL.= " FROM rutasmexicobanner";
			$strSQL.= " ORDER BY NumeOrde";

			$tabla = cargarTabla($strSQL);

			$salida = '';

			if (mysqli_num_rows($tabla) > 0) {
				$salida.= $crlf.'<table class="table table-striped table-condensed">';
				$salida.= $crlf.'<tr>';
				$salida.= $crlf.'<th>Numero</th>';
				$salida.= $crlf.'<th>Imagen</th>';
				$salida.= $crlf.'<th></th>';
				$salida.= $crlf.'<th></th>';
				$salida.= $crlf.'<th></th>';
				$salida.= $crlf.'</tr>';
								 
	    		while ($fila = $tabla->fetch_array()) {
    				$salida.= $crlf.'<tr>';
	    					 
					//Numero
					$salida.= $crlf.'<td id="NumeOrde'.$fila["NumeImag"].'">'.$fila["NumeOrde"].'</td>';
					//Imagen
					$salida.= $crlf.'<td><img class="thumbs" id="Imagen'.$fila["NumeImag"].'" src="'.$fila["Imagen"].'" style="width: 100px; height: auto;" /></td>';
					//Subir
					$salida.= $crlf.'<td style="text-align: center;"><button type="button" title="Subir" class="btn btn-default" onclick="subir('.$fila[0].');"><span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span></button>';
					//Bajar
					$salida.= $crlf.'<button type="button" title="Bajar" class="btn btn-default" onclick="bajar('.$fila[0].');"><span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span></button>';
					$salida.= $crlf.'</td>';
					//Editar
					$salida.= $crlf.'<td style="text-align: center;"><input type="button" value="Editar" onclick="editar(\''.$fila["NumeImag"].'\')" class="btn btn-info" /></td>';
					//Borrar
					$salida.= $crlf.'<td style="text-align: center;"><input type="button" value="Borrar" onclick="borrar(\''.$fila["NumeImag"].'\')" class="btn btn-danger" /></td>';
					
					$salida.= $crlf.'</tr>';
				}
				
				$salida.= $crlf.'</table>';
				$tabla->free();				
	    	}
	    	else {
	    		$salida.= "<h3>Sin datos para mostrar</h3>";
	    	}
	    	
	    	echo $salida;
	
	    	break;
	}
	
?>