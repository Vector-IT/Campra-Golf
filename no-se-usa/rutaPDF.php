<?php
	session_start();
	
	include_once 'admin/php/conexion.php';
	require('fpdf/fpdf.php');
	
	$numeItin = $_GET["Itin"];
	
	//Cargo los datos de la agencia
	$imagAgen = "admin/";
	if (isset($_SESSION['is_logged_in']))
		$imagAgen.= buscarDato("SELECT Imagen FROM agencias WHERE NumeAgen = " . $_SESSION["NumeAgen"]);
	
	if ($imagAgen == "admin/")
		$imagAgen.= "imgAgencias/1.png";
	
	$strSQL = "SELECT ri.NumeRuta, r.Nombre, r.Descripcion, r.ImgPrevia, ri.Codigo, ri.Precio, ri.CiudadFin";
	$strSQL.= " FROM rutasmexicoitinerarios ri";
	$strSQL.= " INNER JOIN rutasmexico r ON ri.NumeRuta = r.NumeRuta";
	$strSQL.= " WHERE ri.NumeItin = {$numeItin}";
	
	$ruta = cargarTabla($strSQL);
	$fila = $ruta->fetch_array();
	
	$numeRuta = $fila["NumeRuta"];
	$nombRuta = $fila["Nombre"];
	$descRuta = $fila["Descripcion"];
	$imgPrevia = "admin/" . $fila["ImgPrevia"];
	$codigo = $fila["Codigo"];
	$precio = $fila["Precio"];
	$ciudadFin = $fila["CiudadFin"];
	
	$strSQL = "SELECT rid.DiaSemana, rd.Ciudades, rd.Descripcion, rd.Imagen";
	$strSQL.= " FROM rutasmexicoitinerariosdetalles rid";
	$strSQL.= " INNER JOIN rutasmexicodias rd ON rid.NumeDia = rd.NumeDia";
	$strSQL.= " INNER JOIN rutasmexicoitinerarios ri ON rid.NumeItin = ri.NumeItin";
	$strSQL.= " WHERE rid.NumeItin = " . $numeItin;
	$strSQL.= " ORDER BY rid.NumeOrde";
	$itinerario = cargarTabla($strSQL);
	
	$diaInicio = buscarDato("SELECT DiaSemana FROM rutasmexicoitinerariosdetalles rid WHERE rid.NumeItin = {$numeItin} ORDER BY rid.NumeOrde LIMIT 1");
	switch ($diaInicio) {
		case 1:
			$diaInicio = "Lunes";
			break;
		case 2:
			$diaInicio = "Martes";
			break;
		case 3:
			$diaInicio = "Miércoles";
			break;
		case 4:
			$diaInicio = "Jueves";
			break;
		case 5:
			$diaInicio = "Viernes";
			break;
		case 6:
			$diaInicio = "Sábado";
			break;
		case 7:
			$diaInicio = "Domingo";
			break;
	}
	
	$pdf = new FPDF();
	$pdf->AddPage();
	
	$pdf->Image($imagAgen, 10, $pdf->GetY(), 50);
	$pdf->Ln(20);
	
	$pdf->SetFont('Arial', 'B', 18);
	$pdf->Cell(40, 15, utf8_decode($nombRuta . ' - ' . $codigo. ' ' . $precio), 0, 0);
	$pdf->Ln(6);
	$pdf->SetFont('Arial', '', 12);
	$pdf->Cell(40, 15, utf8_decode('(Salida todos los '. $diaInicio . ' del año)'), 0, 0);
	$pdf->Ln(20);
	
	$pdf->Image($imgPrevia, 10, $pdf->GetY(), 50);
	$pdf->Cell(55);
	$pdf->MultiCell(140, 6, utf8_decode($descRuta));
	$pdf->Ln(20);
	
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(40, 15, utf8_decode("Itinerario"), 0, 0);
	$pdf->Ln(15);
	
	$yImagen = 90;
	$I = 1;
	$diaNro = 0;
	while ($fila = $itinerario->fetch_array()) {
		$diaSemana = "";
		$diaNro = (int)$fila["DiaSemana"];
		
		switch ($diaNro) {
			case 1:
				$diaSemana = "Lunes";
				break;
			case 2:
				$diaSemana = "Martes";
				break;
			case 3:
				$diaSemana = "Miércoles";
				break;
			case 4:
				$diaSemana = "Jueves";
				break;
			case 5:
				$diaSemana = "Viernes";
				break;
			case 6:
				$diaSemana = "Sábado";
				break;
			case 7:
				$diaSemana = "Domingo";
				break;
		}
		
		//Si esta muy abajo hago un salto de hoja
		if ($pdf->GetY() >= 230) {
			$pdf->AddPage();
		}
		
		if ($fila["Imagen"] != "") {
			$yImagen = $pdf->GetY();
			$pdf->Image("admin/".$fila["Imagen"], 10, $yImagen, 50);
		}
		$pdf->Cell(55);
		$pdf->SetFont('Arial', 'B', 10);
		//$pdf->SetTextColor(51, 153, 51);
		$pdf->MultiCell(130, 6, utf8_decode('Día '. $I .' ('. $diaSemana .') - '. $fila["Ciudades"]));
		//$pdf->Ln(6);
		$pdf->Cell(55);
		$pdf->SetFont('Arial', '', 10);
		//$pdf->SetTextColor(0, 0, 0);
		
		$aux = $pdf->GetY();
	
		$pdf->MultiCell(130, 6, utf8_decode($fila["Descripcion"]));
		
		$aux2 = $pdf->GetY();
		
		//$pdf->Cell(55, 6, 'aux: ' . $aux . ' aux2: ' . $aux2);
		
		if ((abs($aux2 - $aux) < 32) && ($fila["Imagen"] != ""))
			$pdf->Ln(32 - ($aux2 - $aux));
		else 
			$pdf->Ln(10);
		
		$I++;
		$yImagen+= 35;
	}
//Agrego el último día
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
				$diaSemana = "Miércoles";
				break;
			case 4:
				$diaSemana = "Jueves";
				break;
			case 5:
				$diaSemana = "Viernes";
				break;
			case 6:
				$diaSemana = "Sábado";
				break;
			case 7:
				$diaSemana = "Domingo";
				break;
		}
		
		$pdf->Cell(55);
		$pdf->SetFont('Arial', 'B', 10);
		//$pdf->SetTextColor(51, 153, 51);
		$pdf->Cell(50, 6, utf8_decode('Día '. $I .' ('. $diaSemana .') - '. $ciudadFin));
		$pdf->Ln(6);
		$pdf->Cell(55);
		$pdf->SetFont('Arial', '', 10);
		//$pdf->SetTextColor(0, 0, 0);
		$pdf->MultiCell(130, 6, utf8_decode("Desayuno. Traslado a su punto de salida. Fin de nuestros servicios."));
		$pdf->Ln(10);
	}
	
	//Hoteles
	//Si esta muy abajo hago un salto de hoja
	if ($pdf->GetY() >= 230) {
		$pdf->AddPage();
	}
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(190, 15, utf8_decode("HOTELES PREVISTOS"), 0, 0, 'C');
	$pdf->Ln(15);
	
	$pdf->SetDrawColor(0, 0, 0);
	$pdf->SetFillColor(0, 0, 0);
	$pdf->SetTextColor(255, 255, 255);
	$pdf->SetFont('Arial', 'B', 10);
	
	$pdf->Cell(75, 6, utf8_decode("CIUDADES"), 1, 0, 'C', true);
	$pdf->Cell(35, 6, utf8_decode("ESTANCIA"), 1, 0, 'C', true);
	$pdf->Cell(35, 6, utf8_decode("CADENA"), 1, 0, 'C', true);
	$pdf->Cell(45, 6, utf8_decode("NOMBRE DEL HOTEL"), 1, 0, 'C', true);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->Ln(6);
	
	$hoteles = cargarTabla("SELECT h.NombHotel, h.Cadena, h.Ciudad, rh.Estancia FROM hoteles h INNER JOIN rutasmexicoitinerarioshoteles rh ON h.NumeHotel = rh.NumeHotel WHERE rh.NumeItin = {$numeItin}");
	while ($fila = $hoteles->fetch_array()) {
		//$pdf->Ln(6);
		if (strlen($fila["NombHotel"]) <= 27)
			$alto = 6;
		else
			$alto = 12;
		
		$pdf->SetDrawColor(0, 0, 0);
		$pdf->SetTextColor(0, 0, 0);
		
		$pdf->SetFont('Arial', 'B', 10);
		$pdf->Cell(75, $alto, utf8_decode($fila["Ciudad"]), 1, 0, 'C', false);
		$pdf->SetFont('Arial', '', 10);
		if ($fila["Estancia"] == "1") {
			$pdf->Cell(35, $alto, utf8_decode($fila["Estancia"] . ' Noche'), 1, 0, 'C', false);
		}
		else {
			$pdf->Cell(35, $alto, utf8_decode($fila["Estancia"] . ' Noches'), 1, 0, 'C', false);
		}
		$pdf->Cell(35, $alto, utf8_decode($fila["Cadena"]), 1, 0, 'C', false);
		$pdf->MultiCell(45, 6, utf8_decode($fila["NombHotel"]), 1, 'C');
	}
	
	$pdf->ln(10);
	$pdf->Cell(190, 15, utf8_decode("En caso necesario les ofreceríamos otros hoteles de igual categoría."), 1, 0, 'C');
	$pdf->ln(20);
	
	//Excursiones
	if ($pdf->GetY() >= 210) {
		$pdf->AddPage();
	}
	
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(190, 15, utf8_decode("POSIBLES EXCURSIONES OPCIONALES"), 0, 0, 'C');
	$pdf->Ln(15);

	$excursiones = cargarTabla("SELECT e.Titulo, e.Ciudad, e.Descripcion, e.Precio FROM excursiones e INNER JOIN rutasmexicoitinerariosexcursiones re ON e.NumeExcu = re.NumeExcu WHERE re.NumeItin = {$numeItin}");
	while ($fila = $excursiones->fetch_array()) {
		//Si esta muy abajo hago un salto de hoja
		if ($pdf->GetY() >= 230) {
			$pdf->AddPage();
		}
		
		$pdf->Image("images/icono-vineta.png", 10, $pdf->GetY(), 8);

		$pdf->SetFont('Arial', 'B', 10);
		$pdf->Cell(10);
		$pdf->Cell(75, 8, utf8_decode($fila["Titulo"] .' - '. $fila["Precio"]), 0, 0, '', false);
		$pdf->Ln(8);
		
		$pdf->SetFont('Arial', 'B', 10);
		$pdf->Cell(10);
		$pdf->Cell(23, 6, utf8_decode('Ciudad: '), 0, 0, '', false);
		$pdf->SetFont('Arial', '', 10);
		$pdf->MultiCell(140, 6, utf8_decode($fila["Ciudad"]));
		//$pdf->Ln(8);
		
		$pdf->SetFont('Arial', 'B', 10);
		$pdf->Cell(10);
		$pdf->Cell(23, 6, utf8_decode('Descripción: '), 0, 0, '', false);
		$pdf->SetFont('Arial', '', 10);
		$pdf->MultiCell(140, 6, utf8_decode($fila["Descripcion"]));
		$pdf->Ln(10);
	}
	
	$pdf->SetFont('Arial', '', 10);
	$pdf->Cell(190, 15, utf8_decode("Precios en Pesos Mexicanos. Las Excursiones se adquieren directamente durante el desarrollo del tour."), 1, 0, 'C');
	
	$pdf->Output('D', 'Itinerario.pdf');
	//$pdf->Output();
	
	$ruta->free();
	$itineratio->free();
	
	if (isset($hoteles))
		$hoteles->free();
	
	if (isset($excursiones))
		$excursiones->free();
?>