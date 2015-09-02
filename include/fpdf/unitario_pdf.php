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
	$w=array(30,92,24,25,25);
	//Header
	for($i=0;$i<count($header);$i++)
		$this->Cell($w[$i],7,$header[$i],1,0,'C');
	$this->Ln();
	foreach ($data as $eachResult)
	{
	    $importe = ($eachResult["cantidad"]* $eachResult["precio"]);
		$this->Cell(30,6,$eachResult["clave1"],1,0,'C');
		$this->Cell(92,6,$eachResult["descr"],1);
		$this->Cell(24,6,number_format($eachResult["cantidad"],2),1,0,'C');
        $this->Cell(25,6,number_format($eachResult["precio"],2),1,0,'R');
		$this->Cell(25,6,number_format($importe,2),1,0,'R');
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
$search_comp = $_GET["comp"];
//Obtenemos datos del articulo
$sql = "SELECT * FROM articulos WHERE clave = '".$search_comp."'";
$res = $db->query( $sql );
$ar_comp = $db->fetchNextObject( $res );
//Column titles
$header=array('CLAVE','DESCRIPCION','CANTIDAD','PRECIO', 'TOTAL');
//*** Load MySQL Data ***//
$strSQL = "SELECT * FROM compuesto WHERE clave = '".$search_comp."'";
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
$pdf->Cell(192,8,'PRECIO UNITARIO',1,0,'C');
$pdf->Ln();
$pdf->SetFont('Arial','',10);
$pdf->Cell($wl,8,'CLAVE: '.$ar_comp->clave.'',0,0,'L');
$pdf->Ln();
$pdf->Cell($wl,8,'DESCRIPCION: '.$ar_comp->descripcion.'',0,0,'L');
$pdf->Ln();
$pdf->BasicTable($header,$resultData);
$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->Cell($wl2,8,'SUMA TOTAL :    $',0,0,'R');
$pdf->Cell(20,8,''.number_format($ar_comp->precio,2).'',0,0,'R');

$pdf->Output("unitario :".$search_comp.".pdf","D");
?>
<html>
</head>
<meta http-equiv="refresh" content="0;url=<?php echo $raiz; ?>/?pg=inicio" />
</body>
</html>
