<?php 
	session_start();
	
	if (!isset($_SESSION['is_logged_in'])) {
		header("Location:login.php?returnUrl=" . $_SERVER[REQUEST_URI]);
		die();
	}
	elseif	($_SESSION['TipoUsua'] != "1") {
			header("Location:logout.php");
			die();
	}
	elseif ($_SESSION["chkCotizaciones"] != "1") {
		header("Location:index.php");
		die();
	}
	
	include 'php/conexion.php';
	
	//Filtros
	$experiencia = "-1";
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="author" content="Vector-IT" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="../images/favicon.ico" />
	
	<title>Campra Golf - Admin Panel</title>
	
	<link href="css/estilos.css" rel="stylesheet" type="text/css">
	
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	
	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

	<!--  BOOTSTRAP -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>	

	<script type="text/javascript" src="js/vectorMenu.js"></script>
	<script src="../js/jquery.ns-autogrow.min.js"></script>

	<script type="text/javascript">
		function filtrarTours() {
			var NumeExpe = $("#experiencia").val();
			var NumeTour = "-1";
	
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
					$("#tour").html(xmlhttp.responseText);
				}
			};
	
			xmlhttp.open("POST","php/toursProcesar.php",true);
			xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			xmlhttp.send("operacion=20&NumeTour=" + NumeTour + "&NumeExpe=" + NumeExpe);
		}	

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

			$('#comentario').css('overflow', 'hidden').autogrow({vertical: true, horizontal: false});
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
					$("#divDatos").html(xmlhttp.responseText);
				}
			};

			xmlhttp.open("POST","php/cotizarProcesar.php",true);
			xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			xmlhttp.send("operacion=10");
		}

		function aceptar(){
		    $('#actualizando').css('display', 'block');
		    
            var frmData = new FormData();

            if ($("#hdnOperacion").val() != "2") {
                if (!validar())
                    return;
			}
			var strID = $("#numero").val();
			
            frmData.append("operacion", $("#hdnOperacion").val());
            frmData.append("NumeCoti", $("#numero").val());
            frmData.append("NumeAgen", $("#NumeAgen" + strID).val());
            frmData.append("NumeUsua", $("#NumeUsua" + strID).val());
            frmData.append("Codigo", $("#codigo").val());
            frmData.append("NumeExpe", $("#experiencia").val());
            frmData.append("NumeTour", $("#tour").val());
            frmData.append("Nombre", $("#nombre").val());
            frmData.append("Email", $("#email").val());
            frmData.append("Telefono", $("#telefono").val());
            frmData.append("NumeProv", $("#provincia").val());
            frmData.append("Pasajero", $("#pasajero").val());
            frmData.append("FechViaj", $("#fecha").val());
            frmData.append("Origen", $("#origen").val());
            frmData.append("Aereo", $("#aereo").val());
            frmData.append("AdulCant", $("#adulCant").val());
            frmData.append("AdulEdad", $("#adulEdad").val());
            frmData.append("MenoCant", $("#menoCant").val());
            frmData.append("MenoEdad", $("#menoEdad").val());
            frmData.append("InfaCant", $("#infaCant").val());
            frmData.append("InfaEdad", $("#infaEdad").val());
            frmData.append("Comentario", $("#comentario").val());
            frmData.append("NumeEsta", $("#numeEsta").val());
            
            
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
						editar(0);
					}
					else {
						$("#divMsj").removeClass("alert-success");
						$("#divMsj").addClass("alert-danger");
					}
						
					$('#actualizando').css('display', 'none');
					$("#divMsj").show();
				}
			};

			xmlhttp.open("POST","php/cotizarProcesar.php",true);
			xmlhttp.send(frmData);
		}

	    function editar(strID){
		    if (strID > 0) {
		    	$('html, body').animate({
		            scrollTop: $("#formulario").offset().top
		        }, 1000);

		        editar(0);
		        $('#hdnOperacion').val("1");

	            $('#numero').val(strID);
	            $('#nombre').val($("#NombFlyer" + strID).html());
	            $('#tour').val($("#NumeTour" + strID).val());

	            $("#agencia").val($("#NombAgen" + strID).html());
	            $("#numeUsua").val($("#NumeUsua" + strID).val());
	            $("#codigo").val($("#Codigo" + strID).html());
	            $("#experiencia").val($("#NumeExpe" + strID).val());
	            $("#tour").val($("#NumeTour" + strID).val());
	            $("#nombre").val($("#Nombre" + strID).html());
	            $("#email").val($("#Email" + strID).html());
	            $("#telefono").val($("#Telefono" + strID).val());
	            $("#provincia").val($("#NumeProv" + strID).val());
	            $("#pasajero").val($("#Pasajero" + strID).val());
	            $("#fecha").val($("#FechViaj" + strID).val());
	            $("#origen").val($("#Origen" + strID).val());
	            $("#aereo").val($("#Aereo" + strID).val());
	            $("#adulCant").val($("#AdulCant" + strID).val());
	            $("#adulEdad").val($("#AdulEdad" + strID).val());
	            $("#menoCant").val($("#MenoCant" + strID).val());
	            $("#menoEdad").val($("#MenoEdad" + strID).val());
	            $("#infaCant").val($("#InfaCant" + strID).val());
	            $("#infaEdad").val($("#InfaEdad" + strID).val());
	            $("#comentario").val($("#Comentario" + strID).val());	            
	            $("#numeEsta").val($("#NumeEsta" + strID).val());	            
		    }
		    else {
		        $('#hdnOperacion').val("0");

		        $("#numero").val("");
		        $("#agencia").val("");
		        $("#numeUsua").val("");
		        $("#codigo").val("");
		        $("#experiencia").val("");
		        $("#tour").val("");
		        $("#nombre").val("");
		        $("#email").val("");
		        $("#telefono").val("");
		        $("#provincia").val("");
		        $("#pasajero").val("");
		        $("#fecha").val("");
		        $("#origen").val("");
		        $("#aereo").val("");
		        $("#adulCant").val("");
		        $("#adulEdad").val("");
		        $("#menoCant").val("");
		        $("#menoEdad").val("");
		        $("#infaCant").val("");
		        $("#infaEdad").val("");
		        $("#comentario").val("");
			}
		}

		function borrar(strID){
			if (confirm("Desea borrar el registro seleccionado?")){
                $('#hdnOperacion').val("2");
                $('#numero').val(strID);
                aceptar();
	        }
	    }

	    function validar() {
		    var mensaje = "";

	        if (mensaje != "") {
				$("#txtHint").html(mensaje);
				$("#divMsj").removeClass("alert-success");
				$("#divMsj").addClass("alert-danger");
				$("#divMsj").show();
				$("#actualizando").css("display", "none");
				return false;
	        }
	        else
		        return true;
	    }
	</script>
</head>
<body>
	<?php
		include("php/menu.php");
	?>
	<div class="jumbotron">
		<div class="container">
			<img alt="logo" src="imagenes/logo.png">
		</div>
		<div class="absolute top5 left5">
			<button id="btnMenu" class="btn btn-default btn-xs" title="Men&uacute;"><i class="fa fa-bars"></i></button>
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
			<h2>Cotizaciones</h2>
		</div>
		
		<form class="form-horizontal" id="formulario" method="post" onSubmit="return false;">
			<input type="hidden" id="hdnOperacion" value="0" />
			
			<div class="form-group">
				<label for="numero" class="control-label col-md-2">N&uacute;mero:</label>
				<div class="col-md-2">
					<input type="text" class="form-control" id="numero" name="numero" disabled />
				</div>
			</div>
			<div class="form-group">
				<label for="codigo" class="control-label col-md-2">C&oacute;digo:</label>
				<div class="col-md-5">
					<input type="text" class="form-control" id="codigo" />
				</div>
			</div>
			<div class="form-group">
				<label for="experiencia" class="control-label col-md-2">Experiencia:</label>
				<div class="col-md-5">
					<select class="form-control" id="experiencia" name="experiencia" onchange="filtrarTours();" >
						<?php echo cargarCombo("SELECT NumeExpe, NombExpe FROM experiencias WHERE NumeEsta = 1 ORDER BY NombExpe", "NumeExpe", "NombExpe", "", true, "Todas"); ?>

						<option value="-2">Rutas M&eacute;xico</option>
						<option value="-3">Otra</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label for="tour" class="control-label col-md-2">Tour:</label>
				<div class="col-md-5">
					<select class="form-control" name="tour" id="tour">
					<?php
						if ($experiencia == "-1")
							echo cargarCombo("SELECT NumeTour, NombTour FROM tours WHERE NumeEsta = 1 AND EnPromo = 0 ORDER BY NombTour", "NumeTour", "NombTour", $tour, true, "Todos");
						else 
							echo cargarCombo("SELECT NumeTour, NombTour FROM tours WHERE NumeEsta = 1 AND EnPromo = 0 AND NumeExpe = {$experiencia} ORDER BY NombTour", "NumeTour", "NombTour", $tour, true, "Todos");
					?>
						<option value="-2">Otros</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label for="nombre" class="control-label col-md-2">Nombre:</label>
				<div class="col-md-5">
					<input type="text" class="form-control" id="nombre" />
				</div>
			</div>
			<div class="form-group">
				<label for="agencia" class="control-label col-md-2">Agencia:</label>
				<div class="col-md-5">
					<input type="text" class="form-control" id="agencia" readonly />
				</div>
			</div>
			<div class="form-group">
				<label for="email" class="control-label col-md-2">Email:</label>
				<div class="col-md-5">
					<input type="email" class="form-control" id="email" />
				</div>
			</div>
			<div class="form-group">
				<label for="telefono" class="control-label col-md-2">Tel&eacute;fono:</label>
				<div class="col-md-5">
					<input type="tel" class="form-control" id="telefono" required />
				</div>
			</div>
			<div class="form-group">
				<label for="provincia" class="control-label col-md-2">Estado:</label>
				<div class="col-md-5">
					<select class="form-control" id="provincia">
						<?php echo cargarCombo("SELECT NumeProv, NombProv FROM provincias ORDER BY NombProv", "NumeProv", "NombProv", "", true, "Todos"); ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-7">
					<h4>Datos del pasajero</h4>
				</div>
			</div>
			<div class="form-group">
				<label for="pasajero" class="control-label col-md-2">Pasajero:</label>
				<div class="col-md-5">
					<input type="text" class="form-control" id="pasajero" />
				</div>
			</div>
			<div class="form-group">
				<label for="fecha" class="control-label col-md-2">Fecha de viaje:</label>
				<div class="col-md-5">
					<input type="text" class="form-control" id="fecha" required />
				</div>
			</div>
			<div class="form-group">
				<label for="origen" class="control-label col-md-2">Lugar de origen:</label>
				<div class="col-md-5">
					<input type="text" class="form-control" id="origen" />
				</div>
			</div>
			<div class="form-group">
				<label for="aereo" class="control-label col-md-2">Servicios a&eacute;reos:</label>
				<div class="col-md-5">
					<select class="form-control" id="aereo">
						<option value="0">NO</option>
						<option value="1" selected>SI</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label for="adulCant" class="control-label col-md-2">Adultos:</label>
				<div class="col-md-2">
					<input type="number" class="form-control" id="adulCant" min="0" max="10" />
				</div>
				<label for="adulEdad" class="control-label col-md-1">Edades:</label>
				<div class="col-md-2">
					<input type="text" class="form-control" id="adulEdad" />
				</div>
			</div>
			<div class="form-group">
				<label for="menoCant" class="control-label col-md-2">Menores:</label>
				<div class="col-md-2">
					<input type="number" class="form-control" id="menoCant" min="0" max="10" />
				</div>
				<label for="menoEdad" class="control-label col-md-1">Edades:</label>
				<div class="col-md-2">
					<input type="text" class="form-control" id="menoEdad" />
				</div>
			</div>
			<div class="form-group">
				<label for="infaCant" class="control-label col-md-2">Infantiles:</label>
				<div class="col-md-2">
					<input type="number" class="form-control" id="infaCant" min="0" max="10" />
				</div>
				<label for="infaEdad" class="control-label col-md-1">Edades:</label>
				<div class="col-md-2">
					<input type="text" class="form-control" id="infaEdad" />
				</div>
			</div>
			<div class="form-group">
				<label for="comentario" class="control-label col-md-2">Comentarios:</label>
				<div class="col-md-5">
					<textarea id="comentario" class="form-control" required></textarea>
				</div>
			</div>
			<div class="form-group">
				<label for="numeEsta" class="control-label col-md-2">Status:</label>
				<div class="col-md-5">
					<select id="numeEsta" class="form-control">
						<option value="1">Activa</option>
						<option value="2">Cancelada</option>
						<option value="3">A Solicitud</option>
						<option value="4">Venta Concretada</option>
					</select>
				</div>
			</div>
						
			<div class="form-group">
				<div class="col-md-offset-2 col-md-4">
					<button type="submit" class="btn btn-primary" onclick="aceptar();">Aceptar</button>
					&nbsp;
					<button type="reset" class="btn btn-default" onclick="editar(0);">Cancelar</button>
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
		
		<p>		
			<button class="btn btn-default" onclick="location.href = 'php/cotizacionesExcel.php';"><i class="fa fa-file-excel-o"></i> Exportar a Excel</button>
		</p>
		
		<div class="table-responsive" id="divDatos">
			
		</div>	
	</div>
</body>
</html>