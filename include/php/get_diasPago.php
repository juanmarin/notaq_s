<?php
@session_start();
if(isset($_SESSION["REQUIRED1"])){
	## obtener dia de fecha 
	$dia = date("N", strtotime(date($_POST["fecha"])));
	$ndia = substr($_POST["fecha"], -2);
	switch($_POST["tp"])
	{
		case 1:	
				?>
				<option value="nd">DIAS DE PAGO</option>
				<option value="1"<?=($dia==1)?' selected="selected"':'';?>>LUNES</option>
				<option value="2"<?=($dia==2)?' selected="selected"':'';?>>MARTES</option>
				<option value="3"<?=($dia==3)?' selected="selected"':'';?>>MIERCOLES</option>
				<option value="4"<?=($dia==4)?' selected="selected"':'';?>>JUEVES</option>
				<option value="5"<?=($dia==5)?' selected="selected"':'';?>>VIERNES</option>
				<option value="6"<?=($dia==6)?' selected="selected"':'';?>>SABADO</option>
				<option value="7"<?=($dia==7)?' selected="selected"':'';?>>DOMINGO</option>
				<?php
				break;
		case 2:
				?><option value="nd">DIAS DE PAGO</option>
				<option value="1"<?=($dia==1)?' selected="selected"':'';?>>LUNES</option>
				<option value="2"<?=($dia==2)?' selected="selected"':'';?>>MARTES</option>
				<option value="3"<?=($dia==3)?' selected="selected"':'';?>>MIERCOLES</option>
				<option value="4"<?=($dia==4)?' selected="selected"':'';?>>JUEVES</option>
				<option value="5"<?=($dia==5)?' selected="selected"':'';?>>VIERNES</option>
				<option value="6"<?=($dia==6)?' selected="selected"':'';?>>SABADO</option>
				<option value="7"<?=($dia==7)?' selected="selected"':'';?>>DOMINGO</option>
				<?php
				break;
		case 3:
				?>
				<option value="nd">DIAS DE PAGO</option>
				<option value="10-25"<?=(($ndia==10)	|| ($ndia==25))?' selected="selected"':'';?>>10 Y 25 DE CADA MES</option>
				<option value="1-16"<?=(($ndia==6)	|| ($ndia==1))?' selected="selected"':'';?>>16 Y 1 DE CADA MES</option>
				<option value="2-17"<?=(($ndia==17)	|| ($ndia==2))?' selected="selected"':'';?>>17 Y 2 DE CADA MES</option>
				<option value="2-16"<?=(($ndia==2)	|| ($ndia==16))?' selected="selected"':'';?>>2 Y 16 DE CADA MES</option>
				<option value="8-22"<?=(($ndia==8)	|| ($ndia==22))?' selected="selected"':'';?>>8 Y 22 DE CADA MES</option>
				<option value="15-30"<?=(($ndia==15)	|| ($ndia==30))?' selected="selected"':'';?>>15 Y 30 DE CADA MES</option>
				<option value="1-15"<?=(($ndia==15)	|| ($ndia==1))?' selected="selected"':'';?>>15 Y 1 DE CADA MES</option>
				<option value="6-21"<?=(($ndia==6)	|| ($ndia==21))?' selected="selected"':'';?>>6 Y 21 DE CADA MES</option>
				<option value="3-18"<?=(($ndia==3)	|| ($ndia==18))?' selected="selected"':'';?>>3 Y 18 DE CADA MES</option>
				<option value="4-18"<?=(($ndia==4)	|| ($ndia==18))?' selected="selected"':'';?>>4 Y 18 DE CADA MES</option>
				<?php
				break;
		case 4:
				?>
				<option value="nd">DIAS DE PAGO</option>
				<option value="1"<?=($ndia==1)?' selected="selected"':'';?>>1 DE CADA MES</option>
				<option value="16"<?=($ndia==16)?' selected="selected"':'';?>>16 DE CADA MES</option>
				<?php
				break;
		default:
				?>
				<option value="nd">DIAS DE PAGO</option>
				<?php
	}
}
?>
