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
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<title>Contacto Agencia</title>
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
			$(document).ready(function() {
				$('#comentario').css('overflow', 'hidden').autogrow({vertical: true, horizontal: false});
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
						<img src="images/principal-contacto-agencia.jpg" class="img-responsive" alt="" width="1234" height="434" longdesc="">
					</div>
				</div>
			</div>
			<div class="container" style="border-top: 1px solid #ddd;">
				<h1 class="text-center">Contacto</h1>
			</div>
			<div class="container">
				<div class="col-md-10 col-md-offset-1" style="margin-top:40px; margin-bottom: 10px;">
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
					<div class="row" style="margin-top: 20px;">
						<form id="contacto-agencia" class="form-horizontal">
							<div class="form-group">
								<label for="tipoContacto" class="control-label col-md-2">En qu&eacute; podemos ayudarte:</label>
								<div class="col-md-5">
									<select class="form-control" id="tipoContacto">
										<option selected>Sugerencia</option>
										<option>Quejas</option>
										<option>Otros</option> 
									</select>
								</div>
							</div>							
							<div class="form-group">
								<label for="nombre" class="control-label col-md-2">Nombre:</label>
								<div class="col-md-5">
									<input type="text" class="form-control" id="nombre" required value="<?php echo $_SESSION["NombUsua"];?>" />
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
									<select class="form-control" id="provincia" required>
										<?php echo cargarCombo("SELECT NumeProv, NombProv FROM provincias ORDER BY NombProv", "NumeProv", "NombProv", $_SESSION["NumeProv"], true, "Todos"); ?>
									</select>
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