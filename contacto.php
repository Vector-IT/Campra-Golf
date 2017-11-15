<?php
	session_start();
	include("admin/php/conexion.php");
	
	//Cargo los datos de la agencia
	if (isset($_GET["agencia"]))
		$numeAgen = $_GET["agencia"];
	else
		$numeAgen = "1";
	
	$agencia = cargarTabla("SELECT NombAgen, Imagen, Dominio, Direccion, Email, Telefono, Facebook, Twitter, Instagram FROM agencias WHERE NumeAgen = {$numeAgen}");
	
	$fila = $agencia->fetch_array();
	
	$nombAgen = $fila["NombAgen"];
	$dominio = $fila["Dominio"];
	
	$imagAgen = "admin/";
	if (isset($_SESSION['is_logged_in']))
		$imagAgen.= buscarDato("SELECT Imagen FROM agencias WHERE NumeAgen = " . $_SESSION["NumeAgen"]);
	
	if ($imagAgen == "admin/")
		$imagAgen.= $fila["Imagen"];
	
	$direAgen = $fila["Direccion"];
	$mailAgen = $fila["Email"];
	$teleAgen = $fila["Telefono"];
	$faceAgen = $fila["Facebook"];
	$twitAgen = $fila["Twitter"];
	$instAgen = $fila["Instagram"];
		
	$agencia->free();
	
	//Cargo las experiencias
	$experiencias = cargarTabla("SELECT NumeExpe, NombExpe, Dominio FROM experiencias WHERE NumeEsta = 1 ORDER BY NumeOrde");
	
	//Cargo las agencias
	$agencias = cargarTabla("SELECT NumeAgen, NombAgen, Imagen, Direccion, Email, Telefono, Posicion FROM agencias WHERE NumeAgen <> {$numeAgen} AND COALESCE(Ocultar, 0) <> 1 ORDER BY NombAgen");
	
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<title>Contacto</title>
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
			<div class="container">
				<div class="row">
					<div id="map-canvas"></div>
				</div>
			</div>
			<div class="container" style="margin-top: 60px;">
				<div class="row">
					<div class="col-md-1"></div>
					<div class="col-md-7" >
						<div class="row">
							<form id="form-contacto">
								<div class="form-group">
									<div class="col-md-12">
										<h1 style="  font-family:'HelveticaNeueLTStd-Cn'!important; margin-bottom: 30px;">Contáctanos</h1>
									</div>
									<div class="col-xs-6">
										<input type="text" id="nombre" class="form-control form-custom" placeholder="Nombre Completo*" required>
									</div>
									<div class="col-xs-6">
										<input type="text" id="empresa" class="form-control form-custom" placeholder="Empresa u Organización">
									</div>
								</div>
								<div class="row form-group">
									<div class="col-xs-6">
										<input type="email" id="email" class="form-control form-custom" placeholder="E-Mail*" required>
									</div>
									<div class="col-xs-6">
										<input type="text" id="telefono" class="form-control form-custom" placeholder="Teléfono" required>
									</div>
								</div>
								<div class="row form-group">
									<div class="col-xs-12">
										<textarea id="mensaje" class="form-control form-custom" placeholder="Comentarios*" required></textarea>
									</div>
								</div>
								<div class="row form-group" style="display: none;">
									<div class="col-xs-12">
										<label><input type="checkbox" required> He le&iacute;do el <a href="aviso-de-privacidad.php" target="blank" style="color: #C99F37;">Aviso de Privacidad</a></label>
									</div>
								</div>
								<div class="row form-group">
									<div class="col-xs-10">
										<div class="div-msg-form">
											<span class="icon-form glyphicon" aria-hidden="true"></span>&nbsp;<span class="text-msg-form"></span>
										</div>
									</div>
									<div class="col-xs-2">
										<input type="hidden" id="destinatario" value="<?php echo $mailAgen;?>" />
										<button type="submit" class="btn btn-small pull-right">Enviar</button>
									</div>
								</div>
							</form>
						</div>
					</div>
					<div id="contact-info" class="col-md-3" style="padding-top: 45px;">
						<address>
							<span class="glyphicon glyphicon-map-marker" aria-hidden="true" style="font-size: 20px; color: #11A66D;"></span>
							<strong> <?php echo $nombAgen;?></strong><br>
							<span id="map-input"><?php echo $direAgen;?></span><br>
						</address>
						<address>
							<span class="glyphicon glyphicon-phone" aria-hidden="true" style="font-size: 20px; color: #11A66D;"></span>
							<strong> Llámanos</strong><br>
							<?php echo $teleAgen;?>
						</address>
						<address>
							<span class="glyphicon glyphicon-envelope" aria-hidden="true" style="font-size: 20px; color: #11A66D;"></span>
							<strong> Envíanos tu consulta</strong><br>
							<a href="mailto:<?php echo $mailAgen;?>"><?php echo $mailAgen;?></a>
						</address>
					</div>
					<div class="col-md-1"></div>
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
		<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBrSBJfCcJfufsmQ4dPK75GkPaROy-XyrQ&extension=.js&output=embed"></script>
		<script>
			/* google maps */
			google.maps.visualRefresh = true;
			
			var map;
			function initialize() {
			var geocoder = new google.maps.Geocoder();
			var address = $('#map-input').text(); /* change the map-input to your address */
			var mapOptions = {
			zoom: 14,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			scrollwheel: false
			};
			map = new google.maps.Map(document.getElementById('map-canvas'),mapOptions);
			
			if (geocoder) {
			geocoder.geocode( { 'address': address}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
			  if (status != google.maps.GeocoderStatus.ZERO_RESULTS) {
			  map.setCenter(results[0].geometry.location);
			
			    var infowindow = new google.maps.InfoWindow(
			        {
			          content: address,
			          map: map,
			          position: results[0].geometry.location,
			        });
			
			    var marker = new google.maps.Marker({
			        position: results[0].geometry.location,
			        map: map, 
			        title:address
			    }); 
			
			  } else {
			  	alert("No results found");
			  }
			}
			});
			}
			}
			google.maps.event.addDomListener(window, 'load', initialize);
			
			/* end google maps */
			
		</script>
		<script src="js/back-to-top.js"></script> 
		<script src="js/login-modal.js" type="text/javascript"></script>   
	</body>
</html>