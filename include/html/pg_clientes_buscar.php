<script>
	$(document).ready(function(){
		$("#consulta").keyup(function(){
			$.post("include/html/pg_clientes_buscar_query.php", {consulta:$(this).val()}, function(data){
				$("#resultado").html(data);
			});
		});
		$("#consulta_cuenta").keyup(function(){
			$.post("include/html/pg_clientes_buscar_query.php", {consulta_cuenta:$(this).val()}, function(data){
				$("#resultado").html(data);
			});
		});
	});
</script>
<p class="title">Clientes &raquo; Buscar cliente</p>
<table>
<caption>
	Buscar cliente por nombre:<br /> 
	<input type="text" name="consulta" id="consulta" style="width: 450px;" /><br /> 
	Buscar cliente por contrato:<br /> 
	<input type="text" name="consulta_cuenta" id="consulta_cuenta" style="width: 100px;" /><br /> 
</caption>
<thead>
	<tr>
		<th>Nombre</th>
		<th colspan="5">Acciones</th>
	</tr>
</thead>
</table>
<div id="resultado"></div>