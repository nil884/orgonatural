<? include("../../includes/configuration.php"); include("../../classes/product.php");
$prod = new Product(); 
if($action == "approve"){
    $requestid = $_REQUEST['requestedid'];
    $data = array('isApproved' => $status,'isActive'=>$status,"approved_by" =>"Admin","approve_date"=> date("Y-m-d H:i:s") );
    $upategetsubcat = updateQuery(PRODINFO, $data, "id=" . $requestid);
    if ($upategetsubcat) { $upategetsubpro = updateQuery(PRODINFO, $data, "parent_id=" . $requestid); echo 1;
    } else { echo 0; }
}
if($action == "new_arrival"){
    $requestid = $_REQUEST['requestedid'];
    $data = array('new_arrival' => $status, );
    $upategetsubcat = updateQuery(PRODINFO, $data, "id=" . $requestid);
    if ($upategetsubcat) { $upategetsubpro = updateQuery(PRODINFO, $data, "parent_id=" . $requestid); echo 1;
    } else { echo 0; }
}
if($action == "trending_now"){
    $requestid = $_REQUEST['requestedid'];
    $data = array('trending_now' => $status, );
    $upategetsubcat = updateQuery(PRODINFO, $data, "id=" . $requestid);
    if ($upategetsubcat) { $upategetsubpro = updateQuery(PRODINFO, $data, "parent_id=" . $requestid); echo 1;
    } else { echo 0; }
}
if($action == "active_deactive"){
    $requestid = $_REQUEST['requestedid'];
    $data = array('isActive'=>$status );
    $upategetsubcat = updateQuery(PRODINFO, $data, "id=" . $requestid);
    if($upategetsubcat) { $upategetsubcat = updateQuery(PRODINFO, $data, "parent_id=" . $requestid); echo 1;
    }
    else { echo 0; }
}
if($action == "inv_update"){
        
        $chekcproductsku = selectQuery(PRODINFO,"id","sku='".$sku."' and id <> '".$inv_id."' ");
        if(count($chekcproductsku)){
            echo 2; 
        }
        else {
        $todaydate = date("Y-m-d H:i:s");
        if($Sale_start_date != ""){$Sale_start_date = date("Y-m-d H:i:s", strtotime($Sale_start_date)); }
        if($Sale_end_date != ""){$Sale_end_date = date("Y-m-d H:i:s", strtotime($Sale_end_date)); }
        $invetory = array(
        'stock'=>  $quantity,
        'sold'=> 0,
        'sku' => $sku,    
        'mrp' => $mrp,
        'tax' => $tax,
        'vendor_reg_price' => $regular_price,
        'vendor_sale_price' => $Sale_price,
        'vendor_sale_start_date' => $Sale_start_date,
        'vendor_sale_end_date' => $Sale_end_date,
        'admin_price' => $admin_price,  
        'weight' => $weight,
        'length' => $Length,
        'height' => $Height,
        'width' => $Width,
        'is_cancellation_avail' => $Cancellation_days ,
        'return_days' => $Return_days,
        "is_cod_avail" => $cod_Available
    );
        
        if($admin_price != "" && $admin_price != "0"){ $invetory['final_price'] = $admin_price; }
        else { 
            if($Sale_price != "" && $Sale_start_date != "0000-00-00 00:00:00" && $Sale_end_date != "0000-00-00 00:00:00" && $Sale_start_date <= $todaydate && $todaydate <=$Sale_end_date){ $final_price = $Sale_price;}
            else { $final_price = $mrp; }
            $invetory['final_price'] = $final_price; 
        }
        $specification = array();
        if($variant_1_name!= "" ){
            $invetory['variant_value1'] = ucwords(addslashes($variant_1_val));
            $specification[$variant_1_name] = ucwords(addslashes($variant_1_val));
        } if($variant_2_name!= "" ){
            $invetory['variant_value2'] = ucwords(addslashes($variant_2_val));
            $specification[$variant_2_name] = ucwords(addslashes($variant_2_val));
        } if($variant_3_name!= "" ){
            $invetory['variant_value3'] = ucwords(addslashes($variant_3_val));
            $specification[$variant_3_name] = ucwords(addslashes($variant_3_val));
        } if($variant_1_name != ""  || $variant_2_name!= "" || $variant_3_name!= "" ){
            $productData = $prod->getParentName($inv_id); 
            $basic_namenew = $productData;
        } else {
            $productData = $prod->getShortDetails($inv_id); 
            $basic_namenew = $productData[0]['prod_name'];
        }
        
        $product_name = trim(ucwords(addslashes($basic_namenew." ".$variant_1_val." ".$variant_2_val." ".$variant_3_val)));
        
        //$url = $prod->createProdURL($product_name,$sub_cat,$inv_id);
        try {

            $url = $prod->createProdURL($product_name, $sub_cat, $inv_id);

        //    var_dump($url);

        } catch (Throwable $e) {

            echo $e->getMessage();
            echo "<br>";
            echo $e->getFile();
            echo "<br>";
            echo $e->getLine();
            die;
        }

        $invetory['prod_name'] = $product_name;
        $invetory['url_title'] = $url;
        
        $upate_inv = updateQuery(PRODINFO, $invetory, "id=" . $inv_id);
        $update_table = updateQuery($prodtable, $specification, "prod_id =" . $inv_id);
        if($upate_inv){ echo 1; }
        else { echo 0; }
    }
}
if($action =="update_attribute"){
    $invarray = explode(",",$invarray);
    if(!empty($specifications)) { 
        for($i=0;$i<count($specifications);$i++){
            $split = explode("|",$specifications[$i]) ;
            $templatedata[$split['0']] = addslashes($split['1']);
        }
    }   
    $templatedata['highlight'] = addslashes($prod_description);
    for($i=0;$i<count($invarray);$i++){
        $upategetsubcat = updateQuery($prodtable, $templatedata, "prod_id =" . $invarray[$i]);
    }
    echo "1"; 
}
if($action == "approve_inv"){
    $requestid = $_REQUEST['requestedid'];
    $data = array('isApproved' => $status,'isActive'=>$status,"approved_by" =>"Admin","approve_date"=> date("Y-m-d H:i:s") );
    $upategetsubcat = updateQuery(PRODINFO, $data, "id=" . $requestid);
    if ($upategetsubcat) { echo 1; } else { echo 0; }
}
if($action == "Active_deactive_inv"){
   $requestid = $_REQUEST['requestedid'];
    $data = array('isActive'=>$status );
    $upategetsubcat = updateQuery(PRODINFO, $data, "id=" . $requestid);
      $getparent = selectQuery(PRODINFO, "parent_id", "id=" . $requestid);
     if($getparent[0]['parent_id']!=0){
          $getparentdata = selectQuery(PRODINFO, "count(id) as prodcnt", "isActive=1 AND isApproved=1 AND parent_id=" . $getparent[0]['parent_id']);
           if($getparentdata[0]['prodcnt']>0){
               $pdata = array('isActive'=>1);
           }else{
              $pdata = array('isActive'=>0);
           }
          
         updateQuery(PRODINFO, $pdata, "id=" . $getparent[0]['parent_id']);
      }

    if ($upategetsubcat) { echo 1; } else { echo 0; }
}

if($action == "Delete_product"){
    $requestid = $_REQUEST['prod_id'];
    if($prod->deleteProd($requestid)){ echo 1; } else { echo 0; }
}
if($action == "Delete_inventory"){
    $requestid = $_REQUEST['prod_id'];
    if($prod->deleteProd($requestid)){ echo 1; } else { echo 0; }
}
if($action == "approve_all"){
    $data = array('isApproved' =>1,'isActive'=>1, "approved_by" =>"Admin","approve_date"=> date("Y-m-d H:i:s") );
    $get_prod = selectQuery(PRODINFO,"id","isApproved ='0' and parent_id='0' ");
     for($i=0;$i<count($get_prod);$i++){
        $upatemainprod = updateQuery(PRODINFO, $data, "isApproved ='0' ");
        $upateinv = updateQuery(PRODINFO, $data, "isApproved ='0'  and parent_id= '".$get_prod[$i]['id']."'");
     }
     echo 1;
} ?>