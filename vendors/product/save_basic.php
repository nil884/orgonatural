<?php include("../../includes/configuration.php");
include("../../classes/product.php");
$get_vendor_details = selectQuery(VENDOR,"auto_approve_product","dealer_id=".$_SESSION['seller']); 
$prod = new Product();
$gettablename = selectQuery(PRODCAT,"template","id=".$subcategory);
$tablename = $gettablename[0]['template']; 
$todaydate = date("Y-m-d H:i:s");
if($tablename!=""){
    $results = showQuery($tablename);
    $arrcol = array();
    $arrtype = array();
    for($i=0;$i<count($results);$i++){
        array_push($arrcol, $results[$i]['Field']);
        array_push($arrtype, $results[$i]['Type']);
    }
}
$name = ucfirst(addslashes($productname));
$url = $prod->createProdURL($name,$subcategory);
$dataprod=array('parent_id' => 0, 'parent_cat' =>$parent_category, 'master_cat' =>$master_category, 'sub_cat'    =>$subcategory, 'product_type' => $product_type, 'prod_name' => trim(ucfirst(addslashes($productname))), 'url_title'=>$url, 'prod_company' => ucfirst(addslashes($Company)), 'hsn_code'=> $HSN_code, 'variation_available' => $Variation_Available, 'vendor' => $_SESSION['seller'], 'insert_date'=>date('Y-m-d H:i:s'),);
if($get_vendor_details[0]['auto_approve_product'] == 1){
    $dataprod['isApproved'] = 1;
    $dataprod['isActive'] = 1;
    $dataprod['approved_by'] = 'Auto';
    $dataprod['approved_by'] = 'Auto';
    $dataprod['approve_date'] = date('Y-m-d H:i:s');
}
$dataprod['seo_title'] = trim(ucfirst(addslashes($productname))); $dataprod['seo_description'] = trim(ucfirst(addslashes($productname))); $dataprod['seo_keywords'] = trim(ucfirst(addslashes($productname)));
$indata = insertQuery(PRODINFO,$dataprod);
if($indata){
    if($Variation_Available == 1){ 
    $inventry_array = $_SESSION['inventry_array'];
    for($i=0;$i<count($inventry_array);$i++){
    $getinventry = explode("|",$inventry_array[$i]);
    $val1 = explode("^",$getinventry[0]);
    $val2 = explode("^",$getinventry[1]);
    $val3 = explode("^",$getinventry[2]);
    if($getinventry[6]!= "") { $Sale_start_date = date("Y-m-d H:i:s", strtotime($getinventry[6])); } else { $Sale_start_date = "0000-00-00 00:00:00"; }
    if($getinventry[7] != ""){ $Sale_end_date = date("Y-m-d H:i:s", strtotime($getinventry[7])); } else { $Sale_end_date = "0000-00-00 00:00:00"; }
        if($getinventry[5] != "" && $Sale_start_date != "0000-00-00 00:00:00" && $Sale_end_date != "0000-00-00 00:00:00" && $Sale_start_date <= $todaydate && $todaydate <= $Sale_end_date){ $final_price = $getinventry[5]; }
        else{ $final_price = $getinventry[12]; }
        $name = trim(ucwords(addslashes($productname." ".$val1[1]." ".$val2[1]." ".$val3[1])));
        $url = $prod->createProdURL($name,$subcategory);
        $invetory = array('parent_id' => $indata, 'parent_cat' =>$parent_category, 'master_cat' =>$master_category, 'sub_cat' =>$subcategory, 'product_type' =>$product_type, 'prod_name'  => $name, 'url_title'=>$url, 'prod_company' => ucwords(addslashes($Company)), 'hsn_code'=> addslashes($HSN_code), 'vendor' => $_SESSION['seller'], 'insert_date'=>date('Y-m-d H:i:s'), 'variant_name1' =>$val1[0], 'variant_value1' => ucwords(addslashes($val1[1])), 'variant_name2' => $val2[0], 'variant_value2' => ucwords(addslashes($val2[1])), 'variant_name3' => $val3[0], 'variant_value3' => ucwords(addslashes($val3[1])), 'stock'=>  $getinventry[3], 'tax'=>$getinventry[13], 'mrp'=>$getinventry[12], 'vendor_reg_price' =>$getinventry[4], 'vendor_sale_price'=>$getinventry[5],   'final_price' => $final_price, 'vendor_sale_start_date'=> $Sale_start_date, 'vendor_sale_end_date'=> $Sale_end_date, 'weight'=> $getinventry[8], 'length'=>$getinventry[9], 'height'=>$getinventry[10], 'width' =>$getinventry[11], 'is_cancellation_avail' => $getinventry[14], 'return_days'=>$getinventry[15], 'sku'=>$getinventry[16], 'is_cod_avail' => $getinventry[17]);  
        if($get_vendor_details[0]['auto_approve_product'] == 1){
        $invetory['isApproved'] = 1;
        $invetory['isActive'] = 1;
        $invetory['approved_by'] = 'Auto';
        $invetory['approve_date'] = date('Y-m-d H:i:s');
        }
        $inssubprod = insertQuery(PRODINFO,$invetory);  
        if($inssubprod){
            $templatedata = array('prod_id' => $inssubprod, 'highlight'=>addslashes($description), );
            for($j=3;$j<sizeOf($arrcol);$j++){    
            $a=$arrcol[$j];
            if ($_REQUEST[$a]!= ""){ $templatedata[$a]=ucwords(addslashes($_REQUEST[$a])); } 
            else{
            if($a ==  $val1[0]){ $templatedata[$a]=ucwords(addslashes($val1[1])); }
            if($a ==  $val2[0]){ $templatedata[$a]=ucwords(addslashes($val2[1])); }
            if($a ==  $val3[0]){ $templatedata[$a]=ucwords(addslashes($val3[1])); }  
            }
            }
            $ins_templatedata=insertQuery($tablename,$templatedata);  
        }
    }
}
else {
    if($Sale_start_date!= ""){ $Sale_start_date = date("Y-m-d H:i:s", strtotime($Sale_start_date)); } else{ $Sale_start_date = "0000-00-00 00:00:00"; }
    if($Sale_end_date!= ""){ $Sale_end_date = date("Y-m-d H:i:s", strtotime($Sale_end_date)); } else{ $Sale_end_date = "0000-00-00 00:00:00"; }
    if($Sale_price != "" && $Sale_start_date != "0000-00-00 00:00:00" && $Sale_end_date != "0000-00-00 00:00:00" && $Sale_start_date <= $todaydate &&  $todaydate <=$Sale_end_date){  $final_price = $Sale_price;}
    else { $final_price = $mrp; }
    $invetory = array('sku' => $sku, 'stock' => $quantity, 'mrp'=>$mrp, 'vendor_reg_price' =>$regular_price, 'vendor_sale_price'=>$Sale_price, 'vendor_sale_start_date'=> $Sale_start_date , 'vendor_sale_end_date'=> $Sale_end_date ,'final_price' =>  $final_price, 'tax' => $tax, 'weight'=> $weight, 'length' => $Length, 'height' => $Height, 'width' => $Width, 'is_cancellation_avail' => $cancelation_Available, 'return_days'=>$Return_days,'is_cod_avail' => $cod_Available);
    $upategetsubcat = updateQuery(PRODINFO, $invetory, "id=" . $indata);
    $templatedata = array('prod_id' => $indata, 'highlight'=>addslashes($description),); 
for($i=3;$i<sizeOf($arrcol);$i++){    
    $a = $arrcol[$i];
    $templatedata[$a] = ucwords(addslashes($_REQUEST[$a]));
}
$ins_templatedata = insertQuery($tablename,$templatedata);
} 
if($Variation_Available == 1){$img_id = $inssubprod; } else { $img_id  = $indata; } ?>
<script> var main_id="<?=base64_encode($img_id); ?>"; window.location="uploadimg.php?prodid="+main_id;</script>
<?php } ?>