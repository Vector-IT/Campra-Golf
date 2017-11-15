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
	<div class="item" data-url="rutasmexicoIndex.php" title="Banner de Rutas de M&eacute;xico">
		Banner de Rutas de M&eacute;xico
		<div class="flRight"><i class="fa fa-image fa-fw"></i></div>
	</div>
	
	<div class="separator"></div>
	<div class="item" data-url="hoteles.php" title="Hoteles">
		Hoteles
		<div class="flRight"><i class="fa fa-bed fa-fw"></i></div>
	</div>
	
	<div class="separator"></div>
	<div class="item" data-url="excursiones.php" title="Excursiones">
		Excursiones
		<div class="flRight"><i class="fa fa-car fa-fw"></i></div>
	</div>
	
	<div class="separator"></div>
	<div class="item" data-url="rutasmexico.php" title="Rutas">
		Rutas
		<div class="flRight"><i class="fa fa-road fa-fw"></i></div>
	</div>
	
	<div class="separator"></div>
	<div class="item" data-url="ciudades.php" title="Ciudades">
		Ciudades
		<div class="flRight"><i class="fa fa-list-alt fa-fw"></i></div>
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