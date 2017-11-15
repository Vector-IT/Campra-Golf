<?php include_once 'conexion.php';?>
<div id="sidebar" class="menu">
	<div class="absolute top5 right5">
		<button class="btnMenu btn btn-default btn-xs" title="Men&uacute;"><i class="fa fa-bars"></i></button>
	</div>
	<div class="item" data-url="index.php" title="Inicio">
		Inicio
		<div class="flRight"><i class="fa fa-home fa-fw"></i></div>
	</div>
	<div class="separator"></div>
	<div class="item" data-url="banners.php" title="Banner de Inicio">
		Banner de Inicio
		<div class="flRight"><i class="fa fa-image fa-fw"></i></div>
	</div>
	<div class="separator"></div>
	<div class="item" data-url="institucional.php" title="Institucional">
		Institucional
		<div class="flRight"><i class="fa fa-table fa-fw"></i></div>
	</div>
	<div class="separator"></div>
	<div class="item" data-url="unidades-de-negocio.php" title="Unidades de Negocio">
		Unidades de Negocio
		<div class="flRight"><i class="fa fa-university fa-fw"></i></div>
	</div>
	<div class="separator"></div>
	<div class="item" data-url="productos.php" title="Productos">
		Productos
		<div class="flRight"><i class="fa fa-bus fa-fw"></i></div>
	</div>
	<div class="separator"></div>
	<div class="item" data-url="novedades.php" title="Novedades">
		Novedades
		<div class="flRight"><i class="fa fa-file-text fa-fw"></i></div>
	</div>
	<div class="separator"></div>
	<div class="item" data-url="usuarios.php" title="Usuarios">
		Usuarios
		<div class="flRight"><i class="fa fa-users fa-fw"></i></div>
	</div>
	<div class="separator"></div>
	<div class="item" data-url="<?php echo $raiz;?>" title="Ir al Sitio">
		Ir al Sitio
		<div class="flRight"><i class="fa fa-paper-plane fa-fw"></i></div>
	</div>
	<div class="separator"></div>
	<div class="item" data-url="logout.php" title="Salir">
		Salir
		<div class="flRight"><i class="fa fa-sign-out fa-fw"></i></div>
	</div>
</div>

<button class="btnMenu onlyMobile btn btn-default btn-xs fixed top5 left5" title="Men&uacute;"><i class="fa fa-bars"></i></button>