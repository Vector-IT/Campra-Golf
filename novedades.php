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
	
	//Cargo los articulos del blog
	
	//Verifico si hay filtros
	$numeExpe = "";
	if (isset($_GET["categoria"]))
		$numeExpe = $_GET["categoria"];
	
	$etiqueta = "";
	if (isset($_GET["etiqueta"]))
		$etiqueta = $_GET["etiqueta"];

	$filtros = "";
	if ($numeExpe != "")
		$filtros.= " WHERE be.NumeExpe = " . $numeExpe;
	
	if ($etiqueta != "") {
		if ($numeExpe != "")
			$filtros.= " AND";
		else
			$filtros.= " WHERE";

		$filtros.= " b.Etiquetas LIKE '%{$etiqueta}%'";
	}
	
	$rec_limit = 6;
	$rec_count = buscarDato("SELECT COUNT(*) FROM blog b LEFT JOIN blogexperiencias be ON b.NumeBlog = be.NumeBlog " . $filtros);

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
	$strSQL.= $filtros;
	$strSQL.= " GROUP BY b.NumeBlog, b.Titulo, b.Dominio, b.Imagen, b.Copete, b.Fecha, b.Etiquetas";
	$strSQL.= " ORDER BY Fecha DESC";
	$strSQL.= " LIMIT {$offset}, {$rec_limit}";
	
	
	$blog = cargarTabla($strSQL);
	
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
	
	//Cargo los comentarios
	$strSQL = "SELECT b.Dominio, bc.DescCome, u.NombComp";
	$strSQL.= " FROM blogcomentarios bc";
	$strSQL.= " INNER JOIN blog b ON bc.NumeBlog = b.NumeBlog";
	$strSQL.= " INNER JOIN usuarios u ON bc.NumeUsua = u.NumeUsua";
	$strSQL.= " ORDER BY bc.Fecha DESC";
	$strSQL.= " LIMIT 3";
		
	$comentarios = cargarTabla($strSQL);
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<title>Novedades</title>
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
		
		<!-- fotorama-->
		<link  href="fotorama/fotorama.css" rel="stylesheet"> 

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
						<div class="container" style="">
				<div class="row">
					<div class="fotorama" data-width="100%" data-height="55%" data-fit="cover" data-transition="crossfade"  data-autoplay="true" data-arrows="true"
     data-click="true"  data-loop="true" data-autoplay="true">
					<img src="images/novedades-1.jpg">
					<img src="images/novedades-1.jpg">
					</div>
				</div>
			</div>
			<br>
			<br>
			<br>
			<h1 class="text-center" style="font-size: 40px; margin-bottom: 50px;">Novedades</h1>
			<br>
			<div class="container">
				<div class="row">
					<div class="col-md-offset-1 col-md-7">
					<?php
						$salida = "";
						
						if (isset($blog)) {
							while ($fila = $blog->fetch_array()) {
								$cantCome = buscarDato("SELECT COUNT(*) FROM blogcomentarios WHERE NumeBlog = " .  $fila["NumeBlog"]);
								
								$salida.= $crlf . '<article style="border-bottom: 1px dotted #ccc; padding-bottom: 20px;">';
								$salida.= $crlf . '<h2><a href="nota/'.$fila["Dominio"].'">'.$fila["Titulo"].'</a></h2>';
								$salida.= $crlf . '<div class="row">';
								$salida.= $crlf . '<div class="col-sm-6 col-md-6" style="display: none;">';
								$salida.= $crlf . '<span class="glyphicon glyphicon-bookmark"></span> ' . $fila["Etiquetas"];
								$salida.= $crlf . '</div>';
								$salida.= $crlf . '<div class="col-sm-6 col-md-6" style="text-align: right; display: none;">';
								$salida.= $crlf . '<span class="glyphicon glyphicon-pencil"></span> <a href="nota/'.$fila["Dominio"].'">'.$cantCome.' Comentarios</a>';
								$salida.= $crlf . '&nbsp;&nbsp;<span class="glyphicon glyphicon-time"></span> ' . $fila["Fecha"];
								$salida.= $crlf . '</div>';
								$salida.= $crlf . '<hr>';
								$salida.= $crlf . '<a href="nota/'.$fila["Dominio"].'"> <img src="admin/'.$fila["Imagen"].'" class="img-responsive"></a>';
								$salida.= $crlf . '<br />';
								$salida.= $crlf . '<p>'.$fila["Copete"].'</p>';
								$salida.= $crlf . '<div class="experienciaAmpliar"><a href="nota/'.$fila["Dominio"].'" target="_self"><span class="glyphicon glyphicon glyphicon-plus-sign" aria-hidden="true" style="font-size: 20px; margin-right: 25px; float: right !important; margin-bottom: 35px;"></span></a></div>';
								$salida.= $crlf . '<br />';
								$salida.= $crlf . '</article>';
							}
	
							//Armo el paginador
							$salida.= $crlf . '<ul class="pager">';
							$salida.= $crlf . '';
							if ($page > 1) {
								$last = $page - 1;
								$salida.= $crlf . '<li class="previous"><a href="novedades.php?page='.$last.'">&larr; Anterior</a></li>';
								
								if ($left_rec > 0)
									$salida.= $crlf . '<li class="next"><a href="novedades.php?page='.($page + 1).'">Siguiente &rarr;</a></li>';
							}
							else if ($left_rec > 0) {
								$salida.= $crlf . '<li class="next"><a href="novedades.php?page='.($page + 1).'">Siguiente &rarr;</a></li>';
							}				
							
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
					<div class="col-md-3">
						<div id="newsletter-widget"  style="display: none;">
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
									// $salida.= $crlf . '<li class="list-group-item"><a href="nota/'.$fila["Dominio"].'">'.$fila["Titulo"].'<br />'.$fila["Fecha"].'</a></li>';
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
								
								while ($fila = $comentarios->fetch_array()) {
									$salida.= $crlf . '<li class="list-group-item"><a href="nota/'.$fila["Dominio"].'">'.substr($fila["DescCome"], 0, 30).'... - <em>'.$fila["NombComp"].'</em></a></li>';
								}
								
								echo $salida;
							?>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<?php
				include_once 'pie-de-pagina.php';
			?>
		</div>
		<a href="#0" class="cd-top"></a>
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="js/bootstrap.min.js"></script>
		<script src="js/bootstrap-portfilter.js"></script>

		<!-- fotorama-->
		<script src="fotorama/fotorama.js"></script> 
		<script src="js/back-to-top.js"></script> 
		<script src="js/login-modal.js" type="text/javascript"></script>

<?php 
	if (isset($experiencias))
		$experiencias->free();
	
	if (isset($blog))
		$blog->free();
	
	if (isset($comentarios))
		$comentarios->free();
	
	if (isset($ultimos))
		$ultimos->free();
?>
	</body>
</html>
