<?php
 /* Generando excel desde mysql con PHP
    @Autor: Juan Marin
    Version: 1.0
 */
  
 $conexion = mysql_connect ("localhost", "root", "root");
 $conexion = mysql_connect ("localhost", "confian1_notaq", "99_shamp00");
 //mysql_select_db ("notaq", $conexion);
 mysql_select_db ("confian1_notaq", $conexion);    
 $sql = "SELECT * FROM clientes ORDER BY nombre ASC";
 $resultado = mysql_query ($sql, $conexion) or die (mysql_error ());
 $registros = mysql_num_rows ($resultado);
  
 if ($registros > 0) {
   require_once 'Classes/PHPExcel.php';
   $objPHPExcel = new PHPExcel();
    
   //Informacion del excel
   $objPHPExcel->
    getProperties()
        ->setCreator("Notaq")
        ->setLastModifiedBy("Notaq")
        ->setTitle("Listado de clientes")
        ->setSubject("Listado Clientes")
        ->setDescription("Notaq Lista CLientes")
        ->setKeywords("Notaq Lista Clientes")
        ->setCategory("Reportes");    
 			
   $i = 1;    
   while ($registro = mysql_fetch_object ($resultado)) {
      $nomCte = $registro->nombre." ".$registro->apellidop;
      $objPHPExcel->setActiveSheetIndex(0);
      $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth('B1', 100);
      $objPHPExcel->getActiveSheet()->setCellValue('A1', 'N. CLIENTE');
      $objPHPExcel->getActiveSheet()->setCellValue('B1', 'NOMBRE CLIENTE');
      $objPHPExcel->getActiveSheet()->setCellValue('C1', 'DIRECCION');
      $objPHPExcel->getActiveSheet()->setCellValue('D1', 'COLONIA');
      $objPHPExcel->getActiveSheet()->setCellValue('E1', 'TELEFONO');
      $objPHPExcel->getActiveSheet()->setCellValue('F1', 'CELULAR');
      $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $registro->id);
      $objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $nomCte);
      $objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $registro->direccion);
      $objPHPExcel->getActiveSheet()->setCellValue('D'.$i, $registro->colonia);
      $objPHPExcel->getActiveSheet()->setCellValue('E'.$i, $registro->telefono);
      $objPHPExcel->getActiveSheet()->setCellValue('F'.$i, $registro->celular);
  
      $i++;
       
   }
}
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="listado clientes.xlsx"');
header('Cache-Control: max-age=0');
 
$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
$objWriter->save('php://output');
exit;
mysql_close ();
?>