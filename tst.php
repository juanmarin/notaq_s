<?php
include "include/php/fun_global.php";
$texto = "EN 1972, en un pequeño pueblo de Georgia siguen estando latentes los problemas interraciales. La muerte, en extrañas circunstancias, de un oficial de color perteneciente al ejército de los Estados Unidos provoca que se abra una investigación para esclarecer los hechos. El Mayor Kendall Laird (John Lithgow), con la ayuda de los padres del joven (Morgan Freeman y CCH Pounder), hará todo lo posible para que la verdad salga a la luz";

echo $texto . "<br /> <br />";

$txt = cortarTexto($texto);

echo $txt[0];
?>