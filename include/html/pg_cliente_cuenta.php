<script>
$("#tipo_pago").change(function(){
	if($(this).val() == 4){
		$("#dias_pago").css("display", "none");
	}else{
		$("#dias_pago").css("display", "inline");
		$.post("include/php/get_diasPago.php", {tp:$(this).val(),fecha:$("#fechapp").val()}, function(data){
			$("#dias_pago").html(data);
		});
	}
});
$("#fechapp").change(function(){
	fecha = $(this).val();
	//alert(fecha);
	a = fecha.substring(0, 4);
	m = fecha.substring(5, 7);
	mes = m - 1;
	d = fecha.substring(8, 10);
	fecha = new Date(a, mes, d);
	dia = fecha.getDay();
	//alert(dia + ' // ' + m);
	if(dia > 7){
		alert("Dia de la semana invalido.\n Seleccione otro día.");
	}else{
		$("#dias_pago option").removeAttr("selected");
		$("#dias_pago option[value="+dia+"]").attr("selected", "selected");
	}
	if(fecha < "<?php echo date('Y-m-d');?>"){
		alert("No puede seleccionar fechas anteriores para el primer pago");
	}
});
$("#dias_pago").change(function(){
	$.post("include/php/get_fechapp.php", {dp:$(this).val(),fecha:$("#fechapp").val(), tp:$("#tipo_pago").val() }, function(data){
		if(data == ""){
			alert("Fecha incorrecta.\nLa fecha del primer pago no coincide con las fechas seleccionadas.");
		}else{
			$("#fechapp").val(data);
		}
	});
});
$("#EditarCuenta").click(function(){
	ncuenta=$(this).attr("rel");
	$.post("include/php/set_editarCuenta.php", {editarCuenta:ncuenta}, function(data){
		location.reload('?pg=2e&cl='+ncuenta); 
	});
	return false;
});
$("#CancelarEditarCuenta").click(function(){
	ncuenta=$(this).attr("rel");
	$.post("include/php/unset_editarCuenta.php", {editarCuenta:ncuenta}, function(data){
		location.reload('?pg=2e&cl='+ncuenta); 
	});
	return false;
});
$(".validarpago").keyup(function(){
	var pago = $(this).attr("rel");
	if( parseInt($(this).val()) > parseInt(pago) ){
		alert("No se permiten cantidades mayores ("+$(this).val()+") al pago asignado ("+pago+").\nAun se aceptan pagos adelantados en el siguiente pago.");
		$(this).val(pago);
	}
});
$("#mapacliente").click(function(){
	if($("#mapacontenedor").text() == ""){
		$.post("include/html/pg_cliente_cuenta_mapa.php",{c:$(this).attr("rel")}, function(data){
			$("#mapacontenedor").html(data);
		});
	} else {
		$("#mapacontenedor").text()
	}
});
$("#btnabrecuenta").click(function(){
	if($("#plazos").is(':checked')){
		if( $("#cantidad").val()=="" || $("#monto1").val()=="" || $("plazo1").val()=="" || ($("#dias_pago").val()=="" && $("#tipo_pago").val()=="nd") ){
			alert("Error al abrir cuenta:\nFaltan campos por llenar en el formulario.");
		}else{
			var cantidad = ($("#plazo1").val() * $("#monto1").val()) + ($("#plazo2").val() * $("#monto2").val());
			if( cantidad == $("#cantidad").val() ){
				$("#frmabrecuenta").submit();
			}else{
				alert("Error: Las cantidades no coinciden.\nLa cantidad prestada es de:"+$("#cantidad").val()+" y la cuenta en plazos es de: "+cantidad);
			}
		}
	}else{
		var editar = <?php echo (isset($_SESSION["EDITARCUENTA"]))?$_SESSION["EDITARCUENTA"]:0;?>;
		if(editar>0){
			alert("Esta funcion no esta disponible");
		}else{
			if( $("#cantidad").val()=="" || $("#interes").val()=="" || $("plazo1").val()=="" || ($("#dias_pago").val()=="" && $("#tipo_pago").val()=="nd" && $("#fechapp").val()=="") ){
				alert("Error al abrir cuenta:\nFaltan campos por llenar en el formulario.");
			}else{
				$("#frmabrecuenta").submit();
			}
		}
	}
});
$("#plazos").click(function(){
	 $(".plazo").attr("disabled",true);
	 $(".int").removeAttr("disabled");
	 $("#interes").removeAttr("checked");
	 $(".plazo").css({ 'background': '#D8D8D8'});
	 $(".int").css({ 'background': ''});
 });

$("#interes").click(function(){
	$(".plazo").removeAttr("disabled");
	$(".int").attr("disabled",true);
	$("#plazos").removeAttr("checked");
	$(".int").css({ 'background': '#D8D8D8'});
	$(".plazo").css({ 'background': ''});
});
</script>
<p class="title">Clientes &raquo; Cuenta</p>
<table>
<caption></caption>
<thead>
	<tr>
		<th colspan="2"></th>
	</tr>
</thead>
<tbody>
	<?php
	@session_start();
	$UserName = $_SESSION["USERNAME"];
	$UserLevel = $_SESSION["U_NIVEL"];
	require_once("include/php/sys_db.class.php");
	require_once("include/conf/Config_con.php");
	$attr = 'selected="selected"';// Stributo select para los select de opcion mutiple
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
	if(!isset($_GET["cl"]))
	{
		$_GET["cl"] = $_SESSION["clid"];
	}
	$result = $db->query("SELECT * FROM clientes WHERE id = ".$_GET["cl"]." LIMIT 0, 1");
	$_SESSION["idcliente"] = $_GET["cl"]; 
	$_SESSION["ifecliente"] = $_GET["cl"]."_ife";
	while ($ln = $db->fetchNextObject($result))
	{
		?>
		<tr>
			<td rowspan="4" width="210">
			<?php
			$sql = "SELECT * FROM clientefoto WHERE idcliente = ".$_GET["cl"]." AND detalle = 'P'";
			$fres= $db->query($sql);
			if( $db->numRows($fres) >= 0 ){
				?>
				<div class="fotocliente-frame">
				<div class="fotocliente-image" style="background:url(include/html/pg_clientes_muestraine.php?c=<?php echo $_GET["cl"];?>&d=IF) center no-repeat;background-size:200px;">
				</div>
				</div>
				<?php
			}
			else
			{
				?>
				<img src="img/User-Icon.png" class="fotocliente" />
				<?php
			}
			?>
			</td>
		</tr>
		<tr>		
			<th>
			<?php
			if(!isset($_SESSION["nohaycuenta"]))
			{
				?>
				<!--
				<a href="include/html/box_nota.php?width=500&height=390&cl=<?php echo $_GET["cl"];?>" class="thickbox" >
				<img src="estilo/img/order-162.png" />
				</a>
				-->
				&nbsp;
				
				<?php
				if( (!isset($_SESSION["EDITARCUENTA"]) || $_SESSION["EDITARCUENTA"] != $ncta) && $_SESSION["U_NIVEL"] == 0)
				{
					?>
					<a href="?pg=2e&cl=<?=$_GET['cl'];?>" id="EditarCuenta" title="Editar datos de cuenta" rel="<?=$ncta;?>">
					<img src="estilo/img/notepencil32.png" />
					</a>
					<?php
				}
				$lnkmap=(isset($_GET["mapa"]))?"?pg=2e&cl=".$_GET['cl']:"?pg=2e&cl=".$_GET['cl']."&mapa=1";
				?>
				&nbsp;
				<a href="<?php echo $lnkmap;?>">
					<img src="img/map_.png" />
				</a>
				<?php
			}
			?>
				&nbsp;
				<a href="include/html/box_cliente_cuenta_ine.php?width=700&height=600&cl=<?php echo $_GET["cl"];?>" class="thickbox" title="Actualizar imágenes de cliente" >
				<img src="estilo/img/contactcard32.png" />
				</a>
				&nbsp;
				<a href="include/html/box_cliente.php?width=700&height=410&cl=<?php echo $_GET["cl"];?>" class="thickbox" title="Detalles de cliente" >
				<img src="estilo/img/user_32.png" />
				</a>
			</th>
			<td colspan="4"><strong>Nombre: </strong> <br /><?php echo $ln->nombre." ".$ln->apellidop." ".$ln->apellidom;?></td>
		<tr>
			<td><strong>Calle: </strong> <br /><?php echo $ln->direccion;?></td>
			<td><strong>Colonia: </strong> <br /><?php echo $ln->colonia;?></td>
			<td><strong>Tel&eacute;fono: </strong> <br /><?php echo $ln->telefono;?></td>
		</tr>
		<tr>
			<td colspan="1"><strong>Celular: </strong> <br /><?php echo $ln->celular;?></td>
			<td><strong>Entrecalles: </strong> <br /><?php echo $ln->entrecalles;?></td>
			<td><strong>Cobrador: </strong> <br /><?php echo $ln->c_cobrador;?></td>
		</tr>
		<?php
		$cobrador = $ln->c_cobrador;
	}
	?>
	<tr>
		<td colspan="4">
			Imágenes: 
			<a href="include/html/pg_clientes_muestraine_html.php?width=800&height=500&c=<?php echo $_GET["cl"];?>&d=IF" title="Credencial INE Frente" class="thickbox">
				<img src="include/html/pg_clientes_muestraine.php?c=<?php echo $_GET["cl"];?>&d=IF" height="30" style="border:1px solid #888;background:#ccc;padding:2px;" />
			</a> 
			<a href="include/html/pg_clientes_muestraine_html.php?width=800&height=500&c=<?php echo $_GET["cl"];?>&d=IR" title="Credencial INE Reverso" class="thickbox">
				<img src="include/html/pg_clientes_muestraine.php?c=<?php echo $_GET["cl"];?>&d=IR" height="30" style="border:1px solid #888;background:#ccc;padding:2px;" />
			</a> 
			<a href="include/html/pg_clientes_muestraine_html.php?width=800&height=500&c=<?php echo $_GET["cl"];?>&d=D1" title="Domicilio 1" class="thickbox">
				<img src="include/html/pg_clientes_muestraine.php?c=<?php echo $_GET["cl"];?>&d=D1" height="30" style="border:1px solid #888;background:#ccc;padding:2px;" />
			</a> 
			<a href="include/html/pg_clientes_muestraine_html.php?width=800&height=500&c=<?php echo $_GET["cl"];?>&d=D2" title="Domicioio 2" class="thickbox">
				<img src="include/html/pg_clientes_muestraine.php?c=<?php echo $_GET["cl"];?>&d=D2" height="30" style="border:1px solid #888;background:#ccc;padding:2px;" />
			</a> 
			<a href="include/html/pg_clientes_muestraine_html.php?width=800&height=500&c=<?php echo $_GET["cl"];?>&d=A1" title="Aval 1" class="thickbox">
				<img src="include/html/pg_clientes_muestraine.php?c=<?php echo $_GET["cl"];?>&d=A1" height="30" style="border:1px solid #888;background:#ccc;padding:2px;" />
			</a> 
			<a href="include/html/pg_clientes_muestraine_html.php?width=800&height=500&c=<?php echo $_GET["cl"];?>&d=A2" title="Aval 2" class="thickbox">
				<img src="include/html/pg_clientes_muestraine.php?c=<?php echo $_GET["cl"];?>&d=A2" height="30" style="border:1px solid #888;background:#ccc;padding:2px;" />
			</a>
		</td>
	</tr>
</tbody>
<tfoot>
	<tr>
		<th colspan="6"></th>
	</tr>				
</tfoot>
</table>
<?php
$db1 = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
$result = $db->query("SELECT * FROM clientes WHERE id = ".$_GET["cl"]." LIMIT 0, 1");
$_SESSION["idcliente"] = $_GET["cl"]; 
$_SESSION["clid"] = $_GET["cl"];
$_SESSION["ifecliente"] = $_GET["cl"]."_ife";
while ($ln2 = $db1->fetchNextObject($result))
{
	?>
	<table>
	<caption>Empleo del Cliente</caption>
	<tr>
		<td><strong>Calle:<strong></td>
		<td><?php echo $ln2->dir_empl;?></td>
		<td><strong>Colonia: </strong></td>	
		<td><?php echo $ln2->c_empleo;?></td>
	</tr>
	<tr>
		<td><strong>Tel&eacute;fono: </strong> </td>
		<td><?php echo $ln2->tel_empl;?> </td>
		<td><strong>Empresa: </strong></td>	
		<td><?php echo $ln2->empleo;?></td>	
	</tr>
	</table>	
	<table>
	<caption>Datos del aval</caption>
	<?php
	if($ln2->Aval!=1)
	{	
		?>
		<tr>
		<td rowspan="2" colspan="4"> No hay datos de Aval, Si desea capturar uno <a href="?pg=2db&cl=<?php echo $_SESSION["clid"];?>"><b>click aqui</b></a></td>	
		</tr>			
		<?php			
	}else{
		?>
		<tr>
		<td><strong>Nombre: </strong> </td>
		<td><?php echo $ln2->R1nombre." ".$ln2->R1apellidop." ".$ln2->R1apellidom;?> </td>
		<td><strong>Direccion: </strong></td>	
		<td><?php echo $ln2->R1dir;?></td>	
		</tr>
		<tr>
		<td><strong>Colonia: </strong> </td>
		<td><?php echo $ln2->R1col;?> </td>
		<td><strong>Telefono: </strong></td>	
		<td><?php echo $ln2->R1tel;?></td>	
		</tr>	
		<tr>
		<td colspan="4" style="text-align:center;"><a href="include/html/box_cliente_avales.php?width=800&height=490&cl=<?php echo $_GET["cl"];?>" class="thickbox" >Ver datos de avales</a></td>		
		</tr>
		</tbody>
		<tfoot>
		<tr>
		<th colspan="6"></th>
		</tr>				
		</tfoot>
		</table>
		</table>	
		<?php
	}
}
?>
<br>
<form name="abrecuenta" id="frmabrecuenta" action="include/php/sys_modelo.php" method="post">
<input type="hidden" name="action" value="cuenta_nueva" />
<input type="hidden" name="cl" value="<?php echo $_GET["cl"];?>" />
<?php
$sql = "SELECT * FROM cuentas WHERE estado = 0 AND cliente = ".$_GET["cl"];
$res = $db->query($sql);
$chk = $db->numRows($res);
if($chk == 0 || (isset($_SESSION["EDITARCUENTA"])&&$_SESSION["EDITARCUENTA"]==$ncta)){
	#[DATOS DE CUENTA SI SE VA A EDITAR]#####################################################################################
	//echo "Cuenta: ".$ncta."<br />Session: ".$_SESSION["EDITARCUENTA"];
	if ( isset($_SESSION["EDITARCUENTA"]) && ($_SESSION["EDITARCUENTA"]==$ncta) )
	{
		$sql = "SELECT * FROM cuentas WHERE id = ".$_SESSION["EDITARCUENTA"];
		$res = $db->query($sql);
		$ec  = $db->fetchNextObject($res);
		$ec_fech = $ec->fecha;
		$ec_fepa = $ec->fecha_pago;
		$ec_cant = $ec->total;
		$ec_cobr = $cc->conrador;
		$ec_tpag = $ec->tipo_pago;
		$ec_dpag = $ec->dias_pago;
		$ec_capital = $ec->capital_inicial;
		#[OBTENER MONTOS Y PAGOS]#
		$sql = "select count(*) plazo,pago monto from pagos where cuenta = ".$_SESSION["EDITARCUENTA"]." group by pago ORDER BY id";
		$res = $db->query($sql);
		$cnt=0;
		while($ecp = $db->fetchNextObject($res))
		{
			if($cnt==0)
			{
				$ec_pzo1 = $ecp->plazo;
				$ec_mto1 = $ecp->monto;
			}
			else 
			{
				$ec_pzo2 = $ecp->plazo;
				$ec_mto2 = $ecp->monto;
			}
			$cnt++;
		}
		$ec_obse = $ec->observaciones;
	}
	################################################################################################[FORMULARIO ABRIR CUENTA]
	?>
	<table>
	<caption>Abrir cuenta</caption>
	<thead>
	<tr>
		<th colspan="4"></th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<?php
		if( isset($_SESSION["EDITARCUENTA"]) ){
			$frmfe=$ec_fech;
			$frmfp=($ec_fepa=='0000-00-00')?date('Y-m-d'):$ec_fepa;
		}else{
			$frmfe=date('Y-m-d');
			$frmfp=date('Y-m-d');
		}
		/*
		ALTER TABLE `cuentas` ADD `tipo_prestamo` SMALLINT(4) NULL DEFAULT NULL COMMENT '1=nuevo, 2=renovacion, 3=restructura, 4=convenio' AFTER `cuenta_tipo`;
		*/
		?>
	<tr>
		<th width="200">Seleccione tipo de prestamo: </th>
		<td colspan="1">&nbsp;&nbsp;&nbsp;<label><input type="radio" id="plazos" value="plazo" name="tipo_c" checked /> Plazos </label>
		&nbsp;&nbsp;&nbsp;<label><input type="radio" id="interes" name="tipo_c" value="interes" /> Interes</label><br /></td>	
		<th width="100">Tipo cuenta: </th>
		<td colspan="2">
			<select name="tipo_prestamo" id="tipo_prestamo">
			<option value="0">SELECCIONAR</option>
			<option value="1">NUEVO</option>
			<option value="2">RENOVACION</option>
			<option value="3">REESTRUCTURA</option>
			<option value="4">CONVENIO</option>
			</select>
		</td>	
	</tr>
	<tr>
		<th width="200">Capital prestado: </th>
		<td width="250">$&nbsp;<input type="text" class="int" name="cap_inicial" id="cap_inicial" size="10" value="<?=$ec_cap;?>" /></td>
		<th >Interes</th>
		<td>%&nbsp;<input type="text" class="plazo" name="interes" id="interes" size="5" value="<?=$ec_int;?>" /></td>
	</tr>
		<th width="120">Fecha:</th>
		<td width="210"><input type="text" name="fecha" id="fecha" size="10" value="<?=$frmfe;?>" class="dpfecha" /></td>
		<th width="150">Primer pago:</th>
		<td><input type="text" name="fechapp" id="fechapp" size="10" value="<?=$frmfp;?>" class="dpfecha" /></td>
	</tr>
	<tr>
		<th>Cantidad:</th>
		<td>$&nbsp;<input type="text" name="cantidad" id="cantidad" size="5" value="<?=$ec_cant;?>" /></td>
		<th>Cobrador: </th>
		<td>
			<select name="cobrador" id="cobrador">
			<?php
		        $sql = "SELECT username FROM mymvcdb_users WHERE username!='jmarincastro'";
			$res = $db->query($sql);
		        while( $cob = $db->fetchNextObject($res) )
		        {
		        	?>
				<option value="<?php echo $cob->username;?>" <?php echo $cob->username == $cobrador? $attr : ''; ?>><?php echo $cob->username;?></option>
				<?php
			}
			?>
			</select>
		</td>
	</tr>
	<tr>
		<th>Tipo de Pago:</th>
		<td>
		<select name="tipo_pago" id="tipo_pago">
			<option value="nd">SELECCIONAR</option>
			<option value="1" <?=((isset($_SESSION["EDITARCUENTA"]) && $ec_tpag==1)?'SELECTED':'')?>>SEMANAL</option>
			<option value="2" <?=((isset($_SESSION["EDITARCUENTA"]) && $ec_tpag==2)?'SELECTED':'')?>>CATORCENAL</option>
			<option value="3" <?=((isset($_SESSION["EDITARCUENTA"]) && $ec_tpag==3)?'SELECTED':'')?>>QUINCENAL</option>
			<option value="4" <?=((isset($_SESSION["EDITARCUENTA"]) && $ec_tpag==4)?'SELECTED':'')?>>MENSUAL</option>
		</select>
		</td>
		<th>Dias de Pago:</th>
		<td>
		<select name="dias_pago" id="dias_pago">
		<?php
		if(isset($_SESSION["EDITARCUENTA"]))
		{
			switch($ec_tpag)
			{
				case 1:	
						?>
						<option value="nd">DIAS DE PAGO</option>
						<option value="1"<?=($ec_dpag==1)?' selected="selected"':'';?>>LUNES</option>
						<option value="2"<?=($ec_dpag==2)?' selected="selected"':'';?>>MARTES</option>
						<option value="3"<?=($ec_dpag==3)?' selected="selected"':'';?>>MIERCOLES</option>
						<option value="4"<?=($ec_dpag==4)?' selected="selected"':'';?>>JUEVES</option>
						<option value="5"<?=($ec_dpag==5)?' selected="selected"':'';?>>VIERNES</option>
						<option value="6"<?=($ec_dpag==6)?' selected="selected"':'';?>>SABADO</option>
						<option value="7"<?=($ec_dpag==7)?' selected="selected"':'';?>>DOMINGO</option>
						<?php
						break;
				case 2:
						?><option value="nd">DIAS DE PAGO</option>
						<option value="1"<?=($ec_dpag==1)?' selected="selected"':'';?>>LUNES</option>
						<option value="2"<?=($ec_dpag==2)?' selected="selected"':'';?>>MARTES</option>
						<option value="3"<?=($ec_dpag==3)?' selected="selected"':'';?>>MIERCOLES</option>
						<option value="4"<?=($ec_dpag==4)?' selected="selected"':'';?>>JUEVES</option>
						<option value="5"<?=($ec_dpag==5)?' selected="selected"':'';?>>VIERNES</option>
						<option value="6"<?=($ec_dpag==6)?' selected="selected"':'';?>>SABADO</option>
						<option value="7"<?=($ec_dpag==7)?' selected="selected"':'';?>>DOMINGO</option>
						<?php
						break;
				case 3:
						?>
						<option value="nd">DIAS DE PAGO</option>
						<option value="10-25"	<?=($ec_dpag=='10-25')?	' selected':'';?>>10 Y 25 DE CADA MES</option>
						<option value="1-16"	<?=($ec_dpag=='1-16')?	' selected':'';?>>16 Y 1 DE CADA MES</option>
						<option value="2-17"	<?=($ec_dpag=='2-17')?	' selected':'';?>>17 Y 2 DE CADA MES</option>
						<option value="2-16"	<?=($ec_dpag=='2-16')?	' selected':'';?>>2 Y 16 DE CADA MES</option>
						<option value="8-22"	<?=($ec_dpag=='8-22')?	' selected':'';?>>8 Y 22 DE CADA MES</option>
						<option value="15-30"	<?=($ec_dpag=='15-30')?	' selected':'';?>>15 Y 30 DE CADA MES</option>
						<option value="1-15"	<?=($ec_dpag=='1-15')?	' selected':'';?>>15 Y 1 DE CADA MES</option>
						<option value="6-21"	<?=($ec_dpag=='6-21')?	' selected':'';?>>6 Y 21 DE CADA MES</option>
						<option value="3-18"	<?=($ec_dpag=='3-16')?	' selected':'';?>>3 Y 18 DE CADA MES</option>
						<option value="4-18"	<?=($ec_dpag=='4-18')?	' selected':'';?>>4 Y 18 DE CADA MES</option>
						<?php
						break;
				case 4:
						?>
						<option value="nd">DIAS DE PAGO</option>
						<option value="1"<?=($ec_dpag==1)?' selected="selected"':'';?>>1 DE CADA MES</option>
						<option value="16"<?=($ec_dpag==16)?' selected="selected"':'';?>>16 DE CADA MES</option>
						<?php
						break;
				default:
						?>
						<option value="nd">DIAS DE PAGO</option>
						<?php
			}
		}
		else
		{
			?><option value="nd">DIAS DE PAGO</option><?php
		}
		?>
		</select>
		</td>
	</tr>
	<tr>
		<th>Plazo/Meses:</th>
		<td><input type="text" name="plazo1" id="plazo1" size="10" value="<?=$ec_pzo1;?>" /></td>
		<th>Monto:</th>
		<td>$&nbsp;<input type="text" class="int" name="monto1" id="monto1" size="10" value="<?=$ec_mto1;?>" /></td>
	</tr>
	<tr>
		<th>Plazo:</th>
		<td><input type="text" class="int" name="plazo2" id="plazo2" size="10" value="<?=$ec_pzo2;?>" /></td>
		<th>Monto:</th>
		<td>$&nbsp;<input type="text" class="int" name="monto2" id="monto2" size="10" value="<?=$ec_mto2;?>" /></td>
	</tr>	
	<tr>
		<th>Observaciones</th>
		<td colspan="3"><textarea name="observ" id="observ" cols="48" rows="2"><?=$ec_obse;?></textarea></td>		
	</tr>	
	</tbody>
	<tfoot>	
		<?php
		if (isset($_SESSION["EDITARCUENTA"])){
			?>
			<th colspan="4">
			<input type="button" value="Cancelar" id="CancelarEditarCuenta" title="Cancelar editar datos de cuenta" rel="<?=$ncta;?>" />
			<input type="button" id="btnabrecuenta" value="Guardar cambios" />
			</th>
			<?php
		}
		else
		{
			?>
			<th colspan="4"><input type="button" id="btnabrecuenta" value="Abrir cuenta" /></th>
			<?php
		}
		?>
	</tr>
	</tfoot>
	</table>
	</form>
	<br />
	<?php
}else{
	#################################################################################################[DETALLES DE LA CUENTA]
	$r = $db->fetchNextObject($res);
	$cuenta = $r->id;
	$cliente = $r->cliente;
	$cantidad = $r->cantidad;
	$interes = $r->interes; 
	$fecha = $r->fecha;
	$saldo = $r->total;
	$t_presta = $r->tipo_prestamo;
	if($r->tipo_pago == 1)
	{
		$tp = "SEMANAL";
	}
	elseif($r->tipo_pago == 2)
	{
		$tp = "CATORCENA";
	}
	elseif($r->tipo_pago == 3)
	{
		$tp = "QUINCENAL";
	}
	else
	{
		$tp = "MENSUAL";
	}
	?>
	<table>
	<caption>DETALLES DE LA CUENTA</caption>
	<tbody>
	<tr>
		<th>CAPITAL PRESTADO:</th><td>$&nbsp;<?php moneda($r->capital_inicial); ?></td>
		<th>TIPO CUENTA:</th><td><?php echo getTipoprestamo($t_presta); ?></td>
		<th>NUM. CONTROL:</th><td><?php echo $cuenta; ?></td>
	</tr>
	<tr>
		<th>FECHA:</th><td colspan="1"><?php echo date ("d-m-Y", strtotime($r->fecha)); ?></td>
		<th>COBRADOR:</th><td colspan=""><?php echo $cobrador; ?></td>
		<th>RIESGO VENC.:</th><td colspan="1"><?php echo getMaxDv($cliente);?></td>
	</tr>
	<tr>
		<th>MONTO:</th>		<td>$&nbsp;<?php moneda($r->cantidad); ?></td>
		<th> </th>		<td> </td>
		<th>SALDO:</th>		<td>$&nbsp;<?php echo moneda($saldo); ?></td>
	</tr>
	<tr>
		<th>TIEMPO:</th><td><?php echo $r->tiempo . " "; ?></td>
		<th>MODO DE PAGO:</th><td><?php echo $tp; ?></td>
		<th>DIAS DE PAGO:</th><td><?php if($r->tipo_pago < 4){getDiaSemana($r->dias_pago, $r->tipo_pago);}else{echo 'Días '.$r->dias_pago.' de cada mes.';} ?></td>
	<tr>
		<th>OBSERVACIONES:</th><td colspan="5" style="text-align:left;"><?php echo nl2br($r->observaciones); ?></td>		
	</tr>
	</tr>
	</tbody>
	<tfoot>
	<tr>
		<th colspan="6"></th>
	</tr>
	</tfoot>
	</table>
	
	<!-- ###################################################################################################[MAPA - MOSTRANDO MAPA] -->
	<?php
	$sql = "SELECT idcoord FROM coordenadas WHERE cliente = $cliente";
	$res = $db->query($sql);
	if($db->numRows()==0 || isset($_GET["mapa"]))
	{
		?>
		<br />
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
		 		<iframe src="include/html/pg_cliente_cuenta_mapa.php?c=<?php echo $_GET['cl'];?>" style="width:100%;border:0px;min-width:400px;height:480px;"></iframe> 
		 		</td>
			</tr>
		</tbody>
		<tfoot>
			<tr><th></th></tr>
		</tfoot>
		</table>
		<?php
	}
	###################################################################################################[COMPROBANDO RECARGOS]
	## buscando fecha primer pago
	$sql = 'SELECT id, fecha FROM pagos WHERE estado = 0 AND cuenta = '.$cuenta.''; 				 	
	$res = $db->query($sql);
	while($r = $db->fetchNextObject($res))
	{
	        $pago_id = $r->id;
	        $proxpago = $r->fecha;
	        $hoy = date("Y-m-d");
		## verificando estado del pago 
		if(getHayRecargo($proxpago) == 1)
		{
			$dAtras = date_diffe($hoy,$proxpago,$cliente);
			$sql = "SELECT * FROM recargos WHERE pago_id = ".$pago_id." AND pago = '".$proxpago."'";
			$rec = $db->query($sql);
			$monto = (10 * $dAtras);
			if($db->numRows() == 0)
			{
				$sql = "INSERT INTO recargos (cuenta, cliente, pago, fecha, monto, pago_id, dias_atraso) 
				VALUES (".$cuenta.", ".$cliente.", '".$proxpago."', '".date("Y-m-d")."', ".$monto.", ".$pago_id.", ".$dAtras.")";
				$db->execute($sql);	
			}
			elseif ($db->numRows() > 0)
			{
				$sql = "UPDATE recargos SET monto = $monto, dias_atraso = ".$dAtras." WHERE estado = 0 AND pago_id = ".$pago_id."";
				$db->execute($sql);
			}
		}
	}
	#####################################################################################################[ABONAR A LA CUENTA]
	#####################################################################################################[HISTORIAL DE PAGOS]
	?>
	<br />
	<br />
	<!--############# ACCIONES DE CUENTAS ###################### -->
	<table>
	<caption>ACCIONES DE CUENTA</caption>
	<thead>
	<tr>
		<th colspan = "5"></th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<td colspan="5">
		<a href="include/html/box_cliente_cuenta_saldar.php?width=500&height=430&c=<?= $cuenta;?>" class="thickbox boton esqRedondas sombra">Sald. Cta.</a>
		<a href="include/html/box_cuenta_elim.php?width=480&height=250&cte=<?= $cliente;?>&cta=<?= $cuenta;?>" class="thickbox boton esqRedondas sombra">Elim. Cuenta</a>
		<a href="?pg=3da&cl=<?php echo $cliente;?>" class="boton esqRedondas sombra">Historial</a>			
		<?php
		#if($r->tipo_pago == 4)
		if($tp == "MENSUAL")
		{
			?>
			<a href="include/html/box_cliente_cuenta_interes.php?width=500&height=430&c=<?= $cuenta;?>" class="thickbox boton esqRedondas sombra">Solo interes</a>
			<?php
		}
		?>
		<a href="include/html/box_cliente_cuenta_pagare.php?height=500&width=400&c=<?php echo $cuenta;?>&cl=<?php echo $cliente; ?>" class="thickbox boton esqRedondas sombra">Pagare</a>
		<a href="include/html/box_cliente_reimprime_cuenta.php?width=500&height=430&c=<?= $cuenta;?>" class="thickbox boton esqRedondas sombra">Re. Prestamo</a>
		<a href="include/html/box_pagos_elim.php?width=500&height=420&cta=<?=$cuenta;?>&cte=<?=$cliente;?>" class="thickbox boton esqRedondas sombra">C. Pagos</a>
		<a href="include/html/box_recargos_elim.php?width=500&height=420&cta=<?=$cuenta;?>&cte=<?=$cliente;?>" class="thickbox boton esqRedondas sombra">Rec.Cond</a>
		</td>
	</tr>		
	</table>	
	<br />
	<table>
	<caption>DESCRIPCI&Oacute;N DE LA CUENTA ACTUAL</caption>
	<thead>
	<tr>
		<th></th>
		<th align="left">FECHA</th>
		<th align="left">F. PAGO</th>
		<th>ABONO</th>
		<th align="center">CARGO</th>
		<th align="left"></th>
		<th align="center">RECARGOS</th>
		<th align="center">ABONADO</th>
		<th align="center"></th>
	</tr>
	</thead>
	<tbody>
	<?php
	$i = 0;
	$pago_acum=0;
	$fcnt= 0;
	$sql = "SELECT * FROM pagos WHERE cuenta = ".$cuenta ." ORDER BY id ASC";	
	$res = $db->query($sql);
	while($r = $db->fetchNextObject($res))
	{
		#-MOSTRANDO ABONOS MENORES AL PAGO--
		$sql = "SELECT * FROM abono WHERE idpago = ".$r->id;
		$rab = $db->query($sql);
		while($ab = $db->fetchNextObject($rab))
		{
			//$i++;
			?>
			<tr>
				<td><!-- <?= $i; ?> --></td>
				<td><?php echo date("d-m-Y", strtotime($r->fecha)); ?></td>
				<td><?php echo date("d-m-Y", strtotime($ab->fecha)); ?></td>	
				<td>$ <?php moneda($ab->cargo); ?></td>
				<td>$ <?php moneda($ab->abono); ?></td>
				
				<td>
					<form name="frm_<?php echo $ab->idabono;?>" action="include/php/sys_modelo.php" method="post">
					<input type="hidden" name="idpago" value="<?= $ab->idpago;?>" />
					<input type="hidden" name="abono" value="<?= $ab->abono;?>" />
					<input type="hidden" name="cl" value="<?= $_GET['cl'];?>" />
					<input type="hidden" name="c" value="<?= $ab->idcuenta;?>" />
					<input type="hidden" name="action" value="abono_" />
					<input type="submit" value="REIMP" />
					<?php
					/*
					if ($UserLevel == 0) {
						?>
						<form name="frm_<?php echo $ab->idabono;?>" action="include/php/sys_modelo.php" method="post">
						<input type="hidden" name="idpago" value="<?= $ab->idpago;?>" />
						<input type="hidden" name="idabono" value="<?= $ab->idabono;?>" />
						<input type="hidden" name="abono" value="<?= $ab->abono;?>" />
						<input type="hidden" name="c" value="<?= $ab->idcuenta;?>" />
						<input type="hidden" name="cl" value="<?= $_GET['cl'];?>" />
						<input type="hidden" name="action" value="abono_elimina" />
						<input type="submit" id="pago_cancel" value="C. ABONO"/>
						<?php
					}
					*/
					?>
					</form>
				</td>
				<th></th>
				<th></th>
				<th></th>
			</tr>
			<?php
		}
		$i++;
		?>
		<tr>
			<td width="10"><?php echo $i;?></td>
			<td>
			<?php
			if($r->pago == 0)
			{
				echo date("d-m-Y", strtotime($r->fechaPago));
			}else{
				echo date("d-m-Y", strtotime($r->fecha));
			}
			if ($r->fechaPago != "0000-00-00") {
				$r->fechaPago = date("d-m-Y", strtotime($r->fechaPago));
			}else{
				$r->fechaPago = $r->fechaPago;
			}
			?>
			</td>
			<td><?php echo $r->fechaPago;?></td>
			<td>$ <?php moneda($r->pago); ?></td>
			<td>$ <?php moneda($r->pago_real); ?></td>
			
			<th style="text-align: center;">
			<?php
			if($r->estado == 0)
			{
				if (  ($r->fecha > date("Y-m-d")) && ($fcnt > 0) )
				{
					$opcnpagar	= 'disabled style="background:#999;color:#777;border-color:#666;width:70px;visibility:hidden;"';
					$opcnpagarbtn	= 'style="visibility: hidden;"';
				}else{
					$opcnpagar 	= 'style="width:70px;"';
					$opcnpagarbtn	= '';
				}
				$fcnt++;
				$pago_acum += getPagoRedondo($r->pago);
				$textoconfirm = "Esta seguro que desea aplicar el pago del dia" .date("d-m-Y", strtotime($r->fecha)). "por la cantidad de " .$pago_acum. "?";
				if($saldo > $pago_acum)

				{
					?>
					<form name="frm_<?php echo $r->id;?>" action="include/php/sys_modelo.php" method="post">
					<input type="hidden" 	name="numpago" 	value="<?= $i;?>" />
					<input type="text" 		name="pago" 	value="<?= $pago_acum;?>" <?=$opcnpagar;?> class="validarpago" rel="<?= $pago_acum;?>" />
					<input type="hidden" 	name="cl" 	value="<?= $_GET['cl'];?>" />
					<input type="hidden" 	name="c" 	value="<?= $cuenta;?>" />
					<input type="hidden" 	name="pid" 	value="<?= $r->id;?>" />
					<input type="hidden" 	name="action" 	value="cuenta_pagar" />	
					<input type="submit" 	onclick="return confirm('<?php echo $textoconfirm;?>' )" value="ABONAR" <?=$opcnpagarbtn;?>   />
					</form>
					<?php
				}
				elseif(($pago_acum - $saldo) > 0)
				{
					//$pago_acum = $saldo;
					?>
					<form name="frm_<?php echo $r->id;?>" action="include/php/sys_modelo.php" method="post">
					<input type="hidden"	name="numpago"	value="<?= $i;?>" />
					<input type="text"		name="pago" 	value="<?= $pago_acum;?>" <?=$opcnpagar;?> class="validarpago" rel="<?= $pago_acum;?>" />
					<input type="hidden"	name="cl" 		value="<?= $_GET['cl'];?>" />
					<input type="hidden"	name="c"		value="<?= $cuenta;?>" />
					<input type="hidden"	name="pid"		value="<?= $r->id;?>" />
					<input type="hidden"	name="action"	value="cuenta_pagar" />
					<input type="submit"	onclick="return confirm('Esta seguro que desea aplicar el pago por \n la cantidad de $'+ <?php echo ($pago_acum);?>+'00 ?' )" value="ABONAR" <?=$opcnpagarbtn;?> />
					</form>
					<?php
				}
				else
				{
					//$pago_acum = $saldo;
					?>
					<form name="frm_<?php echo $r->id;?>" action="include/php/sys_modelo.php" method="post">
					<input type="hidden" name="numpago"	value="<?= $i;?>" />
					<input type="text" name="pago"		value="<?= $pago_acum;?>" <?=$opcnpagar;?> />
					<input type="hidden" name="cl"		value="<?= $_GET['cl'];?>" />
					<input type="hidden" name="c"		value="<?= $cuenta;?>" />
					<input type="hidden" name="pid"		value="<?= $r->id;?>" />
					<input type="hidden" name="action"	value="cuenta_pagar" />
					<input type="submit" onclick="return confirm('Esta seguro que desea aplicar el pago por \n la cantidad de $'+ <?php echo ($pago_acum);?>+'.00 ?')" value="ABONAR" <?=$opcnpagarbtn;?> />
					</form>
					<?php
				}
			}
			elseif($r->estado == 1)
			{
				if($r->pago == 0){
					//$pago_acum = $saldo;
					?>
					<form name="frm_<?php echo $r->id;?>" action="include/php/sys_modelo.php" method="post">
					<input type="hidden" name="numpago" value="<?= $i;?>" />
					<input type="hidden" name="pago" value="<?= $pago_acum;?>" />
					<input type="hidden" name="cl" value="<?= $_GET['cl'];?>" />
					<input type="hidden" name="c" value="<?= $cuenta;?>" />
					<input type="hidden" name="pid" value="<?= $r->id;?>" />
					<input type="hidden" name="action" value="cuenta_pagar" />
					<input type="submit" value="REIMP" />
					<?php
					/*
					if ($UserLevel == 0) {
						?>
						<form name="frm_<?php echo $r->id;?>" action="include/php/sys_modelo.php" method="post">
						<input type="hidden" name="numpago" value="<?= $i;?>" />
						<input type="hidden" name="pago" value="<?= $r->pago_real;?>" />
						<input type="hidden" name="cl" value="<?= $_GET['cl'];?>" />
						<input type="hidden" name="c" value="<?= $cuenta;?>" />
						<input type="hidden" name="pid" value="<?= $r->id;?>" />
						<input type="hidden" name="action" value="pago_elimina" />
						<input type="submit" id="pago_cancel" value="CANCELAR"/>
						<?php
					}
					*/
					?>
					</form>
					<?php
					#echo '<p style="color:#4A9E41;">PAGO DE INTERES</p>';
				}
				else
				{
					//$pago_acum = $saldo;
					?>
					<form name="frm_<?php echo $r->id;?>" action="include/php/sys_modelo.php" method="post">
					<input type="hidden" name="numpago" value="<?= $i;?>" />
					<input type="hidden" name="pago" value="<?= $pago_acum;?>" />
					<input type="hidden" name="cl" value="<?= $_GET['cl'];?>" />
					<input type="hidden" name="c" value="<?= $cuenta;?>" />
					<input type="hidden" name="pid" value="<?= $r->id;?>" />
					<input type="hidden" name="action" value="cuenta_pagar" />
					<input type="submit" value="REIMP" />
					<?php
					/*
					if ($UserLevel == 0) {
					?>
						<form name="frm_<?php echo $r->id;?>" action="include/php/sys_modelo.php" method="post">
						<input type="hidden" name="numpago" value="<?= $i;?>" />
						<input type="hidden" name="pago" value="<?= $r->pago_real;?>" />
						<input type="hidden" name="cl" value="<?= $_GET['cl'];?>" />
						<input type="hidden" name="c" value="<?= $cuenta;?>" />
						<input type="hidden" name="pid" value="<?= $r->id;?>" />
						<input type="hidden" name="action" value="pago_elimina" />
						<input type="submit" id="pago_cancel" value="CANCELAR"/>
					<?php
					}
					*/
					?>
					</form>
					<?php
				}
			}
			elseif($r->estado == 3)
			{
				?>
				<form name="frm_<?php echo $r->id;?>" action="include/php/sys_modelo.php" method="post">
				<input type="hidden" name="numpago" value="<?= $i;?>" />
				<input type="hidden" name="pago" value="<?= $r->pago_real;?>" />
				<input type="hidden" name="cl" value="<?= $_GET['cl'];?>" />
				<input type="hidden" name="c" value="<?= $cuenta;?>" />
				<input type="hidden" name="pid" value="<?= $r->id;?>" />
				<input type="hidden" name="action" value="cuenta_pagar" />
				<input type="submit" value="REIMP" />
				<?php
					/*
					if ($UserLevel == 0) {
					?>
						<form name="frm_<?php echo $r->id;?>" action="include/php/sys_modelo.php" method="post">
						<input type="hidden" name="numpago" value="<?= $i;?>" />
						<input type="hidden" name="pago" value="<?= $r->pago_real;?>" />
						<input type="hidden" name="cl" value="<?= $_GET['cl'];?>" />
						<input type="hidden" name="c" value="<?= $cuenta;?>" />
						<input type="hidden" name="pid" value="<?= $r->id;?>" />
						<input type="hidden" name="action" value="pago_elimina" />
						<input type="submit" id="pago_cancel" value="CANCELAR"/>
					<?php
					}
					*/
					?>
					</form>
					<?php
			}
			?>
			</th>
			<?php
			#-MOSTRANDO RECARGOS POR PAGO VENCIDO-#######################################################################################
			$sql = "SELECT * FROM recargos WHERE cuenta = ".$cuenta." AND pago_id = ".$r->id." AND estado<2 ORDER BY pago ASC";
			$rec = $db->query($sql);
			$tot=0;
			if($db->numRows() > 0)
			{
				while($re = $db->fetchNextObject($rec))
				{
					if($re->estado == 0){
						//-MONTO SALDADO
						$SQL="select sum(abono) saldado from abono_recargos where idrec=".$re->id;
						$RES=$db->query($SQL);
						$ars=$db->fetchNextObject($RES);
						$pendiente=($r->estado==0)?$re->monto-$ars->saldado:$re->monto;
						echo '	<td> <center>$ '; moneda($pendiente).'</center></td>';
						echo '	<td> <center>$ '; moneda($ars->saldado).'</center></td>';
						$tot += $re->monto;
						?>
						<th>
						<form name="frm_saldar" action="include/php/sys_modelo.php"  method="post">
						<input type="hidden" name="pendiente" value="<?= $pendiente;?>" />
						<input type="hidden" name="recargo_id" value="<?= $re->id;?>" />
						<input type="hidden" name="c" value="<?= $cuenta;?>" />
						<input type="hidden" name="cl" value="<?= $cliente;?>" />
						<input type="hidden" name="fecha_recargo" value="<?= $re->pago;?>" />
						<input type="hidden" name="action" value="recargos" />
						<center>
							<input type="text" name="recargo" value="<?= $pendiente;?>" style="width:70px;" />
							<input type="submit" name="rec_pagar" value="Pagar" />
						</center>
						</form>
						</th>
						<?php
					}else{
						$SQL="select sum(abono) saldado from abono_recargos where idrec=".$re->id;
						$RES=$db->query($SQL);
						$ars=$db->fetchNextObject($RES);
						echo '	<td> <center>$ '; moneda(0).'</center></td>';
						echo '	<td> <center>$ '; moneda($re->monto_saldado+$ars->saldado).'</center></td>';
						?>
						<th>
						<form name="frm_re_recargo" action="include/php/sys_modelo.php"  method="post">
						<input type="hidden" name="pago_id" value="<?= $re->pago_id;?>" />
						<input type="hidden" name="c" value="<?= $cuenta;?>" />
						<input type="hidden" name="cl" value="<?= $cliente;?>" />
						<input type="hidden" name="recargo" value="<?= $re->monto;?>" />
						<input type="hidden" name="fecha_recargo" value="<?= $re->pago;?>" />
						<input type="hidden" name="action" value="recargos" />
						<center><input type="submit" name="rec_reimprime" value="REIMP" /> </center>
						</form>
						</th>
						<?php    
					}
					$tot;
				}
			}
			else
			{
				?>
				<th></th>
				<th></th>
				<th></th>
				<?php
			}
			?>
		</tr>
		<?php
	}
	?>
</tbody>
<tfoot>
<tr>
	<th colspan="6"></th>	
</tr>
</tfoot>
</table>
<?php
}
?>
