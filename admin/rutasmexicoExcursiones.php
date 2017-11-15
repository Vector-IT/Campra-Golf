<?php 
	session_start();
	
	if ((!isset($_SESSION['is_logged_in'])) ||
			($_SESSION['TipoUsua'] != "1")) {
		header("Location:login.php?returnUrl=" . $_SERVER[REQUEST_URI]);
		die();
	}
	
	include_once 'php/conexion.php';
	
	$NumeItin = $_GET["Itin"];
	$tabla = cargarTabla("SELECT r.Nombre, i.Codigo FROM rutasmexicoitinerarios i INNER JOIN rutasmexico r ON i.NumeRuta = r.NumeRuta WHERE i.NumeItin = ".$NumeItin);
	$fila = $tabla->fetch_array();
	
	$NombRuta = $fila["Nombre"];
	$Codigo = $fila["Codigo"];
	
	$tabla->free();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="author" content="Vector-IT" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<title>Campra Golf - Admin Panel</title>
	
	<link href="css/estilos.css" rel="stylesheet" type="text/css">
	
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	
	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

	<!--  BOOTSTRAP -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>	

	<script type="text/javascript" src="js/vectorMenu.js"></script>

	<script type="text/javascript">
		$(document).ready(function() {
			$(".menu").vectorMenu({
				trigger: ".btnMenu",
				duration: 100,
				opacity: 0.8,
				background: "#000",
				closeWidth: "30px"
			});
			
			$("#divMsj").hide();
			listar();
		});

		function listar() {
			//Borro los datos
	    	$("#divDatos").html(""); 
		    	
	    	if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp = new XMLHttpRequest();
			}
			else
			{// code for IE6, IE5
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState==4 && xmlhttp.status==200) {
					$('#actualizando').css('display', 'none');
					
					var excursiones = xmlhttp.responseText.split("@@");

					for (var I = 0; I < excursiones.length; I++) {
						$("#chkNumeExcu" + excursiones[I]).prop('checked', true);
					}
				}
			};

			xmlhttp.open("POST","php/rutasmexicoExcursionesProcesar.php",true);
			xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			xmlhttp.send("operacion=10&NumeItin=<?php echo $NumeItin?>");
		}

		function aceptar(){
		    $('#actualizando').css('display', 'block');
		    
            var frmData = new FormData();

            var excursiones = "";
			for (var I = 0; I < $(".chk").length; I++) {
				if ($($(".chk")[I]).prop('checked')) {
					if (excursiones != "")
						excursiones+= "@@";

					excursiones+= $(".chk")[I].id.replace("chkNumeExcu", "");
				}
			}
				
            frmData.append("operacion", $("#hdnOperacion").val());
            frmData.append("NumeItin", <?php echo $NumeItin?>);
            frmData.append("Excursiones", excursiones);

			if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp = new XMLHttpRequest();
			}
			else
			{// code for IE6, IE5
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState==4 && xmlhttp.status==200) {
					$("#txtHint").html(xmlhttp.responseText);
					
					if (xmlhttp.responseText.indexOf('Error') == -1) {
						$("#divMsj").removeClass("alert-danger");
						$("#divMsj").addClass("alert-success");
						listar();
					}
					else {
						$("#divMsj").removeClass("alert-success");
						$("#divMsj").addClass("alert-danger");
					}
						
					$('#actualizando').css('display', 'none');
					$("#divMsj").show();
				}
			};

			xmlhttp.open("POST","php/rutasmexicoExcursionesProcesar.php",true);
			xmlhttp.send(frmData);
		}
	</script>
</head>
<body>
	<?php
		include("php/menu-rutasmexico.php");
	?>
	<div class="jumbotron">
		<div class="container">
			<img alt="logo" src="imagenes/logo.png">
		</div>
		<div class="absolute top5 right5">
			<small>
			<?php
				echo $_SESSION["NombUsua"];
			?>
			</small>
			<button class="btn btn-default btn-xs" onclick="location.href='logout.php';">Logout</button>
		</div>
	</div>	

	<div class="container">
		<div class="page-header">
			<h2>Excursiones de <?php echo $NombRuta?> - <?php echo $Codigo?></h2>
		</div>
		
		<form class="form-horizontal" id="formulario" method="post" onSubmit="return false;">
			<input type="hidden" id="hdnOperacion" value="0" />
			<div class="form-group">
				<div class="col-md-3">
					<input type="button" class="btn btn-primary" onclick="history.go(-1);" value="Volver" />
				</div>
			</div>
			<?php
				$tabla = cargarTabla("SELECT NumeExcu, Titulo, Ciudad FROM excursiones ORDER BY Titulo");
				
				while ($fila = $tabla->fetch_array()) {
			?>
			<div class="form-group">
				<div class="col-md-4">
					<label class="labelCheck">
						<input id="chkNumeExcu<?php echo $fila["NumeExcu"]?>" type="checkbox" class="chk"> <span id="Titulo<?php echo $fila["NumeExcu"]?>"><?php echo $fila["Titulo"]?></span> - <?php echo $fila["Ciudad"]?> 
					</label>
				</div>
			</div>
			<?php 
				}
				
				if (isset($tabla)) 
					$tabla->free();
			?>
			
			<div class="form-group">
				<div class="col-md-offset-1 col-md-4">
					<button type="submit" class="btn btn-primary" onclick="aceptar();">Aceptar</button>
					&nbsp;
					<button type="reset" class="btn btn-default" onclick="listar();">Cancelar</button>
				</div>
			</div>
		</form>
		
		<div id="actualizando" class="alert alert-info" role="alert">
			<img alt="" src="imagenes/spinner.gif" style="width: 16px;"> Actualizando datos, por favor espere...
		</div>
		
		<div id="divMsj" class="alert alert-danger alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<span id="txtHint">info</span>
		</div>
	</div>
</body>
</html>