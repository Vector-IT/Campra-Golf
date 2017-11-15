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
	
	//Cargo los datos de las experiencias
	$experiencias = cargarTabla("SELECT NumeExpe, NombExpe, Dominio, FotoBanner FROM experiencias WHERE NumeEsta = 1 ORDER BY NumeOrde");
	
	//Cargo las promociones
	$promociones = cargarTabla("SELECT NumeTour, NombTour, Dominio, Articulo, AbrirLink FROM tours WHERE EnPromo = 1 AND NumeEsta = 1 ORDER BY NombTour");
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<title>Promociones</title>
		<link rel="shortcut icon" href="images/favicon.ico" />
		<!-- Bootstrap -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/custom.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="css/back-to-top.css">

		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="js/bootstrap.min.js"></script>
		<script src="js/back-to-top.js"></script> 
		<script src="js/login-modal.js" type="text/javascript"></script>  

		<link rel="stylesheet" href="owlcarousel/owl.carousel.css" /> 
		<link rel="stylesheet" href="owlcarousel/owl.theme.css" />
		<script src="owlcarousel/owl.carousel.js"></script>     
		
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
	</head>
	<body>
	<?php include_once("analyticstracking.php") ?>
		<div class="wrapper top-style">
			<?php
				include_once 'encabezado.php';
			?>
			<div class="container">
				<div class="row">
					<div class="media"> <img src="images/promos.jpg" class="img-responsive" alt="" width="1234" height="434" longdesc=""></div>
				</div>
			</div>
			<div class="container" style="margin-top: 30px;">
				<div class="col-md-12 text-center">
					<h1>Promociones</h1>
				</div>
				<div class="row">
					<div class="col-md-10 col-md-offset-1"  style="margin-top: 30px;">
						<div id="owl-carousel" class="owl-theme">
						<?php
							$salida = "";
							
							while ($fila = $promociones->fetch_array()) {
								$salida.= $crlf.'<div>';
								if ($fila["AbrirLink"] == "1")
									$salida.= $crlf.'<a href="tour/'.$fila["Dominio"].'">';
								$salida.= $crlf.'<div class="img-hoverPromos">';
								$salida.= $crlf.'<img src="admin/'.$fila["Articulo"].'">';
								$salida.= $crlf.'</div>';
								$salida.= $crlf.'<h2 class="text-center" style="font-size: 16px !important;">'.$fila["NombTour"].'</h2>';
								if ($fila["AbrirLink"] == "1")
									$salida.= $crlf.'</a>';
								$salida.= $crlf.'</div>';
							}
							
							echo $salida;
						?>
						<!-- 
							<div>
								<a href="ruta/ruta-independencia">
									<div class="img-hoverPromos">
										<img src="images/promo-independencia/descuento-nextia.jpg">
									</div>
									<h2 class="text-center" style="font-size: 16px !important;">PROMOCIÓN 10 % DE DESCUENTO SALIDA 17 DE MAYO</h2>
								</a>
							</div>
						 -->
						</div>
					</div>
				</div>
			</div>
			<div class="container" style="margin-top:110px;">
				<div class="col-md-10 col-md-offset-1">
					<div class="media" style="text-align: center;">
						<img class="img-responsive center-block" src="images/partners/partners.png" alt="partners" longdesc="">
					</div>
				</div>
			</div>
			<!-- BEGIN # MODAL LOGIN -->
			<div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header" align="center">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
							</button>
						</div>
						<!-- Begin # DIV Form -->
						<div id="div-forms">
						<?php
							include_once("loginForm.php");
							include_once("registroForm.php");
						?>
						</div>
						<!-- End # DIV Form -->
					</div>
				</div>
			</div>
			<!-- END # MODAL LOGIN -->
			<?php 
				include_once 'agradecimiento.php';
			?>
			<footer>
				<div class="container" style="margin-top:5px;">
					<div class="col-md-10 col-md-offset-1">
						<ol class="nav-center breadcrumb footer-nav" style="font-family:'FranklinGothic-Book' !important; color: #535353; font-size: 13px;">
							<li><a href="quienes-somos.php">Quienes Somos</a></li>
							<li><a href="experiencias.php">Experiencias</a></li>
							<li><a href="promociones.php">Promociones</a></li>
							<li><a href="atencion-agencias.php">Atención Agencias</a></li>
							<li><a href="blog-de-viaje.php">Blog de Viaje</a></li>
							<li><a href="contacto.php">Contacto</a></li>
							<li><a href="aviso-de-privacidad.php">Aviso de privacidad</a></li>
						</ol>
					</div>
					<div class="col-md-12 text-center">
						<p><span style="text-transform: uppercase; text-align: center; font-family:'FranklinGothic-Book' !important; font-size: 13px;"><a href="index.php"><?php echo $nombAgen;?> 2015</a></span></p>
					</div>
				</div>
			</footer>
		</div>
		<a href="#0" class="cd-top"></a>
		<script type='text/javascript'>
			$(document).ready(function(){

				$('#owl-carousel').owlCarousel({
					loop:false,
					margin:10,
					autoplay:true,
					autoplayTimeout:6000,
					autoplayHoverPause:true,
					items:<?php if ($promociones->num_rows > 3) echo "3"; else echo ($promociones->num_rows);?>,
					nav:false
				});
				
				$('#1owl-carousel').owlCarousel({
					loop:true,
					margin:10,
					autoplay:true,
					autoplayTimeout:6000,
					autoplayHoverPause:true,
					responsiveClass:true,
					responsive:{
						0:{
							items:1,
							nav:false
						},
						600:{
							items:<?php if ($promociones->num_rows > 2) echo "2"; else echo $promociones->num_rows;?>,
							nav:false
						},
						1000:{
							items:<?php if ($promociones->num_rows > 3) echo "3"; else echo $promociones->num_rows;?>,
							nav:false,
							loop:true
						}
					}
				});
			});
		</script>
<?php 
	if (isset($promociones))
		$promociones->free();
?>
	</body>
</html>