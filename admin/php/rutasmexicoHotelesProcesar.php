<?php
	session_start();
	
	if (!isset($_SESSION['is_logged_in'])) {
		header("Location:login.php");
		die();
	}
	
	include("conexion.php");
	include("upload_file.php");
	
	$operacion = $_POST["operacion"];
	
	if (isset($_POST["NumeItin"])) {
		$NumeItin = $_POST["NumeItin"];
	}
	
	if (isset($_POST["NumeHotel"])) {
		$NumeHotel = $_POST["NumeHotel"];
	}
	
	if (isset($_POST["Estancia"])) {
		$Estancia = $_POST["Estancia"];
	}

	switch ($operacion) {
		case 0: //INSERT
			$Aux = buscarDato("SELECT COALESCE(NumeItin, 0) FROM rutasmexicoitinerarioshoteles WHERE NumeItin = {$NumeItin} AND NumeHotel = {$NumeHotel}");
			
			if ($NumeItin == $Aux) {
				echo "Error - El Hotel ingresado ya existe.";
				die();
			}
						
			$strSQL = "INSERT INTO rutasmexicoitinerarioshoteles(NumeItin, NumeHotel, Estancia)";
			$strSQL.= " VALUES({$NumeItin}, {$NumeHotel}, '{$Estancia}')";

			$resultado = ejecutarCMD($strSQL);
			if (!$resultado)
				echo "Error al crear asociaci&oacute;n:<br />" . $resultado . "<br />" . $strSQL;
			else 
				echo "Asociaci&oacute;n Creada!<br>";

			break;

		case 1: //UPDATE
			$strSQL = "UPDATE rutasmexicoitinerarioshoteles";
			$strSQL.= "SET Estancia = '{$Estancia}'";
			$strSQL.= " WHERE NumeItin = " . $NumeItin;
			$strSQL.= " AND NumeHotel = " . $NumeHotel;
			
			$resultado = ejecutarCMD($strSQL);
			if (!$resultado)
				echo "Error al modificar asociaci&oacute;n:<br />" . $resultado . "<br />" . $strSQL;
			else 
				echo "Asociaci&oacute;n Modificada!<br>";

			break;

		case 2: //DELETE
			$strSQL = "DELETE FROM rutasmexicoitinerarioshoteles WHERE NumeItin = {$NumeItin} AND NumeHotel = {$NumeHotel}";
			
			$resultado = ejecutarCMD($strSQL);
			if (!$resultado)
				echo "Error al borrar asociaci&oacute;n:<br />(" . $resultado . "<br />" . $strSQL;
			else
				echo "Asociaci&oacute;n borrada!";

			break;

		case 10: //LISTAR
			$strSQL = "SELECT rh.NumeHotel, h.NombHotel, h.Ciudad, rh.Estancia";
			$strSQL.= " FROM rutasmexicoitinerarioshoteles rh";
			$strSQL.= " INNER JOIN hoteles h ON rh.NumeHotel = h.NumeHotel";
			$strSQL.= " WHERE rh.NumeItin = " . $NumeItin;
			$strSQL.= " ORDER BY h.NombHotel";

			$tabla = cargarTabla($strSQL);

			$salida = '';

			if (mysqli_num_rows($tabla) > 0) {
				$salida.= $crlf.'<table class="table table-striped table-condensed">';
				$salida.= $crlf.'<tr>';
				$salida.= $crlf.'<th>Hotel</th>';
				$salida.= $crlf.'<th>Ciudad</th>';
				$salida.= $crlf.'<th>Estancia</th>';
				$salida.= $crlf.'<th></th>';
				$salida.= $crlf.'<th></th>';
				$salida.= $crlf.'</tr>';
								 
	    		while ($fila = $tabla->fetch_array()) {
    				$salida.= $crlf.'<tr>';
	    					 
					//Hotel
					$salida.= $crlf.'<td id="NombHotel'.$fila[0].'">'.$fila["NombHotel"].'</td>';
					//Ciudad
					$salida.= $crlf.'<td>'.$fila["Ciudad"].'</td>';
					//Estancia
					$salida.= $crlf.'<td id="Estancia'.$fila[0].'">'.$fila["Estancia"].'</td>';

					//Editar
					$salida.= $crlf.'<td style="text-align: center;">';
					$salida.= $crlf.'<input type="hidden" id="NumeHotel'.$fila[0].'" value="'.$fila[0].'" />';
					$salida.= $crlf.'<input type="button" value="Editar" onclick="editar(\''.$fila[0].'\')" class="btn btn-info" /></td>';
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