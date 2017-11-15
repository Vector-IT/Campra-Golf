<?php 
	session_start();
	
	if ((!isset($_SESSION['is_logged_in'])) ||
			($_SESSION['TipoUsua'] != "1")) {
		header("Location:login.php?returnUrl=" . $_SERVER[REQUEST_URI]);
		die();
	}

	include 'php/conexion.php';
	
	if (isset($_GET["tour"])) 
		$numeTour = $_GET["tour"];
	else
		$numeTour = "1";
	$tour = cargarTabla("SELECT NombTour FROM tours WHERE NumeTour = {$numeTour}");
	
	$fila = $tour->fetch_array();
	$tour->free();
	
	$nombTour = $fila["NombTour"];
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

		function agregarDia(efecto) {
			efecto = efecto || "si";
			
			var CantDia = $("#dias").children().length;
			var idDia = $("div[id^='dias']").children()[CantDia-1].id;
			
			var Dia = $("#" + idDia).clone();
			var re;
			
			var CantDiaOld = CantDia; 
			CantDia++;
			
			Dia.attr('id', 'divDia' + CantDia);

			Dia.html(Dia.html().replace("quitarDia('" + idDia, "quitarDia('divDia" + CantDia));
			Dia.html(Dia.html().replace("subirDia('" + idDia, "subirDia('divDia" + CantDia));
			Dia.html(Dia.html().replace("bajarDia('" + idDia, "bajarDia('divDia" + CantDia));

			re = new RegExp('Dia' + CantDiaOld, 'g');
			Dia.html(Dia.html().replace(re, 'Dia' + CantDia));

			Dia.find("input[class='form-control']").val("");
			Dia.find("textarea[class='form-control']").val("");
			Dia.find("input[id='numeroDia']")[0].value = CantDia;
			Dia.css("display", "none");

			Dia.appendTo($('#dias'));
			if (efecto == "si")
				Dia.fadeIn("slow");
			else
				Dia.css("display", "block");
		}

		function quitarDia(strID) {
			var CantDia = $("#dias").children().length;
			if (CantDia > 1) {
				var Dia = $('#' + strID).fadeOut("slow", function() {
					$(this).remove();

					var CantDia = $("#dias").children().length;
					for (var I = 0; I < CantDia; I++) {
						$("input[id='numeroDia']")[I].value = I + 1;
					}
				});

			}
			else {
    			var Dia = $('#' + strID);
    			
    			Dia.find("input[id='numeroDia']")[0].value = "1";
    			Dia.find("input[id='nombreDia']")[0].value = "";
    			Dia.find("textarea[id='descripcionDia']")[0].value = "";
			}				
		}

		function subirDia(strID) {
			var CantDia = $("#dias").children().length;
			for (var I = 0; I < CantDia; I++) {
				if ($("#dias").children()[I].id == strID) {
					if (I == 0) {
						//Si esta primero no hago nada
						return;
					}
					else {
						//Primero lo copio
						var Dia = $("#" + strID).clone();
						Dia.css("display", "none");

						//Lo elimino
						$('#' + strID).fadeOut("slow", function() {
							$(this).remove();

							//Reseteo los numeros de dias
							for (var I = 0; I < CantDia; I++) {
								$("input[id='numeroDia']")[I].value = I + 1;
							}
						});	

						var J = I - 1;
						//Lo agrego en el indice que corresponde
						$($("#dias").children()[J]).before(Dia[0].outerHTML);

						$($("#dias").children()[J]).find("input[id='nombreDia']")[0].value = Dia.find("input[id='nombreDia']")[0].value;
						$($("#dias").children()[J]).find("textarea[id='descripcionDia']")[0].value = Dia.find("textarea[id='descripcionDia']")[0].value;

						Dia = $($("#dias").children()[J]);
						Dia.fadeIn("slow");
						break;
					}
				}
			}
		}

		function bajarDia(strID) {
			var CantDia = $("#dias").children().length;
			for (var I = 0; I < CantDia; I++) {
				if ($("#dias").children()[I].id == strID) {
					if (I == (CantDia - 1)) {
						//Si esta ultimo no hago nada
						return;
					}
					else {
						//Primero lo copio
						var Dia = $("#" + strID).clone();
						Dia.css("display", "none");

						//Lo elimino
						$('#' + strID).fadeOut("slow", function() {
							$(this).remove();

							//Reseteo los numeros de dias
							for (var I = 0; I < CantDia; I++) {
								$("input[id='numeroDia']")[I].value = I + 1;
							}
						});	

						var J = I + 1;
						//Lo agrego en el indice que corresponde
						$($("#dias").children()[J]).after(Dia[0].outerHTML);

						$($("#dias").children()[J+1]).find("input[id='nombreDia']")[0].value = Dia.find("input[id='nombreDia']")[0].value;
						$($("#dias").children()[J+1]).find("textarea[id='descripcionDia']")[0].value = Dia.find("textarea[id='descripcionDia']")[0].value;

						Dia = $($("#dias").children()[J+1]);
						Dia.fadeIn("slow");

						break;
					}
				}
			}
		}
		
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

			xmlhttp.open("POST","php/itinerarioProcesar.php",true);
			xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			xmlhttp.send("operacion=10&NumeTour=<?php echo $numeTour;?>");
		}

		function aceptar(){
		    $('#actualizando').css('display', 'block');
		    
            var frmData = new FormData();

            if ($("#hdnOperacion").val() != "2") {
                if (!validar())
                    return;
			}
            frmData.append("operacion", $("#hdnOperacion").val());
            frmData.append("NumeItin", $("#numero").val());
            frmData.append("NumeTour", "<?php echo $numeTour;?>");
            frmData.append("NombItin", $("#nombre").val());
            frmData.append("CantDias", $("#dias").children().length);

            var nombDia = "";
            var descDia = "";
            for (var I = 0; I < $("#dias").children().length; I++) {
            	nombDia+= "@#@" + $("input[id='nombreDia']")[I].value;
            	descDia+= "@#@" + $("textarea[id='descripcionDia']")[I].value;
            }
            nombDia = nombDia.substring(3);
            descDia = descDia.substring(3);
            frmData.append("NombDia", nombDia);
            frmData.append("DescDia", descDia);
            
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

			xmlhttp.open("POST","php/itinerarioProcesar.php",true);
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
	        	$('#nombre').val($('#NombItin' + strID).html());

	        	var cantDias = $('#CantDias' + strID).html();
	        	var nombDias = $('#NombDias' + strID).val().split("@#@");
	        	var descDias = $('#DescDias' + strID).val().split("@#@");

	        	for (var I = 0; I < cantDias; I++) {
		        	if (I > 0)
			        	agregarDia("no");
		        	
	        		$("input[id='nombreDia']")[I].value = nombDias[I];
	            	$("textarea[id='descripcionDia']")[I].value = descDias[I];
	        	}
		    }
		    else {
		        $('#hdnOperacion').val("0");

		        $("#numero").val("");
		        $("#nombre").val("");

		        var CantDia = $("#dias").children().length;

		        for (var I = (CantDia - 1); I > 0; I--) {
		        	$($("#dias").children()[I]).remove();
		        }

		        $("input[id='numeroDia']")[0].value = "1";
		        $("input[id='nombreDia']")[0].value = "";
		        $("textarea[id='descripcionDia']")[0].value = "";
			}
		}

		function borrar(strID){
	        if (confirm("Desea borrar el registro " + $("#NombItin" + strID).html())){
                $('#hdnOperacion').val("2");
                $('#numero').val(strID);
                aceptar();
	        }
	    }

	    function validar() {
		    var mensaje = "";

	        if ($('#nombre').val().trim().length == 0)
		        mensaje+= "El Nombre del itinerario no puede estar vac&iacute;o.";

	        for (var I = 0; I < $("#dias").children().length; I++) {
            	if ($("textarea[id='descripcionDia']")[I].value.trim().length == 0) {
            		if (mensaje != "")
    			        mensaje+= "<br>";
    			        
            		mensaje+= "El campo descripci&oacute;n del d&iacute;a " + (I+1)+ " no puede estar vac&iacute;o."; 
            	}
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
			<h2>Itinerario del tour <?php echo $nombTour;?></h2>
		</div>
		<form class="form-horizontal" id="formulario" method="post" onSubmit="return false;">
			<input type="hidden" id="hdnOperacion" value="0" />
			<div class="form-group">
				<div class="col-md-4">
					<input type="button" class="btn btn-primary" onclick="history.go(-1);" value="Volver" />
					<input type="button" class="btn btn-primary" onclick="editar(0);" value="Nuevo" />
				</div>
			</div>
			
			<div class="form-group">
				<label for="numero" class="control-label col-md-2">N&uacute;mero:</label>
				<div class="col-md-2">
					<input type="text" class="form-control" id="numero" name="numero" disabled />
				</div>
			</div>
			<div class="form-group">
				<label for="nombre" class="control-label col-md-2">Nombre:</label>
				<div class="col-md-4">
					<input type="text" class="form-control" id="nombre" name="nombre" />
				</div>
			</div>
			<hr />
			<h4>Días</h4>
			<div id="dias">
				<div class="form-group" id="divDia1">
					<label for="numeroDia1" class="control-label col-md-1">N&uacute;mero:</label>
					<div class="col-md-1">
						<input type="text" class="form-control" id="numeroDia" name="numeroDia1" value="1" disabled/>
					</div>
					<label for="nombreDia1" class="control-label col-md-1">Nombre:</label>
					<div class="col-md-2">
						<input type="text" class="form-control" id="nombreDia" name="nombreDia1"/>
					</div>
					<label for="descripcionDia1" class="control-label col-md-1">Descripci&oacute;n:</label>
					<div class="col-md-4">
						<textarea class="form-control" id="descripcionDia" name="descripcionDia1"></textarea>
					</div>
					<div class="col-md-2">
						<button type="button" class="btn btn-default" onclick="subirDia('divDia1');"><span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span></button>
						<button type="button" class="btn btn-default" onclick="bajarDia('divDia1');"><span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span></button>
						<button class="btn btn-warning" onclick="quitarDia('divDia1');">Quitar</button>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-4">
					<button class="btn btn-success" onclick="agregarDia();">Agregar</button>
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