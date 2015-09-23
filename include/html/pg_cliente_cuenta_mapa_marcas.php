<?php
@session_start();
require_once("../../include/php/sys_db.class.php");
require_once("../../include/conf/Config_con.php");
$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
?>
<!DOCTYPE html>
<html> 
<head> 
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" /> 
  <title>Google Maps Multiple Markers</title> 
  <script src="http://maps.google.com/maps/api/js?sensor=false" 
          type="text/javascript"></script>
</head> 
<body>
  <div id="map" style="width:100%; min-width:300px; height: 400px;"></div>
	
  <script type="text/javascript">
    var colors = [
    	'../../img/green-dot.png',
    	'../../img/yellow-dot.png',
    	'../../img/red-dot.png',
    	'../../img/purple-dot.png',
    	'../../img/blue-dot.png'
    ];
    var locations = [
    	<?php
    	//-VERIFICAR SI EL USUARIO ES COBRADOR
    	$ftrcobrador = ($_SESSION["U_NIVEL"]==3)?"AND cobrador = '".$_SESSION["USERNAME"]."'":"";
    	//- VERDE - CLIENTES DE 0 A 7 DÍAS VENCIDOS
    	if(!isset($_GET["marks"]) || $_GET["marks"]==1)
    	{
			$sql1 = "select * from (
				select concat(cl.nombre,' ',cl.apellidop,' ',cl.apellidom) nombre, cl.c_cobrador cobrador
				,pa.cuenta cuenta,pa.cliente cliente,pa.fecha fecha
				,co.latitud latitud, co.longitud longitud, co.zoom zoom
				from cuentas cu 
				left join clientes cl on cl.id=cu.cliente 
				left join pagos pa on pa.cuenta=cu.id 
				right join coordenadas co on co.cliente=pa.cliente
				where cu.estado=0 and pa.estado=0 group by pa.cliente having min(pa.fecha)) as clientes 
				where fecha between '".date('Y-m-d' , strtotime('- 7 days'))."' and '".date("Y-m-d")."' $ftrcobrador";
			$res = $db->query($sql1);
			$coma = '';
			$cont = 1;
			while($co = $db->fetchNextObject($res))
			{
				echo $coma."['".$co->nombre."', ".$co->latitud.", ".$co->longitud.", $cont, colors[0]]";
				$coma = ",";
				$cont++;
			}
    	}
    	//- AMARILLO - CLIENTES DE 8 A 30 DÍAS VENCIDOS
    	if(!isset($_GET["marks"]) || $_GET["marks"]==2)
    	{
			$sql2 = "select * from (
					select concat(cl.nombre,' ',cl.apellidop,' ',cl.apellidom) nombre, cl.c_cobrador cobrador
				,pa.cuenta cuenta,pa.cliente cliente,pa.fecha fecha 
				,co.latitud latitud, co.longitud longitud, co.zoom zoom
				from cuentas cu 
				left join clientes cl on cl.id=cu.cliente 
				left join pagos pa on pa.cuenta=cu.id 
				right join coordenadas co on co.cliente=pa.cliente
				where cu.estado=0 and pa.estado=0 group by pa.cliente having min(pa.fecha)) as clientes 
				where fecha between '".date('Y-m-d' , strtotime('- 30 days'))."' and '".date('Y-m-d' , strtotime('- 8 days'))."' $ftrcobrador";
			$res = $db->query($sql2);
			while($co = $db->fetchNextObject($res))
			{
				echo $coma."['".$co->nombre."', ".$co->latitud.", ".$co->longitud.", $cont, colors[1]]";
				$coma = ",";
				$cont++;
			}
    	}
    	//- ROJO - CLIENTES DE 31 A 60 DÍAS VENCIDOS
    	if(!isset($_GET["marks"]) || $_GET["marks"]==3)
    	{
			$sql3 = "select * from (
					select concat(cl.nombre,' ',cl.apellidop,' ',cl.apellidom) nombre, cl.c_cobrador cobrador
				,pa.cuenta cuenta,pa.cliente cliente,pa.fecha fecha 
				,co.latitud latitud, co.longitud longitud, co.zoom zoom
				from cuentas cu 
				left join clientes cl on cl.id=cu.cliente 
				left join pagos pa on pa.cuenta=cu.id 
				right join coordenadas co on co.cliente=pa.cliente
				where cu.estado=0 and pa.estado=0 group by pa.cliente having min(pa.fecha)) as clientes 
				where fecha between '".date('Y-m-d' , strtotime('- 60 days'))."' and '".date('Y-m-d' , strtotime('- 31 days'))."' $ftrcobrador";
			$res = $db->query($sql3);
			while($co = $db->fetchNextObject($res))
			{
				echo $coma."['".$co->nombre."', ".$co->latitud.", ".$co->longitud.", $cont, colors[2]]";
				$coma = ",";
				$cont++;
			}
    	}
    	//- PURPURA - CLIENTES DE MAS DE 61 DÍAS VENCIDOS
    	if(!isset($_GET["marks"]) || $_GET["marks"]==4)
    	{
			$sql4 = "select * from (
					select concat(cl.nombre,' ',cl.apellidop,' ',cl.apellidom) nombre, cl.c_cobrador cobrador
				,pa.cuenta cuenta,pa.cliente cliente,pa.fecha fecha 
				,co.latitud latitud, co.longitud longitud, co.zoom zoom
				from cuentas cu 
				left join clientes cl on cl.id=cu.cliente 
				left join pagos pa on pa.cuenta=cu.id 
				right join coordenadas co on co.cliente=pa.cliente
				where cu.estado=0 and pa.estado=0 group by pa.cliente having min(pa.fecha)) as clientes 
				where fecha < '".date('Y-m-d' , strtotime('- 61 days'))."' $ftrcobrador";
			$res = $db->query($sql4);
			while($co = $db->fetchNextObject($res))
			{
				echo $coma."['".$co->nombre."', ".$co->latitud.", ".$co->longitud.", $cont, colors[3]]";
				$coma = ",";
				$cont++;
			}
    	}
    	?>
    ];

    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 10,
      center: new google.maps.LatLng(29.116446876790647, -110.95934242327883),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var infowindow = new google.maps.InfoWindow();

    var marker, i;

    for (i = 0; i < locations.length; i++) {  
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        map: map,
        icon: locations[i][4]
      });

      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(locations[i][0]);
          infowindow.open(map, marker);
        }
      })(marker, i));
    }
  </script>
  <?php 
  //echo $sql2;
  ?>
</body>
</html>
