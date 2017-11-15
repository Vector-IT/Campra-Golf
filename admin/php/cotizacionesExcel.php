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
	
	header("content-disposition: attachment;filename=Cotizaciones.xls");
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
		$strSQL = "SELECT c.NumeCoti"; //0
		$strSQL.= ", c.FechCoti";//1
		$strSQL.= ", a.NombAgen";//2
		$strSQL.= ", c.Nombre";//3
		$strSQL.= ", c.Codigo";//4
		$strSQL.= ", e.NombExpe";//5
		$strSQL.= ", t.NombTour";//6
		$strSQL.= ", c.Email";//7
		$strSQL.= ", c.Telefono";//8
		$strSQL.= ", p.NombProv";//9
		$strSQL.= ", c.Pasajero";//10
		$strSQL.= ", c.FechViaj";//11
		$strSQL.= ", c.Origen";//12
		$strSQL.= ", c.Aereo";//13
		$strSQL.= ", c.AdulCant";//14
		$strSQL.= ", c.AdulEdad";//15
		$strSQL.= ", c.MenoCant";//16
		$strSQL.= ", c.MenoEdad";//17
		$strSQL.= ", c.InfaCant";//18
		$strSQL.= ", c.InfaEdad";//19
		$strSQL.= ", c.Comentario";//20
		$strSQL.= ", c.NumeEsta ";//21
		$strSQL.= ", c.NumeExpe ";//22
		$strSQL.= ", c.NumeTour ";//23
		$strSQL.= " FROM cotizaciones c";
		$strSQL.= " LEFT JOIN agencias a ON c.NumeAgen = a.NumeAgen";
		//$strSQL.= " LEFT JOIN usuarios u ON c.NumeUsua = u.NumeUsua";
		$strSQL.= " LEFT JOIN experiencias e ON c.NumeExpe = e.NumeExpe";
		$strSQL.= " LEFT JOIN tours t ON c.NumeTour = t.NumeTour";
		$strSQL.= " LEFT JOIN provincias p ON c.NumeProv = p.NumeProv";
		$strSQL.= " ORDER BY c.NumeCoti desc";
		
		$tabla = cargarTabla($strSQL);
		
		$salida = '';
		
		if (mysqli_num_rows($tabla) > 0) {
			$salida.= $crlf.'<table class="table table-striped table-condensed">';
			$salida.= $crlf.'<tr>';
			$salida.= $crlf.'<th>Numero</th>';
			$salida.= $crlf.'<th>Fecha</th>';
			$salida.= $crlf.'<th>Agencia</th>';
			$salida.= $crlf.'<th>Usuario</th>';
			$salida.= $crlf.'<th>C&oacute;digo</th>';
			$salida.= $crlf.'<th>Experiencia</th>';
			$salida.= $crlf.'<th>Tour</th>';
			$salida.= $crlf.'<th>Email</th>';
			$salida.= $crlf.'<th>Tel&eacute;fono</th>';
			$salida.= $crlf.'<th>Estado</th>';
			$salida.= $crlf.'<th>Pasajero</th>';
			$salida.= $crlf.'<th>Fecha de viaje</th>';
			$salida.= $crlf.'<th>Origen</th>';
			$salida.= $crlf.'<th>Svc aereos</th>';
			$salida.= $crlf.'<th>Cant. Adultos</th>';
			$salida.= $crlf.'<th>Edad Adultos</th>';
			$salida.= $crlf.'<th>Cant. Menores</th>';
			$salida.= $crlf.'<th>Edad Menores</th>';
			$salida.= $crlf.'<th>Cant. Infantiles</th>';
			$salida.= $crlf.'<th>Edad Infantiles</th>';
			$salida.= $crlf.'<th>Comentarios</th>';
			$salida.= $crlf.'<th>Status</th>';
			$salida.= $crlf.'</tr>';
				
			while ($fila = $tabla->fetch_array()) {
				$salida.= $crlf.'<tr>';
					
				$salida.= $crlf.'<td>'.$fila[0].'</td>';
				$salida.= $crlf.'<td>'.$fila[1].'</td>';
				$salida.= $crlf.'<td>'.$fila[2].'</td>';
				$salida.= $crlf.'<td>'.$fila[3].'</td>';
				$salida.= $crlf.'<td>'.$fila[4].'</td>';
				switch ($fila[22]) {
					case -3:
						$salida.= $crlf.'<td>OTRA</td>';
						break;
					case -2:
						$salida.= $crlf.'<td>RUTAS MEXICO</td>';
						break;
					case -1:
						$salida.= $crlf.'<td>TODAS</td>';
						break;
					default:
						$salida.= $crlf.'<td>'.$fila[5].'</td>';
						break;
				}
				
				switch ($fila[23]) {
					case -2:
						$salida.= $crlf.'<td>OTRO</td>';
						break;
					case -1:
						$salida.= $crlf.'<td>TODOS</td>';
						break;
					default:
						$salida.= $crlf.'<td>'.$fila[6].'</td>';
						break;
				}
				$salida.= $crlf.'<td>'.$fila[7].'</td>';
				$salida.= $crlf.'<td>'.$fila[8].'</td>';
				$salida.= $crlf.'<td>'.$fila[9].'</td>';
				$salida.= $crlf.'<td>'.$fila[10].'</td>';
				$salida.= $crlf.'<td>'.$fila[11].'</td>';
				$salida.= $crlf.'<td>'.$fila[12].'</td>';
				if ($fila[13] == 1)
					$salida.= $crlf.'<td>SI</td>';
				else
					$salida.= $crlf.'<td>NO</td>';
				$salida.= $crlf.'<td>'.$fila[14].'</td>';
				$salida.= $crlf.'<td>'.$fila[15].'</td>';
				$salida.= $crlf.'<td>'.$fila[16].'</td>';
				$salida.= $crlf.'<td>'.$fila[17].'</td>';
				$salida.= $crlf.'<td>'.$fila[18].'</td>';
				$salida.= $crlf.'<td>'.$fila[19].'</td>';
				$salida.= $crlf.'<td>'.$fila[20].'</td>';
				
				//Estado
				switch ($fila[21]) {
					case "1":
						$salida.= $crlf.'<td>Activa</td>';
						break;
					case "2":
						$salida.= $crlf.'<td>Cancelada</td>';
						break;
					case "3":
						$salida.= $crlf.'<td>A Solicitud</td>';
						break;
					case "4":
						$salida.= $crlf.'<td>Venta Concretada</td>';
						break;
				}
				$salida.= $crlf.'</tr>';
			}
		
			$salida.= $crlf.'</table>';
		}
		$tabla->free();
		
		echo $salida;
	?>
	</body>

</html>