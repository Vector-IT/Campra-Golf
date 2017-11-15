<?php
	session_start();
	
	if (!isset($_SESSION['is_logged_in'])) {
		header("Location:login.php");
		die();
	}
	
	include("conexion.php");
	include("upload_file.php");
	
	$operacion = $_POST["operacion"];
	
	if (isset($_POST["NumeBlog"]))
		$NumeBlog = $_POST["NumeBlog"];
	
	if (isset($_POST["Titulo"]))
		$Titulo = str_replace("'", "\'", $_POST["Titulo"]);
	
	if (isset($_POST["Dominio"]))
		$Dominio = str_replace(" ", "-", trim(str_replace("'", "\'", $_POST["Dominio"])));
	
	if (isset($_POST["Experiencias"]))
		$Experiencias = explode("@#@", $_POST["Experiencias"]);
	
	if (isset($_POST["Copete"]))
		$Copete = str_replace("'", "\'", $_POST["Copete"]);
	
	if (isset($_POST["Descripcion"]))
		$Descripcion = str_replace("'", "\'", $_POST["Descripcion"]);
	
	if (isset($_POST["Etiquetas"]))
		$Etiquetas = str_replace("'", "\'", $_POST["Etiquetas"]);
	
	$conn = new mysqli($dbhost, $dbuser, $dbpass, $db) or die("Problemas al conectar con la BD");

	switch ($operacion) {
		case 0: //INSERT
			$strSQL = "SELECT COALESCE(MAX(NumeBlog), 0) + 1 NumeBlog FROM blog";
			$tabla = $conn->query($strSQL);
			$fila = $tabla->fetch_array();

			if (!empty($_FILES["Imagen"])) {
				$temp = explode(".", $_FILES["Imagen"]["name"]);
				$extension = end($temp);
			
				$archivo = "previa - " . $fila["NumeBlog"] . "." . $extension;
				$Imagen = "imgBlog/" . $archivo;
					
				subir_archivo($_FILES["Imagen"], "../imgBlog", $archivo);
				
				$strSQL = "INSERT INTO blog(NumeBlog, Titulo, Dominio, Imagen, Copete, Descripcion, Fecha, Etiquetas)";
				$strSQL.= " VALUES({$fila["NumeBlog"]}, '{$Titulo}', '{$Dominio}', '{$Imagen}', '{$Copete}', '{$Descripcion}', DATE_FORMAT(SYSDATE(), '%Y-%m-%d'), '{$Etiquetas}')";
	
				if (!$conn->query($strSQL))
					echo "Error al crear art&iacute;culo:<br />(" . $conn->errno . ") " . $conn->error . "<br />" . $strSQL;
				else {
					$error = "";
					
					for ($I = 0; $I < count($Experiencias); $I++) {
						$strSQL = "INSERT INTO blogexperiencias(NumeBlog, NumeExpe)";
						$strSQL.= "VALUES({$fila["NumeBlog"]}, {$Experiencias[$I]})";
							
						if (!$conn->query($strSQL)) {
							$error = "Error al crear art&iacute;culo:<br />(" . $conn->errno . ") " . $conn->error . "<br />" . $strSQL;
							break;
						}
					}
					
					if ($error == "")
						echo "Art&iacute;culo Creado!<br>";
					else
						echo $error;
				}
			}
			else 
				echo "Error! Falta imagen";

			$tabla->free();
			break;

		case 1: //UPDATE
			if (!empty($_FILES["Imagen"])) {
				$strSQL = "SELECT Imagen FROM blog WHERE NumeBlog = " . $NumeBlog;
				$tabla = cargarTabla($strSQL);
				$fila = $tabla->fetch_array();
			
				unlink("../" . $fila["Imagen"]);
			
				$temp = explode(".", $_FILES["Imagen"]["name"]);
				$extension = end($temp);
			
				$archivo = "previa - " . $NumeBlog . "." . $extension;
				$Imagen = "imgBlog/" . $archivo;
					
				subir_archivo($_FILES["Imagen"], "../imgBlog", $archivo);
			}
			
			$strSQL = "UPDATE blog";
			$strSQL.= " SET Titulo = '{$Titulo}'";
			$strSQL.= ", Dominio = '{$Dominio}'";
			$strSQL.= ", Copete = '{$Copete}'";
			$strSQL.= ", Descripcion = '{$Descripcion}'";
			$strSQL.= ", Etiquetas = '{$Etiquetas}'";
			
			if (!empty($_FILES["Imagen"]))
				$strSQL.= ", Imagen = '{$Imagen}'";
			
			$strSQL.= " WHERE NumeBlog = " . $NumeBlog;

			if (!$conn->query($strSQL))
				echo "Error al modificar art&iacute;culo:<br />(" . $conn->errno . ") " . $conn->error . "<br />";
			else {
				$strSQL = "DELETE FROM blogexperiencias WHERE NumeBlog = " . $NumeBlog;
				
				if (!$conn->query($strSQL))
					echo "Error al modificar art&iacute;culo:<br />(" . $conn->errno . ") " . $conn->error . "<br />" . $strSQL;
				else {
					$error = "";
						
					for ($I = 0; $I < count($Experiencias); $I++) {
						$strSQL = "INSERT INTO blogexperiencias(NumeBlog, NumeExpe)";
						$strSQL.= "VALUES({$NumeBlog}, {$Experiencias[$I]})";
						
						if (!$conn->query($strSQL)) {
							$error = "Error al crear art&iacute;culo:<br />(" . $conn->errno . ") " . $conn->error . "<br />" . $strSQL;
							break;
						}
					}
						
					if ($error == "")
						echo "Art&iacute;culo Modificado!<br>";
					else
						echo $error;
				}
			}
			break;

		case 2: //DELETE
			$strSQL = "DELETE FROM blogexperiencias WHERE NumeBlog = {$NumeBlog}";
			
			if (!$conn->query($strSQL))
				echo "Error al borrar art&iacute;culo:<br />(" . $conn->errno . ") " . $conn->error . "<br />" . $strSQL;
			else {
				$strSQL = "DELETE FROM blog WHERE NumeBlog = {$NumeBlog}";
				if (!$conn->query($strSQL))
					echo "Error al borrar art&iacute;culo:<br />(" . $conn->errno . ") " . $conn->error . "<br />" . $strSQL;
				else
					echo "Art&iacute;culo borrado!";
			}
			
			break;
			
		case 3: //SUBIR IMAGEN
			if (!empty($_FILES["Imagen"])) {
				$strSQL = "SELECT COALESCE(MAX(NumeImag), 0) + 1 NumeImag FROM blogimagenes";
				$tabla = $conn->query($strSQL);
				$fila = $tabla->fetch_array();
				$tabla->free();
				
				$temp = explode(".", $_FILES["Imagen"]["name"]);
				$extension = end($temp);
			
				$archivo = $fila["NumeImag"] . "." . $extension;
				$Imagen = "imgBlog/" . $archivo;
					
				subir_archivo($_FILES["Imagen"], "../imgBlog", $archivo);
			
				$strSQL = "INSERT INTO blogimagenes(NumeImag, Imagen)";
				$strSQL.= " VALUES({$fila["NumeImag"]}, '{$Imagen}')";
			
				if (!$conn->query($strSQL))
					echo "Error al subir imagen:<br />(" . $conn->errno . ") " . $conn->error . "<br />" . $strSQL;
					else {
						echo "Imagen subida!<br>";
					}
			}
			break;
		
		case 4: //CARGAR IMAGENES
			$strSQL = "SELECT NumeImag, Imagen";
			$strSQL.= " FROM blogimagenes";
			$strSQL.= " ORDER BY NumeImag Desc";
			
			$tabla = cargarTabla($strSQL);
			
			$salida = '';
			
			if (mysqli_num_rows($tabla) > 0) {
				while ($fila = $tabla->fetch_array()) {
					$salida.= $crlf.'<img class="thumbnail flLeft clickable" src="'.$fila["Imagen"].'" onclick="selectImg(this);" />';
				}
				$salida.= $crlf.'<div class="clearer"></div>';
			}
			else {
				$salida.= $crlf."<h3>Sin datos para mostrar</h3>";
			}
			
			echo $salida;
			break;

		case 10: //LISTAR
			$strSQL = "SELECT NumeBlog, Titulo, Dominio, Imagen, Copete, Descripcion, Etiquetas";
			$strSQL.= " FROM blog";
			$strSQL.= " ORDER BY NumeBlog";

			$tabla = cargarTabla($strSQL);

			$salida = '';

			if (mysqli_num_rows($tabla) > 0) {
				$salida.= $crlf.'<table class="table table-striped table-condensed">';
				$salida.= $crlf.'<tr>';
				$salida.= $crlf.'<th>N&uacute;mero</th>';
				$salida.= $crlf.'<th>T&iacute;tulo</th>';
				$salida.= $crlf.'<th>Imagen</th>';
				$salida.= $crlf.'<th></th>';
				$salida.= $crlf.'</tr>';
								 
	    		while ($fila = $tabla->fetch_array()) {
	    			$strSQL = "SELECT NumeExpe FROM blogexperiencias WHERE NumeBlog = {$fila["NumeBlog"]}";
	    			$tabla2 = cargarTabla($strSQL);

	    			$experiencias = "";
	    			while ($fila2 = $tabla2->fetch_array()) {
	    				if ($experiencias != "")
	    					$experiencias.= "@#@";
	    				
	    					$experiencias.= $fila2["NumeExpe"];
	    			}
	    			
	    			if (isset($tabla2))
	    				$tabla2->free();
	    			
	    			$salida.= $crlf.'<tr>';
	    					 
	    			//Numero
	    			$salida.= $crlf.'<td id="NumeBlog'.$fila["NumeBlog"].'">'.$fila["NumeBlog"];
	    			//Experiencias
	    			$salida.= $crlf.'<input type="hidden" id="Experiencias'.$fila["NumeBlog"].'" value="'.$experiencias.'" />';
	    			//Dominio
	    			$salida.= $crlf.'<input type="hidden" id="Dominio'.$fila["NumeBlog"].'" value="'.str_replace("\"", "&quot;", $fila["Dominio"]).'" />';
	    			//Copete
	    			$salida.= $crlf.'<input type="hidden" id="Copete'.$fila["NumeBlog"].'" value="'.str_replace("\"", "&quot;", $fila["Copete"]).'" />';
	    			//Descripcion
	    			$salida.= $crlf.'<input type="hidden" id="Descripcion'.$fila["NumeBlog"].'" value="'.str_replace("\"", "&quot;", $fila["Descripcion"]).'" />';
	    			//Etiquetas
	    			$salida.= $crlf.'<input type="hidden" id="Etiquetas'.$fila["NumeBlog"].'" value="'.str_replace("\"", "&quot;", $fila["Etiquetas"]).'" />';
	    			$salida.= $crlf.'</td>';
	    			//Imagen
	    			$salida.= $crlf.'<td><img id="Imagen'.$fila["NumeBlog"].'" src="'.$fila["Imagen"].'" style="width: 100px; height: auto;" /></td>';
					//Titulo
					$salida.= $crlf.'<td id="Titulo'.$fila["NumeBlog"].'">'.$fila["Titulo"].'</td>';
					
					$salida.= $crlf.'<td style="text-align: center;">';
					//Comentarios
					$salida.= $crlf.'<input type="button" value="Comentarios" onclick="location.href=\'blogcomentarios.php?blog='.$fila["NumeBlog"].'\'" class="btn btn-default btn-sm" />';
					//Editar
					$salida.= $crlf.'<input type="button" value="Editar" onclick="editar(\''.$fila["NumeBlog"].'\')" class="btn btn-info btn-sm" />';
					//Borrar
					$salida.= $crlf.'&nbsp;<input type="button" value="Borrar" onclick="borrar(\''.$fila["NumeBlog"].'\')" class="btn btn-danger btn-sm" />';
					$salida.= $crlf.'</td>';
					
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