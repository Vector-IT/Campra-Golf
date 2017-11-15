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
	$filtro = "";
	$experiencia = "-1";
	$tour = "-1";
	$codigo = "";
	$imagenes = "1";
		
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if (!empty($_POST["experiencia"])) {
			$experiencia = $_POST["experiencia"];
		}
		if (!empty($_POST["tour"])) {
			$tour = $_POST["tour"];
		}
		if (!empty($_POST["codigo"])) {
			$codigo = $_POST["codigo"];
		}
		if (!empty($_POST["imagenes"])) {
			$imagenes = $_POST["imagenes"];
		}
		else {
			$imagenes = "0";
		}
	}
		
	if ($experiencia != "-1") {
		$filtro.= " t.NumeExpe = " . $experiencia;
	}
		
	if ($tour != "-1") {
		if ($filtro != "")
			$filtro.= " AND ";
		
		$filtro.= " i.NumeTour = " . $tour;
	}
		
	if ($codigo != "") {
		if ($filtro != "")
			$filtro.= " AND ";
				
			$filtro.= " t.Codigo = '{$codigo}'";
	}
	
	$strSQL = "SELECT i.NumeItin, i.NombItin, t.NombTour";
	$strSQL.= " FROM itinerarios i";
	$strSQL.= " INNER JOIN tours t ON i.NumeTour = t.NumeTour";
	if ($filtro != "") {
		$strSQL.= " WHERE ". $filtro;
	}
	$strSQL.= " ORDER BY NumeItin";
	
	$itinerarios = cargarTabla($strSQL);
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<title>Itinerarios</title>
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

			function descargar(strID) {
				var NumeItin = strID;
				var Imagenes = $("#imagenes").val();

				$('#inset_form').html('<form action="itinerariosDetalles.php" name="frmDescarga" method="post" style="display:none;"><input type="text" name="NumeItin" value="' + NumeItin + '" /><input type="text" name="Imagenes" value="' + Imagenes + '" /><input type="text" name="Imprimir" value="1" /></form>');

				document.forms["frmDescarga"].submit();
			}

			function buscarItinerario(strID) {
				$("#txtHint" + strID).html('<i class="fa fa-refresh fa-spin"></i> Enviando mail...');
				var mail = '<?php echo $_SESSION['NombMail'];?>';

				var frmData = new FormData();
	            frmData.append("operacion", "3");
	            frmData.append("NumeItin", strID);
	            frmData.append("Imagenes", "1");
	            frmData.append("Imprimir", "0");
	            
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
						var mensajeHTML = xmlhttp.responseText;
						enviarCorreo(strID, mensajeHTML);
					}
				};

				xmlhttp.open("POST","itinerariosDetalles.php",true);
				xmlhttp.send(frmData);
			}

			function enviarCorreo(strID, mensajeHTML) {
				var mail = '<?php echo $_SESSION['NombMail'];?>';

				var frmData = new FormData();
	            frmData.append("operacion", "3");
	            frmData.append("NombMail", mail);
	            frmData.append("mensajeHTML", mensajeHTML);
	            
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

				xmlhttp.open("POST","admin/php/itinerarioProcesar.php",true);
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
						<img src="images/principal_itinerario.jpg" class="img-responsive" alt="" width="1234" height="434" longdesc="">
					</div>
				</div>
			</div>
			<div class="container" style="border-top: 1px solid #ddd;">
				<h1 class="text-center">&iexcl;Comparte con tu clientes el itinerario de cada una de nuestras experiencias!</h1>
			</div>
			<div class="container">
				<div class="col-md-10 col-md-offset-1" style="margin-top:40px; margin-bottom: 10px;">
					<div class="row">
						<form class="form-horizontal" method="post">
							<div class="row">
								<div class="col-md-2" style="text-align: right;">
									<label style="font-size: 14pt; padding-top: 5px;">Experiencia: </label>
								</div>
								<div class="col-md-3">
									<select class="form-control" style="margin-top: 0;" name="experiencia" id="experiencia" onchange="filtrarTours();"><?php echo cargarCombo("SELECT NumeExpe, NombExpe FROM experiencias ORDER BY NombExpe", "NumeExpe", "NombExpe", $experiencia, true, "Todas");?></select>
								</div>
								<div class="col-md-2" style="text-align: right;">
									<label style="font-size: 14pt; padding-top: 5px;">Tour: </label>
								</div>
								<div class="col-md-3">
									<select class="form-control" style="margin-top: 0;" name="tour" id="tour">
									<?php
										if ($experiencia == "-1")
											echo cargarCombo("SELECT NumeTour, NombTour FROM tours WHERE NumeEsta = 1 AND EnPromo = 0 ORDER BY NombTour", "NumeTour", "NombTour", $tour, true, "Todos");
										else 
											echo cargarCombo("SELECT NumeTour, NombTour FROM tours WHERE NumeEsta = 1 AND EnPromo = 0 AND NumeExpe = {$experiencia} ORDER BY NombTour", "NumeTour", "NombTour", $tour, true, "Todos");
									?>
									</select>
								</div>
							</div>
							<div class="row" style="margin-top: 20px;">
								<div class="col-md-2" style="text-align: right;">
									<label style="font-size: 14pt; padding-top: 5px;">Código: </label>
								</div>
								<div class="col-md-3">
									<input class="form-control" style="margin-top: 0;" name="codigo" value="<?php echo $codigo;?>" />
								</div>
								<div class="col-md-2" style="text-align: right;">
									<label style="font-size: 14pt; padding-top: 5px;">Incluir im&aacute;genes: </label>
								</div>
								<div class="col-md-3">
									<select class="form-control" style="margin-top: 0;" name="imagenes" id="imagenes">
										<option value="0" <?php if ($imagenes == "0") echo "selected";?>>NO</option>
										<option value="1" <?php if ($imagenes == "1") echo "selected";?>>SI</option>
									</select>
								</div>
								<div class="col-md-2">
									<button type="submit" class="btn btn-default">FILTRAR</button>
								</div>
							</div>
						</form>
						<div id="inset_form" style="display: none;"></div>
					</div>
					<div class="row" style="margin-top: 20px;">
					<?php
						while ($filaItin = $itinerarios->fetch_array()) {
					?>
							<div class="col-md-4 panel panel-default" style="min-height: 300px;">
								<div class="panel-heading" style="margin: 0 !important; min-height: 200px;">
									<h3><?php echo $filaItin["NombTour"] . "<br><br>" . $filaItin["NombItin"]; ?></h3>
								</div>
								
								<div id="txtHint<?php echo $filaItin["NumeItin"];?>"></div>
								<div class="col-md-12" style="padding: 2px 0;">
									<button class="btn btn-default" style="width: 100%; text-align: center;" onclick="descargar(<?php echo $filaItin["NumeItin"];?>);">Imprime o Descarga</button>
								</div>
								<div class="col-md-12" style="padding: 2px 0;">
									<button class="btn btn-default" style="width: 100%; text-align: center;" onclick="buscarItinerario(<?php echo $filaItin["NumeItin"];?>);">Enviar por correo</button>
								</div>
							</div>
					<?php 
						}
						
						if (isset($dias))
							$dias->free();
						
						if (isset($itinerarios))
							$itinerarios->free();
						
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