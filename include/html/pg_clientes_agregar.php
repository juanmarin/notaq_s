<?php unset($_SESSION["clid"]); ?>
<script>
	$(document).ready(function(){
	
	});
</script>
<p class="title">Clientes &raquo; Nuevo cliente </p>

<table>
<form name="nc1" action="include/php/sys_modelo.php" method="post">
<input type="hidden" name="action" value="cliente_nuevo" />
	<caption>Agregar nuevo cliente: Datos personales</caption>
	<thead>
		<tr>
			<th colspan="2">&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>
			Nombre(s):<br />
			<input type="text" name="nombre" id="nombre" style="width:400px;" maxlength="25" />
			</td>
			<td>
			Apellido paterno:<br />
			<input type="text" name="apellidop" id="apellidop" maxlength="15" />
			</td>
		</tr>
		<tr>
			<td>
			Apellido materno:<br />
			<input type="text" name="apellidom" id="apellidom" maxlength="15" />
			</td>
			<td>
			Dirección:<br />
			<input type="text" name="dir" id="dir" style="width:430px" maxlength="40" />
			</td>
		</tr>
		<tr>
			<td>
			Colonia:<br />
			<input type="text" name="col" id="col" maxlength="30" />
			</td>
			<td>
			Teléfono de casa:<br />
			<input type="text" name="tel" id="tel" maxlength="20" />
			</td>
		</tr>
		<tr>
			<td>
			Teléfono celular:<br />
			<input type="text" name="cel" id="cel" maxlength="20" />
			</td>
			<td>
			RFC:<br />
			<input type="text" name="rfc" id="rfc" maxlength="20" />
			</td>
		</tr>
		<tr>
			<td>
				Asignar Cobrador:
			</td>
			<td>
				<?php
		            $sql = "SELECT userID, username FROM mymvcdb_users WHERE nivel = 3";
		            $rs = mysql_query($sql) or die(mysql_error());
		            echo "<select name='cobrador' id='cobrador'>";
		            echo "<option value='seleccionar'>Asignar</option>";
		            while($row = mysql_fetch_array($rs)){
		            echo "<option value='".$row["username"]."'>".$row["username"]."</option>";
		            }mysql_free_result($rs);
		    	?>
			</select>
			</td>
		</tr>
		<tr>
			<td colspan="1">
			Vivienda:<br />
			<label><input type="radio" name="vivienda" class="vivienda" value="1" /> Propia</label>
			<label><input type="radio" name="vivienda" class="vivienda" value="2" /> Renta</label>
			<label><input type="radio" name="vivienda" class="vivienda" value="3" /> Padres</label>
			<label><input type="radio" name="vivienda" class="vivienda" value="4" /> Otro</label>
			</td>
			<td colspan="1">
			Capturar Aval:<br />
			<label><input type="radio" name="aval" class="aval" value="1" /> Si</label>
			<label><input type="radio" name="aval" class="aval" value="2" /> No</label>
			</td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<th colspan="4">
			<input type="submit" name="personal" id="personal" value="Siguiente &raquo;" />
			</th>
		</tr>				
	</tfoot>
</form>
<table>