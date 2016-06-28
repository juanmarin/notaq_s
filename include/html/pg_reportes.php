<p class="title">Portada &raquo; Listado de clientes</p>
<table>
<caption>CUENTAS ABIERTAS</caption>
<thead>
	<tr>
		<th colspan="1" style="text-align:center">ID</th>
		<th colspan="1" style="text-align:center">CLIENTE</th>
		<th colspan="1" style="text-align:center">MONTO PRESTADO</th>
		<th colspan="1" style="text-align:center">ACCIONES</th>
	</tr>
</thead>
<tbody>
	<?php
			require("include/php/paginacion.php");
	         //Realizamos la conexion a la BD
	         $cn = mysql_connect("localhost", "root", "99_shamp00");
	         mysql_select_db("notaq", $cn);
	         $query = "SELECT clientes.id, clientes.nombre, clientes.apellidom, clientes.apellidop, clientes.id, cuentas.cantidad FROM clientes, cuentas WHERE clientes.id = cuentas.cliente AND cuentas.estado = 0";
	         $rsT =  mysql_query($query, $cn);
	         $total = mysql_num_rows($rsT);
	         $pg = $_GET['page'];
	         $cantidad = 8; //Cantidad de registros que se desea mostrar por pagina
	         $paginacion = new paginacion($cantidad, $pg);
	         $desde = $paginacion->getFrom();
	         $query = "SELECT * FROM clientes, cuentas WHERE clientes.id = cuentas.cliente LIMIT $desde, $cantidad";
	         $rs = mysql_query($query, $cn);
         while ($row = mysql_fetch_assoc($rs)) {
		?>
		<tr>
			<th colspan="1" style="text-align:center"><?echo $row['id'];?></th>
			<th colspan="1" style="text-align:center"><?echo $row['nombre'] . " ".$row['apellidop']." " .$row['apellidom'];?></td>
			<th colspan="1" style="text-align:center">$ <?echo $row['cantidad'];?></td>
			<td width="80"><a href="?pg=2e&cl=<?echo $row['id'];?>" class="tboton sombra esqRedondas cuenta">Cuenta</a></td>
		</tr>
		<?
	}
	?>
</tbody>
<tfoot>
	<tr>
		<th colspan="4" style="text-align:center">
            <div class="paginacion">
                 <?php
	                  $url = "?pg=3";
                      //Si se desea pasar otros parámetros se hace así
	                  //(Ejemplo) $url = "ejemploPaginacion.php?catLibro=$idCat"
	                  $classCss = "numPages";
	                  //Clase CSS que queremos asignarle a los links
	                  $back = "&laquo;Atras";
	                  $next = "Siguiente&raquo;";
	                  $paginacion->generaPaginacion($total, $back, $next, $url, $classCss);
                 ?>
            </div>
        </th>
	</tr>
</tfoot>
</table>
