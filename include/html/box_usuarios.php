<?php header('Content-Type: text/html; charset=iso-8859-1'); ?>
<?php
require_once("../php/sys_db.class.php");
require_once("../conf/Config_con.php");
$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
$sql = "SELECT * FROM prestamos WHERE id = ".$_GET["u"];
$result = $db->query($sql);
$ln = $db->fetchNextObject($result);
?>
<table>
<caption></caption>
<tbody>
	<tr>
		<th>Nombre:</th>
		<td><?php echo $ln->nombre;?></td>
	</tr>
	<tr>
		<th>Puesto:</th>
		<td><?php echo $ln->puesto;?></td>
	</tr>
	<tr>
		<th>Departamento:</th>
		<td><?php echo $ln->departamento;?></td>
	</tr>
	<tr>
		<th>Nivel:</th>
		<td><?php echo $ln->nivel;?></td>
	</tr>
	<tr>
		<th>Correo:</th>
		<td><?php echo $ln->email;?></td>
	</tr>
	<tr>
		<th>Teléfono:</th>
		<td><?php echo $ln->telefono;?></td>
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
		<td><?php echo $ln->username;?></td>
	</tr>
</tbody>
<tfoot>
	<tr>
		<th colspan="2">
			
		</th>
	</tr>
</tfoot>
</table>
