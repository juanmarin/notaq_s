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
			<select name="d">
				<option value="IF">INE Frente</option>
				<option value="IR">INE Reverso</option>
				<option value="P">Perfil de cliente</option>
				<option value="D1">Domicilio 1</option>
				<option value="D2">domicilio 2</option>
				<option value="A1">Aval F</option>
				<option value="A2">Aval R</option>
			</select>
			<input type="submit" value="Agregar" />
		</td>
	</tr>
</tbody>
</table>
</form>

<br />

<table>
<tbody>
	<tr><th colspan="2">Imágenes de credencial de INE:</th></tr>
	<tr>
		<td align="center"><img src="include/html/pg_clientes_muestraine.php?c=<?=$_GET['cl']?>&d=IF" style="max-width:320px" /></td>
		<td align="center"><img src="include/html/pg_clientes_muestraine.php?c=<?=$_GET['cl']?>&d=IR" style="max-width:320px" /></td>
	</tr>
	
	<tr><th colspan="2">Imágenes de comprobante de domicilio:</th></tr>
	<tr>
		<td align="center"><img src="include/html/pg_clientes_muestraine.php?c=<?=$_GET['cl']?>&d=D1" style="max-width:320px" /></td>
		<td align="center"><img src="include/html/pg_clientes_muestraine.php?c=<?=$_GET['cl']?>&d=D2" style="max-width:320px" /></td>
	</tr>
	
	<tr><th colspan="2">Imágenes de aval:</th></tr>
	<tr>
		<td align="center"><img src="include/html/pg_clientes_muestraine.php?c=<?=$_GET['cl']?>&d=A1" style="max-width:320px" /></td>
		<td align="center"><img src="include/html/pg_clientes_muestraine.php?c=<?=$_GET['cl']?>&d=A2" style="max-width:320px" /></td>
	</tr>
</tbody>
</table>
