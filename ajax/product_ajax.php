<?php include("../includes/configuration.php");
include("../classes/product.php");
include "../classes/shiprocket.php";
$username = SHIPUSER; $pasword=SHIPPWD;
$ship = new shiprocket($username,$pasword);
$prod = new Product();
$action = $_POST['action'];
if($action=="getvariationURL"){
    $prodid = $_POST['prodid'];
    $variations = $_POST['variations'];
    $id = $prod->getProductVariationID($prodid,$variations);
    if($id){echo getUrl("Product",$id);} else{echo "";}
}
if($action=="addReview"){ $main_prod_id = $_POST['main_prod_id']; $prodid = $_POST['productid']; $userid = $_POST['userid']; $reviewtitle = $_POST['reviewTitle']; $reviewcomment = $_POST['reviewComment']; $rate = $_POST['rating'];
    echo $prod->addReview($main_prod_id,$prodid,$userid,$reviewtitle,$reviewcomment,$rate);
}
if($action=="checkpincode"){
     $isFreeShipping = ($getconfigdetails[0]['free_shipping_on_order']=="ON"?1:0); $freeShippingOn = $getconfigdetails[0]['free_shipping_on_order_cost'];
    $token = $ship->authenticate(); 
    $prodid = $_POST['prodid'];$price=$_POST['price'];$pincode=$_POST['pincode']; 
    //$pincode= 'aaaa';
    $deliverydetails = $prod->getDeliverydetails($prodid);

    $vendor = "v".$deliverydetails[0]['vendor']; $weight = $deliverydetails[0]['weight'];

    $pickups = $ship->getPickups($token);
    $pickupid = array_search($vendor, array_column($pickups, 'pickup_location'));
    $pickupdetails = $pickups[$pickupid];

     $getpin=selectQuery(MANUAL,"*","pincode='".$pincode."'");
     if(!count($getpin)){
        $data = array("pickup_postcode"=>$pickupdetails['pin_code'], "delivery_postcode"=>$pincode, "cod"=>0, "declared_value"=>$price, "weight"=>$weight, "is_return"=>0);
        $rate = $ship->getServiceability($token,$data);

        if($rate['status']!=200 ){
            echo "<span class='alert alert-danger d-inline-block'>Delivery is not available at ".$pincode."</span>";
        }else{
            $recoment = $rate['data']['shiprocket_recommended_courier_id'];
            $avail = $rate['data']['available_courier_companies'];
            $id = array_search($recoment, array_column($avail, 'courier_company_id'));
            $shipdata = $avail[$id];
            $shiprate = $shipdata['rate'];
            $deliverydate = $shipdata['etd'];
              if($isFreeShipping==1){
                 echo "<span class='alert alert-success d-inline-block'>Congratulation, delivery is available at ".$pincode."<br>Delivery by ".$deliverydate."<span class='mx-1'></span><br>";
                if($price>=$freeShippingOn){
                 echo "</span><span class='alert alert-success d-inline-block'>";  echo "<b>CONGRATULATION</b>, this order is eligible for free shipping ";  echo "</span>";
                }else{
                    echo "Shipping Charges : <i class='fa fa-inr'></i>".$shiprate; echo "</span>";
                    echo "<span class='alert alert-success d-inline-block'><b>OFFER</b>: Free shipping is available on order value above <i class='fa fa-inr'></i>".$freeShippingOn;  echo "</span>";
                }
              }else{
                   echo "<span class='alert alert-success d-inline-block'>Congratulation, delivery is available at ".$pincode."<br>Delivery by ".$deliverydate."<span class='mx-1'></span><br>";
                   echo  "Shipping Charges : <i class='fa fa-inr'></i>".$shiprate;
                   echo "</span>";
            }
        }
    }else{
         $r=$getpin[0];  $ratePer=$r['chargesPer']; $rateUnit=$r['chargesUnit'];  $shippingcharges=$r['shipping_charges'];   $delivery=$r['deliveryDays'];

       if($rateUnit=="piece"){
            $shiprate=  $shippingcharges;
       }else if($rateUnit=="gm"){
            $wtingm= $weight*1000;
            $conv=$wtingm/$ratePer;
            $shiprate= round($conv)*$shippingcharges;
       }
        $deliverydate=Date('M d,Y', strtotime('+'.$delivery.' days'));
          if($isFreeShipping==1){
                 echo "<span class='alert alert-success d-inline-block'>Congratulation, delivery is available at ".$pincode."<br>Delivery by ".$deliverydate."<span class='mx-1'></span><br>";
                if($price>=$freeShippingOn){
                 echo "</span><span class='alert alert-success d-inline-block'>";  echo "<b>CONGRATULATION</b>, this order is eligible for free shipping ";  echo "</span>";
                }else{
                    echo "Shipping Charges : <i class='fa fa-inr'></i>".$shiprate; echo "</span>";
                    echo "<span class='alert alert-success d-inline-block'><b>OFFER</b>: Free shipping is available on order value above <i class='fa fa-inr'></i>".$freeShippingOn;  echo "</span>";
                }
              }else{
                   echo "<span class='alert alert-success d-inline-block'>Congratulation, delivery is available at ".$pincode."<br>Delivery by ".$deliverydate."<span class='mx-1'></span><br>";
                   echo  "Shipping Charges : <i class='fa fa-inr'></i>".$shiprate;
                   echo "</span>";
            }
    }
}
if($action=="Add_to_cart"){
    if(isset($_SESSION['shopping_cart'])){ $item_array_id = array_column($_SESSION['shopping_cart'] ,"prod_id" ); } else { $item_array_id = array(); }
    if(!in_array($prodid,$item_array_id )){
        $cmp_array = $_SESSION['shopping_cart'];
        $item_array = array('prod_id'=>$prodid,'quantity' =>$qunatity );
        $cmp_array[] = $item_array;
        $_SESSION['shopping_cart'] = $cmp_array;
        if($_SESSION['reguser'] != ""){  
            $data = array(
            'user_id'=>$_SESSION['reguser'],'type'=> 'CART' , 'prod_id'=> $prodid,'quantity'=> $qunatity,'insert_date'=> date("Y-m-d H:i:s"));
            $checkincart = selectQuery(CART,"id","user_id='".$_SESSION['reguser']."' and type ='CART' prod_id = '".$cartdet1[$i]['prod_id']."' ");
            if(count($checkincart) == 0){ $insert = insertQuery(CART,$data); }
        }
        echo 1;
    } else{ echo "Exist"; } 
}
if($action=="addtowishlist"){
    if(isset($_SESSION['wishitems'])){ $item_array_id = array_column($_SESSION['wishitems'] ,"prod_id" ); } else { $item_array_id = array(); }
    if(!in_array($prodid,$item_array_id )){
        $cmp_array = $_SESSION['wishitems'];
        $item_array = array('prod_id'=>$prodid,'quantity' =>$qunatity );
        $cmp_array[] = $item_array;
        $_SESSION['wishitems'] = $cmp_array;
        if($_SESSION['reguser'] != ""){  
            $data = array( 'user_id'=>$_SESSION['reguser'],'type'=> 'WISHLIST' , 'prod_id'=> $prodid,'quantity'=> $qunatity,'insert_date'=> date("Y-m-d H:i:s"));
            $checkincart = selectQuery(CART,"id","user_id='".$_SESSION['reguser']."' and type ='WISHLIST' prod_id = '".$cartdet1[$i]['prod_id']."' ");
            if(count($checkincart) == 0){ $insert = insertQuery(CART,$data); }
        }
        echo 1;
    } else{ echo "Exist"; }
}
if($action == "remove_from_wishlist"){
    $prodid = $_REQUEST['prodid'];
    foreach($_SESSION['wishitems'] as $key =>$value){
        if($value['prod_id'] == $prodid){
            unset($_SESSION['wishitems'][$key]);
            $_SESSION['wishitems'] = array_values($_SESSION['wishitems']);
        }
    } if($_SESSION['reguser'] != ""){
        $delete = deleteQuery(CART,"user_id=".$_SESSION['reguser']." and prod_id= ".$prodid." and type= 'WISHLIST' ");
    }
    echo count($_SESSION['wishitems']);
}
if($action == "remove_from_cart"){
    $prodid = $_REQUEST['prodid'];
    foreach($_SESSION['shopping_cart'] as $key =>$value){
        if($value['prod_id'] == $prodid){
            unset($_SESSION['shopping_cart'][$key]);
            $_SESSION['shopping_cart'] = array_values($_SESSION['shopping_cart']);
        }
    } if($_SESSION['reguser'] != ""){
        $delete= deleteQuery(CART,"user_id=".$_SESSION['reguser']." and prod_id= ".$prodid." and type= 'CART' ");
    }
    echo count($_SESSION['shopping_cart']);
}
if($action == "remove_deleted_cart"){
    require_once('../classes/product.php'); $prod = new Product();
    $getcart = selectQuery(CART,"prod_id,cart_id,quantity","user_id=".$_SESSION['reguser']." and type='CART'");
    for($i=0;$i<count($getcart);$i++){
        $product = $getcart[$i]['prod_id'];$cart_id= $getcart[$i]['cart_id'];
        $productdata = $prod->getProductFullDetails($product); 
        if($productdata['name'] ==""){ $prod->removeFromCart($cart_id,$product); }  
    }
    echo 1;
}
if($action == "remove_deleted_wishlist"){
    require_once('../classes/product.php'); $prod = new Product();
    $getcart=selectQuery(CART,"prod_id,cart_id,quantity","user_id=".$_SESSION['reguser']." and type='WISHLIST'");
    for($i=0;$i<count($getcart);$i++){
        $product = $getcart[$i]['prod_id'];$cart_id= $getcart[$i]['cart_id'];
        $productdata = $prod->getProductFullDetails($product); 
        if($productdata['name'] ==""){ $prod->removeFromWishlist($cart_id,$product); }  
    }
    echo 1;
}
if($action == "decidequantity_wishlist"){
    $cartid = $_POST['arrayid'];
    $_SESSION['wishitems'][$cartid]['quantity'] = $_POST['no_of_unit'];
    $cartdet = $_SESSION['wishitems'];
    if($_SESSION['reguser'] != ""){
        $data = array("quantity" => $_POST['no_of_unit']  );
        $upategetsubcat = updateQuery(CART, $data, "user_id=".$_SESSION['reguser']." and prod_id= ".$prod_id." and type= 'WISHLIST'");
    }
    echo 1;
}
if($action == "decidequantity"){
    $cartid = $_POST['arrayid'];
    $_SESSION['shopping_cart'][$cartid]['quantity'] = $_POST['no_of_unit'];
    $cartdet = $_SESSION['shopping_cart'];
    if($_SESSION['reguser'] != ""){
        $data = array("quantity" => $_POST['no_of_unit']  );
        $upategetsubcat = updateQuery(CART, $data, "user_id=".$_SESSION['reguser']." and prod_id= ".$prod_id." and type= 'CART'");
    }
    echo 1;
}
if($action == "add_to_compare"){
    if($_SESSION['compare'] != ""){ $product_array = $_SESSION['compare'];}
    else { $product_array = array();}
    if(count($product_array) > COMPAIRLIMIT ){
    echo 'limit';
    } else{
        $getsubcat = selectQuery(PRODINFO,"sub_cat","id='".$prodid."'");
        if($_SESSION['subcat'] == ""){$_SESSION['subcat'] = $getsubcat[0]['sub_cat'];} 
        if($_SESSION['subcat'] == "" || $_SESSION['subcat'] == $getsubcat[0]['sub_cat']){
            if(!in_array($prodid,$product_array )){
                array_push($product_array,$prodid);
                $_SESSION['compare'] = $product_array;
                echo count($_SESSION['compare']);
            } else{ echo "Exist"; }
        } else { echo "Diffrent"; }
    }
}
if($action == "remove_from_compare"){
    $product_array = $_SESSION['compare'];
    if (($key = array_search($prodid, $product_array)) !== false) {
        unset($product_array[$key]);
        $_SESSION['compare'] = array_values($product_array);
        if(count($_SESSION['compare']) == 0 ){
            unset($_SESSION['subcat']);
        } 
    }
    echo count($_SESSION['compare']);
} 
if($action == "clearcompare"){
    unset($_SESSION['subcat']); 
    unset($_SESSION['compare']);
    echo 1; 
}
if($action == "bulk_order"){
    $getProductDetails = $prod->getProductFullDetails($product_id);
    $var_str = "";
    $variations = $getProductDetails['variations'];
    $currentvar = $getshortdetails['currentVariartions'];
    $variationarr = array();  $varcnt=0;
    foreach($variations as $key=>$val){ $variationarr[$key]= $currentvar[$varcnt];  $varcnt++;  }
    foreach($variations as $key=>$value){ 
        $var_str = $var_str." ".$key.":".implode(",",array_unique($value));
    }
    $fisrtname = $_REQUEST['name1'];
    $lastname = $_REQUEST['name2'];
    $email = $_REQUEST['email'];
    $contactNo = $_REQUEST['contactNop'];
    if($_REQUEST['Adress'] != ""){ $Adress = $_REQUEST['Adress']; } else{ $Adress = "NA"; }
    $qty = $_REQUEST['qty'];
    if($_REQUEST['date'] != ""){ $expdate = $_REQUEST['date']; } else{ $expdate = "NA"; }
    $mail1 = $email;
    $replacement_array =  array('siteurl' => SITEURL, 'sitename' => SITENAME, 'smssitename' => SMSSITENAME, "name" =>  $fisrtname." ".$lastname, "mobile"=> $contactNo, "email"=>$email, "Address" =>  $Adress, "Productname" => $getProductDetails['name'], "qunatity" =>$qty, "variation_detail" =>$var_str, "Expected_delivery_date" => $expdate,);
    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers .= "From:".SITENAME."<".EMAIL_SENDER.">";
    $admin_email = selectQuery(EMAILTEMPLATE,"subject,body","type='Bulk order' and  mail_to= 'Admin' "); 
    $subject_admin = convertemailstr($replacement_array,$admin_email[0]['subject']);
    $body_admin = convertemailstr($replacement_array,$admin_email[0]['body']);   
    $sentmail1 = @mail(EMAIL_ORDER, $subject_admin, $body_admin, $headers);     
    $buyer_email = selectQuery(EMAILTEMPLATE,"subject,body","type='Bulk order' and  mail_to= 'Buyer' "); 
    $subject_buyer = convertemailstr($replacement_array,$buyer_email[0]['subject']);
    $body_buyer = convertemailstr($replacement_array,$buyer_email[0]['body']);   
    $sentmail = @mail($email , $subject_buyer, $body_buyer, $headers);
    if(SMS_SYSTEM=="ON"){    
        $buyer_sms = selectQuery(SMSTEMPLATE,"sms_text,templateId","type='Bulk order' and  sms_to = 'Buyer' ");
        $admin_sms = selectQuery(SMSTEMPLATE,"sms_text,templateId","type='Bulk order' and  sms_to = 'Admin' ");
        $msg = convertsmsstr($replacement_array,$buyer_sms[0]['sms_text'] );
        $templateId= $buyer_sms[0]['templateId'];
        $sms = sendsms($contactNo,$msg,WORKINGKEY,SMS_SENDER,$templateId);
        $id = (unserialize($sms));
        $msid = $id['data']['0']['id'];
        $status = $id['data']['0']['status'];
        $datasms = array("msg_id"=>$msid, "msg_type"=>"Bulk Order SMS To Buyer", "user_name"=> $fisrtname." ".$lastname, "mobile_no"=>$contactNo, "message"=>$msg, "date"=>date("Y-m-d H:i:s"), "status"=>$status );
        $store = insertQuery(SMS,$datasms);
        $arr = explode(",",ADMINCONTACT);
        for($k=0;$k<sizeOf($arr);$k++){
            $tempmob = $arr[$k];
            $msg = convertsmsstr($replacement_array,$admin_sms[0]['sms_text'] );
            $templateId = $admin_sms[0]['templateId'];
            $sms1 = sendsms($tempmob,$msg,WORKINGKEY,SMS_SENDER,$templateId);
            $id1 = (unserialize($sms1));
            $msid1 = $id1['data']['0']['id'];
            $status1 = $id1['data']['0']['status'];
            $datasms1 = array("msg_id"=>$msid1, "msg_type"=>"Bulk Order SMS To Admin", "user_name"=>"Admin",
            "mobile_no"=>$tempmob, "message"=>$msg, "date"=>date("Y-m-d H:i:s"), "status"=>$status1,);
            $insert1 = insertQuery(SMS,$datasms1);
        }
    }
    if($sentmail1){ echo "1"; } else{ echo "0";}
}

/* if($action=="getvariationProduct"){
    $prodid=base64_decode($_POST['prodid']); $parent=base64_decode($_POST['parent']); $variationBy=$_POST['variationBy']; $variation=$_POST['variation'];
    $getdef1   = selectQuery(PRODATTR,"attr_for_template","attr_name='".$variationBy."' AND attr_for_template<>''");
    $getprodvar   = selectQuery(PRODINFO,"variant_name1,variant_name2,variant_name3","id=".$prodid);
    if($getprodvar[0]['variant_name1']==$getdef1[0]['attr_for_template']){ $vari='variant_name1';  $varval='variant_value1'; }
    else if($getprodvar[0]['variant_name2']==$getdef1[0]['attr_for_template']){ $vari='variant_name2'; $varval='variant_value2';}
    else if($getprodvar[0]['variant_name3']==$getdef1[0]['attr_for_template']){ $vari='variant_name3';  $varval='variant_value3';}
    $getprod   = selectQuery(PRODINFO,"id","parent_id=".$parent." AND ".$vari."='".$getdef1[0]['attr_for_template']."' AND ".$varval."='".$variation."'");
  
    $productids=$prod->getProductsByGroup("id=".$getprod[0]['id']);
    $imgtype = "product"; include("../getimgpath.php");
   for($i=0;$i<count($productids);$i++){
    
    include("../product-view.php");
   }
   
} */

if($action=="getvariationProduct"){
    $prodid=base64_decode($_POST['prodid']); $parent=base64_decode($_POST['parent']); $variationBy=base64_decode($_POST['variationBy']);
    $vari=json_decode($variationBy,true);
   // $keys=array_keys($vari);
   //print_r($vari); 
   $whrarr=array();
   foreach($vari as $key=>$value){ 
    $getdef1   = selectQuery(PRODATTR,"attr_for_template","attr_name='".$key."' AND attr_for_template<>''");
    $getprodvar   = selectQuery(PRODINFO,"variant_name1,variant_name2,variant_name3","id=".$prodid);
    if($getprodvar[0]['variant_name1']==$getdef1[0]['attr_for_template']){ $vari='variant_name1';  $varval='variant_value1';  $str="(".$vari."='".$getdef1[0]['attr_for_template']."' AND ".$varval."='".$value[0]."')";  array_push($whrarr,$str); }
    else if($getprodvar[0]['variant_name2']==$getdef1[0]['attr_for_template']){ $vari='variant_name2'; $varval='variant_value2';  $str="(".$vari."='".$getdef1[0]['attr_for_template']."' AND ".$varval."='".$value[0]."')";  array_push($whrarr,$str);}
    else if($getprodvar[0]['variant_name3']==$getdef1[0]['attr_for_template']){ $vari='variant_name3';  $varval='variant_value3';  $str="(".$vari."='".$getdef1[0]['attr_for_template']."' AND ".$varval."='".$value[0]."')";  array_push($whrarr,$str);}
    } 
    
    $whrestr= implode(" AND ",$whrarr);

     $getprod   = selectQuery(PRODINFO,"id","parent_id=".$parent." AND ".$whrestr);
  
    $productids=$prod->getProductsByGroup("p.id=".$getprod[0]['id']);
    $imgtype = "product"; include("../getimgpath.php");
   for($i=0;$i<count($productids);$i++){
    include("../product-view.php");
   }
   
}


if($action=="priority_image"){
    $str = $_POST['str'];
    $priority = explode(',',$str);
    $cnt =1;
    for($i=0;$i<count($priority);$i++){
        $check = selectQuery(PRODIMG,'img_name','id='.$priority[$i]);
        if(count($check)){
            $pr = array('priority'=>$cnt);
            updateQuery(PRODIMG,$pr,'id='.$priority[$i]); 
            $cnt++;
        }
    }

}
?>