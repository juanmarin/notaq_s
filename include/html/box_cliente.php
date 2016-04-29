<?php
/*
*/
?>
<?php header('Content-Type: text/html; charset=iso-8859-1'); ?>
<style>
	table tbody tr th{text-align: right;}
</style>
<table>
<caption></caption>
<thead>
	<tr>
		<th colspan="2"></th>
	</tr>
</thead>
<tbody>
	<?php
	require_once("../php/sys_db.class.php");
	require_once("../conf/Config_con.php");
	
	function getVivienda($tipo)
	{
		switch($tipo)
		{
			case 1:		echo "Propia";		break;
			case 2:		echo "Renta";		break;
			case 3:		echo "Padres";		break;
			case 4:		echo "otro";		break;
			default:	echo "No definido";	break;
		}
	}
	function getProp($tipo) 
	{
		switch($tipo)
		{
			case 1:		echo "Si";		break;
			case 0:		echo "No";		break;
			default:	echo "No definido";	break;
		}
	}
	$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
	$result = $db->query("SELECT * FROM clientes WHERE id = ".$_GET["cl"]." LIMIT 0, 1");
	while ($ln = $db->fetchNextObject($result))
	{
		$cliente = $ln->id;
		?>
		<tr>
			<th width="195">Nombre: </th><td><?= $ln->nombre." ".$ln->apellidop." ".$ln->apellidom;?></td>
		</tr>
		<tr>
			<th>Direcci&oacute;n: </th><td><?= $ln->direccion.", Col&oacute;nia: ".$ln->colonia;?></td>
		</tr>
		<tr>
			<th>Entrecalles: </th><td><?= $ln->entrecalles;?></td>
		</tr>
		<tr>
			<th>Tel&eacute;fono de casa: </th><td><?= $ln->telefono;?></td>
		</tr>
		<tr>
			<th>Tel&eacute;fono celular: </th><td><?= $ln->celular;?></td>
		</tr>
		
		<tr>
			<th>Vivienda: </th><td><?= getVivienda($ln->vivienda);?></td>
		</tr>
		<tr>
			<th>Empresa: </th><td><?= $ln->empresa;?></td>
		</tr>
		<tr>
			<th>Puesto: </th><td><?= $ln->epuesto;?></td>
		</tr>
		<tr>
			<th>Tel&eacute;fono: </th><td><?= $ln->etelefono;?></td>
		</tr>
		<tr>
			<th>Direcci&oacute;n: </th><td><?= $ln->edireccion .", Colonia: ".$ln->ecolonia;?></td>
		</tr>
		<tr>
			<th>Negocio propio: </th><td><?= getProp($ln->propio);?></td>
		</tr>
		<tr>
			<th>Cliente activo: </th><td><?= getProp($ln->activo);?></td>
		</tr>
		<tr>
			<th>Cobrador: </th><td><?= $ln->c_cobrador;?></td>
		</tr>
		<?php
	}
	?>
</tbody>

<tfoot>
	<tr>
		<th colspan="4"><a href="?pg=2e&cl=<?= $cliente;?>" class="boton">Ver cuenta de cliente</a></th>
	</tr>				
</tfoot>
<table>
