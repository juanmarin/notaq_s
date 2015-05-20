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
	require_once("include/php/sys_db.class.php");
	require_once("include/conf/Config_con.php");
	
	function getVivienda($tipo)
	{
		switch($tipo)
		{
			case 1:	echo "Propia";	break;
			case 2:	echo "Renta";	break;
			case 3:	echo "Padres";	break;
			case 4:	echo "otro";	break;
			default:	echo "No definido";	break;
		}
	}
	function getProp($tipo) 
	{
		switch($tipo)
		{
			case 1:	echo "Si";	break;
			case 0:	echo "No";	break;
			default:	echo "No definido";	break;
		}
	}
	$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
	if(!isset($_GET["cl"])){
		$_GET["cl"] = $_SESSION["clid"];
	}
	$result = $db->query("SELECT * FROM clientes WHERE id = ".$_GET["cl"]." LIMIT 0, 1");
	$_SESSION["idcliente"] = $_GET["cl"]; 
    $_SESSION["ifecliente"] = $_GET["cl"]."_ife";
	while ($ln = $db->fetchNextObject($result))
	{
		?>
		<tr>
			<th rowspan="2" width="50">
			<?php
			if(!isset($_SESSION["nohaycuenta"])){
				?>			
				<a href="include/html/box_nota.php?width=500&height=390&cl=<?php echo $_GET["cl"];?>" class="thickbox" ><img src="estilo/img/order-162.png" /></a></th>
				<?php
			}
			?>
			 <td rowspan="2" width="390"><strong>Nombre: </strong> <br /><?php echo $ln->nombre." ".$ln->apellidop." ".$ln->apellidom;?></td>
			 <td rowspan="2" width="200"><strong>Cobrador: </strong> <br /><?php echo $ln->c_cobrador;?></td>			
			<?php
			/*  
			//--- FOTOGRAFIA DEL CLIENTE ---------------------------------------------------------------------------------------
			$exists = 0;
			if(file_exists("include/jpegcam/htdocs/" . $_SESSION["idcliente"] . ".jpg")){
				?>
				<td rowspan="2" width="250">
                    <a href="include/jpegcam/htdocs/test.html?keepThis=true&TB_iframe=true&height=320&width=300" class="thickbox" rel="foto" title="<?php echo $ln->nombre." ".$ln->apellidop." ".$ln->apellidom;?>"><img src="include/jpegcam/htdocs/<?php echo $_SESSION["idcliente"];?>.jpg" /></a></td>
				<?php
			}else{
				?>
				<td rowspan="2" width="250">
                    <a href="include/jpegcam/htdocs/test.html?keepThis=true&TB_iframe=true&height=380&width=480" class="thickbox" rel="foto" title="<?php echo $ln->nombre." ".$ln->apellidop." ".$ln->apellidom;?>">Capturar</a></td>
				<?php
			}
			//--- FOTOGRAFIA DE IFE NUMERO UNO ---------------------------------------------------------------------------------
			if(file_exists("include/jpegcam/htdocs/" . $_SESSION["ifecliente"] . ".jpg")){
				$exists++;
				?>
				<td width="220">
                    <a href="include/jpegcam/htdocs/test_ife.html?keepThis=true&TB_iframe=true&height=350&width=480" class="thickbox" rel="ife" title="<?php echo $ln->nombre.' '.$ln->apellidop.' '.$ln->apellidom;?>"><img src="include/jpegcam/htdocs/<?php echo $_SESSION["ifecliente"];?>.jpg" width="100" /></a></td>
				<?php
			}else{
				?>
				<td width="220">
                    <a href="include/jpegcam/htdocs/test_ife.html?keepThis=true&TB_iframe=true&height=350&width=480" class="thickbox" rel="ife" title="<?php echo $ln->nombre.' '.$ln->apellidop.' '.$ln->apellidom;?>">Capturar IFE</a></td>
				<?php
			}
			//--- FOTOGRAFIA DE IFE NUMERO DOS ---------------------------------------------------------------------------------
			if(file_exists("include/jpegcam/htdocs/" . $_SESSION['ifecliente'] . "2.jpg")){
				$exists++;
				?>
				<td width="220">
					<a href="include/jpegcam/htdocs/test_ife2.html?keepThis=true&TB_iframe=true&height=350&width=480" class="thickbox" rel="ife" title="<?php echo $ln->nombre.' '.$ln->apellidop.' '.$ln->apellidom;?>"><img src="include/jpegcam/htdocs/<?php echo $_SESSION["ifecliente"];?>2.jpg" width="100" /></a>
				</td>
				<?php
			}else{
				?>
				<td width="220">
					<a href="include/jpegcam/htdocs/test_ife2.html?keepThis=true&TB_iframe=true&height=350&width=480" class="thickbox" rel="ife" title="<?php echo $ln->nombre.' '.$ln->apellidop.' '.$ln->apellidom;?>">Capturar IFE</a>
				</td>
				<?php
			}
			?>
			<tr>
            <td colspan="2" align="center">
			<?php
			if($exists > 0){
				?>
				<a href="include/html/box_fotos.php?height=275&width=750" class="thickbox" rel="ife" title="<?php echo "IFE"." ".$ln->nombre.' '.$ln->apellidop.' '.$ln->apellidom;?>">Ampliar IFE</a>
				<?php
			}
			*/
			?>
            </tr>
			</td>
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
<br />
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
	<caption>Direcci&oacute;n del cliente</caption>
	<thead>
		<tr>
			<th colspan="4"></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><strong>Calle: </strong> </td>
			<td><?php echo $ln2->direccion;?> </td>
		
			<td><strong>Colonia: </strong></td>	
			<td><?php echo $ln2->colonia;?></td>	
		</tr>
		<tr>
			<td><strong>Tel&eacute;fono: </strong> </td>
			<td><?php echo $ln2->telefono;?> </td>
		
			<td><strong>Celular: </strong></td>	
			<td><?php echo $ln2->celular;?></td>	
		</tr>
		<table>
			<caption>Datos del aval</caption>
	<?php
		if($ln2->Aval!=1) {	
	?>
		<tr>
			<td rowspan="2" colspan="4"> No hay datos de Aval, Si desea capturar uno <a href="?pg=2db&cl=<?php echo $_SESSION["clid"];?>"><b>click aqui</b></a></td>	
		</tr>			
	<?php			
		}else {
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
	<?php
		}
	}
	?>
	</tbody>
	<tfoot>
	<tr>
		<th colspan="6"></th>
	</tr>				
</tfoot>
</table>
</table>
<br>
<form name="abrecuenta" action="include/php/sys_modelo.php" method="post">
<input type="hidden" name="action" value="cuenta_nueva" />
<input type="hidden" name="cl" value="<?php echo $_GET["cl"];?>" />
<?php
$sql = "SELECT * FROM cuentas WHERE estado = 0 AND cliente = ".$_GET["cl"];
$res = $db->query($sql);
$chk = $db->numRows($res);
if($chk == 0){
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
			<th width="120">Fecha:</th>
			<td width="210"><input type="text" name="fecha" id="fecha" size="10" value="<?php echo date('Y-m-d');?>" class="dpfecha" /></td>
			<th width="150">Primer pago:</th>
			<td><input type="text" name="fechapp" id="fechapp" size="10" value="<?php echo date('Y-m-d');?>" class="dpfecha" /></td>
		</tr>
		<tr>
			<th>Cantidad:</th>
			<td>$<input type="text" name="cantidad" size="5" /></td>
			<th>Cobrador:</th>
			<td>
    <?php
            $sql = "SELECT userID, username FROM mymvcdb_users WHERE nivel = 3";
            $rs = mysql_query($sql) or die(mysql_error());
            echo "<select name='cobrador' id='cobrador'>";
            echo "<option value='seleccionar'>Asignar</option>";
            while($row = mysql_fetch_array($rs)){
            echo "<option value='".$row["username"]."'>".$row["username"]."</option>";
            }mysql_free_result($rs);
    ?>
			</select>
            </td>
		</tr>
		<tr>
            <th>Tipo de Pago:</th>
			<td>
				<select name="tipo_pago" id="tipo_pago">
					<option value="nd">SELECCIONAR</option>
					<option value="1">SEMANAL</option>
					<option value="2">CATORCENAL</option>
					<option value="3">QUINCENAL</option>
					<option value="4">MENSUAL</option>
				</select>
			</td>
            <th>Dias de Pago:</th>
			<td>
				<select name="dias_pago" id="dias_pago">
					<option value="nd">SELECCIONAR</option>
				</select>
			</td>
		</tr>
        <tr>
			<th>Plazo:</th>
			<td><input type="text" name="plazo1" size="10" /></td>
            
			<th>Monto:</th>
			<td>$<input type="text" name="monto1" size="10" /></td>
        </tr>
        <tr>
			<th>Plazo:</th>
			<td><input type="text" name="plazo2" size="10" /></td>
            
			<th>Monto:</th>
			<td>$<input type="text" name="monto2" size="10" /></td>
        </tr>	
		<tr>
			<th>Observaciones</th>
			<td colspan="3"><textarea name="observ" id="observ" cols="48" rows="2"></textarea></td>		
		</tr>		
	</tbody>
	<tfoot>
		<tr>
			<th colspan="4"><input type="submit" value="Abrir cuenta" /></th>
		</tr>
	</tfoot>
	</table>
	</form>
	<br />
	<?php
}else {
	#################################################################################################[DETALLES DE LA CUENTA]
	$r = $db->fetchNextObject($res);
	$cuenta = $r->id;
	$cliente = $r->cliente;
	$cantidad = $r->cantidad;
	$interes = $r->interes; 
	$fecha = $r->fecha;
	$saldo = $r->total;
	if($r->tipo_pago == 1) {
		$tp = "SEMANAL";
	}elseif($r->tipo_pago == 2) {
		$tp = "CATORCENA";
	}elseif($r->tipo_pago == 3) {
		$tp = "QUINCENAL";
	}else {
		$tp = "MENSUAL";
	}
	?>
	<table>
	<caption>DETALLES DE LA CUENTA</caption>
	<tbody>
		<tr>
			<th>FECHA:</th><td colspan="3"><?php echo $r->fecha; ?></td>
			<th>COBRADOR:</th><td colspan=""><?php echo $r->cobrador; ?></td>
		</tr>
		<tr>
			<th>MONTO:</th>		<td>$&nbsp;<?php moneda($r->cantidad); ?></td>
			<th> </th>		<td> </td>
			<th>SALDO:</th>		<td>$&nbsp;<?php echo moneda($saldo); ?></td>
		</tr>
		<tr>
			<th>TIEMPO:</th><td><?php echo $r->tiempo . " " . $tt; ?></td>
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
	<br />
	<?php
	###################################################################################################[COMPROBANDO RECARGOS]
	## buscando fecha primer pago
	$sql = "SELECT id, fecha FROM pagos WHERE estado = 0 AND cuenta = ".$cuenta."";
	$res = $db->query($sql);
	while($r = $db->fetchNextObject($res)){
        $pago_id = $r->id;
        $proxpago = $r->fecha;
		## verificando estado del pago 
		if(getHayRecargo($proxpago) == 1) {
			$sql = "SELECT * FROM recargos WHERE pago_id = ".$pago_id." AND pago = '".$proxpago."'";
			$rec = $db->query($sql);
			$monto = 10;
			if($db->numRows() == 0){
				$sql = "INSERT INTO recargos (cuenta, cliente, pago, fecha, monto, pago_id) VALUES (".$cuenta.", ".$cliente.", '".$proxpago."', '".date("Y-m-d")."', ".$monto.", ".$pago_id.")";
				$db->execute($sql);	
			}
		}
	}
	$sql = "SELECT * FROM recargos WHERE cuenta = ".$cuenta." ORDER BY pago ASC";
    	//echo $sql."<br>";
	$rec = $db->query($sql);
	if($db->numRows() > 0){
		###################################################################################################[CARGANDO RECARGOS]
		?>
		<table>
		<caption>RECARGOS POR DEMORA</caption>
		<thead>
			<tr>
                <th colspan="1">#</th>
				<th colspan="1">Fecha</th>
                <th colspan="1">Cantidad</th>
                <th colspan="1">Acciones</th>
			</tr>
		</thead>
		<tbody>
		<?php	
		$i = 0;
		while($re = $db->fetchNextObject($rec)) {
			$i++;
			echo '<tr>';
            echo '	<th> <center>'.$i.'</center></td>';
			echo '	<th> <center>'.$re->pago.'</center></td>';
			echo '	<td> <center>$ '; moneda($re->monto).'</center></td>';
            //$monto = moneda($re->monto);
            if($re->estado == 0){
            $tot += $re->monto;
        ?>
                <form name="frm_saldar" action="include/php/sys_modelo.php"  method="post">
                <input type="hidden" name="pago_id" value="<? echo $re->pago_id;?>" />
                <input type="hidden" name="c" value="<? echo $cuenta;?>" />
                <input type="hidden" name="cl" value="<? echo $cliente;?>" />
                <input type="hidden" name="recargo" value="<? echo $re->monto;?>" />
                <input type="hidden" name="fecha_recargo" value="<? echo $re->pago;?>" />
                <input type="hidden" name="action" value="recargos" />
                <th><center> <input type="submit" name="rec_pagar" value="Pagar Recargo" /> </center></th>
                </form>
        <?php
            }else{
        ?>
                <form name="frm_re_recargo" action="include/php/sys_modelo.php"  method="post">
                <input type="hidden" name="pago_id" value="<? echo $re->pago_id;?>" />
                <input type="hidden" name="c" value="<? echo $cuenta;?>" />
                <input type="hidden" name="cl" value="<? echo $cliente;?>" />
                <input type="hidden" name="recargo" value="<? echo $re->monto;?>" />
                <input type="hidden" name="fecha_recargo" value="<? echo $re->pago;?>" />
                <input type="hidden" name="action" value="recargos" />
                <th> <center> <input type="submit" name="rec_reimprime" value="Reimprimir" /> </center></th>
                </form>
        <?php    
            }
			echo '</tr>';
			$tot;
		}
		?>
		</tbody>
		<tfoot>
			<tr>
				<th colspan="4" style="text-align: left;padding:2px 10px;">TOTAL RECARGOS POR PAGAR:  $ <?php moneda($tot);?></th>
			</tr>
		</tfoot>
		</table>
		<?php
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
				<a href="include/html/box_cliente_cuenta_saldar.php?width=500&height=430&c=<? echo $cuenta;?>" class="thickbox boton esqRedondas sombra">Saldar cuenta</a>
				<a href="include/html/box_cuenta_elim.php?width=480&height=250&cte=<?php echo $r->cliente;?>&cta=<?php echo $cuenta;?>" class="thickbox boton esqRedondas sombra">Elim. Cuenta</a>
				<a href="?pg=3da&cl=<?php echo $cliente;?>" class="boton esqRedondas sombra">Historial</a>			
				<?php
                     #if($r->tipo_pago == 4)
                     if($tp == "MENSUAL"){
               	?>
				 <a href="include/html/box_cliente_cuenta_interes.php?width=500&height=430&c=<? echo $cuenta;?>" class="thickbox boton esqRedondas sombra">Solo interes</a>
               	<?php
                   		}
               	?>
      			<a href="include/html/box_cliente_cuenta_pagare.php?height=500&width=400&c=<?php echo $cuenta;?>&cl=<?php echo $cliente; ?>" class="thickbox boton esqRedondas sombra">Imp. Pagare</a>
				<a href="include/html/box_cliente_reimprime_cuenta.php?width=500&height=430&c=<? echo $cuenta;?>" class="thickbox boton esqRedondas sombra">Re. Prestamo</a>
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
			<th align="left">CARGO</th>
			<th>ABONO</th>
			<th align="center">F. PAGO</th>
			<th align="left"></th>
			<th align="left"></th>
		</tr>
	</thead>
	<tbody>
	<?php
	$sql = "SELECT * FROM pagos WHERE cuenta = ".$cuenta ." ORDER BY id ASC";
    	//echo $sql;
	$res = $db->query($sql);
	$i = 0;
	while($r = $db->fetchNextObject($res)) {
		$i++;
		?>
		<tr>
			<td width="10"><?php echo $i;?></td>
			<td>
				<?php
				if($r->pago == 0){
					getFecha($r->fechaPago);
				}else {
					getFecha($r->fecha);
				} 
				?>
			</td>
			<td>$ <?php moneda($r->pago); ?></td>
			<td>$ <?php moneda($r->pago_real); ?></td>
			<td><?php echo $r->fechaPago;?></td>
			<th style="text-align: center;">
				<?php
				if($r->estado == 0) {
					$pago_acum += getPagoRedondo($r->pago);
					if($saldo > $pago_acum){
						?>
							<form name="frm_<?php echo $r->id;?>" action="include/php/sys_modelo.php" method="post">
								<input type="hidden" name="numpago" value="<? echo $i;?>" />
								<input type="text" name="pago" value="<? echo $pago_acum;?>" size="7" />
								<input type="hidden" name="cl" value="<? echo $_GET['cl'];?>" />
								<input type="hidden" name="c" value="<? echo $cuenta;?>" />
								<input type="hidden" name="pid" value="<? echo $r->id;?>" />
								<input type="hidden" name="action" value="cuenta_pagar" />
								<input type="submit" value="ABONAR" />
							</form>
						<?php
					}elseif(($pago_acum - $saldo) > 0){
						$pago_acum = $saldo;
						?>
							<form name="frm_<?php echo $r->id;?>" action="include/php/sys_modelo.php" method="post">
								<input type="hidden" name="numpago" value="<? echo $i;?>" />
								<input type="text" name="pago" value="<? echo $pago_acum;?>" size="7" />
								<input type="hidden" name="cl" value="<? echo $_GET['cl'];?>" />
								<input type="hidden" name="c" value="<? echo $cuenta;?>" />
								<input type="hidden" name="pid" value="<? echo $r->id;?>" />
								<input type="hidden" name="action" value="cuenta_pagar" />
								<input type="submit" value="ABONAR" />
							</form>
						<?php
					}else{
						$pago_acum = $saldo;
						?>
							<form name="frm_<?php echo $r->id;?>" action="include/php/sys_modelo.php" method="post">
								<input type="hidden" name="numpago" value="<? echo $i;?>" />
								<input type="text" name="pago" value="<? echo $r->pago_real;?>" size="7" />
								<input type="hidden" name="cl" value="<? echo $_GET['cl'];?>" />
								<input type="hidden" name="c" value="<? echo $cuenta;?>" />
								<input type="hidden" name="pid" value="<? echo $r->id;?>" />
								<input type="hidden" name="action" value="cuenta_pagar" />
								<input type="submit" value="ABONAR" />
							</form>
						<?php
					}
				}elseif($r->estado == 1) {
					if($r->pago == 0){
						?>
							<form name="frm_<?php echo $r->id;?>" action="include/php/sys_modelo.php" method="post">
								<input type="hidden" name="numpago" value="<? echo $i;?>" />
								<input type="hidden" name="pago" value="<? echo $pago_acum;?>" />
								<input type="hidden" name="cl" value="<? echo $_GET['cl'];?>" />
								<input type="hidden" name="c" value="<? echo $cuenta;?>" />
								<input type="hidden" name="pid" value="<? echo $r->id;?>" />
								<input type="hidden" name="action" value="cuenta_pagar" />
								<input type="submit" value="REIMPRIMIR" />
							</form>
						<?php
						#echo '<p style="color:#4A9E41;">PAGO DE INTERES</p>';
					}else {
						?>
							<form name="frm_<?php echo $r->id;?>" action="include/php/sys_modelo.php" method="post">
								<input type="hidden" name="numpago" value="<? echo $i;?>" />
								<input type="hidden" name="pago" value="<? echo $pago_acum;?>" />
								<input type="hidden" name="cl" value="<? echo $_GET['cl'];?>" />
								<input type="hidden" name="c" value="<? echo $cuenta;?>" />
								<input type="hidden" name="pid" value="<? echo $r->id;?>" />
								<input type="hidden" name="action" value="cuenta_pagar" />
								<input type="submit" value="REIMPRIMIR" />
							</form>
						<?php
						#echo 'PAGADO';
					}
				}elseif($r->estado == 3) {
						?>
							<form name="frm_<?php echo $r->id;?>" action="include/php/sys_modelo.php" method="post">
								<input type="hidden" name="numpago" value="<? echo $i;?>" />
								<input type="hidden" name="pago" value="<? echo $r->pago_real;?>" />
								<input type="hidden" name="cl" value="<? echo $_GET['cl'];?>" />
								<input type="hidden" name="c" value="<? echo $cuenta;?>" />
								<input type="hidden" name="pid" value="<? echo $r->id;?>" />
								<input type="hidden" name="action" value="cuenta_pagar" />
								<input type="submit" value="REIMPRIMIR" />
							</form>
						<?php
					#echo '<p style="color:#C65F00;">PAGO ANTICIPADO</p>';
				}
				?>
			</th>
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
