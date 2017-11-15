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
		<title>Aviso de Privacidad</title>
		<link rel="shortcut icon" href="images/favicon.ico" />
		<!-- Bootstrap -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<!-- 
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
		 -->
		
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
			@import url('fonts/FranklinGothic-Book.css');
			@import url('fonts/Helvetica-Bold.css');
			@import url('fonts/HelveticaNeueLTStd-Cn.css');
			@import url('fonts/HelveticaNeueLTStd-CnO.css');
			@import url('fonts/Humanist521BT-Light.css');
			@import url('fonts/MyriadWebPro-Bold.css');
			@import url('fonts/MyriadWebPro-Italic.css');
			@import url('fonts/MyriadWebPro-Regular.css');
			.panel-heading {
			padding: 10px; font-size: 16px !important;  text-align: left !important; font-family: 'Humanist521Bt-Light' !important;
			}
		</style>
	</head>
	<body>
	<?php include_once("analyticstracking.php") ?>
		<div class="wrapper top-style">
			<?php
				include_once 'encabezado.php';
			?>
			<div class="container" style="padding-top: 10px; border-top: 1px solid #ccc;">
				<div class="col-md-12 text-center">
					<h1>Aviso de Privacidad</h1>
					<br>
					<br>
				</div>
				<div class="row">
					<div class="col-md-10 col-md-offset-1"  style="margin-top: 0px;">
						<div class="row">
							<div class="col-md-12 panel-heading" style="text-align: center !important; border-bottom: 1px solid #fff !important; font-size: 18px !important;">
								<td width="100%" colspan="8" valign="top" class="panel-heading"  >
									<p align="center"><strong>AVISO DE PRIVACIDAD</strong></p>
								</td>
							</div>
						</div>
						<div class="row row-eq-height" style="border: 1px solid grey;">
							<div class="col-md-3 panel-heading">
								<p>Responsable de la protección de sus datos personales</p>
							</div>
							<div class="col-md-9">
								<p>Iconn Travel S.A. de C.V. (en lo sucesivo &ldquo;Iconn Travel&rdquo;), con domicilio &Aacute;lamo Plateado No.44 Int. 101 Col. Los &Aacute;lamos, Naucalpan Estado de M&eacute;xico C.P. 53230, es responsable del tratamiento de sus datos personales. De conformidad con lo dispuesto en la Ley Federal de Protecci&oacute;n de Datos Personales en Posesi&oacute;n de los Particulares, (en lo sucesivo la &ldquo;Ley&rdquo;), Iconn Travel cuenta con todas las medidas de seguridad f&iacute;sicas, t&eacute;cnicas y administrativas adecuadas para proteger sus datos personales. Bajo ninguna circunstancia comercializaremos sus datos personales sin su consentimiento previo y por escrito.</p>
							</div>
						</div>
						<div class="row row-eq-height" style="border: 1px solid grey;">
							<div class="col-md-3 panel-heading">
								<p>¿Para qué fines recabamos y utilizamos sus datos personales?</p>
							</div>
							<div class="col-md-9">
								<p>Sus datos personales serán utilizados para las siguientes finalidades:</p>
								<div class="row row-eq-height">
									<div class="col-md-3" style="border: 1px solid black;">
										<p>Si usted es <strong>CLIENTE </strong>para:</p>
										<ul>
											<li>Proveer y hacer cargos en su tarjeta de cr&eacute;dito correspondientes a los servicios de venta de boletos de avi&oacute;n, renta de autos y adquisici&oacute;n de toda clase de servicios tur&iacute;sticos y de viaje comercializados por Iconn Travel.</li>
											<li>Tr&aacute;mites de obtenci&oacute;n de visa y tr&aacute;mites relacionados con viajes.</li>
											<li>Informarle sobre cambios en la l&iacute;nea de productos, precios, disponibilidad y condiciones de pago de los mismos.</li>
											<li>Env&iacute;o de informaci&oacute;n por medios electr&oacute;nicos acerca de los productos comercializados por Iconn Travel.</li>
											<li>Evaluar la calidad del servicio que le brindamos.</li>
											<li>Prestar servicios de atenci&oacute;n al cliente.</li>
										</ul>
									</div>
									<div class="col-md-3" style="border: 1px solid black;">
										<p>Si usted es <strong>PROVEEDOR</strong> para:</p>
										<ul>
										    <li>Contratar y promover los servicios y/ productos relacionados con viajes y turismo que ofrece a nuestros clientes.</li> 
											<li>Realizar consultas acerca de los servicios y productos que comercializa.</li>
											<li>Obtener cotizaciones y efectuar pagos por sus servicios.</li>
											<li>Enviar y coordinar solicitudes de reserva.</li>
											<li>Verificaci&oacute;n de sus datos y obtenci&oacute;n de referencias comerciales.</li>
										</ul>
									</div>
									<div class="col-md-3" style="border: 1px solid black;">
										<p>Si usted es <strong>SOCIO DE NEGOCIOS</strong> para:</p>
										<ul>
										    <li>Env&iacute;o de informaci&oacute;n por medios electr&oacute;nicos acerca de los productos comercializados por Iconn Travel y por sus proveedores.</li>
										</ul>
									</div>
									<div class="col-md-3" style="border: 1px solid black;">
										<p>Si usted es <strong>EMPLEADO</strong> para:</p>
										<ul>
											<li>Verificar antecedentes laborales y acad&eacute;micos.</li>
											<li>Realizar actividades de selecci&oacute;n y contrataci&oacute;n de personal.</li>
											<li>Incluir en su expediente laboral.</li> 
										</ul>
									</div>
								</div>
							</div>
						</div>
						<div class="row row-eq-height" style="border: 1px solid grey;">
							<div class="col-md-3 panel-heading">
								<p>Finalidades no Necesarias</p>
							</div>
							<div class="col-md-9">
								<p>Asimismo, al proporcionar sus datos, usted autoriza a Iconn Travel su utilizaci&oacute;n con fines mercadot&eacute;cnicos, estad&iacute;sticos, promocionales, publicitarios, informativos o de prospecci&oacute;n comercial a trav&eacute;s de env&iacute;o de correos electr&oacute;nicos y, newsletters y llamadas telef&oacute;nicas directas, respecto a las actividades y productos comercializados Iconn Travel, sus filiales, subsidiarias y/o socios de negocios, sin que estas &uacute;ltimas sean finalidades necesarias ni finalidades que den origen a la relaci&oacute;n jur&iacute;dica entre usted y Iconn Travel</p>
							</div>
						</div>
						<div class="row row-eq-height" style="border: 1px solid grey;">
							<div class="col-md-3 panel-heading">
								<p>&iquest;C&oacute;mo puedo manifestar mi negativa para el uso de mis datos para finalidades no necesarias?</p>
							</div>
							<div class="col-md-9">
								<p>Si usted no est&aacute; de acuerdo con la utilizaci&oacute;n de sus datos para alguna de las denominadas &ldquo;finalidades no necesarias&rdquo;, o si usted desea limitar el uso o divulgaci&oacute;n de sus datos personales, puede manifestar su negativa o limitaci&oacute;n a dicho tratamiento o divulgaci&oacute;n dentro de un plazo de 5 d&iacute;as h&aacute;biles a partir de que ha tenido conocimiento del presente aviso de privacidad de la siguiente manera:</p>
								<ol type="a">
									<li>Mediante comunicaci&oacute;n escrita dirigida al Departamento de Datos Personales de Iconn Travel y podr&aacute; entregarse en el domicilio de Iconn Travel antes indicado o enviarse al siguiente correo datospersonales@iconnservices.com.mx</li>
									<li>Se&ntilde;alar su nombre completo y domicilio u otro medio para comunicarle la respuesta a su solicitud.</li>
									<li>Acompa&ntilde;ar la documentaci&oacute;n que acredite su identidad o en su caso la representaci&oacute;n legal del titular de los datos, los elementos y/o documentos que faciliten la localizaci&oacute;n de los datos personales.</li>
									<li>Especificar las finalidades que desea eliminar o las limitaciones que desea establecer.</li>
								</ol>
								<p>Lo anterior, en el entendido de que en todo caso quedar&aacute;n a salvo sus derechos de revocaci&oacute;n y oposici&oacute;n. Una vez recibida su solicitud, la responderemos ya sea mediante correo electr&oacute;nico o por escrito de acuerdo a la informaci&oacute;n que usted nos haya indicado en su solicitud, en un plazo de 20 d&iacute;as h&aacute;biles a partir de la recepci&oacute;n de su solicitud. Lo anterior, en el entendido de que podremos requerir informaci&oacute;n adicional en los t&eacute;rminos previstos en la Ley y su Reglamento, en cuyo caso el plazo de respuesta se contar&aacute; a partir de que nos proporcione la informaci&oacute;n/documentaci&oacute;n adicional.</p>
							</div>
						</div>
						<div class="row row-eq-height" style="border: 1px solid grey;">
							<div class="col-md-3 panel-heading">
								<p>¿Qué datos personales obtenemos y de dónde? </p>
							</div>
							<div class="col-md-9">
								<p>Para las finalidades se&ntilde;aladas en el presente aviso de privacidad, podemos recabar sus datos personales de distintas formas: cuando usted nos los proporciona directamente; cuando visita nuestro sitio de internet o utiliza nuestros servicios en l&iacute;nea, y cuando obtenemos informaci&oacute;n a trav&eacute;s de llamadas telef&oacute;nicas u otras fuentes p&uacute;blicas permitidas por la Ley.</p>
								<div class="row row-eq-height">
									<div class="col-md-3" style="border: 1px solid black;">
										<p>Si usted es CLIENTE recabaremos los siguientes datos:</p>
										<ul>
										    <li>Nombre</li>
										    <li>Correo electr&oacute;nico</li>
										    <li>N&uacute;mero telef&oacute;nico.</li>
										    <li>Domicilio.</li>
										    <li>Datos de tarjeta de cr&eacute;dito.</li>
										    <li>Ciudad de residencia.</li>
										</ul>
									</div>
									<div class="col-md-3" style="border: 1px solid black;">
										<p>Si usted es&nbsp; PROVEEDOR recabaremos los siguientes datos:</p>
										<ul>
										    <li>Nombre.</li>
										    <li>Correo electr&oacute;nico.</li>
										    <li>N&uacute;mero telef&oacute;nico</li>
										    <li>Domicilio.</li>
										</ul>
									</div>
									<div class="col-md-3" style="border: 1px solid black;">
										<p>Si usted es&nbsp; SOCIO DE NEGOCIOS recabaremos los siguientes datos:</p>
										<ul>
										    <li>Nombre</li>
										    <li>N&uacute;mero telef&oacute;nico</li>
										    <li>Correo electr&oacute;nico</li>
										    <li>Ciudad de residencia</li>
										</ul>
									</div>
									<div class="col-md-3" style="border: 1px solid black;">
										<p>Si usted es&nbsp; EMPLEADO recabaremos los siguientes datos:</p>
										<ul>
										    <li>Nombre</li>
										    <li>Edad</li>
										    <li>Correo electr&oacute;nico</li>
										    <li>N&uacute;mero telef&oacute;nico</li>
										    <li>Domicilio</li>
										    <li>Antecedentes laborales / escolares</li>
										    <li>Datos familiares</li>
										    <li>Datos de tarjetas de cr&eacute;dito</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
						<div class="row row-eq-height" style="border: 1px solid grey;">
							<div class="col-md-3 panel-heading">
								<p>Datos personales sensibles </p>
							</div>
							<div class="col-md-9">
								<p>No obtendremos ni trataremos datos considerados como &ldquo;Datos Sensibles&rdquo; en los t&eacute;rminos del art&iacute;culo 3ro. Fracci&oacute;n VI de la Ley.</p>
							</div>
						</div>
						<div class="row row-eq-height" style="border: 1px solid grey;">
							<div class="col-md-3 panel-heading">
								<p>¿Cómo acceder o rectificar sus datos personales o cancelar u oponerse a su uso? </p>
							</div>
							<div class="col-md-9">
								<p>Usted tiene derecho a conocer qu&eacute; datos personales tenemos de usted, para qu&eacute; los utilizamos y las condiciones del uso que les damos (<strong>Acceso</strong>). Asimismo, es su derecho solicitar la correcci&oacute;n de su informaci&oacute;n personal en caso de que est&eacute; desactualizada, sea inexacta o incompleta (<strong>Rectificaci&oacute;n</strong>); que la eliminemos de nuestros registros o bases de datos cuando considere que la misma no est&aacute; siendo utilizada conforme a los principios, deberes y obligaciones previstas en la normativa (<strong>Cancelaci&oacute;n</strong>); as&iacute; como oponerse al uso de sus datos personales para fines espec&iacute;ficos (<strong>Oposici&oacute;n</strong>). Estos derechos se conocen como derechos <strong>ARCO</strong>. Para el ejercicio de cualquiera de los derechos <strong>ARCO</strong>, usted deber&aacute; presentar la solicitud respectiva de la siguiente manera:</p>
								<ol type="a">
									<li>Mediante comunicaci&oacute;n escrita dirigida al Departamento de Datos Personales de Iconn Travel y podr&aacute; entregarse en el domicilio de Iconn Travel o enviarse al siguiente correo: datospersonales@iconnservices.com.mx.</li>
									<li>Se&ntilde;alar su nombre completo y domicilio u otro medio para comunicarle la respuesta a su solicitud.</li>
									<li>Acompa&ntilde;ar la documentaci&oacute;n que acredite su identidad o en su caso la representaci&oacute;n legal del titular de los datos, los elementos y/o documentos que faciliten la localizaci&oacute;n de los datos personales.</li>
									<li>Se&ntilde;alar la descripci&oacute;n clara y precisa de los datos personales respecto de los que se busca ejercer alguno de los derechos antes mencionados, los elementos y/o documentos que faciliten la localizaci&oacute;n de los datos personales, y los requisitos previstos para el ejercicio de los derechos ARCO en la Ley y su Reglamento.</li>
								</ol>
								<p>Una vez recibida su solicitud, la responderemos ya sea mediante correo electr&oacute;nico o por escrito de acuerdo a la informaci&oacute;n que usted nos haya indicado en su solicitud, en un plazo de 20 d&iacute;as h&aacute;biles a partir de la recepci&oacute;n de su solicitud. Lo anterior, en el entendido de que podremos requerir informaci&oacute;n adicional en los t&eacute;rminos previstos en la Ley y su Reglamento, en cuyo caso el plazo de respuesta se contar&aacute; a partir de que nos proporcione la informaci&oacute;n/documentaci&oacute;n adicional. Trat&aacute;ndose de solicitudes de acceso a sus datos personales, la informaci&oacute;n se le proporcionar&iacute;a en su caso, mediante copias simples o electr&oacute;nicas, dependiendo de la disponibilidad de las mismas en nuestros archivos.</p>
							</div>
						</div>
						<div class="row row-eq-height" style="border: 1px solid grey;">
							<div class="col-md-3 panel-heading">
								<p>¿Cómo puede revocar su consentimiento para el tratamiento de sus datos? </p>
							</div>
							<div class="col-md-9">
								<p>Usted puede <strong>Revocar</strong> el consentimiento que, en su caso, nos haya otorgado para el tratamiento de sus datos personales. Sin embargo, es importante que tenga en cuenta que no en todos los casos podremos atender su solicitud o concluir el uso de forma inmediata, ya que es posible que por alguna obligaci&oacute;n legal requiramos seguir tratando sus datos personales. Asimismo, usted deber&aacute; considerar que, para ciertos fines, la revocaci&oacute;n de su consentimiento implicar&aacute; que no le podamos seguir prestando el servicio que nos solicit&oacute;, o la conclusi&oacute;n de su relaci&oacute;n con nosotros. Para revocar su consentimiento deber&aacute; presentar su solicitud de la siguiente manera:</p>
								<ol type="a">
									<li>Mediante comunicaci&oacute;n escrita dirigida al Departamento de Datos Personales de Iconn Travel y podr&aacute; entregarse en el domicilio de Iconn Travel antes indicado o enviarse al siguiente correo datospersonales@iconnservices.com.mx.</li>
									<li>Se&ntilde;alar su nombre completo y domicilio u otro medio para comunicarle la respuesta a su solicitud.</li>
									<li>Acompa&ntilde;ar la documentaci&oacute;n que acredite su identidad o en su caso la representaci&oacute;n legal del titular de los datos, los elementos y/o documentos que faciliten la localizaci&oacute;n de los datos personales.</li>
									<li>Se&ntilde;alar la descripci&oacute;n clara y precisa de los datos personales respecto de los que se busca revocar su consentimiento.</li>
								</ol>
								<p>Una vez recibida su solicitud, la responderemos ya sea mediante correo electr&oacute;nico o por escrito de acuerdo a la informaci&oacute;n que usted nos haya indicado en su solicitud, en un plazo de 20 d&iacute;as h&aacute;biles a partir de la recepci&oacute;n de su solicitud. Lo anterior, en el entendido de que podremos requerir informaci&oacute;n adicional en los t&eacute;rminos previstos en la Ley y su Reglamento, en cuyo caso el plazo de respuesta se contar&aacute; a partir de que nos proporcione la informaci&oacute;n/documentaci&oacute;n adicional.</p>
							</div>
						</div>
						<div class="row row-eq-height" style="border: 1px solid grey;">
							<div class="col-md-3 panel-heading">
								<p>Sus datos pueden viajar a otro país o compartidos con otros </p>
							</div>
							<div class="col-md-9">
								<p>Le informamos que Iconn Travel &uacute;nicamente transfiere sus datos a autoridades fiscales o autoridades administrativas o judiciales. Lo anterior, a fin de cumplir con obligaciones de car&aacute;cter fiscal, a cargo de Iconn Travel o de resolver alguna controversia con el titular de los datos personales ante autoridades judiciales o administrativas. Lo anterior, sin perjuicio de las remisiones de datos que Iconn Travel hace a sus encargados que le apoyan en cuestiones de manejo y almacenamiento de bases de datos, ventas, b&uacute;squeda y manejo de nuevos clientes, solicitud de referencias, pagos, env&iacute;o de informaci&oacute;n, avisos, promociones y entrega de producto.</p>
								<p>Dado que cada una de las transferencias anteriores se encuentran previstas en la ley, de conformidad con lo dispuesto en el art&iacute;culo 37 de la Ley, no requerimos obtener su consentimiento para efectuar dichas transferencias.</p>
								<p>Iconn Travel no realiza transferencia alguna de datos personales para la cual sea necesario obtener su consentimiento.</p>
							</div>
						</div>
						<div class="row row-eq-height" style="border: 1px solid grey;">
							<div class="col-md-3 panel-heading">
								<p>Modificaciones al aviso de privacidad </p>
							</div>
							<div class="col-md-9">
								<p>Nos reservamos el derecho de efectuar en cualquier momento modificaciones o actualizaciones al presente aviso de privacidad, para la atenci&oacute;n de novedades legislativas, pol&iacute;ticas internas o nuevos requerimientos para la prestaci&oacute;n u ofrecimiento de nuestros servicios o productos. Estas modificaciones estar&aacute;n disponibles al p&uacute;blico a trav&eacute;s de nuestro sitio web www.iconntravel.com</p>
							</div>
						</div>
						<div class="row row-eq-height" style="border: 1px solid grey;">
							<div class="col-md-3 panel-heading">
								<p>Uso de cookies y web beacons </p>
							</div>
							<div class="col-md-9">
								<p><strong>Le informamos que en nuestro sitio web utilizamos cookies ni web beacons.</strong> Para efectos de este documento, se entender&aacute; por <strong>Cookies</strong> al archivo de datos que se almacena en el disco duro del equipo de c&oacute;mputo o del dispositivo de comunicaciones electr&oacute;nicas de un usuario al navegar en un sitio de internet espec&iacute;fico, el cual permite intercambiar informaci&oacute;n de estado entre dicho sitio y el navegador del usuario. La informaci&oacute;n de estado puede revelar medios de identificaci&oacute;n de sesi&oacute;n, autenticaci&oacute;n o preferencias del usuario, as&iacute; como cualquier dato almacenado por el navegador respecto al sitio de internet; y por <strong>web beacons</strong> Imagen visible u oculta insertada dentro de un sitio web o correo electr&oacute;nico, que se utiliza para monitorear el comportamiento del usuario en estos medios. A trav&eacute;s de &eacute;stos se puede obtener informaci&oacute;n como la direcci&oacute;n IP de origen, navegador utilizado, sistema operativo, momento en que se accedi&oacute; a la p&aacute;gina, y en el caso del correo electr&oacute;nico, la asociaci&oacute;n de los datos anteriores con el destinatario. Si usted desea deshabilitar dichas cookies o web bacons necesita acceder a la configuraci&oacute;n de su computadora o dispositivo y marcar la opci&oacute;n de deshabilitar dichas cookies o web bacons o contactarnos al correo electr&oacute;nico datospersonales@iconnservices.com.mx.</p>
							</div>
						</div>
						<div class="row row-eq-height" style="border: 1px solid grey;">
							<div class="col-md-3 panel-heading">
								<p>¿Cómo puede limitar el uso o divulgación de sus datos personales? </p>
							</div>
							<div class="col-md-9">
								<p>En adici&oacute;n a cualquier mecanismo anteriormente descrito para limitar el uso o divulgaci&oacute;n de sus datos personales, si usted desea dejar de recibir mensajes promocionales de nuestra parte puede solicitarlo a trav&eacute;s del Departamento de Datos Personales al correo datospersonales@iconnservices.com.mx o inscribirse en el Registro P&uacute;blico de Consumidores previsto en la Ley Federal de Protecci&oacute;n al Consumidor y comunicarnos dicha inscripci&oacute;n.</p>
							</div>
						</div>
						<div class="row row-eq-height" style="border: 1px solid grey;">
							<div class="col-md-3 panel-heading">
								<p>Ultima fecha de actualización </p>
							</div>
							<div class="col-md-9">
								<p>25 abril de 2016</p>
							</div>
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
							<li><a href="atencion-agencias.php">Atención Agencias</a></li>
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
		<script src="js/back-to-top.js"></script> 
		<script src="js/login-modal.js" type="text/javascript"></script>   
	</body>
</html>