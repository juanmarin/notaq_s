<p class="title">Panel &raquo; Agregar usuario</p>
<form name="infousr" method="post" action="include/php/sys_modelo.php">
<input type="hidden" name="action" value="usr_nuevo" />
<table>
<caption>INFORMACIÓN DE USUARIO</caption>
<tbody>
	<?
	echo $_SESSION["msg"];
	unset($_SESSION["msg"]);
	?>
	<tr>
		<th width="350">Nombre:</th>
		<td><input type="text" name="nombre" id="nombre" size="50" value="<?php echo $_SESSION["nu_nom"];?>" /></td>
	</tr>
	<tr>
	<tr>
		<th>Departamento:</th>
		<td>
			<select name="departamento" id="departamento">
				<option value="ADMINISTRACION"<?echo ($_SESSION["nu_dep"] = "ADMINISTRACION")? ' selected="selected"' : '';?>>ADMINISTRACION</option>
				<option value="COBRANZA"<?echo ($_SESSION["nu_dep"] = "COBRANZA")? ' selected="selected"' : '';?>>COBRANZA</option>
			</select>
		</td>
	</tr>
		<th>Puesto:</th>
		<td>
			<select name="puesto" id="puesto">
				<option value="ADMINISTRADOR">ADMINISTRADOR</option>
				<option value="COBRADOR">COBRADOR</option>
			</select>
		</td>
	</tr>
	<tr>
		<th>Correo:</th>
		<td><input type="text" name="email" id="email" size="40" value="<?php echo $_SESSION["nu_ema"];?>" /></td>
	</tr>
	<tr>
		<th>Teléfono:</th>
		<td><input type="text" name="telefono" id="telefono" size="20" value="<?php echo $_SESSION["nu_tel"];?>" /></td>
	</tr>
</tbody>
<tfoot>
	<tr>
		<th colspan="2"></th>
	</tr>
</tfoot>
</table>

<table>
<caption>INFORMACIÓN DE CUENTA</caption>
<tbody>
	<tr>
		<th width="350">Nombre de usuario:</th>
		<td><input type="text" name="uname" id="uname" value="<?php echo $_SESSION["nu_una"];?>" /></td>
	</tr>
	<tr>
		<th>Contraseña:</th>
		<td><input type="password" name="conu" id="conu" /></td>
	</tr>
	<tr>
		<th>Confirmar contraseña nueva:</th>
		<td><input type="password" name="confnu" id="confnu" /></td>
	</tr>
</tbody>
<tfoot>
	<tr>
		<th colspan="2">
			<input type="submit" value="Guardar &raquo;" />
		</th>
	</tr>
</tfoot>
</table>
</form>