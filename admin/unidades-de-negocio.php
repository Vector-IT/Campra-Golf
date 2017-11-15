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
	elseif ($_SESSION["chkExperiencias"] != "1") {
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

			$("#fotoBanner").change( previewBanner );
			$("#fotoPortada").change( previewPortada );
			$("#divMsj").hide();
			
			listar();
		});
		
		function previewBanner(event) {
            var divPreview = $("#divPreviewBanner");
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

		function previewPortada(event) {
            var divPreview = $("#divPreviewPortada");
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

			xmlhttp.open("POST","php/experienciasProcesar.php",true);
			xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			xmlhttp.send("operacion=10");
		}

		function aceptar(){
		    $('#actualizando').css('display', 'block');
		    
            var frmData = new FormData();

            if ($("#hdnOperacion").val() < 2) {
                if (!validar())
                    return;
			}
            frmData.append("operacion", $("#hdnOperacion").val());
            frmData.append("NumeExpe", $("#numero").val());
            frmData.append("NombExpe", $("#nombre").val());
            frmData.append("Dominio", $("#dominio").val());
            frmData.append("DescExpe", $("#descripcion").val());
            frmData.append("FotoBanner", $("#fotoBanner").get(0).files[0]);
            frmData.append("FotoPortada", $("#fotoPortada").get(0).files[0]);
            frmData.append("NumeEsta", $("#estado").val());
            frmData.append("NumeOrde", $("#numeorde").val());

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

			xmlhttp.open("POST","php/experienciasProcesar.php",true);
			xmlhttp.send(frmData);
		}

	    function editar(strID){
		    if (strID > 0) {
		    	$('html, body').animate({
		            scrollTop: $("#formulario").offset().top
		        }, 1000);
		        
		        $('#hdnOperacion').val("1");

	            $('#numero').val(strID);
	            $('#numeorde').val($('#NumeOrde' + strID).html());
	        	$('#nombre').val($('#NombExpe' + strID).html());
	        	$('#dominio').val($('#Dominio' + strID).val());
	        	$('#descripcion').val($('#DescExpe' + strID).val());
	        	$("#divPreviewBanner").html("<img class='thumbnail' src='" + $("#FotoBanner" + strID).attr("src") + "' />");
	        	$('#fotoBanner').val("");
	        	$("#divPreviewPortada").html("<img class='thumbnail' src='" + $("#FotoPortada" + strID).val() + "' />");
	        	$('#fotoPortada').val("");
	        	$('#estado').val($('#NumeEsta' + strID).val());
		    }
		    else {
		    	$("#divPreviewBanner").html("La resolución recomendada es 370x370 con fondo blanco o transparente.");				
		    	$("#divPreviewPortada").html("La resolución recomendada es 1234x462 con fondo blanco o transparente.");
		    	
		        $('#hdnOperacion').val("0");

		        $('#numero').val("");
		        $('#numeorde').val("");
	        	$('#nombre').val("");
	        	$('#dominio').val("");
	        	$('#descripcion').val("");
	            $('#fotoBanner').val("");
	            $('#fotoPortada').val("");
	            $('#estado').val(1);
			}
		}

		function borrar(strID){
	        if (confirm("Desea borrar el registro " + $("#NombExpe" + strID).html())){
                $('#hdnOperacion').val("2");
                $('#numero').val(strID);
                aceptar();
	        }
	    }

	    function validar() {
		    var mensaje = "";

	        if ($('#nombre').val().trim().length == 0)
		        mensaje+= "El Nombre de la experiencia no puede estar vac&iacute;o.";

	        if ($('#dominio').val().trim().length == 0) {
		        if (mensaje != "")
			        mensaje+= "<br>";
        		mensaje+= "El campo dominio de la experiencia no puede estar vac&iacute;o.";
        	}
        	
	        if ($('#hdnOperacion').val() == "0") {
		        //Nuevo
	        	if ($('#fotoBanner').val() == "") {
			        if (mensaje != "")
				        mensaje+= "<br>";
	        		mensaje+= "Debe seleccionar una imagen de banner para la experiencia.";
	        	}

	        	if ($('#fotoPortada').val() == "") {
			        if (mensaje != "")
				        mensaje+= "<br>";
	        		mensaje+= "Debe seleccionar una imagen de portada para la experiencia.";
	        	}
	        }
	        else {
		        //Editando
	        	if (($('#fotoBanner').val() == "") && ($("#divPreviewBanner").html() == "")) {
			        if (mensaje != "")
				        mensaje+= "<br>";
	        		mensaje+= "Debe seleccionar una imagen de banner para la experiencia.";
	        	}
	        	
	        	if (($('#fotoPortada').val() == "") && ($("#divPreviewPortada").html() == "")) {
			        if (mensaje != "")
				        mensaje+= "<br>";
	        		mensaje+= "Debe seleccionar una imagen de portada para la experiencia.";
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

		function subir(strID) {
			var NumeOrde = $("#NumeOrde" + strID).html();
			
			if (NumeOrde > 1) {
				NumeOrde--;
				
		        $("#hdnOperacion").val("3");

	            $('#numero').val(strID);
		    	$("#numeorde").val(NumeOrde);
				
		    	aceptar();
			}
			else {
				$("#txtHint").html("La experiencia ya se encuentra en su posici&oacute;n m&aacute;s alta.");
				$("#divMsj").removeClass("alert-success");
				$("#divMsj").addClass("alert-danger");
				$("#divMsj").show();
			}
		}

		function bajar(strID) {
			var NumeOrde = $("#NumeOrde" + strID).html();
			var CantImag = $(".thumbs").size();
			
			if (NumeOrde < CantImag) {
				NumeOrde++;
				
		        $("#hdnOperacion").val("4");

	            $('#numero').val(strID);
		    	$("#numeorde").val(NumeOrde);
				
		    	aceptar();
			}
			else {
				$("#txtHint").html("La experiencia ya se encuentra en su posici&oacute;n m&aacute;s baja.");
				$("#divMsj").removeClass("alert-success");
				$("#divMsj").addClass("alert-danger");
				$("#divMsj").show();
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
			<h2>Unidades de Negocio</h2>
		</div>
		
		<form class="form-horizontal" id="formulario" method="post" onSubmit="return false;">
			<input type="hidden" id="hdnOperacion" value="0" />
			<input type="hidden" id="numero" />
			<div class="form-group">
				<div class="col-md-4">
					<input type="button" class="btn btn-primary" onclick="editar(0);" value="Nuevo" />
				</div>
			</div>
			
			<div class="form-group">
				<label for="numeorde" class="control-label col-md-2">N&uacute;mero:</label>
				<div class="col-md-2">
					<input type="text" class="form-control" id="numeorde" name="numeorde" disabled />
				</div>
			</div>
			<div class="form-group">
				<label for="nombre" class="control-label col-md-2">Nombre:</label>
				<div class="col-md-4">
					<input type="text" class="form-control" id="nombre" name="nombre" />
				</div>
			</div>
			<div class="form-group">
				<label for="dominio" class="control-label col-md-2">Dominio:</label>
				<div class="col-md-4">
					<input type="text" class="form-control" id="dominio" name="dominio" />
				</div>
			</div>
			<div class="form-group">
				<label for="descripcion" class="control-label col-md-2">Descripci&oacute;n:</label>
				<div class="col-md-4">
					<textarea class="form-control" id="descripcion" name="descripcion"></textarea>
				</div>
			</div>
			<div class="form-group">
				<label for="fotoBanner" class="control-label col-md-2">Imagen de banner:</label>
				<div class="col-md-4">
					<div id="divPreviewBanner">
						La resolución recomendada es 370x370 con fondo blanco o transparente.
					</div>
					<input type="file" class="form-control" id="fotoBanner" name="fotoBanner" />
				</div>
			</div>
			<div class="form-group">
				<label for="fotoPortada" class="control-label col-md-2">Imagen de portada:</label>
				<div class="col-md-4">
					<div id="divPreviewPortada">
						La resolución recomendada es 1234x462 con fondo blanco o transparente.
					</div>
					<input type="file" class="form-control" id="fotoPortada" name="fotoPortada" />
				</div>
			</div>
			<div class="form-group">
				<label for="estado" class="control-label col-md-2">Estado:</label>
				<div class="col-md-4">
					<select class="form-control" id="estado" name="estado">
						<option value="1">Activo</option>
						<option value="0">Inactivo</option>
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
		
		<div class="table-responsive" id="divDatos">
			
		</div>	
	</div>
</body>
</html>