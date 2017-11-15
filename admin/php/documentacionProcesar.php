<?php
	session_start();
	
	if (!isset($_SESSION['is_logged_in'])) {
		header("Location:login.php");
		die();
	}
	
	include("conexion.php");
	
	$operacion = $_POST["operacion"];
	
	if (isset($_POST["NumeDocu"]))
		$NumeDocu = $_POST["NumeDocu"];
	
	if (isset($_POST["NumeTour"]))
		$NumeTour = $_POST["NumeTour"];
	
	if (isset($_POST["DescDocu"]))
		$DescDocu = str_replace("'", "\'", $_POST["DescDocu"]);
	
	$conn = new mysqli($dbhost, $dbuser, $dbpass, $db) or die("Problemas al conectar con la BD");

	switch ($operacion) {
		case 0: //INSERT
			$strSQL = "SELECT COALESCE(MAX(NumeDocu), 0) + 1 NumeDocu FROM toursdocumentacion";
			$tabla = $conn->query($strSQL);
			$fila = $tabla->fetch_array();

			//INSERT
			$strSQL = "INSERT INTO toursdocumentacion(NumeDocu, NumeTour, DescDocu)";
			$strSQL.= " VALUES({$fila["NumeDocu"]}, {$NumeTour}, '{$DescDocu}')";

			if (!$conn->query($strSQL))
				echo "Error al crear item:<br />(" . $conn->errno . ") " . $conn->error . "<br />" . $strSQL;
			else 				
				echo "Item Creado!<br>";

			$tabla->free();
			break;

		case 1: //UPDATE
			$strSQL = "UPDATE toursdocumentacion";
			$strSQL.= " SET DescDocu = '{$DescDocu}'";
			$strSQL.= " WHERE NumeDocu = " . $NumeDocu;

			if (!$conn->query($strSQL))
				echo "Error al modificar item:<br />(" . $conn->errno . ") " . $conn->error . "<br />";
			else 
				echo "Item Modificado!<br>";
			
			break;

		case 2: //DELETE
			$strSQL = "DELETE FROM toursdocumentacion WHERE NumeDocu = {$NumeDocu}";
			
			if (!$conn->query($strSQL))
				echo "Error al borrar item:<br />(" . $conn->errno . ") " . $conn->error . "<br />" . $strSQL;
			else
				echo "Item borrado!";

			break;

		case 10: //LISTAR
			$strSQL = "SELECT NumeDocu, DescDocu";
			$strSQL.= " FROM toursdocumentacion";
			$strSQL.= " WHERE NumeTour = " . $NumeTour;
			$strSQL.= " ORDER BY NumeDocu";

			$tabla = cargarTabla($strSQL);

			$salida = '';

			if (mysqli_num_rows($tabla) > 0) {
				$salida.= $crlf.'<table class="table table-striped table-condensed">';
				$salida.= $crlf.'<tr>';
				$salida.= $crlf.'<th>Descripci&oacute;n</th>';
				$salida.= $crlf.'<th></th>';
				$salida.= $crlf.'<th></th>';
				$salida.= $crlf.'</tr>';
								 
	    		while ($fila = $tabla->fetch_array()) {
	    			$salida.= $crlf.'<tr>';
	    					 
					//Descripcion
					$salida.= $crlf.'<td id="DescDocu'.$fila["NumeDocu"].'">'.$fila["DescDocu"].'</td>';
					//Editar
					$salida.= $crlf.'<td style="text-align: center;"><input type="button" value="Editar" onclick="editar(\''.$fila["NumeDocu"].'\')" class="btn btn-info" /></td>';
					//Borrar
					$salida.= $crlf.'<td style="text-align: center;"><input type="button" value="Borrar" onclick="borrar(\''.$fila["NumeDocu"].'\')" class="btn btn-danger" /></td>';
					
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