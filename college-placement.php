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
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<title>College Placement</title>
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
	<?php include_once("analyticstracking.php") ?>
		<div class="wrapper top-style">
			<?php
				include_once 'encabezado.php';
			?>
			<div class="container" style="">
				<div class="row">
					<div class="fotorama" data-width="100%" data-height="55%" data-fit="cover" data-transition="crossfade"  data-autoplay="true" data-arrows="true"
     data-click="true"  data-loop="true" data-autoplay="true">
					<img src="images/college-placement3.jpg">
					</div>
				</div>
			</div>
			<div class="container">
				<div id="quienes-somos" class="row">
					<div class="col-md-10 col-md-offset-1">
						<h1 style="text-align: center; margin: 40px 0px;">College Placement</h1>
						<p style="text-align: center;">En <span style="color: #000;  font-family: 'HelveticaNeueLTStd-Cn' !important;">Campra Golf</span> somos apasionados de buscar y crear oportunidades para jóvenes golfistas. Creemos que la posibilidad de estudiar y competir en cualquiera de las ligas interuniversitarias de golf de Estados Unidos (NCAA DI, NCAA DII, NAIA, o NJCAA) como miembro y representante de un equipo universitario es una oportunidad maravillosa que debe ser aprovechada. Por eso ofrecemos el servicio de College Placement mediante el cual prestamos asistencia a jóvenes golfistas y sus familias en el competitivo y complejo proceso de búsqueda y gestión para el ingreso a una universidad y su respectivo equipo de golf. <br/>

Nuestro objetivo es encontrar la mejor opción deportiva, académica y financiera en relación a las necesidades y perfil de cada jugador. Para ello, realizamos una promoción, búsqueda y gestión personalizada a fines de encontrar instituciones que ofrezcan oportunidades para cada jugador. De tal manera, intentamos que el estudiante y su familia tengan acceso a opciones que puedan analizar para luego poder seleccionar la institución y oferta mas adecuada a su situación específica.</p>
					</div>
				</div>
			</div>
			<div id="staff" class="container">
				<div class="row">
					<div class="col-md-10 col-md-offset-1" style="margin-top:60px; margin-bottom: 0px; ">
						<div class="col-md-6" style="margin-top:20px;"><img class="img-responsive" src="images/college-placement1.jpg" width="100%" height="auto"></div>
						<div class="col-md-6">
							<p>Nuestro staff en <span style="color: #000;  font-family: 'HelveticaNeueLTStd-Cn' !important;">Campra Golf</span> comprende perfectamente el compromiso que implica asistir a jóvenes y sus familias en este proceso universitario. Por eso nuestro servicio garantiza acompañamiento y asistencia individual en todo momento. En primer término, efectuamos la recepción del curriculum del jugador, tanto académico como deportivo, y lo adaptamos a nuestro modelo que presenta la información del jugador de manera clara y concreta.  Luego procedemos a realizar una búsqueda y selección preliminar de universidades y coaches a contactar que cuadren con el perfil del jugador. A modo siguiente, concretamos la presentación y ofrecimiento del jugador a coaches universitarios. Esto podrá realizarse por distintos medios como e mails, llamados telefónicos, entrevistas interactivas, etc. y llevará a la etapa de gestión de beca deportiva, total o parcial.</p>
						</div>
					</div>
				</div>
								<div class="row">
					<div class="col-md-10 col-md-offset-1" style="margin-top:30px; margin-bottom: 0px; ">
						<div class="col-md-6">
							<p  style="margin-top:30px;">Cabe destacar que el jugador debe ser aceptado por el Centro de Elegibilidad de la liga universitaria en la que desee participar y también aceptado por la Oficina de Admisiones de la universidad correspondiente (admisión de carácter académico) para poder garantizar la incorporación del estudiante-jugador a la universidad pertinente. Es por esto que también prestamos asistencia en el proceso de inscripción a los centros de elegibilidad académica.</p>
						</div>
						<div class="col-md-6"><img class="img-responsive" src="images/college-placement2.jpg" width="100%" height="auto"></div>						
					</div>
				</div>
								<div class="row">
					<div class="col-md-10 col-md-offset-1" style="margin-top:60px; margin-bottom: 0px; ">
							<p style="text-align: center;">En <span style="color: #000;  font-family: 'HelveticaNeueLTStd-Cn' !important;">Campra Golf</span> estamos comprometidos a brindar un servicio personalizado y efectivo. Nos apasiona la posibilidad de que jóvenes golfistas puedan continuar participando en el deporte que aman y al mismo tiempo puedan estudiar, crecer académicamente y recibir un titulo universitario. Por esto, trabajamos y gestionamos con pasión a fin de convertir la maravillosa posibilidad de college golf en una oportunidad concreta para nuestros clientes.</p>
					</div>
				</div>
				<div class="row">
					<div class="col-md-10 col-md-offset-1" style="margin-top:40px; margin-bottom: 10px;">
						<p style="padding: 15px;color: #000;font-size: 23px;line-height: 36px;text-align: center;padding: 0px 5%;">Para mayor información contactarse via e-mail a <a href="mailto:collegeplacement@campragolf.com?Subject=Consulta%20Web" target="_top">collegeplacement@campragolf.com</a> o telefónicamente a +54 9351 664 0190</p>
					</div>
				</div>				
			</div>
			<?php
				include_once 'pie-de-pagina.php';
			?>
		</div>
		</div><a href="#0" class="cd-top"></a>
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="js/bootstrap.min.js"></script>
		<!-- fotorama-->
		<script src="fotorama/fotorama.js"></script> 
		<script src="js/back-to-top.js"></script> <script src="js/login-modal.js" type="text/javascript"></script>   
	</body>
</html>