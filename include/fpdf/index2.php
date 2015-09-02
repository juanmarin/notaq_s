<?
$clave = 'A 0663';
require('fpdf.php');

class PDF extends FPDF
{
    //Cabecera de página
    function Header()
    {
        //Logo
        $this->Image("../../images/cerison_logo.png" , 10 ,8, 50 , 38 , "PNG");
        //Arial bold 15
        $this->SetFont('Arial','B',13);
        //Movernos a la derecha
        $this->Cell(80);
        //Título
        $this->Cell(50,10,'CERCOS ARQUITECT, S.A. DE C.V.',0,0,'C');
        //Salto de línea
        $this->Ln(20);
        $this->Cell(215,10,'PRECIO UNITARIO',0,0,'C');

    }

    //Pie de página
    function Footer()
    {
        //Posición: a 1,5 cm del final
        $this->SetY(-15);
        //Arial italic 8
        $this->SetFont('Arial','I',8);
        //Número de página
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
    //Tabla simple
    function TablaSimple($header)
    {
        //Cabecera
        foreach($header as $col)
            $this->Cell(40,7,$col,1);
        $this->Ln();

        $this->Cell(40,5,'$clave',1);
        $this->Cell(40,5,"DESCRIPCION",1);
        $this->Cell(40,5,"CANTIDAD",1);
        $this->Cell(40,5,"PRECIO",1);
        $this->Cell(40,5,"TOTAL",1);
        $this->Ln();
        $this->Cell(40,5,"linea ",1);
        $this->Cell(40,5,"linea 2",1);
        $this->Cell(40,5,"linea 3",1);
        $this->Cell(40,5,"linea 4",1);
        $this->Cell(40,5,"linea 5",1);
    }
}
$pdf=new PDF();
//Títulos de las columnas
$header=array('CLAVE ','DESCRIPCION','CANTIDAD','PRECIO','TOTAL');
$pdf->AliasNbPages();
//Primera página
$pdf->AddPage();
$pdf->SetY(65);
//$pdf->AddPage();
$pdf->TablaSimple($header);
//Segunda página
//$pdf->AddPage();
//$pdf->SetY(65);
$pdf->Output();
?>


