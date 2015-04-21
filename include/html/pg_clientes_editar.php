<?php unset($_SESSION["clid"]); ?>
<script>
	$(document).ready(function(){
		
	});
</script>
<p class="title">Clientes &raquo; Ediar [1/3]</p>

<?php
require_once("include/php/sys_db.class.php");
require_once("include/conf/Config_con.php");
$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
$sql = "SELECT * FROM clientes WHERE id = ".$_GET["cl"];
$res = $db->query($sql);
$r = $db->fetchNextObject($res);
?>
<form action="include/php/sys_modelo.php" method="post">
<input type="hidden" name="action" value="cliente_editar_1" />
<input type="hidden" name="cl" value="<?echo $r->id;?>" />
<table>
<caption>Editar información de cliente: Datos personales</caption>
<thead>
	<tr>
		<th colspan="2">&nbsp;</th>
	</tr>
</thead>
<tbody>
	<tr>
		<td>
		Nombre(s):<br />
		<input type="text" name="nombre" id="nombre" style="width:400px;" maxlength="25" value="<?echo $r->nombre;?>" />
		</td>
		<td>
		Apellido paterno:<br />
		<input type="text" name="apellidop" id="apellidop" maxlength="15" value="<?echo $r->apellidop;?>" />
		</td>
	</tr>
	<tr>
		<td>
		Apellido materno:<br />
		<input type="text" name="apellidom" id="apellidom" maxlength="15" value="<?echo $r->apellidom;?>" />
		</td>
		<td>
		Dirección:<br />
		<input type="text" name="dir" id="dir" style="width:430px" maxlength="40" value="<?echo $r->direccion;?>" />
		</td>
	</tr>
	<tr>
		<td>
		Colonia:<br />
		<input type="text" name="col" id="col" maxlength="30" value="<?echo $r->colonia;?>" />
		</td>
		<td>
		Teléfono de casa:<br />
		<input type="text" name="tel" id="tel" maxlength="20" value="<?echo $r->telefono;?>" />
		</td>
	</tr>
	<tr>
		<td>
		Teléfono celular:<br />
		<input type="text" name="cel" id="cel" maxlength="20" value="<?echo $r->celular;?>" />
		</td>
		<td>
		RFC:<br />
		<input type="text" name="rfc" id="rfc" maxlength="20" value="<?echo $r->rfc;?>" />
		</td>
	</tr>
	<tr>
		<td colspan="1">
		Vivienda:<br />
		<label><input type="radio" name="vivienda" class="vivienda" value="1"<?echo ($r->vivienda == 1)?" checked='checked'":"";?> /> Propia</label>
		<label><input type="radio" name="vivienda" class="vivienda" value="2"<?echo ($r->vivienda == 2)?" checked='checked'":"";?> /> Renta</label>
		<label><input type="radio" name="vivienda" class="vivienda" value="3"<?echo ($r->vivienda == 3)?" checked='checked'":"";?> /> Padres</label>
		<label><input type="radio" name="vivienda" class="vivienda" value="4"<?echo ($r->vivienda == 4)?" checked='checked'":"";?> /> Otro</label>
		</td>
		<td colspan="1">
		Editar Aval:<br />
		<label><input type="radio" name="aval_edit1" class="propio" value="1"/> Si</label>
		<label><input type="radio" name="aval_edit1" class="propio" value="0"/> No</label>
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
<table>
</form>
