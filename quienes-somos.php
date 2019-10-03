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
      <title>Quienes Somos</title>
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
      			<?php

include_once 'header-links.php';

?>
   </head>
   <body>
      <div class="wrapper top-style">
         <?php
            include_once 'encabezado.php';
            ?>
         <div class="container" style="">
            <div class="row">
               <h1 class="sliderTitle" style="display: none;">Campra Golf</h1>
               <p class="sliderDesc" style="display: none;">Academia de golf de jerarquía mundial que crea un espacio de encuentro y aprendizaje para golﬁstas de todos los niveles. </p>
               <div class="fotorama" data-width="100%" data-height="55%" data-fit="cover" data-transition="crossfade"  data-autoplay="true" data-arrows="true"
                  data-click="true"  data-loop="true" data-autoplay="true">
                  <img src="images/image1.jpg">
               </div>
            </div>
         </div>
         <div class="container">
            <div id="quienes-somos" class="row">
               <div class="col-md-10 col-md-offset-1">
                  <h1 style="text-align: center;">Academia Campra Golf</h1>
                  <p style="text-align: center;">Orientado a golfistas amateurs, profesionales, sociales, empresariales y a quienes se quieran iniciar en el deporte, <span style="color: #000;  font-family: 'HelveticaNeueLTStd-Cn' !important;">Campra Golf</span> - Pepa Campra y su excelente staff de instructores - vuelcan todo su conocimiento y experiencia en un espacio de aprendizaje equipado con tecnología de última línea.<br/><br/>
                     Campra Golf cuenta con las mejores herramientas y softwares en el mercado - cámaras de video de alta definición, Boditrak, Trackman, V1, K-Vest & Sam Putt Lab - mediante las cuales se pueden analizar diversos aspectos del swing y el juego de manera precisa, así como también realizar fittings de palos.    <br/>  <br/> 
                     Campra Golf trae a Córdoba, Argentina y América Latina una academia de golf de jerarquía mundial que crea un espacio de encuentro y aprendizaje para golfistas de todos los niveles.                
                  </p>
                  <br/>
                  <iframe width="100%" height="500" src="https://www.youtube.com/embed/jYa6_MIfdkM?showinfo=0&rel=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
               </div>
            </div>
         </div>
         <div id="staff" class="container">
            <div class="row">
               <div class="col-md-10 col-md-offset-1" style="margin-top:60px; margin-bottom: 0px; ">
                  <h2  style="text-align: center;">Campra Golf Staff</h2>
                  <img class="img-responsive" src="images/Imagen5.jpg" width="100%" height="auto" style="margin-bottom: 60px;">
                  <div class="col-md-6">
                     <div class="fotorama" data-width="100%" data-height="55%" data-fit="contain" data-transition="crossfade"  data-autoplay="true" data-arrows="true" data-click="true"  data-loop="true" data-autoplay="true">
                        <img src="images/1.jpg">
                        <img src="images/0 (35).jpg">
                     </div>
                  </div>
                  <div class="col-md-6"  style="margin-top:40px; margin-bottom: 10px;">
                     <h3 style="margin-top: 90px;">Jose Luis “Pepa” Campra</h3>
                     <p>Director de Instrucción - Reconocido en el golf latinoamericano por su notable carrera como jugador aficionado y profesional, Pepa consagra su pasión por el juego y la instrucción en la realización de Campra Golf. Su reconocimiento como coach e instructor de primer nivel se atribuye a innumerables capacitaciones en el exterior con instructores de renombre como Butch Harmon, Sean Foley & George Gankas. Además, acumula conocimientos excepcionales como caddie en el PGA Tour de los mejores profesionales argentinos, entre ellos, Angel Cabrera y Emiliano Grillo.</p>
                  </div>
               </div>
            </div>
            <div class="row" style="display: none;">
               <div class="col-md-10 col-md-offset-1" style="margin-top:40px; margin-bottom: 10px;">
                  <p style="background: #11A66D;padding: 15px;color: #fff;font-size: 30px;line-height: 36px;text-align: center;margin-bottom: 35px;">Acompañan a Pepa y aportan su gran conocimiento técnico de la mano de un excelente manejo de las tecnologías a disposición.</p>
               </div>
            </div>
            <div class="row">
               <div class="col-md-10 col-md-offset-1" style="margin-top:0px;">
                  <div class="row"  style="margin-top:40px; margin-bottom: 10px;">
                     <div class="col-md-6">					
                        <img class="img-responsive aligncenter" src="images/0 (26).jpg" width="" height="" style="max-width: 75%;">
                     </div>
                     <div class="col-md-6"  style="margin-top:40px; margin-bottom: 10px;">
                        <h3>Martina Gavier</h3>
                        <p>Jugadora de gran trayectoria amateur en el golf argentino, sudamericano e interuniversitario norteamericano como parte del equipo de Kent State University. Compitió tres años como profesional en la LPGA Symetra Tour. Se dedica a la instrucción y coaching desde 2015. Actual directora del Programa de Formación Deportiva de Menores de la Federación de Golf de la Provincia de Córdoba.</p>
                     </div>
                  </div>
                  <div class="row"  style="margin-top:40px; margin-bottom: 10px;">
                     <div class="col-md-6">					
                        <img class="img-responsive aligncenter" src="images/Imagen2.jpg" width="" height="" style="max-width: 75%;">
                     </div>
                     <div class="col-md-6"  style="margin-top:40px; margin-bottom: 10px;">
                        <h3>Maxi Lacuara</h3>
                        <p>Instructor certificado de la PGA Argentina. Maxi está dedicado exclusivamente a la instrucción de golf desde el año 2010. Fue director de la Escuela de Menores del Córdoba Golf Club dos años y director del Programa de Desarrollo de Escuelas de Golf de la Federación de Golf de la Provincia de Córdoba.</p>
                     </div>
                  </div>
                  <div class="row"  style="margin-top:40px; margin-bottom: 10px;">
                     <div class="col-md-6">					
                        <img class="img-responsive aligncenter" src="images/Imagen30.jpg" width="" height="" style="max-width: 75%;">
                     </div>
                     <div class="col-md-6"  style="margin-top:40px; margin-bottom: 10px;">
                        <h3>Lucía Fernandez Valdes</h3>
                        <p>Directora de Campra KIDS. Lucía compitió exitosamente en el golf interuniversitario norteamericano para la universidad de Jacksonville State. Como instructora y coach adquirió experiencia como asistente del equipo femenino de golf de Florida International University, Jim McLean Golf Academy, Riviera Country Club y Crandon Golf Academy a cargo del programa de verano para menores.</p>
                     </div>
                  </div>
                  <div class="row"  style="margin-top:40px; margin-bottom: 10px;">
                     <div class="col-md-6">					
                        <img class="img-responsive aligncenter" src="images/Imagen3.jpg" width="" height="" style="max-width: 75%;">
                     </div>
                     <div class="col-md-6"  style="margin-top:40px; margin-bottom: 10px;">
                        <h3>Guido Travella</h3>
                        <p>Actualmente cursando la capacitación para Instructor Nacional de Golf de PGA. Representante del Área Metropolitana en competencias de menores. Sus últimos 3 años los pasó trabajando de caddie en una importante cancha de golf en las afueras de Nueva York.</p>
                     </div>
                  </div>
                  <div class="row"  style="margin-top:40px; margin-bottom: 10px;">
                     <div class="col-md-6">					
                        <img class="img-responsive aligncenter" src="images/Imagen4.jpg" width="" height="" style="max-width: 75%;">
                     </div>
                     <div class="col-md-6"  style="margin-top:40px; margin-bottom: 10px;">
                        <h3>Lucas Liprandi</h3>
                        <p>Gerente Comercial y Director del Tour Campra Golf. Lucas fue Gerente de la Federación de Golf de la Provincia de Córdoba durante diez años. Aporta experiencia en la organización y gestión de eventos y torneos de golf.</p>
                     </div>
                  </div>
               </div>
            </div>
		 </div>
         <div id="tecnologias" class="container">
            <div class="row">
               <div class="col-md-10 col-md-offset-1" style="margin-top:60px; margin-bottom: 0px; ">
                  <h2 style="text-align: center;">Tecnologías a Disposición en la Academia Campra Golf</h2>
                  <div class="row" style="margin-top:60px; margin-bottom: 60px; ">
                     <div class="col-md-4" style="padding: 3%;">
                        <h3>TrackMan</h3>
                        <p>Dispositivo de última generación que utiliza la tecnología radar doppler para proporcionar un abanico de datos sobre el palo en el momento del impacto y el vuelo de la bola. Lo utilizamos para brindar nuestro servicio de club fitting y a modo de complemento en clases individuales y grupales.</p>
                     </div>
                     <div class="col-md-8">
                        <div class="fotorama" data-width="100%" data-height="" data-fit="contain" data-transition="crossfade"  data-autoplay="true" data-arrows="true" data-click="true"  data-loop="true" data-autoplay="true">
                           <img src="images/0 (139).jpg">
                           <img src="images/0 (165).jpg">
                        </div>
                     </div>
                  </div>
                  <div class="row" style="margin-top:60px; margin-bottom: 60px; ">
                     <div class="col-md-8 ">
                        <div class="fotorama" data-width="100%" data-height="" data-fit="contain" data-transition="crossfade"  data-autoplay="true" data-arrows="true" data-click="true"  data-loop="true" data-autoplay="true">
                           <img src="images/0 (295).jpg">
                           <img src="images/0 (327).jpg">
                        </div>
                     </div>
                     <div class="col-md-4" style="padding: 3%;">
                        <h3>Boditrak</h3>
                        <p>Alfombra portátil con sensores que mediante un programa de aplicación (software) ofrece datos en tiempo real sobre como el golfista mueve las presiones de su cuerpo e interactúa con el suelo en cada momento del swing.</p>
                     </div>
                  </div>
                  <div class="row" style="margin-top:60px; margin-bottom: 60px; ">
                     <div class="col-md-4 " style="padding: 3%;">
                        <h3>V1</h3>
                        <p>Programa de aplicación (software) utilizado para realizar evaluaciones técnicas sobre el swing que permite al jugador visualizar las correcciones indicadas por el instructor maximizando su experiencia de aprendizaje. Ademas ofrece la posibilidad al instructor de enviar via e-mail a sus alumnos videos repaso de lo trabajado durante la clase.</p>
                     </div>
                     <div class="col-md-8">
                        <div class="fotorama" data-width="100%" data-height="" data-fit="contain" data-transition="crossfade"  data-autoplay="true" data-arrows="true" data-click="true"  data-loop="true" data-autoplay="true">
                           <img src="images/0 (144).jpg">
                           <img src="images/0 (149).jpg">
                        </div>
                     </div>
                  </div>
                  <div class="row" style="margin-top:60px; margin-bottom: 60px; ">
                     <div class="col-md-8 ">
                        <div class="fotorama" data-width="100%" data-height="" data-fit="contain" data-transition="crossfade"  data-autoplay="true" data-arrows="true" data-click="true"  data-loop="true" data-autoplay="true">
                           <img src="images/0 (193).jpg">
                           <img src="images/0 (254).jpg">
                        </div>
                     </div>
                     <div class="col-md-4" style="padding: 3%;">
                        <h3>K-Vest</h3>
                        <p>Único sistema de aprendizaje de movimiento humano de la industria. Consiste en un sistema inalámbrico todo en uno que mide instantáneamente datos 3D del swing del golfista. Evalúa las características del jugador, genera informes precisos y automáticamente transfiere esos informes a un creador de programas de entrenamiento que en tiempo real posibilitan que el jugador sienta nuevos patrones de movimiento.</p>
                     </div>
                  </div>
                  <div class="row" style="margin-top:60px; margin-bottom: 60px; ">
                     <div class="col-md-4 " style="padding: 3%;">
                        <h3>Sam Putt Lab</h3>
                        <p>Sistema de entrenamiento y análisis de putt más completo y preciso del mundo. Portátil para uso interior y exterior. Ofrece análisis de todos los aspectos más relevantes del swing de putt y también módulos de entrenamiento específicos.</p>
                     </div>
                     <div class="col-md-8">
                        <div class="fotorama" data-width="100%" data-height="" data-fit="contain" data-transition="crossfade"  data-autoplay="true" data-arrows="true" data-click="true"  data-loop="true" data-autoplay="true">
                           <img src="images/0 (507).jpg">
                           <img src="images/0 (551).jpg">
                        </div>
                     </div>
                  </div>
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