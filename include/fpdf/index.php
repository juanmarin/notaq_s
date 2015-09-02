<?php
$tittle = 'archivo';
require('fpdf.php');
require('../php/functions_pdf.php');
class PDF extends FPDF
{
   //Cabecera de página
   function Header()
   {
      $this->Image('../../images/cerison_logo.png',10,8,33);
      $this->SetFont('Arial','B',12);
      $this->Cell(40,23,'',0,0,'C');
   }
}
//Creación del objeto de la clase heredada
$pdf=new PDF();
$pdf->AddPage();
$pdf->SetFont('Times','B',12);
$pdf->Cell(130,10,'CERCOS ARQUITECT, S.A. DE C.V.',0,0,'C');
$pdf->Ln();
$pdf->Cell(200,10,'PRECIO UNITARIO',0,0,'C');
$pdf->Ln();
$pdf->SetFont('Times','B',10);
$pdf->Cell(30,10,'CLAVE:',0,0,'C');
$pdf->Ln();
$pdf->Cell(30,10,'DESCRIPCION:',0,0,'C');
$pdf->Ln();
$pdf->Cell(40,10,'_________________________________________________________________________________________________________________________________________________________________________________________________________',0,0,'C');
$pdf->Tabla_simple($header);
$pdf->Output('test.pdf','d');
?>


