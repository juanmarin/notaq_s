<?php 
	header('Content-Type: text/html; charset=iso-8859-1'); 
?>
<style>
	table tbody tr th td {text-align: center;}
</style>
<script>
$(document).ready(function(){
	$("#cancelar_pagos").click(function(){
		var cliente = $("#cte").val();
		var cuenta  = $("#cta").val();
		var contador = 0;
		//-CANCELANDO PAGOS
		$(".pagoacancelar").each(function(){
			if( $(this).is(":checked") )
			{
				contador++;
				$.post("include/php/sys_modelo.php",{
					action	: "pago_elimina",
					cl	: cliente,
					c	: cuenta,
					pid	: $(this).attr("rel"),
					pago	: $(this).attr("ref")
				}, function(data){
					//alert(data);
				});
			}	
		});
		//-CANCELANDO ABONOS
		$(".abonoacancelar").each(function(){
			if( $(this).is(":checked") )
			{
				contador++;
				$.post("include/php/sys_modelo.php",{
					action	: "abono_elimina",
					cl	: cliente,
					c	: cuenta,
					idabono	: $(this).val(),
					idpago	: $(this).attr("rel"),
					abono	: $(this).attr("ref")
				}, function(data){
					//alert(data);
				});
			}	
		});
		if(contador > 0){
			alert("Los cambios se han realizado correctamente");
			location.reload();
		} else {
			alert("No se seleccionaron pagos ni abonos para cancelar");
		}
	});
});
</script>

<table>
<caption>Cancelar Pagos</caption>
<thead>
	<tr>
		<th align="center"># Pago</th>
		<th align="center">Fecha</th>
		<th align="center">F.Pago</th>
		<th align="center">Monto</th>	
		<th align="center">Aplicado</th>
		<th align="center">Acciones</th>
	</tr>
</thead>
<tbody>
<?php
	require_once("../php/sys_db.class.php");
	require_once("../conf/Config_con.php");
	$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
	$result = $db->query("SELECT * FROM pagos WHERE (estado > 0 AND cuenta = ".$_GET["cta"].") OR id IN(SELECT idpago FROM abono WHERE idcuenta=".$_GET["cta"].") ORDER BY id ASC");
	$i = 0;
	while ($ln = $db->fetchNextObject($result)){
		$res = $db->query("SELECT * FROM abono WHERE idcuenta = ".$_GET["cta"]." AND idpago=".$ln->id);
		while ($ab = $db->fetchNextObject($res)){
			?>
			<tr>
			<td align="center"></td>
			<td align="center"></td>
			<td align="center"><?php echo date("d-m-Y", strtotime($ab->fecha)); ?></td>
			<td align="right"><?php echo "$".number_format($ab->abono, 2); ?></td>
			<td align="right"><?php echo $ab->aplicado_x; ?></td>
			<td align="center"><input type="checkbox" name="ids[]" value="<?= $ab->idabono; ?>" rel="<?= $ln->id; ?>" ref="<?= $ab->abono; ?>" class="abonoacancelar"></td>
			</tr>
			<?php
		}
		$i++;
		$pagoestado=($ln->estado==0)?'style="color:#666;"':'';
		?>
		<tr>
		<td <?=$pagoestado?> align="center"><?php echo $i; ?></td>
		<td <?=$pagoestado?> align="center"><?php echo date("d-m-Y", strtotime($ln->fecha)); ?></td>
		<td <?=$pagoestado?> align="center"><?php echo date("d-m-Y", strtotime($ln->fechaPago)); ?></td>
		<td <?=$pagoestado?> align="right"><?php echo "$".number_format($ln->pago_real, 2); ?></td>
		<td <?=$pagoestado?> align="right"><?php echo $ln->aplicado_x; ?></td>
		<td <?=$pagoestado?> align="center"><?=($ln->estado==0)?'':'<input type="checkbox" name="ids[]" value="'.$ln->id.'" rel="'.$ln->id.'" ref="'.$ln->pago_real.'" class="pagoacancelar">';?></td>
		</tr>
		<?php
	}
	?>
</tbody>
<tfoot>
	<tr>
		<th colspan="6">
		<input type="hidden" name="cte" id="cte" value="<?php echo $_GET['cte']; ?>" />
		<input type="hidden" name="cta" id="cta" value="<?php echo $_GET['cta']; ?>" />
		<input type="button" id="cancelar_pagos" value="Cancelar Pago(s)" />
		</th>
	</tr>				
</tfoot>
</table>
