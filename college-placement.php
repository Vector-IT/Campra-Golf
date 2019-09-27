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
      <?php

include_once 'header-links.php';

?>
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
                  <h1 style="text-align: center; margin: 40px 0px 0px;">College Placement</h1>
               </div>
            </div>
         </div>
         <div id="staff" class="container">
            <div class="row">
               <div class="col-md-10 col-md-offset-1" style="margin-bottom: 0px; ">
                  <div class="col-md-12">
                     <p>Servicio de búsqueda y gestión de cupos y becas deportivas en universidades de los Estados Unidos. <br>
                        Nuestro objetivo es encontrar la mejor opción deportiva, académica y financiera para cada cliente.Para ello, realizamos una promoción personalizada a fines de encontrar la mejor oportunidad para cada jugador. 
                     </p>
                     <p>Nuestro staff comprende perfectamente la responsabilidad que implica asistir a jóvenes y sus familias en este proceso de búsqueda universitaria. Por eso nuestro servicio garantiza acompañamiento y asistencia individual en cada etapa del proceso.</p>
					 <p >En Campra Golf nos apasiona la posibilidad de que jóvenes golfistas puedan continuar participando en el deporte que aman y al mismo tiempo puedan estudiar, crecer académicamente y recibir un título universitario.</p>
                     <p><strong>Para mayor información contactarse via e-mail a <a href="mailto:collegeplacement@campragolf.com?Subject=Consulta%20Web" target="_top">collegeplacement@campragolf.com</a> o telefónicamente a +54 9 351 6009597</strong></p>
                  </div>
                  <div class="col-md-12" style="margin-top:20px;"><img class="img-responsive" src="images/college-placement1.jpg" width="100%" height="auto"></div>
                  <div class="col-md-12" style="margin-top:20px;"><img class="img-responsive" src="images/college-placement2.jpg" width="100%" height="auto"></div>
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