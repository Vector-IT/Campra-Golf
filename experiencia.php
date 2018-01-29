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
	
	//Cargo los datos de la experiencia actual
	if (isset($_GET["experiencia"])) 
		$dominioExpe = $_GET["experiencia"];
	else
		$dominioExpe = "1";
		
	$experiencia = cargarTabla("SELECT NumeExpe, NombExpe, DescExpe, FotoPortada FROM experiencias WHERE NumeEsta = 1 AND Dominio = '{$dominioExpe}'");

	$fila = $experiencia->fetch_array();
		
	$numeExpe = $fila["NumeExpe"];
	$nombExpe = $fila["NombExpe"];
	$descExpe = $fila["DescExpe"];
	$imagExpe = "admin/" . $fila["FotoPortada"];
	$experiencia->free();
	
	//Cargo los tours de la experiencia 
	$tours = cargarTabla("SELECT NumeTour, NombTour, Dominio, Lugares, Copete, Duracion, Articulo FROM tours WHERE NumeEsta = 1 AND EnPromo = 0 AND NumeExpe = " . $numeExpe);
	
	//Cargo los datos de las experiencias
	$experiencias = cargarTabla("SELECT NumeExpe, NombExpe, Dominio, FotoBanner FROM experiencias WHERE NumeEsta = 1 ORDER BY NumeOrde");
	
	//Cargo los datos del blog
	$strSQL = "SELECT NumeBlog, Titulo, Imagen, Dominio, Copete, DATE_FORMAT(Fecha, '%d/%m/%Y') Fecha ";
	$strSQL.= " FROM blog ";
	$strSQL.= " WHERE NumeBlog IN (SELECT NumeBlog FROM blogexperiencias WHERE NumeExpe = {$numeExpe})";
	$strSQL.= " ORDER BY Fecha DESC";
	$strSQL.= " LIMIT 3";
	
	$blog = cargarTabla($strSQL);
	
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
		<title><?php echo $nombExpe;?> - Experiencias, <?php echo $nombAgen;?></title>
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
					<div class="media">
						<img src="<?php echo $imagExpe;?>" class="img-responsive" alt="bienvenidos" width="1234" height="434" longdesc="">
					</div>
				</div>
			</div>
			<div class="container" style="margin-top: 20px;">
				<div class="row">
					<div class="col-md-10 col-md-offset-1" id="intro" style="margin-bottom: 30px;">
						<h1><?php echo $nombExpe;?></h1>
						<p class="sub-intro"><?php echo $descExpe;?></p>
					</div>
				</div>
				<div class="row">
					<div class="col-md-1"></div>
					<div class="col-md-7 bordeDerecha" style="padding-top:0px !important;">
<!-- TOURS -->
					<?php
						$strSalida = "";
						while ($fila = $tours->fetch_array()) {
							$strSalida.= $crlf.'<div id="experiencias">';
							$strSalida.= $crlf.'<p class="duracion">'.$fila["Duracion"].'</p>';
							$strSalida.= $crlf.'<a href="producto/'.$fila["Dominio"].'" target="_self">';
							$strSalida.= $crlf.'<h2>'.$fila["NombTour"].'</h2>';
							$strSalida.= $crlf.'</a>';
							$strSalida.= $crlf.'<div class="img-hover">';
							$strSalida.= $crlf.'<a href="producto/'.$fila["Dominio"].'" target="_self"><img class="img-responsive" src="admin/'.$fila["Articulo"].'" width="100%" height="auto" alt=""></a>';
							$strSalida.= $crlf.'</div>';
							$strSalida.= $crlf.'<h3 class="destinos">'.$fila["Lugares"].'</h3>';
							$strSalida.= $crlf.'<p class="descripcion">'.$fila["Copete"].'</p>';
							$strSalida.= $crlf.'<div class="experienciaAmpliar"><a href="producto/'.$fila["Dominio"].'" target="_self"><span class="glyphicon glyphicon glyphicon-plus-sign" aria-hidden="true" style="font-size: 20px; margin-right: 25px; float: right !important;"></span></a></div>';
							$strSalida.= $crlf.'</div>';
						}
						echo $strSalida;
					?>
					</div>
					<div class="col-md-3" id="articulos-relacionados">
						<div class="caja-titulo">
							<h4 class="titulo-articulos"><a href="novedades.php">Novedades</a></h4>
						</div>
						<?php
							$salida = "";
							
							while ($fila = $blog->fetch_array()) {
								$salida.= $crlf.'<div id="articulo">';
								$salida.= $crlf.'<a href="nota/'.$fila["Dominio"].'">';
								$salida.= $crlf.'<h5 class="titulo-post">'.$fila["Titulo"].'</h5>';
								$salida.= $crlf.'<img class="img-responsive" src="admin/'.$fila["Imagen"].'"  alt="" style="padding-bottom: 10px;">';
								$salida.= $crlf.'<p class="extracto-post">'.$fila["Copete"].'</p>';
								$salida.= $crlf.'<p class="fecha-post">Posteado '.$fila["Fecha"].'</p>';
								$salida.= $crlf.'</a>';
								$salida.= $crlf.'</div>';
							}
							echo $salida;
							
							if (isset($blog))
								$blog->free();
						?>

					</div>
					<div class="col-md-1"></div>
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
		<script src="js/back-to-top.js"></script> <script src="js/login-modal.js" type="text/javascript"></script>

<?php 
	$tours->free();
	$experiencias->free();
?>
	</body>
</html>