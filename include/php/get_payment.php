<?php
require_once("sys_db.class.php");
require_once("fun_global.php");
require_once("../conf/Config.php");
$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);

?>
<table>
<?php
$cobrador = 'COB1';
$res = mysql_query("call sp_get_payments('$cobrador')");
while($p = mysql_fetch_array($res))
{
	echo "<tr>";
	echo "<td>".$p["CLIENTE"]."</td>";
	echo "<td>".$p["F. PAGO"]."</td>";
	echo "<td>".$p["F. COBRO"]."</td>";
	echo "<td>".$p["PAGOS"]."</td>";
	echo "<td>".$p["ABONOS"]."</td>";
	echo "<td>".$p["RECARGOS"]."</td>";
	echo "<td>".$p["AB. RECARGO"]."</td>";
	echo "</tr>";
}
?>
</table>
