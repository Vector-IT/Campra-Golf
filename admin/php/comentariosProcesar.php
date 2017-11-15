<?php
	session_start();
	
	include("conexion.php");
	
	$operacion = $_POST["operacion"];
	
	if (isset($_POST["NumeCome"]))
		$NumeCome = $_POST["NumeCome"];
	
	if (isset($_POST["Numero"]))
		$Numero = $_POST["Numero"];
	
	if (isset($_POST["Tipo"]))
		$Tipo = $_POST["Tipo"];
	
	if (isset($_POST["Nombre"]))
		$Nombre = $_POST["Nombre"];
	
	if (isset($_POST["Correo"]))
		$Correo = $_POST["Correo"];
	
	if (isset($_POST["Mensaje"]))
		$Mensaje = str_replace("'", "\'", $_POST["Mensaje"]);
	
	$Puntaje = 0;
	if (isset($_POST["Puntaje"]))
		$Puntaje = $_POST["Puntaje"];
	
	if (isset($_SESSION["NumeUsua"]))
		$Usuario = $_SESSION["NumeUsua"];
	else 
		$Usuario = 'null';
	
	$conn = new mysqli($dbhost, $dbuser, $dbpass, $db) or die("Problemas al conectar con la BD");

	switch ($operacion) {
		case 0: //INSERT
			if ($Tipo == "1") {
				$strSQL = "SELECT COALESCE(MAX(NumeCome), 0) + 1 NumeCome FROM blogcomentarios";
				$tabla = $conn->query($strSQL);
				$fila = $tabla->fetch_array();
	
				//INSERT
				$strSQL = "INSERT INTO blogcomentarios(NumeCome, NumeBlog, DescCome, Fecha, NumeUsua, Nombre, Email)";
				$strSQL.= " VALUES({$fila["NumeCome"]}, {$Numero}, '{$Mensaje}', SYSDATE(), {$Usuario}, '{$Nombre}', '{$Correo}')";
			}
			else {
				$strSQL = "SELECT COALESCE(MAX(NumeCome), 0) + 1 NumeCome FROM tourscomentarios";
				$tabla = $conn->query($strSQL);
				$fila = $tabla->fetch_array();
				
				//INSERT
				$strSQL = "INSERT INTO tourscomentarios(NumeCome, NumeTour, PuntCome, DescCome, Fecha, NumeUsua, Nombre, Email)";
				$strSQL.= " VALUES({$fila["NumeCome"]}, {$Numero}, {$Puntaje}, '{$Mensaje}', SYSDATE(), {$Usuario}, '{$Nombre}', '{$Correo}')";
			}	
			if (!$conn->query($strSQL))
				echo "Error";
			else
				echo "Exito";
			
			break;

		case 1: //UPDATE
			if ($Tipo == "1") {
				$strSQL = "UPDATE blogcomentarios";
				$strSQL.= " SET DescCome = '{$Mensaje}'";
				$strSQL.= " WHERE NumeCome = " . $NumeCome;
			}
			else {
				$strSQL = "UPDATE tourscomentarios";
				$strSQL.= " SET DescCome = '{$Mensaje}'";
				$strSQL.= ", PuntCome = " . $Puntaje;
				$strSQL.= " WHERE NumeCome = " . $NumeCome;
			}
			if (!$conn->query($strSQL))
				echo "Error";
			else
				echo "Exito";
			break;

		case 2: //DELETE
			if ($Tipo == "1") {
				$strSQL = "DELETE FROM blogcomentarios WHERE NumeCome = {$NumeCome}";
			}
			else {
				$strSQL = "DELETE FROM tourscomentarios WHERE NumeCome = {$NumeCome}";
			}
			if (!$conn->query($strSQL))
				echo "Error";
			else
				echo "Exito";

			break;

		case 10: //LISTAR
			if ($Tipo == "1") {
				$strSQL = "SELECT bc.NumeCome, bc.NumeBlog, bc.DescCome, bc.Fecha, u.NombComp, bc.Nombre, bc.Email";
				$strSQL.= " FROM blogcomentarios bc";
				$strSQL.= " LEFT JOIN usuarios u ON bc.NumeUsua = u.NumeUsua";
				$strSQL.= " WHERE NumeBlog = " . $Numero;
				$strSQL.= " ORDER BY NumeCome DESC";
			}
			else {
				$strSQL = "SELECT bc.NumeCome, bc.NumeTour, bc.PuntCome, bc.DescCome, bc.Fecha, u.NombComp, bc.Nombre, bc.Email";
				$strSQL.= " FROM tourscomentarios bc";
				$strSQL.= " LEFT JOIN usuarios u ON bc.NumeUsua = u.NumeUsua";
				$strSQL.= " WHERE NumeTour = " . $Numero;
				$strSQL.= " ORDER BY NumeCome DESC";
			}
			
			$tabla = cargarTabla($strSQL);

			$salida = '';

			if (mysqli_num_rows($tabla) > 0) {
				$salida.= $crlf.'<table class="table table-striped table-condensed">';
				$salida.= $crlf.'<tr>';
				$salida.= $crlf.'<th>N&uacute;mero</th>';
				$salida.= $crlf.'<th>Fecha</th>';
				$salida.= $crlf.'<th>Usuario</th>';
				$salida.= $crlf.'<th>Nombre</th>';
				$salida.= $crlf.'<th>Correo</th>';
				if ($Tipo != "1") 
					$salida.= $crlf.'<th>Puntaje</th>';
				$salida.= $crlf.'<th>Comentario</th>';
				$salida.= $crlf.'<th></th>';
				$salida.= $crlf.'<th></th>';
				$salida.= $crlf.'</tr>';
								 
	    		while ($fila = $tabla->fetch_array()) {
	    			$salida.= $crlf.'<tr>';
	    					 
	    			//Numero
	    			$salida.= $crlf.'<td id="NumeCome'.$fila["NumeCome"].'">'.$fila["NumeCome"].'</td>';
	    			//Fecha
	    			$salida.= $crlf.'<td>'.$fila["Fecha"].'</td>';
	    			//Usuario
	    			$salida.= $crlf.'<td>'.$fila["NombComp"].'</td>';
	    			//Nombre
	    			$salida.= $crlf.'<td>'.$fila["Nombre"].'</td>';
	    			//Correo
	    			$salida.= $crlf.'<td>'.$fila["Email"].'</td>';
	    			//Puntaje
	    			if ($Tipo != "1")
	    				$salida.= $crlf.'<td id="PuntCome'.$fila["NumeCome"].'">'.$fila["PuntCome"].'</td>';
					//Comentario
					$salida.= $crlf.'<td id="DescCome'.$fila["NumeCome"].'">'.str_replace("\"", "&quot;", $fila["DescCome"]).'</td>';
					//Editar
					$salida.= $crlf.'<td style="text-align: center;"><input type="button" value="Editar" onclick="editar(\''.$fila["NumeCome"].'\')" class="btn btn-info" /></td>';
					//Borrar
					$salida.= $crlf.'<td style="text-align: center;"><input type="button" value="Borrar" onclick="borrar(\''.$fila["NumeCome"].'\')" class="btn btn-danger" /></td>';
					
					$salida.= $crlf.'</tr>';
				}
				
				$salida.= $crlf.'</table>';
	    	}
	    	else {
	    		$salida.= "<h3>Sin datos para mostrar</h3>";
	    	}
	    	
	    	echo $salida;
	
	    	break;
	}
	
	if (isset($tabla))
		$tabla->free();
	
	$conn->close();
?>