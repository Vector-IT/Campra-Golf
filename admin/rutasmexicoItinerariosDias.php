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

			$("#imagen").change( previewImg );
			$("#divMsj").hide();
			listar();

			$('#descripcion').css('overflow', 'hidden').autogrow({vertical: true, horizontal: false});
		});

		function previewImg(event) {
            var divPreview = $("#divPreview");
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

			xmlhttp.open("POST","php/rutasmexicoItinerariosDiasProcesar.php",true);
			xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			xmlhttp.send("operacion=10&NumeRuta=<?php echo $NumeRuta?>");
		}

		function aceptar(){
		    $('#actualizando').css('display', 'block');
		    
            var frmData = new FormData();

            if (parseInt($("#hdnOperacion").val()) < 2) {
                if (!validar())
                    return;
			}
            frmData.append("operacion", $("#hdnOperacion").val());
            frmData.append("NumeDia", $("#numero").val());
            frmData.append("NumeRuta", <?php echo $NumeRuta?>);
            frmData.append("Ciudades", $("#ciudades").val());
            frmData.append("Descripcion", $("#descripcion").val());
            frmData.append("Imagen", $("#imagen").get(0).files[0]);
            

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

			xmlhttp.open("POST","php/rutasmexicoItinerariosDiasProcesar.php",true);
			xmlhttp.send(frmData);
		}

	    function editar(strID){
		    if (strID > 0) {
		    	$('html, body').animate({
		            scrollTop: $("#formulario").offset().top
		        }, 1000);
		        
		        $('#hdnOperacion').val("1");

	            $('#numero').val(strID);
	            $('#ciudades').val($('#Ciudades' + strID).html());
	            $('#descripcion').val($('#Descripcion' + strID).val());

	            $("#divPreview").html("<img class='thumbnail' src='" + $("#Imagen" + strID).attr("src") + "' />");
	        	$('#imagen').val("");

	        	$('#descripcion').css('overflow', 'hidden').autogrow({vertical: true, horizontal: false});
		    }
		    else {
				$("#divPreview").html("La resolución recomendada es 495x330 con fondo blanco o transparente.");				
		    	
		        $('#hdnOperacion').val("0");

		        $('#numero').val("");
	            $('#ciudades').val("");
	            $('#descripcion').val("");
	            $('#imagen').val("");
			}
		}

		function borrar(strID){
	        if (confirm("Desea borrar el día " + + strID)){
                $('#hdnOperacion').val("2");
                $('#numero').val(strID);
                aceptar();
	        }
	    }

	    function validar() {
		    var mensaje = "";

		    if ($('#ciudades').val().trim().length == 0) {
		    	if (mensaje != "")
			        mensaje+= "<br>";
			        
		        mensaje+= "Debe establecer las ciudades.";
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
		    editar(0);
			var NumeOrde = $("#NumeOrde" + strID).html();
			
			if (NumeOrde > 1) {
				NumeOrde--;
				
		        $("#hdnOperacion").val("3");

		        $("#numero").val(strID);
		    	$("#numeorde").val(NumeOrde);
		    	$("#divPreview").html("<img class='thumbnail' src='" + $("#Imagen" + strID).attr("src") + "' />");
				
		    	aceptar();
			}
			else {
				$("#txtHint").html("La d&iacute;a ya se encuentra en su posici&oacute;n m&aacute;s alta.");
				$("#divMsj").removeClass("alert-success");
				$("#divMsj").addClass("alert-danger");
				$("#divMsj").show();
			}
		}

		function bajar(strID) {
			editar(0);
			var NumeOrde = $("#NumeOrde" + strID).html();
			var CantImag = $(".thumbs").size();
			
			if (NumeOrde < CantImag) {
				NumeOrde++;
				
		        $("#hdnOperacion").val("4");

		        $("#numero").val(strID);
		    	$("#numeorde").val(NumeOrde);
		    	$("#divPreview").html("<img class='thumbnail' src='" + $("#Imagen" + strID).attr("src") + "' />");
				
		    	aceptar();
			}
			else {
				$("#txtHint").html("La d&iacute;a ya se encuentra en su posici&oacute;n m&aacute;s baja.");
				$("#divMsj").removeClass("alert-success");
				$("#divMsj").addClass("alert-danger");
				$("#divMsj").show();
			}
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
			<h2>D&iacute;as de Itinerarios de <?php echo $NombRuta?></h2>
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
					<input type="text" class="form-control" id="numero" disabled />
				</div>
			</div>
			<div class="form-group">
				<label for="imagen" class="control-label col-md-2">Imagen:</label>
				<div class="col-md-4">
					<div id="divPreview">
						La resolución recomendada es 495x330 con fondo blanco o transparente.
					</div>
					<input type="file" class="form-control" id="imagen" />
				</div>
			</div>
			
			<div class="form-group">
				<label for="ciudades" class="control-label col-md-2">Ciudades:</label>
				<div class="col-md-6">
					<input class="form-control" id="ciudades" />
				</div>
			</div>
			
			<div class="form-group">
				<label for="descripcion" class="control-label col-md-2">Descripci&oacute;n:</label>
				<div class="col-md-6">
					<textarea class="form-control" id="descripcion"></textarea>
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