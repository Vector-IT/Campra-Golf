<?php 
	session_start();
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

	<!--  BOOTSTRAP -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>	

	<script type="text/javascript">
		$(document).ready(function() {
			$("input[name='usuario']").focus();
		});
	</script>
</head>
<body>
	<div class="jumbotron">
		<div class="container">
			<img alt="logo" src="imagenes/logo.png">
		</div>
	</div>	
	
	<div class="container">
		<form action="php/loginProcesar.php" method="post" class="form-horizontal">
			<div class="page-header">
				<h2>Acceda al sistema</h2>
			</div>
			
			<?php
				if (isset($_REQUEST["error"])) {
					$strSalida = '';
					$strSalida.= '<div class="alert alert-danger alert-dismissible" role="alert">';
					$strSalida.= '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
					$strSalida.= '<strong>Error!</strong> El usuario o la contrase&ntilde;a son incorrectos.';
					$strSalida.= '</div>';
					
					echo $strSalida;
				}
				
				if (isset($_REQUEST["returnUrl"])) 
					echo '<input type="hidden" name="returnUrl" value="'.$_REQUEST["returnUrl"].'" />';
				else
					echo '<input type="hidden" name="returnUrl" value="../index.php" />';
			?>
			
			<div class="form-group">
				<label for="usuario" class="control-label col-md-2">Usuario:</label>
				<div class="col-md-4">
					<input type="text" class="form-control" name="usuario" placeholder="Usuario" required />
				</div>
			</div>
			<div class="form-group">
				<label for="password" class="control-label col-md-2">Contrase&ntilde;a:</label>
				<div class="col-md-4">
					<input type="password" class="form-control" name="password" placeholder="Contrase&ntilde;a" required />
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-offset-2 col-md-4">
					<button type="submit" class="btn btn-primary">Login</button>
				</div>
			</div>
		</form>
	</div>
</body>
</html>