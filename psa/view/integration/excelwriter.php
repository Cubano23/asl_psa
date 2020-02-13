<?php
// Simulateur writeexcel

require_once 'PHPExcel.php';
require_once 'PHPExcel/IOFactory.php';
              

class writeexcel_workbookbig {

var $fileName="";
var $objPHPExcel;
var $index=0;
var $objWriter;
                function endsWith($haystack, $needle)
                {
                return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
                }

               function __construct ($filename  )
               {
                  
                     if ($this->endsWith($filename, "xls")==true)
                                 $filename = $filename."x";                
                     $this->fileName = $filename;
                     $this->objPHPExcel = new PHPExcel();
                                 
               
               }

                function addworksheet($title)
                {
                    if($this->index!=0)
                    {   
                         $objWorksheet = new PHPExcel_Worksheet($this->objPHPExcel);
	                       $this->objPHPExcel->addSheet($objWorksheet);
                    }
                    else
                          $objWorksheet = $this->objPHPExcel->getActiveSheet(); 
                   $this->index++; 
	                 $objWorksheet->setTitle( $title);
                
                  return   new writeexcel_worksheet( $objWorksheet);
                }
                function close()
                {
//                echo "1";
//                  $this->objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel2007');
                  $objWriter = new PHPExcel_Writer_Excel2007($this->objPHPExcel);
//                  echo "2";
                  $objWriter->save($this->fileName);
                  unset($objWriter);
                  $this->objPHPExcel->disconnectWorksheets();
/*                  
                foreach ($this->objPHPExcel->getWorksheetIterator() as $sheet) {
                      foreach ($sheet->getRowIterator() as $row) {
                                        $cellIterator = $row->getCellIterator();
                        //$cellIterator->setIterateOnlyExistingCells(false); 

                      foreach ($cellIterator as $cell) {
                                        unset($cell);
                        }
                      unset($row);
                    }
                
                unset($sheet);
                }

  */
                  unset($this->objPHPExcel);
//                  echo "3";
                }               

}


class writeexcel_worksheet {

var $obj;

          function __construct ($obj  )
          {
          
            $this->obj = $obj;
          
          }
          function write($cell, $data)
          {
                
            $this->obj->setCellValue($cell, $data);              
          }


}


?>