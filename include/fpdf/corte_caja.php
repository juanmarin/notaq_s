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
		$this->Cell(70,6,$eachResult["nombre"],1);
		$this->Cell(20,6,date("d-m-Y", strtotime($eachResult["fechacob"])),1,0,'R');
		$this->Cell(20,6,date("d-m-Y", strtotime($eachResult["fecha"])),1,0,'R');
		$this->Cell(20,6,"$ ".number_format($eachResult["pagos"],2),1,0,'R');
		$this->Cell(20,6,"$ ".number_format($eachResult["abonos"],2),1,0,'R');
		$this->Cell(21,6,"$ ".number_format($eachResult["recargos"],2),1,0,'R');
		$this->Ln();
		$row ++;
		$pagos+= $eachResult["pagos"];
	}
	//$this->Cell(150,6,"$ ".number_format($pagos,2),1,0,'R');
	$this->Cell(78,6,"SUBTOTALES : ",0,0,'R');
	$this->Cell(62,6,''."$ ".number_format($pagos,2).'',0,0,'R');
	$this->Cell(20,6,''."$ ".number_format($pagos,2).'',0,0,'R');
	$this->Cell(21	,6,''."$ ".number_format($pagos,2).'',0,0,'R');
	$this->Ln(5);
	$this->Cell(78,6,"TOTAL A ENTREGAR : ",0,0,'R');
	$this->Cell(62,6,''."$ ".number_format($pagos,2).'',0,0,'R');
	$this->Ln(90);
	//$this->Ln();
	$this->Cell(60,5,'___________________________',0,0,'C');
	$this->Cell(180,5,'___________________________',0,0,'C');
	$this->Ln();
	$this->Cell(60,5,'SUPERVISOR',0,0,'C');
	$this->Cell(180,5,'ENTREGO',0,0,'C');
	$this->Ln();
	$this->Cell(60,5,'',0,0,'C');
	$this->Cell(180,5,'COBRADOR',0,0,'C');

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
//$db = new DB(DB_DATABASE, DB_HOST, DB_USER, DB_PASSWORD);
$strSQL = "SELECT 
			cliente, nombre, fechacob, fecha, pagos, abonos, recargos 
			FROM
			(SELECT 
			cl.id cliente, concat(cl.nombre,' ',cl.apellidop,' ',cl.apellidom) nombre, cl.c_cobrador cobrador
			, pa.fecha fechacob, pa.fechaPago fecha, pa.pago_real pagos, pa.estado ep, pa.reportado rp
			, ab.fecha fechaabono, ab.abono abonos, ab.reportado ra
			, re.fecha fecharecargo, re.monto_saldado recargos, re.estado er, re.reportado rr
			FROM cuentas cu
			left join clientes cl on cl.id=cu.cliente
			left join pagos pa on pa.cuenta=cu.id 
			left join abono ab on ab.idpago=pa.id
			left join recargos re on re.pago_id=pa.id
			WHERE cu.estado=0) AS cobros
			WHERE ((ep=1 AND fecha <='".$hoy."' AND rp=0) 
			OR (fechaabono is not null AND fechaabono <= '".$hoy."' AND ra=0) 
			OR (er!=0 and fecharecargo <='".$hoy."' and rr=0))
			AND cobrador = '".$cobrador."'";
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
$titulo = "/home/confian1/public_html/include/fpdf/reportes/c_caja_".$cobrador."_".date("Y-m-d_H:i:s").".pdf";
//$titulo = "/var/www/html/notaq_s/include/fpdf/reportes/c_caja_".$cobrador."_".date("Y-m-d_H:i:s").".pdf";
$pdf->Output($titulo, "F");

?>
