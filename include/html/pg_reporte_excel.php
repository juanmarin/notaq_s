<?php
/*
*/
?>
<?php
@session_start();
if(isset($_SESSION["U_NIVEL"]) && $_SESSION["U_NIVEL"]==0){
	?>
	<p class="title">Reportes &raquo; Generar reportes en excel</p>
	<table>
	<thead>
	<tr>
	<th colspan="2">Seleccione un reporte para generar un documento de excel</th>
	</tr>
	</thead>
	<tbody>
	<!-- REPORTE DE CLIENTES ACTIVOS -->
	<tr>
	<th>Reporte de clientes activos a la fecha</th>
	<th>
		<form action="xls.php" method="post">
		<input type="hidden" name="generar_reporte" value="1" />
		<input type="submit" name="Descargr       " value="Descargar" />
		</form>
	</th>
	</tr>
	<!-- REPORTE JUAN MARIN  
	<tr>
	<th>Reporte de Juan Marin</th>
	<th>
		<form action="xls.php" method="post">
		<input type="hidden" name="generar_reporte" value="1" />
		<input type="submit" name="Descargr       " value="Descargar" />
		</form>
	</th>
	</tr>
	-->
	</tbody>
	</table>
	<?php
}
?>
