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
	elseif ($_SESSION["chkUsuarios"] != "1") {
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
					$("#divDatos").html(xmlhttp.responseText);
				}
			};

			xmlhttp.open("POST","php/usuariosProcesar.php",true);
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
            frmData.append("NumeUsua", $("#numero").val());
            frmData.append("NombComp", $("#nombre").val());
            frmData.append("NombMail", $("#mail").val());
            frmData.append("NombUsua", $("#usuario").val());
            frmData.append("NombPass", $("#password").val());
            frmData.append("TipoUsua", $("#tipo").val());
            frmData.append("NumeAgen", $("#agencia").val());
            frmData.append("NumeEsta", $("#estado").val());

            frmData.append("chkBanners", $("#chkBanners").prop("checked") ? 1 : 0);
            frmData.append("chkAgencias", $("#chkAgencias").prop("checked") ? 1 : 0);
            frmData.append("chkAgRegistros", $("#chkAgRegistros").prop("checked") ? 1 : 0);
            frmData.append("chkExperiencias", $("#chkExperiencias").prop("checked") ? 1 : 0);
            frmData.append("chkTours", $("#chkTours").prop("checked") ? 1 : 0);
            frmData.append("chkBlog", $("#chkBlog").prop("checked") ? 1 : 0);
            frmData.append("chkFlyers", $("#chkFlyers").prop("checked") ? 1 : 0);
            frmData.append("chkCotizaciones", $("#chkCotizaciones").prop("checked") ? 1 : 0);
            frmData.append("chkUsuarios", $("#chkUsuarios").prop("checked") ? 1 : 0);
            
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

			xmlhttp.open("POST","php/usuariosProcesar.php",true);
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
				$("#nombre").val($("#NombComp" + strID).html());
				$("#mail").val($("#NombMail" + strID).val());
				$("#usuario").val($("#NombUsua" + strID).html());
				$("#password").val("****");
				$("#tipo").val($("#TipoUsua" + strID).val());
				$("#agencia").val($("#NumeAgen" + strID).val());
				$('#estado').val($('#NumeEsta' + strID).val());

				$("#chkBanners").prop("checked", Boolean(parseInt($("#chkBanners" + strID).val())));
				$("#chkAgencias").prop("checked", Boolean(parseInt($("#chkAgencias" + strID).val())));
				$("#chkAgRegistros").prop("checked", Boolean(parseInt($("#chkAgRegistros" + strID).val())));
				$("#chkExperiencias").prop("checked", Boolean(parseInt($("#chkExperiencias" + strID).val())));
				$("#chkTours").prop("checked", Boolean(parseInt($("#chkTours" + strID).val())));
				$("#chkBlog").prop("checked", Boolean(parseInt($("#chkBlog" + strID).val())));
				$("#chkFlyers").prop("checked", Boolean(parseInt($("#chkFlyers" + strID).val())));
				$("#chkCotizaciones").prop("checked", Boolean(parseInt($("#chkCotizaciones" + strID).val())));
				$("#chkUsuarios").prop("checked", Boolean(parseInt($("#chkUsuarios" + strID).val())));
		    }
		    else {
		        $('#hdnOperacion').val("0");

		        $("#numero").val("");
				$("#nombre").val("");
				$("#mail").val("");
				$("#usuario").val("");
				$("#password").val("");
				$("#tipo").val(1);
				$("#agencia").val(-1);
				$("#estado").val(1);

				$("#chkBanners").prop("checked", false);
				$("#chkAgencias").prop("checked", false);
				$("#chkAgRegistros").prop("checked", false);
				$("#chkExperiencias").prop("checked", false);
				$("#chkTours").prop("checked", false);
				$("#chkBlog").prop("checked", false);
				$("#chkFlyers").prop("checked", false);
				$("#chkCotizaciones").prop("checked", false);
				$("#chkUsuarios").prop("checked", false);
			}
		}

		function borrar(strID){
	        if (confirm("Desea borrar el registro " + $("#NombComp" + strID).html())){
                $('#hdnOperacion').val("2");
                $('#numero').val(strID);
                aceptar();
	        }
	    }

	    function validar() {
		    var mensaje = "";

		    if (parseInt($('#tipo').val()) <= 3) {
		    	if ($('#nombre').val().trim().length == 0) {
		    		if (mensaje != "")
				        mensaje+= "<br>";
				        
			        mensaje+= "El Nombre no puede estar vac&iacute;o.";
		    	}
		    	
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
/*
		    var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;

		    if ($('#mail').val().trim().length == 0) {
	    		if (mensaje != "")
			        mensaje+= "<br>";
			        
		        mensaje+= "El E-Mail no puede estar vac&iacute;o.";
	    	}
		    else if (!re.test($('#mail').val().trim())) {
		    	if (mensaje != "")
			        mensaje+= "<br>";
			        
		        mensaje+= "El E-Mail tiene que tener el formato usuario@servidor.com.";
		    }
*/
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
			<h2>Administraci&oacute;n de Usuarios</h2>
		</div>
		
		<form class="form-horizontal" id="formulario" method="post" onSubmit="return false;">
			<input type="hidden" id="hdnOperacion" value="0" />
			<div class="col-md-6">
				<div class="form-group">
					<div class="col-md-4">
						<input type="button" class="btn btn-primary" onclick="editar(0);" value="Nuevo" />
					</div>
				</div>
				
				<div class="form-group">
					<label for="numero" class="control-label col-md-4">N&uacute;mero:</label>
					<div class="col-md-8">
						<input type="text" class="form-control" id="numero" name="numero" disabled />
					</div>
				</div>
				<div class="form-group">
					<label for="nombre" class="control-label col-md-4">Nombre completo:</label>
					<div class="col-md-8">
						<input type="text" class="form-control" id="nombre" name="nombre" />
					</div>
				</div>
				<div class="form-group">
					<label for="mail" class="control-label col-md-4">E-Mail:</label>
					<div class="col-md-8">
						<input type="email" class="form-control" id="mail" name="mail" />
					</div>
				</div>
				<div class="form-group">
					<label for="usuario" class="control-label col-md-4">Usuario:</label>
					<div class="col-md-8">
						<input type="text" class="form-control" id="usuario" name="usuario" />
					</div>
				</div>
				<div class="form-group">
					<label for="password" class="control-label col-md-4">Contrase&ntilde;a:</label>
					<div class="col-md-8">
						<input type="password" class="form-control" id="password" name="password" />
					</div>
				</div>
				<div class="form-group">
					<label for="tipo" class="control-label col-md-4">Tipo de usuario:</label>
					<div class="col-md-8">
						<select class="form-control" id="tipo" name="tipo">
							<option value="1">Administrador</option>
							<option value="2">Agencia de viajes</option>
							<option value="3">Usuario de p&aacute;gina</option>
							<option value="4">Usuario de newsletter</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="agencia" class="control-label col-md-4">Agencia:</label>
					<div class="col-md-8">
						<select class="form-control" id="agencia">
							<?php echo cargarCombo("SELECT NumeAgen, NombAgen FROM agencias ORDER BY NombAgen", "NumeAgen", "NombAgen", "", true);?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="estado" class="control-label col-md-4">Estado:</label>
					<div class="col-md-8">
						<select class="form-control" id="estado" name="estado">
							<option value="1">Activo</option>
							<option value="0">Inactivo</option>
						</select>
					</div>
				</div>
			</div>
			<div class="col-md-5 col-md-offset-1">
				<div class="form-group">
					<label class="control-label col-md-12" style="text-align: left !important;">Permisos de acceso al sistema</label>
				</div>
				<div class="form-group">
					<div class="col-md-10">
						<label class="labelCheck">
							<input id="chkBanners" type="checkbox"> Banner principal
						</label>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-10">
						<label class="labelCheck">
							<input id="chkAgencias" type="checkbox"> Agencias
						</label>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-10">
						<label class="labelCheck">
							<input id="chkAgRegistros" type="checkbox"> Solicitud de agencias
						</label>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-10">
						<label class="labelCheck">
							<input id="chkExperiencias" type="checkbox"> Experiencias
						</label>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-10">
						<label class="labelCheck">
							<input id="chkTours" type="checkbox"> Tours
						</label>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-10">
						<label class="labelCheck">
							<input id="chkBlog" type="checkbox"> Blog del viajero
						</label>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-10">
						<label class="labelCheck">
							<input id="chkFlyers" type="checkbox"> Flyers
						</label>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-10">
						<label class="labelCheck">
							<input id="chkCotizaciones" type="checkbox"> Cotizaciones
						</label>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-10">
						<label class="labelCheck">
							<input id="chkUsuarios" type="checkbox"> Usuarios
						</label>
					</div>
				</div>
			</div>
			<div class="clearer"></div>
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
			<button class="btn btn-default" onclick="location.href = 'php/usuariosExcel.php';"><i class="fa fa-file-excel-o"></i> Exportar a Excel</button>
		</p>
		<div class="table-responsive" id="divDatos">
			
		</div>
	</div>
</body>
</html>