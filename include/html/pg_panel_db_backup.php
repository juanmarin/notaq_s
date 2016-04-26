<?php unset($_SESSION["clid"]); ?>
<script>
	$(document).ready(function(){
	
	});
</script>
<p class="title">Respaldar Informacion del Sistema</p>

<table>
<form name="nc1" action="include/php/dumpeo.php" method="post">
<input type="hidden" name="action" value="backup" />
	<caption>Respaldo de Registros del Sistema</caption>
	<thead>
		<tr>
			<th colspan="1">&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>
			Nombre del archivo:<br />
			<input type="text" name="db_name" id="db_name" style="width:400px;" maxlength="25" />
			</td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<th colspan="4">
			<input type="submit" name="personal" id="personal" value="Respaldar &raquo;" />
			</th>
		</tr>				
	</tfoot>
</form>
<table>