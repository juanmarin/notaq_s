<?php header('Content-Type: text/html; charset=iso-8859-1'); ?>
<p class="title">Panel &raquo; Edición de usuario</p>
<?php
	require_once("../php/sys_db.class.php");
	require_once("../conf/Config_con.php");
	$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
	$sql = "SELECT * FROM mymvcdb_users WHERE userID = ".$_GET['usr'];
	$result = $db->query($sql);
	$ln = $db->fetchNextObject($result);
?>
<form name="infousredit" method="post" action="include/php/sys_modelo.php">
<input type="hidden" name="action" value="usr_editar" />
<input type="hidden" name="id" value="<?php echo $_GET['usr'];?>" />

<table>
<caption>INFORMACIÓN DE USUARIO</caption>
<tbody>
	<tr>
		<th>Nombre:</th>
		<td><input type="text" name="nombre" id="nombre" value="<?php echo $ln->nombre;?>" size="40"/></td>
	</tr>
	<tr>
		<th>Departamento:</th>
		<td>
			<select name="departamento" id="departamento">
				<option value="ADMINISTRACION"<?php echo ($ln->departamento = "ADMINISTRACION")? ' selected="selected"' : '';?>>ADMINISTRACION</option>
				<option value="COBRANZA"<?php echo ($ln->departamento = "COBRANZA")? ' selected="selected"' : '';?>>COBRANZA</option>
			</select>
		</td>
	</tr>
		<th>Puesto:</th>
		<td>
			<select name="puesto" id="puesto">
				<option value="ADMINISTRADOR"<?php echo ($ln->puesto = "ADMINISTRADOR")? ' selected="selected"' : '';?>>ADMINISTRADOR</option>
				<option value="COBRADOR"<?php echo ($ln->puesto = "COBRADOR")? ' selected="selected"' : '';?>>COBRADOR</option>
			</select>
		</td>
	</tr>
	<tr>
		<th>Correo:</th>
		<td><input type="text" name="email" id="email" placeholder = "email@cofianzp.com" value="<?php echo $ln->email;?>" size = "40" /></td>
	</tr>
	<tr>
		<th>Telefono:</th>
		<td><input type="text" name="telefono" id="telefono" value="<?php echo $ln->telefono;?>" /></td>
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
		<th>Nombre de usuario:</th>
		<td><input type="text" name="uname" id="uname" value="<?php echo $ln->username;?>" /></td>
	</tr>
	<tr>
		<th>Contraseña actual:</th>
		<td><input type="password" name="conac" id="conac" value="" /></td>
	</tr>
	<tr>
		<th>Contraseña nueva:</th>
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
			<input type="submit" value="Guardar cambios &raquo;" />
		</th>
	</tr>
</tfoot>
</table>
</form>