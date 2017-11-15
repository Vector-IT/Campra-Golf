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
		<title>Formas de Pago</title>
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
						<img src="images/promos.jpg" class="img-responsive" alt="" width="1234" height="434" longdesc="">
					</div>
				</div>
			</div>
			<div class="container" style="border-top: 1px solid #ddd;">
				<h1 class="text-center">FORMAS DE PAGO</h1>
			</div>
			<div class="container">
				<div class="col-md-10 col-md-offset-1" style="margin-top:40px; margin-bottom: 10px;">
					<p class="intro-full">
						<h3>Depósitos y Transferencias</h3>
						
						<span style="text-align: justify;">
							Se requiere de un dep&oacute;sito no reembolsable del 10% del total del costo del 
							paquete en el momento de la reserva y debi&eacute;ndose liquidar el total 45 
							d&iacute;as antes de la salida del pasajero. Si dicha reserva es realizada dentro de 
							los 45 d&iacute;as anteriores a la fecha de salida del pasajero tendr&aacute; que ser 
							liquidada el total de la reserva en el momento de la confirmaci&oacute;n.	
						</span>
					</p>
					<div class="col-md-8 col-md-offset-2" style="border: 2px solid black;">
						<table class="table table-striped table-condensed">
							<tr>
								<th width="40%">TIPO DE MONEDA</th>
								<th>MXN</th>
							</tr>
							<tr>
								<td width="40%">RAZ&Oacute;N SOCIAL</td>
								<td>ICONN TRAVEL S.A. DE C.V.</td>
							</tr>
							<tr>
								<td width="40%">RFC</td>
								<td>ITR051219JU3</td>
							</tr>
							<tr>
								<td width="40%">DIRECCI&Oacute;N</td>
								<td>ALAMO PLATEADO No.44 INT.101 COL. LOS &Aacute;LAMOS C.P. 53230 NAUCALPAN ESTADO DE M&Eacute;XICO.</td>
							</tr>
							<tr>
								<td width="40%">BANCO</td>
								<td>BBVA BANCOMER</td>
							</tr>
							<tr>
								<td width="40%">SUCURSAL 0387</td>
								<td>EMPRESAS SATELITE</td>
							</tr>
							<tr>
								<td width="40%">DIRECCI&Oacute;N</td>
								<td>CIRCUITO MEDICOS I COL. CD. SATELITE</td>
							</tr>
							<tr>
								<td width="40%">PLAZA</td>
								<td>NAUCALPAN</td>
							</tr>
							<tr>
								<td width="40%">CUENTA</td>
								<td>N&Uacute;M. 01 59 44 58 97</td>
							</tr>
							<tr>
								<td width="40%">NO. CUENTA CLAVE</td>
								<td>N&Uacute;M. 012 18000159445897 8</td>
							</tr>
						</table>						
					</div>
					<div class="col-md-8 col-md-offset-2" style="border: 2px solid black; margin-top: 20px;">
						<table class="table table-striped table-condensed">
							<tr>
								<th width="40%">TIPO DE MONEDA</th>
								<th>USD</th>
							</tr>
							<tr>
								<td width="40%">RAZ&Oacute;N SOCIAL</td>
								<td>ICONN TRAVEL S.A. DE C.V.</td>
							</tr>
							<tr>
								<td width="40%">RFC</td>
								<td>ITR051219JU3</td>
							</tr>
							<tr>
								<td width="40%">DIRECCI&Oacute;N</td>
								<td>ALAMO PLATEADO No.44 INT.101 COL. LOS &Aacute;LAMOS C.P. 53230 NAUCALPAN ESTADO DE M&Eacute;XICO.</td>
							</tr>
							<tr>
								<td width="40%">BANCO</td>
								<td>BBVA BANCOMER</td>
							</tr>
							<tr>
								<td width="40%">SUCURSAL 0387</td>
								<td>EMPRESAS SATELITE</td>
							</tr>
							<tr>
								<td width="40%">DIRECCI&Oacute;N</td>
								<td>CIRCUITO MEDICOS I COL. CD. SATELITE</td>
							</tr>
							<tr>
								<td width="40%">PLAZA</td>
								<td>NAUCALPAN</td>
							</tr>
							<tr>
								<td width="40%">CUENTA</td>
								<td>N&Uacute;M. 01 54 59 84 74</td>
							</tr>
							<tr>
								<td width="40%">NO. CUENTA CLAVE</td>
								<td>N&Uacute;M. 012 180 00154598474 7</td>
							</tr>
						</table>						
					</div>
				</div>
				<div class="row">
					<div class="col-md-10 col-md-offset-1" style="margin-top:20px;">
						<p class="intro-full">
							<h3>Meses Sin Intereses</h3>
							
							<span style="text-align: justify;">
								En caso de querer contar con opciones de pago a meses diferidos para sus 
								pasajeros pueden ofrecerlos contemplando los siguientes cargos bancarios:
							</span>
							<div class="col-md-8 col-md-offset-2" style="border: 2px solid black; margin-top: 20px;">
								<table class="table table-striped table-condensed">
									<tr>
										<th width="40%">BANCO</th>
										<th>PLAZOS</th>
									</tr>
									<tr>
										<td width="40%">BBVA Bancomer</td>
										<td>Desde 7 hasta 18 meses</td>
									</tr>
								</table>
							</div>
						</p>
					</div>
				</div>
				<div class="row">
					<div class="col-md-10 col-md-offset-1" style="margin-top:20px;">
						<p class="intro-full">
							<h3>Cambios</h3>
							
							<span style="text-align: justify;">
								Cualquier cambio una vez confirmada la reserva, tendr&aacute; un cargo de 
								$25 USD, m&aacute;s la adecuaci&oacute;n por tipo de cambio y/o cambios de 
								tarifa.
							</span>
						</p>
					</div>
				</div>
				<div class="row">
					<div class="col-md-10 col-md-offset-1" style="margin-top:20px;">
						<p class="intro-full">
							<h3>Reembolsos</h3>
							
							<p style="text-align: justify;">
								No se realizan reembolsos sobre los servicios que no se hayan usado, 
								quedando solo a reserva por casos que sean totalmente verificables y que sean 
								mandados v&iacute;a correo electr&oacute;nico y posteriormente entregados en 
								nuestras oficinas por escrito en un plazo no mayor a 30 d&iacute;as 
								calendarios despu&eacute;s de que haya terminado el servicio, posterior a 
								&eacute;ste plazo no se recibir&aacute; ninguna reclamaci&oacute;n. 
								Los servicios tales como excursiones y comidas ser&aacute;n totalmente no 
								reembolsables.
							</p>
							<p style="text-align: justify;">
								ICONN TRAVEL S.A de C.V es intermediario entre el cliente final y los 
								proveedores del servicio a prestar, por lo que NO es responsable de los 
								servicios prestados. En los casos de fuerza mayor, tales como; atrasos, 
								huelgas, cuarentenas y/o desastres naturales, en lo que respecta a los 
								pasajeros y/o sus pertenencias aplicar&aacute;n las pol&iacute;ticas aceptadas y contenidas 
								en la cotizaci&oacute;n. Podr&aacute; la empresa ofrecer opciones de cambio de itinerario, 
								las mismas que tendrán un costo administrativo de $25USD más lo que cobre el 
								prestador de servicios.
							</p>
						</p>
					</div>
				</div>
				<div class="row">
					<div class="col-md-10 col-md-offset-1" style="margin-top:20px;">
						<p class="intro-full">
							<h3>Cancelaciones</h3>
							
							<span style="text-align: justify;">
								Las cancelaciones primero se realizar&aacute;n v&iacute;a internet y 
								posteriormente no en un lapso mayor a 46 d&iacute;as tendr&aacute;n que ser 
								entregadas en las oficinas de ICONN TRAVEL S.A de C.V ubicadas en &Aacute;lamo 
								Plateado 44 Int. 101, Colonia Los &Aacute;lamos y estar&aacute;n sujetas a 
								un cargo que equivale al dep&oacute;sito realizado.
							</span>
							<ul>
								<li>Si la cancelaci&oacute;n es realizada entre el d&iacute;a 45 y 30 ser&aacute; penalizado con el 25% del total del precio del paquete</li>
								<li>Si la cancelaci&oacute;n es realizada entre el d&iacute;a 29 y 15 ser&aacute; penalizado con el 75% del total del precio del paquete</li>
								<li>Si la cancelaci&oacute;n se realiza con 14 d&iacute;as o menos ser&aacute; penalizado con la totalidad del monto del paquete.</li>
							</ul>
							<p style="text-align: justify;">
								Estas penalizaciones ser&aacute;n aplicadas en lo que corresponde a 
								pol&iacute;ticas propias de ICONN TRAVEL S.A de C.V. En caso de las 
								pol&iacute;ticas de cada proveedor del servicio, siendo &eacute;ste: hotel, 
								traslado, avi&oacute;n, comidas, boletos de entrada a parques y/o shows, se 
								tendr&aacute; que hacer el ajuste conforme a las mismas que ser&aacute;n 
								informadas desde el momento de la cotizaci&oacute;n, quedando ICONN TRAVEL 
								S.A de C.V libre de responsabilidad.
							</p>
						</p>
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
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="js/bootstrap.min.js"></script>
		<script src="js/back-to-top.js"></script> <script src="js/login-modal.js" type="text/javascript"></script>   
	</body>
</html>