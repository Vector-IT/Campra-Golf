<?php
session_start();

include_once 'admin/php/conexion.php';
require('fpdf/fpdf.php');

$NumeFlyer = $_GET["NumeFlyer"];
$Imagen = "admin/temp/". $_SESSION["NumeAgen"] .' - '. $NumeFlyer .'.png';

list($width, $height) = getimagesize($Imagen);

$pdf = new FPDF();

if ($width >= $height) {
	$pdf->AddPage('L', array($width, $height));
}
else {
	$pdf->AddPage('P', array($width, $height));
}

$pdf->Image($Imagen, 0, 0, $width, $height);


$pdf->Output('D', 'Flyer' . $_SESSION["NumeAgen"] .' - '. $NumeFlyer . '.pdf');
//$pdf->Output();
?>