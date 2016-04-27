<?php
@session_start();
//header('Content-Type: text/html; charset=UTF-8');
$UserName = $_SESSION["USERNAME"];
$UserLevel = $_SESSION["U_NIVEL"];
?>
<p class="title">Portada &raquo; Cuadro de Avance</p>
<?php
if ($UserLevel == 0) {
	$clcobrador = "";
	?>
	<table>
	<caption>REPORTE DE COBRADORES</caption>
	<thead>
		<tr>
			<th>COBRADOR</th>
			<th>USUARIO</th>
			<th>ASIG</th>
			<th>CTE</th>
			<th>VENC</th>
			<th>TOTAL %</th>
		</tr>
	</thead>
	<tbody>
	<?php
	//// ESTADISTICOS DE COBRADORES///
	require_once("include/php/sys_db.class.php");
	require_once("include/php/fun_global.php");
	require_once("include/conf/Config_con.php");
	$fecha = date("Y-m-d");
	$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
	$sql = "SELECT mymvcdb_users.username AS cobrador, mymvcdb_users.nombre, clientes.id, COUNT(clientes.c_cobrador) AS mis_ctes, cuentas.cliente, cuentas.estado
		FROM mymvcdb_users,clientes,cuentas
		WHERE mymvcdb_users.username = clientes.c_cobrador
		AND clientes.id = cuentas.cliente
		AND cuentas.estado = 0
		GROUP BY mymvcdb_users.username";
	$res = $db->query($sql);
	$tot_asi 	= 0;
	$tot_corr 	= 0;
	$tot_mor 	= 0;	
	$tot_avance 	= 0;
	while($cob=$db->fetchNextObject($res))
	{
		?>
		<tr>
			<td style="font-size:small;"><?php echo $cob->nombre;?></td>
			<td style="font-size:small;"><?php echo $cob->cobrador;?></td>
			<td style="font-size:small;" align="center"> <?php echo $cob->mis_ctes; ?></td>
			<?php
			/// ***** ****** OBTENIEDO LOS CLIENTES CON PAGOS VENCIDOS 
			$sql2="SELECT clientes.id, cuentas.cliente, clientes.c_cobrador, 
			cuentas.cobrador, cuentas.estado, 
			pagos.cuenta, pagos.cliente, pagos.fecha, pagos.estado
			FROM clientes, cuentas, pagos 
			WHERE
				clientes.id = cuentas.cliente
				AND cuentas.id = pagos.cuenta 
				AND cuentas.estado = 0 
				AND pagos.estado = 0 
				AND pagos.fecha < '".$fecha."'
				AND clientes.c_cobrador='".$cob->cobrador."'
				GROUP BY pagos.cliente";
			//echo $sql2 . "<br />";
			$res2 = $db->query($sql2);
			$morosos = mysql_num_rows($res2);
			//--
			$corriente	= $cob->mis_ctes-$morosos;
			$avance		= ($corriente/$cob->mis_ctes)*100;
			$tot_asi	+= $cob->mis_ctes;
			$tot_corr	+= $corriente;
			$tot_mor	+= $morosos;	
			$tot_avance	= ($tot_corr/$tot_asi)*100;
			?>
		<td style="background-color: #7DB77B; font-size:small" align="center"><?php echo $corriente;?></td>
		<td style="background-color: #F78181; font-size:small" align="center"><?php echo $morosos;?></td>
		<td style="font-size:small;" align="center"><?php echo number_format($avance, 2)."%";?></td>
		</tr>
	    	<?php
	}
	?>
	<tr style="font-weight: bold;">
		<td colspan="2">Totales</td>
		<td align="center"><?php echo $tot_asi ?></td>
		<td style="background-color: #7DB77B"align="center"><?php echo $tot_corr;?></td>
		<td style="background-color: #F78181;" align="center"><?php echo $tot_mor;?></td>
		<td align="center"><?php echo number_format($tot_avance, 2)."%";?></td>
	</tr>
</tbody>
</table>
<br />

<?php
#-LINEA DE AVANCE POR COBRADOR -- ---------------------------------------------------------------------------------------------------------------------------------------
require_once("include/html/pg_sys_principal_lineacobradores.php"); ///- cargando contenido

?>

<br />
	<script>
	$("#filtromapas").change(function(){
		var ruta = "include/html/pg_cliente_cuenta_mapa_marcas.php?nada=0";
		if( $("#filtromapas").val() != "" ){
			ruta += "&marks="+$("#filtromapas").val();
		}
		if( $("#mapacobrador").val() != "" ){
			ruta += "&cobrador="+$("#mapacobrador").val();
		}
		
		$("#mapa").attr("src",ruta);
	});
	
	$("#mapacobrador").change(function(){
		var ruta = "include/html/pg_cliente_cuenta_mapa_marcas.php?nada=0";
		if( $("#filtromapas").val() != "" ){
			ruta += "&marks="+$("#filtromapas").val();
		}
		if( $("#mapacobrador").val() != "" ){
			ruta += "&cobrador="+$("#mapacobrador").val();
		}
		$("#mapa").attr("src",ruta);
	});
	</script>
	<table class="formato">
	<caption>Localización geogr&aacute;fica</caption>
	<thead>
		<tr>
			<th>Mueva el marcador para cambiar la localizaci&oacute;n del cliente.</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>
	 		Filtrar marcadores:  
	 		<select name="filtromapas" id="filtromapas">
				<option value="">Mostrar todos</option>
				<option value="6" style="background:#4dd2ff;">CLIENTES AL CORRIENTE</option>
				<option value="5" style="background:#0052cc;">CLIENTES DEL DÍA DE HOY</option>
				<option value="1" style="background:#4acc66;">CLIENTES DE 0 A 7 DÍAS VENCIDOS</option>
				<option value="2" style="background:#f3ce2e;">CLIENTES DE 8 A 30 DÍAS VENCIDOS</option>
				<option value="3" style="background:#ce1818;">CLIENTES DE 31 A 60 DÍAS VENCIDOS</option>
				<option value="4" style="background:#a020f0;">CLIENTES DE MAS DE 61 DÍAS VENCIDOS</option>
	 		</select>
	 		</td>
	 	</tr>
	 	<?php
	 		if ($UserLevel == 0) {
	 	?>
		<tr>
	 		<td>
	 		Filtrar Cobrador: &nbsp; &nbsp;
				<?php
		            $sql = "SELECT userID, username FROM mymvcdb_users WHERE username!='jmarincastro' ORDER BY username";
		            $rs = mysql_query($sql) or die(mysql_error());
		            echo "<select name='cobrador' id='mapacobrador'>";
		            echo "<option value=''>Mostar todos</option>";
		            while($row = mysql_fetch_array($rs)){
		            echo "<option value='".$row["username"]."'>".$row["username"]."</option>";
		            }mysql_free_result($rs);
		    	?>
			</select>
	 		</td>
		</tr>
		<?php
			}
		?>
		<tr>
			<td>
	 		<iframe name="mapa" id="mapa" src="include/html/pg_cliente_cuenta_mapa_marcas.php" style="width:100%;border:0px;min-width:400px;height:420px;border:none;"></iframe> 
	 		</td>
		</tr>
	</tbody>
	<tfoot>
		<tr><th></th></tr>
	</tfoot>
	</table>
<br/>
<br/>
<!-- REPORTE DE PUNTUALIDAD POR COBRADOR -->
<?php
#FORMULARIO PARA GENERAR REPROTE DE DESEMPEÑO
?>
<form action="" method="post">
	<table class="table">
	<caption>Generar reporte de desempe&nacute;o</caption>
	<tbody>
		<tr>
		<td>Seleccionar rango de fechas:</td>
		</tr>
		<tr>
		<td>Desde: <input type="text" name="fi" class="dpfecha" value="<?=$_POST['fi'];?>" /></td>
		</tr>
		<tr>
		<td>Hasta: <input type="text" name="ff" class="dpfecha" value="<?=date('Y-m-d')?>" /></td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
		<td><input type="submit" name="desempxtiempo" value="Generar reporte" /></td>
		</tr>
	</tfoot>
</table>
</form>
<?php
if(isset($_POST["desempxtiempo"]))
{
	#- REPORTE DE DESEMPEÑO POR RANGO DE FECHAS
	echo "<br />";
	include_once "include/html/pg_sys_principal_rdesempeno.php";
	#- REPORTE DE DESEMPEÑO MONETARIO POR RANGO DE FECHAS
	echo "<br />";
	include_once "include/html/pg_sys_principal_rdesempeno_monetario.php";
}
} else {
	$clcobrador = "AND c_cobrador = '$UserName'";
	#-LINEA DE AVANCE POR COBRADOR -- ---------------------------------------------------------------------------------------------------------------------------------------
	require_once("include/html/pg_sys_principal_lineacobradores.php"); ///- cargando contenido
	?>
	<br />
	<table>
	<caption>COBRADOR: &nbsp; <b><?php echo $user->userData[6];?></b></caption>
	<thead>
		<tr>
			<th>C. ASIGNADOS</th>
			<th>C. CORRIENTE</th>
			<th>C. VENCIDOS</th>
			<th>TOTAL AVANCE %</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$fecha = date("Y-m-d");
		require_once("include/php/sys_db.class.php");
		require_once("include/php/fun_global.php");
		require_once("include/conf/Config_con.php");
		$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
		#Buscando los clientes asignados al cobrador con cuentas abiertas
		$sql = "SELECT clientes.id, clientes.activo, clientes.c_cobrador, cuentas.cliente, cuentas.estado 
		FROM clientes, cuentas 
		WHERE activo = 1
		$clcobrador
		AND clientes.id = cuentas.cliente 
		AND cuentas.estado = 0";
		$res = $db->query($sql);
		$mis_ctes = mysql_num_rows($res);
		#Buscando el total de clientes Morosos
		$sql = "SELECT clientes.id, clientes.demanda, cuentas.cliente, clientes.c_cobrador, 
		cuentas.cobrador, cuentas.estado, 
		pagos.cuenta, pagos.cliente, pagos.fecha, pagos.estado
		FROM clientes, cuentas, pagos 
		WHERE
		clientes.id = cuentas.cliente 
		AND clientes.demanda != 1 
		AND cuentas.id = pagos.cuenta 
		AND cuentas.estado = 0 
		AND pagos.estado = 0 
		AND pagos.fecha < '".$fecha."'
		$clcobrador
		GROUP BY pagos.cliente 
		ORDER BY clientes.nombre ASC";
		$res = $db->query($sql);
		$mis_morosos = mysql_num_rows($res);
		$mis_corriente = ($mis_ctes - $mis_morosos);
		$avance = ($mis_corriente/$mis_ctes)*100;
		?>
		<tr>
			<td style="text-align:center"> <?php echo $mis_ctes;?></td>
			<td style="background-color: #7DB77B"align="center"><?php echo $mis_corriente;?></td>
			<td style="background-color: #F78181;" align="center"><?php echo $mis_morosos;?></td>
			<td align="right"><?php echo number_format($avance, 2)."%";?></td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<th colspan="4"></th>
		</tr>
	</tfoot>
	</table>
	<br/>
	<br/>
	<table>
	<caption>COBROS PARA EL DIA :<b><?php echo date("d-m-Y", strtotime($fecha)); ?></b> </caption>
	<thead>
		<tr>
			<th>C. POR COBRAR</th>
			<th>C. REALIZADOS</th>
			<th>AVANCE DEL DIA %</th>
		</tr>
	</thead>
	<tbody>
	<?php
		#Buscando los clientes asignados al cobrador
		require_once("include/php/sys_db.class.php");
		require_once("include/php/fun_global.php");
		require_once("include/conf/Config_con.php");
		$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
		#Buscando el total de clientes a visitar hoy
		$sql = "SELECT clientes.id, clientes.c_cobrador, pagos.id, pagos.cliente, pagos.cuenta, pagos.fecha, pagos.estado 
		FROM clientes, pagos 
		WHERE clientes.id = pagos.cliente 
		AND pagos.fecha = '".$fecha."' 
		AND pagos.estado = 0
		$clcobrador";
		$res = $db->query($sql);
		$x_visitar = mysql_num_rows($res);
		#Buscando el total de clientes a visitados hoy
		$sql = "SELECT clientes.id, clientes.c_cobrador, pagos.id, pagos.cliente, pagos.cuenta, pagos.fecha, pagos.estado 
		FROM clientes, pagos 
		WHERE clientes.id = pagos.cliente 
		AND pagos.fecha = '".$fecha."' 
		AND pagos.fechaPago = '".$fecha."' 
		AND pagos.estado = 1
		$clcobrador";
		$res = $db->query($sql);
		$visitados = mysql_num_rows($res);
		$avanced = ($visitados/($x_visitar+$visitados))*(100);
		$tbl_color = semaforo(number_format($avanced, 2));
		?>
		<tr style="font-weight: bold;">
			<td style="text-align:center"> <?php echo $x_visitar;?></td>
			<td style="background-color: "align="center"><?php echo $visitados;?></td>
			<td style="background-color:<?php echo $tbl_color;?>" align="right"><?php echo number_format($avanced, 2)."%";?></td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<th colspan="4"></th>
		</tr>
	</tfoot>
	</table>
	<br />
	<script>
	$("#filtromapas").change(function(){
		$("#mapa").attr("src",$(this).val());
	});
	</script>
	<table class="formato">
	<caption>Localización geográfica</caption>
	<thead>
		<tr>
			<th>Mueva el marcador para cambiar la localización del cliente.</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>
	 		Filtrar marcadores: 
	 		<select name="filtromapas" id="filtromapas">
				<option value="include/html/pg_cliente_cuenta_mapa_marcas.php">Mostrar todos</option>
				<option value="5" style="background:#0066CC;">CLIENTES DEL DÍA DE HOY</option>
				<option value="1" style="background:#4acc66;">CLIENTES DE 0 A 7 DÍAS VENCIDOS</option>
				<option value="2" style="background:#f3ce2e;">CLIENTES DE 8 A 30 DÍAS VENCIDOS</option>
				<option value="3" style="background:#ce1818;">CLIENTES DE 31 A 60 DÍAS VENCIDOS</option>
				<option value="4" style="background:#a020f0;">CLIENTES DE MAS DE 61 DÍAS VENCIDOS</option>
	 		</select>
	 		</td>
		</tr>
		<tr>
			<td>
	 		<iframe name="mapa" id="mapa" src="include/html/pg_cliente_cuenta_mapa_marcas.php" style="width:100%;border:0px;min-width:400px;height:420px;border:none;"></iframe> 
	 		</td>
		</tr>
	</tbody>
	<tfoot>
		<tr><th>&nbsp;</th></tr>
	</tfoot>
	</table>
<br/>
<?php 
}
#MOSTRANDO TABLA DE DESEMPEñoO ---
$cobrador=($UserLevel>1)?"AND cobrador='$UserName'":"";
$semana = 
$sql = "SELECT * FROM desempeno WHERE year=".date("Y")." AND semana>".(date("W")-5)." $cobrador ORDER BY semana DESC, cobrador ASC";
$res = $db->query($sql);
$sem=0;
$con=0;
$separador="";
$fondo="";
while($d=$db->fetchNextObject($res))
{
	if($sem!=$d->semana)
	{
		$sem=$d->semana;
		$separador="</tbody></table><br />";
		if($con>=0){echo $separador;}
		echo"
		<table>
		<caption>Desempeño semanal (Semana actual: $sem)</caption>
		<thead>
			<tr>
				<th>SEMANA</th>
				<th>COBRADOR</th>
				<th>TOTAL</th>
				<th>EN FECHA</th>
				<th>F. FECHA</th>
				<th>P. COBRAR</th>
				<th>AVANCE</th>";
				if ($UserName == "jmarincastro" || $UserName == "francisco") {
			?>
				<th>EFE</th>
				<th>T. PAGOS</th>
				<th>REC.COB.</th>
				<th>%.REC.</th>
				<th>T.PAGAR</th>
			<?php
		}
		echo"	</tr>
		</thead>
		<tbody>";
		$con++;
	}
	echo"
	<tr>
		<td style='font-size:small;' align='center'>".$d->semana."</td>
		<td>".$d->cobrador."</td>
		<td align='center'>".$d->total."</td>
		<td align='center'>".$d->en_fecha."</td>
		<td align='center'>".$d->fuera_fecha."</td>
		<td align='center'>".$d->por_cobrar."</td>
		<td align='right'>".number_format(((($d->en_fecha+$d->fuera_fecha)/$d->total)*100),2,".",",")." %</td>";
		if ($UserName == "jmarincastro" || $UserName == "francisco") {
		$sql1 = "SELECT * FROM cuadroavance WHERE week = ".$sem." AND cobrador = '".$d->cobrador."'";
		$res1 = $db->query($sql1);
		while ($d1=$db->fetchNextObject($res1)){
			?>
			<td <?php echo $fondo; ?> align='right'><?php echo number_format($d1->porcentaje,2,".",",");?> %</td>
			<td <?php echo $fondo; ?> style='background-color: #F3F781' align='right'><?php echo $pagaPagos = pagaCobrador($d1->porcentaje, $d->en_fecha);?> </td>
			<td <?php echo $fondo; ?> align='right'><?php echo number_format($d1->recargoscobrados,2,".",",");?> </td>
			<td <?php echo $fondo; ?> style='background-color: #F3F781' align='right'><?php echo $pag_rec =number_format(pagaRecargos($d1->recargoscobrados),2,".",",");?> </td>
			<td <?php echo $fondo; ?> style='background-color:#4acc66' align='right'><?php echo number_format($pag_rec+$pagaPagos,2,".",",");?> </td>
		<?php
		}
	}
	echo "</tr>";
}
echo "<br />";
#### Cargando lista de cobranza diaria solo para cobradores
if ($UserLevel == 3) {
	require_once("include/html/pg_sys_principal_cobranza_diaria.php");

	}	
?>
</tbody>
</table>
