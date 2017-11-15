<?php
	session_start();
	
	if (!isset($_SESSION['is_logged_in'])) {
		header("Location:login.php");
		die();
	}
	
	include("conexion.php");
	
	$operacion = $_POST["operacion"];
	
	if (isset($_POST["NumeExcu"]))
		$NumeExcu = $_POST["NumeExcu"];
	
	if (isset($_POST["Titulo"]))
		$Titulo = $_POST["Titulo"];
	
	if (isset($_POST["Ciudad"]))
		$Ciudad = $_POST["Ciudad"];
	
	if (isset($_POST["Descripcion"]))
		$Descripcion = $_POST["Descripcion"];
	
	if (isset($_POST["Precio"]))
		$Precio = $_POST["Precio"];
	
	switch ($operacion) {
		case 0: //INSERT
			$NumeExcu = buscarDato("SELECT COALESCE(MAX(NumeExcu), 0) + 1 FROM excursiones");

			//INSERT
			$strSQL = "INSERT INTO excursiones(NumeExcu, Titulo, Ciudad, Descripcion, Precio)";
			$strSQL.= " VALUES({$NumeExcu}, '{$Titulo}', '{$Ciudad}', '{$Descripcion}', '{$Precio}')";

			$resultado = ejecutarCMD($strSQL);
			if (!$resultado)
				echo "Error al crear excursi&oacute;n.<br>" . $resultado;
			else
				echo "Excursi&oacute;n creada!";
			
			break;

		case 1: //UPDATE
			$strSQL = "UPDATE excursiones";
			$strSQL.= " SET Titulo = '{$Titulo}'";
			$strSQL.= ", Ciudad = '{$Ciudad}'";
			$strSQL.= ", Descripcion = '{$Descripcion}'";
			$strSQL.= ", Precio = '{$Precio}'";
			$strSQL.= " WHERE NumeExcu = " . $NumeExcu;

			$resultado = ejecutarCMD($strSQL);
			if (!$resultado)
				echo "Error al modificar excursi&oacute;n.<br>" . $resultado;
			else
				echo "Excursi&oacute;n modificada!";
			
			break;

		case 2: //DELETE
			$strSQL = "DELETE FROM excursiones WHERE NumeExcu = {$NumeExcu}";

			$resultado = ejecutarCMD($strSQL);
			if (!$resultado)
				echo "Error al borrar excursi&oacute;n.<br>" . $resultado;
			else
				echo "Excursi&oacute;n borrada!";

			break;

		case 10: //LISTAR
			$strSQL = "SELECT NumeExcu, Titulo, Ciudad, Descripcion, Precio";
			$strSQL.= " FROM excursiones";
			$strSQL.= " ORDER BY NumeExcu";
			
			$tabla = cargarTabla($strSQL);

			$salida = '';

			if (mysqli_num_rows($tabla) > 0) {
				$salida.= $crlf.'<table class="table table-striped table-condensed">';
				$salida.= $crlf.'<tr>';
				$salida.= $crlf.'<th>N&uacute;mero</th>';
				$salida.= $crlf.'<th>Titulo</th>';
				$salida.= $crlf.'<th>Ciudad</th>';
				$salida.= $crlf.'<th>Precio</th>';
				$salida.= $crlf.'<th></th>';
				$salida.= $crlf.'<th></th>';
				$salida.= $crlf.'</tr>';
								 
	    		while ($fila = $tabla->fetch_array()) {
	    			$salida.= $crlf.'<tr>';
	    					 
	    			//Numero
	    			$salida.= $crlf.'<td id="NumeExcu'.$fila["NumeExcu"].'">'.$fila["NumeExcu"];
	    			//Descripcion
	    			$salida.= $crlf.'<input type="hidden" id="Descripcion'.$fila["NumeExcu"].'" value="'. $fila["Descripcion"] .'" /></td>';
	    			//Nombre
	    			$salida.= $crlf.'<td id="Titulo'.$fila["NumeExcu"].'">'.$fila["Titulo"].'</td>';
	    			//Ciudad
	    			$salida.= $crlf.'<td id="Ciudad'.$fila["NumeExcu"].'">'.$fila["Ciudad"].'</td>';
	    			//Precio
	    			$salida.= $crlf.'<td id="Precio'.$fila["NumeExcu"].'">'.$fila["Precio"].'</td>';
					//Editar
					$salida.= $crlf.'<td style="text-align: center;"><input type="button" value="Editar" onclick="editar(\''.$fila["NumeExcu"].'\')" class="btn btn-info" /></td>';
					//Borrar
					$salida.= $crlf.'<td style="text-align: center;"><input type="button" value="Borrar" onclick="borrar(\''.$fila["NumeExcu"].'\')" class="btn btn-danger" /></td>';
					
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