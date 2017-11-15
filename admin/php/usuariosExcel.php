<?php 
	session_start();
	
	if ((!isset($_SESSION['is_logged_in'])) ||
			($_SESSION['TipoUsua'] != "1")) {
		header("Location:login.php?returnUrl=" . $_SERVER[REQUEST_URI]);
		die();
	}
	include "conexion.php";

	header("Content-Type: application/vnd.ms-excel");
	
	header("Expires: 0");
	
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	
	header("content-disposition: attachment;filename=Usuarios.xls");
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Usuarios</title>
		
		<style type="text/css">
			th, td {border: 1px solid;}
		</style>
	</head>
	<body>
	<?php 
		$strSQL = "SELECT u.NumeUsua, u.FechAlta, u.NombComp, u.NombMail, u.NombUsua, u.TipoUsua, u.NumeEsta, ur.NombComp NombCompRefe, u.NumeAgen, a.NombAgen";
		$strSQL.= " FROM usuarios u";
		$strSQL.= " LEFT JOIN usuarios ur ON u.NumeUsuaRefe = ur.NumeUsua";
		$strSQL.= " LEFT JOIN agencias a ON u.NumeAgen = a.NumeAgen";
		$strSQL.= " ORDER BY u.TipoUsua, u.NumeUsua";
		
		$tabla = cargarTabla($strSQL);
		
		$salida = '';
		
		if (mysqli_num_rows($tabla) > 0) {
			$salida.= $crlf.'<table class="table table-striped table-condensed">';
			$salida.= $crlf.'<tr>';
			$salida.= $crlf.'<th>N&uacute;mero</th>';
			$salida.= $crlf.'<th>Nombre</th>';
			$salida.= $crlf.'<th>Usuario</th>';
			$salida.= $crlf.'<th>Mail</th>';
			$salida.= $crlf.'<th>Tipo de usuario</th>';
			$salida.= $crlf.'<th>Agencia</th>';
			$salida.= $crlf.'<th>Fecha de registro</th>';
			$salida.= $crlf.'<th>Usuario que lo cre&oacute;</th>';
			$salida.= $crlf.'<th>Estado</th>';
			$salida.= $crlf.'</tr>';
				
			while ($fila = $tabla->fetch_array()) {
				$salida.= $crlf.'<tr>';
					
				//Numero
				$salida.= $crlf.'<td id="NumeUsua'.$fila["NumeUsua"].'">'.$fila["NumeUsua"];
				$salida.= $crlf.'</td>';
				//Nombre
				$salida.= $crlf.'<td id="NombComp'.$fila["NumeUsua"].'">'.$fila["NombComp"].'</td>';
				//Usuario
				$salida.= $crlf.'<td id="NombUsua'.$fila["NumeUsua"].'">'.$fila["NombUsua"].'</td>';
				//Mail
				$salida.= $crlf.'<td>'.$fila["NombMail"].'</td>';
				//Tipo
				switch ($fila["TipoUsua"]) {
					case 1:
						$salida.= $crlf.'<td>Administrador</td>';
						break;
							
					case 2:
						$salida.= $crlf.'<td>Agencia de viajes</td>';
						break;
							
					case 3:
						$salida.= $crlf.'<td>Usuario de p&aacute;gina</td>';
						break;
							
					case 4:
						$salida.= $crlf.'<td>Usuario de newsletter</td>';
						break;
				}
				//Agencia
				$salida.= $crlf.'<td>'.$fila["NombAgen"].'</td>';
				//Fecha alta
				$salida.= $crlf.'<td>'.$fila["FechAlta"].'</td>';
				//Usuario referencia
				$salida.= $crlf.'<td>'.$fila["NombCompRefe"].'</td>';
				//Estado
				if ($fila["NumeEsta"] == 1)
					$salida.= $crlf.'<td>Activo</td>';
				else
					$salida.= $crlf.'<td>Inactivo</td>';
				$salida.= $crlf.'</tr>';
			}
		
			$salida.= $crlf.'</table>';
		}
		$tabla->free();
		
		echo $salida;
	?>
	</body>

</html>