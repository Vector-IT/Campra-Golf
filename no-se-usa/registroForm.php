<!-- Begin | Register Form Usuario -->
<form id="register-form-usuario" style="display:none;">
	<?php
		if (isset($_SESSION["NumeUsua"]))
			$numeUsua = $_SESSION["NumeUsua"];
		else
			$numeUsua = "";
	?>
	<div class="modal-body">
		<div class="form-group" style=" border: 1px solid #69717E; padding: 30px; margin-bottom: 0px !important;">
			<h4 class="modal-title" style="">Alta de Usuario</h4>
			<div id="div-registerUs-msg">
				<span class="icon-form glyphicon" aria-hidden="true"></span>&nbsp;<span id="text-registerUs-msg"></span>
			</div>
			
			<div class="row">
				<div class="col-md-12">
					<label for="nombreCompleto" style="color: #E4CD74!important;">Datos del Usuario</label>
					<input type="text" class="form-control form-custom" id="nombreCompleto" name="name" placeholder="Nombre Completo *" required>
					<input type="email" class="form-control form-custom" id="emailUsuario" placeholder="Correo Electrónico *" required>
					<input type="text" class="form-control form-custom" id="nombreUsuario" placeholder="Usuario *" required>
					<input type="password" class="form-control form-custom" id="password1Usuario" placeholder="Contrase&ntilde;a *" required>
					<input type="password" class="form-control form-custom" id="password2Usuario" placeholder="Repetir Contrase&ntilde;a *" required>
					<input type="hidden" id="usuarioReferencia" value="<?php echo $numeUsua;?>" />
					<input type="hidden" id="agencia" value="<?php echo $numeAgen;?>" />
				</div>
			</div>
			<br>
			<button type="submit" class="btn btn-small center-block"><span style="font-size: 12px;">Registrarse</span></button>  
			<br>
			<p style="color: #f8f8f8!important; font-size: 12px; ">Ya eres un usuario registrado? <button id="registerUs_login_btn" type="button" class="btn btn-link">Iniciar sesión</button>	</p>
		</div>
	</div>
</form>
<!-- End | Register Form Usuario -->
<!-- Begin | Register Form Agencia -->
<form id="register-form-agencia" style="display:none;">
	<div class="modal-body">
		<div class="form-group" style=" border: 1px solid #69717E; padding: 30px; margin-bottom: 0px !important;">
			<h4 class="modal-title" style="">UNETE A NOSOTROS</h4>
			<p style="text-align: center; color: #fff !important; font-size: 12px; padding-left: 15px; line-height: 16px !important;">
				Si quieres acceder a los precios y tarifas, ¡Regístrate!<br>
				Obtén los beneficios de trabajar con Nosotros.<br>
				¡Tú promueves experiencias de vida para tus clientes, nosotros te ayudamos a lograrlo.!
			</p>
			<div class="div-msg-form">
				<span class="icon-form glyphicon" aria-hidden="true"></span>&nbsp;<span class="text-msg-form"></span>
			</div>
			
			<div class="row">
				<div class="col-md-6">
					<label for="nombreAgencia" style="color: #E4CD74!important;">Datos de la Agencia </label>
					<input type="text" class="form-control form-custom" id="nombreAgencia" name="name" placeholder="Nombre Comercial de la Agencia *" required>
					<label for="provincia"> </label>
					<select class="form-control form-custom" id="provincia" placeholder="Seleccione un estado">
					<?php
						echo cargarCombo("SELECT NumeProv, NombProv FROM provincias ORDER BY NombProv", "NumeProv", "NombProv");
					?>
					</select>
					<label for="direccion"> </label>
					<input type="text" class="form-control form-custom" id="direccion" name="name" placeholder="Dirección Completa *" required>
					<label for="telefonoAgencia"> </label>
					<input type="text" class="form-control form-custom" id="telefonoAgencia" name="name" placeholder="Teléfono *" required>
				</div>
				<div class="col-md-6">
					<label for="paginaWeb">&nbsp;</label>
					<input type="text" class="form-control form-custom" id="paginaWeb" name="name" placeholder="Página Web">
					<label for="razonSocial"> </label>
					<input type="text" class="form-control form-custom" id="razonSocial" name="name" placeholder="Razón Social de la Agencia *" required>
					<label for="iata"> </label>
					<input type="text" class="form-control form-custom" id="iata" name="name" placeholder="IATA">
					<label for="numeroSectur"> </label>
					<input type="text" class="form-control form-custom" id="numeroSectur" name="name" placeholder="Número de alta en SECTUR">
					<label for="rfc"> </label>
					<input type="text" class="form-control form-custom" id="rfc" name="name" placeholder="RFC">
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<br>
					<label for="nombreAdmin" style="color: #E4CD74!important;">Datos del Contacto Administrativo</label>
					<input type="text" class="form-control form-custom" id="nombreAdmin" name="name" placeholder="Nombre Completo *" required>
					<label for="telefonoAdmin"> </label>
					<input type="text" class="form-control form-custom" id="telefonoAdmin" name="name" placeholder="Teléfono *" required>
					<label for="emailAdmin"> </label>   
					<input type="email" class="form-control form-custom" id="emailAdmin" placeholder="Correo Electrónico *" required>
				</div>
				<div class="col-md-6">
					<br> 
					<label for="nombreVentas" style="color: #E4CD74!important;">Datos del Contacto de Ventas</label>
					<input type="text" class="form-control form-custom" id="nombreVentas" name="name" placeholder="Nombre Completo *" required>
					<label for="telefonoVentas"> </label>
					<input type="text" class="form-control form-custom" id="telefonoVentas" name="name" placeholder="Teléfono *" required>
					<label for="emailVentas"> </label>   
					<input type="email" class="form-control form-custom" id="emailVentas" placeholder="Correo Electrónico *" required>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12" style="margin-top: 10px; color: white;">
					<label><input type="checkbox" required> He le&iacute;do el <a href="aviso-de-privacidad.php" target="blank" style="color: #C99F37;">Aviso de Privacidad</a></label>
				</div>
			</div>
			<br>
			<button type="submit" class="btn btn-small center-block"><span style="font-size: 12px;">Registrarse</span></button>  
			<br>
			<p style="color: #f8f8f8!important; font-size: 12px; ">Ya eres un usuario registrado? <button id="registerAg_login_btn" type="button" class="btn btn-link">Iniciar sesión</button>	</p>
		</div>
	</div>
</form>
<!-- End | Register Form Agencia -->
