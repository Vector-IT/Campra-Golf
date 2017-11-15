<?php
	session_start();
	
	if (!isset($_SESSION['is_logged_in'])) {
		header("Location:login.php");
		die();
	}
	
	include("conexion.php");
	
	$operacion = $_POST["operacion"];
	
	if (isset($_POST["NumeHotel"]))
		$NumeHotel = $_POST["NumeHotel"];
	
	if (isset($_POST["NombHotel"]))
		$NombHotel = $_POST["NombHotel"];
	
	if (isset($_POST["Cadena"]))
		$Cadena = $_POST["Cadena"];
	
	if (isset($_POST["Ciudad"]))
		$Ciudad = $_POST["Ciudad"];
	
	switch ($operacion) {
		case 0: //INSERT
			$NumeHotel = buscarDato("SELECT COALESCE(MAX(NumeHotel), 0) + 1 FROM hoteles");

			//INSERT
			$strSQL = "INSERT INTO hoteles(NumeHotel, NombHotel, Cadena, Ciudad)";
			$strSQL.= " VALUES({$NumeHotel}, '{$NombHotel}', '{$Cadena}', '{$Ciudad}')";

			$resultado = ejecutarCMD($strSQL);
			if (!$resultado)
				echo "Error al crear hotel.<br>" . $resultado;
			else
				echo "Hotel creado!";
			
			break;

		case 1: //UPDATE
			$strSQL = "UPDATE hoteles";
			$strSQL.= " SET NombHotel = '{$NombHotel}'";
			$strSQL.= ", Cadena = '{$Cadena}'";
			$strSQL.= ", Ciudad = '{$Ciudad}'";
			$strSQL.= " WHERE NumeHotel = " . $NumeHotel;

			$resultado = ejecutarCMD($strSQL);
			if (!$resultado)
				echo "Error al modificar hotel.<br>" . $resultado;
			else
				echo "Hotel modificado!";
			
			break;

		case 2: //DELETE
			$strSQL = "DELETE FROM hoteles WHERE NumeHotel = {$NumeHotel}";

			$resultado = ejecutarCMD($strSQL);
			if (!$resultado)
				echo "Error al borrar hotel.<br>" . $resultado;
			else
				echo "Hotel borrado!";

			break;

		case 10: //LISTAR
			$strSQL = "SELECT NumeHotel, NombHotel, Cadena, Ciudad";
			$strSQL.= " FROM hoteles";
			$strSQL.= " ORDER BY NumeHotel";
			
			$tabla = cargarTabla($strSQL);

			$salida = '';

			if (mysqli_num_rows($tabla) > 0) {
				$salida.= $crlf.'<table class="table table-striped table-condensed">';
				$salida.= $crlf.'<tr>';
				$salida.= $crlf.'<th>N&uacute;mero</th>';
				$salida.= $crlf.'<th>Nombre</th>';
				$salida.= $crlf.'<th>Cadena</th>';
				$salida.= $crlf.'<th>Ciudad</th>';
				$salida.= $crlf.'<th></th>';
				$salida.= $crlf.'<th></th>';
				$salida.= $crlf.'</tr>';
								 
	    		while ($fila = $tabla->fetch_array()) {
	    			$salida.= $crlf.'<tr>';
	    					 
	    			//Numero
	    			$salida.= $crlf.'<td id="NumeHotel'.$fila["NumeHotel"].'">'.$fila["NumeHotel"].'</td>';
	    			//Nombre
	    			$salida.= $crlf.'<td id="NombHotel'.$fila["NumeHotel"].'">'.$fila["NombHotel"].'</td>';
	    			//Cadena
	    			$salida.= $crlf.'<td id="Cadena'.$fila["NumeHotel"].'">'.$fila["Cadena"].'</td>';
	    			//Ciudad
	    			$salida.= $crlf.'<td id="Ciudad'.$fila["NumeHotel"].'">'.$fila["Ciudad"].'</td>';
					//Editar
					$salida.= $crlf.'<td style="text-align: center;"><input type="button" value="Editar" onclick="editar(\''.$fila["NumeHotel"].'\')" class="btn btn-info" /></td>';
					//Borrar
					$salida.= $crlf.'<td style="text-align: center;"><input type="button" value="Borrar" onclick="borrar(\''.$fila["NumeHotel"].'\')" class="btn btn-danger" /></td>';
					
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