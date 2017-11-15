<?php 
	session_start();
	
	if ((!isset($_SESSION['is_logged_in'])) ||
			($_SESSION['TipoUsua'] != "1")) {
		header("Location:login.php?returnUrl=" . $_SERVER[REQUEST_URI]);
		die();
	}
	
	include_once 'php/conexion.php';
	
	$NumeRuta = $_GET["Ruta"];
	$NombRuta = buscarDato("SELECT Nombre FROM rutasmexico WHERE NumeRuta = ".$NumeRuta);
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

			$(".chkHotel").change(function() {
				var strID = this.id.replace("chkNumeHotel", "");
				
				if($(this).is(":checked")) {
					$("#estancia" + strID).prop("disabled", false);
				}
				else {
					$("#estancia" + strID).prop("disabled", true);
				}
			});

			$(".chkDias").change(function() {
				var strID = this.id.replace("chkNumeDia", "");
				
				if($(this).is(":checked")) {
					$("#diasemana" + strID).prop("disabled", false);
					$("#numeorde" + strID).prop("disabled", false);
				}
				else {
					$("#diasemana" + strID).prop("disabled", true);
					$("#numeorde" + strID).prop("disabled", true);
				}
			});
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

			xmlhttp.open("POST","php/rutasmexicoItinerariosProcesar.php",true);
			xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			xmlhttp.send("operacion=10&NumeRuta=<?php echo $NumeRuta?>");
		}

		function aceptar(){
		    $('#actualizando').css('display', 'block');
		    
            var frmData = new FormData();

            if ($("#hdnOperacion").val() != "2") {
                if (!validar())
                    return;
			}

            //Hoteles
            var numeHotel = "";
            var estancia = "";
            
            for (var I = 0; I < $(".chkHotel").length; I++) {
            	if ($($(".chkHotel")[I]).prop('checked')) {
					if (numeHotel != "") {
						numeHotel+= "@@";
						estancia+= "@@";
					}

					var strID = $(".chkHotel")[I].id.replace("chkNumeHotel", "");
					numeHotel+= strID;
					
					estancia+= $("#estancia" + strID).val();
				}
            }
            
            //Excursiones
            var numeExcu = "";
            
            for (var I = 0; I < $(".chkExcu").length; I++) {
            	if ($($(".chkExcu")[I]).prop('checked')) {
					if (numeExcu != "") {
						numeExcu+= "@@";
					}

					var strID = $(".chkExcu")[I].id.replace("chkNumeExcu", "");
					numeExcu+= strID;
				}
            }
            
            //Dias
            var numeDias = "";
            var diaSemana = "";
            var numeOrde = "";
            
			for (var I = 0; I < $(".chkDias").length; I++) {
				if ($($(".chkDias")[I]).prop('checked')) {
					if (numeDias != "") {
						numeDias+= "@@";
						diaSemana+= "@@";
						numeOrde+= "@@";
					}

					var strID = $(".chkDias")[I].id.replace("chkNumeDia", "");
					numeDias+= strID;
					
					diaSemana+= $("#diasemana" + strID).val();
					numeOrde+= $("#numeorde" + strID).val();
				}
			}
			
            frmData.append("operacion", $("#hdnOperacion").val());
            frmData.append("NumeItin", $("#numero").val());
            frmData.append("NumeRuta", <?php echo $NumeRuta?>);
            frmData.append("Codigo", $("#codigo").val());
            frmData.append("Precio", $("#precio").val());
            frmData.append("CiudadFin", $("#ciudadFin").val());
            frmData.append("NumeHotel", numeHotel);
            frmData.append("Estancia", estancia);
            frmData.append("NumeExcu", numeExcu);
            frmData.append("NumeDias", numeDias);
            frmData.append("DiaSemana", diaSemana);
            frmData.append("NumeOrde", numeOrde);

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

			xmlhttp.open("POST","php/rutasmexicoItinerariosProcesar.php",true);
			xmlhttp.send(frmData);
		}

	    function editar(strID){
		    if (strID > 0) {
		    	$('html, body').animate({
		            scrollTop: $("#formulario").offset().top
		        }, 1000);
		        
		        $('#hdnOperacion').val("1");

		        $('#numero').val(strID);
	            $('#codigo').val($('#Codigo' + strID).html());
	            $('#precio').val($('#Precio' + strID).html());
	            $('#ciudadFin').val($('#CiudadFin' + strID).val());

	            var numeHotel = $('#NumeHotel' + strID).val().split("@@");
	            var estancia = $('#Estancia' + strID).val().split("@@");
	            for (var I = 0; I < $(".chkHotel").length; I++) {
					$($(".chkHotel")[I]).prop('checked', false);
					var aux = $(".chkHotel")[I].id.replace("chkNumeHotel", "");
					
					$("#estancia" + aux).prop('disabled', true);
		        }
		        
	            for (var I = 0; I < numeHotel.length; I++) {
					$("#chkNumeHotel" + numeHotel[I]).prop('checked', true);
					
					$("#estancia" + numeHotel[I]).prop('disabled', false);
					$("#estancia" + numeHotel[I]).val(estancia[I]);
				}
				
	            var numeExcu = $('#NumeExcu' + strID).val().split("@@");
	            for (var I = 0; I < $(".chkExcu").length; I++) {
					$($(".chkExcu")[I]).prop('checked', false);
		        }
		        
	            for (var I = 0; I < numeExcu.length; I++) {
					$("#chkNumeExcu" + numeExcu[I]).prop('checked', true);
				}

	            var numeDias = $('#NumeDias' + strID).val().split("@@");
	            var diaSemana = $('#DiaSemana' + strID).val().split("@@");
	            var numeOrde = $('#NumeOrde' + strID).val().split("@@");
	            for (var I = 0; I < $(".chkDias").length; I++) {
					$($(".chkDias")[I]).prop('checked', false);
					var aux = $(".chkDias")[I].id.replace("chkNumeDia", "");
					
					$("#diasemana" + aux).prop('disabled', true);
					$("#numeorde" + aux).prop('disabled', true);
		        }
		        
	            for (var I = 0; I < numeDias.length; I++) {
					$("#chkNumeDia" + numeDias[I]).prop('checked', true);
					
					$("#diasemana" + numeDias[I]).prop('disabled', false);
					$("#diasemana" + numeDias[I]).val(diaSemana[I]);
					
					$("#numeorde" + numeDias[I]).prop('disabled', false);
					$("#numeorde" + numeDias[I]).val(numeOrde[I]);
				}
	            
		    }
		    else {
		        $('#hdnOperacion').val("0");

		        $("#numero").val("");
		        $("#codigo").val("");
		        $("#precio").val("");
		        $("#ciudadFin").val("");

		        for (var I = 0; I < $(".chkHotel").length; I++) {
					$($(".chkHotel")[I]).prop('checked', false);
					strID = $(".chkHotel")[I].id.replace("chkNumeHotel", "");
					
					$("#estancia" + strID).prop('disabled', true);
		        }

		        for (var I = 0; I < $(".chkExcu").length; I++) {
					$($(".chkExcu")[I]).prop('checked', false);
		        }
		        
		        for (var I = 0; I < $(".chkDias").length; I++) {
					$($(".chkDias")[I]).prop('checked', false);
					strID = $(".chkDias")[I].id.replace("chkNumeDia", "");
					
					$("#diasemana" + strID).prop('disabled', true);
					$("#numeorde" + strID).prop('disabled', true);
		        }
			}
		}

		function borrar(strID){
	        if (confirm("Desea borrar el itinerario " + $('#Codigo' + strID).html())){
                $('#hdnOperacion').val("2");
                $('#numero').val(strID);
                aceptar();
	        }
	    }

	    function validar() {
		    var mensaje = "";

		    if ($('#codigo').val().trim().length == 0) {
		    	if (mensaje != "")
			        mensaje+= "<br>";
			        
		        mensaje+= "Debe establecer un C&oacute;digo.";
		    }
		    
		    if ($('#precio').val().trim().length == 0) {
		    	if (mensaje != "")
			        mensaje+= "<br>";
			        
		        mensaje+= "Debe establecer un Precio.";
		    }
		    
		    if ($('#ciudadFin').val().trim().length == 0) {
		    	if (mensaje != "")
			        mensaje+= "<br>";
			        
		        mensaje+= "Debe establecer una Ciudad de fin de itinerario.";
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

	    function abrirHoteles(strID) {
	    	location.href = "rutasmexicoHoteles.php?Itin=" + strID;
	    }
	    
	    function abrirExcursiones(strID) {
	    	location.href = "rutasmexicoExcursiones.php?Itin=" + strID;
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
			<h2>Itinerarios de <?php echo $NombRuta?></h2>
		</div>
		
		<form class="form-horizontal" id="formulario" method="post" onSubmit="return false;">
			<input type="hidden" id="hdnOperacion" value="0" />
			<div class="form-group">
				<div class="col-md-4">
					<input type="button" class="btn btn-primary" onclick="history.go(-1);" value="Volver" />
					<input type="button" class="btn btn-primary" onclick="editar(0);" value="Nuevo" />
					<input type="button" class="btn btn-warning" onclick="location.href = 'rutasmexicoItinerariosDias.php?Ruta=<?php echo $NumeRuta?>';" value="Cargar nuevos d&iacute;as" />
				</div>
			</div>
			
			<div class="form-group">
				<label for="numero" class="control-label col-md-2">N&uacute;mero:</label>
				<div class="col-md-2">
					<input type="text" class="form-control" id="numero" disabled />
				</div>
			</div>
			<div class="form-group">
				<label for="codigo" class="control-label col-md-2">C&oacute;digo:</label>
				<div class="col-md-2">
					<input type="text" class="form-control" id="codigo" />
				</div>
			</div>
			<div class="form-group">
				<label for="precio" class="control-label col-md-2">Precio:</label>
				<div class="col-md-2">
					<input type="text" class="form-control" id="precio" />
				</div>
			</div>
			<div class="form-group">
				<label for="ciudadFin" class="control-label col-md-2">Ciudad de fin de itinerario:</label>
				<div class="col-md-2">
					<input type="text" class="form-control" id="ciudadFin" />
				</div>
			</div>
<!-- HOTELES -->
			<hr>
			<div class="col-md-11 col-md-offset-1">
				<h4>Hoteles (seleccione los hoteles habilitados para este itinerario)</h4>
			</div>
			<div class="clearer"></div>
			<?php
				$tabla = cargarTabla("SELECT NumeHotel, NombHotel, Ciudad FROM hoteles ORDER BY NombHotel");
				
				while ($fila = $tabla->fetch_array()) {
			?>
			<div class="form-group">
				<div class="col-md-4">
					<label class="labelCheck">
						<input id="chkNumeHotel<?php echo $fila["NumeHotel"]?>" type="checkbox" class="chkHotel"> <?php echo $fila["NombHotel"]. ' - '. $fila["Ciudad"]?> 
					</label>
				</div>
				<label for="estancia<?php echo $fila["NumeHotel"]?>" class="control-label col-md-3">Estancia:</label>
				<div class="col-md-2">
					<input type="number" class="form-control" id="estancia<?php echo $fila["NumeHotel"]?>" min="0" disabled/>
				</div>
			</div>
			<?php 
				}
				
				if (isset($tabla)) 
					$tabla->free();
			?>
			
<!-- EXCURSIONES -->
			<hr>
			<div class="col-md-11 col-md-offset-1">
				<h4>Excursiones (seleccione las excursiones habilitadas para este itinerario)</h4>
			</div>
			<div class="clearer"></div>
			<?php
				$tabla = cargarTabla("SELECT NumeExcu, Titulo, Ciudad FROM excursiones ORDER BY Titulo");
				
				while ($fila = $tabla->fetch_array()) {
			?>
			<div class="form-group">
				<div class="col-md-4">
					<label class="labelCheck">
						<input id="chkNumeExcu<?php echo $fila["NumeExcu"]?>" type="checkbox" class="chkExcu"> <span id="Titulo<?php echo $fila["NumeExcu"]?>"><?php echo $fila["Titulo"]?></span> - <?php echo $fila["Ciudad"]?> 
					</label>
				</div>
			</div>
			<?php 
				}
				
				if (isset($tabla)) 
					$tabla->free();
			?>
			
<!-- DIAS -->
			<hr>
			<div class="col-md-11 col-md-offset-1">
				<h4>D&iacute;as (seleccione los d&iacute;as habilitados para este itinerario)</h4>
			</div>
			<div class="clearer"></div>
			
			<?php
				$tabla = cargarTabla("SELECT NumeDia, Ciudades FROM rutasmexicodias WHERE NumeRuta = {$NumeRuta} ORDER BY NumeDia");
				if (mysqli_num_rows($tabla) > 0) {
					$I = 1;
					while ($fila = $tabla->fetch_array()) {
					
			?>
			<div class="form-group">
				<div class="col-md-5">
					<label class="labelCheck">
						<input id="chkNumeDia<?php echo $fila["NumeDia"]?>" type="checkbox" class="chkDias"> <?php echo $fila["Ciudades"]?></span>
					</label>
				</div>
				<label for="diasemana<?php echo $fila["NumeDia"]?>" class="control-label col-md-2">D&iacute;a de la semana:</label>
				<div class="col-md-2">
					<select class="form-control" id="diasemana<?php echo $fila["NumeDia"]?>" disabled>
						<option value="1">Lunes</option>
						<option value="2">Martes</option>
						<option value="3">Mi&eacute;rcoles</option>
						<option value="4">Jueves</option>
						<option value="5">Viernes</option>
						<option value="6">S&aacute;bado</option>
						<option value="7">Domingo</option>
					</select>
				</div>
				<label for="numeorde<?php echo $fila["NumeDia"]?>" class="control-label col-md-1">Orden:</label>
				<div class="col-md-2">
					<input type="number" class="form-control" id="numeorde<?php echo $fila["NumeDia"]?>" min="1" value="<?php echo $I?>" disabled />
				</div>
			</div>
			
			<?php 
						$I++;
					}
				
					$tabla->free();
				}
				else { 
					echo "No hay d&iacute;as registrados todavía.";
				}
			?>
			
			<div class="form-group" style="margin-top: 20px;">
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