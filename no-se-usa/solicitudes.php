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
	

	$fechDesd = "";
	$fechHast = "";
	$numeUsua = "-1";
	
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$fechDesd = $_POST["fechDesd"];
		$fechHast = $_POST["fechHast"];
		$numeUsua = $_POST["numeUsua"];
	}
	
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<title>Mis Solicitudes</title>
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
			$(document).ready(function() {
				$('#fechDesd, #fechHast').datetimepicker({
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
						<img src="images/promos.jpg" class="img-responsive" alt="" width="1234" height="434" longdesc="">
					</div>
				</div>
			</div>
			<div class="container" style="border-top: 1px solid #ddd;">
				<h1 class="text-center">Solicitudes</h1>
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
						<h3>Historial de solicitudes</h3>
						<form class="form-horizontal" method="post">
							<div class="row">
								<div class="col-md-6">
									<div class="col-md-2">
										<label style="font-size: 14pt; padding-top: 5px;">Per&iacute;odo:</label>
									</div>
									<div class="col-md-4">
										<input type="text" class="form-control" id="fechDesd" name="fechDesd" required />
									</div>
									<div class="col-md-4">
										<input type="text" class="form-control" id="fechHast" name="fechHast" required />
									</div>
								</div>
								<div class="col-md-6">
									<div class="col-md-2">
										<label style="font-size: 14pt; padding-top: 5px;">Usuario:</label>
									</div>
									<div class="col-md-10">
										<select class="form-control" id="numeUsua" name="numeUsua">
											<?php echo cargarCombo("SELECT NumeUsua, NombComp FROM usuarios WHERE NumeAgen = {$_SESSION["NumeAgen"]} ORDER BY NombComp", "NumeUsua", "NombComp", "", true, "Todos los usuarios");?>
										</select>
									</div>
								</div>
							</div>
							<div class="row" style="margin-top: 20px;">
								<div class="col-md-12 text-right">
									<button type="submit" class="btn btn-default">Buscar</button>
								</div>
							</div>
						</form>
					</div>
					<div class="row" style="margin-top: 20px">
						<?php
							$cantSoli = "0";
							
							$strSQL = "SELECT c.FechCoti, c.Nombre, c.Pasajero, e.NombExpe, t.NombTour, c.NumeEsta";
							$strSQL.= " FROM cotizaciones c";
							$strSQL.= " LEFT JOIN experiencias e ON c.NumeExpe = e.NumeExpe";
							$strSQL.= " LEFT JOIN tours t ON c.NumeTour = t.NumeTour";
							$strSQL.= " WHERE c.NumeAgen = " . $_SESSION["NumeAgen"];
							if (($fechDesd != "") && ($fechHast != ""))
								$strSQL.= " AND c.FechCoti BETWEEN DATE_FORMAT('{$fechDesd}', '%Y-%m-%d') AND DATE_FORMAT('{$fechHast}', '%Y-%m-%d')";
							
							if ($numeUsua != "-1")
								$strSQL.= " AND c.NumeUsua = " . $numeUsua;
							
							$strSQL.= " ORDER BY c.FechCoti DESC";
							
							$tabla = cargarTabla($strSQL);
							
							if ($tabla->num_rows > 0) {
								$cantSoli = $tabla->num_rows;
							}
						?>
						<h4>N&uacute;mero de solicitudes: <?php echo $cantSoli;?></h4>
						
						
						<table class="table table-striped table-condensed">
							<tr>
								<th>Fecha</th>
								<th>Nombre</th>
								<th>Pasajero</th>
								<th>Experiencia</th>
								<th>Tour</th>
								<th>Status</th>
							</tr>
							<?php if ($cantSoli == "0") { ?>
							<tr><td colspan="6">Sin datos para mostrar</td></tr>
							<?php 
								} 
								else {
									while ($fila = $tabla->fetch_array()) {
										$salida = $crlf.'<tr>';
										$salida.= $crlf.'<td>'. $fila["FechCoti"] .'</td>';
										$salida.= $crlf.'<td>'. $fila["Nombre"] .'</td>';
										$salida.= $crlf.'<td>'. $fila["Pasajero"] .'</td>';
										$salida.= $crlf.'<td>'. $fila["NombExpe"] .'</td>';
										$salida.= $crlf.'<td>'. $fila["NombTour"] .'</td>';
										switch ($fila["NumeEsta"]) {
											case "1":
												$salida.= $crlf.'<td>Activa</td>';
												break;
											case "2":
												$salida.= $crlf.'<td>Cancelada</td>';
												break;
											case "3":
												$salida.= $crlf.'<td>A Solicitud</td>';
												break;
											case "4":
												$salida.= $crlf.'<td>Venta Concretada</td>';
												break;
										}
										$salida.= $crlf.'</tr>';
									}
									echo $salida;
								}
							?>
						</table>
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