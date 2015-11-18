<?php
/*
*/
?>
<?php @session_start(); header('Content-Type: text/html; charset=iso-8859-1'); ?>
<script type="text/javascript" src="js/thickbox.js"></script>
<script>
	$(document).ready(function(){
		$(".elimina").click(function(){
			var res = confirm("¿Eliminar registro permanentemente?");
			if(res){
				var id = $(this).attr("rel");
				$.post("include/php/sys_modelo.php", { action: "lscl_elimina", key: id }, function(data){});
			}else{
				return false;
			}
		});
	});
</script>
<table>
<tbody>
<?php
require_once("../php/sys_db.class.php");
require_once("../conf/Config_con.php");
$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
if($_SESSION["U_NIVEL"] == 0){
	$cobrador = '';
} else {
	$cobrador = "AND clientes.c_cobrador = '".$_SESSION["USERNAME"]."'";
}
if(isset($_POST["consulta"])){
$sql = "SELECT * FROM clientes WHERE (nombre LIKE '%".$_POST["consulta"]."%' OR apellidop LIKE '%".$_POST["consulta"]."%' OR apellidom LIKE '%".$_POST["consulta"]."%') $cobrador";
//echo $sql;
$res = $db->query($sql);
}elseif($_POST["consulta_cuenta"] != ""){
	
$sql = "SELECT cuentas.id, cuentas.cliente AS id, cuentas.estado, clientes.nombre, clientes.apellidop, clientes.apellidom
FROM cuentas, clientes
WHERE (cuentas.id = ".$_POST["consulta_cuenta"]." AND cuentas.cliente = clientes.id AND cuentas.estado = 0)";
$res = $db->query($sql);
$registros = $db->numRows($res);
if ($registros == 0){
	echo "N&uacute;mero de contrato no encontrado";	
	}
	}
while ($ln = $db->fetchNextObject($res))
{
	?>
	<tr>
		<th><?= $ln->nombre." ".($ln->apellidop)." ".($ln->apellidom);?></th>
		<td width="80"><a href="?pg=2e&cl=<?= $ln->id;?>" class="tboton sombra esqRedondas cuenta">Cuenta</a></td>
		<td width="80"><a href="include/html/box_cliente.php?width=500&height=600&cl=<?= $ln->id;?>" class="thickbox tboton sombra esqRedondas detalles">Detalles</a></td>
		<td width="80"><a href="?pg=2b&cl=<?= $ln->id;?>" class="tboton sombra esqRedondas editar">Editar</a></td>
		<td width="80"><a href="include/html/box_cliente_elim.php?width=480&height=250&cl=<?= $ln->id;?>" class="thickbox tboton sombra esqRedondas eliminar" title="Eliminar cliente">Eliminar</a></td>
	</tr>
	<?php
}
?>
</tbody>
</table>
