<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	include("conexion.php");
	
	$user = strtoupper(str_replace("'", "", $_POST["usuario"]));
	$pass = strtoupper(str_replace("'", "", $_POST["password"]));
	
	$strSQL = "SELECT u.NumeUsua, u.NombComp, u.TipoUsua, ur.NombComp NombUsuaRefe, u.NumeAgen, u.NombMail";
	$strSQL.= ", u.chkBanners, u.chkAgencias, u.chkAgRegistros, u.chkExperiencias, u.chkTours, u.chkBlog, u.chkFlyers, u.chkCotizaciones, u.chkUsuarios";
	$strSQL.= ", a.NombAgen, a.NumeProv";
	$strSQL.= " FROM usuarios u";
	$strSQL.= " LEFT JOIN usuarios ur ON u.NumeUsuaRefe = ur.NumeUsua";
	$strSQL.= " LEFT JOIN (SELECT a.NumeAgen, a.NombAgen, COALESCE(ar.NumeProv, -1) NumeProv";
	$strSQL.= "				FROM agencias a";
	$strSQL.= "				LEFT JOIN agenciasregistradas ar ON a.NumeAgenRegi = ar.NumeAgen";
	$strSQL.= "				)a ON u.NumeAgen = a.NumeAgen";
	$strSQL.= " WHERE u.NumeEsta = 1";
	$strSQL.= " AND u.TipoUsua IN (1, 2, 3)";
	$strSQL.= " AND u.NombUsua = '{$user}'";
	$strSQL.= " AND u.NombPass = '{$pass}'";
	
	$tabla = cargarTabla($strSQL);
	
	$strSalida = "";
	
	if ($tabla->num_rows > 0)
	{
		session_start();
		$fila = $tabla->fetch_array();
		$_SESSION['is_logged_in'] = 1;
		$_SESSION['NumeUsua'] = $fila['NumeUsua'];
		$_SESSION['NombUsua'] = $fila['NombComp'];
		$_SESSION['TipoUsua'] = $fila["TipoUsua"];
		$_SESSION['NombUsuaRefe'] = $fila["NombUsuaRefe"];
		$_SESSION['NumeAgen'] = $fila["NumeAgen"];
		$_SESSION['NombAgen'] = $fila["NombAgen"];
		$_SESSION['NombMail'] = $fila["NombMail"];
		$_SESSION['NumeProv'] = $fila["NumeProv"];
		
		$_SESSION['chkBanners'] = $fila["chkBanners"];
		$_SESSION['chkAgencias'] = $fila["chkAgencias"];
		$_SESSION['chkAgRegistros'] = $fila["chkAgRegistros"];
		$_SESSION['chkExperiencias'] = $fila["chkExperiencias"];
		$_SESSION['chkTours'] = $fila["chkTours"];
		$_SESSION['chkBlog'] = $fila["chkBlog"];
		$_SESSION['chkFlyers'] = $fila["chkFlyers"];
		$_SESSION['chkCotizaciones'] = $fila["chkCotizaciones"];
		$_SESSION['chkUsuarios'] = $fila["chkUsuarios"];
		
		$tabla->free();
	}
	else {
		//Error
		if ($_POST["returnUrl"] == "-1") {
			echo "Error";
		}
		else {
			header("Location:../login.php?error=1");
			die();
		}
	}
}

if ($_POST["returnUrl"] == "-1") {
	echo "Ok";
}
else if ($_POST["returnUrl"] == "")
	header("Location:../index.php");
else
	header("Location:".$_POST["returnUrl"]);
//die();

?>
