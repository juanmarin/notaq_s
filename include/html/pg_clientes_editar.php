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
<input type="hidden" name="cl" value="<?= $r->id;?>" />
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
		<input type="text" name="nombre" id="nombre" style="width:400px;" maxlength="25" value="<?= $r->nombre;?>" />
		</td>
		<td>
		Apellido paterno:<br />
		<input type="text" name="apellidop" id="apellidop" maxlength="15" value="<?= $r->apellidop;?>" />
		</td>
	</tr>
	<tr>
		<td>
		Apellido materno:<br />
		<input type="text" name="apellidom" id="apellidom" maxlength="15" value="<?= $r->apellidom;?>" />
		</td>
		<td>
		Dirección:<br />
		<input type="text" name="dir" id="dir" style="width:430px" maxlength="40" value="<?= $r->direccion;?>" />
		</td>
	</tr>
	<tr>
		<td>
		Colonia:<br />
		<input type="text" name="col" id="col" maxlength="30" value="<?= $r->colonia;?>" />
		</td>
		<td>
		Teléfono de casa:<br />
		<input type="text" name="tel" id="tel" maxlength="20" value="<?= $r->telefono;?>" />
		</td>
	</tr>
	<tr>
		<td>
		Teléfono celular:<br />
		<input type="text" name="cel" id="cel" maxlength="20" value="<?= $r->celular;?>" />
		</td>
		<td>
			Sector:<br />
			<select name="sector" id="sector">
				<option value="<?php $r->sector; ?>" selected>Seleccione</option>
				<option value="1">Sector 1</option>
				<option value="2">Sector 2</option>
				<option value="3">Sector 3</option>
				<option value="4">Sector 4</option>
				<option value="5">Sector 5</option>
				<option value="6">Sector 6</option>
			</select>
			</td>
	</tr>
	<tr>
			<td>
				Asignar Cobrador:<br/>
				<?php
		            $sql = "SELECT userID, username FROM mymvcdb_users WHERE nivel = 3";
		            $rs = mysql_query($sql) or die(mysql_error());
		            echo "<select name='cobrador' id='cobrador'>";
		            echo "<option value=''>Asignar</option>";
		            while($row = mysql_fetch_array($rs)){
		            echo "<option value='".$row["username"]."'>".$row["username"]."</option>";
		            }mysql_free_result($rs);
		    	?>
			</select>
			</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2" align="center"><b>Datos Empleo<b/></td>
		</tr>
		<tr>
			<td>Nombre Empleo<br/>
				<input type="text" name="empleo" id="empleo" value="<?php echo $r->empleo;?>" maxlength="25">
			</td>
			<td>Direccion Empleo<br/>
				<input type="text" name="dir_empl" id="dir_empl" value="<?php echo $r->dir_empl; ?>" maxlength="25">
			</td>
		</tr>
		<tr>
			<td>Calle Empleo<br/>
				<input type="text" name="c_empl" id="c_empl" value="<?php echo $r->c_empl; ?>" maxlength="25">
			</td>
			<td>Teléfono Empleo<br/>
				<input type="text" name="tel_empl" id="tel_empl" value="<?php echo $r->tel_empl; ?>"maxlength="25">
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center"><b>Datos Referencias<b/></td>
		</tr>
		<tr>
			<td>Referencia 1<br/>
				<input type="text" name="ref_1" id="ref_1" value="<?php echo $r->nom_ref_1; ?>" maxlength="30">
			</td>
			<td>Referencia 2<br/>
				<input type="text" name="ref_2" id="ref_2" value="<?php echo $r->nom_ref_2; ?>" maxlength="30">
			</td>
		</tr>
		<tr>
			<td>Teléfono Celular<br/>
				<input type="text" name="cel_ref1" id="cel_ref1" value="<?php echo $r->cel_ref1; ?>" maxlength="25">
			</td>
			<td>Teléfono Celular<br/>
				<input type="text" name="cel_ref2" id="cel_ref2" value="<?php echo $r->cel_ref2; ?>" maxlength="25">
			</td>
		</tr>
		<tr>
	<tr>
		<td colspan="1">
		Vivienda:<br />
		<label><input type="radio" name="vivienda" class="vivienda" value="1"<?= ($r->vivienda == 1)?" checked='checked'":"";?> /> Propia</label>
		<label><input type="radio" name="vivienda" class="vivienda" value="2"<?= ($r->vivienda == 2)?" checked='checked'":"";?> /> Renta</label>
		<label><input type="radio" name="vivienda" class="vivienda" value="3"<?= ($r->vivienda == 3)?" checked='checked'":"";?> /> Padres</label>
		<label><input type="radio" name="vivienda" class="vivienda" value="4"<?= ($r->vivienda == 4)?" checked='checked'":"";?> /> Otro</label>
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
