<?php 
	include("conexion.php");
	
	if (!isset($_GET["user"]))
		die();
	
	$numeUsua = $_GET["user"];
	
	$strSQL = "UPDATE usuarios SET NumeEsta = 1 WHERE NumeUsua = " . $numeUsua;
	
	$conn = new mysqli($dbhost, $dbuser, $dbpass, $db) or die("Problemas al conectar con la BD");
	$conn->query($strSQL);
	
	header("Location:http://www.iconntravel.com.mx");
?>