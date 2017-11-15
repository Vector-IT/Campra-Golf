<?php
	session_start();
	include("admin/php/conexion.php");

	if (!isset($_SESSION['is_logged_in'])) {
		header("Location:index.php");
		die();
	}
	
	//Filtros
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$numeItin = $_POST["NumeItin"];
		$imagenes = $_POST["Imagenes"];
		$imprimir = $_POST["Imprimir"];
		
		$strSQL = "SELECT i.NumeItin, i.NombItin, t.NombTour, t.Imagen";
		$strSQL.= " FROM itinerarios i";
		$strSQL.= " INNER JOIN (SELECT t.NumeTour, t.NombTour, t.Imagen";
		$strSQL.= " 			FROM tours t";
		$strSQL.= "				WHERE t.NumeEsta = 1 AND t.EnPromo = 0";
		$strSQL.= "				) t ON i.NumeTour = t.NumeTour";
		$strSQL.= " WHERE i.NumeItin = ". $numeItin;
	
		$itinerarios = cargarTabla($strSQL);
	}
	else {
		header("Location:index.php");
		die();
	}
		
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>Itinerarios</title>
		<?php if ($imprimir == "1") { ?>
			<link rel="shortcut icon" href="http://iconntravel.com.mx/images/favicon.ico" />
		<?php }?>
		
		<?php if ($imprimir == "1") { ?>
			<link href="css/bootstrap.min.css" rel="stylesheet">
			<link href="css/custom.css" rel="stylesheet" type="text/css">
			<script src="js/bootstrap.min.js"></script>

			<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
			<script src="http://iconntravel.com.mx/js/jquery.ns-autogrow.min.js"></script>
		<?php } ?>
		
		<style>
			@import url('http://iconntravel.com.mx/fonts/FranklinGothic-Book.css');
			@import url('http://iconntravel.com.mx/fonts/Helvetica-Bold.css');
			@import url('http://iconntravel.com.mx/fonts/HelveticaNeueLTStd-Cn.css');
			@import url('http://iconntravel.com.mx/fonts/HelveticaNeueLTStd-CnO.css');
			@import url('http://iconntravel.com.mx/fonts/Humanist521BT-Light.css');
			@import url('http://iconntravel.com.mx/fonts/MyriadWebPro-Bold.css');
			@import url('http://iconntravel.com.mx/fonts/MyriadWebPro-Italic.css');
			@import url('http://iconntravel.com.mx/fonts/MyriadWebPro-Regular.css');
			
			body {
			    background: linear-gradient(to bottom, #7f7f7f 0%,#000000 0%,#cbaa4b 100%);
			    font-family: MyriadWebPro, Helvetica, Arial, sans-serif;
			    font-size: 14px;
			    line-height: 1.42857143;
			    color: #333;
			    margin: 0;
		    }
		    
			.col-itin {
				background-color:rgb(255, 255, 255);
				box-sizing:border-box;
				color:rgb(51, 51, 51);
				display:block;
				font-family:MyriadWebPro, Helvetica, Arial, sans-serif;
				font-size:14px;
				line-height:20px;
				margin-bottom:60px;
				margin-left:102.828px;
				min-height:1px;
				padding-left:15px;
				padding-right:15px;
				width: 83.33333%;
				margin-left: 8.333333%
			}
			
			.titulo {
			    padding: 10px 10px;
			    border-bottom: none!important;
			    border-radius: 0px !important;
			    background-color: #000 !important;
			    color: #FFF !important;
			    text-transform: uppercase;
			    font-size: 20px !important;
			    font-weight: 400;
			    letter-spacing: 1px;
			    text-align: center !important;
			    margin-bottom: 20px !important;
			    margin-top: 20px !important;
			}
			
			.frm-control {
				background-color:rgb(255, 255, 255);
				background-image:none;
				border-bottom-color:rgb(204, 204, 204);
				border-bottom-left-radius:0px;
				border-bottom-right-radius:0px;
				border-bottom-style:solid;
				border-bottom-width:1px;
				border-image-outset:0px;
				border-image-repeat:stretch;
				border-image-slice:100%;
				border-image-source:none;
				border-image-width:1;
				border-left-color:rgb(68, 68, 68);
				border-left-style:none;
				border-left-width:0px;
				border-right-color:rgb(68, 68, 68);
				border-right-style:none;
				border-right-width:0px;
				border-top-color:rgb(68, 68, 68);
				border-top-left-radius:0px;
				border-top-right-radius:0px;
				border-top-style:none;
				border-top-width:0px;
				box-shadow:none;
				box-sizing:border-box;
				color:rgb(68, 68, 68);
				cursor:auto;
				display:block;
				font-family:HelveticaNeueLTStd-Cn;
				font-size:14px;
				font-stretch:normal;
				font-style:normal;
				font-variant:normal;
				font-weight:normal;
				height:34px;
				margin-bottom:0px;
				margin-left:0px;
				margin-right:0px;
				margin-top:0px;
				padding-bottom:3px;
				padding-left:3px;
				padding-right:12px;
				padding-top:6px;
				text-align:start;
				text-indent:0px;
				text-rendering:auto;
				text-shadow:none;
				text-transform:uppercase;
				transition-delay:0s, 0s;
				transition-duration:0.15s, 0.15s;
				transition-property:border-color, box-shadow;
				transition-timing-function:ease-in-out, ease-in-out;
				width:219.578px;
				word-spacing:0px;
				writing-mode:horizontal-tb;
				-webkit-appearance:none;
				-webkit-rtl-ordering:logical;
				-webkit-user-select:text;			
			}
			
			td {
				padding: 5px 5px 0 5px;
			}
			
			.fondoGris {
			    background-color: #eee !important;
			}
			
			.duracion {
			    color: #E9CC6D!important;
			    font-size: 18px !important;
			    font-family: "Arial"!important;
			    font-weight: 200;
			}
			
			.list-group {
			    margin-bottom: 0px!important;
    			border-radius: 0px !important;
    			border: none!important;
    			padding-left: 0;
    			margin-top: 0;
    		}
			.list-group-item {
				display: block;
				padding: 10px 15px;
			    margin-bottom: 0px!important;
			    background-color: #FFF;
			    border-left: none!important;
			    border-right: none!important;
			    border-top: none!important;
			    border-bottom: 1px dotted #ccc!important;
			}
		</style>
		
		<?php if ($imprimir == "1") { ?>
			<script type="text/javascript">
				$(document).ready(function() {
					$("#txtNombre").focus();
					$('#txtContacto').css('overflow', 'hidden').autogrow({vertical: true, horizontal: false});
				});
			</script>
		<?php }?>
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-itin">
					<div class="row" style="background-color: white;">
						<img alt="Logo" src="<?php echo buscarDato("SELECT CONCAT('http://iconntravel.com.mx/admin/', Imagen) Imagen FROM agencias WHERE NumeAgen = " . $_SESSION["NumeAgen"]);?>" style="width: 30%;"/>
					</div>
					<div class="row" style="background-color: white; margin-top: 20px; font-family: 'Arial' !important;">
						<?php if ($imprimir == "1") { ?>
						<form class="form-horizontal" onsubmit="return false;">
							<table>
								<tr>
									<td style="text-align: right;">
										<label style="font-family: 'Arial' !important; font-size: 14pt; padding-top: 5px;">Nombre del pasajero: </label>
									</td>

									<td>
										<input type="text" class="frm-control" id="txtNombre" style="margin: 0; font-family: 'Arial' !important;"/>
									</td>
								</tr>
								<tr>
									<td style="text-align: right;">
										<label style="font-family: 'Arial' !important; font-size: 14pt; padding-top: 5px;">Precio: </label>
									</td>
									
									<td>
										<input type="text" class="frm-control" style="margin: 0; font-family: 'Arial' !important;"/>
									</td>
								</tr>
								<tr>
									<td style="text-align: right; vertical-align: top;">
										<label style="font-family: 'Arial' !important; font-size: 14pt; padding-top: 5px;">Datos de contacto: </label>
									</td>
									<td>
										<textarea class="frm-control" id="txtContacto" style="margin: 0; font-family: 'Arial' !important; width: 300px;"><?php echo $_SESSION["NombUsua"] . $crlf . $_SESSION['NombMail'];?></textarea>
									</td>
								</tr>
								<tr>
									<td>
										<button class="btn btn-default hidden-print" onclick="location.href = 'itinerarios.php';">VOLVER</button>
									</td>
									<td style="text-align: right;">
										<button type="submit" class="btn btn-default hidden-print" onclick="window.print();">IMPRIMIR</button>
									</td>
								</tr>
							</table>
						</form>
						<?php 
							} 
							else { 
								echo $_SESSION["NombUsua"] . "<br>" . $_SESSION['NombMail'];			
							} 
						?>
					</div>
					<div class="row">
					<?php
						$salida = "";
						
						while ($filaItin = $itinerarios->fetch_array()) {
							$salida.= $crlf . '<div class="panel panel-default">';
							$salida.= $crlf . '<div class="panel-heading titulo">';
							$salida.= $crlf . '<h3>' . $filaItin["NombTour"] . " - " . $filaItin["NombItin"] . '</h3>';
							$salida.= $crlf . '</div>';
							$salida.= $crlf . '<div class="panel-body">';
							$salida.= $crlf . '<ul class="list-group">';
								
							$dias = cargarTabla("SELECT NombDia, DescDia FROM itinerariosdetalles WHERE NumeItin = {$filaItin["NumeItin"]} ORDER BY NumeDia");
							$I = 1;
							while ($filaDia = $dias->fetch_array()) {
								if (($I % 2) != 0)
									$salida.= $crlf . '<li  class="list-group-item">';
								else 
									$salida.= $crlf . '<li  class="list-group-item fondoGris">';
								$salida.= $crlf . '<p class="duracion">Día ' . $I . ' – ' . $filaDia["NombDia"] .'</p>';
								$salida.= $crlf . '<p style="text-align: justify;">' . str_replace($crlf, "<br>", $filaDia["DescDia"]) . '</p>';
								$salida.= $crlf . '</li>';
								
								$I++;
							}
							$salida.= $crlf . '</ul>';
							$salida.= $crlf . '</div>';
							$salida.= $crlf . '</div>';
							if ($imagenes == "1") {
								$salida.= $crlf . '<div style="padding-bottom: 20px;">';
								$salida.= $crlf . '<img alt="" src="http://iconntravel.com.mx/admin/'. str_replace(' ', '%20', $filaItin["Imagen"]) .'" style="width: 100%; height: auto;" />';
								$salida.= $crlf . '</div>';
							}
						}
						
						if (isset($dias))
							$dias->free();
						
						if (isset($itinerarios))
							$itinerarios->free();
						
						echo $salida;
					?>
					</div>
				</div>
			</div>		
		</div>
	</body>
</html>