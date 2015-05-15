<?php
	if($_POST){
		header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
		header("Content-type:   application/x-msexcel; charset=utf-8");
		header("Content-Disposition: attachment; filename=abc.xsl"); 
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
	
		$sql = $_POST["consulta"];
		$res = mysql_query($sql);
		genTabla( $res, 0 );
	} else {
		?>
		<form action="" method="post">
		<textarea name="consulta" style="width:600px;height:85px;"><?= $_POST["consulta"];?></textarea>
		<br />
		<input type="submit">
		</form>
		<?php
	}
?>
