<?php
session_start();
$raiz = "cerison";
require('fpdf.php');
require_once("../php/sys_db.class.php");
require_once("../../conf/Config_con.php");
require('../../conf/conecta_pdf.php');
class PDF extends FPDF
{
    //Tabla Sencilla
function BasicTable($header,$data)
{
    $this->SetFont('times','B',10);
    //Header
    $w=array(30,102,29,30);
    //Header
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],5,$header[$i],1,0,'C');
    $this->Ln();
    //Data
    $this->SetFont('times','',8);
    foreach ($data as $eachResult)
    {
        $importe = ($eachResult["cant"]* $eachResult["prec"]);
        $this->Cell(30,5,$eachResult["cant"],0,0,'C');
        $this->Cell(102,5,$eachResult["descr"],0);
        $this->Cell(20,5,number_format($eachResult["prec"],2),0,0,'R');
        $this->Cell(30,5,number_format($importe,2),0,0,'R');
        $this->Ln();
    }

/*
   function Footer()
   {
   //Posición: a 1,5 cm del final
   $this->SetY(-15);
   //times italic 8
   $this->SetFont('times','I',8);
   //Número de página
   $this->Cell(0,10,'Página '.$this->PageNo().'',0,0,'C');
   }
*/
}
}
$pdf=new PDF();
$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
$search_pres = $_GET["pres_id"];
$usr = $_GET["usr"];
$usr_psto = $_GET["puesto"];
//Obtenemos datos del presupuesto
$sql = "SELECT * FROM presupuesto WHERE id = '".$search_pres."'";
$res = $db->query( $sql );
$pr = $db->fetchNextObject( $res );
//Obtenemos los datos del cliente en base a la clave
$sql2 = "SELECT * FROM clientes WHERE clave = '".$pr->clienclave."'";
$res2 = $db->query( $sql2 );
$ct = $db->fetchNextObject( $res2 );
//Column titles
$header=array('CANTIDAD','DESCRIPCIÓN','PRECIO','IMPORTE');
//*** Load MySQL Data ***//
$strSQL = "SELECT * FROM relacion WHERE pres = '".$search_pres."'";
$objQuery = mysql_query($strSQL);
$resultData = array();
for ($i=0;$i<mysql_num_rows($objQuery);$i++) {
    $result = mysql_fetch_array($objQuery);
    array_push($resultData,$result);
    $rows = mysql_num_rows($objQuery);
}
// Alineacion de los Campos
/* $wl = Width Left */
$wl = 155;
$wl2 = 155;
/* $wr = Width Rigth */
$wr = 36;
$pdf->SetFont('times','B',18);
$pdf->AddPage();
$pdf->Image('../../images/cerison_logo.png',10,8,40,20,"PNG");
$pdf->Cell(80);
$pdf->Cell(50,8,'CERCOS ARQUITEC, S.A. DE C.V.',0,0,'C');
$pdf->Ln(5);
//*** Set font for subtittles ***//
$pdf->SetFont('times','B',8);
$pdf->Cell(215,10,'RFC : CAR-080410-V57',0,0,'C');
$pdf ->Ln(5);
$pdf->Cell(215,10,'Ave. Jorge Valdez Muñoz No. 186 e/ Reyes y Yañes Col. Jesús García',0,0,'C');
$pdf->Ln(5);
$pdf->Cell(215,10,'C.P. 83140  TEL . 2142122',0,0,'C');
$pdf->Ln(5);
$pdf->Cell(215,10,'TEL . 2143822.  HERMOSILLO, SONORA',0,0,'C');
$pdf->Ln(5);
$pdf->Cell(215,10,'E-mail: cerison@prodigy.net.mx',0,0,'C');
$pdf->Ln(10);
$pdf->SetFont('times','B',14);
$pdf->Cell(191,6,'PRESUPUESTO',1,0,'C');
// ---> datos del proyecto ----> //
$pdf->Ln();
$pdf->SetFont('times','',10);
$pdf->Cell($wl,6,'NOMBRE: '.$ct->empresa.'','LT',0,'L');
$pdf->Cell($wr,6,'NUM:   '.$pr->id.'','R',0,'L');
$pdf->Ln();
$pdf->Cell($wl,6,'ATENCIÓN: '.$ct->contacto.'','L',0,'L');
$pdf->Cell($wr,6,'FECHA: '.date('d/m/Y',strtotime($pr->fecha)).' ','R',0,'L');
$pdf->Ln();
$pdf->Cell(135,6,'FACTURAR A: '.$ct->facturar_a.'','L',0,'L');
$pdf->Cell(56,6,'EMAIL: '.$ct->correo.'','R',0,'L');
$pdf->Ln();
$pdf->Cell($wl,6,'DIRECCIÓN: '.$ct->direccion.'','L',0,'L');
$pdf->Cell($wr,6,'TEL: '.$ct->telefono.'','R',0,'L');
$pdf->Ln();
$pdf->Cell(191,6,'CIUDAD: '.$ct->ciudad.'','LR',0,'L');
$pdf->Ln();
##$pdf->Cell(191,6,'OBRA: '.$pr->obra.'','LR',0,'L');
$pdf->MultiCell(191,4,'OBRA: '.$pr->obra.'','LR',‘L’);

###### Fin datos del proyecto #####
$pdf->BasicTable($header,$resultData);
##### Calculamos el numero de renglones a ocupar ###
$pdf->SetXY(150,200);
##### seteamos el numero de saltos que ocupamos #####
$pdf->Ln();
###### ----------------------------------###########
$pdf->SetFont('times','B',10);
$pdf->Cell($wl2,6,"SUBTOTAL :    $",0,0,'R');
$subtotal = $pr->subt+$pr->desc-$pr->inst;
$pdf->Cell(32,6,''.number_format($pr->subt+$pr->desc-$pr->inst,2).'',0,0,'R');
$pdf->Ln(3);
$pdf->Cell($wl2,6,'DESCUENTO :    $',0,0,'R');
$pdf->Cell(32,6,''.number_format($pr->desc,2).'',0,0,'R');
$pdf->Ln(3);
$pdf->Cell($wl2,6,'INSTALACION Y SUPERVISION DE LA OBRA :    $',0,0,'R');
$pdf->Cell(32,6,''.number_format($pr->inst,2).'',0,0,'R');
$pdf->Ln(3);
$pdf->Cell(350,3,'______________',0,0,'C');
$pdf->Ln(3);
$pdf->Cell($wl2,6,'TOTAL DE MATERIALES E INSTALACION S.E.U.O.S. :    $',0,0,'R');
$pdf->Cell(32,6,' '.number_format($subtotal + $pr->inst - $pr->desc,2).'',0,0,'R');
$pdf->Ln(3);
$pdf->Cell($wl2,6,'IVA :    $',0,0,'R');
$pdf->Cell(32,6,''.number_format($pr->iva,2).'',0,0,'R');
$pdf->Ln(3);
$pdf->Cell(350,3,'______________',0,0,'C');
$pdf->Ln(3);
$pdf->Cell($wl2,6,'TOTAL :    $',0,0,'R');
$pdf->Cell(32,6,''.number_format($pr->total,2).'',0,0,'R');
$pdf->Ln(3);
$pdf->Cell($wr,6,'NOTA :',0,0,'L');
$pdf->Ln();
$pdf->SetFont('times','',10);
$pdf->MultiCell(80,4,$pr->nota,0,‘L’);
$pdf->Ln(0.5);
$pdf->Cell(60,8,'ATENTAMENTE',0,0,'C');
$pdf->Ln();
$pdf->Cell(60,5,'___________________________',0,0,'C');
$pdf->Cell(180,5,'___________________________',0,0,'C');
$pdf->Ln();
$pdf->Cell(60,5,''.$usr.'',0,0,'C');
$pdf->Cell(180,5,'ACEPTO',0,0,'C');
$pdf->Ln();
$pdf->Cell(60,5,'GERENTE DE CONSTRUCCION',0,0,'C');
$pdf->Cell(180,5,'CLIENTE',0,0,'C');

$pdf->Output("presupuesto".$search_pres.".pdf","D");
?>
<html>
</head>
<meta http-equiv="refresh" content="0;url=<?php echo $raiz; ?>/?pg=inicio" />
</body>
</html>
