<script>
	$(document).ready(function(){
		$("#nombre").focus();
		$("#actualiza_ud").click(function(){
			//alert($("#user").text());
			$.post("include/php/sys_modelo.php", {
				action: "admin_infouser_actualiza",
				id: $("#user").text(),
				nombre: $("#nombre").val(),
				puesto: $("#puesto").val(),
				dep: $("#dep").val(),
				tel: $("#tel").val(),
				email: $("#email").val(),
				usr: $("#usr").val(),
				con: $("#con").val()
			}, function(data){
				$("#ALERTAS").html(data);
				location.reload();
			});
		});
	});
</script>
<h3>Información de usuario.</h3>
<?php
	require_once("include/php/fun_global.php"); 
	require_once("include/php/sys_db.class.php");
	require_once("include/conf/Config_con.php");
?>
<table>
	<tbody>
		<?php
			$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
			$result = $db->query("SELECT * FROM mymvcdb_users WHERE userID = ". $user->userData[0] ." LIMIT 1");
			$sin_leer = $db->numRows($result);
			$i=0;
			while ($line = $db->fetchNextObject($result))
				{
				?>
				<tr>
					<th align="right" width="150">Id</th><td id="user"><?php echo $line->userID; ?></td>
				</tr>
				<tr>
					<th align="right">Nombre</th><td><input type="text" name="nombre" id="nombre" value="<?php echo $line->nombre; ?>" /></td>				
				</tr>
				<tr>
					<th align="right">Puesto</th><td><input type="text" name="puesto" id="puesto" value="<?php echo $line->puesto; ?>" /></td>
				</tr>
				<tr>
					<th align="right">Departamento</th><td><input type="text" name="dep" id="dep" value="<?php echo $line->departamento; ?>" /></td>
				</tr>
				<tr>
					<th align="right">Teléfono</th><td><input type="text" name="tel" id="tel" value="<?php echo $line->telefono; ?>" /></td>
				</tr>
				<tr>
					<th align="right">E-mail</th><td><input type="text" name="email" id="email" value="<?php echo $line->email; ?>" /></td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<th align="right">Usuario</th><td><input type="text" name="usr" id="usr" value="<?php echo $line->username; ?>" /></td>
				</tr>
				<tr>
					<th align="right">Contraseña</th><td><input type="password" name="con" id="con" /></td>
				</tr>
				<?php
				}
			$db->close();
		?>
		</tbody>
		<tfoot>
			<th colspan="2"><input type="button" id="actualiza_ud" value="Guardar" /></th>
		</tfoot>
</table>