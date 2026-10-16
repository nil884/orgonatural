<?php include "../../includes/configuration.php";

/* ******************************Manual Shipping Start******************************************** */
if($action=="add_manual_pincode"){
  $pincode=$_POST['pincode'];  $shiping_charges=$_POST['shiping_charges'];
  $shippingPer=$_POST['shippingPer'];$shippingUnit=$_POST['shippingUnit'];$delivery=$_POST['delivery'];
   require_once "../../classes/shiprocket.php";
     $username = $api_user; $pasword=$api_pwd;
    $ship = new shiprocket($username,$pasword);
    $token = $ship->authenticate();
    $data = array("postcode"=>$pincode);
    $res = $ship->getPincodeData($token,$data);

    if($res['postcode_details']){
        $chkpin=selectQuery(MANUAL,"pincode","pincode='".$pincode."'");
        if(count($chkpin)){
            echo "Pincode already added";
        }else{
             $data=array("pincode"=>$pincode,"shipping_charges"=>$shiping_charges,"chargesPer"=>$shippingPer,"chargesUnit"=>($shippingPer!=""?$shippingUnit:""),"deliveryDays"=>$delivery);
             insertQuery(MANUAL,$data);
             echo 1;
        }
    }else{
       echo $res['message'];
    }
  //MANUAL
}

if($action=="update_pincode"){
   $pinid=$_POST['id'];   $pincode=$_POST['pincode'];  $shipping=$_POST['shipping'];
     $shippingPer=$_POST['shippingPer'];$shippingUnit=$_POST['shippingUnit']; $delivery=$_POST['delivery'];  
    $data=array("pincode"=>$pincode,"shipping_charges"=>$shipping,"chargesPer"=>$shippingPer,"chargesUnit"=>($shippingPer!=""?$shippingUnit:""),"deliveryDays"=>$delivery);
   updateQuery(MANUAL,$data,"id=".$pinid);
    echo 1;

}
if($action=="delPincode"){
   $pinid=$_POST['id'];
   $del=deleteQuery(MANUAL,"id=".$pinid);
   if($del) echo 1;
   else echo 0;
}

if($action=="export"){
    include_once('../../PHPExcel/Classes/PHPExcel/IOFactory.php');
     // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();
         $chkpin=selectQuery(MANUAL,"pincode,shipping_charges,chargesPer,chargesUnit");
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Me")->setLastModifiedBy("Me")->setTitle("My Excel Sheet")->setSubject("My Excel Sheet")->setDescription("Excel Sheet")->setKeywords("Excel Sheet")->setCategory("Me");

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

          $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, 'Pincode');
           $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, 'Charges Per');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, 'Unit (gm/piece)');
          $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, 'Shipping Charges');
           $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, 'Delivery In Days (Enter 0 for same daay)');
          $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, 'Remove');
        for($i=0;$i<count($chkpin);$i++){
            $row= $chkpin[$i];
            $rowcnt=$i+2;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $rowcnt, $row['pincode']);
             $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $rowcnt, $row['chargesPer']);
              $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $rowcnt, $row['chargesUnit']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $rowcnt, $row['shipping_charges']);
             $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $rowcnt, $row['deliveryDays']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $rowcnt, 0);
        }


        // Set worksheet title
        $objPHPExcel->getActiveSheet()->setTitle(substr("Hyperloop",0,30));
          $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('hyperloop.xlsx');
        echo 'hyperloop.xlsx';
}

if($action=="importdata"){
   include_once('../../PHPExcel/Classes/PHPExcel/IOFactory.php');
    require_once "../../classes/shiprocket.php";
     $username = $api_user; $pasword=$api_pwd;
     $ship = new shiprocket($username,$pasword);
     $token = $ship->authenticate();
   if(isset($_FILES['attachment']['name']) && $_FILES['attachment']['name'] != ""){
    $allowedExtensions = array("xls","xlsx");
    $ext = pathinfo($_FILES['attachment']['name'], PATHINFO_EXTENSION);
    if(in_array($ext, $allowedExtensions)){
        $file = $_FILES['attachment']['name'];
        $extension = end(explode(".", $_FILES["attachment"]["name"]));
        $photo="fileupload_".date("ymdhis").".".$extension;
        //$isUploaded = copy($_FILES['attachment']['tmp_name'], $file);
        move_uploaded_file($_FILES["attachment"]["tmp_name"],$photo);
        try {
            //Load the excel(.xls/.xlsx) file
            $objPHPExcel = PHPExcel_IOFactory::load($photo);
        } catch (Exception $e){
            die('Error loading file "' . pathinfo($photo, PATHINFO_BASENAME). '": ' . $e->getMessage());
        }
        //An excel file may contains many sheets, so you have to specify which one you need to read or work with.
        $sheet = $objPHPExcel->getSheet(0);
          $total_columns = $sheet->getHighestColumn();
           $total_rows = $sheet->getHighestRow();
         for($row =2; $row <= $total_rows; $row++){
            //Read a single row of data and store it as a array.
            //This line of code selects range of the cells like A1:D1
            $single_row = $sheet->rangeToArray('A' . $row . ':' . $total_columns . $row, NULL, TRUE, FALSE);

            //Print each cell of the current row
             $pincode=trim($single_row[0][0]);
             $chargesper=trim($single_row[0][1]);
             $chargesunit=strtolower(trim($single_row[0][2]));
             $shipcharge=trim($single_row[0][3]);
             $deliveryDays=trim($single_row[0][4]);
             $remove=trim($single_row[0][5]);
              $chkpin=selectQuery(MANUAL,"id","pincode='".$pincode."'");
                 if(count($chkpin)){
                      if($remove==1){
                       deleteQuery(MANUAL,"id=".$chkpin[0]['id']);
                     }else{
                        $data=array("pincode"=>$pincode,"shipping_charges"=>$shipcharge,"chargesPer"=>$chargesper,"chargesUnit"=>($chargesper!=""?$chargesunit:""),"deliveryDays"=>$deliveryDays);
                         updateQuery(MANUAL,$data,"id=".$chkpin[0]['id']);
                     }
                 }else{

                        $data = array("postcode"=>$pincode);
                        $res = $ship->getPincodeData($token,$data);

                        if($res['postcode_details']){
                            $chkpinin=selectQuery(MANUAL,"pincode","pincode='".$pincode."'");
                            if(count($chkpinin)){}else{
                                 if($remove!=1){ $data=array("pincode"=>$pincode,"shipping_charges"=>$shipcharge,"chargesPer"=>$chargesper,"chargesUnit"=>($chargesper!=""?$chargesunit:""),"deliveryDays"=>$deliveryDays);
                                    insertQuery(MANUAL,$data); }
                            }
                         }
                 }
         }
         unlink($photo);
        echo 1;
    } else { echo 'This type of file not allowed!'; }
    }
}
/* ******************************Manual Shipping End******************************************** */

?>