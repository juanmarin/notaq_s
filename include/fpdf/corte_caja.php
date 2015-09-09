<?php
@session_start();
require('fpdf.php');
$hoy = date("Y-m-d");
$cobrador = "cob3";
$UserName = $_SESSION["USERNAME"];
$UserLevel = $_SESSION["U_NIVEL"];
class PDF extends FPDF
{
//Tabla Sencilla
function BasicTable($header,$data)
{
    $this->SetFont('Arial','',9);
	//Header Width
	$w=array(10,70,20,20,20,20,21);
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
		$this->Cell(70,6,$eachResult["cte_nom"],1);
		$this->Cell(20,6,date("d-m-Y", strtotime($eachResult["fpago"])),1,0,'R');
		$this->Cell(20,6,date("d-m-Y", strtotime($eachResult["fcobro"])),1,0,'R');
		$this->Cell(20,6,"$ ".number_format($eachResult["pago_real"],2),1,0,'R');
		$this->Cell(20,6,"$ ".number_format($eachResult["pago_real"],2),1,0,'R');
		$this->Cell(21,6,"$ ".number_format($eachResult["pago_real"],2),1,0,'R');
		$this->Ln();
		$row ++;
		$pagos+= $eachResult["pago_real"];
		//date ("d-m-Y", strtotime($r->fecha))
	}
	//$this->Cell(150,6,"$ ".number_format($pagos,2),1,0,'R');
	$this->Cell(78,6,"SUBTOTALES : ",0,0,'R');
	$this->Cell(62,6,''."$ ".number_format($pagos,2).'',0,0,'R');
	$this->Cell(20,6,''."$ ".number_format($pagos,2).'',0,0,'R');
	$this->Cell(21	,6,''."$ ".number_format($pagos,2).'',0,0,'R');
	$this->Ln(5);
	$this->Cell(78,6,"TOTAL A ENTREGAR : ",0,0,'R');
	$this->Cell(62,6,''."$ ".number_format($pagos,2).'',0,0,'R');
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
$header=array('#','CLIENTE','F. PAGO','F. COBRO', 'CANTIDAD', 'ABONOS', 'RECARGOS');
//Data loading
//*** Load MySQL Data ***//
$objConnect = mysql_connect("localhost","root","root") or die("Error Connect to Database");
$objDB = mysql_select_db("notaq");
$strSQL = "SELECT clientes.id AS clientes, CONCAT(clientes.nombre, ' ' ,clientes.apellidop, ' ' ,clientes.apellidom) AS cte_nom, 
	clientes.c_cobrador, pagos.fecha AS fpago, pagos.fechaPago AS fcobro, pagos.pago_real AS pago_real, pagos.estado, pagos.reportado 
	FROM clientes, pagos 
	WHERE clientes.id = pagos.cliente 
	AND DATE(pagos.fechaPago) <= '".$hoy."'
	AND pagos.pago_real > 0 
	AND (pagos.estado = 1 OR pagos.estado = 3) 
	AND pagos.reportado = 0
	AND clientes.c_cobrador = '$cobrador'";
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
//$pdf->Image('../../images/cerison_logo.png',10,8,45,38,"PNG");
//$pdf->Cell(80);
$pdf->Cell(200,10,'CONFIANZP',0,0,'C');
$pdf->Ln(10);
$pdf->Cell(200,10,'REPORTE DE PAGOS RECIBIDOS',0,0,'C');
$pdf->Ln(8);
$pdf->BasicTable($header,$resultData);
$titulo = "/var/www/html/notaq_s/include/fpdf/reportes/c_caja_".$cobrador."_".date("Y-m-d_H:m:s").".pdf";
$pdf->Output($titulo, "F");?>
