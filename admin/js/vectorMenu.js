(function ( $ ) {
	$.fn.vectorMenu = function( options ) {

		//Opciones de configuracion por defecto
		var settings = $.extend({
            width: "250px",
            trigger: '#btnMenu',
            transition: "slide",
            duration: 600,
            startVisible: false,
            startVisibleMobile: false,
            opacity: 0.6,
            background: "#000",
            closeWidth: "0",
            closeWidthMobile: "0",
            itemPadding: "10px 5px",
            itemColor: "#FFF",
            itemColorHover: "#000",
            itemBackground: "",
            itemBackgroundHover: "rgba(255, 255, 255, 0.8)"
        }, options );
		
		//Variable del control del menu
		var vsMenu = $(this);
		
		//Verifico si estoy en un movil
		var mob = false;
		if (( /Android|webOS|iPhone|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) || $(document).width() <= settings.mobileWidth) {
			mob = true;
		}
		
		if (mob) {
			settings.closeWidth = settings.closeWidthMobile;
			settings.startVisible = settings.startVisibleMobile;
		}
		
		//Seteo de estilos al menu
		vsMenu.css({
			position: "fixed", 
			top: 0, 
			bottom: 0, 
			left: 0, 
			width: settings.width, 
			opacity: settings.opacity, 
			display: "none",
			"padding-top": "50px",
			background: settings.background,
			"z-index": 1
		});
		
		//Seteo de estilos a los items en estado normal
		vsMenu.find(".item").css({
			background: settings.itemBackground,
			color: settings.itemColor,
			padding: settings.itemPadding,
			cursor: "pointer"
		});
		//Seteo de estilos a los items en estado hover
		vsMenu.find(".item").hover(
			//hover
			function() {
				$(this).css({
					background: settings.itemBackgroundHover,
					color: settings.itemColorHover
				});
			},
			//normal
			function() {
				$(this).css({
					background: settings.itemBackground,
					color: settings.itemColor
				});
			}
		);
		
		//Seteo estilos a los separadores
		vsMenu.find(".separator").css({
			height: "5px",
			"margin-bottom": "5px",
			"border-bottom": "1px solid"
		});
		
		//Click en el boton disparador
		$(settings.trigger).click(function() {
			//Al cerrar el menu queda un poco abierto
			var mLeft = parseInt(settings.closeWidth.replace("px", "")) - parseInt(settings.width.replace("px", "")) + "px";
			
			if (vsMenu.css("display") == "none") {
				vsMenu.css({
					"margin-left": "-" + settings.width,
					display: "block"
				});
			}
			
			if (vsMenu.css("margin-left") == mLeft) {
				vsMenu.animate({"margin-left": 0}, settings.duration);
			}
			else {
				vsMenu.animate({"margin-left": mLeft}, settings.duration);
			}
		});

		//Click en el item
		vsMenu.find(".item").click(function() {
			if ((settings.closeWidth == "0") || (vsMenu.css("margin-left") == "0px")){
				$(settings.trigger).click();
				
				var item = $(this);
				
				setTimeout(function(){ 
					if (item.attr("data-js") != null)
						eval(item.attr("data-js"));
					
					if (item.attr("data-url") != null)
						location.href = item.attr("data-url"); 
					}, 
					settings.duration
				);
			}
			else {
				if ($(this).attr("data-js") != null)
					eval($(this).attr("data-js"));
				
				if ($(this).attr("data-url") != null)
					location.href = $(this).attr("data-url");
			}
		});
		
		//Si es necesario lo abro completa o parcailmente
		if (settings.startVisible || settings.closeWidth != "0")
			$(settings.trigger).click();
		
	}
}( jQuery ));