<?php
require('fpdf.php');

class PDF extends FPDF
{
//Tabla Sencilla
function BasicTable($header,$data)
{
    $this->SetFont('Arial','',10);
	//Header Width
	$w=array(30,120,20,20);
	//Header
	for($i=0;$i<count($header);$i++)
		$this->Cell($w[$i],7,$header[$i],1,0,'C');
	$this->Ln();
	//Data
	foreach ($data as $eachResult)
	{
		//$this->Cell(30,6,$eachResult["id"],1);
		$this->Cell(30,6,$eachResult["clave"],1);
		$this->Cell(120,6,$eachResult["descripcion"],1);
		$this->Cell(20,6,number_format($eachResult["costo"],2),1,0,'R');
		$this->Cell(20,6,number_format($eachResult["precio"],2),1,0,'R');
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

//Column titles
$header=array('CLAVE','DESCRIPCIÓN','COSTO','PRECIO');
//Data loading
//*** Load MySQL Data ***//
$objConnect = mysql_connect("localhost","root","root") or die("Error Connect to Database");
$objDB = mysql_select_db("cerison");
$strSQL = "SELECT * FROM articulos";
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
$pdf->Image('../../images/cerison_logo.png',10,8,45,38,"PNG");
$pdf->Cell(80);
$pdf->Cell(50,10,'CERCOS ARQUITECT, S.A. DE C.V.',0,0,'C');
$pdf->Ln(20);
$pdf->Cell(215,10,'LISTADO DE ARTICULOS',0,0,'C');
$pdf->Ln(35);
$pdf->BasicTable($header,$resultData);

$pdf->Output("art_lista.pdf","D");?>
