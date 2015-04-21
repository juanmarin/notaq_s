<?php header('Content-Type: text/html; charset=iso-8859-1'); ?>
<style>
	table tbody tr th{text-align: right;}
</style>
<form action="include/php/sys_modelo.php" method="post">
<table>
<caption></caption>
<thead>
	<tr>
		<th colspan="2">Para borrar una cuenta es necesaria la autorización del supervisor</th>
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
		<input type="hidden" name="cte" value="<?php echo $_GET['cte']; ?>" />
		<input type="hidden" name="cta" value="<?php echo $_GET['cta']; ?>" />
		<input type="hidden" name="action" value="cta_elimina" />
		<input type="submit" name="eliminar_cuenta" value="Borrar" />
		</th>
	</tr>				
</tfoot>
<table>
</form>
