<?php header('Content-Type: text/html; charset=iso-8859-1'); ?>
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
$res = $db->query("SELECT * FROM clientes WHERE nombre LIKE '%".$_POST["consulta"]."%' OR apellidop LIKE '%".$_POST["consulta"]."%' OR apellidom LIKE '%".$_POST["consulta"]."%'");
while ($ln = $db->fetchNextObject($res))
{
	?>
	<tr>
		<th><?echo $ln->nombre." ".($ln->apellidop)." ".($ln->apellidom);?></th>
		<td width="80"><a href="?pg=2e&cl=<?echo $ln->id;?>" class="tboton sombra esqRedondas cuenta">Cuenta</a></td>
		<td width="80"><a href="include/html/box_cliente.php?width=500&height=600&cl=<?echo $ln->id;?>" class="thickbox tboton sombra esqRedondas detalles">Detalles</a></td>
		<td width="80"><a href="?pg=2b&cl=<?echo $ln->id;?>" class="tboton sombra esqRedondas editar">Editar</a></td>
		<td width="80"><a href="include/html/box_cliente_elim.php?width=480&height=250&cl=<?echo $ln->id;?>" class="thickbox tboton sombra esqRedondas eliminar" title="Eliminar cliente">Eliminar</a></td>
	</tr>
	<?
}
?>
</tbody>
</table>
