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
	
	if (isset($_POST["NumeRuta"])) {
		$NumeRuta = $_POST["NumeRuta"];
	}
	
	if (isset($_POST["Codigo"])) {
		$Codigo = $_POST["Codigo"];
	}
	
	if (isset($_POST["Precio"])) {
		$Precio = $_POST["Precio"];
	}
	
	if (isset($_POST["CiudadFin"])) {
		$CiudadFin = $_POST["CiudadFin"];
	}
	
	if (isset($_POST["NumeHotel"])) {
		$NumeHotel = $_POST["NumeHotel"];
	}
	
	if (isset($_POST["Estancia"])) {
		$Estancia = $_POST["Estancia"];
	}
	
	if (isset($_POST["NumeExcu"])) {
		$NumeExcu = $_POST["NumeExcu"];
	}
	
	if (isset($_POST["NumeDias"])) {
		$NumeDias = $_POST["NumeDias"];
	}
	
	if (isset($_POST["DiaSemana"])) {
		$DiaSemana = $_POST["DiaSemana"];
	}
	
	if (isset($_POST["NumeOrde"])) {
		$NumeOrde = $_POST["NumeOrde"];
	}
	
	switch ($operacion) {
		case 0: //INSERT
			$NumeItin = buscarDato("SELECT COALESCE(MAX(NumeItin), 0) + 1 FROM rutasmexicoitinerarios");

			$strSQL = "INSERT INTO rutasmexicoitinerarios(NumeItin, NumeRuta, Codigo, Precio, CiudadFin)";
			$strSQL.= " VALUES({$NumeItin}, {$NumeRuta}, '{$Codigo}', '{$Precio}', '{$CiudadFin}')";
			
			$resultado = ejecutarCMD($strSQL);
			if ($resultado) {
				//HOTELES
				if ($NumeHotel != "") {
					$aNumeHotel = explode("@@", $NumeHotel);
					$aEstancia = explode("@@", $Estancia);
						
					for ($I = 0; $I < count($aNumeHotel); $I++) {
						$strSQL = "INSERT INTO rutasmexicoitinerarioshoteles(NumeItin, NumeHotel, Estancia)";
						$strSQL.= " VALUES (". $NumeItin;
						$strSQL.= ", ". $aNumeHotel[$I];
						$strSQL.= ", " . $aEstancia[$I];
						$strSQL.= ")";
				
						$resultado = ejecutarCMD($strSQL);
					}
				}
				
				//EXCURSIONES
				if ($NumeExcu != "") {
					$aNumeExcu = explode("@@", $NumeExcu);
				
					for ($I = 0; $I < count($aNumeExcu); $I++) {
						$strSQL = "INSERT INTO rutasmexicoitinerariosexcursiones(NumeItin, NumeExcu)";
						$strSQL.= " VALUES (". $NumeItin;
						$strSQL.= ", ". $aNumeExcu[$I];
						$strSQL.= ")";
				
						$resultado = ejecutarCMD($strSQL);
					}
				}
				
				//DIAS
				if ($NumeDias != "") {
					//$NumeDias = "'" . str_replace("@@", "', '", $NumeDias) . "'";
					$aNumeDias = explode("@@", $NumeDias);
					$aDiaSemana = explode("@@", $DiaSemana);
					$aNumeOrde = explode("@@", $NumeOrde);
					
					for ($I = 0; $I < count($aNumeDias); $I++) {
						$strSQL = "INSERT INTO rutasmexicoitinerariosdetalles(NumeItin, NumeDia, NumeOrde, DiaSemana, Fin)";
						$strSQL.= " VALUES (". $NumeItin;
						$strSQL.= ", ". $aNumeDias[$I];
						$strSQL.= ", " . $aNumeOrde[$I];
						$strSQL.= ", " . $aDiaSemana[$I];
						
						if ($I != (count($aNumeDias) - 1)) {
							$strSQL.= ", 0)";
						}
						else {
							$strSQL.= ", 1)";
						}
						
						$resultado = ejecutarCMD($strSQL);
					}
				}
				
				echo "Itinerario creado!";
			}
			else {
				echo "Error al crear itinerario:<br />" . $resultado . "<br />" . $strSQL;
			}

			break;

		case 1: //UPDATE
			$strSQL = "UPDATE rutasmexicoitinerarios";
			$strSQL.= " SET Codigo = '{$Codigo}'";
			$strSQL.= ", Precio = '{$Precio}'";
			$strSQL.= ", CiudadFin = '{$CiudadFin}'";
			$strSQL.= " WHERE NumeItin = " . $NumeItin;
			
			$resultado = ejecutarCMD($strSQL);
			if ($resultado) {
				$resultado = ejecutarCMD("DELETE FROM rutasmexicoitinerariosdetalles WHERE NumeItin = " . $NumeItin);
				$resultado = ejecutarCMD("DELETE FROM rutasmexicoitinerarioshoteles WHERE NumeItin = " . $NumeItin);
				$resultado = ejecutarCMD("DELETE FROM rutasmexicoitinerariosexcursiones WHERE NumeItin = " . $NumeItin);
				
				//HOTELES
				if ($NumeHotel != "") {
					$aNumeHotel = explode("@@", $NumeHotel);
					$aEstancia = explode("@@", $Estancia);
				
					for ($I = 0; $I < count($aNumeHotel); $I++) {
						$strSQL = "INSERT INTO rutasmexicoitinerarioshoteles(NumeItin, NumeHotel, Estancia)";
						$strSQL.= " VALUES (". $NumeItin;
						$strSQL.= ", ". $aNumeHotel[$I];
						$strSQL.= ", " . $aEstancia[$I];
						$strSQL.= ")";
				
						$resultado = ejecutarCMD($strSQL);
					}
				}
				
				//EXCURSIONES
				if ($NumeExcu != "") {
					$aNumeExcu = explode("@@", $NumeExcu);
				
					for ($I = 0; $I < count($aNumeExcu); $I++) {
						$strSQL = "INSERT INTO rutasmexicoitinerariosexcursiones(NumeItin, NumeExcu)";
						$strSQL.= " VALUES (". $NumeItin;
						$strSQL.= ", ". $aNumeExcu[$I];
						$strSQL.= ")";
				
						$resultado = ejecutarCMD($strSQL);
					}
				}
				
				//DIAS
				if ($NumeDias != "") {
					$aNumeDias = explode("@@", $NumeDias);
					$aDiaSemana = explode("@@", $DiaSemana);
					$aNumeOrde = explode("@@", $NumeOrde);
					
					for ($I = 0; $I < count($aNumeDias); $I++) {
						$strSQL = "INSERT INTO rutasmexicoitinerariosdetalles(NumeItin, NumeDia, NumeOrde, DiaSemana, Fin)";
						$strSQL.= " VALUES (". $NumeItin;
						$strSQL.= ", ". $aNumeDias[$I];
						$strSQL.= ", " . $aNumeOrde[$I];
						$strSQL.= ", " . $aDiaSemana[$I];
						
						if ($I != (count($aNumeDias) - 1)) {
							$strSQL.= ", 0)";
						}
						else {
							$strSQL.= ", 1)";
						}

						$resultado = ejecutarCMD($strSQL);
					}
				}
			
				echo "Itinerario modificado!";
			}
			else {
				echo "Error al modificar itinerario:<br />" . $resultado . "<br />" . $strSQL;
			}
			break;

		case 2: //DELETE
			$resultado = ejecutarCMD("DELETE FROM rutasmexicoitinerarios WHERE NumeItin = " . $NumeItin);
		
			$resultado = ejecutarCMD("DELETE FROM rutasmexicoitinerariosdetalles WHERE NumeItin = " . $NumeItin);
			$resultado = ejecutarCMD("DELETE FROM rutasmexicoitinerarioshoteles WHERE NumeItin = " . $NumeItin);
			$resultado = ejecutarCMD("DELETE FROM rutasmexicoitinerariosexcursiones WHERE NumeItin = " . $NumeItin);
				
			if ($resultado)
				echo "Itinerario borrado!";
			else
				echo "Error al borrar itinerario:<br />(" . $resultado . "<br />" . $strSQL;

			break;

		case 10: //LISTAR
			$strSQL = "SELECT NumeItin, Codigo, Precio, CiudadFin";
			$strSQL.= " FROM rutasmexicoitinerarios";
			$strSQL.= " WHERE NumeRuta = " . $NumeRuta;
			$strSQL.= " ORDER BY NumeItin";

			$tabla = cargarTabla($strSQL);

			$salida = '';

			if (mysqli_num_rows($tabla) > 0) {
				$salida.= $crlf.'<table class="table table-striped table-condensed">';
				$salida.= $crlf.'<tr>';
				$salida.= $crlf.'<th>Numero</th>';
				$salida.= $crlf.'<th>C&oacute;digo</th>';
				$salida.= $crlf.'<th>Precio</th>';
				$salida.= $crlf.'<th></th>';
				$salida.= $crlf.'<th></th>';
				$salida.= $crlf.'</tr>';
								 
	    		while ($fila = $tabla->fetch_array()) {
    				$salida.= $crlf.'<tr>';
	    			
    				$strSQL = "SELECT NumeDia, NumeOrde, DiaSemana";
    				$strSQL.= " FROM rutasmexicoitinerariosdetalles";
    				$strSQL.= " WHERE NumeItin = " . $fila["NumeItin"];
    				
    				$tabla2 = cargarTabla($strSQL);
    				
    				$NumeDias = '';
    				$NumeOrde = '';
    				$DiaSemana = '';
    				
    				while ($fila2 = $tabla2->fetch_array()) {
    					if ($NumeDias != "") {
    						$NumeDias.= "@@";
    						$DiaSemana.= "@@";
    						$NumeOrde.= "@@";
    					}
    				
    					$NumeDias.= $fila2["NumeDia"];
    					$NumeOrde.= $fila2["NumeOrde"];
    					$DiaSemana.= $fila2["DiaSemana"];
    				}
    				if (isset($tabla2)) {
    					$tabla2->free();
    				}
    				
    				$strSQL = "SELECT NumeHotel, Estancia";
    				$strSQL.= " FROM rutasmexicoitinerarioshoteles";
    				$strSQL.= " WHERE NumeItin = " . $fila["NumeItin"];
    				
    				$tabla2 = cargarTabla($strSQL);
    				
    				$NumeHotel = '';
    				$Estancia = '';
    				while ($fila2 = $tabla2->fetch_array()) {
    					if ($NumeHotel!= "") {
    						$NumeHotel.= "@@";
    						$Estancia.= "@@";
    					}
    				
    					$NumeHotel.= $fila2["NumeHotel"];
    					$Estancia.= $fila2["Estancia"];
    				}
    				if (isset($tabla2)) {
    					$tabla2->free();
    				}
    				
    				$strSQL = "SELECT NumeExcu";
    				$strSQL.= " FROM rutasmexicoitinerariosexcursiones";
    				$strSQL.= " WHERE NumeItin = " . $fila["NumeItin"];
    				
    				$tabla2 = cargarTabla($strSQL);
    				
    				$NumeExcu = '';
    				while ($fila2 = $tabla2->fetch_array()) {
    					if ($NumeExcu!= "") {
    						$NumeExcu.= "@@";
    					}
    				
    					$NumeExcu.= $fila2["NumeExcu"];
    				}
    				if (isset($tabla2)) {
    					$tabla2->free();
    				}
    				
					//Numero
					$salida.= $crlf.'<td id="NumeItin'.$fila[0].'">'.$fila[0];
					$salida.= $crlf.'<input type="hidden" id="NumeHotel'.$fila[0].'" value="'.$NumeHotel.'" />';
					$salida.= $crlf.'<input type="hidden" id="Estancia'.$fila[0].'" value="'.$Estancia.'" />';
					$salida.= $crlf.'<input type="hidden" id="NumeExcu'.$fila[0].'" value="'.$NumeExcu.'" />';
					$salida.= $crlf.'<input type="hidden" id="NumeDias'.$fila[0].'" value="'.$NumeDias.'" />';
					$salida.= $crlf.'<input type="hidden" id="NumeOrde'.$fila[0].'" value="'.$NumeOrde.'" />';
					$salida.= $crlf.'<input type="hidden" id="DiaSemana'.$fila[0].'" value="'.$DiaSemana.'" />';
					$salida.= $crlf.'<input type="hidden" id="CiudadFin'.$fila[0].'" value="'.$fila["CiudadFin"].'" />';
					$salida.= $crlf.'</td>';
					//Codigo
					$salida.= $crlf.'<td id="Codigo'.$fila[0].'">'.$fila["Codigo"].'</td>';
					//Precio
					$salida.= $crlf.'<td id="Precio'.$fila[0].'">'.$fila["Precio"].'</td>';
						
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