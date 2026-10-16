<?php
    include ("../../includes/configuration.php");
  //  include_once('../../PHPExcel/Classes/PHPExcel/IOFactory.php');
   include_once('../../PHPExcel/Classes/PHPExcel.php');
    include_once('../../PHPExcel/excelfunctions.php');
     $sms = selectQuery(SMSTEMPLATE,"*");
    unlink("sms_template.xlsx") ;
    $objPHPExcel = new PHPExcel();

   // $objPHPExcel->getProperties()->setCreator("Me")->setLastModifiedBy("Me")->setTitle("My Excel Sheet")->setSubject("My Excel Sheet")->setDescription("Excel Sheet")->setKeywords("Excel Sheet")->setCategory("Me");
    $attrsa = array();
    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);
    $colarr = array('ID', 'SMS Type', 'Sent To', 'SMS Content', 'Template ID');

    $row = "1";
     for ($i = 0; $i < count($colarr); $i++) {
        $col = $i;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $colarr[$i]);
    }
    for ($i = 0; $i < count($sms); $i++) {
        $row++;
        $replacement_array2 = array('smssitename' => SMSSITENAME);
          $replacement_array = array('siteurl'=>"{#var#}",'sitename'=>"{#var#}",'OTP'=>"{#var#}","vendorname"=>"{#var#}","name"=>"{#var#}","vendornickname"=>"{#var#}","mobile"=>"{#var#}","message"=>"{#var#}","order_id"=>"{#var#}","vendor_name"=>"{#var#}","username"=>"{#var#}","email"=>"{#var#}","user_email"=>"{#var#}","Productname"=>"{#var#}","cancellationgenerator"=>"{#var#}","cancellationreason"=>"{#var#}","refund_amount"=>"{#var#}","request_type"=>"{#var#}","product_name"=>"{#var#}","vendorcurrentplan"=>"{#var#}","txnid"=>"{#var#}");
        $template = convertsmsstr($replacement_array2,$sms[$i]['sms_text']);
        $m = convertsmsstr($replacement_array,$template);
         $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $sms[$i]['id']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $sms[$i]['type']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $sms[$i]['sms_to']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $m);
         $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $sms[$i]['templateId']);
    }


    // Set worksheet title
    $objPHPExcel->getActiveSheet()->setTitle("sms templates");

    /* ---------------------------------Second sheet------------------------------------------------------------------- */

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");

        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header('Content-Disposition: attachment; filename="sms_template.xlsx"');
        header("Cache-Control: max-age=0");
        ob_clean();
  
     $objWriter->save("sms_template.xlsx");