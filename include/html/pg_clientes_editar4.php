<?php
/*
*/
?>
<script>
	$(document).ready(function(){
		
	});
</script>
<p class="title">Clientes &raquo; Editar aval [3/3]</p>

<?php
require_once("include/php/sys_db.class.php");
require_once("include/conf/Config_con.php");
$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
$sql = "SELECT * FROM clientes WHERE id = ".$_GET["cl"];
$res = $db->query($sql);
$r = $db->fetchNextObject($res);
?>
<form action="include/php/sys_modelo.php" method="post">
<input type="hidden" name="action" value="cliente_editar_4" />
<input type="hidden" name="cl" value="<?echo $r->id;?>" />
<table>
<caption>Editar cliente aval 3</caption>
<thead>
	<tr>
		<th colspan="2">&nbsp;</th>
	</tr>
</thead>
<tbody>
	<tr>
		<td>
		Nombre(s):<br />
		<input type="text" name="r3nom" id="r3nom" style="width:400px;" maxlength="25" value="<?echo $r->r3nombre;?>" />
		</td>
		<td>
		Apellido Paterno:<br />
		<input type="text" name="r3app" id="r3app" maxlength="15" value="<?echo $r->r3apellidop;?>" />
		</td>
	</tr>
	<tr>
		<td>
		Apellido Materno:<br />
		<input type="text" name="r3apm" id="r3apm" maxlength="15" value="<?echo $r->r3apellidom;?>" />
		</td>
		<td>
		Direcci&oacute;n:<br />
		<input type="text" name="r3dir" id="r3dir" style="width:350px;" maxlength="40" value="<?echo $r->r3dir;?>" />
		</td>
	</tr>
	<tr>
		<td>
		Colonia:<br />
		<input type="text" name="r3col" id="r3col" maxlength="30" value="<?echo $r->r3col;?>" />
		</td>
		<td>
		Tel&eacute;fono<br />
		<input type="text" name="r3tel" id="r3tel" maxlength="18" value="<?echo $r->r3tel;?>"/>
		</td>
	</tr>
	<tr>
		<td colspan="2">
		Vivienda Propia:<br />
		<label><input type="radio" name="r3vivienda" class="r3vivienda" value="1"<?echo ($r->r3vivienda == 1)?" checked='checked'":"";?> /> Propia</label>
		<label><input type="radio" name="r3vivienda" class="r3vivienda" value="2"<?echo ($r->r3vivienda == 2)?" checked='checked'":"";?> /> Renta</label>
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
