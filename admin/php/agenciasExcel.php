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
	
	header("content-disposition: attachment;filename=AgenciasRegistradas.xls");
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Agencias</title>
		
		<style type="text/css">
			th, td {border: 1px solid;}
		</style>
	</head>
	<body>
	<?php 
		$strSQL = "SELECT a.NumeAgen, a.NombComercial, a.Direccion, a.Telefono, a.PaginaWeb, a.RazonSocial,";
		$strSQL.= "a.IATA, a.SECTUR, a.RFC, a.NombAdmin, a.TeleAdmin, a.MailAdmin, a.NombVent, a.TeleVent,";
		$strSQL.= "a.MailVent, p.NombProv, a.NumeEsta, a.FechAlta";
		$strSQL.= " FROM agenciasregistradas a";
		$strSQL.= " LEFT JOIN provincias p ON a.NumeProv = p.NumeProv";
		$strSQL.= " ORDER BY NumeAgen";
		
		$tabla = cargarTabla($strSQL);
		
		$salida = '';
		
		if (mysqli_num_rows($tabla) > 0) {
			$salida.= $crlf.'<table class="table table-striped table-condensed">';
			$salida.= $crlf.'<tr>';
			$salida.= $crlf.'<th rowspan="2">Numero</th>';
			$salida.= $crlf.'<th rowspan="2">Fecha Alta</th>';
			$salida.= $crlf.'<th rowspan="2">Nombre</th>';
			$salida.= $crlf.'<th rowspan="2">Estado</th>';
			$salida.= $crlf.'<th rowspan="2">Direccion</th>';
			$salida.= $crlf.'<th rowspan="2">Telefono</th>';
			$salida.= $crlf.'<th rowspan="2">P&aacute;gina Web</th>';
			$salida.= $crlf.'<th rowspan="2">Raz&oacute;n Social</th>';
			$salida.= $crlf.'<th rowspan="2">IATA</th>';
			$salida.= $crlf.'<th rowspan="2">SECTUR</th>';
			$salida.= $crlf.'<th rowspan="2">RFC</th>';
			$salida.= $crlf.'<th colspan="3">Contacto Administrativo</th>';
			$salida.= $crlf.'<th colspan="3">Contacto de Ventas</th>';
			$salida.= $crlf.'<th rowspan="2">Estado</th>';
			$salida.= $crlf.'</tr>';
			$salida.= $crlf.'<tr>';
			$salida.= $crlf.'<th>Nombre</th>';
			$salida.= $crlf.'<th>Telefono</th>';
			$salida.= $crlf.'<th>Correo</th>';
			$salida.= $crlf.'<th>Nombre</th>';
			$salida.= $crlf.'<th>Telefono</th>';
			$salida.= $crlf.'<th>Correo</th>';
			$salida.= $crlf.'</tr>';
				
			while ($fila = $tabla->fetch_array()) {
				$salida.= $crlf.'<tr>';
					
				//Numero
				$salida.= $crlf.'<td>'.$fila["NumeAgen"].'</td>';
				//Fecha
				$salida.= $crlf.'<td>'.$fila["FechAlta"].'</td>';
				//Nombre
				$salida.= $crlf.'<td>'.$fila["NombComercial"].'</td>';
				//Estado
				$salida.= $crlf.'<td>'.$fila["NombProv"].'</td>';
				//Direccion
				$salida.= $crlf.'<td>'.$fila["Direccion"].'</td>';
				//Telefono
				$salida.= $crlf.'<td>'.$fila["Telefono"].'</td>';
				//Pagina Web
				$salida.= $crlf.'<td><a href="'.$fila["PaginaWeb"].'" target="_blank">'.$fila["PaginaWeb"].'</a></td>';
				//Razon Social
				$salida.= $crlf.'<td>'.$fila["RazonSocial"].'</td>';
				//IATA
				$salida.= $crlf.'<td>'.$fila["IATA"].'</td>';
				//SECTUR
				$salida.= $crlf.'<td>'.$fila["SECTUR"].'</td>';
				//RFC
				$salida.= $crlf.'<td>'.$fila["RFC"].'</td>';
				//NombAdmin
				$salida.= $crlf.'<td>'.$fila["NombAdmin"].'</td>';
				//TeleAdmin
				$salida.= $crlf.'<td>'.$fila["TeleAdmin"].'</td>';
				//MailAdmin
				$salida.= $crlf.'<td>'.$fila["MailAdmin"].'</td>';
				//NombVent
				$salida.= $crlf.'<td>'.$fila["NombVent"].'</td>';
				//TeleVent
				$salida.= $crlf.'<td>'.$fila["TeleVent"].'</td>';
				//MailVent
				$salida.= $crlf.'<td>'.$fila["MailVent"].'</td>';
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