<?php
session_start();
if(isset($_SESSION["REQUIRED1"])){
	## obtener dia de fecha 
	$dia = date("N", strtotime(date($_POST["fecha"])));
	$ndia = substr($_POST["fecha"], -2);
	switch($_POST["tp"])
	{
		case 1:	
				?><option value="nd">DIAS DE PAGO</option>
				<option value="1"<?if($dia==1){echo ' selected="selected"';}?>>LUNES</option>
				<option value="2"<?if($dia==2){echo ' selected="selected"';}?>>MARTES</option>
				<option value="3"<?if($dia==3){echo ' selected="selected"';}?>>MIERCOLES</option>
				<option value="4"<?if($dia==4){echo ' selected="selected"';}?>>JUEVES</option>
				<option value="5"<?if($dia==5){echo ' selected="selected"';}?>>VIERNES</option>
				<option value="6"<?if($dia==6){echo ' selected="selected"';}?>>SABADO</option>
				<?php
				break;
		case 2:
				?><option value="nd">DIAS DE PAGO</option>
				<option value="1"<?if($dia==1){echo ' selected="selected"';}?>>LUNES</option>
				<option value="2"<?if($dia==2){echo ' selected="selected"';}?>>MARTES</option>
				<option value="3"<?if($dia==3){echo ' selected="selected"';}?>>MIERCOLES</option>
				<option value="4"<?if($dia==4){echo ' selected="selected"';}?>>JUEVES</option>
				<option value="5"<?if($dia==5){echo ' selected="selected"';}?>>VIERNES</option>
				<option value="6"<?if($dia==6){echo ' selected="selected"';}?>>SABADO</option>
				<?php
				break;
		case 3:
				?>
				<option value="nd">DIAS DE PAGO</option>
				<option value="1-16"<?if(($ndia==6) || ($ndia==1)){echo ' selected="selected"';}?>>16 Y 1 DE CADA MES</option>
				<option value="2-17"<?if(($ndia==17) || ($ndia==2)){echo ' selected="selected"';}?>>17 Y 2 DE CADA MES</option>
				<option value="2-16"<?if(($ndia==2) || ($ndia==16)){echo ' selected="selected"';}?>>2 Y 16 DE CADA MES</option>
				<option value="8-22"<?if(($ndia==8) || ($ndia==22)){echo ' selected="selected"';}?>>8 Y 22 DE CADA MES</option>
				<option value="15-30"<?if(($ndia==15) || ($ndia==30)){echo ' selected="selected"';}?>>15 Y 30 DE CADA MES</option>
				<option value="1-15"<?if(($ndia==15) || ($ndia==1)){echo ' selected="selected"';}?>>15 Y 1 DE CADA MES</option>
				<option value="6-21"<?if(($ndia==6) || ($ndia==21)){echo ' selected="selected"';}?>>6 Y 21 DE CADA MES</option>
				<option value="3-18"<?if(($ndia==3) || ($ndia==18)){echo ' selected="selected"';}?>>3 Y 18 DE CADA MES</option>
				<option value="4-18"<?if(($ndia==4) || ($ndia==18)){echo ' selected="selected"';}?>>4 Y 18 DE CADA MES</option>
				<?php
				break;
		case 4:
				?>
				<option value="nd">DIAS DE PAGO</option>
				<option value="1"<?if($ndia==1){echo ' selected="selected"';}?>>1 DE CADA MES</option>
				<option value="16"<?if($ndia==16){echo ' selected="selected"';}?>>16 DE CADA MES</option>
				<?php
				break;
		default:
				?>
				<option value="nd">DIAS DE PAGO</option>
				<?php
	}
}
?>
