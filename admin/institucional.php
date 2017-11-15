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
	elseif ($_SESSION["chkAgencias"] != "1") {
		header("Location:index.php");
		die();
	}
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

			$("#imagen").change( previewImg );
			$("#flyer").change( previewFlyer );
			$("#divMsj").hide();
			
			listar();
		});

		function preview(event, divPreview) {
        	divPreview.html("");

            var files = event.target.files; //FileList object
            
            for(var i = 0; i< files.length; i++)
            {
                var file = files[i];
                
                //Solo imagenes
                if(!file.type.match('image'))
                  continue;
                
                var picReader = new FileReader();
                
                picReader.addEventListener("load",function(event){
                    
                    var picFile = event.target;
                    
                    divPreview.append("<img id='img" + divPreview.children().length + "' class='thumbnail' src='" + picFile.result + "' />");
                    
                });
                
                 //Leer la imagen
                picReader.readAsDataURL(file);
            }                               
		}
		
		function previewImg(event) {
            var divPreview = $("#divPreview");

            preview(event, divPreview);
		}

		function previewFlyer(event) {
            var divPreview = $("#divPreviewFlyer");

            preview(event, divPreview);
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

			xmlhttp.open("POST","php/agenciasProcesar.php",true);
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
            frmData.append("NumeAgen", $("#numero").val());
            frmData.append("NombAgen", $("#nombre").val());
            frmData.append("Dominio", $("#dominio").val());
            frmData.append("Imagen", $("#imagen").get(0).files[0]);
            frmData.append("ImagenFlyer", $("#flyer").get(0).files[0]);
            frmData.append("Direccion", $("#direccion").val());
            frmData.append("Telefono", $("#telefono").val());
            frmData.append("Email", $("#email").val());
            frmData.append("Facebook", $("#facebook").val());
            frmData.append("Twitter", $("#twitter").val());
            frmData.append("Instagram", $("#instagram").val());
            frmData.append("Ocultar", $("#ocultar").prop("checked") ? 1 : 0);
            frmData.append("Posicion", $("#posicion").val());
            frmData.append("NumeAgenRegi", $('#numeagenregi').val());

            frmData.append("NombUsua", $("#usuario").val());
            frmData.append("NombPass", $("#password").val());

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

			xmlhttp.open("POST","php/agenciasProcesar.php",true);
			xmlhttp.send(frmData);
		}

	    function editar(strID){
		    if (strID > 0) {
		    	$('html, body').animate({
		            scrollTop: $("#formulario").offset().top
		        }, 1000);
		        
		        $('#hdnOperacion').val("1");

	            $('#numero').val(strID);
	        	$('#nombre').val($('#NombAgen' + strID).html());
	        	$('#dominio').val($('#Dominio' + strID).html());
	        	$("#divPreview").html("<img class='thumbnail' src='" + $("#Imagen" + strID).attr("src") + "' />");
	        	$('#imagen').val("");
	        	$("#divPreviewFlyer").html("<img class='thumbnail' src='" + $("#ImagenFlyer" + strID).val() + "' />");
	        	$('#flyer').val("");
	        	$('#direccion').val($('#Direccion' + strID).val());
	        	$('#telefono').val($('#Telefono' + strID).val());
	        	$('#email').val($('#Email' + strID).val());
	        	$('#facebook').val($('#Facebook' + strID).val());
	        	$('#twitter').val($('#Twitter' + strID).val());
	        	$('#instagram').val($('#Instagram' + strID).val());
	        	$("#ocultar").prop("checked", Boolean(parseInt($("#Ocultar" + strID).val())));
	        	$('#posicion').val($('#Posicion' + strID).val());
	        	$('#numeagenregi').val($('#NumeAgenRegi' + strID).val());

	        	$("#adicionales").hide();
		    }
		    else {
		    	$("#divPreview").html("La resolución recomendada es 255x105 con fondo blanco o transparente.");				
		    	$("#divPreviewFlyer").html("La resolución recomendada es 255x105 con fondo blanco.");				
		    	
		        $('#hdnOperacion').val("0");

		        $('#numero').val("");
	        	$('#nombre').val("");
	        	$('#dominio').val("");
	            $('#imagen').val("");
	            $('#flyer').val("");
	        	$('#direccion').val("");
	        	$('#telefono').val("");
	        	$('#email').val("");
	        	$('#facebook').val("");
	        	$('#twitter').val("");
	        	$('#instagram').val("");
	        	$("#ocultar").prop("checked", false);
	        	$('#posicion').val("");
	        	$('#numeagenregi').val("-1");

	        	$("#usuario").val("");
	        	$("#password").val("");

	        	$("#adicionales").show();
			}
		}

		function borrar(strID){
	        if (confirm("Desea borrar el registro " + $("#NombAgen" + strID).html())){
                $('#hdnOperacion').val("2");
                $('#numero').val(strID);
                aceptar();
	        }
	    }

	    function validar() {
		    var mensaje = "";

	        if ($('#nombre').val().trim().length == 0)
		        mensaje+= "El Nombre de la agencia no puede estar vacío.";

	        if ($('#dominio').val().trim().length == 0) {
		        if (mensaje != "")
			        mensaje+= "<br>";
	        	mensaje+= "El Dominio de la agencia no puede estar vacío.";
	        }

	        /*
	        if ($('#hdnOperacion').val() == "0") {
		        //Nuevo
	        	if ($('#imagen').val() == "") {
			        if (mensaje != "")
				        mensaje+= "<br>";
	        		mensaje+= "Debe seleccionar una imagen de agencia.";
	        	}
	        }
	        else {
		        //Editando
	        	if (($('#imagen').val() == "") && ($("#divPreview").html() == "")) {
			        if (mensaje != "")
				        mensaje+= "<br>";
	        		mensaje+= "Debe seleccionar una imagen de agencia.";
	        	}
	        }
			*/
			
	        var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;

		    if ($('#email').val().trim().length == 0) {
	    		if (mensaje != "")
			        mensaje+= "<br>";
			        
		        mensaje+= "El E-Mail no puede estar vac&iacute;o.";
	    	}
		    else if (!re.test($('#email').val().trim())) {
		    	if (mensaje != "")
			        mensaje+= "<br>";
			        
		        mensaje+= "El E-Mail tiene que tener el formato usuario@servidor.com.";
		    }

			if ($('#hdnOperacion').val() == "0") {
		        if ($('#usuario').val().trim().length == 0) {
			        if (mensaje != "")
				        mensaje+= "<br>";
	        		mensaje+= "El Usuario no puede estar vac&iacute;o.";
	        	}	        
	
		        if ($('#password').val().trim().length < 4) {
			        if (mensaje != "")
				        mensaje+= "<br>";
	        		mensaje+= "La Contrase&ntilde;a no puede tener menos de 4 caracteres.";
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

	    function cargarDatosAg() {
			var strNombAgen = $('#nombre').val().trim();
			
			$("[id^='hdnDireccion']").each(function() {
				if ($(this).attr("data-agen") == strNombAgen) {
					$("#direccion").val($(this).val()); 
				}
			});

			$("[id^='hdnTelefono']").each(function() {
				if ($(this).attr("data-agen") == strNombAgen) {
					$("#telefono").val($(this).val()); 
				}
			});

			$("[id^='hdnMail']").each(function() {
				if ($(this).attr("data-agen") == strNombAgen) {
					$("#email").val($(this).val()); 
				}
			});

			/*
			$("[id^='hdnNumeAgenRegi']").each(function() {
				if ($(this).attr("data-agen") == strNombAgen) {
					$("#numeagenregi").val($(this).val()); 
				}
			});
			*/
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
			<h2>Institucional</h2>
		</div>
		
		<form class="form-horizontal" id="formulario" method="post" onSubmit="return false;">
			<input type="hidden" id="hdnOperacion" value="0" />
			<!-- <input type="hidden" id="numeagenregi" /> -->
			<div class="form-group">
				<div class="col-md-3">
					<input type="button" class="btn btn-primary" onclick="editar(0);" value="Nuevo" />
				</div>
			</div>
			
			<div class="form-group">
				<label for="numero" class="control-label col-md-3">N&uacute;mero:</label>
				<div class="col-md-2">
					<input type="text" class="form-control" id="numero" name="numero" disabled />
				</div>
			</div>
			<div class="form-group">
				<label for="nombre" class="control-label col-md-3">Nombre:</label>
				<div class="col-md-4">
					<input type="text" class="form-control" id="nombre" name="nombreAg" list="nombres" onchange="cargarDatosAg();" />
					<datalist id="nombres">
					<?php
						$strSQL = "SELECT NumeAgen, RazonSocial, Telefono, Direccion, MailAdmin FROM agenciasregistradas WHERE NumeEsta = 1 ORDER BY RazonSocial";
						echo cargarCombo($strSQL, "RazonSocial", "");
					?>
					</datalist>
					<?php 
						$tabla = cargarTabla($strSQL);
						$salida = "";
						while ($fila = $tabla->fetch_array()) {
							//$salida.= $crlf.'<input type="hidden" id="hdnNumeAgenRegi" value="'. $fila["NumeAgen"] .'" data-agen="'. $fila["RazonSocial"] .'" />'.$crlf;
							$salida.= $crlf.'<input type="hidden" id="hdnTelefono" value="'. $fila["Telefono"] .'" data-agen="'. $fila["RazonSocial"] .'" />'.$crlf;
							$salida.= $crlf.'<input type="hidden" id="hdnDireccion" value="'. $fila["Direccion"] .'" data-agen="'. $fila["RazonSocial"] .'" />'.$crlf;
							$salida.= $crlf.'<input type="hidden" id="hdnMail" value="'. $fila["MailAdmin"] .'" data-agen="'. $fila["RazonSocial"] .'" />'.$crlf;
						}
						echo $salida;
						
						if (isset($tabla))
							$tabla->free();
					?>
				</div>
			</div>
			<div class="form-group">
				<label for="dominio" class="control-label col-md-3">Dominio:</label>
				<div class="col-md-4">
					<input type="text" class="form-control" id="dominio" name="dominio" />
				</div>
			</div>
			<div class="form-group">
				<label for="imagen" class="control-label col-md-3">Imagen:</label>
				<div class="col-md-4">
					<div id="divPreview">
						La resolución recomendada es 255x105 con fondo blanco o transparente.
					</div>
					<input type="file" class="form-control" id="imagen" name="imagen" />
				</div>
			</div>
			
			
			<!-- DATOS ADICIONALES -->
			<div class="form-group">
				<label for="direccion" class="control-label col-md-3">Direcci&oacute;n:</label>
				<div class="col-md-4">
					<input type="text" class="form-control" id="direccion" name="direccion" />
				</div>
			</div>
			<div class="form-group">
				<label for="telefono" class="control-label col-md-3">Tel&eacute;fono:</label>
				<div class="col-md-4">
					<input type="text" class="form-control" id="telefono" name="telefono" />
				</div>
			</div>
			<div class="form-group">
				<label for="email" class="control-label col-md-3">E-Mail:</label>
				<div class="col-md-4">
					<input type="email" class="form-control" id="email" name="email" />
				</div>
			</div>
			<div class="form-group">
				<label for="facebook" class="control-label col-md-3">Facebook:</label>
				<div class="col-md-4">
					<input type="text" class="form-control" id="facebook" name="facebook" />
				</div>
			</div>
			<div class="form-group">
				<label for="twitter" class="control-label col-md-3">Twitter:</label>
				<div class="col-md-4">
					<input type="text" class="form-control" id="twitter" name="twitter" />
				</div>
			</div>
			<div class="form-group">
				<label for="instagram" class="control-label col-md-3">Instagram:</label>
				<div class="col-md-4">
					<input type="text" class="form-control" id="instagram" name="instagram" />
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-4 col-md-offset-3">
					<label class="labelCheck">
						<input id="ocultar" type="checkbox"> Ocultar agencia?
					</label>
				</div>
			</div>
			<div class="form-group">
				<label for="posicion" class="control-label col-md-3">Posici&oacute;n:</label>
				<div class="col-md-4">
					<input type="text" class="form-control" id="posicion" name="posicion" />
				</div>
				<div class="col-md-5">
					El formato a tipear es el siguente: latitud, longitud. Ejemplo: -31.391158, -64.193115<br>
					Puede buscar una posición nueva siguiendo este <a href="http://www.latlong.net/" target="_blank">link</a> y copiando la informaci&oacute;n.
				</div>
			</div>
			<div id="adicionales">
				<h3>Datos de Usuario</h3>
				<div class="form-group">
					<label for="usuario" class="control-label col-md-3">Usuario:</label>
					<div class="col-md-4">
						<input type="text" class="form-control" id="usuario" name="usuario" />
					</div>
				</div>
				<div class="form-group">
					<label for="password" class="control-label col-md-3">Contrase&ntilde;a:</label>
					<div class="col-md-4">
						<input type="password" class="form-control" id="password" name="password" />
					</div>
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-md-offset-3 col-md-4">
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