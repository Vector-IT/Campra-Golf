<?php 
	session_start();
	
	include_once 'conexion.php';
	
	//Me fijo si se realizo una búsqueda
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$salida = "";
		
		$numeRuta = $_POST["NumeRuta"];
		$nombRuta = $_POST["NombRuta"];
		
		if ($_POST["Tipo"] == "1") {
			//Busqueda por dias
			
			$diaDesde = $_POST["DiaDesde"];
			
			$diaHasta = $_POST["DiaHasta"];
			
			$diaHasta = (int)$diaHasta - 1;
			if ($diaHasta == 0) {
				$diaHasta = 7;
			}

			$dias = (int)$_POST["Dias"];
			
			$strSQL = "SELECT ri.NumeItin, ri.Codigo, ri.Precio, ri.CiudadFin";
			$strSQL.= " FROM rutasmexicoitinerarios ri";
			$strSQL.= " WHERE ri.NumeItin IN (SELECT NumeItin";
			$strSQL.= "                       FROM rutasmexicoitinerariosdetalles rid";
			$strSQL.= "                       WHERE rid.NumeOrde = 1 ";
			$strSQL.= "                       AND rid.DiaSemana = {$diaDesde})";
			$strSQL.= " AND ri.NumeItin IN (SELECT rid.NumeItin";
			$strSQL.= "                     FROM rutasmexicoitinerariosdetalles rid";
			$strSQL.= "                     GROUP BY rid.NumeItin";
			$strSQL.= "                     HAVING COUNT(*) = {$dias})";
			/*
			$strSQL.= " AND ri.NumeItin IN (SELECT NumeItin";
			$strSQL.= "                       FROM rutasmexicoitinerariosdetalles rid";
			$strSQL.= "                       WHERE rid.Fin = 1 ";
			$strSQL.= "                       AND rid.DiaSemana = {$diaHasta})";*/
			$strSQL.= " AND ri.NumeRuta = {$numeRuta}";
			
			$itinerarios = cargarTabla($strSQL);
		}
		else {
			$ciudad = strtoupper($_POST["Ciudad"]);
			$dias = (int)$_POST["Dias"] - 1;
			
			$strSQL = "SELECT ri.NumeItin, ri.Codigo, ri.Precio, ri.CiudadFin";
			$strSQL.= " FROM rutasmexicoitinerarios ri";
			$strSQL.= " WHERE ri.NumeItin IN (SELECT rid.NumeItin";
			$strSQL.= "                       FROM rutasmexicoitinerariosdetalles rid";
			$strSQL.= "                       INNER JOIN rutasmexicodias rd ON rid.NumeDia = rd.NumeDia";
			$strSQL.= "                       WHERE rid.NumeOrde = 1";
			$strSQL.= "                       AND UPPER(rd.Ciudades) LIKE '%{$ciudad}%')";
			$strSQL.= " AND ri.NumeItin IN (SELECT rid.NumeItin";
			$strSQL.= "                     FROM rutasmexicoitinerariosdetalles rid";
			$strSQL.= "                     GROUP BY rid.NumeItin";
			$strSQL.= "                     HAVING COUNT(*) = {$dias})";
			$strSQL.= " AND ri.NumeRuta = {$numeRuta}";
	
			$itinerarios = cargarTabla($strSQL);
		}
		
		if (isset($itinerarios)) {
			if ($itinerarios->num_rows > 0) {
				while ($fila = $itinerarios->fetch_array()) {
					$strSQL = "SELECT rid.DiaSemana, rd.Ciudades, rd.Descripcion, rd.Imagen";
					$strSQL.= " FROM rutasmexicoitinerariosdetalles rid";
					$strSQL.= " INNER JOIN rutasmexicodias rd ON rid.NumeDia = rd.NumeDia";
					$strSQL.= " INNER JOIN rutasmexicoitinerarios ri ON rid.NumeItin = ri.NumeItin";
					$strSQL.= " WHERE rid.NumeItin = " . $fila["NumeItin"];
					$strSQL.= " ORDER BY rid.NumeOrde";
					
					$dias = cargarTabla($strSQL);
						
					$salida.= $crlf.'<div class="row" style="margin-top: 20px;">';
					$salida.= $crlf.'<div class="col-md-10">';
					$salida.= $crlf.'<h3>'. $nombRuta .' '.$fila["Codigo"].'</h3>';
					$salida.= $crlf.'</div>';
					$salida.= $crlf.'<div class="col-md-2">';
					if (isset($_SESSION['is_logged_in'])) {
						$salida.= $crlf.'<h3><span class="cajaNegra clickable" onclick="verPDF('.$fila["NumeItin"].');">Descargar PDF</span></h3>';
					}
					$salida.= $crlf.'</div>';
			
					$I = 1;
					$diaNro = 0;
					while ($fila2 = $dias->fetch_array()) {
						
						$diaSemana = "";
						$diaNro = (int)$fila2["DiaSemana"];
						
						switch ($diaNro) {
							case 1:
								$diaSemana = "Lunes";
								break;
							case 2:
								$diaSemana = "Martes";
								break;
							case 3:
								$diaSemana = "Mi&eacute;rcoles";
								break;
							case 4:
								$diaSemana = "Jueves";
								break;
							case 5:
								$diaSemana = "Viernes";
								break;
							case 6:
								$diaSemana = "S&aacute;bado";
								break;
							case 7:
								$diaSemana = "Domingo";
								break;
						}
			
						$salida.= $crlf.'<div class="row" style="margin-top: 10px; padding-bottom: 10px; border-bottom: 1px solid #999;">';
						$salida.= $crlf.'<div class="col-md-3">';
						if ($fila2["Imagen"] != "") {
							$salida.= $crlf.'<img src="admin/'.$fila2["Imagen"].'" class="full-width" />';
						}
						$salida.= $crlf.'</div>';
						$salida.= $crlf.'<div class="col-md-9">';
						$salida.= $crlf.'<h4>DIA '. $I .' - '. $diaSemana .'</h4>';
						$salida.= $crlf.'<p>'. $fila2["Ciudades"] .'</p>';
						$salida.= $crlf.'<p>'. $fila2["Descripcion"] .'</p>';
						$salida.= $crlf.'</div>';
						$salida.= $crlf.'</div>';
			
						$I++;
					}
					
					//Agrego el ultimo dia
					if ($diaNro != 0) {
						$diaNro++;
						
						if ($diaNro > 7) {
							$diaNro = 1;
						}
					
						switch ($diaNro) {
							case 1:
								$diaSemana = "Lunes";
								break;
							case 2:
								$diaSemana = "Martes";
								break;
							case 3:
								$diaSemana = "Mi&eacute;rcoles";
								break;
							case 4:
								$diaSemana = "Jueves";
								break;
							case 5:
								$diaSemana = "Viernes";
								break;
							case 6:
								$diaSemana = "S&aacute;bado";
								break;
							case 7:
								$diaSemana = "Domingo";
								break;
						}

						$salida.= $crlf.'<div class="row" style="margin-top: 10px; padding-bottom: 10px; border-bottom: 1px solid #999;">';
						$salida.= $crlf.'<div class="col-md-3">';
						$salida.= $crlf.'</div>';
						$salida.= $crlf.'<div class="col-md-9">';
						$salida.= $crlf.'<h4>DIA '. $I .' - '. $diaSemana .'</h4>';
						$salida.= $crlf.'<p>'. $fila["CiudadFin"] .'</p>';
						$salida.= $crlf.'<p>Desayuno. Traslado a su punto de salida. Fin de nuestros servicios.</p>';
						$salida.= $crlf.'</div>';
						$salida.= $crlf.'</div>';
					}
					
					$salida.= $crlf.'</div>';
				}
			}
			else {
				$salida.= $crlf.'<div class="row" style="margin-top: 20px;">';
				$salida.= $crlf.'<div class="col-md-12"><h3>NO SE ENCONTRARON ITINERARIOS QUE COINCIDAN CON LOS DATOS.</h3></div>';
				$salida.= $crlf.'</div>';
			}
				
			$itinerarios->free();
		}
		
		echo $salida;
	}
?>