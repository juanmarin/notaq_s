<script>
	$(document).ready(function(){
		 
	});
</script>
<p class="title">Clientes &raquo; Capturar Tercer Aval</p>

<form name="nc1" action="include/php/sys_modelo.php" method="post">
<input type="hidden" name="action" value="cliente_nuevo2" />
<table>
<caption>Agregar nuevo cliente(<?echo $_SESSION["clid"];?>): Datos Tercer Aval </caption>
<thead>
	<tr>
		<th colspan="2">&nbsp;</th>
	</tr>
</thead>
<tbody>
	<tr>
		<td>
		Nombre(s):<br />
		<input type="text" name="r3nom" id="r3nom" style="width:400px;" maxlength="25" />
		</td>
		<td>
		Apellido Paterno:<br />
		<input type="text" name="r3app" id="r3app" maxlength="15" />
		</td>
	</tr>
	<tr>
		<td>
		Apellido Materno:<br />
		<input type="text" name="r3apm" id="r3apm" maxlength="15" />
		</td>
		<td>
		Direcci&oacute;n:<br />
		<input type="text" name="r3dir" id="r3dir" style="width:350px;" maxlength="40" />
		</td>
	</tr>
	<tr>
		<td>
		Colonia:<br />
		<input type="text" name="r3col" id="r3col" maxlength="30" />
		</td>
		<td>
		Tel&eacute;fono<br />
		<input type="text" name="r3tel" id="r3tel" maxlength="18" />
		</td>
	</tr>
	<tr>
		<td colspan="2">
		Vivienda Propia:<br />
		<label><input type="radio" name="r3vivienda" class="rvivienda" value="1" /> Si</label>
		<label><input type="radio" name="r3vivienda" class="rvivienda" value="0" /> No</label>
		</td>
	</tr>
</tbody>
<tfoot>
	<tr>
		<th colspan="4">
		<input type="submit" name="referencias3" id="referencias3" value="Guardar &raquo" />
		</th>
	</tr>				
</tfoot>
<table>