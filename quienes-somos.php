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
		<title>Quienes Somos</title>
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
	</head>
	<body>

		<div class="wrapper top-style">
			<?php
				include_once 'encabezado.php';
			?>
			<div class="container" style="">
				<div class="row">
					<h1 class="sliderTitle">Campra Golf</h1>
					<p class="sliderDesc">Academia de golf de jerarquía mundial que crea un espacio de encuentro y aprendizaje para golﬁstas de todos los niveles. </p>
					<div class="fotorama" data-width="100%" data-height="55%" data-fit="cover" data-transition="crossfade"  data-autoplay="true" data-arrows="true"
     data-click="true"  data-loop="true" data-autoplay="true">
					<img src="images/quienes-somos.jpg">
					<img src="images/quienes-somos.jpg">
					</div>
				</div>
			</div>
			<div class="container">
				<div id="quienes-somos" class="row">
					<div class="col-md-10 col-md-offset-1">
						<h1 style="text-align: center;">Quienes Somos</h1>
						<p style="text-align: center;">Orientado a golﬁstas amateurs, profesionales, sociales, empresariales y a quienes se quieran iniciar en el deporte, Campra Golf - Pepa Campra y su excelente staff de instructores - vuelcan todo su conocimiento y experiencia en un espacio de última tecnología ubicado en el Driving Range Golf Academy de Villa Allende. <br/>
	<span style="color: #000;  font-family: 'HelveticaNeueLTStd-Cn' !important;">Campra Golf</span> cuenta con las mejores herramientas y softwares en el mercado - cámaras de video de alta deﬁnición, Boditrack, Trackman, V-1, K-Vest & Sam Putt Lab - mediante las cuales se pueden analizar diversos aspectos del swing y el juego de manera precisa, así como también realizar ﬁttings de palos.   </p>
					</div>
				</div>
			</div>
			<div id="staff" class="container">
				<div class="row">
					<div class="col-md-10 col-md-offset-1" style="margin-top:60px; margin-bottom: 0px; ">
						<div class="col-md-6"><img class="img-responsive" src="images/1.jpg" width="100%" height="auto"></div>
						<div class="col-md-6">
							<h2>STAFF</h2>
							<h3 style="">Jose Luis “Pepa” Campra</h3>
							<p>Reconocido en el golf latinoamericano por su notable carrera como jugador aﬁcionado y profesional, Pepa consagra su pasión por el juego y la instrucción en la realización de Campra Golf.  <br/>
Su reconocimiento como coach de primer nivel se atribuye a innumerables capacitaciones en el exterior con instructores de renombre como Butch Harmon & Sean Foley. Además, acumula conocimientos excepcionales como caddie en el PGA Tour de los mejores profesionales argentinos, entre ellos, Angel Cabrera y, actualmente Emiliano Grillo.</p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-10 col-md-offset-1" style="margin-top:40px; margin-bottom: 10px;">
						<p style="background: #11A66D;padding: 15px;color: #fff;font-size: 30px;line-height: 36px;text-align: center;margin-bottom: 35px;">Acompañan a Pepa y aportan su gran conocimiento técnico de la mano de un excelente manejo de las tecnologías a disposición.</p>
					</div>
				</div>				
				<div class="row">
					<div class="col-md-10 col-md-offset-1" style="margin-top:0px;">
						<div class="col-md-6">
							<img class="img-responsive aligncenter" src="images/2.jpg" width="" height="">
							<h3 style="text-align: center;">Maxi Lacuara</h3>
							<p style="text-align: center; padding: 3%;">Certiﬁcado de la <span style="color: #000;  font-family: 'HelveticaNeueLTStd-Cn' !important;">PGA Argentina</span> y lleva 6 años dedicado exclusivamente a la instrucción de golf.</p>
						</div>
						<div class="col-md-6">
							<img class="img-responsive aligncenter" src="images/3.jpg" width="" height="">
							<h3 style="text-align: center;">Martina Gavier</h3>
							<p style="text-align: center; padding: 3%;">Ex miembro de la <span style="color: #000;  font-family: 'HelveticaNeueLTStd-Cn' !important;">LPGA Symetra Tour</span> y su experiencia en coaching incluye participación en academias de renombre en los EEUU. </p>
						</div>
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