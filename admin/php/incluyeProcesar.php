<?php
	session_start();
	
	if (!isset($_SESSION['is_logged_in'])) {
		header("Location:login.php");
		die();
	}
	
	include("conexion.php");
	
	$operacion = $_POST["operacion"];
	
	if (isset($_POST["NumeIncl"]))
		$NumeIncl = $_POST["NumeIncl"];
	
	if (isset($_POST["NumeTour"]))
		$NumeTour = $_POST["NumeTour"];
	
	if (isset($_POST["FlagIncl"]))
		$FlagIncl = $_POST["FlagIncl"];
	
	if (isset($_POST["DescIncl"]))
		$DescIncl = str_replace("'", "\'", $_POST["DescIncl"]);
	
	$conn = new mysqli($dbhost, $dbuser, $dbpass, $db) or die("Problemas al conectar con la BD");

	switch ($operacion) {
		case 0: //INSERT
			$strSQL = "SELECT COALESCE(MAX(NumeIncl), 0) + 1 NumeIncl FROM toursincluye";
			$tabla = $conn->query($strSQL);
			$fila = $tabla->fetch_array();

			//INSERT
			$strSQL = "INSERT INTO toursincluye(NumeIncl, NumeTour, FlagIncl, DescIncl)";
			$strSQL.= " VALUES({$fila["NumeIncl"]}, {$NumeTour}, {$FlagIncl}, '{$DescIncl}')";

			if (!$conn->query($strSQL))
				echo "Error al crear item:<br />(" . $conn->errno . ") " . $conn->error . "<br />" . $strSQL;
			else 				
				echo "Item Creado!<br>";

			$tabla->free();
			break;

		case 1: //UPDATE
			$strSQL = "UPDATE toursincluye";
			$strSQL.= " SET FlagIncl = {$FlagIncl}";
			$strSQL.= ", DescIncl = '{$DescIncl}'";
			$strSQL.= " WHERE NumeIncl = " . $NumeIncl;

			if (!$conn->query($strSQL))
				echo "Error al modificar item:<br />(" . $conn->errno . ") " . $conn->error . "<br />";
			else 
				echo "Item Modificado!<br>";
			
			break;

		case 2: //DELETE
			$strSQL = "DELETE FROM toursincluye WHERE NumeIncl = {$NumeIncl}";
			
			if (!$conn->query($strSQL))
				echo "Error al borrar item:<br />(" . $conn->errno . ") " . $conn->error . "<br />" . $strSQL;
			else
				echo "Itinerario borrado!";

			break;

		case 10: //LISTAR
			$strSQL = "SELECT NumeIncl, FlagIncl, DescIncl";
			$strSQL.= " FROM toursincluye";
			$strSQL.= " WHERE NumeTour = " . $NumeTour;
			$strSQL.= " ORDER BY NumeIncl";

			$tabla = cargarTabla($strSQL);

			$salida = '';

			if (mysqli_num_rows($tabla) > 0) {
				$salida.= $crlf.'<table class="table table-striped table-condensed">';
				$salida.= $crlf.'<tr>';
				$salida.= $crlf.'<th>Incluye?</th>';
				$salida.= $crlf.'<th>Descripci&oacute;n</th>';
				$salida.= $crlf.'<th></th>';
				$salida.= $crlf.'<th></th>';
				$salida.= $crlf.'</tr>';
								 
	    		while ($fila = $tabla->fetch_array()) {
	    			$salida.= $crlf.'<tr>';
	    					 
					//FlagIncl
					if ($fila["FlagIncl"] == "0")
						$salida.= $crlf.'<td><span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span></td>';
					else
						$salida.= $crlf.'<td><span class="glyphicon glyphicon-check" aria-hidden="true"></span></td>';
					
					//Descripcion
					$salida.= $crlf.'<td id="DescIncl'.$fila["NumeIncl"].'">'.$fila["DescIncl"].'</td>';
					//Editar
					$salida.= $crlf.'<td style="text-align: center;"><input type="button" value="Editar" onclick="editar(\''.$fila["NumeIncl"].'\')" class="btn btn-info" />';
					$salida.= $crlf.'<input type="hidden" id="FlagIncl' . $fila["NumeIncl"] . '" value="' . $fila["FlagIncl"] . '" />';
					$salida.= $crlf.'</td>';
					//Borrar
					$salida.= $crlf.'<td style="text-align: center;"><input type="button" value="Borrar" onclick="borrar(\''.$fila["NumeIncl"].'\')" class="btn btn-danger" /></td>';
					
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