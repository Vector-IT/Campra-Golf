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
	
	if (isset($_POST["Excursiones"])) {
		$Excursiones = $_POST["Excursiones"];
	}

	switch ($operacion) {
		case 10: //LISTAR
			$strSQL = "SELECT NumeExcu";
			$strSQL.= " FROM rutasmexicoitinerariosexcursiones";
			$strSQL.= " WHERE NumeItin = " . $NumeItin;

			$tabla = cargarTabla($strSQL);

			$salida = '';

    		while ($fila = $tabla->fetch_array()) {
    			if ($salida != "") {
    				$salida.= "@@";
    			}
    				
				$salida.= $fila["NumeExcu"];
			}

			if (isset($tabla)) {
				$tabla->free();				
	    	}
	    	
	    	echo $salida;
	
	    	break;
	    	
		default:
			ejecutarCMD("DELETE FROM rutasmexicoitinerariosexcursiones WHERE NumeItin = " . $NumeItin);
			
			if ($Excursiones != "") {
				$Excursiones = "'" . str_replace("@@", "', '", $Excursiones) . "'";
				ejecutarCMD("INSERT INTO rutasmexicoitinerariosexcursiones(NumeItin, NumeExcu) SELECT {$NumeItin}, NumeExcu FROM excursiones WHERE NumeExcu IN ({$Excursiones})");
			}
			
			echo "Datos actualizados!";
			
			break;
	}
	
?>