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
	if (isset($_GET["blog"])) 
		$dominioBlog = $_GET["blog"];
	else
		$dominioBlog = "1";
		
	$blog = cargarTabla("SELECT NumeBlog, Titulo, Imagen, Descripcion, Fecha, Etiquetas FROM blog WHERE Dominio = '{$dominioBlog}'");

	$fila = $blog->fetch_array();
	
	$numeBlog = $fila["NumeBlog"];
	$tituBlog = $fila["Titulo"];
	$imagBlog = "admin/" . $fila["Imagen"];
	$descBlog = $fila["Descripcion"];
	$fechBlog = $fila["Fecha"];
	$etiqBlog = $fila["Etiquetas"];
	$blog->free();
	
	//Cargo los datos de las experiencias
	$experiencias = cargarTabla("SELECT NumeExpe, NombExpe, Dominio, FotoBanner FROM experiencias WHERE NumeEsta = 1 ORDER BY NumeOrde");
	
	//Cargo los comentarios
	$strSQL = "SELECT bc.DescCome, bc.Fecha, u.NombComp, bc.Nombre, bc.Email FROM blogcomentarios bc LEFT JOIN usuarios u ON bc.NumeUsua = u.NumeUsua WHERE bc.NumeBlog = {$numeBlog} ORDER BY Fecha DESC";
	$comentarios = cargarTabla($strSQL);

	$cantCome = $comentarios->num_rows;
	
	//Ultimos articulos
	$ultimos = cargarTabla("SELECT Titulo, Dominio, Fecha FROM blog ORDER BY Fecha DESC LIMIT 3");
	
	//Cargo las etiquetas
	$etiquetas = cargarTabla("SELECT Etiquetas FROM blog ORDER BY Fecha DESC");
	
	$tags = "";
	while ($fila = $etiquetas->fetch_array()) {
		if ($fila["Etiquetas"] != "")
			$tags.= ",".$fila["Etiquetas"];
	}
	
	if (isset($etiquetas))
		$etiquetas->free();
	
	$tags = substr($tags, 1);
	$tags = explode(",", $tags);

	//Cargo los ultimos comentarios
	$strSQL = "SELECT b.Dominio, bc.DescCome, u.NombComp, bc.Nombre";
	$strSQL.= " FROM blogcomentarios bc";
	$strSQL.= " INNER JOIN blog b ON bc.NumeBlog = b.NumeBlog";
	$strSQL.= " LEFT JOIN usuarios u ON bc.NumeUsua = u.NumeUsua";
	$strSQL.= " ORDER BY bc.Fecha DESC";
	$strSQL.= " LIMIT 3";

	$ultcomentarios = cargarTabla($strSQL);

?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<?php 
			if (($dominio != "/") && ($dominio != "")) {
				echo '<base href="'. $raiz . $dominio .'/" />';
			}
			else {
				echo '<base href="'. $raiz .'" />';
			}
		?>
		<title><?php echo $tituBlog;?></title>
		<link rel="shortcut icon" href="images/favicon.ico" />
		<!-- Bootstrap -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/custom.css" rel="stylesheet" type="text/css">
		<link href="admin/css/stylesheet.css" rel="stylesheet" type="text/css">
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

		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<!-- Add jQuery library -->
		<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
		<!-- Add mousewheel plugin (this is optional) -->
		<script type="text/javascript" src="fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
		<script type="text/javascript" src="fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="js/back-to-top.js"></script> 
		<script src="js/login-modal.js" type="text/javascript"></script>

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
				<div class="row" style="max-height: 180px !important;">
					<div class="media">
						<img class="img-responsive" src="images/blog-title-bg.jpg" alt="" width="1234" height="300" longdesc="">
					</div>
				</div>
			</div>
			<br>
			<br>
			<br>
			<div class="container">
				<div class="row">
					<div class="col-md-1"></div>
					<div class="col-md-7">
						<a href="novedades.php">
							<h2 class="text-left">Novedades</h2>
						</a>
						<article style="border-bottom: 1px dotted #ccc; padding-bottom: 20px;">
							<h1><?php echo $tituBlog;?></h1>
							<div class="row" style="display: none;">
								<div class="col-sm-6 col-md-6" style="padding: 0;">
									<span class="glyphicon glyphicon-bookmark"></span> <?php echo $etiqBlog;?>
								</div>
								<div class="col-sm-6 col-md-6" style="padding: 0; text-align: right;">
									<span class="glyphicon glyphicon-pencil"></span> <span onclick="$('html, body').animate({ scrollTop: $('#comments').offset().top}, 1000);" style="cursor: pointer;"><?php echo $cantCome;?> Comentarios</span>			          		
									&nbsp;&nbsp;<span class="glyphicon glyphicon-time"></span> <?php echo $fechBlog;?>			          		
								</div>
							</div>
							<hr>
							<img src="<?php echo $imagBlog;?>" class="img-responsive">
							<br />
							<?php echo $descBlog;?>
							<br />	
						</article>
						<ul class="pager">
							<li class="previous"><a href="novedades.php">&larr; Volver a News</a></li>
						</ul>
						<!-- Comment form -->
						<div class="well">
							<h4>Dejanos tu comentario</h4>
							<form id="form-comen-blog" role="form" class="clearfix">
								<div class="col-md-12 form-group">
									<?php if (!isset($_SESSION['is_logged_in'])) { ?>
										<input type="text" class="form-control" id="nombre" placeholder="Nombre Completo*" required />
										<input type="email" class="form-control" id="mail" placeholder="Correo*" required />
									<?php } ?>
									<textarea class="form-control" id="comment" placeholder="Comentario" required></textarea>
									<input type="hidden" id="numeBlog" value="<?php echo $numeBlog;?>" />
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
						<p>Comentarios</p>
						<ul id="comments" class="comments" style="border-top: 1px solid #ccc;  padding-top: 15px;list-style: none !important;">
						<?php
							$salida = "";
							
							while ($fila = $comentarios->fetch_array()) {
								$salida.= $crlf.'<li class="comment">';
								$salida.= $crlf.'<div class="clearfix">';
								if ($fila["NombComp"] != "")
									$salida.= $crlf.'<h4 class="pull-left">'.$fila["NombComp"].'</h4>';
								else 
									$salida.= $crlf.'<h4 class="pull-left">'.$fila["Nombre"].'</h4>';
								$salida.= $crlf.'<p class="pull-right">'.$fila["Fecha"].'</p>';
								$salida.= $crlf.'</div>';
								$salida.= $crlf.'<p>';
								$salida.= $crlf.'<em style="text-transform: uppercase;">'.$fila["DescCome"].'</em>';
								$salida.= $crlf.'</p>';
								$salida.= $crlf.'</li>';
							}

							echo $salida;
						?>
						</ul>
					</div>
					<div class="col-md-3">
						<div id="newsletter-widget" style="display: none;">
							<div class="suscripcion" style="background: url(images/newsletter.jpg) no-repeat center center; -webkit-background-size: cover;
								-moz-background-size: cover; -o-background-size: cover; background-size: cover; ">
								<h6 class="titulo-widget">Registrate para recibir nuestros newsletters</h6>
							</div>
							<form>
								<div class="form-group" style="margin-bottom: 20px!important;">
									<label for="emailField"> </label>
									<input type="email" class="form-control" id="emailField" placeholder="E-mail" required>
								</div>
								<button type="submit" class="btn btn-small">Registrarse</button>
							</form>
						</div>
						<!-- Latest Posts -->
						<div class="panel panel-default">
							<div class="panel-heading">
								<h4>Últimos Artículos</h4>
							</div>
							<ul class="list-group">
							<?php
								$salida = "";
								
								while ($fila = $ultimos->fetch_array()) {
									//$salida.= $crlf . '<li class="list-group-item"><a href="nota/'.$fila["Dominio"].'">'.$fila["Titulo"].'<br />'.$fila["Fecha"].'</a></li>';
									$salida.= $crlf . '<li class="list-group-item"><a href="nota/'.$fila["Dominio"].'">'.$fila["Titulo"].'</a></li>';
								}
								
								echo $salida;
							?>
							</ul>
						</div>
						<!-- Categories -->
						<div class="panel panel-default">
							<div class="panel-heading">
								<h4>Categorías</h4>
							</div>
							<ul class="list-group">
								<li class="list-group-item"><a href="novedades.php">Todas</a></li>
							<?php
								$salida = "";
								
								$experiencias->data_seek(0);
								
								while ($fila = $experiencias->fetch_array()) {
									$salida.= $crlf . '<li class="list-group-item"><a href="novedades.php?categoria='.$fila["NumeExpe"].'">'.$fila["NombExpe"].'</a></li>';
								}
								
								echo $salida;
							?>
							</ul>
						</div>
						<!-- Tags -->
						<div class="panel panel-default">
							<div class="panel-heading">
								<h4>Tags</h4>
							</div>
							<div class="panel-body">
								<ul class="list-inline">
								<?php
									$salida = "";
									for ($I = 0; $I < count($tags); $I++) {
										$salida.= $crlf . '<li><a href="novedades.php?etiqueta='.trim($tags[$I]).'">'.trim($tags[$I]).'</a></li>';
									}
									
									echo $salida;
								?>
								</ul>
							</div>
							<div class="col-md-1"></div>
						</div>
						<!-- Recent Comments -->
						<div class="panel panel-default">
							<div class="panel-heading">
								<h4>Comentarios Recientes</h4>
							</div>
							<ul class="list-group">
							<?php 
								$salida = "";
								
								while ($fila = $ultcomentarios->fetch_array()) {
									if ($fila["NombComp"] != "")
										$salida.= $crlf . '<li class="list-group-item"><a href="nota/'.$fila["Dominio"].'">'.substr($fila["DescCome"], 0, 30).'... - <em>'.$fila["NombComp"].'</em></a></li>';
									else 
										$salida.= $crlf . '<li class="list-group-item"><a href="nota/'.$fila["Dominio"].'">'.substr($fila["DescCome"], 0, 30).'... - <em>'.$fila["Nombre"].'</em></a></li>';
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
				</div>
			</div>
			<?php
				include_once 'pie-de-pagina.php';
			?>
		</div>
		<a href="#0" class="cd-top"></a>
<?php 
	if (isset($experiencias))
		$experiencias->free();
	
	if (isset($comentarios))
		$comentarios->free();
	
	if (isset($ultcomentarios))
		$ultcomentarios->free();
	
	if (isset($ultimos))
		$ultimos->free();
?>
	</body>
</html>