<?php
/*
*/
?>
<script>
	$(document).ready(function(){
		 
	});
</script>
<p class="title">Clientes &raquo; Capturar Segundo Aval </p>

<form name="nc4" action="include/php/sys_modelo.php" method="post">
<input type="hidden" name="action" value="cliente_nuevo4" />
<table>
<caption>Agregar nuevo cliente(<?echo $_SESSION["clid"];?>): Datos Segundo Aval </caption>
<thead>
	<tr>
		<th colspan="2">&nbsp;</th>
	</tr>
</thead>
<tbody>
	<tr>
		<td>
		Nombre(s):<br />
		<input type="text" name="r2nom" id="r2nom" style="width:400px;" maxlength="25" />
		</td>
		<td>
		Apellido Paterno:<br />
		<input type="text" name="r2app" id="r2app" maxlength="15" />
		</td>
	</tr>
	<tr>
		<td>
		Apellido Materno:<br />
		<input type="text" name="r2apm" id="r2apm" maxlength="15" />
		</td>
		<td>
		Direcci&oacute;n:<br />
		<input type="text" name="r2dir" id="r2dir" style="width:350px;" maxlength="40" />
		</td>
	</tr>
	<tr>
		<td>
		Colonia:<br />
		<input type="text" name="r2col" id="r2col" maxlength="30" />
		</td>
		<td>
		Tel&eacute;fono<br />
		<input type="text" name="r2tel" id="r2tel" maxlength="18" />
		</td>
	</tr>
	<tr>
		<td colspan="1">
		Vivienda Propia:<br />
		<label><input type="radio" name="r2vivienda" class="rvivienda" value="1" /> Si</label>
		<label><input type="radio" name="r2vivienda" class="rvivienda" value="0" /> No</label>
		</td>
		<td colspan="1">
		Capturar Tercer Aval:<br />
		<label><input type="radio" name="aval3" class="rvivienda" value="1" /> Si</label>
		<label><input type="radio" name="aval3" class="rvivienda" value="0" /> No</label>
		</td>
	</tr>
</tbody>
<tfoot>
	<tr>
		<th colspan="4">
		<input type="submit" name="referencias2" id="referencias2" value="Guardar &raquo" />
		</th>
	</tr>				
</tfoot>
<table>