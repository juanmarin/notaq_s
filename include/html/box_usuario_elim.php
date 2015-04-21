<?php header('Content-Type: text/html; charset=iso-8859-1'); ?>
<style>
	table tbody tr th{text-align: right;}
</style>
<form action="include/php/sys_modelo.php" method="post">
<table>
<caption></caption>
<thead>
	<tr>
		<th colspan="2">Para realizar esta acci&oacute;n es necesaria la autorizaci&oacute;n del Administrador</th>
	</tr>
</thead>
<tbody>
	<tr>
		<th>Usuario:</th>		
        <td><input type="text" name="u"></td>
	</tr>
	<tr>
		<th>Contraseña:</th>	
        <td><input type="password" name="c"></td>
	</tr>
</tbody>

<tfoot>
	<tr>
		<th colspan="4">
		<input type="hidden" name="usr" value="<?php echo $_GET['usr']; ?>" />
		<input type="hidden" name="action" value="usr_elimina" />
		<input type="submit" name="eliminar_usuario" value="Borrar" onclick="return confirm('¿Está seguro que desea eliminar al usuario?');" />
		</th>
	</tr>
</tfoot>
<table>
</form>
