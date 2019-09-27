<?php 
	include_once 'admin/php/conexion.php';
?>
<nav class="navbar navbar-default top-line" style="max-width: 1234px ! important; margin: 0px auto;">
	<div class="container">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse-1, #navbar-collapse-2" aria-expanded="false">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			</button>
			<?php if ($imagAgen != "admin/") { ?>
			<a class="navbar-brand" href="index.php">
			<img src="<?php echo $imagAgen;?>" alt="">
			</a>
			<?php } ?>
		</div>
		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="navbar-collapse-1">
			<ul class="nav navbar-nav navbar-right align-right" style="padding-left:0px !important;">
				<li class="dropdown-submenu" style="float:right; padding-left: 30px; display: none;">
					<a href="#">Atenci&oacute;n Agencias <span class="glyphicon glyphicon-menu-hamburger" aria-hidden="true" style="color: #01A66E; margin-left: 5px; font-weight: light !important;"></span></a>
					<ul class="dropdown-menu subMenu">
						<li>
							<?php if (!isset($_SESSION['is_logged_in'])) { ?>
								<a href="#login-modal" onclick="abrirModal($('#register-form-agencia'));" data-toggle="modal" role="button">Regitrarse</a>							
								<a href="#login-modal" onclick="abrirModal($('#login-form'));" data-toggle="modal" role="button">Iniciar Sesi&oacute;n</a>
							<?php } else { ?>
								<a href="logout.php" role="button">Cerrar Sesi&oacute;n</a>
							<?php } ?>
							
						<li>
					</ul>
				</li>
				<li class="dropdown-submenu" style="float:right;">
					<a  href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Registro <span class="glyphicon glyphicon-menu-hamburger" aria-hidden="true" style="color: #01A66E; margin-left: 5px; font-weight: light !important;"></span></a>
							<div class="dropdown-menu">
							<?php
								include_once("registroNewsletter.php");
							?>
							</div>

					<ul class="dropdown-menu subMenu" style="display: none;">
						<?php 
							if (isset($_SESSION['TipoUsua'])) {
								if ($_SESSION['TipoUsua'] < 2) {
						?>
						<li>
							<a href="#login-modal" onclick="abrirModal($('#register-form-usuario'));" data-toggle="modal" role="button">Regitrar Visitante</a>
						<li>
						<?php 
								} 
							}
						?>
						<li style="display: none;">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Registro Newsletter</a>
							<div class="dropdown-menu">
							<?php
								include_once("registroNewsletter.php");
							?>
							</div>
						</li>
						<li  style="display: none;">
							<a href="catalogo-online.php" role="button">Catálogo Online<span class="glyphicon glyphicon glyphicon-file" aria-hidden="true" style="color: #C79B2E; margin-left: 5px; font-weight: light !important;"></span></a>
						</li>
					</ul>
				</li>
				
				<ul class="align-right" style="padding-top: 15px; display: table-row;">

				<a href="https://www.youtube.com/channel/UCktq_oCQ7k3Z8DxrWBIJ0eg" class="navbar-link social" target="_blank"><img src="images/icon-youtube.png" onmouseover="this.src='images/icon-youtube-negro.png'" onmouseout="this.src='images/icon-youtube.png'" width="28" height="28" alt="YouTube"></a>

					<?php 
						$salida = "";
						if ($twitAgen != "") {
							$salida.= $crlf.'<a href="'.$twitAgen.'" class="navbar-link social" target="_blank" >';
							$salida.= $crlf.'<img src="images/icon-twitter.png" onmouseover="this.src=\'images/icon-twitter-negro.png\'" onmouseout="this.src=\'images/icon-twitter.png\'" width="28" height="28" alt="Twitter">';
							$salida.= $crlf.'</a>';
						}
						
						if ($instAgen != "") {
							$salida.= $crlf.'<a href="'.$instAgen.'" class="navbar-link social" target="_blank" >';
							$salida.= $crlf.'<img src="images/icon-instagram.png" onmouseover="this.src=\'images/icon-instagram-negro.png\'" onmouseout="this.src=\'images/icon-instagram.png\'" width="28" height="28" alt="Instagram">';
							$salida.= $crlf.'</a>';
						}
						
						if ($faceAgen != "") {
							$salida.= $crlf.'<a href="'.$faceAgen.'" class="navbar-link social" target="_blank" >';
							$salida.= $crlf.'<img src="images/icon-facebook.png" onmouseover="this.src=\'images/icon-facebook-negro.png\'" onmouseout="this.src=\'images/icon-facebook.png\'" width="28" height="28" alt="Facbook">';
							$salida.= $crlf.'</a>';
						}
						
						echo $salida;
					?>
					
					<li style="list-style:none!important;">
						<?php
							$salida = "";
							if (isset($_SESSION['is_logged_in'])) {
								$salida.= $crlf.'<a href="logout.php" style="float: right; font-size: 12px !important; margin-left: 10px; margin-top: 5px; ">';
								$salida.= $crlf.$_SESSION["NombUsua"].'&nbsp;<span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>';
								$salida.= $crlf.'</a>';

								if ($_SESSION['TipoUsua'] == "1")
									$salida.= $crlf.'<a href="'.$raiz.'admin" title="Administrar" style="float: right; font-size: 12px !important; margin-left: 10px; margin-top: 5px;"><span class="glyphicon glyphicon-wrench" aria-hidden="true"></span></a>';
								
								$salida.= $crlf.'<span style="float: right; font-size: 12px !important; margin-left: 10px; margin-top: 5px;">'. $_SESSION['NombAgen'] .'</span>';									
							}
							
							echo $salida;
						?>
						
					</li>
				</ul>
				<div style="clear: both;"></div>
				<ul class="align-right" style="padding-top: 15px;">
					<?php 
						if ($teleAgen != "") 
							echo '';
					?>
				</ul>				
			</ul>
			<ul class="nav navbar-nav navbar-right" style="padding-top: 15px; ">
				<form id="search-form" class="navbar-form" role="search" style="margin-right: 0px !important; margin-top: 0px;">
					<div class="form-group">
						<input type="text" id="search-text" class="form-control" placeholder="Buscar" >
					</div>
					<button type="submit" class="btn btn-search"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
				</form>
			</ul>
		</div>

		<!-- /.navbar-collapse -->
		<div class="collapse navbar-collapse" id="navbar-collapse-2">
			<ul class="nav navbar-nav center">
				<li><a href="quienes-somos.php">Quienes Somos</a></li>
				<li><a href="experiencia/instruccion-de-golf">Instrucción de Golf</a></li>	
				<li><a href="experiencia/menores-y-juveniles">Menores y Juveniles</a></li>
				<li><a href="experiencia/campra-viajes">Viajes Golf</a></li>
				<li><a href="college-placement.php">College Placement</a></li>
				<li><a href="experiencia/campra-golf-tour">Campra Golf Tour</a></li>			
				<li><a href="experiencia/clinicas-y-seminario">Clínicas y Seminarios</a></li>	
				<li style="border-right: none !important;"><a href="contacto.php">Contacto</a></li>	
			</ul>
		</div>
		<!-- /.navbar-collapse-2 -->
	</div>
	<!-- /.container-fluid -->
</nav>