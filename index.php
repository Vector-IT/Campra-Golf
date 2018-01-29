<?php
	session_start();
	include("admin/php/conexion.php");

	ini_set("log_errors", 1);
	ini_set("error_log", "php-error.log");
	
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

	//Cargo los datos de las experiencias
	$experiencias = cargarTabla("SELECT NumeExpe, NombExpe, Dominio, FotoBanner FROM experiencias WHERE NumeEsta = 1 ORDER BY NumeOrde");
	
	//Cargo las imagenes del banner principal
	$bannerimg = cargarTabla("SELECT NombImag, DescImag, Imagen, Link FROM bannerimagenes ORDER BY NumeOrde");
	
	//Cargo las promociones
	$promociones = cargarTabla("SELECT NumeTour, NombTour, Dominio, Articulo, AbrirLink FROM tours WHERE EnPromo = 1 AND NumeEsta = 1 ORDER BY NombTour");

	
	//Cargo los articulos del blog
	$rec_limit = 3;
	$rec_count = buscarDato("SELECT COUNT(*) FROM blog b LEFT JOIN blogexperiencias be ON b.NumeBlog = be.NumeBlog");

	//Verifico en que pagina estoy
	if (isset($_GET["page"])) {
		$page = $_GET["page"];
		$offset = $rec_limit * ($page - 1) ;
	}
	else {
		$page = 1;
		$offset = 0;
	}
	$left_rec = $rec_count - ($page * $rec_limit);
	
	$strSQL = "SELECT b.NumeBlog, b.Titulo, b.Dominio, b.Imagen, b.Copete, b.Fecha, b.Etiquetas";
	$strSQL.= " FROM blog b";
	$strSQL.= " LEFT JOIN blogexperiencias be ON b.NumeBlog = be.NumeBlog";
	$strSQL.= " GROUP BY b.NumeBlog, b.Titulo, b.Dominio, b.Imagen, b.Copete, b.Fecha, b.Etiquetas";
	$strSQL.= " ORDER BY Fecha DESC";
	$strSQL.= " LIMIT {$offset}, {$rec_limit}";
	
	
	$blog = cargarTabla($strSQL);
	
	//Ultimos articulos
	$ultimos = cargarTabla("SELECT Titulo, Dominio, Fecha FROM blog ORDER BY Fecha DESC LIMIT 3");
	
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<title><?php echo $nombAgen;?></title>
		<link rel="shortcut icon" href="images/favicon.ico" />
		<!-- Bootstrap -->
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/custom.css">
		
		<link rel="stylesheet" href="css/back-to-top.css">
		<link rel="stylesheet" href="css/allinone_carousel.css">

		<script src="http://code.jquery.com/jquery-latest.js"></script>

		<script src="js/modernizr.js"></script> <!-- Modernizr -->
		<script src="js/bootstrap.min.js"></script>
		<script src="js/back-to-top.js"></script>
		
		<link rel="stylesheet" href="owlcarousel/owl.carousel.css" /> 
		<link rel="stylesheet" href="owlcarousel/owl.theme.css" />
		<script src="owlcarousel/owl.carousel.js"></script>     
		
		<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
		<script src="js/jquery.ui.touch-punch.min.js" type="text/javascript"></script>
		<script src="js/allinone_carousel.js" type="text/javascript"></script>

		<link rel="stylesheet" type="text/css" href="css/kenburns.css">
		<script type="text/javascript" src="js/kenburns.js"></script>
		
		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
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
		<div class="wrapper top-style">
			<?php
				include_once 'encabezado.php';
			?>
			<div class="container" style=" max-height: 462px;">
				<div class="row">
					<div id="kenburns-slideshow">
						<span id="kenSlidePrev" class="kenBtn clickable"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span></span>
						<span id="kenSlideNext" class="kenBtn clickable"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></span>
						<div class="kenburnsTitle"></div>
						<div class="kenburnsDescription"></div>
					</div>
				</div>
			</div>
			<div class="container" style="margin-top: 20px;">
				<div class="col-md-12 text-center">
					<h1>Experiencias únicas</h1>
				</div>
				<div class="col-md-10 col-md-offset-1" style="margin-bottom:30px; margin-top: 20px;">
					<div style="border-bottom:1px solid #ddd;">
						<ul class="nav nav-tabs">
						<?php
							$experiencias->data_seek(0);
							while ($fila = $experiencias->fetch_array()) {
								echo '<li role="presentation"><a href="experiencia/'.$fila["Dominio"].'">'.$fila["NombExpe"].'</a></li>';
							}
						?>
							
						</ul>
					</div>
				</div>
			</div>
			<div id="bannerBg">
				<div id="containingDiv" class="center-block">
					<div id="allinone_carousel_powerful">
						<div class="myloader"></div>
						<!-- CONTENT -->
						<ul class="allinone_carousel_list">
						<?php
							$experiencias->data_seek(0);
							$salida = "";
							while ($fila = $experiencias->fetch_array()) {
								$salida.= $crlf.'<li data-link="experiencia/'.$fila["Dominio"].'" data-target="_self">';
								$salida.= $crlf.'<div class="elementoCarousel" >';
								$salida.= $crlf.'<img src="admin/'.$fila["FotoBanner"].'" style="width: 370px; height: auto;" /></img>';
								$salida.= $crlf.'<div class="elementoCarouselTitulo">'.$fila["NombExpe"].'</div>';
								$salida.= $crlf.'</div>';
								$salida.= $crlf.'</li>';
							}
							echo $salida;
						?>
						
							<!-- 
							<li data-link="rutas-mexico.php" data-target="_self">
								<div class="elementoCarousel" >
									<img src="images/rutasmexico-carousel.jpg" style="width: 370px; height: auto;" /></img>
									<div class="elementoCarouselTitulo">RUTAS M&Eacute;XICO</div>
								</div>
							</li>
							 -->
						</ul>
					</div>
				</div>
			</div>
			<div class="container" style="margin-top:60px;">
				<div class="container" style="margin-top:60px;">
					<div class="row" style="margin: 0px auto !important">
						<div class="col-md-10 col-md-offset-1">
							<div class="col-md-4 text-center" style="padding-left: 0px !important; padding-right: 0px !important;">
								<hr>
							</div>
							<div class="col-md-4 text-center">
								<a href="novedades.php" style="color:#000!important;">
									<h2 style="font-size: 30px; font-family: 'Helvetica-Bold' !important;">Novedades</h2>
								</a>
							</div>
							<div class="col-md-4 text-center" style="padding-left: 0px !important; padding-right: 0px !important;">
								<hr>
							</div>
						</div>
					</div>					
				</div>
			
			
				<div class="container">
					<div class="row">
						<div class="col-md-offset-1 col-md-10">
							<?php
								$salida = "";
								
								if (isset($blog)) {
									while ($fila = $blog->fetch_array()) {
										$cantCome = buscarDato("SELECT COUNT(*) FROM blogcomentarios WHERE NumeBlog = " .  $fila["NumeBlog"]);
										
										$salida.= $crlf . '<article class="col-sm-4" style="padding-bottom: 20px;">';
										$salida.= $crlf . '<h2><a href="notas/'.$fila["Dominio"].'">'.$fila["Titulo"].'</a></h2>';
										$salida.= $crlf . '<a href="notas/'.$fila["Dominio"].'"> <img src="admin/'.$fila["Imagen"].'" class="img-responsive"></a>';
										$salida.= $crlf . '</article>';
									}
			
									//Armo el paginador
									// $salida.= $crlf . '<ul class="pager">';
									// $salida.= $crlf . '';
									// if ($page > 1) {
									// 	$last = $page - 1;
									// 	$salida.= $crlf . '<li class="previous"><a href="novedades.php?page='.$last.'">&larr; Anterior</a></li>';
										
									// 	if ($left_rec > 0)
									// 		$salida.= $crlf . '<li class="next"><a href="novedades.php?page='.($page + 1).'">Siguiente &rarr;</a></li>';
									// }
									// else if ($left_rec > 0) {
									// 	$salida.= $crlf . '<li class="next"><a href="novedades.php?page='.($page + 1).'">Siguiente &rarr;</a></li>';
									// }				
									
									echo $salida;
								}
							?>
								<!-- 
								<ul class="pager">
									<li class="previous"><a href="#">&larr; Anterior</a></li>
									<li class="next"><a href="#">Siguiente &rarr;</a></li>
								</ul>
								-->
						</div>
					</div>
			    </div>
				<!-- <div class="container">
					<div class="col-md-10 col-md-offset-1" style="margin-top:15px;">
						<div id="owl-carousel" class="owl-theme">
						<?php
							$salida = "";
							
							while ($fila = $promociones->fetch_array()) {
								$salida.= $crlf.'<div style="text-align: center;">';
								if ($fila["AbrirLink"] == "1")
									$salida.= $crlf.'<a href="producto/'.$fila["Dominio"].'">';
								$salida.= $crlf.'<h3 class="text-left promos">'.$fila["NombTour"].'</h3>';
								$salida.= $crlf.'<div class="img-hoverPromos">';
								$salida.= $crlf.'<img src="admin/'.$fila["Articulo"].'">';
								$salida.= $crlf.'</div>';
								if ($fila["AbrirLink"] == "1")
									$salida.= $crlf.'</a>';
								$salida.= $crlf.'</div>';
							}
							
							echo $salida;
						?>
							<div style="text-align: center;">
								<a href="ruta/ruta-independencia">
									<h3 class="text-left promos">PROMOCIÓN 10 % DE DESCUENTO SALIDA 17 DE MAYO</h3>
									<div class="img-hoverPromos">
										<img src="images/promo-independencia/descuento-nextia.jpg">
									</div>
								</a>
							</div>
						 
						</div>
					</div>
				</div> -->
				</div>
			<?php
				include_once 'pie-de-pagina.php';
			?>

			</div>
			<a href="#0" class="cd-top"></a>
		</div>
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> -->
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script type='text/javascript'>
			var kenSlide;
			
			$(document).ready(function(){
				
				$("#kenburns-slideshow").css({
					"width": $("#kenburns-slideshow").parent().width() + "px",
					"height": ($("#kenburns-slideshow").parent().width() * 0.3743922204213938) + "px"
				});

				$( window ).resize(function() {
					$("#kenburns-slideshow").css({
						"width": $("#kenburns-slideshow").parent().width() + "px",
						"height": ($("#kenburns-slideshow").parent().width() * 0.3743922204213938) + "px"
					});
				});

				$('#owl-carousel').owlCarousel({
					loop:false,
					margin:"10",
					items:<?php if ($promociones->num_rows > 3) echo "3"; else echo ($promociones->num_rows);?>,
					nav:false
				});

				var titles = [
			    <?php 
					$salida = "";
					$bannerimg->data_seek(0);
					while ($fila = $bannerimg->fetch_array()) {
						$salida.= $crlf.'"'.$fila["NombImag"].'",';
					}
					echo $salida;
			    ?>
		    	];

		    	var descriptions = [
				<?php 
					$salida = "";
					$bannerimg->data_seek(0);
					while ($fila = $bannerimg->fetch_array()) {
						$salida.= $crlf.'"'.$fila["DescImag"].'",';
					}
					echo $salida;
				?>
				];
				
		    	var links = [
				<?php 
					$salida = "";
					$bannerimg->data_seek(0);
					while ($fila = $bannerimg->fetch_array()) {
						$salida.= $crlf.'"'.$fila["Link"].'",';
					}
					echo $salida;
				?>
				];
    	    				
			    kenSlide = $('#kenburns-slideshow').Kenburns({
				    nextBtn: "#kenSlideNext",
				    prevBtn: "#kenSlidePrev",
			    	images: [
				<?php
					$salida = "";
					$bannerimg->data_seek(0);
					while ($fila = $bannerimg->fetch_array()) {
						$salida.= $crlf.'"admin/'.$fila["Imagen"].'",';
					}
					echo $salida;
				?>
			    	],
			    	
			    	scale:0.99,
			    	duration:3000,
			    	fadeSpeed:1200,
			    	ease3d:'cubic-bezier(0.445, 0.050, 0.550, 0.950)',
			    	onSlideComplete: function(){
				    	var I = this.getSlideIndex();
				    	var titulo;

				    	if (links[I] != "") {
					    	$($(".kb-slide")[I]).css("cursor", "pointer");
					    	$($(".kb-slide")[I]).attr("onclick", "location.href = '" + links[I] + "'");
				    	}

					    titulo = titles[I];
				    	
				    	if (titulo != "")
			    			$('.kenburnsTitle').html(titulo).css("display", "block");
				    	else
				    		$('.kenburnsTitle').html(titulo).css("display", "none");

			    		if (descriptions[I] != "")
			    			$('.kenburnsDescription').html(descriptions[I]).css("display", "block");
			    		else
			    			$('.kenburnsDescription').html(descriptions[I]).css("display", "none");
				    	
			    	},
			    });

			});    

			jQuery(function() {
				jQuery('#allinone_carousel_powerful').allinone_carousel({
					skin: 'powerful',
					width: 960,
					height: 430,
					responsive:true,
					autoPlay: 0,
					resizeImages:true,
					//easing:'easeOutBounce',
					numberOfVisibleItems:4,
					elementsHorizontalSpacing:220,
					elementsVerticalSpacing:25,
					verticalAdjustment:10,
					animationTime:0.8,
					showPreviewThumbs:false,
					showCircleTimer:false,
					showBottomNav: false, 
					nextPrevMarginTop:22,
					playMovieMarginTop:0,
					bottomNavMarginBottom:10
				});		
			});
		</script>
		<script src="js/login-modal.js" type="text/javascript"></script>
<?php 
	if (isset($promociones))
		$promociones->free();

	if (isset($bannerimg))
		$bannerimg->free();
?>
	</body>
</html>