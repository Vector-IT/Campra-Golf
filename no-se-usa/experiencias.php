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
	
	//Cargo los tours
	$tours = cargarTabla("SELECT NumeTour, NombTour, Dominio, Articulo, NumeExpe, Posicion FROM tours WHERE NumeEsta = 1 AND EnPromo = 0 ORDER BY NumeExpe, NombTour");
	
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<title>Experiencias</title>
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
			.thumbnails {
				list-style: outside none none;
			}
			.span4 {
				display: inline-flex; 
				width:300px; 
				margin-right: 5px; 
				margin-left: 5px; 
				margin-bottom: 30px;
			}
			.span4 h2 {
				padding-left: 30px;
				padding-right: 30px;
			}
			.img {
				display: inline-block;
			}
		</style>
	</head>
	<body onLoad="initializeMaps()">
	<?php include_once("analyticstracking.php") ?>
		<div class="wrapper top-style">
			<?php
				include_once 'encabezado.php';
			?>
			<div class="container">
				<div class="row">
					<div id="map_canvas" style="width:100%; height:434px"></div>
				</div>
			</div>
			<div class="container" style="margin-top: 30px;">
				<div class="col-md-12 text-center">
					<h1>Experiencias</h1>
				</div>
				<div class="container" style="margin-top: 30px;">
					<div class="row">
						<div class="col-md-10 col-md-offset-1"  style="margin-top: 30px;">
							<div style="border-bottom:1px solid #ddd;">
								<ul class="nav nav-tabs">
									<li role="presentation"><a data-toggle="portfilter" data-target="all" style="cursor: pointer;" class="activo" onclick="marcarExperiencia(this);">Todas</a></li>
								<?php
									$experiencias->data_seek(0);
									while ($fila = $experiencias->fetch_array()) {
										echo '<li role="presentation"><a data-toggle="portfilter" data-target="'.$fila["NumeExpe"].'" style="cursor: pointer;" onclick="marcarExperiencia(this);">'.$fila["NombExpe"].'</a></li>';
									}
								?>
								</ul>
							</div>
							<div class="clearfix"  style="margin-bottom:30px;"></div>
							<br />
							<ul class="thumbnails gallery center-block">
							<?php
								$salida = "";
								
								while ($fila = $tours->fetch_array()) {
									$salida.= $crlf. '<li class="span4" data-tag="'.$fila["NumeExpe"].'">';
									$salida.= $crlf. '<a href="tour/'.$fila["Dominio"].'">';
									$salida.= $crlf. '<div class="img-hoverExperiencias"><img src="admin/'.$fila["Articulo"].'" width="300" height="auto" class="img-responsive img-rounded" alt="" longdesc=""></div>';
									$salida.= $crlf. '<h2 class="text-center" style="font-size: 16px !important;">'.$fila["NombTour"].'</h2>';
									$salida.= $crlf. '</a>';
									$salida.= $crlf. '</li>';
								}
								
								echo $salida;
							?>
							</ul>
							<div class="push"></div>
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
				<div id="atencionAgencias" class="modal fade">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-body">
								<div class="form-group" style=" border: 1px solid #69717E; padding: 30px; margin-bottom: 0px !important;">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
									<h4 class="modal-title">Alta de Agencias</h4>
									<form>
										<br> 
										<div class="row">
											<div class="col-md-6">
												<label for="nombreAgencia">Datos de la Agencia </label>  
												<input type="text" class="form-control form-custom" id="nombreAgencia" name="name" placeholder="Nombre Comercial de la Agencia *" required>
												<label for="direccion"> </label>
												<input type="text" class="form-control form-custom" id="direccion" name="name" placeholder="Dirección Completa *" required>
												<label for="telefonoAgencia"> </label>
												<input type="text" class="form-control form-custom" id="telefonoAgencia" name="name" placeholder="Teléfono *" required>
												<label for="paginaWeb"> </label>
												<input type="text" class="form-control form-custom" id="paginaWeb" name="name" placeholder="Página Web *" required>
											</div>
											<div class="col-md-6">
												<label for="razonSocial"> </label>
												<input type="text" class="form-control form-custom" id="razonSocial" name="name" placeholder="Razón Social de la Agencia *" required>
												<label for="iata"> </label>
												<input type="text" class="form-control form-custom" id="iata" name="name" placeholder="IATA">
												<label for="numeroSectur"> </label>
												<input type="text" class="form-control form-custom" id="numeroSectur" name="name" placeholder="Número de alta en SECTUR">
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<br> 
												<label for="nombreAdmin">Datos del Contacto Administrativo</label>
												<input type="text" class="form-control form-custom" id="nombreAdmin" name="name" placeholder="Nombre Completo *" required>
												<label for="telefonoAdmin"> </label>
												<input type="text" class="form-control form-custom" id="telefonoAdmin" name="name" placeholder="Teléfono *" required>
												<label for="emailAdmin"> </label>   
												<input type="email" class="form-control form-custom" id="emailAdmin" placeholder="Correo Electrónico *" required>
											</div>
											<div class="col-md-6">
												<br> 
												<label for="nombreVentas">Datos del Contacto de Ventas</label>
												<input type="text" class="form-control form-custom" id="nombreVentas" name="name" placeholder="Nombre Completo *" required>
												<label for="telefonoVentas"> </label>
												<input type="text" class="form-control form-custom" id="telefonoVentas" name="name" placeholder="Teléfono *" required>
												<label for="emailVentas"> </label>   
												<input type="email" class="form-control form-custom" id="emailVentas" placeholder="Correo Electrónico *" required>
											</div>
										</div>
										<br>
										<button type="submit" class="btn btn-small center-block"><span style="font-size: 12px;">Registrarse</span></button> 
								</div>
								</form>	
							</div>
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
		<a href="#0" class="cd-top"></a>
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="js/bootstrap.min.js"></script>
		<script src="js/bootstrap-portfilter.js"></script>
		<script src="js/back-to-top.js"></script> 
		<script src="http://maps.googleapis.com/maps/api/js?sensor=false&extension=.js&output=embed"></script>
		<script type="text/javascript">
			var markers = [
			<?php
				$salida = "";
				
				$tours->data_seek(0);
				while ($fila = $tours->fetch_array()) {
					if ($fila["Posicion"] != "")
						$salida.= $crlf. '[\'<a href="tour/'.$fila["Dominio"].'">'.$fila["NombTour"].'<br><img src="admin/'.$fila["Articulo"].'" class="img-responsive" alt="" width="405" height="255" longdesc=""></a>\', '.$fila["Posicion"].'],';
				}
				
				echo rtrim($salida, ",");
				
				if (isset($experiencias))
					$experiencias->free();
				
				if (isset($tours))
					$tours->free();
			?>				
			];
			
			function initializeMaps() {
				var myOptions = {
					mapTypeId: google.maps.MapTypeId.ROADMAP,
					mapTypeControl: true
				};
				var map = new google.maps.Map(document.getElementById("map_canvas"),myOptions);
				var infowindow = new google.maps.InfoWindow(); 
				var marker, i;
				var bounds = new google.maps.LatLngBounds();
			
				for (i = 0; i < markers.length; i++) { 
					var pos = new google.maps.LatLng(markers[i][1], markers[i][2]);
					bounds.extend(pos);
					marker = new google.maps.Marker({
						position: pos,
						map: map
					});
					google.maps.event.addListener(marker, 'click', (function(marker, i) {
						return function() {
							infowindow.setContent(markers[i][0]);
							infowindow.open(map, marker);
						}
					})(marker, i));
				}
				map.fitBounds(bounds);
			}

			function marcarExperiencia(objeto) {
				$(".activo").removeClass("activo");
				$(objeto).toggleClass("activo");
			}
		</script><script src="js/login-modal.js" type="text/javascript"></script>   
	</body>
</html>