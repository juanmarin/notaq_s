/*************************************************
** SCRIPTS PARA LA PAGINA PRINCIPAL 				**
**************************************************/
$(document).ready(function(){
	//-MIS FUNCIONES
	jQuery.alertas = function(){
		$("#ALERTAS").slideDown();
		setTimeout(
			function(){
				$("#ALERTAS").slideUp(500);
			},
			9000
		);
	}
	//-- INICIALIZANDO DATEPICKER ... ----------------------------------------------->
	$(function(){
		$(".dpfecha").datepicker({dateFormat: 'yy-mm-dd'});
		/*$("#fecha").change(function(){
			alert($(this).val());
		});*/
	});
	//-MOSTRANDO EFECTO AL INICIAR PAGINA ----------------------------------------
	$("#contenido").hide();
	$("#contenido").show("slide", { direction: "right" }, 1000);
	//-MOSTRANDO ALERTAS ---------------------------------------------------------
	$.alertas();
	$("#usuario").focus();
	//-FORMULARIO PARA ENVIAR MENSAJES
	//-VALIDANDO FORMULARIO
	$("#frm_mensaje_enviar").validate();
	//-ENVIANDO DATOS
	$("#btn_mensaje_enviar").click(function(){
		$.post("include/php/sys_modelo.php", {
			action: "frm_mensaje_guardar",
			asunto: $("#asunto").val(),
			para: $("#para").val(),
			mensaje: $("#mensaje").val()
		}, function(data){
			$("#ALERTAS").html(data);
			$.alertas();
			setTimeout(function(){
				//$("#frm_mensaje_enviar")[0].reset();
				$("#mensaje").val("");
			},1000);
		});
	});
	
	$("#btnborranotas").click(function(){
		var cuenta = $(this).attr("rel");
		$(".checkboxnota").each(function(){
			if( $(this).is(":checked") )
			{
				var idn = $(this).attr("rel");
				$("#note_"+idn).hide();
				$.post("include/php/sys_modelo.php", {action:"borrarnota", n:idn, c:cuenta}, function(data){
					if(data==0)
					{
						$("#btnborranotas").hide();
						$("ul.notas").html("<li>No hay notas para este cliente.</li>");
					}
				});
			}
		});
	});
	// Tooltip only Text
        $('.masterTooltip').hover(function(){
                // Hover over code
                var title = $(this).attr('title');
                $(this).data('tipText', title).removeAttr('title');
                $('<p class="tooltip"></p>')
                .text(title)
                .appendTo('body')
                .fadeIn('slow');
        }, function() {
                // Hover out code
                $(this).attr('title', $(this).data('tipText'));
                $('.tooltip').remove();
        }).mousemove(function(e) {
                var mousex = e.pageX + 20; //Get X coordinates
                var mousey = e.pageY + 10; //Get Y coordinates
                $('.tooltip')
                .css({ top: mousey, left: mousex })
        });
});
