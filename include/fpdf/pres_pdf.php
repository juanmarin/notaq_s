<?php
session_start();
?>
<?php
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
    $this->SetFont('Arial','',10);
	//Header
	$w=array(30,102,29,30);
	//Header
	for($i=0;$i<count($header);$i++)
		$this->Cell($w[$i],7,$header[$i],1,0,'C');
	$this->Ln();
	//Data

	foreach ($data as $eachResult)
	{
	    $importe = ($eachResult["cant"]* $eachResult["prec"]);
		$this->Cell(30,6,$eachResult["cant"],1,0,'C');
		$this->Cell(102,6,$eachResult["descr"],1);
		$this->Cell(29,6,number_format($eachResult["prec"],2),1,0,'R');
		$this->Cell(30,6,number_format($importe,2),1,0,'R');
		$this->Ln();
	}
}

    function Footer()
    {
        //Posición: a 1,5 cm del final
        $this->SetY(-15);
        //Arial italic 8
        $this->SetFont('Arial','I',8);
        //Número de página
        $this->Cell(0,10,'Página '.$this->PageNo().'',0,0,'C');
    }
}
$pdf=new PDF();
$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
$search_pres = $_GET["pres_id"];
$cte = $_GET¨["cte"];
$usr = $_GET["usr"];
//Obtenemos datos del presupuesto
$sql = "SELECT * FROM presupuesto WHERE id = '".$search_pres."'";
$res = $db->query( $sql );
$pr = $db->fetchNextObject( $res );
//Obtenemos los datos del cliente en base a la clave
$sql2 = "SELECT * FROM clientes WHERE clave = '".$pr->clienclave."'";
$res2 = $db->query( $sql2 );
$ct = $db->fetchNextObject( $res2 );
//Column titles
$header=array('Cantidad','Descripcion','Precio','Importe');
//*** Load MySQL Data ***//
$strSQL = "SELECT * FROM relacion WHERE pres = '".$search_pres."'";
$objQuery = mysql_query($strSQL);
$resultData = array();
for ($i=0;$i<mysql_num_rows($objQuery);$i++) {
    $result = mysql_fetch_array($objQuery);
    array_push($resultData,$result);
}
// Alineacion de los Campos
/* $wl = Width Left */
$wl = 155;
$wl2 = 170;
/* $wr = Width Rigth */
$wr = 50;
$pdf->SetFont('Arial','B',13);
$pdf->AddPage();

$pdf->Image('../../images/cerison_logo.png',10,8,45,30,"PNG");
$pdf->Cell(80);
$pdf->Cell(50,10,'CERCOS ARQUITECT, S.A. DE C.V.',0,0,'C');
$pdf->Ln(5);
//*** Set font for subtittles ***//
$pdf->SetFont('Arial','B',8);
$pdf->Cell(215,10,'RFC : CAR-080410-V57',0,0,'C');
$pdf ->Ln(5);
$pdf->Cell(215,10,'Ave. Jorge Valdez Muñoz No. 186 e/ Reyes y Yañes Col. Jesús García',0,0,'C');
$pdf->Ln(5);
$pdf->Cell(215,10,'C.P. 83140  TEL . 2142122',0,0,'C');
$pdf->Ln(5);
$pdf->Cell(215,10,'TEL . 2142822 y 3020757 HERMOSILLO, SONORA',0,0,'C');
$pdf->Ln(5);
$pdf->Cell(215,10,'E-mail: cerison@prodigy.net.mx',0,0,'C');
$pdf->Ln(10);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(192,8,'PRESUPUESTO',1,0,'C');
$pdf->Ln();
$pdf->SetFont('Arial','',10);
$pdf->Cell($wl,8,'Nombre: '.$ct->empresa.'',0,0,'L');
$pdf->Cell($wr,8,'Num:   '.$pr->id.'',0,0,'L');
$pdf->Ln();
$pdf->Cell($wl,8,'Atencion: '.$ct->contacto.'',0,0,'L');
$pdf->Cell($wr,8,'Fecha: '.$pr->fecha.'',0,0,'L');
$pdf->Ln();
$pdf->Cell($wl,8,'Facturar a: '.$ct->facturar_a.'',0,0,'L');
$pdf->Ln();
$pdf->Cell($wl,8,'Direccion: '.$ct->direccion.'',0,0,'L');
$pdf->Cell($wr,8,'Tel: '.$ct->telefono.'',0,0,'L');
$pdf->Ln();
$pdf->Cell($wl,8,'Ciudad: '.$ct->ciudad.'',0,0,'L');
$pdf->Ln();
$pdf->Cell($wl,8,'Obra: '.$pr->obra.'',0,0,'L');
$pdf->Ln();
$pdf->BasicTable($header,$resultData);
$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->Cell($wl2,8,'SUBTOTAL :    $',0,0,'R');
$pdf->Cell(20,8,''.number_format($pr->subt,2).'',0,0,'R');
$pdf->Ln();
$pdf->Cell(170,8,'DESCUENTO :    $',0,0,'R');
$pdf->Cell(20,8,''.number_format($pr->desc,2).'',0,0,'R');
$pdf->Ln();
$pdf->Cell($wl2,8,'INSTALACION Y SUPERVISION DE LA OBRA :    $',0,0,'R');
$pdf->Cell(20,8,''.number_format($pr->inst,2).'',0,0,'R');
$pdf->Ln();
$pdf->Cell($wl2,8,'TOTAL DE MATERIALES E INSTALACION S.E.U.O.S. :    $',0,0,'R');
$pdf->Cell(20,8,' '.number_format($pr->subt-$pr->desc+$pr->inst,2).'',0,0,'R');
$pdf->Ln();
$pdf->Cell($wl2,8,'IVA :    $',0,0,'R');
$pdf->Cell(20,8,''.number_format($pr->iva,2).'',0,0,'R');
$pdf->Ln();
$pdf->Cell($wl2,8,'TOTAL :    $',0,0,'R');
$pdf->Cell(20,8,''.number_format($pr->total,2).'',0,0,'R');
$pdf->Ln();
$pdf->Cell($wr,8,'NOTA :',0,0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','',10);
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

$pdf->Output("presupuesto :".$search_pres.".pdf","D");
?>
<html>
</head>
<meta http-equiv="refresh" content="0;url=<?php echo $raiz; ?>/?pg=inicio" />
</body>
</html>
