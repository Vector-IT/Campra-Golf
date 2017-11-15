<?php
	session_start();
	
	if (!isset($_SESSION['is_logged_in'])) {
		header("Location:login.php");
		die();
	}
	
	include("conexion.php");
	include("upload_file.php");
	
	$operacion = $_POST["operacion"];
	
	if (isset($_POST["NumeRuta"])) {
		$NumeRuta = $_POST["NumeRuta"];
	}
	
	if (isset($_POST["Nombre"])) {
		$Nombre = $_POST["Nombre"];
	}
	
	if (isset($_POST["Descripcion"])) {
		$Descripcion = $_POST["Descripcion"];
	}
	
	if (isset($_POST["Descripcion2"])) {
		$Descripcion2 = $_POST["Descripcion2"];
	}
	
	if (isset($_POST["Dominio"])) {
		$Dominio = $_POST["Dominio"];
	}
	
	switch ($operacion) {
		case 0: //INSERT
			$NumeRuta = buscarDato("SELECT COALESCE(MAX(NumeRuta), 0) + 1 FROM rutasmexico");

			//IMAGEN DE VISTA PREVIA
			$temp = explode(".", $_FILES["ImgPrevia"]["name"]);
			$extension = end($temp);

			$archivo = $NumeRuta. " - previa." . $extension;
			$ImgPrevia = "imgRutasMexico/" . $archivo;
			
			if (!is_dir("../imgRutasMexico")) {
				mkdir("../imgRutasMexico");
			}
			
			subir_archivo($_FILES["ImgPrevia"], "../imgRutasMexico", $archivo);
			
			//IMAGEN DE PORTADA
			$temp = explode(".", $_FILES["ImgPortada"]["name"]);
			$extension = end($temp);
			
			$archivo = $NumeRuta. " - portada." . $extension;
			$ImgPortada = "imgRutasMexico/" . $archivo;
				
			if (!is_dir("../imgRutasMexico")) {
				mkdir("../imgRutasMexico");
			}
				
			subir_archivo($_FILES["ImgPortada"], "../imgRutasMexico", $archivo);
			
			//IMAGEN DE MAPA GRANDE
			$temp = explode(".", $_FILES["ImgMapaGrande"]["name"]);
			$extension = end($temp);
			
			$archivo = $NumeRuta. " - mapagrande." . $extension;
			$ImgMapaGrande = "imgRutasMexico/" . $archivo;
				
			if (!is_dir("../imgRutasMexico")) {
				mkdir("../imgRutasMexico");
			}
				
			subir_archivo($_FILES["ImgMapaGrande"], "../imgRutasMexico", $archivo);
			
			//IMAGEN DE MAPA CHICO
			$temp = explode(".", $_FILES["ImgMapaChico"]["name"]);
			$extension = end($temp);
			
			$archivo = $NumeRuta. " - mapachico." . $extension;
			$ImgMapaChico = "imgRutasMexico/" . $archivo;
				
			if (!is_dir("../imgRutasMexico")) {
				mkdir("../imgRutasMexico");
			}
				
			subir_archivo($_FILES["ImgMapaChico"], "../imgRutasMexico", $archivo);
			
			//IMAGEN DE REFERENCIAS
			$temp = explode(".", $_FILES["ImgReferencias"]["name"]);
			$extension = end($temp);
			
			$archivo = $NumeRuta. " - referencias." . $extension;
			$ImgReferencias = "imgRutasMexico/" . $archivo;
				
			if (!is_dir("../imgRutasMexico")) {
				mkdir("../imgRutasMexico");
			}
				
			subir_archivo($_FILES["ImgReferencias"], "../imgRutasMexico", $archivo);
			
			$strSQL = "INSERT INTO rutasmexico(NumeRuta, Nombre, Descripcion, Descripcion2, Dominio, ImgPrevia, ImgPortada, ImgMapaGrande, ImgMapaChico, ImgReferencias)";
			$strSQL.= " VALUES({$NumeRuta}, '{$Nombre}', '{$Descripcion}', '{$Descripcion2}', '{$Dominio}', '{$ImgPrevia}', '{$ImgPortada}', '{$ImgMapaGrande}', '{$ImgMapaChico}', '{$ImgReferencias}')";

			$resultado = ejecutarCMD($strSQL);
			if (!$resultado)
				echo "Error al crear ruta:<br />" . $resultado . "<br />" . $strSQL;
			else 
				echo "Ruta Creada!<br>";

			break;

		case 1: //UPDATE
			//IMAGEN DE VISTA PREVIA
			if (!empty($_FILES["ImgPrevia"])) {
				$ImgPrevia = buscarDato("SELECT ImgPrevia FROM rutasmexico WHERE NumeRuta = " . $NumeRuta);
				
				unlink("../" . $ImgPrevia);
				
				$temp = explode(".", $_FILES["ImgPrevia"]["name"]);
				$extension = end($temp);

				$archivo = $NumeRuta . " - previa." . $extension;
				$ImgPrevia = "imgRutasMexico/" . $archivo;
				 
				subir_archivo($_FILES["ImgPrevia"], "../imgRutasMexico", $archivo);
			}
			
			//IMAGEN DE PORTADA
			if (!empty($_FILES["ImgPortada"])) {
				$ImgPortada = buscarDato("SELECT ImgPortada FROM rutasmexico WHERE NumeRuta = " . $NumeRuta);
				
				unlink("../" . $ImgPortada);
				
				$temp = explode(".", $_FILES["ImgPortada"]["name"]);
				$extension = end($temp);

				$archivo = $NumeRuta . " - portada." . $extension;
				$ImgPortada = "imgRutasMexico/" . $archivo;
				 
				subir_archivo($_FILES["ImgPortada"], "../imgRutasMexico", $archivo);
			}
			
			//IMAGEN DE MAPA GRANDE
			if (!empty($_FILES["ImgMapaGrande"])) {
				$ImgMapaGrande = buscarDato("SELECT ImgMapaGrande FROM rutasmexico WHERE NumeRuta = " . $NumeRuta);
				
				unlink("../" . $ImgMapaGrande);
				
				$temp = explode(".", $_FILES["ImgMapaGrande"]["name"]);
				$extension = end($temp);

				$archivo = $NumeRuta . " - mapagrande." . $extension;
				$ImgMapaGrande = "imgRutasMexico/" . $archivo;
				 
				subir_archivo($_FILES["ImgMapaGrande"], "../imgRutasMexico", $archivo);
			}
			
			//IMAGEN DE MAPA CHICO
			if (!empty($_FILES["ImgMapaChico"])) {
				$ImgMapaChico = buscarDato("SELECT ImgMapaChico FROM rutasmexico WHERE NumeRuta = " . $NumeRuta);
				
				unlink("../" . $ImgPrevia);
				
				$temp = explode(".", $_FILES["ImgMapaChico"]["name"]);
				$extension = end($temp);

				$archivo = $NumeRuta . " - mapachico." . $extension;
				$ImgMapaChico = "imgRutasMexico/" . $archivo;
				 
				subir_archivo($_FILES["ImgMapaChico"], "../imgRutasMexico", $archivo);
			}
			
			//IMAGEN DE REFERENCIAS
			if (!empty($_FILES["ImgReferencias"])) {
				$temp = explode(".", $_FILES["ImgReferencias"]["name"]);
				$extension = end($temp);
					
				$archivo = $NumeRuta. " - referencias." . $extension;
				$ImgReferencias = "imgRutasMexico/" . $archivo;
				
				if (!is_dir("../imgRutasMexico")) {
					mkdir("../imgRutasMexico");
				}
				
				subir_archivo($_FILES["ImgReferencias"], "../imgRutasMexico", $archivo);
			}
			
			$strSQL = "UPDATE rutasmexico";
			$strSQL.= " SET Nombre = '{$Nombre}'";
			$strSQL.= ", Descripcion = '{$Descripcion}'";
			$strSQL.= ", Descripcion2 = '{$Descripcion2}'";
			$strSQL.= ", Dominio = '{$Dominio}'";
			if (!empty($_FILES["ImgPrevia"])) {
				$strSQL.= ", ImgPrevia = '{$ImgPrevia}'";
			}
			if (!empty($_FILES["ImgPortada"])) {
				$strSQL.= ", ImgPortada = '{$ImgPortada}'";
			}
			if (!empty($_FILES["ImgMapaGrande"])) {
				$strSQL.= ", ImgMapaGrande = '{$ImgMapaGrande}'";
			}
			if (!empty($_FILES["ImgMapaChico"])) {
				$strSQL.= ", ImgMapaChico = '{$ImgMapaChico}'";
			}
			if (!empty($_FILES["ImgReferencias"])) {
				$strSQL.= ", ImgReferencias = '{$ImgReferencias}'";
			}
			$strSQL.= " WHERE NumeRuta = " . $NumeRuta;
			
			$resultado = ejecutarCMD($strSQL);
			if (!$resultado)
				echo "Error al modificar ruta:<br />" . $resultado . "<br />" . $strSQL;
			else 
				echo "Ruta Modificada!<br>";

			break;

		case 2: //DELETE
			$ImgPrevia = buscarDato("SELECT ImgPrevia FROM rutasmexico WHERE NumeRuta = " . $NumeRuta);
				
			unlink("../" . $ImgPrevia);
			
			$ImgPortada = buscarDato("SELECT ImgPortada FROM rutasmexico WHERE NumeRuta = " . $NumeRuta);
				
			unlink("../" . $ImgPortada);
			
			$ImgMapaGrande = buscarDato("SELECT ImgMapaGrande FROM rutasmexico WHERE NumeRuta = " . $NumeRuta);
				
			unlink("../" . $ImgMapaGrande);
			
			$ImgMapaChico = buscarDato("SELECT ImgMapaChico FROM rutasmexico WHERE NumeRuta = " . $NumeRuta);
				
			unlink("../" . $ImgMapaChico);
			
			
			$strSQL = "DELETE FROM rutasmexico WHERE NumeRuta = " . $NumeRuta;
			
			$resultado = ejecutarCMD($strSQL);
			if (!$resultado)
				echo "Error al borrar ruta:<br />(" . $resultado . "<br />" . $strSQL;
			else
				echo "Ruta borrada!";

			break;

		case 10: //LISTAR
			$strSQL = "SELECT NumeRuta, Nombre, Descripcion, Descripcion2, Dominio, ImgPrevia, ImgPortada, ImgMapaGrande, ImgMapaChico, ImgReferencias";
			$strSQL.= " FROM rutasmexico";
			$strSQL.= " ORDER BY NumeRuta";

			$tabla = cargarTabla($strSQL);

			$salida = '';

			if (mysqli_num_rows($tabla) > 0) {
				$salida.= $crlf.'<table class="table table-striped table-condensed">';
				$salida.= $crlf.'<tr>';
				$salida.= $crlf.'<th>Numero</th>';
				$salida.= $crlf.'<th>Nombre</th>';
				$salida.= $crlf.'<th>Previa</th>';
				$salida.= $crlf.'<th></th>';
				$salida.= $crlf.'<th></th>';
				$salida.= $crlf.'<th></th>';
				$salida.= $crlf.'</tr>';
								 
	    		while ($fila = $tabla->fetch_array()) {
    				$salida.= $crlf.'<tr>';
	    					 
					//Numero
					$salida.= $crlf.'<td id="NumeRuta'.$fila[0].'">'.$fila[0].'</td>';
					//Nombre
					$salida.= $crlf.'<td id="Nombre'.$fila[0].'">'.$fila["Nombre"].'</td>';
					//Imagen previa
					$salida.= $crlf.'<td><img class="thumbs" src="'.$fila["ImgPrevia"].'" style="width: 100px; height: auto;" /></td>';
					//Botones y datos adicionales
					$salida.= $crlf.'<td style="text-align: center;">';
					$salida.= $crlf.'<button type="button" title="Galer&iacute;a" class="btn btn-default" onclick="abrirGaleria('.$fila[0].');">Galer&iacute;a</button>';
					$salida.= $crlf.'<button type="button" title="Itinerarios" class="btn btn-default" onclick="abrirItinerarios('.$fila[0].');">Itinierarios</button>';
					
					$salida.= $crlf.'<input type="hidden" id="Descripcion'.$fila[0].'" value="'.str_replace("\"", "&quot;", $fila["Descripcion"]).'" />';
					$salida.= $crlf.'<input type="hidden" id="DescripcionDos'.$fila[0].'" value="'.str_replace("\"", "&quot;", $fila["Descripcion2"]).'" />';
					$salida.= $crlf.'<input type="hidden" id="Dominio'.$fila[0].'" value="'.$fila["Dominio"].'" />';
					$salida.= $crlf.'<input type="hidden" id="ImgPrevia'.$fila[0].'" value="'.$fila["ImgPrevia"].'" />';
					$salida.= $crlf.'<input type="hidden" id="ImgPortada'.$fila[0].'" value="'.$fila["ImgPortada"].'" />';
					$salida.= $crlf.'<input type="hidden" id="ImgMapaGrande'.$fila[0].'" value="'.$fila["ImgMapaGrande"].'" />';
					$salida.= $crlf.'<input type="hidden" id="ImgMapaChico'.$fila[0].'" value="'.$fila["ImgMapaChico"].'" />';
					$salida.= $crlf.'<input type="hidden" id="ImgReferencias'.$fila[0].'" value="'.$fila["ImgReferencias"].'" />';
					$salida.= $crlf.'</td>';
					//Editar
					$salida.= $crlf.'<td style="text-align: center;"><input type="button" value="Editar" onclick="editar(\''.$fila[0].'\')" class="btn btn-info" /></td>';
					//Borrar
					$salida.= $crlf.'<td style="text-align: center;"><input type="button" value="Borrar" onclick="borrar(\''.$fila[0].'\')" class="btn btn-danger" /></td>';
					
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