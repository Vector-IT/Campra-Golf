<?php 
	$dbhost = "mysql1102.ixwebhosting.com";
	$db = "BBBmawh_campra";
	$dbuser = "BBBmawh_campra";
	$dbpass = "Vector123";
	$crlf = "\n";
	$raiz = "/";
	
	function ejecutarCMD($strSQL) {
		global $dbhost, $dbuser, $dbpass, $db, $crlf;
		
		$conn = new mysqli($dbhost, $dbuser, $dbpass, $db);
	
		$strError = "";
	
		if (!$conn->query($strSQL))
			$strError = $conn->error;
		$conn->close();
	
		if ($strError == "")
			return TRUE;
		else
			return $strError;
	}

	function buscarDato($strSQL) {
		global $dbhost, $dbuser, $dbpass, $db, $crlf;
		
		$conn = new mysqli($dbhost, $dbuser, $dbpass, $db);
	
		$strSalida = "";
	
		if (!($tabla = $conn->query($strSQL))) {
			$strSalida = "Error al realizar la consulta.";
		}
		else {
			$fila = $tabla->fetch_array();
			$strSalida = $fila[0];
			$tabla->free();
		}
	
		$conn->close();
		 
		return $strSalida;
	}
	
	function cargarTabla($strSQL) {
		global $dbhost, $dbuser, $dbpass, $db, $crlf;
		
		$conn = new mysqli($dbhost, $dbuser, $dbpass, $db);
		$tabla = $conn->query($strSQL);
		
		$conn->close();
		
		return $tabla;
	}
	
	function cargarCombo($strSQL, $CampoNumero, $CampoTexto, $Seleccion = "", $itBlank = false, $itBlankText = "Seleccione...") {
		global $dbhost, $dbuser, $dbpass, $db, $crlf;
		
		$conn = new mysqli($dbhost, $dbuser, $dbpass, $db);
		$tabla = $conn->query($strSQL);
		
		$conn->close();
		
		$strSalida = "";
		if ($itBlank)
			$strSalida.= $crlf.'<option value="-1">'. $itBlankText .'</option>';
		
		while ($fila = $tabla->fetch_array()) {
			if ($CampoTexto != "") {
				if (strcmp($fila[$CampoNumero], $Seleccion) != "0")
					$strSalida.= $crlf.'<option value="'.$fila[$CampoNumero].'">'.htmlentities(utf8_decode($fila[$CampoTexto])).'</option>';
				else
					$strSalida.= $crlf.'<option value="'.$fila[$CampoNumero].'" selected>'.htmlentities(utf8_decode($fila[$CampoTexto])).'</option>';
			}
			else {
				if (strcmp($fila[$CampoNumero], $Seleccion) != "0")
					$strSalida.= $crlf.'<option value="'.$fila[$CampoNumero].'" />';
				else
					$strSalida.= $crlf.'<option value="'.$fila[$CampoNumero].'" selected />';
			}
		}
		
		return $strSalida;
	}
	
	function get_random_string($valid_chars, $length)
	{
		// start with an empty random string
		$random_string = "";
	
		// count the number of chars in the valid chars string so we know how many choices we have
		$num_valid_chars = strlen($valid_chars);
	
		// repeat the steps until we've created a string of the right length
		for ($i = 0; $i < $length; $i++)
		{
			// pick a random number from 1 up to the number of valid chars
			$random_pick = mt_rand(1, $num_valid_chars);
	
			// take the random character out of the string of valid chars
			// subtract 1 from $random_pick because strings are indexed starting at 0, and we started picking at 1
			$random_char = $valid_chars[$random_pick-1];
	
			// add the randomly-chosen char onto the end of our string so far
			$random_string .= $random_char;
		}
	
		// return our finished random string
		return $random_string;
	}
	
	function procesarRuteo() {
		$archivo = "../../.htaccess";
	
		$contenido = "<IfModule mod_rewrite.c>";
		$contenido.= "\nRewriteEngine On";
		$contenido.= "\nRewriteBase /";
		$contenido.= "\nRewriteRule ^tour/([^/]*)/? /tour.php?tour=$1 [L,PT]";
		$contenido.= "\nRewriteRule ^experiencia/([^/]*)/? /experiencia.php?experiencia=$1 [L,PT]";
		$contenido.= "\nRewriteRule ^blog/([^/]*)/? /blog.php?blog=$1 [L,PT]";
		$contenido.= "\nRewriteRule ^ruta/([^/]*)/? /ruta.php?ruta=$1 [L,PT]";
	
		$strSQL = "SELECT NumeAgen, Dominio";
		$strSQL.= " FROM agencias";
		$strSQL.= " WHERE Dominio <> '/'";
	
		$tabla = cargarTabla($strSQL);
	
		while ($fila = $tabla->fetch_array()) {
			$contenido.= "\n";
			$contenido.= "\nRewriteRule ^{$fila["Dominio"]}/experiencia/([^/]*)/? /experiencia.php?experiencia=$1&agencia={$fila["NumeAgen"]} [L,PT]";
			$contenido.= "\nRewriteRule ^{$fila["Dominio"]}/producto/([^/]*)/? /producto.php?producto=$1&agencia={$fila["NumeAgen"]} [L,PT]";
			$contenido.= "\nRewriteRule ^{$fila["Dominio"]}/novedades/([^/]*)/? /blog.php?novedades=$1&agencia={$fila["NumeAgen"]} [L,PT]";
			$contenido.= "\nRewriteRule ^{$fila["Dominio"]}/ruta/([^/]*)/? /ruta.php?ruta=$1&agencia={$fila["NumeAgen"]} [L,PT]";
			$contenido.= "\nRewriteRule ^{$fila["Dominio"]}/?(.+) /$1?agencia={$fila["NumeAgen"]} [NC]";
		}
		
		$contenido.= "\n";
		$contenido.= "\nRewriteCond %{REQUEST_URI}  !\.(css|js|php|html?|shtml|jpg|gif|png|jpeg|eot|otf|svg|ttf|woff|woff2|pdf)$";
		$contenido.= "\nRewriteRule ^(.*)([^/])$ http://%{HTTP_HOST}/$1$2/ [L,R=301]";
		$contenido.= "\n</IfModule>";
	
		file_put_contents($archivo, $contenido);
	
		$tabla->free();
	}

	function crearWaterMark($imgOrigen, $imgLogo, $imgDestino) {
		// Load the stamp and the photo to apply the watermark to
		$im = imageCreateFromAny($imgOrigen);
		
		// First we create our stamp image manually from GD
		$logo = imageCreateFromAny($imgLogo);
		
		$thumb = imagecreatetruecolor(255, 105);
		$sx = imagesx($logo);
		$sy = imagesy($logo);
		imagecopyresized($thumb, $logo, 0, 0, 0, 0, 255, 105, $sx, $sy);
		
		// Set the margins for the stamp and get the height/width of the stamp image
		$margen_left = 10;
		$margen_right = 10;
		$margen_top = 10;
		$margen_bottom = 10;
		$sx = imagesx($thumb);
		$sy = imagesy($thumb);
		
		// Merge the stamp onto our photo with an opacity of 50%
		//Logo abajo a la derecha
		//imagecopymerge($im, $thumb, imagesx($im) - $sx - $margen_right, imagesy($im) - $sy - $margen_bottom, 0, 0, imagesx($thumb), imagesy($thumb), 50);

		//Logo Arriba a la izquierda
		imagecopymerge(
				$im, //Imagen de flyer 
				$thumb, //Logo de agencia
				$margen_left,  //X en destino 
				$margen_top, //Y en destino
				0, //Origen en X del logo
				0, //Origen en Y del logo
				imagesx($thumb), //Ancho del logo  
				imagesy($thumb), //Alto del logo
				100); //Transparencia dl logo
		
		// Save the image to file and free memory
		imagepng($im, 'admin/temp/'.$imgDestino);
		imagedestroy($im);
		imagedestroy($thumb);
		imagedestroy($logo);
	}
	
	function imageCreateFromAny($filepath) {
		$type = exif_imagetype($filepath); // [] if you don't have exif you could use getImageSize()
		$allowedTypes = array(
				1,  // [] gif
				2,  // [] jpg
				3,  // [] png
				6   // [] bmp
		);
		if (!in_array($type, $allowedTypes)) {
			return false;
		}
		switch ($type) {
			case 1 :
				$im = imageCreateFromGif($filepath);
				break;
			case 2 :
				$im = imageCreateFromJpeg($filepath);
				break;
			case 3 :
				$im = imageCreateFromPng($filepath);
				break;
			case 6 :
				$im = imageCreateFromBmp($filepath);
				break;
		}
		return $im;
	}
?>