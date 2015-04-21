<p class="title">Panel &raquo; Información de usuario</p>
<?php
	require_once("include/php/sys_db.class.php");
	require_once("include/conf/Config_con.php");
	$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
	$sql = "SELECT * FROM mymvcdb_users WHERE userID = ".$user->userData[0];
	$result = $db->query($sql);
	$ln = $db->fetchNextObject($result);
?>
<form name="infousr" method="post" action="include/php/sys_modelo.php">
<input type="hidden" name="action" value="usr_editar" />
<input type="hidden" name="id" value="<?php echo $user->userData[0];?>" />
<table>
<caption>INFORMACIÓN DE USUARIO</caption>
<tbody>
	<tr>
		<th>Nombre:</th>
		<td><input type="text" name="nombre" id="nombre" value="<?php echo $ln->nombre;?>" /></td>
	</tr>
	<tr>
		<th>Puesto:</th>
		<td><input type="text" name="puesto" id="puesto" value="<?php echo $ln->puesto;?>" /></td>
	</tr>
	<tr>
		<th>Departamento:</th>
		<td><input type="text" name="departamento" id="departamento" value="<?php echo $ln->departamento;?>" /></td>
	</tr>
	<tr>
		<th>Correo:</th>
		<td><input type="text" name="email" id="email" value="<?php echo $ln->email;?>" /></td>
	</tr>
	<tr>
		<th>Teléfono:</th>
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