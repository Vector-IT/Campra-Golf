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
	elseif ($_SESSION["chkAgRegistros"] != "1") {
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

			xmlhttp.open("POST","php/agenciasProcesar.php",true);
			xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			xmlhttp.send("operacion=20");
		}

		function aceptar(){
		    $('#actualizando').css('display', 'block');
		    
            var frmData = new FormData();

            frmData.append("operacion", $("#hdnOperacion").val());
            frmData.append("NumeAgen", $("#numero").val());
            frmData.append("NombComercial", $('#nombreAgencia').val());
            frmData.append("Provincia", $('#provincia').val());
            frmData.append("Direccion", $('#direccion').val());
            frmData.append("Telefono", $('#telefonoAgencia').val());
            frmData.append("PaginaWeb", $('#paginaWeb').val());
            frmData.append("RazonSocial", $('#razonSocial').val());
            frmData.append("IATA", $('#iata').val());
            frmData.append("SECTUR", $('#sectur').val());
            frmData.append("RFC", $('#rfc').val());
            frmData.append("NombAdmin", $('#nombreAdmin').val());
            frmData.append("TeleAdmin", $('#telefonoAdmin').val());
            frmData.append("MailAdmin", $('#emailAdmin').val());
            frmData.append("NombVent", $('#nombreVentas').val());
            frmData.append("TeleVent", $('#telefonoVentas').val());
            frmData.append("MailVent", $('#emailVentas').val());        

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
		        
		        $('#hdnOperacion').val("4");

		        $('#numero').val(strID);
	        	$('#nombreAgencia').val($('#NombComercial' + strID).html());
	        	$('#provincia').val($('#Provincia' + strID).val());
	        	$('#direccion').val($('#Direccion' + strID).html());
	        	$('#telefonoAgencia').val($('#Telefono' + strID).html());
	        	$('#paginaWeb').val($('#PaginaWeb' + strID).val());
	        	$('#razonSocial').val($('#RazonSocial' + strID).val());
	        	$('#iata').val($('#IATA' + strID).val());
	        	$('#numeroSectur').val($('#SECTUR' + strID).val());
	        	$('#rfc').val($('#RFC' + strID).val());
	        	$('#nombreAdmin').val($('#NombAdmin' + strID).val());
	        	$('#telefonoAdmin').val($('#TeleAdmin' + strID).val());
	        	$('#emailAdmin').val($('#MailAdmin' + strID).val());
	        	$('#nombreVentas').val($('#NombVent' + strID).val());
	        	$('#telefonoVentas').val($('#TeleVent' + strID).val());
	        	$('#emailVentas').val($('#MailVent' + strID).val());
		    }
		    else {
		    	$("#divPreview").html("La resolución recomendada es 255x105 con fondo blanco o transparente.");				
		    	
		        $('#hdnOperacion').val("99");

		        $('#numero').val(strID);
		        $('#nombreAgencia').val("");
		        $('#provincia').val("");
	        	$('#direccion').val("");
	        	$('#telefonoAgencia').val("");
	        	$('#paginaWeb').val("");
	        	$('#razonSocial').val("");
	        	$('#iata').val("");
	        	$('#sectur').val("");
	        	$('#rfc').val("");
	        	$('#nombreAdmin').val("");
	        	$('#telefonoAdmin').val("");
	        	$('#emailAdmin').val("");
	        	$('#nombreVentas').val("");
	        	$('#telefonoVentas').val("");
	        	$('#emailVentas').val("");
			}
		}

		function borrar(strID){
	        if (confirm("Desea borrar el registro " + $("#NombComercial" + strID).html())){
                $('#hdnOperacion').val("12");
                $('#numero').val(strID);
                aceptar();
	        }
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
			<h2>Solicitudes de Registro de Agencia</h2>
		</div>
		
		<form class="form-horizontal" id="formulario" method="post" onSubmit="return false;">
			<input type="hidden" id="hdnOperacion" value="0" />
			<div class="form-group">
				<div class="col-md-12">
					<strong style="color: #C99F37!important;">Datos de la Agencia</strong>
				</div>
				<div class="col-md-6">
					<label for="numero">N&uacute;mero</label>  
					<input type="text" class="form-control form-custom" id="numero" disabled />
					<label for="nombreAgencia">Nombre Comercial de la Agencia</label>
					<input type="text" class="form-control form-custom" id="nombreAgencia" />
					<label for="provincia">Estado</label>
					<select class="form-control form-custom" id="provincia">
					<?php
						echo cargarCombo("SELECT NumeProv, NombProv FROM provincias ORDER BY NombProv", "NumeProv", "NombProv");
					?>
					</select>  
					<label for="direccion">Direcci&oacute;n Completa</label>
					<input type="text" class="form-control form-custom" id="direccion" />
					<label for="telefonoAgencia">Tel&eacute;fono</label>
					<input type="text" class="form-control form-custom" id="telefonoAgencia" />
				</div>
				<div class="col-md-6">
					<label for="paginaWeb">P&aacute;gina Web</label>
					<input type="text" class="form-control form-custom" id="paginaWeb" />
					<label for="razonSocial">Raz&oacute;n Social</label>
					<input type="text" class="form-control form-custom" id="razonSocial" />
					<label for="iata">IATA</label>
					<input type="text" class="form-control form-custom" id="iata" />
					<label for="numeroSectur">N&uacute;mero de alta en SECTUR</label>
					<input type="text" class="form-control form-custom" id="numeroSectur" />
					<label for="rfc">RFC</label>
					<input type="text" class="form-control form-custom" id="rfc" />
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-md-6">
					<strong style="color: #C99F37!important;">Datos del Contacto Administrativo</strong>
				</div>
				<div class="col-md-6">
					<strong style="color: #C99F37!important;">Datos del Contacto de Ventas</strong>
				</div>
				<div class="col-md-6">
					<label for="nombreAdmin">Nombre Completo</label>
					<input type="text" class="form-control form-custom" id="nombreAdmin" />
					<label for="telefonoAdmin">Tel&eacute;fono</label>
					<input type="text" class="form-control form-custom" id="telefonoAdmin" />
					<label for="emailAdmin">E-mail</label>   
					<input type="email" class="form-control form-custom" id="emailAdmin" />
				</div>
				<div class="col-md-6">
					<label for="nombreVentas">Nombre Completo</label>
					<input type="text" class="form-control form-custom" id="nombreVentas" />
					<label for="telefonoVentas">Tel&eacute;fono</label>
					<input type="text" class="form-control form-custom" id="telefonoVentas" />
					<label for="emailVentas">E-mail</label>   
					<input type="email" class="form-control form-custom" id="emailVentas" />
				</div>
			</div>

			<div class="form-group">
				<div class="col-md-4">
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
			<button class="btn btn-default" onclick="location.href = 'php/agenciasExcel.php';"><i class="fa fa-file-excel-o"></i> Exportar a Excel</button>
		</p>
		
		<div class="table-responsive" id="divDatos">
			
		</div>
	</div>
</body>
</html>