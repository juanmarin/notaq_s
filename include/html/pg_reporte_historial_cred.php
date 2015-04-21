<?php
 $fecha = date('Y-m-d');
 #$fecha = "2011-07-06";
?>
<p class="title">Reportes &raquo; Historial crediticio</p>
<table>
<caption>LISTADO DE CLIENTES CON HISTORIAL CREDITICIO</caption>
<thead>
	<tr>
      <th>NOMBRE</th>
      <th>CUENTAS CERRADAS</th>
      <th></th>
	</tr>
</thead>
<tbody>
	<?php
	require_once("include/php/sys_db.class.php");
	require_once("include/conf/Config_con.php");
	$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
	$sql = "SELECT clientes.id, nombre, apellidop, apellidom, count(clientes.id) as cuentas FROM clientes, cuentas WHERE clientes.id=cuentas.cliente AND cuentas.estado>0 GROUP BY clientes.id";
	$result = $db->query($sql);
	$regs =  mysql_num_rows($result);
	while ($ln = $db->fetchNextObject($result)){
		echo '<tr>';
		echo '	<td>'.$ln->nombre.' '.$ln->apellidom.' '.$ln->apellidop.'</td>';
		echo '	<td align="center">'.$ln->cuentas.'</td>';
		echo '	<td><a href="?pg=3da&cl='.$ln->id.'" class="boton esqRedondas sombra">Historial</a></td>';
		echo '</tr>';   
	}
	$db->close(); 
	?>
   </tbody>

<tfoot>
	<tr>
		<th colspan="3"></th>
	</tr>
</tfoot>
<table>