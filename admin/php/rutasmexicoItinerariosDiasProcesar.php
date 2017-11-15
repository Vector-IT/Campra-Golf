<?php
	session_start();
	
	if (!isset($_SESSION['is_logged_in'])) {
		header("Location:login.php");
		die();
	}
	
	include("conexion.php");
	include("upload_file.php");
	
	$operacion = $_POST["operacion"];
	
	if (isset($_POST["NumeDia"])) {
		$NumeDia = $_POST["NumeDia"];
	}
	
	if (isset($_POST["NumeRuta"])) {
		$NumeRuta = $_POST["NumeRuta"];
	}
	
	if (isset($_POST["Ciudades"])) {
		$Ciudades = $_POST["Ciudades"];
	}
	
	if (isset($_POST["Descripcion"])) {
		$Descripcion = $_POST["Descripcion"];
	}
	
	switch ($operacion) {
		case 0: //INSERT
			$NumeDia = buscarDato("SELECT COALESCE(MAX(NumeDia), 0) + 1  FROM rutasmexicodias");

			if (!empty($_FILES["Imagen"])) {
				$temp = explode(".", $_FILES["Imagen"]["name"]);
				$extension = end($temp);

				$archivo = $NumeDia. "." . $extension;
				$Imagen = "imgRutasMexicoDias/" . $archivo;
				
				if (!is_dir("../imgRutasMexicoDias")) {
					mkdir("../imgRutasMexicoDias");
				}
				
				subir_archivo($_FILES["Imagen"], "../imgRutasMexicoDias", $archivo);
			}
			else {
				$Imagen = "";
			}

			$strSQL = "INSERT INTO rutasmexicodias(NumeDia, NumeRuta, Ciudades, Descripcion, Imagen)";
			$strSQL.= " VALUES({$NumeDia}, {$NumeRuta}, '{$Ciudades}', '{$Descripcion}', '{$Imagen}')";

			$resultado = ejecutarCMD($strSQL);
			if (!$resultado)
				echo "Error al crear d&iacute;a:<br />" . $resultado . "<br />" . $strSQL;
			else 
				echo "D&iacute;a Creado!<br>";

			break;

		case 1: //UPDATE
			if (!empty($_FILES["Imagen"])) {
				$Imagen = buscarDato("SELECT Imagen FROM rutasmexicodias WHERE NumeDia = " . $NumeDia);
				
				unlink("../" . $Imagen);
				
				$temp = explode(".", $_FILES["Imagen"]["name"]);
				$extension = end($temp);

				$archivo = $NumeDia. "." . $extension;
				$Imagen = "imgRutasMexicoDias/" . $archivo;
				
				if (!is_dir("../imgRutasMexicoDias")) {
					mkdir("../imgRutasMexicoDias");
				}
				
				subir_archivo($_FILES["Imagen"], "../imgRutasMexicoDias", $archivo);
			}
			
			$strSQL = "UPDATE rutasmexicodias";
			$strSQL.= " SET Ciudades = '{$Ciudades}'";
			$strSQL.= ", Descripcion = '{$Descripcion}'";
			if (!empty($_FILES["Imagen"])) {
				$strSQL.= ", Imagen = '{$Imagen}'";
			}
			$strSQL.= " WHERE NumeDia = " . $NumeDia;
				
			$resultado = ejecutarCMD($strSQL);
			if (!$resultado)
				echo "Error al modificar d&iacute;a:<br />" . $resultado . "<br />" . $strSQL;
			else 
				echo "D&iacute;a Modificado!<br>";

			break;

		case 2: //DELETE
			$Imagen = buscarDato("SELECT Imagen FROM rutasmexicodias WHERE NumeDia = " . $NumeDia);
				
			unlink("../" . $Imagen);
			
			$strSQL = "DELETE FROM rutasmexicodias WHERE NumeDia = " . $NumeDia;
			
			$resultado = ejecutarCMD($strSQL);
			if (!$resultado)
				echo "Error al borrar d&iacute;a:<br />(" . $resultado . "<br />" . $strSQL;
			else
				echo "D&iacute;a borrado!";

			break;

		case 10: //LISTAR
			$strSQL = "SELECT NumeDia, Ciudades, Descripcion, Imagen";
			$strSQL.= " FROM rutasmexicodias";
			$strSQL.= " WHERE NumeRuta = " . $NumeRuta;
			$strSQL.= " ORDER BY NumeDia";

			$tabla = cargarTabla($strSQL);

			$salida = '';

			if (mysqli_num_rows($tabla) > 0) {
				$salida.= $crlf.'<table class="table table-striped table-condensed">';
				$salida.= $crlf.'<tr>';
				$salida.= $crlf.'<th>Numero</th>';
				$salida.= $crlf.'<th>Ciudades</th>';
				$salida.= $crlf.'<th>Imagen</th>';
				$salida.= $crlf.'<th></th>';
				$salida.= $crlf.'<th></th>';
				$salida.= $crlf.'</tr>';
								 
	    		while ($fila = $tabla->fetch_array()) {
    				$salida.= $crlf.'<tr>';
	    					 
					//Numero
					$salida.= $crlf.'<td id="NumeDia'.$fila[0].'">'.$fila["NumeDia"];
					//Descripcion
					$salida.= $crlf.'<input type="hidden" id="Descripcion'.$fila[0].'" value="'.str_replace("\"", "&quot;", $fila["Descripcion"]).'" />'; 
					$salida.= $crlf.'</td>';
					//Ciudades
					$salida.= $crlf.'<td id="Ciudades'.$fila[0].'">'.$fila["Ciudades"].'</td>';
					//Imagen
					$salida.= $crlf.'<td><img class="thumbs" id="Imagen'.$fila["NumeDia"].'" src="'.$fila["Imagen"].'" style="width: 100px; height: auto;" /></td>';
					//Editar
					$salida.= $crlf.'<td style="text-align: center;"><input type="button" value="Editar" onclick="editar(\''.$fila["NumeDia"].'\')" class="btn btn-info" /></td>';
					//Borrar
					$salida.= $crlf.'<td style="text-align: center;"><input type="button" value="Borrar" onclick="borrar(\''.$fila["NumeDia"].'\')" class="btn btn-danger" /></td>';
					
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