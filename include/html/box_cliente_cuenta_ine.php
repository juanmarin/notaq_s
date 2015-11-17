<form method="post" action="include/php/sys_modelo.php" enctype="multipart/form-data">
<input type="hidden" name="c" value="<?=$_GET['cl']?>" />
<input type="hidden" name="action" value="clienteine" />
<table>
<tbody>
	<tr>
		<td>Subir imegen:</td>
	</tr>
	<tr>
		<td>
			<input name="image" accept="image/jpeg" type="file">
			<select name="l"><option value="F">Frente</option><option value="R">Reverso</option></select>
			<input type="submit" value="Agregar" />
		</td>
	</tr>
</tbody>
</table>
</form>

<br />

<table>
<tbody>
	<tr><td align="center"><img src="include/html/pg_clientes_muestraine.php?c=<?=$_GET['cl']?>&l=F" style="max-width:650px" /></td></tr>
	<tr><td align="center"><img src="include/html/pg_clientes_muestraine.php?c=<?=$_GET['cl']?>&l=R" style="max-width:650px" /></td></tr>
</tbody>
</table>
