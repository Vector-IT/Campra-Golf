<!-- Begin # Login Form -->
<form id="login-form">
	<h4 class="modal-title">Iniciar Sesión</h4>
	<div class="modal-body">
		<div id="div-login-msg">
			<span class="icon-form glyphicon" aria-hidden="true"></span>&nbsp;<span id="text-login-msg"></span>
		</div>
	
		<input id="login_username" class="form-control" type="text" placeholder="Usuario" required style="text-transform: none !important;">
		<input id="login_password" class="form-control" type="password" placeholder="Contraseña" required style="text-transform: none !important;">
		<input type="hidden" id="returnUrl" value="<?php echo $_SERVER["REQUEST_URI"];?>" />
		<!-- 
		<div class="checkbox">
			<label>
			<input type="checkbox"> Recordarme
			</label>
		</div>
		 -->
	</div>
	<div class="modal-footer" style="border-top: none !important;">
		<div>
			<button type="submit" class="btn btn-small pull-left">Iniciar Sesión</button>
			<button id="login_lost_btn" type="button" class="btn btn-link">Recuperar contraseña</button>
		</div>
	</div>
</form>
<!-- End # Login Form -->
<!-- Begin | Lost Password Form -->
<form id="lost-form" style="display:none;">
	<input type="hidden" id="remitente" value="<?php echo buscarDato("SELECT Email FROM agencias WHERE NumeAgen = " . $numeAgen);?>" />
	<h4 class="modal-title">Recuperar Contraseña</h4>
	<div class="modal-body">
		<div id="div-lost-msg">
			<span class="icon-form glyphicon" aria-hidden="true"></span>&nbsp;<span id="text-lost-msg"></span>
		</div>
		<input id="lost_email" class="form-control" type="text" placeholder="E-Mail" style="text-transform: none !important;" required>
	</div>
	<div class="modal-footer"  style="border-top: none !important;">
		<div>
			<button type="submit" class="btn btn-small pull-left">Enviar</button>
		</div>
		<div>
			<button id="lost_login_btn" type="button" class="btn btn-link">Iniciar Sesión</button>
		</div>
	</div>
</form>
<!-- End | Lost Password Form -->