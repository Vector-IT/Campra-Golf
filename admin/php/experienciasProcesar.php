<?php
	session_start();
	
	if (!isset($_SESSION['is_logged_in'])) {
		header("Location:login.php");
		die();
	}
	
	include("conexion.php");
	include("upload_file.php");
	
	$operacion = $_POST["operacion"];
	
	if (isset($_POST["NumeExpe"]))
		$NumeExpe = $_POST["NumeExpe"];
	
	if (isset($_POST["NombExpe"]))
		$NombExpe = str_replace("'", "\'", $_POST["NombExpe"]);
	
	if (isset($_POST["Dominio"]))
		$Dominio = str_replace(" ", "-", trim(str_replace("'", "\'", $_POST["Dominio"])));

	if (isset($_POST["Codigo"]))
		$Codigo = str_replace("'", "\'", $_POST["Codigo"]);
	
	if (isset($_POST["DescExpe"]))
		$DescExpe = str_replace("'", "\'", $_POST["DescExpe"]);
	
	if (isset($_POST["NumeEsta"]))
		$NumeEsta = str_replace("'", "\'", $_POST["NumeEsta"]);

	if (isset($_POST["NumeOrde"])) {
		$NumeOrde = $_POST["NumeOrde"];
	}
	
	$conn = new mysqli($dbhost, $dbuser, $dbpass, $db) or die("Problemas al conectar con la BD");

	switch ($operacion) {
		case 0: //INSERT
			$NumeExpe = buscarDato("SELECT COALESCE(MAX(NumeExpe), 0) + 1 NumeExpe FROM experiencias");
			$NumeOrde = buscarDato("SELECT COALESCE(MAX(NumeOrde), 0) + 1 FROM experiencias");

			if (empty($_FILES["FotoBanner"])) {
				echo "Error! Falta imagen de banner";
				die();
			}
			if (empty($_FILES["FotoPortada"])) {
				echo "Error! Falta imagen de portada";
				die();
			}
			
			//Foto de banner
			$temp = explode(".", $_FILES["FotoBanner"]["name"]);
			$extension = end($temp);

			$archivo = $NumeExpe . " - banner." . $extension;
			$FotoBanner = "imgExperiencias/" . $archivo;
			 
			subir_archivo($_FILES["FotoBanner"], "../imgExperiencias", $archivo);
			
			//Foto de portada
			$temp = explode(".", $_FILES["FotoPortada"]["name"]);
			$extension = end($temp);

			$archivo = $NumeExpe . " - portada." . $extension;
			$FotoPortada = "imgExperiencias/" . $archivo;
			 
			subir_archivo($_FILES["FotoPortada"], "../imgExperiencias", $archivo);
			
			//INSERT
			$strSQL = "INSERT INTO experiencias(NumeExpe, NumeOrde, NombExpe, Dominio, DescExpe, FotoBanner, FotoPortada, NumeEsta)";
			$strSQL.= " VALUES({$NumeExpe}, {$NumeOrde}, '{$NombExpe}', '{$Dominio}', '{$DescExpe}', '{$FotoBanner}', '{$FotoPortada}', {$NumeEsta})";

			if (!$conn->query($strSQL))
				echo "Error al crear unidad de negocio:<br />(" . $conn->errno . ") " . $conn->error . "<br />" . $strSQL;
			else
				echo "Unidad de Negocio Creada!<br>";

			break;

		case 1: //UPDATE
			$strSQL = "SELECT FotoBanner, FotoPortada FROM experiencias WHERE NumeExpe = " . $NumeExpe;
			$tabla = cargarTabla($strSQL);
			$fila = $tabla->fetch_array();

			if (!empty($_FILES["FotoBanner"])) {
				unlink("../" . $fila["FotoBanner"]);
				
				$temp = explode(".", $_FILES["FotoBanner"]["name"]);
				$extension = end($temp);

				$archivo = $NumeExpe . " - banner." . $extension;
				$FotoBanner = "imgExperiencias/" . $archivo;
				 
				subir_archivo($_FILES["FotoBanner"], "../imgExperiencias", $archivo);
			}
				
			if (!empty($_FILES["FotoPortada"])) {
				unlink("../" . $fila["FotoPortada"]);
				
				$temp = explode(".", $_FILES["FotoPortada"]["name"]);
				$extension = end($temp);

				$archivo = $NumeExpe . " - portada." . $extension;
				$FotoPortada = "imgExperiencias/" . $archivo;
				 
				subir_archivo($_FILES["FotoPortada"], "../imgExperiencias", $archivo);
			}
			
			$strSQL = "UPDATE experiencias";
			$strSQL.= " SET NombExpe = '{$NombExpe}'";
			$strSQL.= ", Dominio = '{$Dominio}'";
			$strSQL.= ", DescExpe = '{$DescExpe}'";
			$strSQL.= ", NumeEsta = '{$NumeEsta}'";
				
			if (!empty($_FILES["FotoBanner"]))
				$strSQL.= ", FotoBanner = '{$FotoBanner}'";
			
			if (!empty($_FILES["FotoPortada"]))
				$strSQL.= ", FotoPortada = '{$FotoPortada}'";
			
			$strSQL.= " WHERE NumeExpe = " . $NumeExpe;

			if (!$conn->query($strSQL))
				echo "Error al modificar unidad de negocio:<br />(" . $conn->errno . ") " . $conn->error . "<br />";
			else
				echo "Unidad de Negocio modificada!";
			
			break;

		case 2: //DELETE
			$strSQL = "SELECT FotoBanner, FotoPortada FROM experiencias WHERE NumeExpe = " . $NumeExpe;
			$tabla = cargarTabla($strSQL);
			$fila = $tabla->fetch_array();
			
			unlink("../" . $fila["FotoBanner"]);
			unlink("../" . $fila["FotoPortada"]);
			
			$strSQL = "DELETE FROM experiencias WHERE NumeExpe = " . $NumeExpe;
			
			if (!$conn->query($strSQL))
				echo "Error al borrar unidad de negocio:<br />(" . $conn->errno . ") " . $conn->error . "<br />" . $strSQL;
			else
				echo "Unidad de Negocio borrada!";

			break;

		case 3: //SUBIR ORDEN
		case 4: //BAJAR ORDEN
			$strSQL = "SELECT NumeExpe";
			$strSQL.= " FROM experiencias";
			$strSQL.= " WHERE NumeOrde = " . $NumeOrde;
				
			$NumeExpeOld = buscarDato($strSQL);
		
			if ($operacion == 3) {
				//Bajo la imagen anterior
				ejecutarCMD("UPDATE experiencias SET NumeOrde = " . ($NumeOrde + 1) . " WHERE NumeExpe = " . $NumeExpeOld);
			}
			else {
				//Subo la imagen anterior
				ejecutarCMD("UPDATE experiencias SET NumeOrde = " . ($NumeOrde - 1) . " WHERE NumeExpe = " . $NumeExpeOld);
			}
				
			//Subo la imagen actual
			ejecutarCMD("UPDATE experiencias SET NumeOrde = {$NumeOrde} WHERE NumeExpe = " . $NumeExpe);
				
			echo "Imagen modificada!";
			break;
			
		case 10: //LISTAR
			$strSQL = "SELECT NumeOrde, NumeExpe, NombExpe, Dominio, DescExpe, FotoBanner, FotoPortada, NumeEsta";
			$strSQL.= " FROM experiencias";
			$strSQL.= " ORDER BY NumeOrde";

			$tabla = cargarTabla($strSQL);

			$salida = '';

			if (mysqli_num_rows($tabla) > 0) {
				$salida.= $crlf.'<table id="tablaExperiencias" class="table table-striped table-condensed">';
				$salida.= $crlf.'<tr>';
				$salida.= $crlf.'<th>Numero</th>';
				$salida.= $crlf.'<th>Nombre</th>';
				$salida.= $crlf.'<th>Banner</th>';
				$salida.= $crlf.'<th>Estado</th>';
				$salida.= $crlf.'<th></th>';
				$salida.= $crlf.'<th></th>';
				$salida.= $crlf.'<th></th>';
				$salida.= $crlf.'</tr>';
								 
	    		while ($fila = $tabla->fetch_array()) {
    				$salida.= $crlf.'<tr>';
	    					 
					//Numero
    				$salida.= $crlf.'<td id="NumeOrde'.$fila["NumeExpe"].'">'.$fila["NumeOrde"].'</td>';
					//$salida.= $crlf.'<td id="NumeExpe'.$fila["NumeExpe"].'">'.$fila["NumeExpe"];
					$salida.= $crlf.'<input type="hidden" id="Dominio'.$fila["NumeExpe"].'" value="'.str_replace("\"", "&quot;", $fila["Dominio"]).'" />';
					$salida.= $crlf.'<input type="hidden" id="DescExpe'.$fila["NumeExpe"].'" value="'.str_replace("\"", "&quot;", $fila["DescExpe"]).'" />';
					$salida.= $crlf.'<input type="hidden" id="FotoPortada'.$fila["NumeExpe"].'" value="'.$fila["FotoPortada"].'" />';
					$salida.= $crlf.'<input type="hidden" id="NumeEsta'.$fila["NumeExpe"].'" value="'.$fila["NumeEsta"].'" />';
					$salida.= $crlf.'</td>';
					//Nombre
					$salida.= $crlf.'<td id="NombExpe'.$fila["NumeExpe"].'">'.$fila["NombExpe"].'</td>';
					//Imagen Banner
					$salida.= $crlf.'<td><img class="thumbs" id="FotoBanner'.$fila["NumeExpe"].'" src="'.$fila["FotoBanner"].'" style="width: 100px; height: auto;" /></td>';
					//Estado
					if ($fila["NumeEsta"] == 1)
						$salida.= $crlf.'<td>Activo</td>';
					else
						$salida.= $crlf.'<td>Inactivo</td>';
					//Subir
					$salida.= $crlf.'<td style="text-align: center;"><button type="button" title="Subir" class="btn btn-default" onclick="subir('.$fila["NumeExpe"].');"><span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span></button>';
					//Bajar
					$salida.= $crlf.'<button type="button" title="Bajar" class="btn btn-default" onclick="bajar('.$fila["NumeExpe"].');"><span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span></button>';
					$salida.= $crlf.'</td>';
					
					//Editar
					$salida.= $crlf.'<td style="text-align: center;"><input type="button" value="Editar" onclick="editar(\''.$fila["NumeExpe"].'\')" class="btn btn-info" /></td>';
					//Borrar
					$salida.= $crlf.'<td style="text-align: center;"><input type="button" value="Borrar" onclick="borrar(\''.$fila["NumeExpe"].'\')" class="btn btn-danger" /></td>';
					
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