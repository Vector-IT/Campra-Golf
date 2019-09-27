<?php
	session_start();
	include("admin/php/conexion.php");
	
	//Cargo los datos de la agencia
	if (isset($_GET["agencia"]))
		$numeAgen = $_GET["agencia"];
	else
		$numeAgen = "1";
	
	$agencia = cargarTabla("SELECT NombAgen, Imagen, Dominio, Telefono, Email, Facebook, Twitter, Instagram FROM agencias WHERE NumeAgen = {$numeAgen}");
	
	$fila = $agencia->fetch_array();
	
	$nombAgen = $fila["NombAgen"];
	$dominio = $fila["Dominio"];
	
	$imagAgen = "admin/";
	if (isset($_SESSION['is_logged_in']))
		$imagAgen.= buscarDato("SELECT Imagen FROM agencias WHERE NumeAgen = " . $_SESSION["NumeAgen"]);
	
	if ($imagAgen == "admin/")
		$imagAgen.= $fila["Imagen"];
	
	$teleAgen = $fila["Telefono"];
	$mailAgen = $fila["Email"];
	$faceAgen = $fila["Facebook"];
	$twitAgen = $fila["Twitter"];
	$instAgen = $fila["Instagram"];
		
	$agencia->free();
	
	//Cargo las experiencias
	$experiencias = cargarTabla("SELECT NumeExpe, NombExpe, Dominio FROM experiencias WHERE NumeEsta = 1 ORDER BY NumeOrde");
	
	if (isset($_GET["termino"]))
		$termino = $_GET["termino"];
	else
		$termino = "";
	
	if ($termino != "") {
		$strSQL = "SELECT t.NumeTour, t.NombTour, t.Dominio, t.Lugares, t.Copete, t.Articulo, 1 Tipo";
		$strSQL.= " FROM tours t";
		$strSQL.= " WHERE t.NumeEsta = 1";
		$strSQL.= " AND t.EnPromo = 0";
		$strSQL.= " AND (";
		$strSQL.= "   (t.NombTour LIKE '%{$termino}%') OR";
		$strSQL.= "   (t.Lugares LIKE '%{$termino}%') OR";
		$strSQL.= "   (t.Copete LIKE '%{$termino}%') OR";
		$strSQL.= "   (t.Subtitulo LIKE '%{$termino}%') OR";
		$strSQL.= "   (t.DescTour LIKE '%{$termino}%')";
		$strSQL.= " )";
		$strSQL.= " UNION ALL";
		$strSQL.= " SELECT b.NumeBlog, b.Titulo, b.Dominio, \"\", b.Copete, b.Imagen, 2 Tipo";
		$strSQL.= " FROM blog b";
		$strSQL.= " WHERE (b.Titulo LIKE '%{$termino}%') OR";
		$strSQL.= "   (b.Copete LIKE '%{$termino}%') OR";
		$strSQL.= "   (b.Descripcion LIKE '%{$termino}%') OR";
		$strSQL.= "   (b.Etiquetas LIKE '%{$termino}%')";
		
		$resultados = cargarTabla($strSQL);
	}
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<title>Resultados de b&uacute;squeda</title>
		<link rel="shortcut icon" href="images/favicon.ico" />
		<!-- Bootstrap -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/custom.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="css/back-to-top.css">
		<script src="js/modernizr.js"></script> <!-- Modernizr -->
		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
		<!-- must have -->
		
		<style>
			@import url('fonts/FranklinGothic-Book.css');
			@import url('fonts/Helvetica-Bold.css');
			@import url('fonts/HelveticaNeueLTStd-Cn.css');
			@import url('fonts/HelveticaNeueLTStd-CnO.css');
			@import url('fonts/Humanist521BT-Light.css');
			@import url('fonts/MyriadWebPro-Bold.css');
			@import url('fonts/MyriadWebPro-Italic.css');
			@import url('fonts/MyriadWebPro-Regular.css');
		</style>
				<!-- Facebook Pixel Code -->
				<script>
		!function(f,b,e,v,n,t,s)
		{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
		n.callMethod.apply(n,arguments):n.queue.push(arguments)};
		if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
		n.queue=[];t=b.createElement(e);t.async=!0;
		t.src=v;s=b.getElementsByTagName(e)[0];
		s.parentNode.insertBefore(t,s)}(window, document,'script',
		'https://connect.facebook.net/en_US/fbevents.js');
		fbq('init', '1345305482241846');
		fbq('track', 'PageView');
		</script>
		<noscript><img height="1" width="1" style="display:none"
		src="https://www.facebook.com/tr?id=1345305482241846&ev=PageView&noscript=1"
		/></noscript>
		<!-- End Facebook Pixel Code -->
		<?php

include_once 'header-links.php';

?>
	</head>
	<body>
	
		<div class="wrapper top-style">
			<?php
				include_once 'encabezado.php';
			?>
			<div class="container">
				<div class="row">
					<div class="media">
						<img src="images/principal_blog.jpg" class="img-responsive" alt="" width="1234" height="434" longdesc="">
					</div>
				</div>
			</div>
			<div class="container" style="margin-top:20px;">
				<div class="row">
					<div class="col-md-10 col-md-offset-1" id="intro" style="margin-bottom: 30px;">
						<h1>Resultados de: <?php echo $termino;?></h1>
					</div>
				</div>
				<div class="row">
					<div class="col-md-1"></div>
					<div class="col-md-10">
<!-- RESULTADOS -->
					<?php
						$strSalida = "";
						while ($fila = $resultados->fetch_array()) {
							$strSalida.= $crlf.'<div id="experiencias">';
							
							if ($fila["Tipo"] == "1")
								$strSalida.= $crlf.'<a href="tour/'.$fila["Dominio"].'" target="_self">';
							else
								$strSalida.= $crlf.'<a href="blog/'.$fila["Dominio"].'" target="_self">';
							
							if ($fila["Tipo"] == "1")
								$strSalida.= $crlf.'<h2>Tour: '.$fila["NombTour"].'</h2>';
							else 
								$strSalida.= $crlf.'<h2>Blog: '.$fila["NombTour"].'</h2>';
								
							$strSalida.= $crlf.'</a>';
							$strSalida.= $crlf.'<div class="img-hover">';
							
							if ($fila["Tipo"] == "1")
								$strSalida.= $crlf.'<a href="tour/'.$fila["Dominio"].'" target="_self"><img class="img-responsive" src="admin/'.$fila["Articulo"].'" width="100%" height="auto" alt=""></a>';
							else
								$strSalida.= $crlf.'<a href="blog/'.$fila["Dominio"].'" target="_self"><img class="img-responsive" src="admin/'.$fila["Articulo"].'" width="100%" height="auto" alt=""></a>';
							
							$strSalida.= $crlf.'</div>';
							$strSalida.= $crlf.'<h3 class="destinos">'.$fila["Lugares"].'</h3>';
							$strSalida.= $crlf.'<p class="descripcion">'.$fila["Copete"].'</p>';
							$strSalida.= $crlf.'<div class="experienciaAmpliar"><a href="tour/'.$fila["Dominio"].'" target="_self"><span class="glyphicon glyphicon glyphicon-plus-sign" aria-hidden="true" style="font-size: 20px; margin-right: 25px; float: right !important;"></span></a></div>';
							$strSalida.= $crlf.'</div>';
						}
						echo $strSalida;
						
						if (isset($resultados))
							$resultados->free();
					?>
					</div>
					<div class="col-md-1"></div>
				</div>
			</div>
			<?php
				include_once 'pie-de-pagina.php';
			?>
		</div>
		</div><a href="#0" class="cd-top"></a>
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="js/bootstrap.min.js"></script>
		<script src="js/back-to-top.js"></script> <script src="js/login-modal.js" type="text/javascript"></script>   
	</body>
</html>