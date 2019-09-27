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
		<title>Videos</title>
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

			.paging-button {
  visibility: hidden;
}

.button-container {
  clear: both;
}
</style>

		<!-- Facebook Pixel Code -->
		<script>
		!function(f,b,e,v,n,t,s)
		{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
		n.callMethod.apply(n,arguments):n.queue.push(arguments)};
		if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
		n.queue=[];t=b.createElement(e);t.async=!0;
		t.src=v;s=b.getElementsByTagName(e)[0];
		s.parentNode.insertBefore(t,s)}(window, document,'script',
		'https://connect.facebook.net/en_US/fbevents.js');
		fbq('init', '1345305482241846');
		fbq('track', 'PageView');
		</script>
		<noscript><img height="1" width="1" style="display:none"
		src="https://www.facebook.com/tr?id=1345305482241846&ev=PageView&noscript=1"
		/></noscript>
		<!-- End Facebook Pixel Code -->
	</head>
	<?php

include_once 'header-links.php';

?>
	<body>

		<div class="wrapper top-style">
			<?php
				include_once 'encabezado.php';
			?>
			<br>
			<br>
			<br>
			<h1 class="text-center" style="font-size: 40px; margin-bottom: 50px;">Videos</h1>
			<br>
			<div class="container">
				<div class="row">
					<div class="col-md-offset-1 col-md-10">

					<div id="login-container" class="pre-auth">
					This application requires access to your YouTube account.
					Please <a href="#" id="login-link">authorize</a> to continue.
					</div>
					<div id="video-container"></div>
					<div class="button-container">
					<button id="prev-button" class="paging-button" onclick="previousPage();">Previous Page</button>
					<button id="next-button" class="paging-button" onclick="nextPage();">Next Page</button>
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

	<!-- YouTube-->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script type="text/javascript" src="js/auth.js"></script>
    <script type="text/javascript" src="js/my_uploads.js"></script>
    <script src="https://apis.google.com/js/client.js?onload=googleApiClientReady"></script>

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
