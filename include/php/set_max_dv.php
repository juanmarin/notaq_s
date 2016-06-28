<?php
require_once("sys_db.class.php");
require_once("fun_global.php");
//require_once("../conf/Config_con.php");
require_once("/home/confianzp/confianzp.com/include/conf/Config_con.php");
$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
$sql = "INSERT INTO max_dv(cliente, dv) 
SELECT * 
FROM (
    SELECT * 
    FROM (
        SELECT cliente, MAX(DATEDIFF(DATE(NOW()), fecha)) AS days
        FROM pagos 
        WHERE estado = 0 AND fecha <= DATE(NOW())
        GROUP BY cliente
    ) sq
) sq2
ON DUPLICATE KEY UPDATE dv = IF(dv < sq2.days, sq2.days, dv)";
echo "<p>$sql</p>";
$res= $db->query($sql);
?>
