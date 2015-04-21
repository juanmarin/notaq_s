<?php header('Content-Type: text/html; charset=iso-8859-1'); ?>
<style>
	table tbody tr th{text-align: left;}
	table tbody td{text-align: left;}
</style>
<table>
<caption></caption>
<thead>
	<tr>
		<th colspan="4"></th>
	</tr>
</thead>
<tbody>
	<?php
	require_once("../php/sys_db.class.php");
	require_once("../conf/Config_con.php");
	
	$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
	$result = $db->query("SELECT * FROM clientes WHERE id = ".$_GET["cl"]." LIMIT 0, 1");
	while ($ln = $db->fetchNextObject($result))
	{
		$cliente = $ln->id;
		?>
		<tr>
			<th width="155">Avales para: </th><td colspan="3" style="text-align:center;"><?echo $ln->nombre." ".$ln->apellidop." ".$ln->apellidom;?></td>
		</tr>
		<tr>
			<td><strong>Nombre: </strong> </td>
			<td><?echo $ln->R1nombre." ".$ln->R1apellidop." ".$ln->R1apellidom;?> </td>
		
			<td><strong>Direccion: </strong></td>	
			<td><?echo $ln->R1dir;?></td>	
		</tr>
		<tr>
			<td><strong>Colonia: </strong> </td>
			<td><?echo $ln->R1col;?> </td>
		
			<td><strong>Telefono: </strong></td>	
			<td><?echo $ln->R1tel;?></td>	
		</tr>
				
<tr><td colspan="4">&nbsp;</td></tr>
	<tr>
			<td><strong>Nombre: </strong> </td>
			<td><?echo $ln->R2nombre." ".$ln->R2apellidop." ".$ln->R2apellidom;?> </td>
		
			<td><strong>Direccion: </strong></td>	
			<td><?echo $ln->R2dir;?></td>	
		</tr>
		<tr>
			<td><strong>Colonia: </strong> </td>
			<td><?echo $ln->R2col;?> </td>
		
			<td><strong>Telefono: </strong></td>	
			<td><?echo $ln->R2tel;?></td>	
		</tr>
<tr><td colspan="4">&nbsp;</td></tr>
	<tr>
			<td><strong>Nombre: </strong> </td>
			<td><?echo $ln->R3nombre." ".$ln->R3apellidop." ".$ln->R3apellidom;?> </td>
		
			<td><strong>Direccion: </strong></td>	
			<td><?echo $ln->R3dir;?></td>	
		</tr>
		<tr>
			<td><strong>Colonia: </strong> </td>
			<td><?echo $ln->R3col;?> </td>
		
			<td><strong>Telefono: </strong></td>	
			<td><?echo $ln->R3tel;?></td>	
		</tr>

		<?php
	}
	?>
</tbody>

<tfoot>
	<tr>
		<td colspan="2"><a href="?pg=2ba&cl=<?echo $cliente;?>"><b>Agregar/Editar Avales</b></a> </td>	
	</tr>				
</tfoot>
<table>
</form>
