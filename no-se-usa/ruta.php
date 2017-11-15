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

	//Cargo los datos de la experiencia actual
	if (isset($_GET["ruta"]))
		$dominioRuta = $_GET["ruta"];
	else
		$dominioRuta = "1";
	
	$ruta = cargarTabla("SELECT NumeRuta, Nombre, Descripcion, Descripcion2, ImgPortada, ImgMapaGrande, ImgMapaChico, ImgReferencias FROM rutasmexico WHERE Dominio = '{$dominioRuta}'");
	$fila = $ruta->fetch_array();
	
	$numeRuta = $fila["NumeRuta"];
	$nombRuta = $fila["Nombre"];
	$descRuta = $fila["Descripcion"];
	$descRuta2 = $fila["Descripcion2"];
	$imgPortada = "admin/" . $fila["ImgPortada"];
	$imgMapaGrande = "admin/" . $fila["ImgMapaGrande"];
	$imgMapaChico = "admin/" . $fila["ImgMapaChico"];
	$imgReferencias = "admin/" . $fila["ImgReferencias"];
	
	$ruta->free();

	//Cargo las imagenes para la galeria
	$galeria = cargarTabla("SELECT Imagen FROM rutasmexicogaleria WHERE NumeRuta = {$numeRuta} ORDER BY NumeOrde");
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<?php 
			if (($dominio != "/") && ($dominio != ""))
				echo '<base href="'. $raiz . $dominio .'/" />';
			else
				echo '<base href="'. $raiz .'" />';
		?>
		
		<title><?php echo $nombRuta?></title>
		<link rel="shortcut icon" href="images/favicon.ico" />
		<!-- Bootstrap -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/custom.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="css/back-to-top.css">
		<script src="js/modernizr.js"></script> <!-- Modernizr -->
		<!-- Add fancyBox -->
		<link rel="stylesheet" href="fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
		<link rel="stylesheet" href="fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />
		<!-- Optionally add helpers - button, thumbnail and/or media -->
		<link rel="stylesheet" href="fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />		
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
						<img src="<?php echo $imgPortada;?>" class="img-responsive" alt="bienvenidos" width="1234" height="434" longdesc="">
					</div>
				</div>
			</div>
			
			<div class="container" style="border-top: 1px solid #ddd;">
				<div class="col-md-10 col-md-offset-1" id="intro">
					<h1><?php echo $nombRuta?></h1>
					<div class="sub-intro">
						<?php echo $descRuta?>
						<br><br>
						<?php echo $descRuta2?>
					</div>
				</div>
			</div>
			<div class="container">
				<div class="row sub-intro">
					<span id="itinerarioToggle" class="clickable cajaNegra">Descargar Itinerario</span>
				</div>
				<div class="row sub-intro textoBuscador oculto text-center marginTop20">
					<p>
						Recuerda que puedes filtrar tu búsqueda por fecha de salida o por ciudad de partida.
					</p>
					<p>
						Los circuitos tienen días de salida específicos, si buscas algún otro tipo de combinación o de servicio ¡contáctanos!
					</p> 
				</div>
				<div class="row rutaBuscador oculto">
					<div class="col-md-6">
						<h3 class="text-center">ARME SU ITINERARIO POR DIA</h3>
						<form method="post" id="ruta-dias">
							<input type="hidden" id="hdnNumeRuta" value="<?php echo $numeRuta?>" />
							<input type="hidden" id="hdnNombRuta" value="<?php echo $nombRuta?>" />
							<input type="hidden" id="hdnDiaDesde" value="1" />
							<input type="hidden" id="hdnDiaHasta" value="1" />
							<div class="col-md-5">
								<div class="text-center">DESDE</div>
								<input type="date" id="dtpDesde" class="full-width" value="" />
							</div>
							<div class="col-md-5">
								<div class="text-center">HASTA</div>
								<input type="date" id="dtpHasta" class="full-width" value="" />
							</div>
							<div class="col-md-2">
								<br>
								<div class="full-width text-center cajaBuscador clickable">
									<button type="submit" style="background-color: transparent; border: none;"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
								</div>
							</div>
						</form>
					</div>
					<div class="col-md-6">
						<h3 class="text-center">ARME SU ITINERARIO POR CIUDAD</h3>
						<form method="post" id="ruta-ciudad">
							<input type="hidden" id="hdnNumeRuta" value="<?php echo $numeRuta?>" />
							<input type="hidden" id="hdnNombRuta" value="<?php echo $nombRuta?>" />
							<div class="col-md-8">
								<div class="text-center">CIUDAD DE PARTIDA</div>
								<!-- <input type="text" id="txtCiudad" class="full-width" value="" /> -->
								<select class="full-width" id="txtCiudad">
									<?php echo cargarCombo("SELECT UPPER(NombCity) NombCity FROM ciudades WHERE NumeRuta = {$numeRuta} ORDER BY 1", "NombCity", "NombCity");?>
								</select>
							</div>
							<div class="col-md-2">
								<div class="text-center">DIAS</div>
								<input type="number" id="txtDias" class="full-width" value="1" min="1"/>
							</div>
							<div class="col-md-2">
								<br>
								<div class="full-width text-center cajaBuscador clickable">
									<button type="submit" style="background-color: transparent; border: none;"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
								</div>
							</div>
						</form>
					</div>
				</div>
				
				<div class="row oculto" id="resultados"></div>
				
				<div class="row" style="margin-top: 20px;" id="detallesItin">
					<div class="col-md-8">
						<img class="img-responsive" src="<?php echo $imgMapaGrande?>" style="width: 100%;"/>
					</div>
					<div class="col-md-4 text-center">
						<img class="img-responsive" src="<?php echo $imgMapaChico?>" style="width: 100%;"/>
						<img src="<?php echo $imgReferencias?>" style="width: 195px; height: 205px;" />
						<br>
						<div class="cajaNaranja"><h4>Galeria de imagenes</h4></div>
						<div style="display: none;">
							<?php
								$salida = "";
								while ($fila = $galeria->fetch_array()) {
									$salida.= $crlf.'<a class="fancybox" rel="gallery" href="admin/' . $fila["Imagen"] . '"></a>';
								}
								echo $salida;
								
								if (isset($galeria))
									$galeria->free();
							?>
						</div>
					</div>
				</div>
			</div>
			<div class="container" style="margin-top: 40px;">
				<div class="row">
					<div class="col-md-10 col-md-offset-1">
						<div class="row">
							<div class="col-md-12 text-center" style="border-bottom: 1px solid #ddd;">
								<h3 style="font-family: 'HelveticaNeueLTStd-Cn' !important; color: #E2BB3D;">MAS ITINERARIOS MEXICO</h3>
							</div>
						</div>
						<div class="row">							
							<?php
							//Cargo las rutas
							$rutas = cargarTabla("SELECT NumeRuta, Nombre, Descripcion, ImgPrevia, Dominio FROM rutasmexico WHERE NumeRuta <> {$numeRuta} ORDER BY Nombre");
							
								
							$salida = "";
							while ($fila = $rutas->fetch_array()) {
								$salida.= $crlf.'<div class="col-md-4">';
								$salida.= $crlf.'<a href="ruta/'.$fila["Dominio"].'"><h2 class="tituloRuta">'. $fila["Nombre"] .'</h2></a>';
								
								$salida.= $crlf.'<div class="imgPreviaRuta">';
								$salida.= $crlf.'<a href="ruta/'.$fila["Dominio"].'">';
								$salida.= $crlf.'<img src="admin/'. $fila["ImgPrevia"] .'" class="img-responsive" />';
								$salida.= $crlf.'</a>';
								$salida.= $crlf.'</div>';
								
								$salida.= $crlf.'<div class="descripcionRuta">'.$fila["Descripcion"].'</div>';
								$salida.= $crlf.'<div><a href="ruta/'.$fila["Dominio"].'" target="_self"><span class="glyphicon glyphicon glyphicon-plus-sign" aria-hidden="true" style="font-size: 20px; float: right !important;"></span></a></div>';
								$salida.= $crlf.'</div>';
							}
							echo $salida;
							?>
						</div>
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
		<script type="text/javascript" src="fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
		<script type="text/javascript" src="fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
		<script type='text/javascript'>
			$(document).ready(function(){
				$(".fancybox").fancybox();

				$(".cajaNaranja").click(function () {
					$(".fancybox").eq(0).trigger("click");
				});
				
				$("#itinerarioToggle").click(function() {
					$(".textoBuscador").fadeToggle();
					$(".rutaBuscador").fadeToggle();

					$("#dtpDesde").focus();
				});
				
				var today = new Date();
				var dd = today.getDate();
				var mm = today.getMonth()+1; //January is 0!
				var yyyy = today.getFullYear();
				
				if(dd<10) {
					dd='0'+dd
				} 
				
				if(mm<10) {
					mm='0'+mm
				} 
				
				today = yyyy+'-'+mm+'-'+dd;
				$("#dtpDesde").val(today);
				$("#dtpHasta").val(today);
				
				$(".cajaBuscador").click(function() {
					$(this).parents("form").submit();
				});
			});

			function verPDF(strID) {
				location.href = "rutaPDF.php?Itin=" + strID;
			}
		</script>
	</body>
</html>
<?php 
	if (isset($rutas))
		$rutas->free();
?>