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
			
			$("#imgprevia").change( previewImgPrevia );
			$("#imgportada").change( previewImgPortada );
			$("#imgmapagrande").change( previewImgMapaGrande );
			$("#imgmapachico").change( previewImgMapaChico );
			$("#imgreferencias").change( previewImgReferencias );
			$("#divMsj").hide();
			
			listar();

			$('#descripcion').css('overflow', 'hidden').autogrow({vertical: true, horizontal: false});
			$('#descripcion2').css('overflow', 'hidden').autogrow({vertical: true, horizontal: false});
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

		function previewImgPrevia(event) {
            var divPreview = $("#divPreviewImgPrevia");

            preview(event, divPreview);
		}

		function previewImgPortada(event) {
            var divPreview = $("#divPreviewImgPortada");

            preview(event, divPreview);
		}

		function previewImgMapaGrande(event) {
            var divPreview = $("#divPreviewImgMapaGrande");

            preview(event, divPreview);
		}
		
		function previewImgMapaChico(event) {
            var divPreview = $("#divPreviewImgMapaChico");

            preview(event, divPreview);
		}
		
		function previewImgReferencias(event) {
            var divPreview = $("#divPreviewImgReferencias");

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

			xmlhttp.open("POST","php/rutasmexicoProcesar.php",true);
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
            frmData.append("NumeRuta", $("#numero").val());
            frmData.append("Nombre", $("#nombre").val());
            frmData.append("Descripcion", $("#descripcion").val());
            frmData.append("Descripcion2", $("#descripcion2").val());
            frmData.append("Dominio", $("#dominio").val());
            frmData.append("ImgPrevia", $("#imgprevia").get(0).files[0]);
            frmData.append("ImgPortada", $("#imgportada").get(0).files[0]);
            frmData.append("ImgMapaGrande", $("#imgmapagrande").get(0).files[0]);
            frmData.append("ImgMapaChico", $("#imgmapachico").get(0).files[0]);
            frmData.append("ImgReferencias", $("#imgreferencias").get(0).files[0]);

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

			xmlhttp.open("POST","php/rutasmexicoProcesar.php",true);
			xmlhttp.send(frmData);
		}

	    function editar(strID){
		    if (strID > 0) {
		    	$('html, body').animate({
		            scrollTop: $("#formulario").offset().top
		        }, 1000);
		        
		        $('#hdnOperacion').val("1");

	            $('#numero').val(strID);
	            $('#nombre').val($('#Nombre' + strID).html());
	            $('#descripcion').val($('#Descripcion' + strID).val());
	            $('#descripcion2').val($('#DescripcionDos' + strID).val());
	            $('#dominio').val($('#Dominio' + strID).val());

	            $("#divPreviewImgPrevia").html("<img class='thumbnail' src='" + $("#ImgPrevia" + strID).val() + "' />");
	        	$('#imgprevia').val("");
	        	$("#divPreviewImgPortada").html("<img class='thumbnail' src='" + $("#ImgPortada" + strID).val() + "' />");
	        	$('#imgportada').val("");
	        	$("#divPreviewImgMapaGrande").html("<img class='thumbnail' src='" + $("#ImgMapaGrande" + strID).val() + "' />");
	        	$('#imgmapagrande').val("");
	        	$("#divPreviewImgMapaChico").html("<img class='thumbnail' src='" + $("#ImgMapaChico" + strID).val() + "' />");
	        	$('#imgmapachico').val("");
	        	$("#divPreviewImgReferencias").html("<img class='thumbnail' src='" + $("#ImgReferencias" + strID).val() + "' />");
	        	$('#imgreferencias').val("");
	        	
	            $('#descripcion').css('overflow', 'hidden').autogrow({vertical: true, horizontal: false});
	            $('#descripcion2').css('overflow', 'hidden').autogrow({vertical: true, horizontal: false});
		    }
		    else {
		        $('#hdnOperacion').val("0");

		        $("#numero").val("");
		        $("#nombre").val("");
		        $("#descripcion").val("");
		        $("#descripcion2").val("");
		        $("#dominio").val("");
		        $("#imgprevia").val("");
		        $("#imgportada").val("");
		        $("#imgmapagrande").val("");
		        $("#imgmapachico").val("");
		        $("#imgreferencias").val("");

	            $("#divPreviewImgPrevia").html("La resolución recomendada es 495x330 con fondo blanco o transparente.");
	            $("#divPreviewImgPortada").html("La resolución recomendada es 1234x462 con fondo blanco o transparente.");
	            $("#divPreviewImgMapaGrande").html("La resolución recomendada es 850x500 con fondo blanco o transparente.");
	            $("#divPreviewImgMapaChico").html("La resolución recomendada es 320x215 con fondo blanco o transparente.");
	            $("#divPreviewImgReferencias").html("La resolución recomendada es 195x205 con fondo blanco o transparente.");
	            
	            $('#descripcion').css('overflow', 'hidden').autogrow({vertical: true, horizontal: false});
	            $('#descripcion2').css('overflow', 'hidden').autogrow({vertical: true, horizontal: false});
			}
		}

		function borrar(strID){
	        if (confirm("Desea borrar la Ruta " + $('#Nombre' + strID).html())){
                $('#hdnOperacion').val("2");
                $('#numero').val(strID);
                aceptar();
	        }
	    }

	    function validar() {
		    var mensaje = "";

		    if ($('#nombre').val().trim().length == 0) {
		    	if (mensaje != "")
			        mensaje+= "<br>";
			        
		        mensaje+= "El Nombre de la Ruta no puede estar vac&iacute;o.";
		    }

		    if ($('#dominio').val().trim().length == 0) {
		    	if (mensaje != "")
			        mensaje+= "<br>";
			        
		        mensaje+= "El Dominio de la Ruta no puede estar vac&iacute;o.";
		    }
		    
	        if ($('#hdnOperacion').val() == "0") {
		        //Nuevo
	        	if ($('#imgprevia').val() == "") {
			        if (mensaje != "")
				        mensaje+= "<br>";
	        		mensaje+= "Debe seleccionar una imagen de vista previa.";
	        	}

	        	if ($('#imgportada').val() == "") {
			        if (mensaje != "")
				        mensaje+= "<br>";
	        		mensaje+= "Debe seleccionar una imagen de portada.";
	        	}

	        	if ($('#imgmapagrande').val() == "") {
			        if (mensaje != "")
				        mensaje+= "<br>";
	        		mensaje+= "Debe seleccionar una imagen de mapa grande.";
	        	}

	        	if ($('#imgmapachico').val() == "") {
			        if (mensaje != "")
				        mensaje+= "<br>";
	        		mensaje+= "Debe seleccionar una imagen de mapa chico.";
	        	}

	        	if ($('#imgreferencias').val() == "") {
			        if (mensaje != "")
				        mensaje+= "<br>";
	        		mensaje+= "Debe seleccionar una imagen de referencias.";
	        	}
	        }
	        else {
		        //Editando
	        	if (($('#imgprevia').val() == "") && ($("#divPreviewImgPrevia").html() == "")) {
			        if (mensaje != "")
				        mensaje+= "<br>";
	        		mensaje+= "Debe seleccionar una imagen de vista previa.";
	        	}
	        	
	        	if (($('#imgportada').val() == "") && ($("#divPreviewImgPortada").html() == "")) {
			        if (mensaje != "")
				        mensaje+= "<br>";
	        		mensaje+= "Debe seleccionar una imagen de portada.";
	        	}
	        	
	        	if (($('#imgmapagrande').val() == "") && ($("#divPreviewImgMapaGrande").html() == "")) {
			        if (mensaje != "")
				        mensaje+= "<br>";
	        		mensaje+= "Debe seleccionar una imagen de mapa grande.";
	        	}
	        	
	        	if (($('#imgmapachico').val() == "") && ($("#divPreviewImgMapaChico").html() == "")) {
			        if (mensaje != "")
				        mensaje+= "<br>";
	        		mensaje+= "Debe seleccionar una imagen de mapa chico.";
	        	}
	        	
	        	if (($('#imgreferencias').val() == "") && ($("#divPreviewImgReferencias").html() == "")) {
			        if (mensaje != "")
				        mensaje+= "<br>";
	        		mensaje+= "Debe seleccionar una imagen de referencias.";
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

	    function abrirGaleria(strID) {
		    location.href = "rutasmexicoGaleria.php?Ruta=" + strID;
	    }

	    function abrirItinerarios(strID) {
	    	location.href = "rutasmexicoItinerarios.php?Ruta=" + strID;
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
			<h2>Rutas</h2>
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
				<label for="descripcion" class="control-label col-md-2">Descripci&oacute;n:</label>
				<div class="col-md-10">
					<textarea class="form-control" id="descripcion"></textarea>
				</div>
			</div>
			<div class="form-group">
				<label for="descripcion2" class="control-label col-md-2">Descripci&oacute;n alternativa:</label>
				<div class="col-md-10">
					<textarea class="form-control" id="descripcion2"></textarea>
				</div>
			</div>
			<div class="form-group">
				<label for="dominio" class="control-label col-md-2">Dominio:</label>
				<div class="col-md-4">
					<input type="text" class="form-control" id="dominio" />
				</div>
			</div>
			<div class="form-group">
				<label for="imgprevia" class="control-label col-md-2">Imagen de vista previa*:</label>
				<div class="col-md-4">
					<div id="divPreviewImgPrevia">
						La resolución recomendada es 495x330 con fondo blanco o transparente.
					</div>
					<input type="file" class="form-control" id="imgprevia" />
				</div>
			</div>
			<div class="form-group">
				<label for="imgportada" class="control-label col-md-2">Imagen de portada*:</label>
				<div class="col-md-4">
					<div id="divPreviewImgPortada">
						La resolución recomendada es 1234x462 con fondo blanco o transparente.
					</div>
					<input type="file" class="form-control" id="imgportada" />
				</div>
			</div>
			<div class="form-group">
				<label for="imgmapagrande" class="control-label col-md-2">Imagen de mapa grande*:</label>
				<div class="col-md-4">
					<div id="divPreviewImgMapaGrande">
						La resolución recomendada es 850x500 con fondo blanco o transparente.
					</div>
					<input type="file" class="form-control" id="imgmapagrande" />
				</div>
			</div>
			<div class="form-group">
				<label for="imgmapachico" class="control-label col-md-2">Imagen de mapa chico*:</label>
				<div class="col-md-4">
					<div id="divPreviewImgMapaChico">
						La resolución recomendada es 320x215 con fondo blanco o transparente.
					</div>
					<input type="file" class="form-control" id="imgmapachico" />
				</div>
			</div>
			<div class="form-group">
				<label for="imgreferencias" class="control-label col-md-2">Imagen de referencia de mapa*:</label>
				<div class="col-md-4">
					<div id="divPreviewImgReferencias">
						La resolución recomendada es 195x205 con fondo blanco o transparente.
					</div>
					<input type="file" class="form-control" id="imgreferencias" />
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