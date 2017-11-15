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
		<title>Atención Agencias</title>
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
						<img src="images/principal_atencion-agencias.jpg" class="img-responsive" alt="" width="1234" height="434" longdesc="">
					</div>
				</div>
			</div>
			<div class="container" style="margin-top: 20px;">
				<div class="row">
					<div class="col-md-10 col-md-offset-1" id="intro" style="margin-bottom: 20px;">
						<h1>Si quieres acceder a los precios y tarifas, ¡regístrate!</h1>
						<p class="sub-intro"> Obtén todos los beneficios de trabajar con Nosotros.<br><br>  
							<span style="color: #E4CD74; font-weight: bold; padding: 10px; background-color: cadetblue;">Tú promueves experiencias de vida para tus clientes, nosotros te ayudamos a lograrlo.</span> 
						</p>
					</div>
				</div>
				<div class="row">
					<div class="col-md-10 col-md-offset-1"  style="margin-top: 0px;">
						<div class="col-md-3 iconBox">
							<h3 class="iconBox-title"><span class="glyphicon glyphicon-gift" aria-hidden="true"></span><br>Sin Cargos</h3>
							<p class="iconBox-text">Ser una agencia afiliada a Iconn Travel es ¡Gratis! No hay cargos ni cuotas adicionales.</p>
						</div>
						<div class="col-md-3 iconBox">
							<h3 class="iconBox-title"><span class="glyphicon glyphicon-flag" aria-hidden="true"></span><br>Sin Metas Fijas</h3>
							<p class="iconBox-text">No existe un mínimo de reservaciones o pagos por renovación de afiliación.</p>
						</div>
						<div class="col-md-3 iconBox">
							<h3 class="iconBox-title"><span class="glyphicon glyphicon-flash" aria-hidden="true"></span><br>Innovación</h3>
							<p class="iconBox-text">Constante renovación de productos y servicios.</p>
						</div>
						<div class="col-md-3 iconBox">
							<h3 class="iconBox-title"><span class="glyphicon glyphicon-usd" aria-hidden="true"></span><br>Monedas que Aceptamos</h3>
							<p class="iconBox-text">Las transferencias y pagos pueden ser en dólares americanos y pesos mexicanos.</p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-10 col-md-offset-1"  style="margin-top: 0px; ">
						<div class="col-md-3 iconBox">
							<h3 class="iconBox-title"><span class="glyphicon glyphicon-certificate" aria-hidden="true"></span><br>Compartimos Nuestros Medios</h3>
							<p class="iconBox-text">Podr&aacute;s personalizar nuestros materiales para impresi&oacute;n de flyers y env&iacute;o de mailings a tus clientes.</p>
						</div>
						<div class="col-md-3 iconBox">
							<h3 class="iconBox-title"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span><br>
								Comisión Por Venta 
							</h3>
							<p class="iconBox-text">Te ofrecemos un porcentaje de comisión ya sea por venta de servicios individuales (hoteles, tours, renta autos, traslados) o por servicios empaquetados.</p>
						</div>
						<div class="col-md-3 iconBox">
							<h3 class="iconBox-title"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span><br>Atención Personalizada</h3>
							<p class="iconBox-text">Contamos con asesores de viaje especializados de lunes a s&aacute;bado en horario de oficina.</p>
						</div>
						<div class="col-md-3 iconBox">
							<h3 class="iconBox-title"><span class="glyphicon glyphicon-bullhorn" aria-hidden="true"></span><br>Promociones</h3>
							<p class="iconBox-text">
								Ofrece nuestras promociones para tus clientes con tarjetas de crédito BBVA <em>Bancomer</em> - Desde 7, hasta 18 meses sin intereses.
							</p>
						</div>
					</div>
				</div>
				<div class="row" style="margin-top: 30px;">
					<div class="col-md-1"></div>
					<div class="col-md-10" >
						<div class="form-group">
							<h4 class="panel-heading" style="text-align: left !important;  padding-left: 15px;">Alta de Agencias</h4>
							<form id="atencionAgencias">
								<div class="row">
									<div class="col-md-6">
										<label for="nombreAgencia" style="color:  #C99F37 !important; font-size: 18px;"><strong>Datos de la Agencia</strong> </label>  
										<input type="text" class="form-control form-custom" id="nombreAgencia" name="name" placeholder="Nombre Comercial de la Agencia *" required>
										<label for="provincia"> </label>
										<select class="form-control form-custom" id="provincia" placeholder="Seleccione un estado">
										<?php
											echo cargarCombo("SELECT NumeProv, NombProv FROM provincias ORDER BY NombProv", "NumeProv", "NombProv");
										?>
										</select>
										<label for="direccion"> </label>
										<input type="text" class="form-control form-custom" id="direccion" name="name" placeholder="Dirección Completa *" required>
										<label for="telefonoAgencia"> </label>
										<input type="text" class="form-control form-custom" id="telefonoAgencia" name="name" placeholder="Teléfono *" required>
									</div>
									<div class="col-md-6" style="padding-top: 5px;">
										<label for="paginaWeb">&nbsp;</label>
										<input type="text" class="form-control form-custom" id="paginaWeb" name="name" placeholder="Página Web">
										<label for="razonSocial"> </label>
										<input type="text" class="form-control form-custom" id="razonSocial" name="name" placeholder="Razón Social de la Agencia *" required>
										<label for="iata"> </label>
										<input type="text" class="form-control form-custom" id="iata" name="name" placeholder="IATA">
										<label for="numeroSectur"> </label>
										<input type="text" class="form-control form-custom" id="numeroSectur" name="name" placeholder="Número de alta en SECTUR">
										<label for="rfc"> </label>
										<input type="text" class="form-control form-custom" id="rfc" name="name" placeholder="RFC" >
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<br> <br>
										<label for="nombreAdmin" style="color: #C99F37!important;"><strong>Datos del Contacto Administrativo</strong></label>
										<input type="text" class="form-control form-custom" id="nombreAdmin" name="name" placeholder="Nombre Completo *" required>
										<label for="telefonoAdmin"> </label>
										<input type="text" class="form-control form-custom" id="telefonoAdmin" name="name" placeholder="Teléfono *" required>
										<label for="emailAdmin"> </label>   
										<input type="email" class="form-control form-custom" id="emailAdmin" placeholder="Correo Electrónico *" required>
									</div>
									<div class="col-md-6">
										<br> <br>
										<label for="nombreVentas" style="color:  #C99F37!important;"><strong>Datos del Contacto de Ventas</strong></label>
										<input type="text" class="form-control form-custom" id="nombreVentas" name="name" placeholder="Nombre Completo *" required>
										<label for="telefonoVentas"> </label>
										<input type="text" class="form-control form-custom" id="telefonoVentas" name="name" placeholder="Teléfono *" required>
										<label for="emailVentas"> </label>   
										<input type="email" class="form-control form-custom" id="emailVentas" placeholder="Correo Electrónico *" required>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12" style="margin-top: 20px;">
										<label><input type="checkbox" required> He le&iacute;do el <a href="aviso-de-privacidad.php" target="blank" style="color: #C99F37;">Aviso de Privacidad</a></label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-9">
										<div class="div-msg-form">
											<span class="icon-form glyphicon" aria-hidden="true"></span>&nbsp;<span class="text-msg-form"></span>
										</div>
									</div>
									<div class="col-md-3">
										<button type="submit" class="btn btn-small pull-right"><span style="font-size: 12px;">Registrarse</span></button>
									</div>
								</div>  
							</form>
						</div>
					</div>
					<div class="col-md-1"></div>
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
		</div><a href="#0" class="cd-top"></a>
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="js/bootstrap.min.js"></script>
		<script src="http://maps.googleapis.com/maps/api/js?sensor=false&extension=.js&output=embed"></script>
		<script src="js/back-to-top.js"></script> 
		<script src="js/login-modal.js" type="text/javascript"></script>   
	</body>
</html>