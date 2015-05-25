<?php
/*
*/
?>
<script>
	$(document).ready(function(){
		 
	});
</script>
<p class="title">Clientes &raquo; Capturar Aval </p>

<form name="nc3" action="include/php/sys_modelo.php" method="post">
<input type="hidden" name="action" value="cliente_nuevo3" />
<table>
<caption>Agregar nuevo cliente(<?echo $_SESSION["clid"];?>): Datos del Aval</caption>
<thead>
	<tr>
		<th colspan="2">&nbsp;</th>
	</tr>
</thead>
<tbody>
	<tr>
		<td>
		Nombre(s):<br />
		<input type="text" name="r1nom" id="r1nom" style="width:400px;" maxlength="25" />
		</td>
		<td>
		Apellido Paterno:<br />
		<input type="text" name="r1app" id="r1app" maxlength="15" />
		</td>
	</tr>
	<tr>
		<td>
		Apellido Materno:<br />
		<input type="text" name="r1apm" id="r1apm" maxlength="15" />
		</td>
		<td>
		Direcci&oacute;n:<br />
		<input type="text" name="r1dir" id="r1dir" style="width:350px;" maxlength="40" />
		</td>
	</tr>
	<tr>
		<td>
		Colonia:<br />
		<input type="text" name="r1col" id="r1col" maxlength="30" />
		</td>
		<td>
		Tel&eacute;fono<br />
		<input type="text" name="r1tel" id="r1tel" maxlength="18" />
		</td>
	</tr>
	<tr>
		<td colspan="1">
		Vivienda Propia:<br />
		<label><input type="radio" name="r1vivienda" class="rvivienda" value="1" /> Si</label>
		<label><input type="radio" name="r1vivienda" class="rvivienda" value="0" /> No</label>
		</td>
		<td colspan="1">
		Capturar Otro Aval?:<br />
		<label><input type="radio" name="aval2" class="rvivienda" value="1" /> Si</label>
		<label><input type="radio" name="aval2" class="rvivienda" value="0" /> No</label>
		</td>
	</tr>
</tbody>
<tfoot>
	<tr>
		<th colspan="4">
		<input type="submit" name="referencias1" id="referencias1" value="Guardar &raquo" />
		</th>
	</tr>				
</tfoot>
<table>