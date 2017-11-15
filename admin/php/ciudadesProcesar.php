<?php
	session_start();
	
	if (!isset($_SESSION['is_logged_in'])) {
		header("Location:login.php");
		die();
	}
	
	include("conexion.php");
	
	$operacion = $_POST["operacion"];
	
	if (isset($_POST["NumeCity"])) {
		$NumeCity = $_POST["NumeCity"];
	}
	
	if (isset($_POST["NombCity"])) {
		$NombCity = $_POST["NombCity"];
	}
	
	if (isset($_POST["NumeRuta"])) {
		$NumeRuta = $_POST["NumeRuta"];
	}
	
	switch ($operacion) {
		case 0: //INSERT
			$NumeCity = buscarDato("SELECT COALESCE(MAX(NumeCity), 0) + 1 FROM ciudades");

			//INSERT
			$strSQL = "INSERT INTO ciudades(NumeCity, NombCity, NumeRuta)";
			$strSQL.= " VALUES({$NumeCity}, '{$NombCity}', {$NumeRuta})";

			$resultado = ejecutarCMD($strSQL);
			if (!$resultado)
				echo "Error al crear ciudad.<br>" . $resultado;
			else
				echo "Ciudad creada!";
			
			break;

		case 1: //UPDATE
			$strSQL = "UPDATE ciudades";
			$strSQL.= " SET NombCity = '{$NombCity}'";
			$strSQL.= ", NumeRuta = ". $NumeRuta;
			$strSQL.= " WHERE NumeCity = " . $NumeCity;

			$resultado = ejecutarCMD($strSQL);
			if (!$resultado)
				echo "Error al modificar ciudad.<br>" . $resultado;
			else
				echo "Ciudad modificada!";
			
			break;

		case 2: //DELETE
			$strSQL = "DELETE FROM ciudades WHERE NumeCity = {$NumeCity}";

			$resultado = ejecutarCMD($strSQL);
			if (!$resultado)
				echo "Error al borrar ciudad.<br>" . $resultado;
			else
				echo "Ciudad borrada!";

			break;

		case 10: //LISTAR
			$strSQL = "SELECT c.NumeCity, c.NombCity, c.NumeRuta, r.Nombre";
			$strSQL.= " FROM ciudades c";
			$strSQL.= " LEFT JOIN rutasmexico r ON c.NumeRuta = r.NumeRuta";
			$strSQL.= " ORDER BY r.Nombre, c.NombCity";
			
			$tabla = cargarTabla($strSQL);

			$salida = '';

			if (mysqli_num_rows($tabla) > 0) {
				$salida.= $crlf.'<table class="table table-striped table-condensed">';
				$salida.= $crlf.'<tr>';
				$salida.= $crlf.'<th>N&uacute;mero</th>';
				$salida.= $crlf.'<th>Nombre</th>';
				$salida.= $crlf.'<th>Ruta</th>';
				$salida.= $crlf.'<th></th>';
				$salida.= $crlf.'<th></th>';
				$salida.= $crlf.'</tr>';
								 
	    		while ($fila = $tabla->fetch_array()) {
	    			$salida.= $crlf.'<tr>';
	    					 
	    			//Numero
	    			$salida.= $crlf.'<td id="NumeCity'.$fila[0].'">'.$fila[0].'</td>';
	    			//Nombre
	    			$salida.= $crlf.'<td id="NombCity'.$fila[0].'">'.$fila["NombCity"].'</td>';
	    			//Ruta
	    			$salida.= $crlf.'<td>'.$fila["Nombre"];
	    			$salida.= $crlf.'<input type="hidden" id="NumeRuta'.$fila[0].'" value="'.$fila["NumeRuta"].'" /></td>';
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