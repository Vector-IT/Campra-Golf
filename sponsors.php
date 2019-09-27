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
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<title>Sponsors</title>
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

		<!-- fotorama-->
		<link  href="fotorama/fotorama.css" rel="stylesheet"> 
		
		<style>
			@import url('fonts/Helvetica-Bold.css');
			@import url('fonts/HelveticaNeueLTStd-Cn.css');
			@import url('fonts/HelveticaNeueLTStd-LtCn.css');
			@import url('fonts/HelveticaNeueLTStd-CnO.css');
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
	<?php include_once("analyticstracking.php") ?>
		<div class="wrapper top-style">
			<?php
				include_once 'encabezado.php';
			?>
			<div  id="sponsors" class="container">
				<div class="row">
					<div class="col-md-10 col-md-offset-1" style="margin-top:60px; margin-bottom: 0px; ">
						<div class="col-md-4" style="margin-top:0px;">
							<a href="http://fedegolfcba.com.ar/" target="_blank"><img class="img-responsive aligncenter" src="images/fgpc.jpg" width="180px" height="auto"></a>
						</div>
						<div class="col-md-4" style="margin-top:30px;">
							<a href="http://infoenard.org.ar/" target="_blank"><img class="img-responsive aligncenter" src="images/enard.jpg" width="180px" height="auto"></a>
						</div>							
						<div class="col-md-4" style="margin-top:30px;">
							<a href="http://cmp.callawaygolf.com/" target="_blank"><img class="img-responsive aligncenter" src="images/callaway.png" width="170px" height="auto"></a>
						</div>										
					</div>
				</div>
			<div class="container">
				<div class="row">
					<div class="col-md-10 col-md-offset-1" style="margin-top:40px; margin-bottom: 10px;">
						<p style="padding: 15px;color: #000;font-size: 23px;line-height: 36px;text-align: center;padding: 0px 5%;"><span style="color: #000;  font-family: 'HelveticaNeueLTStd-Cn' !important;">Campra Golf</span> tiene el privilegio de contar con el patrocinio de <span style="color: #000;  font-family: 'HelveticaNeueLTStd-Cn' !important;">Callaway Golf</span> y el apoyo de la <span style="color: #000;  font-family: 'HelveticaNeueLTStd-Cn' !important;">Federación de Golf de la Provincia de Córdoba (FGPC)</span> y el <span style="color: #000;  font-family: 'HelveticaNeueLTStd-Cn' !important;">Ente Nacional de Alto Rendimiento Deportivo (ENARD).</span>
						<br/><br/>Agradecemos su confianza en Campra Golf y sostenemos el compromiso de mutua colaboración. </p>
					</div>
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
		<!-- fotorama-->
		<script src="fotorama/fotorama.js"></script> 
		<script src="js/back-to-top.js"></script> <script src="js/login-modal.js" type="text/javascript"></script>   
	</body>
</html>