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
		<title>Materiales para promoción</title>
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
		</style>
		
		<script type="text/javascript">
			function enviarCorreo(strID) {
				$("#txtHint" + strID).html('<i class="fa fa-refresh fa-spin"></i> Enviando mail...');
				var mail = '<?php echo $_SESSION['NombMail'];?>';

				var frmData = new FormData();
	            frmData.append("operacion", "3");
	            frmData.append("NumeFlyer", strID);
	            frmData.append("NombMail", mail);
	            
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
						$("#txtHint" + strID).html(xmlhttp.responseText);
					}
				};

				xmlhttp.open("POST","admin/php/flyersProcesar.php",true);
				xmlhttp.send(frmData);
			}
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
						<img src="images/material-promocion.jpg" class="img-responsive" alt="" width="1234" height="434" longdesc="">
					</div>
				</div>
			</div>
			<div class="container" style="border-top: 1px solid #ddd;">
				<h1 class="text-center">¡Promueve las experiencias con material personalizado!</h1>
			</div>
			<div class="container">
				<div class="col-md-10 col-md-offset-1" style="margin-top:40px; margin-bottom: 10px;">
					
					<div class="row">
						<div class="col-md-12">
							<h3>Sólo sigue estos sencillos pasos:</h3>
							<ol TYPE="A" style="font-size: 14pt;">
								<li>Filtra por tipo de experiencia.</li>
								<li>Marca el material que vas a descargar.</li>
								<li>Elige el formato que se adecue a tus necesidades:
									<ul>
										<li>Enviar por correo</li>
										<li>Impresión flyer</li>
										<li>PDF</li>
									</ul>
								</li>
								<li>¡Listo! Tus materiales ya incluyen el logo de la agencia.</li>
							</ol>
							
							<?php
								$logo = buscarDato("SELECT CONCAT('admin/', COALESCE(ImagenFlyer, Imagen)) Imagen FROM agencias WHERE NumeAgen = " . $_SESSION["NumeAgen"]);
								
								$filtro = "";
								$tour = "-1";
								$codigo = "";
								
								if ($_SERVER['REQUEST_METHOD'] === 'POST') {
									if (!empty($_POST["tour"])) {
										$tour = $_POST["tour"];
									}
									if (!empty($_POST["codigo"])) {
										$codigo = $_POST["codigo"];
									}
								}
								
								if ($tour != "-1") {
									$filtro.= " NumeTour = " . $tour;
								}
								
								if ($codigo != "") {
									if ($filtro != "")
										$filtro.= " AND ";
								
									$filtro.= " NumeTour IN (SELECT NumeTour FROM tours WHERE Codigo = '{$codigo}')";
								}

								/* PAGINACION */
								$strSQL = "SELECT COUNT(*) FROM flyers";
								if ($filtro != "") {
									$strSQL.= " WHERE " . $filtro;
								}
								$rec_count = buscarDato($strSQL);
								$rec_limit = 6;

								if( isset($_GET{'page'} ) ) {
									$page = $_GET{'page'} + 1;
									$offset = $rec_limit * $page ;
								}
								else {
									$page = 0;
									$offset = 0;
								}

								$left_rec = $rec_count - (($page+1) * $rec_limit);



								/* FLYERS */
								$strSQL = "SELECT NumeFlyer, NombFlyer, Imagen FROM flyers";
								if ($filtro != "") {
									$strSQL.= " WHERE " . $filtro;
								}
								$strSQL.= " LIMIT {$rec_limit} OFFSET {$offset}";
								
								$tabla = cargarTabla($strSQL);
								
								if ($tabla) {
									while ($fila = $tabla->fetch_array()) {
										crearWaterMark('admin/'.$fila["Imagen"], $logo, $_SESSION["NumeAgen"] .' - '. $fila["NumeFlyer"] .'.png');
									}
								}
							?>
							
						</div>
					</div>
					
					<div class="row" style="margin-top: 20px;">
						<form class="form-horizontal" method="post">
							<div class="col-md-1" style="text-align: right;">
								<label style="font-size: 14pt; padding-top: 5px;">Tour: </label>
							</div>
							<div class="col-md-4">
								<select class="form-control" style="margin-top: 0;" name="tour"><?php echo cargarCombo("SELECT NumeTour, NombTour FROM tours WHERE NumeEsta = 1 AND EnPromo = 0 ORDER BY NombTour", "NumeTour", "NombTour", $tour, true, "Todos");?></select>
							</div>
							<div class="col-md-1" style="text-align: right;">
								<label style="font-size: 14pt; padding-top: 5px;">Código: </label>
							</div>
							<div class="col-md-4">
								<input class="form-control" style="margin-top: 0;" name="codigo" value="<?php echo $codigo;?>" />
							</div>
							<div class="col-md-2">
								<button type="submit" class="btn btn-default">FILTRAR</button>
							</div>
						</form>
					</div>
					
					<div class="row" style="margin-top: 20px;">
						<?php
							if( $page > 0 ) {
								$last = $page - 2;
								echo "<button class=\"btn btn-default\" onclick=\"location.href = '{$_SERVER["PHP_SELF"]}?page=$last';\"><i class=\"fa fa-caret-left\" aria-hidden=\"true\"></i> Anterior</button>";
							}
							if( $left_rec > 0 ) {
								echo "<button class=\"btn btn-default\" onclick=\"location.href = '{$_SERVER["PHP_SELF"]}?page=$page'\">Siguiente <i class=\"fa fa-caret-right\" aria-hidden=\"true\"></i></button>";
							} 
						?>
					</div>

					<div class="row" style="margin-top: 20px;">
						<?php
							$tabla->data_seek(0);
						
							while ($fila = $tabla->fetch_array()) {
						?>
							<div class="col-md-4" style="min-height:300px;">
								<img alt="<?php echo $fila["NombFlyer"];?>" src="admin/temp/<?php echo $_SESSION["NumeAgen"] .' - '. $fila["NumeFlyer"] .'.png';?>" style="width: 100%; height: 140px;" />
								
								<h4>
								<?php 
									if ($fila["NombFlyer"] != "") 
										echo $fila["NombFlyer"];
									else
										echo "Sin nombre";
								?>
								</h4>
								
								<div id="txtHint<?php echo $fila["NumeFlyer"];?>"></div>
								<div class="col-sm-6" style="padding: 0 2px;">
									<button class="btn btn-default" style="width: 100%; text-align: center;" onclick="enviarCorreo(<?php echo $fila["NumeFlyer"];?>);">Enviar por correo</button>
								</div>
								<div class="col-sm-6" style="padding: 0 2px;">
									<button class="btn btn-default" style="width: 100%; text-align: center;" onclick="location.href='admin/temp/<?php echo $_SESSION["NumeAgen"] .' - '. $fila["NumeFlyer"] .'.png';?>';">Imprimir</button>
								</div>
								<div class="col-sm-12" style="padding: 4px 2px;">
									<button class="btn btn-default" style="width: 100%; text-align: center;" onclick="location.href='flyerPDF.php?NumeFlyer=<?php echo $fila["NumeFlyer"];?>';">PDF</button>
								</div>
								
							</div>
						<?php 
							}
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
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="js/bootstrap.min.js"></script>
		<script src="js/back-to-top.js"></script> <script src="js/login-modal.js" type="text/javascript"></script>   
	</body>
</html>