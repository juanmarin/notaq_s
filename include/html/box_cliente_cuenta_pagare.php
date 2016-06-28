<?php 
session_start();
header('Content-Type: text/html; charset=iso-8859-1');
require_once "../php/fun_global.php"; 
require_once("../php/sys_db.class.php");
require_once("../conf/Config_con.php");
$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);

## INFORMACION DE LA CUENTA
$cuenta = $_GET["c"];
$cliente = $_GET["cl"];
?>
<form action="imprimir_pagare/imprime_pagare.php" method="post" target="_blank">
<input type="hidden" name="c" value="<?php echo $cuenta; ?>" />
<input type="hidden" name="cl" value="<?php echo $cliente; ?>" />
<input type="hidden" name="pp" value="<?php echo $_SESSION['pp']; ?>" />
	<table>
	<tbody>
		<tr>
			<th>AGREGAR NOTAS AL PAGARE</th>
		</tr>
		<tr>
			<td>Nota número 1:<br /> <textarea name="nota1"></textarea></td>
		</tr>
		<tr>
			<td>Nota número 2:<br /> <textarea name="nota2"></textarea></td>
		</tr>
		<tr>
			<td>Nota número 3:<br /> <textarea name="nota3"></textarea></td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<th><input type="submit" name="pagare" value="Vista previa" /></th>
		</tr>
	</tfoot>
	</table>
</form>
