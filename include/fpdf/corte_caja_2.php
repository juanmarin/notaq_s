<?php
@session_start();
require('fpdf.php');
require_once("../php/sys_db.class.php");
require_once("../conf/Config_con.php");


$hoy = date("Y-m-d");

//$cobrador = "cob3";
$UserName = $_SESSION["USERNAME"];
$UserLevel = $_SESSION["U_NIVEL"];
class PDF extends FPDF
{
//Tabla Sencilla
function BasicTable($header,$data)
{
    $this->SetFont('Arial','',9);
	//Header Width
	$w=array(10,60,20,20,20,20,21,20);
	//Header
	for($i=0;$i<count($header);$i++)
		$this->Cell($w[$i],7,$header[$i],1,0,'C');
	$this->Ln();
	$pagos = 0;
	$row = 1;
	foreach ($data as $eachResult)
	{
		//$this->Cell(30,6,$eachResult["id"],1);
		$this->Cell(10,6,$row,1);
		$this->Cell(60,6,$eachResult["nombre"],1);
		$this->Cell(20,6,date("d-m-Y", strtotime($eachResult["fechacob"])),1,0,'R');
		$this->Cell(20,6,date("d-m-Y", strtotime($eachResult["fecha"])),1,0,'R');
		$this->Cell(20,6,"$ ".number_format($eachResult["pagos"],2),1,0,'R');
		$this->Cell(20,6,"$ ".number_format($eachResult["abonos"],2),1,0,'R');
		$this->Cell(21,6,"$ ".number_format($eachResult["recargos"],2),1,0,'R');
		$this->Cell(20,6,"$ ".number_format($eachResult["abrecargos"],2),1,0,'R');
		$this->Ln();
		$row ++;
	}
	//$this->Cell(150,6,"$ ".number_format($pagos,2),1,0,'R');
	$this->Cell(76,6,"SUBTOTALES : ",0,0,'R');
	$this->Cell(54,6,''."$ ".number_format($eachResult["totpagos"],2).'',0,0,'R');
	$this->Cell(17,6,''."$ ".number_format($eachResult["totabonos"],2).'',0,0,'R');
	$this->Cell(22	,6,''."$ ".number_format($eachResult["totrecargos"],2).'',0,0,'R');
	$this->Cell(21	,6,''."$ ".number_format($eachResult["totabrecargos"],2).'',0,0,'R');
	$this->Ln(5);
	$this->Cell(76,6,"TOTAL A ENTREGAR : ",0,0,'R');
	$this->Cell(54,6,''."$ ".number_format($eachResult["totglobal"],2).'',0,0,'R');
	$this->Ln(70);
	//$this->Ln();
	$sql = "SELECT nombre, email FROM mymvcdb_users WHERE username = '".$eachResult["cobrador"]."'";
	$res = mysql_query($sql);
	$rec = mysql_fetch_array($res);

	$this->Cell(60,5,'___________________________',0,0,'C');
	$this->Cell(180,5,'___________________________',0,0,'C');
	$this->Ln();
	$this->Cell(60,5,'SUPERVISOR',0,0,'C');
	$this->Cell(180,5,'ENTREGO',0,0,'C');
	$this->Ln();
	$this->Cell(60,5,''.$_SESSION["UNOMBRE"].'',0,0,'C');
	$this->Cell(180,5,''.$rec["0"].'',0,0,'C');

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

//Column titles
$header=array('#','CLIENTE','F. PAGO','F. COBRO', 'PAGOS', 'ABONOS', 'RECARGOS', 'AB.RECARGO');
//Data loading
//*** Load MySQL Data ***//
$strSQL = "SELECT cc.id, cc.cobrador AS cobrador, cc.recibido_x AS supervisor, cc.created_at AS fechar, cc.totpagos AS totpagos, 
cc.totabonos AS totabonos, cc.totrecargos AS totrecargos, cc.totglobal AS totglobal, ccd.cocaj_id, ccd.client_id, ccd.client_nom AS nombre, 
ccd.fechaPago AS fechacob, ccd.fechacobro AS fecha, ccd.pago_importe AS pagos, ccd.abono_importe AS abonos, ccd.recarg_importe AS recargos,
ccd.abrecarg_importe AS abrecargos
FROM corte_caja cc
INNER JOIN corte_caja_detail ccd 
ON cc.id = ccd.cocaj_id
WHERE cc.id = ".$ccaj_id." ";
$objQuery = mysql_query($strSQL);
$resultData = array();
for ($i=0;$i<mysql_num_rows($objQuery);$i++) {
	$result = mysql_fetch_array($objQuery);
	array_push($resultData,$result);
}
//************************//

$pdf->SetFont('Arial','B',13);

//*** Print Table ***//
$pdf->AddPage();
$pdf->Cell(200,10,'CONFIANZP',0,0,'C');
$pdf->Ln(10);
$pdf->Cell(200,10,"REPORTE DE PAGOS RECIBIDOS HASTA EL DIA ".date("d-m-Y")."",0,0,'C');
$pdf->Ln(8);
$pdf->BasicTable($header,$resultData);
$titulo = "/home/confian1/public_html/include/fpdf/reportes/c_caja_".$cobrador."_".$created.".pdf";
//$titulo = "/var/www/notaq_s/include/fpdf/reportes/c_caja_".$cobrador."_".$created.".pdf";
$pdf->Output($titulo, "F");

?>
