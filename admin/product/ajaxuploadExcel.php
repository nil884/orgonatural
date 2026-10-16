<?php
include("../../includes/configuration.php");

  include("../../PHPExcel/Classes/PHPExcel/IOFactory.php");
 $target="../../cronjob/cronfiles/";
         if(isset($_FILES['attachment']['name']) && $_FILES['attachment']['name'] != "") {
            $allowedExtensions = array("xls","xlsx","csv");
            $ext = pathinfo($_FILES['attachment']['name'], PATHINFO_EXTENSION);
            if(in_array($ext, $allowedExtensions)) {

                   $file = $_FILES['attachment']['name'];
                   $extension = end(explode(".", $_FILES["attachment"]["name"]));
                   $photo="fileupload_".date("ymdhis").".".$extension;
                  $isUploaded = copy($_FILES['attachment']['tmp_name'], $file);
                     move_uploaded_file($_FILES["attachment"]["tmp_name"],$target.$photo);

                    try {
                            //Load the excel(.xls/.xlsx) file
                            $objPHPExcel = PHPExcel_IOFactory::load($target.$photo);
                        } catch (Exception $e) {
                             die('Error loading file "' . pathinfo($target.$photo, PATHINFO_BASENAME). '": ' . $e->getMessage());
                        }

                        //An excel file may contains many sheets, so you have to specify which one you need to read or work with.
                        $sheet = $objPHPExcel->getSheet(0);

                        //It returns the highest number of rows
                        $total_rows = $sheet->getHighestRow();
                        $data=array(
                        "uploadedBy"=>0,
                         "startDate"=>date("Y-m-d H:i:s"),
                         "showname"=>$file,
                         "filename"=>$photo,
                         "status"=>"Initiate",
                         "totalRow"=>$total_rows
                        );
                        $in=insertQuery(PRODCRON,$data);

                      echo 1;
            } else {
                echo 'This type of file not allowed!';
            }
        } else {
            echo 'Select an excel file first!';
        }
      unlink($file);
    ?>