<?php 
	session_start();
	
	if ((!isset($_SESSION['is_logged_in'])) ||
			($_SESSION['TipoUsua'] != "1")) {
		header("Location:login.php?returnUrl=" . $_SERVER[REQUEST_URI]);
		die();
	}
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
	<script src="js/jquery.ns-autogrow.min.js"></script>

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
			
			$('#descripcion').css('overflow', 'hidden').autogrow({vertical: true, horizontal: false});
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

			xmlhttp.open("POST","php/excursionesProcesar.php",true);
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
            frmData.append("operacion", $("#hdnOperacion").val());
            frmData.append("NumeExcu", $("#numero").val());
            frmData.append("Titulo", $("#nombre").val());
            frmData.append("Ciudad", $("#ciudad").val());
            frmData.append("Descripcion", $("#descripcion").val());
            frmData.append("Precio", $("#precio").val());

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

			xmlhttp.open("POST","php/excursionesProcesar.php",true);
			xmlhttp.send(frmData);
		}

	    function editar(strID){
		    if (strID > 0) {
		    	$('html, body').animate({
		            scrollTop: $("#formulario").offset().top
		        }, 1000);
		        
		        $('#hdnOperacion').val("1");

	            $('#numero').val(strID);
	            $('#nombre').val($('#Titulo' + strID).html());
	            $('#ciudad').val($('#Ciudad' + strID).html());
	            $('#descripcion').val($('#Descripcion' + strID).val());
	            $('#precio').val($('#Precio' + strID).html());

	            $('#descripcion').css('overflow', 'hidden').autogrow({vertical: true, horizontal: false});
		    }
		    else {
		        $('#hdnOperacion').val("0");

		        $('#numero').val("");
	            $('#nombre').val("");
	            $('#ciudad').val("");
	            $('#descripcion').val("");
	            $('#precio').val("");

	            $('#descripcion').css('overflow', 'hidden').autogrow({vertical: true, horizontal: false});
			}
		}

		function borrar(strID){
	        if (confirm("Desea borrar la excursion " + $('#Titulo' + strID).html())){
                $('#hdnOperacion').val("2");
                $('#numero').val(strID);
                aceptar();
	        }
	    }

	    function validar() {
		    var mensaje = "";

		    if ($('#nombre').val().trim().length == 0)
		        mensaje+= "El Nombre de la Excursi&oacute;n no puede estar vac&iacute;o.";

	        if ($('#ciudad').val().trim().length == 0) {
		        if (mensaje != "")
			        mensaje+= "<br>";
        		mensaje+= "El campo Ciudad de la Excursi&oacute;n no puede estar vac&iacute;o.";
        	}

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
			<h2>Excursiones</h2>
		</div>
		
		<form class="form-horizontal" id="formulario" method="post" onSubmit="return false;">
			<input type="hidden" id="hdnOperacion" value="0" />
			<div class="form-group">
				<div class="col-md-3">
					<input type="button" class="btn btn-primary" onclick="editar(0);" value="Nuevo" />
				</div>
			</div>
			
			<div class="form-group">
				<label for="numero" class="control-label col-md-2">N&uacute;mero:</label>
				<div class="col-md-2">
					<input type="text" class="form-control" id="numero" disabled />
				</div>
			</div>
			<div class="form-group">
				<label for="nombre" class="control-label col-md-2">Nombre:</label>
				<div class="col-md-4">
					<input type="text" class="form-control" id="nombre" />
				</div>
			</div>
			<div class="form-group">
				<label for="ciudad" class="control-label col-md-2">Ciudad:</label>
				<div class="col-md-4">
					<input type="text" class="form-control" id="ciudad" />
				</div>
			</div>
			<div class="form-group">
				<label for="descripcion" class="control-label col-md-2">Descripci&oacute;n:</label>
				<div class="col-md-4">
					<textarea class="form-control" id="descripcion"></textarea>
				</div>
			</div>
			<div class="form-group">
				<label for="precio" class="control-label col-md-2">Precio:</label>
				<div class="col-md-4">
					<input type="text" class="form-control" id="precio" />
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
		
		<div class="table-responsive" id="divDatos">
			
		</div>
	</div>
</body>
</html>