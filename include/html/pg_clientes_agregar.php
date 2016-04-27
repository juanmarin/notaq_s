<?php unset($_SESSION["clid"]); ?>
<script>
	$(document).ready(function(){
	
	});
</script>
<p class="title">Clientes &raquo; Nuevo cliente </p>

<table>
<form name="nc1" action="include/php/sys_modelo.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="action" value="cliente_nuevo" />
<input type="hidden" name="activo" value="1" />
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
			<input type="text" name="nombre" id="nombre" style="width:400px;" maxlength="30" />
			</td>
			<td>
			Apellido paterno:<br />
			<input type="text" name="apellidop" id="apellidop" maxlength="20" />
			</td>
		</tr>
		<tr>
			<td>
			Apellido materno:<br />
			<input type="text" name="apellidom" id="apellidom" maxlength="20" />
			</td>
			<td>
			Direcci&oacute;n:<br />
			<input type="text" name="dir" id="dir" style="width:430px" maxlength="40" />
			</td>
		</tr>
		<tr>
			<td>
			Colonia:<br />
			<input type="text" name="col" id="col" maxlength="30" />
			</td>
			<td>
			Tel&eacute;fono de casa:<br />
			<input type="text" name="tel" id="tel" maxlength="20" />
			</td>
		</tr>
		<tr>
			<td>
			Tel&eacute;fono celular:<br />
			<input type="text" name="cel" id="cel" maxlength="20" />
			</td>
			<td>
			Sector:<br />
			<select name="sector" id="sector">
				<option value=" ">Seleccionar</option>
				<option value="1">Sector 1</option>
				<option value="2">Sector 2</option>
				<option value="3">Sector 3</option>
				<option value="4">Sector 4</option>
				<option value="5">Sector 5</option>
				<option value="6">Sector 6</option>
			</select>
			</td>
		</tr>
		<tr>
			<td>
				Asignar Cobrador:<br/>
				<?php
		            $sql = "SELECT userID, username FROM mymvcdb_users WHERE username!='jmarincastro'";
		            $rs = mysql_query($sql) or die(mysql_error());
		            echo "<select name='cobrador' id='cobrador'>";
		            echo "<option value=''>Asignar</option>";
		            while($row = mysql_fetch_array($rs)){
		            echo "<option value='".$row["username"]."'>".$row["username"]."</option>";
		            }mysql_free_result($rs);
		    	?>
			</select>
			</td>
			<td>
				Cargar imagen de cliente (JPG):<br />
				<input name="image" accept="image/jpeg" type="file">
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center"><b>Datos Empleo<b/></td>
		</tr>
		<tr>
			<td>Nombre Empleo<br/>
				<input type="text" name="empleo" id="empleo" maxlength="25">
			</td>
			<td>Calle Empleo<br/>
				<input type="text" name="dir_empl" id="dir_empl" maxlength="35">
			</td>
		</tr>
		<tr>
			<td>Colonia Empleo<br/>
				<input type="text" name="c_empl" id="c_empl" maxlength="25">
			</td>
			<td>Tel&eacute;fono Empleo<br/>
				<input type="text" name="tel_empl" id="tel_empl" maxlength="25">
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center"><b>Datos Referencias<b/></td>
		</tr>
		<tr>
			<td>Referencia 1<br/>
				<input type="text" name="ref_1" id="ref_1" maxlength="30">
			</td>
			<td>Referencia 2<br/>
				<input type="text" name="ref_2" id="ref_2" maxlength="30">
			</td>
		</tr>
		<tr>
			<td>Tel&eacute;fono Celular<br/>
				<input type="text" name="cel_ref1" id="cel_ref1" maxlength="25">
			</td>
			<td>Tel&eacute;fono Celular<br/>
				<input type="text" name="cel_ref2" id="cel_ref2" maxlength="25">
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
