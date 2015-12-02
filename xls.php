<?php
/*
*/
$hoy 		= date("d-m-Y");
@session_start();
if(isset($_SESSION["U_NIVEL"]) && $_SESSION["U_NIVEL"]==0 && isset($_POST)){
	header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
	header("Content-type:   application/x-msexcel; charset=utf-8");
	header("Content-Disposition: attachment; filename=".Cuentas_activas."".$hoy.".xls"); 
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);

	require_once("include/php/sys_db.class.php");
	require_once("include/conf/Config.php");
	function genTabla( $exe, $mostrar_num_de_filas = 1 )
	{
		if($exe){if(mysql_num_rows($exe)>0){$n=0;echo'<table class="formato">';while($r=mysql_fetch_array($exe)){if($n==0){echo'<thead><tr>';
		if($mostrar_num_de_filas==1){echo'<th>#</th>';}$c=1;foreach($r as $var => $value){if(($c%2)==0){echo'<th>'.$var.'</th>';
		}$c++;}echo'</tr></thead>';}$n++;echo'<tbody><tr>';if($mostrar_num_de_filas == 1){echo'<td>'.$n.'</td>';}$c=1;
		foreach($r as $var => $value){if(($c%2)==0){echo('<td>'.$value.'</td>');}$c++;}echo'</tr></tbody>';}echo'</table>';}else{
		echo'<table class="noprint"><caption style="text-align:left;">La consulta devolvió 0 registros</caption></table>';}}else{
		echo'<table class="noprint"><caption style="text-align:left;">El arreglo de datos esta vacío.</caption></table>';}
	}
	switch($_POST["generar_reporte"])
	{
		case 0:	# GENERAR REPORTE DE CLIENTES ACTIVOS
			$reporte="
			SELECT clientes.id, clientes.nombre, clientes.apellidop, clientes.apellidom, cuentas.total 
			FROM clientes, cuentas 
			WHERE clientes.id = cuentas.cliente AND cuentas.estado = 0 
			ORDER BY clientes.nombre ASC
			";
			break;
		case 1 :
			$reporte="
			SELECT 
				 cu.fecha 'Fecha préstamo'
				,cu.id 'Contrato'
				,concat(cl.apellidop,' ',cl.apellidom,' ',cl.nombre) 'Nombre del cliente'
				,cl.colonia 'Colonia'
				,cl.direccion 'Calle'
				,'Dato no existe' as 'Entre calles domicilio'
				,cl.telefono 'Telefono domicilio directo'
				,'Dato no existe' as 'Tel. empleo'
				,cl.celular 'Tel. celuar'
				,cl.sector  'Sector'
				,cu.total 'Total adeudo'
				,(SELECT SUM(pa.pago) FROM pagos pa WHERE pa.cuenta = cu.id AND pa.estado=0 AND pa.fecha < CURDATE()) AS 'Total vencido en pagos'
				/* ** ----------------------------------------------------------------------------------------------------------------------------------------- ** */
				/* ** REGARCOG SPOR RETRAZO: ------------------------------------------------------------------------------------------------------------------	** */
				/* ** Faltaría checar el estado del recargo, que significan ----------------------------------------------------------------------------------- ** */
				/* ** y si están bien actualizados los datos del recargo -------------------------------------------------------------------------------------- ** */
				,(SELECT SUM(MONTO) FROM recargos re WHERE re.cuenta = cu.id) AS 'Recargos por retrazo'
				,case cu.tipo_pago
					when 1 then 'SEMANAL'
					when 2 then 'CATORCENAL'
					when 3 then 'QUINCENAL'
					when 4 then 'MENSUAL'
					else 'NO DEFINIDO'
				end AS 'Frecuencia de pago'
				,cu.npagos 'Plazo'
				,(SELECT COUNT(*)+1 FROM pagos pa WHERE pa.cuenta = cu.id AND pa.estado=1) AS 'Pago vencido'
				,cu.npagos 'Plazo préstamo'
				,(SELECT COUNT(*) FROM pagos pa WHERE pa.cuenta = cu.id AND pa.estado=0 AND pa.fecha < CURDATE()) AS 'Pagos vencidos'
				,cl.c_cobrador 'Gestor'
				,co.nombre 'Nombre gestor'
				/* ** ----------------------------------------------------------------------------------------------------------------------------------------- ** */
				/* ** DIAS VENCIDOS: -------------------------------------------------------------------------------------------------------------------------- ** */
				/* ** Faltaría validar si estan vencidos los días para evitar salga un número negativo -------------------------------------------------------- ** */
				,(SELECT DATEDIFF(CURDATE(),pa.fecha) FROM pagos pa WHERE pa.cuenta = cu.id AND pa.estado=0 AND pa.fecha < CURDATE() ORDER BY pa.fecha ASC LIMIT 0,1) AS 'Dias vencidos'
				,(SELECT pa.fecha FROM pagos pa WHERE pa.cuenta = cu.id AND pa.estado=1 ORDER BY pa.fecha DESC LIMIT 0,1) AS 'Fecha ultimo pago'
				,'Dato no existe' AS 'Colonia empleo'
				,'Dato no existe' AS 'Nombre de empresa'
				,cl.nom_ref_1 'Referencia 1'
				,cl.cel_ref1 'Tel. referencia 1'
				,cl.nom_ref_2 'Referencia 2'
				,cl.cel_ref2 'Tel. referencia 2'
			FROM clientes cl 
				RIGHT JOIN cuentas cu ON cl.id=cu.cliente
				LEFT JOIN mymvcdb_users co ON cl.c_cobrador=co.username
			WHERE cu.estado=0
			ORDER BY cu.fecha DESC;
			";
			break;
	}
	$res = mysql_query($reporte);
	genTabla( $res, 0 );
}
?>
