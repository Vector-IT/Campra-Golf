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
	
	//Cargo las imagenes del banner
	$bannerimg = cargarTabla("SELECT Imagen FROM rutasmexicobanner ORDER BY NumeOrde");
	
	//Cargo las rutas
	$rutas = cargarTabla("SELECT NumeRuta, Nombre, Descripcion, ImgPrevia, Dominio FROM rutasmexico ORDER BY Nombre");
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<title>Rutas M&eacute;xico</title>
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

		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="js/bootstrap.min.js"></script>
		<script src="js/back-to-top.js"></script> 
		<script src="js/login-modal.js" type="text/javascript"></script>   

		<link rel="stylesheet" type="text/css" href="css/kenburns.css">
		<script type="text/javascript" src="js/kenburns.js"></script>
		
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
		
		<style>
			@import url('fonts/FranklinGothic-Book.css');
			@import url('fonts/Helvetica-Bold.css');
			@import url('fonts/HelveticaNeueLTStd-Cn.css');
			@import url('fonts/HelveticaNeueLTStd-CnO.css');
			@import url('fonts/Humanist521BT-Light.css');
			@import url('fonts/MyriadWebPro-Bold.css');
			@import url('fonts/MyriadWebPro-Italic.css');
			@import url('fonts/MyriadWebPro-Regular.css');
			
			input[type=text], 
			input[type=password], 
			input[type=email],
			select, 
			textarea {
				margin-top: 0;
			}
		</style>
		
		<script type='text/javascript'>
			$(document).ready(function(){
				
				$("#kenburns-slideshow").css({
					"width": $("#kenburns-slideshow").parent().width() + "px",
					"height": ($("#kenburns-slideshow").parent().width() * 0.3743922204213938) + "px"
				});

				$( window ).resize(function() {
					$("#kenburns-slideshow").css({
						"width": $("#kenburns-slideshow").parent().width() + "px",
						"height": ($("#kenburns-slideshow").parent().width() * 0.3743922204213938) + "px"
					});
				});

			    $('#kenburns-slideshow').Kenburns({
			    	nextBtn: "#kenSlideNext",
				    prevBtn: "#kenSlidePrev",
			    	images: [
				<?php
					$salida = "";
					$bannerimg->data_seek(0);
					while ($fila = $bannerimg->fetch_array()) {
						$salida.= $crlf.'"admin/'.$fila["Imagen"].'",';
					}
					echo $salida;
				?>
			    	],
			    	
			    	scale:0.8,
			    	duration:8000,
			    	fadeSpeed:1200,
			    	ease3d:'cubic-bezier(0.445, 0.050, 0.550, 0.950)',
			    });
				
			});
		</script>
	</head>
	<body>
	<?php include_once("analyticstracking.php") ?>
		<div class="wrapper top-style">
			<?php
				include_once 'encabezado.php';
			?>
			
			<div class="container" style=" max-height: 462px;">
				<div class="row">
					<div id="kenburns-slideshow">
						<span id="kenSlidePrev" class="kenBtn clickable"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span></span>
						<span id="kenSlideNext" class="kenBtn clickable"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></span>
					</div>
				</div>
			</div>
			
			<div class="container" style="margin-top: 20px; border-top: 1px solid #ddd;">
				<div class="col-md-10 col-md-offset-1" id="intro">
					<h1>Itinerarios &Uacute;nicos de M&eacute;xico</h1>
					<p class="sub-intro">
						Ofrecemos nuestras rutas, estancias y excursiones con las que podr&aacute;n conocer 
						una de las 7 Maravillas del Mundo Moderno, todas las ciudades Mexicanas Patrimonio 
						Mundial, sus Pubelos M&aacute;gicos y Tesoros Coloniales en una modalidad &uacute;til y 
						c&oacute;moda.
					</p>
					<p class="sub-intro" style="font-weight: bold; color: black !important;">¡Beneficios y Descuentos!</p>
					<p class="sub-intro">
						<img alt="" src="images/img-rutas.png" style="width: 80%; height: auto;">
					</p>
				</div>
			</div>
			<div class="container">
				<div class="col-md-10 col-md-offset-1" style="margin-top:40px; margin-bottom: 10px;">
					<div class="row">
						
						<?php
							$salida = "";
							$blnEspacio = 0;
							while ($fila = $rutas->fetch_array()) {
								if ($blnEspacio == 1) {
									$salida.= $crlf.'<div class="col-md-1"></div>';
								}
								elseif ($blnEspacio == 2) {
									$blnEspacio = 0;
									$salida.= $crlf.'<div class="clearer"></div>';
								}
								
								$salida.= $crlf.'<div class="rutaPrevia">';
								$salida.= $crlf.'<a href="ruta/'.$fila["Dominio"].'"><h2 class="tituloRuta">'. $fila["Nombre"] .'</h2></a>';
								
								$salida.= $crlf.'<div class="imgPreviaRuta">';
								$salida.= $crlf.'<a href="ruta/'.$fila["Dominio"].'">';
								$salida.= $crlf.'<img src="admin/'. $fila["ImgPrevia"] .'" class="img-responsive" />';
								$salida.= $crlf.'</a>';
								$salida.= $crlf.'</div>';
								
								$salida.= $crlf.'<div class="descripcionRuta">'.$fila["Descripcion"].'</div>';
								$salida.= $crlf.'<div><a href="ruta/'.$fila["Dominio"].'" target="_self"><span class="glyphicon glyphicon glyphicon-plus-sign" aria-hidden="true" style="font-size: 20px; float: right !important;"></span></a></div>';
								$salida.= $crlf.'</div>';
								
								$blnEspacio++;	
							}
							echo $salida;
						?>
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
							<li><a href="#atencionAgencias" data-toggle="modal">Atención Agencias</a></li>
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
	</body>
</html>
<?php 
	if (isset($bannerimg))
		$bannerimg->free();
	
	if (isset($rutas))
		$rutas->free();
?>