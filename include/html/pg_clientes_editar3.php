<?php
/*
*/
?>
<script>
	$(document).ready(function(){
		
	});
</script>
<p class="title">Clientes &raquo; Editar cliente [3/4]</p>

<?php
require_once("include/php/sys_db.class.php");
require_once("include/conf/Config_con.php");
$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
$sql = "SELECT * FROM clientes WHERE id = ".$_GET["cl"];
$res = $db->query($sql);
$r = $db->fetchNextObject($res);
?>
<form action="include/php/sys_modelo.php" method="post">
<input type="hidden" name="action" value="cliente_editar_3" />
<input type="hidden" name="cl" value="<?echo $r->id;?>" />
<table>
<caption>Editar cliente aval 2</caption>
<thead>
	<tr>
		<th colspan="2">&nbsp;</th>
	</tr>
</thead>
<tbody>
	<tr>
		<td>
		Nombre(s):<br />
		<input type="text" name="r2nom" id="r2nom" style="width:400px;" maxlength="25" value="<?echo $r->R2nombre;?>" />
		</td>
		<td>
		Apellido Paterno:<br />
		<input type="text" name="r2app" id="r2app" maxlength="15" value="<?echo $r->R2apellidop;?>" />
		</td>
	</tr>
	<tr>
		<td>
		Apellido Materno:<br />
		<input type="text" name="r2apm" id="r2apm" maxlength="15" value="<?echo $r->R2apellidom;?>" />
		</td>
		<td>
		Direcci&oacute;n:<br />
		<input type="text" name="r2dir" id="r2dir" style="width:350px;" maxlength="40" value="<?echo $r->R2dir;?>" />
		</td>
	</tr>
	<tr>
		<td>
		Colonia:<br />
		<input type="text" name="r2col" id="r2col" maxlength="30" value="<?echo $r->R2col;?>" />
		</td>
		<td>
		Tel&eacute;fono<br />
		<input type="text" name="r2tel" id="r2tel" maxlength="18" value="<?echo $r->R2tel;?>"/>
		</td>
	</tr>
	<tr>
		<td colspan="1">
		Vivienda Propia:<br />
		<label><input type="radio" name="r2vivienda" class="r2vivienda" value="1"<?echo ($r->vivienda == 1)?" checked='checked'":"";?> /> Propia</label>
		<label><input type="radio" name="r2vivienda" class="r2vivienda" value="2"<?echo ($r->vivienda == 2)?" checked='checked'":"";?> /> Renta</label>
		</td>
		<td colspan="1">
		Editar Tercer Aval:<br />
		<label><input type="radio" name="aval_edit3" class="propio" value="1"/> Si</label>
		<label><input type="radio" name="aval_edit3" class="propio" value="0"/> No</label>
		</td>
	</tr>
</tbody>
<tfoot>
	<tr>
		<th colspan="4">
		<input type="submit" name="referencias1" id="referencias1" value="Siguiente &raquo" />
		</th>
	</tr>				
</tfoot>
<table>
