function abrirModal($newForm) {
	$('#login-form').css( {"display": "none"});
	$('#lost-form').css( {"display": "none"});
	$('#register-form-agencia').css( {"display": "none" });
	$('#register-form-usuario').css( {"display": "none" });
	
	$newForm.parents().find(".modal-dialog").removeClass("modal-lg");	
	$newForm.css( {"display": "block" });
	
	$('#div-forms').css("height","initial");
};

$(function() {
    
    var $formLogin = $('#login-form');
    var $formLost = $('#lost-form');
    var $formRegisterAg = $('#register-form-agencia');
    var $formRegisterUs = $('#register-form-usuario');
    var $agradecimiento = $('#thanks-modal');
    
    var $divForms = $('#div-forms');
    var $modalAnimateTime = 300;
    var $msgAnimateTime = 150;
    var $msgShowTime = 2000;

    $("form").submit(function () {
    	var form = $(this);
    	
        switch(this.id) {
            case "login-form":
                var $lg_username=$('#login_username').val().trim();
                var $lg_password=$('#login_password').val().trim();

                var frmData = new FormData();
                frmData.append("usuario", $lg_username);
                frmData.append("password", $lg_password);
                frmData.append("returnUrl", $("#returnUrl").val());

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
    					if (xmlhttp.responseText.indexOf('Error') == -1) {
    						msgChange($('#div-login-msg'), $('.icon-form'), $('#text-login-msg'), "success", "glyphicon-ok", "Login OK", true, false);
    					}
    					else {
    						msgChange($('#div-login-msg'), $('.icon-form'), $('#text-login-msg'), "error", "glyphicon-remove", "Login error", false, false);
    					}
    				}
    			};

    			xmlhttp.open("POST","admin/php/loginProcesar.php",true);
    			xmlhttp.send(frmData);
    			return false;
                break;

            case "cambio-pass":
            	var numeUser = $(this).find('#numeUsua').val().trim();
                var passActu = $(this).find('#cambio_password').val().trim();
                var passActuNew = $(this).find('#cambio_password_new').val().trim();
                var passActuNew2 = $(this).find('#cambio_password_new2').val().trim();
                
                if ((passActuNew.length >= 4) && (passActuNew == passActuNew2)) {
	            	var frmData = new FormData();
	                frmData.append("operacion", "4");
	                frmData.append("NumeUsua", numeUser);
	                frmData.append("NombPass", passActu);
	                frmData.append("NombPassNew", passActuNew);
	
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
	    					if (xmlhttp.responseText.indexOf('Error') == -1) {
	    						$('#cambio_password').val("");
	    						$('#cambio_password_new').val("");
	    						$('#cambio_password_new2').val("");
	    						
	    						msgChange($('#div-pass-msg'), $('.icon-form'), $('#text-pass-msg'), "success", "glyphicon-ok", "Contraseña modificada!", false, false);
	    					}
	    					else {
	    						msgChange($('#div-pass-msg'), $('.icon-form'), $('#text-pass-msg'), "error", "glyphicon-remove", "Error al modificar la contraseña", false, false);
	    					}
	    				}
	    			};
	
	    			xmlhttp.open("POST","admin/php/usuariosProcesar.php",true);
	    			xmlhttp.send(frmData);
                }
                else {
                	msgChange($('#div-pass-msg'), $('.icon-form'), $('#text-pass-msg'), "error", "glyphicon-remove", "Las contraseñas nuevas no coinciden.", false, false);
                }
                	
                return false;
            	break;
                
            case "lost-form":
                var frmData = new FormData();
                frmData.append("operacion", "3");
                frmData.append("email", $(this).find("#lost_email").val());
                frmData.append("remitente", $(this).find("#remitente").val());

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
    					if (xmlhttp.responseText.indexOf('Error') == -1) {
    						msgChange($('#div-lost-msg'), $('.icon-form'), $('#text-lost-msg'), "success", "glyphicon-ok", "Contraseña restablecida!", true, false);
    					}
    					else {
    						msgChange($('#div-lost-msg'), $('.icon-form'), $('#text-lost-msg'), "error", "glyphicon-remove", "Email inexistente", false, false);
    					}
    				}
    			};

    			xmlhttp.open("POST","admin/php/usuariosProcesar.php",true);
    			xmlhttp.send(frmData);
                
    			return false;
    			break;
                
            case "register-form-usuario":
            	var nombre = $(this).find("#nombreCompleto").val();
            	var numeUsua = $(this).find("#usuarioReferencia").val();
            	var numeAgen = $(this).find("#agencia").val();
            	var mail = $(this).find("#emailUsuario").val();
            	var usuario = $(this).find("#nombreUsuario").val();
            	var pass1 = $(this).find("#password1Usuario").val();
            	var pass2 = $(this).find("#password2Usuario").val();
            	
            	if (pass1 != pass2){
            		msgChange($('#div-registerUs-msg'), $('#icon-registerUs-msg'), $('#text-registerUs-msg'), "error", "glyphicon-remove", "Las contraseñas no son iguales.", false, false);
            	}
            	else {
	                var frmData = new FormData();
	                frmData.append("operacion", "0");
	                frmData.append("NombComp", nombre);
	                frmData.append("NombMail", mail);
	                frmData.append("NombUsua", usuario);
	                frmData.append("NombPass", pass1);
	                frmData.append("TipoUsua", "3");
	                frmData.append("NumeEsta", "1");
	                frmData.append("NumeUsuaRefe", numeUsua);
	                frmData.append("NumeAgen", numeAgen);
	
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
	    					if (xmlhttp.responseText.indexOf('Error') == -1) {
	    						msgChange($('#div-registerUs-msg'), $('.icon-form'), $('#text-registerUs-msg'), "success", "glyphicon-ok", xmlhttp.responseText, true, false);
	    					}
	    					else {
	    						msgChange($('#div-registerUs-msg'), $('.icon-form'), $('#text-registerUs-msg'), "error", "glyphicon-remove", xmlhttp.responseText, false, false);
	    					}
	    				}
	    			};
	
	    			xmlhttp.open("POST","admin/php/usuariosProcesar.php",true);
	    			xmlhttp.send(frmData);
            	}
            	
            	return false;
            	break;
            	
            case "register-form-agencia":
            case "atencionAgencias":
            	var	NombComercial	= $(this).find("#nombreAgencia").val();
            	var Provincia = $(this).find("#provincia").val();
            	var	Direccion	= $(this).find("#direccion").val();
            	var	Telefono	= $(this).find("#telefonoAgencia").val();
            	var	PaginaWeb	= $(this).find("#paginaWeb").val();
            	var	RazonSocial	= $(this).find("#razonSocial").val();
            	var	IATA	= $(this).find("#iata").val();
            	var	SECTUR	= $(this).find("#numeroSectur").val();
            	var	RFC	= $(this).find("#rfc").val();
            	var	NombAdmin	= $(this).find("#nombreAdmin").val();
            	var	TeleAdmin	= $(this).find("#telefonoAdmin").val();
            	var	MailAdmin	= $(this).find("#emailAdmin").val();
            	var	NombVent	= $(this).find("#nombreVentas").val();
            	var	TeleVent	= $(this).find("#telefonoVentas").val();
            	var	MailVent	= $(this).find("#emailVentas").val();

                var frmData = new FormData();
                frmData.append("operacion", "3");
                frmData.append("NombComercial", NombComercial);
                frmData.append("Provincia", Provincia);
                frmData.append("Direccion", Direccion);
                frmData.append("Telefono", Telefono);
                frmData.append("PaginaWeb", PaginaWeb);
                frmData.append("RazonSocial", RazonSocial);
                frmData.append("IATA", IATA);
                frmData.append("SECTUR", SECTUR);
                frmData.append("RFC", RFC);
                frmData.append("NombAdmin", NombAdmin);
                frmData.append("TeleAdmin", TeleAdmin);
                frmData.append("MailAdmin", MailAdmin);
                frmData.append("NombVent", NombVent);
                frmData.append("TeleVent", TeleVent);
                frmData.append("MailVent", MailVent);

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
    					if (xmlhttp.responseText.indexOf('Error') == -1) {
    						msgChange($(".div-msg-form"), $(".icon-form"), $(".text-msg-form"), "success", "glyphicon-ok", "Su información ya fue recibida, en breve nos pondremos en contacto con usted", false, true);
    						//msgChange($(".div-msg-form"), $(".icon-form"), $(".text-msg-form"), "success", "glyphicon-ok", xmlhttp.responseText, false);
    					}
    					else {
    						msgChange($(".div-msg-form"), $(".icon-form"), $(".text-msg-form"), "error", "glyphicon-remove", xmlhttp.responseText, false, false);
    					}
    				}
    			};

    			xmlhttp.open("POST","admin/php/agenciasProcesar.php",true);
    			xmlhttp.send(frmData);
    			
    			return false;
    			break;
                
            case "newsletter-form":
            	var nombre = $(this).find("#firstName").val() + " " + $(this).find("#lastName").val();
            	var mail = $(this).find("#emailField").val();

                var frmData = new FormData();
                frmData.append("operacion", "0");
                frmData.append("NombComp", nombre);
                frmData.append("NombMail", mail);
                frmData.append("TipoUsua", "4");
                frmData.append("NumeEsta", "1");

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
    					if (xmlhttp.responseText.indexOf('Error') == -1) {
    						msgChange($('#div-news-msg'), $('.icon-form'), $('#text-news-msg'), "success", "glyphicon-ok", "Registro OK", true, false);
    					}
    					else {
    						msgChange($('#div-news-msg'), $('.icon-form'), $('#text-news-msg'), "error", "glyphicon-remove", xmlhttp.responseText, false, false);
    					}
    				}
    			};

    			xmlhttp.open("POST","admin/php/usuariosProcesar.php",true);
    			xmlhttp.send(frmData);
            	
    			return false;
    			break;
            	
            case "form-comen-blog":
            	var frmData = new FormData();
            	var Numero = $(this).find("#numeBlog").val();
            	var Mensaje = $(this).find("#comment").val();
            	var Nombre = $(this).find("#nombre").val();
            	var Correo = $(this).find("#mail").val();
            	
            	frmData.append("operacion", "0");
            	frmData.append("Numero", Numero);
            	frmData.append("Tipo", "1");
                frmData.append("Mensaje", Mensaje);
                frmData.append("Nombre", Nombre);
                frmData.append("Correo", Correo);

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
    					if (xmlhttp.responseText.indexOf('Error') == -1) {
    						msgChange($(".div-msg-form"), $(".icon-form"), $(".text-msg-form"), "success", "glyphicon-ok", "Comentario enviado!", true, false);
    					}
    					else {
    						msgChange($(".div-msg-form"), $(".icon-form"), $(".text-msg-form"), "error", "glyphicon-remove", "Error al enviar comentario!", false, false);
    					}
    				}
    			};

    			xmlhttp.open("POST","/admin/php/comentariosProcesar.php",true);
    			xmlhttp.send(frmData);
            	
    			return false;
    			break;

            case "form-comen-tour":
            	var frmData = new FormData();
            	var Numero = $(this).find("#numeTour").val();
            	var Mensaje = $(this).find("#comment").val();
            	var Nombre = $(this).find("#nombre").val();
            	var Correo = $(this).find("#mail").val();
            	var Puntaje = $(this).find("input[name=rating]:checked").val();
            	
            	frmData.append("operacion", "0");
            	frmData.append("Numero", Numero);
            	frmData.append("Tipo", "2");
                frmData.append("Mensaje", Mensaje);
                frmData.append("Puntaje", Puntaje);
                frmData.append("Nombre", Nombre);
                frmData.append("Correo", Correo);

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
    					if (xmlhttp.responseText.indexOf('Error') == -1) {
    						msgChange($(".div-msg-form"), $(".icon-form"), $(".text-msg-form"), "success", "glyphicon-ok", "Comentario enviado!", true, false);
    					}
    					else {
    						msgChange($(".div-msg-form"), $(".icon-form"), $(".text-msg-form"), "error", "glyphicon-remove", "Error al enviar comentario!", false, false);
    					}
    				}
    			};

    			xmlhttp.open("POST","/admin/php/comentariosProcesar.php",true);
    			xmlhttp.send(frmData);
            	
    			return false;
    			break;
            	
            case "form-reservas":
            	var mensaje = '<span style="font-family: Calibri;">'; 
                mensaje+= "Estimado(a), " + $(this).find("#nombre").val();
                mensaje+= "<br><br>";
                mensaje+= "Agradecemos su visita e inter&eacute;s en vivir la experiencia " + $(document).find("title").text();
                mensaje+= ". Le confirmamos que hemos recibido su solicitud que est&aacute; siendo procesada.";
                mensaje+= "<br><br>";
                mensaje+= "Con base a los siguientes requerimientos, uno de nuestros agentes de viajes m&aacute;s cercano a usted, se pondr&aacute; en contacto para dar forma a esa experiencia y presentarle una cotizaci&oacute;n de acuerdo a: ";
                mensaje+= $(this).find("#mensaje").val();
                mensaje+= "<br><br>";
                mensaje+= "Sus datos de contacto registrados:";
                mensaje+= "<br>Correo: " + $(this).find("#email").val();
                mensaje+= "<br>Tel&eacute;fono: " + $(this).find("#telefono").val();
                mensaje+= "<br>Tour: " + document.URL;
                mensaje+= "<br>Estado: " + $(this).find("#provincia option:selected").text();
                mensaje+= "<br>Comentarios: " + $(this).find("#mensaje").val();
                
                mensaje+= "<br><br>";
                mensaje+= "Atentamente";
                mensaje+= '<br><img src="http://iconntravel.com.mx/admin/imgAgencias/1.png" style="width: 240px;height: auto;border-right: 1px solid #C99F37;padding-right: 10px;float: left;margin-right: 10px;" />';
                mensaje+= '<span>';
                mensaje+= '<strong>Central de Experiencias</strong><br><br>';
                mensaje+= '<a href="http://www.iconntravel.com.mx">www.iconntravel.com.mx</a><br><br>';
                mensaje+= 'Tel. + 52 (55) 42 10 15 00';
                mensaje+= '</span>';
                mensaje+= '</span>';

                var frmData = new FormData();

                frmData.append("para1", $(this).find("#email").val());
                //frmData.append("cco", 'iconntravel@iconnservices.com.mx');
                frmData.append("cco", $(this).find("#cco").val());
                frmData.append("asunto", "Su próximo viaje a " + $(document).find("title").text());
                frmData.append("mensaje", mensaje);

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
    					if (xmlhttp.responseText.indexOf('Error') == -1) {
    						msgChange($("#div-reservas-msg"), $(".icon-form"), $("#text-reservas-msg"), "success", "glyphicon-ok", "Mensaje enviado!", false, true);
    					}
    					else {
    						msgChange($("#div-reservas-msg"), $(".icon-form"), $("#text-reservas-msg"), "error", "glyphicon-remove", "Error al enviar mensaje", false, false);
    					}
    				}
    			};

    			xmlhttp.open("POST","admin/php/enviarMail.php",true);
    			xmlhttp.send(frmData);
    			
    			return false;
    			break;
            	
            case "form-contacto":
            	var mensaje = ""; 
                mensaje+= "Mensaje recibido en formulario de contacto.";
                mensaje+= "<br>";
                mensaje+= "<br>Nombre: " + $(this).find("#nombre").val();
                mensaje+= "<br>Empresa u Organizacion: " + $(this).find("#empresa").val();
                mensaje+= "<br>Correo: " + $(this).find("#email").val();
                mensaje+= "<br>Telefono: " + $(this).find("#telefono").val();
                mensaje+= "<br>";
                mensaje+= "<br>Comentarios: " + $(this).find("#mensaje").val();

                var mensajeAlt = ""; 
                mensajeAlt+= "Mensaje recibido en formulario de contacto.";
                mensajeAlt+= "\n";
                mensajeAlt+= "\nNombre: " + $(this).find("#nombre").val();
                mensajeAlt+= "\nEmpresa u Organizacion: " + $(this).find("#empresa").val();
                mensajeAlt+= "\nCorreo: " + $(this).find("#email").val();
                mensajeAlt+= "\nTelefono: " + $(this).find("#telefono").val();
                mensajeAlt+= "\n";
                mensajeAlt+= "\nComentarios: " + $(this).find("#mensaje").val();
                
                var frmData = new FormData();

                frmData.append("para1", $(this).find("#destinatario").val());
                frmData.append("email", $(this).find("#email").val());
                frmData.append("asunto", "Contacto en IconnTravel.com.mx");
                frmData.append("mensaje", mensaje);
                frmData.append("mensajeAlt", mensajeAlt);

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
    					if (xmlhttp.responseText.indexOf('Error') == -1) {
    						msgChange($(".div-msg-form"), $(".icon-form"), $(".text-msg-form"), "success", "glyphicon-ok", "Mensaje enviado!", false, false);
    					}
    					else {
    						msgChange($(".div-msg-form"), $(".icon-form"), $(".text-msg-form"), "error", "glyphicon-remove", "Error al enviar mensaje", false, false);
    					}
    				}
    			};

    			xmlhttp.open("POST","admin/php/contactoProcesar.php",true);
    			xmlhttp.send(frmData);
    			
    			return false;
    			break;
    			
            case "contacto-agencia":
            	var mensaje = ""; 
                mensaje+= "Nuevo contacto de agencia.";
                mensaje+= "<br>";
                mensaje+= "<br>Tipo de consulta: " + $(this).find("#tipoContacto").find(":selected").text();
                mensaje+= "<br>Nombre: " + $(this).find("#nombre").val();
                mensaje+= "<br>Agencia: " + $(this).find("#agencia").val();
                mensaje+= "<br>E-Mail: " + $(this).find("#email").val();
                mensaje+= "<br>Telefono: " + $(this).find("#telefono").val();
                mensaje+= "<br>Estado: " + $(this).find("#provincia").find(":selected").text();
                mensaje+= "<br>";
                mensaje+= "<br>Comentarios: " + $(this).find("#comentario").val();

                var mensajeAlt = ""; 
                mensajeAlt+= "Nuevo contacto de agencia.";
                mensajeAlt+= "\n";
                mensajeAlt+= "\nTipo de consulta: " + $(this).find("#tipoContacto").find(":selected").text();
                mensajeAlt+= "\nNombre: " + $(this).find("#nombre").val();
                mensajeAlt+= "\nAgencia: " + $(this).find("#agencia").val();
                mensajeAlt+= "\nE-Mail: " + $(this).find("#email").val();
                mensajeAlt+= "\nTelefono: " + $(this).find("#telefono").val();
                mensajeAlt+= "\nEstado: " + $(this).find("#provincia").find(":selected").text();
                mensajeAlt+= "\n";
                mensajeAlt+= "\nComentarios: " + $(this).find("#comentario").val();
                
                var frmData = new FormData();

                frmData.append("email", $(this).find("#email").val());
                frmData.append("asunto", "Contacto de agencia en IconnTravel.com.mx");
                frmData.append("mensaje", mensaje);
                frmData.append("mensajeAlt", mensajeAlt);

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
    					if (xmlhttp.responseText.indexOf('Error') == -1) {
    						$('#contacto-agencia')[0].reset();
    						
    						msgChange($(".div-msg-form"), $(".icon-form"), $(".text-msg-form"), "success", "glyphicon-ok", "Mensaje enviado!", false, false);
    					}
    					else {
    						msgChange($(".div-msg-form"), $(".icon-form"), $(".text-msg-form"), "error", "glyphicon-remove", "Error al enviar mensaje", false, false);
    					}
    				}
    			};

    			xmlhttp.open("POST","admin/php/contactoAgenciaProcesar.php",true);
    			xmlhttp.send(frmData);
    			
    			return false;
    			break;
            	
            case "search-form":
            	location.href = 'resultados.php?termino=' + $(this).find("#search-text").val();
            	return false;
            	break;
            	
            case "form-cotizacion":
            	var frmData = new FormData();

            	frmData.append("operacion", "0");
                frmData.append("NumeAgen", $(this).find("#numeAgen").val());
                frmData.append("NumeUsua", $(this).find("#numeUsua").val());
                frmData.append("Codigo", $(this).find("#codigo").val());
                frmData.append("NumeExpe", $(this).find("#experiencia").val());
                frmData.append("NumeTour", $(this).find("#tour").val());
                frmData.append("Nombre", $(this).find("#nombre").val());
                frmData.append("Email", $(this).find("#email").val());
                frmData.append("Telefono", $(this).find("#telefono").val());
                frmData.append("NumeProv", $(this).find("#provincia").val());
                frmData.append("Pasajero", $(this).find("#pasajero").val());
                frmData.append("FechViaj", $(this).find("#fecha").val());
                frmData.append("Origen", $(this).find("#origen").val());
                frmData.append("Aereo", $(this).find("#aereo").val());
                frmData.append("AdulCant", $(this).find("#adulCant").val());
                frmData.append("AdulEdad", $(this).find("#adulEdad").val());
                frmData.append("MenoCant", $(this).find("#menoCant").val());
                frmData.append("MenoEdad", $(this).find("#menoEdad").val());
                frmData.append("InfaCant", $(this).find("#infaCant").val());
                frmData.append("InfaEdad", $(this).find("#infaEdad").val());
                frmData.append("Comentario", $(this).find("#comentario").val());
                
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
    					if (xmlhttp.responseText.indexOf('Error') == -1) {
    						$('#form-cotizacion')[0].reset();
    						 
    						msgChange($(".div-msg-form"), $(".icon-form"), $(".text-msg-form"), "success", "glyphicon-ok", "Cotizacion recibida!", false, true);
    					}
    					else {
    						msgChange($(".div-msg-form"), $(".icon-form"), $(".text-msg-form"), "error", "glyphicon-remove", "Error al enviar cotización!", false, false);
    					}
    				}
    			};

    			xmlhttp.open("POST","admin/php/cotizarProcesar.php",true);
    			xmlhttp.send(frmData);
    			
    			return false;
            	break;
            	
            case "ruta-dias":
            	var d = new Date($("#dtpDesde").val());
                var n = ((d.getDay() + 7) % 7) + 1;
                $("#hdnDiaDesde").val(n);
                
                var h = new Date($("#dtpHasta").val());
                var n = ((h.getDay() + 7) % 7) + 1;
                $("#hdnDiaHasta").val(n);
                
                var timeDiff = Math.abs(d.getTime() - h.getTime());
                var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24)); 
                
                var frmData = new FormData();

            	frmData.append("Tipo", "1");
            	frmData.append("NumeRuta", $(this).find("#hdnNumeRuta").val());
            	frmData.append("NombRuta", $(this).find("#hdnNombRuta").val());
                frmData.append("DiaDesde", $(this).find("#hdnDiaDesde").val());
                frmData.append("DiaHasta", $(this).find("#hdnDiaHasta").val());
                frmData.append("Dias", diffDays);
                
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
    					if (xmlhttp.responseText.indexOf('Error') == -1) {
    						$("#resultados").html(xmlhttp.responseText);
    						
    						$("#resultados").removeClass("oculto");
    					}
    				}
    			};

    			xmlhttp.open("POST","admin/php/rutasmexicoBusquedaProcesar.php",true);
    			xmlhttp.send(frmData);
    			
                return false;
            	break;
            
            case "ruta-ciudad":
            	var frmData = new FormData();
            	
            	frmData.append("Tipo", "2");
            	frmData.append("NumeRuta", $(this).find("#hdnNumeRuta").val());
            	frmData.append("NombRuta", $(this).find("#hdnNombRuta").val());
            	frmData.append("Ciudad", $(this).find("#txtCiudad").val());
            	frmData.append("Dias", $(this).find("#txtDias").val());
            	
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
            			if (xmlhttp.responseText.indexOf('Error') == -1) {
            				$("#resultados").html(xmlhttp.responseText);
            				
            				$("#resultados").removeClass("oculto");
            			}
            		}
            	};
            	
            	xmlhttp.open("POST","admin/php/rutasmexicoBusquedaProcesar.php",true);
            	xmlhttp.send(frmData);
            	
            	return false;
            	break;
        }
        
    });
    
    $('#registerAg_login_btn').click( function () { modalAnimate($formRegisterAg, $formLogin); });
    $('#registerUs_login_btn').click( function () { modalAnimate($formRegisterUs, $formLogin); });
    $('#lost_login_btn').click( function () { modalAnimate($formLost, $formLogin); });
    $('#login_registerAg_btn').click( function () { modalAnimate($formLogin, $formRegisterAg) });
    $('#lost_registerAg_btn').click( function () { modalAnimate($formLost, $formRegisterAg); });
    $('#login_registerUs_btn').click( function () { modalAnimate($formLogin, $formRegisterUs) });
    $('#lost_registerUs_btn').click( function () { modalAnimate($formLost, $formRegisterUs); });
    $('#login_lost_btn').click( function () { modalAnimate($formLogin, $formLost); });
    
    function modalAnimate ($oldForm, $newForm) {
        var $oldH = $oldForm.height();
        var $newH = $newForm.height();
        $divForms.css("height",$oldH);
        $oldForm.fadeToggle($modalAnimateTime, function(){
            $divForms.animate({height: $newH}, $modalAnimateTime, function(){
                $newForm.fadeToggle($modalAnimateTime);
            });
        });
    }
    
    function msgFade ($msgId, $msgText) {
        $msgId.fadeOut($msgAnimateTime, function() {
            $(this).text($msgText).fadeIn($msgAnimateTime);
        });
    }
    
    function msgChange($divTag, $iconTag, $textTag, $divClass, $iconClass, $msgText, $refresh, $thanks) {
        var $msgOld = $divTag.text();
        msgFade($textTag, $msgText);
        $divTag.addClass($divClass);
        $iconTag.removeClass("glyphicon-chevron-right");
        $iconTag.addClass($iconClass + " " + $divClass);
        
        setTimeout(function() {
            msgFade($textTag, $msgOld);
            $divTag.removeClass($divClass);
            //$iconTag.addClass("glyphicon-chevron-right");
            $iconTag.removeClass($iconClass + " " + $divClass);
            
            if ($refresh) {
            	location.reload();
            	return;
            }
            
            if ($thanks) {
            	var $dlgAux = $divTag.parent();
            	while (!$dlgAux.hasClass("modal")) {
            		$dlgAux = $dlgAux.parent();
            	}
            	
            	$dlgAux.modal('hide');
            	
            	$agradecimiento.modal('show');
            }
   
  		}, $msgShowTime);
  		
    }
});