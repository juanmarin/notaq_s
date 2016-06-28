<?php 
	header('Content-Type: text/html; charset=iso-8859-1'); 
?>
<style>
	table tbody tr th td {text-align: center;}
</style>
<script>
$(document).ready(function(){
	$("#condonar_recargo").click(function(){
		var contador = 0;
		//-CONDONANDO RECARGOS
		$(".condonarecargo").each(function(){
			if( $(this).is(":checked") )
			{
				contador++;
				$.post("include/php/sys_modelo.php",{
					action	: "recargo_condonar",
					idr	: $(this).val()
				}, function(data){
					//alert(data);
				});
			}	
		});
		if(contador > 0){
			alert("Los cambios se han realizado correctamente");
			location.reload();
		} else {
			alert("No se seleccionaron recargos para condonar");
		}
	});
});
</script>

<table>
<caption>Condonar Recargos</caption>
<thead>
	<tr>
		<th align="center">#</th>
		<th align="center">Fecha</th>
		<th align="center">D. Venc.</th>
		<th align="center">Monto</th>
		<th align="center">Acciones</th>
	</tr>
</thead>
<tbody>
<?php
	require_once("../php/sys_db.class.php");
	require_once("../conf/Config_con.php");
	$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
	$result = $db->query("SELECT * FROM recargos WHERE estado = 0 AND cuenta = ".$_GET["cta"]);
	echo $db->query;
	$i = 0;
	while ($ln = $db->fetchNextObject($result)){
		$i++;
?>

	<tr>
	<td align="center"><?php echo $i; ?></td>
	<td align="center"><?php echo date("d-m-Y", strtotime($ln->pago)); ?></td>
        <td align="center"><?php echo $ln->dias_atraso; ?></td>
        <td align="center"><?php echo "$".number_format($ln->monto, 2); ?></td>
        <td align="center"><input type="checkbox" name="ids[]" value="<?= $ln->id; ?>" class="condonarecargo"></td>
	</tr>
	<?php
	}
	?>
</tbody>

<tfoot>
	<tr>
		<th colspan="5">
		<input type="submit" id="condonar_recargo" value="Condonar Recargo(s)" />
		</th>
	</tr>				
</tfoot>
</table>
