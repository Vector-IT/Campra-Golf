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
	
	//Cargo los datos del tour actual
	if (isset($_GET["tour"])) 
		$dominioTour = $_GET["tour"];
	else
		$dominioTour = "1";
		
	$tour = cargarTabla("SELECT NumeTour, NombTour, Subtitulo, DescTour, Portada, Imagen, Duracion, Lugares, Vigencia, Precio, EnPromo FROM tours WHERE NumeEsta = 1 AND Dominio = '{$dominioTour}'");

	$fila = $tour->fetch_array();
	
	$numeTour = $fila["NumeTour"];
	$nombTour = $fila["NombTour"];
	$subtitulo = $fila["Subtitulo"];
	$descTour = $fila["DescTour"];
	$duracion = $fila["Duracion"];
	$lugares = $fila["Lugares"];
	$vigencia = $fila["Vigencia"];
	$precio = $fila["Precio"];
	$portTour = "admin/" . $fila["Portada"];
	$imagTour = "admin/" . $fila["Imagen"];
	$enPromo = $fila["EnPromo"];
	$tour->free();

	
	$itinerarios = cargarTabla("SELECT NumeItin, NombItin FROM itinerarios WHERE NumeTour = {$numeTour} ORDER BY NumeItin");
	$incluye = cargarTabla("SELECT DescIncl FROM toursincluye WHERE FlagIncl = 1 AND NumeTour = {$numeTour} ORDER BY NumeIncl");
	$noincluye = cargarTabla("SELECT DescIncl FROM toursincluye WHERE FlagIncl = 0 AND NumeTour = {$numeTour} ORDER BY NumeIncl");
	$documentacion = cargarTabla("SELECT DescDocu FROM toursdocumentacion WHERE NumeTour = {$numeTour} ORDER BY NumeDocu");
	$galeria = cargarTabla("SELECT Imagen, NombImag FROM toursgaleria WHERE NumeTour = {$numeTour} ORDER BY NumeImag");
	
	//Cargo los datos de las experiencias
	$experiencias = cargarTabla("SELECT NumeExpe, NombExpe, Dominio, FotoBanner FROM experiencias WHERE NumeEsta = 1 ORDER BY NumeOrde");
	
	//Cargo los comentarios
	$strSQL = "SELECT COUNT(*) CantCome, COALESCE(TRUNCATE(AVG(PuntCome), 1), 0) PuntCome FROM tourscomentarios WHERE NumeTour = ". $numeTour;
	$comentarios = cargarTabla($strSQL);

	$fila = $comentarios->fetch_array();
	$comentarios->free();
	
	if ($fila["CantCome"] > 0) {
		//Hay comentarios
		$cantCome = $fila["CantCome"];
		$puntCome = $fila["PuntCome"];
	}
	else {
		//No hay comentarios
		$cantCome = rand(1, 200);
		$puntCome = rand (80, 100) / 10;
	}
	$strSQL = "SELECT tc.PuntCome, tc.DescCome, tc.Fecha, u.NombComp, tc.Nombre, tc.Email FROM tourscomentarios tc LEFT JOIN usuarios u ON tc.NumeUsua = u.NumeUsua WHERE tc.NumeTour = {$numeTour} ORDER BY Fecha DESC";
	$comentarios = cargarTabla($strSQL);
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
		<title><?php echo $nombTour;?></title>
		<link rel="shortcut icon" href="images/favicon.ico" />
		<!-- Bootstrap -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/custom.css" rel="stylesheet" type="text/css">
		<link href="css/starRating.css" rel="stylesheet" type="text/css">
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
		<link href="css/allinone_carousel.css" rel="stylesheet" type="text/css">
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
					<div class="media">
						<img class="img-responsive" src="<?php echo $portTour;?>" alt="bienvenidos" width="1234" height="434" longdesc="">
					</div>
				</div>
			</div>
			<div class="container" style="margin-top: 20px;">
				<div class="row" id="titulo-experiecia">
					<div class="col-md-10 col-md-offset-1" id="intro" style="margin-bottom: 30px;">
						<h1><?php echo $subtitulo;?></h1>
					</div>
				</div>
				<div class="row">
					<div class="col-md-1"></div>
					<div class="col-md-7" style="padding-top:0px !important;">
						<p class="intro"><?php echo str_replace($crlf, "<br>", $descTour);?></p>
						<img class="img-responsive" src="<?php echo $imagTour;?>" width="666" height="auto" alt="">
					</div>
					<div class="col-md-3" >
						<div id="detalle-viaje">
							<div class="caja-titulo">
								<h4 style="border: 2px solid #eee;">Detalle<?php if ($enPromo != "1") echo "";?></h4>
							</div>
							<p><?php echo $duracion;?></p>
							<?php 
								if ($enPromo != "1")
									echo '<h5 style="text-transform: uppercase !important; margin-bottom: 0px;"></h5>';
							?>
							<p><?php echo $lugares;?></p>
							<?php if ($enPromo != "1") { ?>
							<button class="btn btn-small" onclick="$('html, body').animate({ scrollTop: $('#comentarios').offset().top}, 1000);">Ver Comentarios</button>
							
								<a href="#reservas" data-toggle="modal"><button class="btn-contactanos">CONTACTANOS!</button> </a>							
							
							<?php 
							}
							?>
						</div>
						<?php if (mysqli_num_rows($galeria) > 0) { ?>
							<a href="javascript:;" id="launcher">
								<div id="galeria-widget">
									<div class="caja-galeria" style="background: url(images/widgetGaleria.jpg) no-repeat center right; -webkit-background-size: cover;
										-moz-background-size: cover; -o-background-size: cover; background-size: cover;">
										<h6 class="titulo-widget">Galería de Fotos<br><?php echo $nombTour;?></h6>
									</div>
								</div>
							</a>
							<div style="visibility: hidden;">
							<?php
								$salida = "";
								while ($fila = $galeria->fetch_array()) {
									$salida.= $crlf.'<a class="fancybox" rel="gallery" href="admin/' . $fila["Imagen"] . '" caption="' . $fila["NombImag"] . '"></a>';
								}
								echo $salida;
								
								if (isset($galeria))
									$galeria->free();
							?>
							</div>
						<?php } ?>
					</div>
					<div class="col-md-1"></div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-10 col-md-offset-1" id="itinerario" style="margin-bottom: 60px;">
				<?php
					$salida = "";
					
					while ($filaItin = $itinerarios->fetch_array()) {
						$salida.= $crlf . '<div class="panel panel-default">';
						$salida.= $crlf . '<div class="panel-heading">';
						$salida.= $crlf . '<h3>' . $filaItin["NombItin"] . '</h3>';
						$salida.= $crlf . '</div>';
						$salida.= $crlf . '<div class="panel-body">';
						$salida.= $crlf . '<ul class="list-group">';
							
						$dias = cargarTabla("SELECT NombDia, DescDia FROM itinerariosdetalles WHERE NumeItin = {$filaItin["NumeItin"]} ORDER BY NumeDia");
						$I = 1;
						while ($filaDia = $dias->fetch_array()) {
							if (($I % 2) != 0)
								$salida.= $crlf . '<li  class="list-group-item">';
							else 
								$salida.= $crlf . '<li  class="list-group-item fondoGris">';
							$salida.= $crlf . '<p class="duracion">Día ' . $I . ' – ' . $filaDia["NombDia"] .'</p>';
							$salida.= $crlf . '<p>' . str_replace($crlf, "<br>", $filaDia["DescDia"]) . '</p>';
							$salida.= $crlf . '</li>';
							
							$I++;
						}
						$salida.= $crlf . '</ul>';
						$salida.= $crlf . '</div>';
						$salida.= $crlf . '</div>';
					}
					
					if (isset($dias))
						$dias->free();
					
					if (isset($itinerarios))
						$itinerarios->free();
					
					echo $salida;
				?>
				<?php
					if (isset($_SESSION['TipoUsua']))
						$TipoUsua = $_SESSION['TipoUsua'];
					else
						$TipoUsua = "0";
					
					if ((isset($_SESSION['is_logged_in'])) ||
						($TipoUsua == "1") ||
						($TipoUsua == "2")) {
			
						if ($precio != "") { ?>
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3>Precio</h3>
							</div>
							<div class="panel-body">
								<ul type="disc">
									<li>
										<?php echo str_replace($crlf, "<br>", $precio);?>
									</li>
								</ul>
							</div>
						</div>
				<?php 
						}
					}
				?>
				<?php if ($vigencia != "") { ?>
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3>Vigencia</h3>
						</div>
						<div class="panel-body">
							<ul type="disc">
								<li><?php echo $vigencia;?></li>
							</ul>
						</div>
					</div>
				<?php } ?>
				<?php if (mysqli_num_rows($incluye) > 0) { ?>
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3>Incluye</h3>
						</div>
						<div class="panel-body">
							<ul>
							<?php
								$salida = "";
								while ($fila = $incluye->fetch_array()) {
									$salida.= $crlf."<li>{$fila["DescIncl"]}</li>"; 
								}
								echo $salida;
								
								if (isset($incluye))
									$incluye->free();
							?>
							</ul>
						</div>
					</div>
				<?php } ?>
				<?php if (mysqli_num_rows($noincluye) > 0) { ?>
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3>No Incluye</h3>
						</div>
						<div class="panel-body">
							<ul>
							<?php
								$salida = "";
								while ($fila = $noincluye->fetch_array()) {
									$salida.= $crlf."<li>{$fila["DescIncl"]}</li>"; 
								}
								echo $salida;
								
								if (isset($noincluye))
									$noincluye->free();
							?>
							</ul>
						</div>
					</div>
				<?php } ?>
				<?php if (mysqli_num_rows($documentacion) > 0) { ?>
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3>Documentación Requerida</h3>
						</div>
						<div class="panel-body">
							<ul>
							<?php
								$salida = "";
								while ($fila = $documentacion->fetch_array()) {
									$salida.= $crlf."<li>{$fila["DescDocu"]}</li>";
								}
								echo $salida;
								
								if (isset($documentacion))
									$documentacion->free();
							?>
							</ul>
						</div>
					</div>
				<?php } ?>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 id="comentarios">Comentarios</h3>
					</div>
				
					<div class="well">
						<h4>D&eacute;janos tu comentario</h4>
						<form id="form-comen-tour" role="form" class="clearfix">
							<input type="hidden" id="numeTour" value="<?php echo $numeTour;?>" />
							
							<div class="col-md-12 form-group">
								<?php if (!isset($_SESSION['is_logged_in'])) { ?>
									<input type="text" class="form-control" id="nombre" placeholder="Nombre Completo*" required />
									<input type="email" class="form-control" id="mail" placeholder="Correo*" required />
								<?php } ?>
								
							</div>						
							<div class="col-md-12 form-group">
								<label class="sr-only" for="email">Comentario</label>
								<textarea class="form-control" id="comment" placeholder="Comentario" required></textarea>
							</div>
							<div class="col-md-4">
								<button type="submit" class="btn btn-small">Enviar Comentario</button>
							</div>
							<div class="col-md-8">
								<div class="div-msg-form">
									<span class="icon-form glyphicon" aria-hidden="true"></span>&nbsp;<span class="text-msg-form"></span>
								</div>
							</div>
						</form>
					</div>
					<ul id="comments" class="comments" style="border-top: 1px solid #ccc;  padding-top: 15px;list-style: none !important;">
					<?php
						$salida = "";
						$I = 1;
						if (isset($_GET["verTodos"]))
							$verTodos = $_GET["verTodos"];
						else
							$verTodos = "";
						
						while ($fila = $comentarios->fetch_array()) {
							if (($verTodos == "") && ($I > 2)) {
								$salida.= $crlf.'<button class="btn btn-small" onclick="location.href = location.href + \'&verTodos=1#comentarios\';">Ver Todos</button>';
								break;
							}
							$salida.= $crlf.'<li class="comment">';
							$salida.= $crlf.'<div class="clearfix">';
							
							if ($fila["NombComp"] != "")
								$salida.= $crlf.'<h4 class="pull-left">'.$fila["NombComp"].'</h4>';
							else
								$salida.= $crlf.'<h4 class="pull-left">'.$fila["Nombre"].'</h4>';
							
							$salida.= $crlf.'<div class="pull-left" style="margin: 10px; display: none;">';
							for ($J = 1; $J <= 10; $J++) {
								if ($J <= $fila["PuntCome"])
									$salida.= $crlf.'<img src="'.$raiz.'images/star-on.svg" style="width: 18px;" />';
								else
									$salida.= $crlf.'<img src="'.$raiz.'images/star-off.svg" style="width: 18px;" />';
							}
							$salida.= $crlf.'</div>';
							
							$salida.= $crlf.'<p class="pull-right">'.$fila["Fecha"].'</p>';
							$salida.= $crlf.'</div>';
							$salida.= $crlf.'<p>';
							$salida.= $crlf.'<em style="text-transform: uppercase;">'.$fila["DescCome"].'</em>';
							$salida.= $crlf.'</p>';
							$salida.= $crlf.'</li>';
							
							$I++;
						}
	
						echo $salida;
					?>
					</ul>
				</div>
					<div class="social-widget" style="margin-top: 60px !important;">
						<?php 
							$salida = "";
							if ($twitAgen != "") {
								$salida.= $crlf.'<a href="'.$twitAgen.'" class="navbar-link" target="_blank" >';
								$salida.= $crlf.'<img src="images/icon-twitter.png" onmouseover="this.src=\'images/icon-twitter-negro.png\'" onmouseout="this.src=\'images/icon-twitter.png\'" width="28" height="28" alt="Twitter">';
								$salida.= $crlf.'</a>';
							}
							
							if ($instAgen != "") {
								$salida.= $crlf.'<a href="'.$instAgen.'" class="navbar-link" target="_blank" >';
								$salida.= $crlf.'<img src="images/icon-instagram.png" onmouseover="this.src=\'images/icon-instagram-negro.png\'" onmouseout="this.src=\'images/icon-instagram.png\'" width="28" height="28" alt="Instagram">';
								$salida.= $crlf.'</a>';
							}
							
							if ($faceAgen != "") {
								$salida.= $crlf.'<a href="'.$faceAgen.'" class="navbar-link" target="_blank" >';
								$salida.= $crlf.'<img src="images/icon-facebook.png" onmouseover="this.src=\'images/icon-facebook-negro.png\'" onmouseout="this.src=\'images/icon-facebook.png\'" width="28" height="28" alt="Facbook">';
								$salida.= $crlf.'</a>';
							}
							
							echo $salida;
						?>					
					</div>
				</div>

				<div id="reservas" class="modal fade">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-body">
								<div class="form-group" style=" border: 1px solid #69717E; padding: 30px; margin-bottom: 0px !important;">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
									<h4 class="modal-title">Contactanos</h4>
									<p class="reservasModal">Ingrese sus datos y consulta, nos pondremos en contacto a la brevedad.</p>
									<form id="form-reservas">
										<div id="div-reservas-msg">
											<span class="icon-form glyphicon" aria-hidden="true"></span>&nbsp;<span id="text-reservas-msg"></span>
										</div>
										<div class="row">
											<input type="text" class="form-control form-custom" id="nombre" name="name" placeholder="Nombre Completo*" required>
											<input type="text" class="form-control form-custom" id="email" placeholder="Email*" required>
											<input type="text" class="form-control form-custom" id="telefono" placeholder="Teléfono*" required>
											<select class="form-control form-custom" id="provincia">
											<?php
												echo cargarCombo("SELECT NumeProv, NombProv FROM provincias ORDER BY NombProv", "NumeProv", "NombProv", "", true, "Seleccione un estado...");
											?>
											</select>
											<textarea class="form-control form-custom" id="mensaje" placeholder="Comentarios*"></textarea>
											<input type="hidden" id="cco" value="<?php echo $mailAgen;?>" />
											<br>
											<button class="btn btn-small center-block">Enviar</button>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php
				include_once 'pie-de-pagina.php';
			?>
		</div>
		<a href="#0" class="cd-top"></a>
		<!-- Add jQuery library -->
		<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<!-- Add mousewheel plugin (this is optional) -->
		<script type="text/javascript" src="fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
		<script type="text/javascript" src="fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="js/back-to-top.js"></script> 
		<script type="text/javascript">
			$(document).ready(function() {
				$(".fancybox").fancybox();
			});
			
			$(".fancybox")
			   .attr('rel', 'gallery')
			   .fancybox({
			       padding    : 0,
			       margin     : 5,
			       nextEffect : 'fade',
			       prevEffect : 'none',
			       autoCenter : false,
			       afterLoad  : function () {
			           $.extend(this, {
			               aspectRatio : false,
			               type    : 'html',
			               width   : '100%',
			               height  : '100%',
			           });
			       }
			   });
		</script>
		<script>
			$("#launcher").on("click", function(){
			$(".fancybox").eq(0).trigger("click");
			});
		</script>
		<script>
			$(document).ready(function() {
			 $(".fancybox").fancybox({
			  helpers : { 
			   title : { type : 'inside' }
			  }, // helpers
			  beforeLoad: function() {
			   this.title = $(this.element).attr('caption');
			  }
			 }); // fancybox
			}); // ready
		</script><script src="js/login-modal.js" type="text/javascript"></script>   
	</body>
</html>