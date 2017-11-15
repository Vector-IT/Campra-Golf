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
	
	if (isset($_POST["NumeTour"]))
		$NumeTour = $_POST["NumeTour"];
	
	if (isset($_POST["NombImag"]))
		$NombImag = str_replace("'", "\'", $_POST["NombImag"]);
	
	$conn = new mysqli($dbhost, $dbuser, $dbpass, $db) or die("Problemas al conectar con la BD");

	switch ($operacion) {
		case 0: //INSERT
			$strSQL = "SELECT COALESCE(MAX(NumeImag), 0) + 1 NumeImag FROM toursgaleria";
			$tabla = $conn->query($strSQL);
			$fila = $tabla->fetch_array();

			if (!empty($_FILES["Imagen"])) {
				$temp = explode(".", $_FILES["Imagen"]["name"]);
				$extension = end($temp);
			
				$archivo = $NumeTour . " galeria " . $fila["NumeImag"] . "." . $extension;
				$Imagen = "imgTours/" . $archivo;
					
				subir_archivo($_FILES["Imagen"], "../imgTours", $archivo);
			
				$strSQL = "INSERT INTO toursgaleria(NumeImag, NumeTour, Imagen, NombImag)";
				$strSQL.= " VALUES({$fila["NumeImag"]}, {$NumeTour}, '{$Imagen}', '{$NombImag}')";
			
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
				$strSQL = "SELECT Imagen FROM toursgaleria WHERE NumeImag = " . $NumeImag;
				$tabla = cargarTabla($strSQL);
				$fila = $tabla->fetch_array();
				
				unlink("../" . $fila["Imagen"]);
				
				$temp = explode(".", $_FILES["Imagen"]["name"]);
				$extension = end($temp);

				$archivo = $NumeTour . " galeria " . $NumeImag . "." . $extension;
				$Imagen = "imgTours/" . $archivo;
				 
				subir_archivo($_FILES["Imagen"], "../imgTours", $archivo);
			}				
			
			$strSQL = "UPDATE toursgaleria";
			$strSQL.= " SET NombImag = '{$NombImag}'";
			
			if (!empty($_FILES["Imagen"]))
				$strSQL.= ", Imagen = '{$Imagen}'";
			
			$strSQL.= " WHERE NumeImag = " . $NumeImag;
			
			if (!$conn->query($strSQL))
				echo "Error al modificar imagen:<br />(" . $conn->errno . ") " . $conn->error . "<br />";
			else
				echo "Imagen modificada!";
			
			break;

		case 2: //DELETE
			$strSQL = "SELECT Imagen FROM toursgaleria WHERE NumeImag = " . $NumeImag;
			$tabla = $conn->query($strSQL);
			$fila = $tabla->fetch_array();
			
			unlink("../" . $fila["Imagen"]);
			
			if (isset($tabla))
				$tabla->free();
			
			$strSQL = "DELETE FROM toursgaleria WHERE NumeImag = {$NumeImag}";
			
			if (!$conn->query($strSQL))
				echo "Error al borrar imagen:<br />(" . $conn->errno . ") " . $conn->error . "<br />" . $strSQL;
			else
				echo "Imagen borrada!";

			break;

		case 10: //LISTAR
			$strSQL = "SELECT NumeImag, NombImag, Imagen";
			$strSQL.= " FROM toursgaleria";
			$strSQL.= " WHERE NumeTour = " . $NumeTour;
			$strSQL.= " ORDER BY NumeImag";

			$tabla = cargarTabla($strSQL);

			$salida = '';

			if (mysqli_num_rows($tabla) > 0) {
				$salida.= $crlf.'<table class="table table-striped table-condensed">';
				$salida.= $crlf.'<tr>';
				$salida.= $crlf.'<th>Nombre</th>';
				$salida.= $crlf.'<th>Imagen</th>';
				$salida.= $crlf.'<th></th>';
				$salida.= $crlf.'<th></th>';
				$salida.= $crlf.'</tr>';
								 
	    		while ($fila = $tabla->fetch_array()) {
	    			$salida.= $crlf.'<tr>';
	    					 
	    			//Nombre
	    			$salida.= $crlf.'<td id="NombImag'.$fila["NumeImag"].'">'.$fila["NombImag"].'</td>';
					//Imagen
					$salida.= $crlf.'<td><img id="Imagen'.$fila["NumeImag"].'" src="'.$fila["Imagen"].'" style="width: 100px; height: auto;" /></td>';
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