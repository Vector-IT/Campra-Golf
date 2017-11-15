<?php
	session_start();
	include("admin/php/conexion.php");
	
	if (!isset($_SESSION['is_logged_in'])) {
		header("Location:index.php");
		die();
	}	
	
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
	
	//Filtros
	$experiencia = "-1";
	$tour = "-1";
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<title>Quiero Cotizar</title>
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
		<script src="js/back-to-top.js"></script> <script src="js/login-modal.js" type="text/javascript"></script>   
		<script src="js/jquery.ns-autogrow.min.js"></script>
		
		<link href="css/jquery.datetimepicker.css" rel="stylesheet" type="text/css" />
		<script src="js/jquery.datetimepicker.js"></script>
		
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
		
		<script type="text/javascript">
			function filtrarTours() {
				var NumeExpe = $("#experiencia").val();
				var NumeTour = "-1";

		    	if (window.XMLHttpRequest)
				{// code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp = new XMLHttpRequest();
				}
				else
				{// code for IE6, IE5
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState==4 && xmlhttp.status==200) {
						$("#tour").html(xmlhttp.responseText);
					}
				};

				xmlhttp.open("POST","admin/php/toursProcesar.php",true);
				xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
				xmlhttp.send("operacion=20&NumeTour=" + NumeTour + "&NumeExpe=" + NumeExpe);
			}

			$(document).ready(function() {
				$('#comentario').css('overflow', 'hidden').autogrow({vertical: true, horizontal: false});

				$('#fecha').datetimepicker({
					format:'Y-m-d',
					startDate:new Date(),
					value: new Date(),
					mask:false,
					timepicker:false
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
			<div class="container">
				<div class="row">
					<div class="media">
						<img src="images/principal-cotizar.jpg" class="img-responsive" alt="" width="1234" height="434" longdesc="">
					</div>
				</div>
			</div>
			<div class="container" style="border-top: 1px solid #ddd;">
				<h1 class="text-center">Cotizar</h1>
				
				<h3 class="text-center">¿Buscas otro tipo de experiencia?...¡Nosotros te ayudamos!</h3>
				
				<div class="col-md-10 col-md-offset-1" style="margin-bottom: 10px;">
					<div class="row">
						<h3>Nos interesa estar cada vez m&aacute;s cerca de ti</h3>
						<div class="col-md-8 col-md-offset-2" style="border: 2px solid black; padding: 0;">
							<table class="table table-striped table-condensed" style="margin-bottom: 0;">
								<tr>
									<td><i class="fa fa-envelope fa-fw"></i></td>
									<td>Env&iacute;anos un correo</td>
									<td><a target="_blank" href="mailto:iconntravel@iconnservices.com.mx">iconntravel@iconnservices.com.mx</a></td>
								</tr>
								<tr>
									<td><i class="fa fa-phone fa-fw"></i></td>
									<td>LL&aacute;manos!</td>
									<td><a target="_blank" href="tel:(55) 42101500">(55) 42101500</a></td>
								</tr>
								<tr>
									<td><i class="fa fa-facebook fa-fw"></i></td>
									<td>S&iacute;guenos en Facebook</td>
									<td><a target="_blank" href="https://www.facebook.com/iconntravel">ICONN TRAVEL</a></td>
								</tr>
							</table>
						</div>
					</div>
					<div class="row">
						<h3>Necesito me coticen lo siguiente:</h3>
						<form id="form-cotizacion" class="form-horizontal">
							<div class="form-group">
								<label for="codigo" class="control-label col-md-2">C&oacute;digo:</label>
								<div class="col-md-5">
									<input type="text" class="form-control" id="codigo" />
								</div>
							</div>
							<div class="form-group">
								<label for="experiencia" class="control-label col-md-2">Experiencia:</label>
								<div class="col-md-5">
									<select class="form-control" id="experiencia" name="experiencia" onchange="filtrarTours();">
										<?php echo cargarCombo("SELECT NumeExpe, NombExpe FROM experiencias WHERE NumeEsta = 1 ORDER BY NombExpe", "NumeExpe", "NombExpe", "", true, "Todas"); ?>

										<option value="-2">Rutas M&eacute;xico</option>
										<option value="-3">Otra</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="tour" class="control-label col-md-2">Tour:</label>
								<div class="col-md-5">
									<select class="form-control" name="tour" id="tour">
									<?php
										if ($experiencia == "-1")
											echo cargarCombo("SELECT NumeTour, NombTour FROM tours WHERE NumeEsta = 1 AND EnPromo = 0 ORDER BY NombTour", "NumeTour", "NombTour", $tour, true, "Todos");
										else 
											echo cargarCombo("SELECT NumeTour, NombTour FROM tours WHERE NumeEsta = 1 AND EnPromo = 0 AND NumeExpe = {$experiencia} ORDER BY NombTour", "NumeTour", "NombTour", $tour, true, "Todos");
									?>
										<option value="-2">Otros</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="nombre" class="control-label col-md-2">Nombre:</label>
								<div class="col-md-5">
									<input type="text" class="form-control" id="nombre" value="<?php echo $_SESSION["NombUsua"];?>" />
								</div>
							</div>
							<div class="form-group">
								<label for="agencia" class="control-label col-md-2">Agencia:</label>
								<div class="col-md-5">
									<input type="text" class="form-control" id="agencia" readonly value="<?php echo $_SESSION["NombAgen"];?>" />
								</div>
							</div>
							<div class="form-group">
								<label for="email" class="control-label col-md-2">Email:</label>
								<div class="col-md-5">
									<input type="email" class="form-control" id="email" required value="<?php echo $_SESSION["NombMail"];?>" />
								</div>
							</div>
							<div class="form-group">
								<label for="telefono" class="control-label col-md-2">Tel&eacute;fono:</label>
								<div class="col-md-5">
									<input type="tel" class="form-control" id="telefono" required />
								</div>
							</div>
							<div class="form-group">
								<label for="provincia" class="control-label col-md-2">Estado:</label>
								<div class="col-md-5">
									<select class="form-control" id="provincia">
										<?php echo cargarCombo("SELECT NumeProv, NombProv FROM provincias ORDER BY NombProv", "NumeProv", "NombProv", $_SESSION["NumeProv"], true, "Todos"); ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-7 text-center">
									<h4>Datos del pasajero</h4>
								</div>
							</div>
							<div class="form-group">
								<label for="pasajero" class="control-label col-md-2">Pasajero:</label>
								<div class="col-md-5">
									<input type="text" class="form-control" id="pasajero" />
								</div>
							</div>
							<div class="form-group">
								<label for="fecha" class="control-label col-md-2">Fecha de viaje:</label>
								<div class="col-md-5">
									<input type="text" class="form-control" id="fecha" required />
								</div>
							</div>
							<div class="form-group">
								<label for="origen" class="control-label col-md-2">Lugar de origen:</label>
								<div class="col-md-5">
									<input type="text" class="form-control" id="origen" />
								</div>
							</div>
							<div class="form-group">
								<label for="aereo" class="control-label col-md-2">Servicios a&eacute;reos:</label>
								<div class="col-md-5">
									<select class="form-control" id="aereo">
										<option value="0">NO</option>
										<option value="1" selected>SI</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="adulCant" class="control-label col-md-2">Adultos:</label>
								<div class="col-md-2">
									<input type="number" class="form-control" id="adulCant" min="0" max="10" />
								</div>
								<label for="adulEdad" class="control-label col-md-1">Edades:</label>
								<div class="col-md-2">
									<input type="text" class="form-control" id="adulEdad" />
								</div>
							</div>
							<div class="form-group">
								<label for="menoCant" class="control-label col-md-2">Menores:</label>
								<div class="col-md-2">
									<input type="number" class="form-control" id="menoCant" min="0" max="10" />
								</div>
								<label for="menoEdad" class="control-label col-md-1">Edades:</label>
								<div class="col-md-2">
									<input type="text" class="form-control" id="menoEdad" />
								</div>
							</div>
							<div class="form-group">
								<label for="infaCant" class="control-label col-md-2">Infantiles:</label>
								<div class="col-md-2">
									<input type="number" class="form-control" id="infaCant" min="0" max="10" />
								</div>
								<label for="infaEdad" class="control-label col-md-1">Edades:</label>
								<div class="col-md-2">
									<input type="text" class="form-control" id="infaEdad" />
								</div>
							</div>
							<div class="form-group">
								<label for="comentario" class="control-label col-md-2">Comentarios:</label>
								<div class="col-md-5">
									<textarea id="comentario" class="form-control" required></textarea>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-7">
									<div class="div-msg-form">
										<span class="icon-form glyphicon" aria-hidden="true"></span>&nbsp;<span class="text-msg-form"></span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-7 text-right">
									<input type="hidden" id="numeAgen" value="<?php echo $_SESSION["NumeAgen"];?>" />
									<input type="hidden" id="numeUsua" value="<?php echo $_SESSION["NumeUsua"];?>" />
								
									<button type="submit" class="btn btn-default">Enviar</button>
								</div>
							</div>
							
						</form>
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