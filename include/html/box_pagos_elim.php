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
		$(".pagoacancelar").each(function(){
			var contador = 0;
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
			if(contador > 0){
				location.reload();
			}
		});
	});
});
</script>
<form action="include/php/sys_modelo.php" method="post">
<table>
<caption>Cancelar Pagos</caption>
<thead>
	<tr>
		<th align="center"># Pago</th>
		<th align="center">Fecha</th>
		<th align="center">F.Pago</th>
		<th align="center">Monto</th>	
		<th align="center">Acciones</th>
	</tr>
</thead>
<tbody>
<?php
	require_once("../php/sys_db.class.php");
	require_once("../conf/Config_con.php");
	$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
	$result = $db->query("SELECT * FROM pagos WHERE estado > 0 AND cuenta = ".$_GET["cta"]);
	$i = 0;
	while ($ln = $db->fetchNextObject($result)){
		$i++;
		?>
		<tr>
		<td align="center"><?php echo $i; ?></td>
		<td align="center"><?php echo date("d-m-Y", strtotime($ln->fecha)); ?></td>
		<td align="center"><?php echo date("d-m-Y", strtotime($ln->fechaPago)); ?></td>
		<td align="center"><?php echo "$".number_format($ln->pago, 2); ?></td>
		<td align="center"><input type="checkbox" name="ids[]" value="<?= $ln->id; ?>" rel="<?= $ln->id; ?>" ref="<?= $ln->pago; ?>" class="pagoacancelar"></td>
		</tr>
		<?php
	}
	?>
</tbody>

<tfoot>
	<tr>
		<th colspan="5">
		<input type="hidden" name="cte" id="cte" value="<?php echo $_GET['cte']; ?>" />
		<input type="hidden" name="cta" id="cta" value="<?php echo $_GET['cta']; ?>" />
		<input type="hidden" name="action" value="pago_elimina" />
		<input type="button" id="cancelar_pagos" value="Cancelar Pago(s)" />
		</th>
	</tr>				
</tfoot>
<table>
</form>
