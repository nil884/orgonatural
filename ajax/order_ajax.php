<?php require_once("../includes/configuration.php");
if($action=="pincodedetails"){
    require_once "../classes/shiprocket.php";
    $username = SHIPUSER; $pasword=SHIPPWD;
    $ship = new shiprocket($username,$pasword);
    $pincode = $_POST['pincode'];
    $token = $ship->authenticate();
    $data = array("postcode"=>$pincode);
    $res = $ship->getPincodeData($token,$data);
    if($res['postcode_details']){
    $data = $res['postcode_details'];
    $ret = array("status"=>"success","city"=>$data['city'],"state"=>$data['state']);
    }else{ $ret=array("status"=>"failed","message"=>$res['message']); }
    echo json_encode($ret);
}
if($action=="addAddressAndSet"){
    require_once("../classes/user.php"); 
    $usercl = new User(); 
    $adress_type = $_POST['adress_type']; $country = $_POST['country']; 
    $user = $_POST['user'];$fullname=$_POST['fullname']; $mobile = $_POST['mobile']; $pincode = $_POST['pincode']; $address = $_POST['address']; $location = $_POST['addlocation']; $city = $_POST['city']; $state = $_POST['state'];
    if($user!=""){
        $check = $usercl->checkDuplicateShippingAddress($user,$fullname,$mobile,$pincode,$address,$location,$city,$state);
        if($check==0){
            $in = $usercl->addShippingAddress($user,$fullname,$mobile,$pincode,$address,$location,$city,$state,$country,$adress_type);
            echo $in; $_SESSION['userPincode']=$pincode;$_SESSION['shippingAdrId']=$in;
        } else{echo $check;}
    }else{ echo 0; }
}
if($action=="setUserPincode"){ $_SESSION['userPincode'] = $_POST['pincode']; $_SESSION['shippingAdrId'] = $_POST['adrid'];}
    if($action=="removeUserPincode"){ unset($_SESSION['userPincode']);  unset($_SESSION['shippingAdrId']); }
    function getTransId(){
    $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
    $ord = selectQuery(ORDER,"transaction_id","transaction_id='".$txnid."'");
    if(count($ord)){getTransId();} else{
        $data = array("transaction_id"=>$txnid); $id=insertQuery(ORDER,$data);
        $res = array("transaction_id"=>$txnid,"ordid"=>$id); return $res;
    }
}
if($action=="proceedOrder"){
   
    require_once "../classes/shiprocket.php";
    $imgtype = "product";
    include("../getimgpath.php");
    $username = SHIPUSER; $pasword=SHIPPWD;
    $ship = new shiprocket($username,$pasword);
   
    $paymentmode = $_POST['paymentmode'];
    $pincode = $_POST['pincode'];
    $token = $ship->authenticate();
    if($_SESSION['reguser']!=""){
        $transres = getTransId();
        $fail = 0;
        $ordid = $transres['ordid']; $transid = $transres['transaction_id'];
        require_once("../classes/user.php"); require_once("../classes/product.php");
        $usercl = new User(); $prod = new Product();
        $data = $_POST['items']; $dataarr=json_decode($data,true);
        $shippingId = $dataarr['shipingAddress']; $isFreeShipping=$dataarr['isFreeShipping'];
        $items = $dataarr['items'];
        $shipingdetails = $usercl->getShippingDetails($shippingId);
        $adminpin = $getconfigdetails[0]['pincode'];
        //for calculating gst
        /*$data= array("postcode"=>$adminpin);
        $res = $ship->getPincodeData($token,$data); $pinRes = $res['postcode_details']; $adminState=$pinRes['state'];
        $userpincode = $shipingdetails[0]['pincode'];
        $dataUserPin = array("postcode"=>$userpincode);
        $resUPin=$ship->getPincodeData($token,$dataUserPin); $pinResUser= $resUPin['postcode_details'];
        $UserState=$pinResUser['state'];*/
      
        if($dataarr['isFreeShipping'] == 1){ $freeship_reason ="Free shipping above Rs ".$getconfigdetails[0]['free_shipping_on_order_cost']." /- ."; }  else{ $freeship_reason = "";  }
        $dataorder = array(
        "order_date" => date("Y-m-d H:i:s"),
        "user_id" => $_SESSION['reguser'],
        "is_cod" => ($paymentmode=="COD"?1:0),
        "payment_mode" => $paymentmode,
        "total_taxable_amount" => $dataarr['totalTaxable'],
        "isFreeShipping" => $dataarr['isFreeShipping'],
        "Freeshippingreason" =>   $freeship_reason, 
        "total_shipping_charges" => $dataarr['totalShipping'],
        "total_gst" => $dataarr['totalGst'],
        "total_cod_charges"=> $dataarr['totalCodCharges'],
        "total_bill_amount" => ($dataarr['totalTaxable']+$dataarr['totalGst']+$dataarr['totalCodCharges']+($dataarr['isFreeShipping']==0?$dataarr['totalShipping']:0.00)),
        //"total_bill_amount"=> ($dataarr['totalTaxable']+($dataarr['isFreeShipping']==0?$dataarr['totalShipping']:0.00)),
        "payable_amount" => $dataarr['finalPayable'],
        "payment_status" => "Initiated",
        "shippingAddr_id" =>$shippingId,
        "shippingAddr_type" => $shipingdetails[0]['address_type'],
        "shippingAddr_name" => $shipingdetails[0]['address_name'],
        "shippingAddr_mobile" => $shipingdetails[0]['mobile_number'],
        "shippingAddr_address" => $shipingdetails[0]['address'],
        "shippingAddr_landmark" => $shipingdetails[0]['landmark'],
        "shippingAddr_city" => $shipingdetails[0]['city'],
        "shippingAddr_state" => $shipingdetails[0]['state'],
        "shippingAddr_country" => $shipingdetails[0]['country'],
        "shippingAddr_pincode" => $shipingdetails[0]['pincode']);
       
        $up=updateQuery(ORDER,$dataorder,"id=".$ordid); /* $totaltax = 0; $totaltaxable = 0;*/
       
        for($i=0;$i<count($items);$i++){
        $prodid = $items[$i]['productid'];
        if($prodid){
            $proddata = $prod->getselectedAttr($prodid,"prod_name,prod_company,parent_id,variant_name1,variant_name2,variant_name3,variant_value1,variant_value2,variant_value3,return_days,is_cancellation_avail,admin_price,mrp,stock,sold,blocked,tax,hsn_code,sku,isActive");
            $prodname = $proddata[0]['prod_name'];
            $tax = $proddata[0]['tax']; $quantitiesPurchase = $items[$i]['quantity'];
            $vendorprice = $prod->getVendorProductPriceForOrder($prodid);  $adminprice = $proddata[0]['admin_price']; $mrp = $proddata[0]['mrp'];
            $vendorpriceWithGst = $vendorprice; $adminpriceWithGst = $adminprice;
            $getPriceDetails = $prod->getProductPrice($prodid);
            $prodsellOn = $getPriceDetails['priceon'];
            $vendorpriceWithoutGst = round($vendorpriceWithGst-(($vendorpriceWithGst*($tax/(100+$tax)))));
            $adminpriceWithoutGst = round($adminpriceWithGst-(($adminpriceWithGst*($tax/(100+$tax)))));
            //$vendorpriceWithGst = ceil($vendorprice+round((($vendorprice/100)*$tax),2));  $adminpriceWithGst= ceil($adminprice+round((($adminprice/100)*$tax),2));
            $soldonprice = $items[$i]['perUnitPrice'];
            $taxonmrp = ($mrp*($tax/(100+$tax)));
            $mrpWithoutGst = round($mrp-$taxonmrp);
            /*if($soldonprice==$mrpWithoutGst){ $perwithoutgst = $mrpWithoutGst; }else if($soldonprice==$adminprice){ $perwithoutgst = $adminprice; }else if($soldonprice==$vendorprice){ $perwithoutgst = $vendorprice; }*/
            if($prodsellOn=="MRP"){ $perwithoutgst = $mrpWithoutGst; }else if($prodsellOn=="Admin Price"){ $perwithoutgst = $adminprice; }else if($prodsellOn=="Vendor Price"){ $perwithoutgst = $vendorprice; }
            $currentStock = (($proddata[0]['stock']!=""?$proddata[0]['stock']:0)-(($proddata[0]['sold']!=""?$proddata[0]['sold']:0)+($proddata[0]['blocked']!=""?$proddata[0]['blocked']:0)));
             if($proddata[0]['prod_name']!=""&&$proddata[0]['isActive']=="1"&&($quantitiesPurchase<=$currentStock)){
                 $checkprod = selectQuery(ORDERSUB,"count(item_id) as itmcnt","order_id=".$ordid." and product_id=".$prodid);
                if($checkprod[0]['itmcnt']==0){
                    $varidMainarra=array();
                    if($proddata[0]['variant_name1']!="")array_push($varidMainarra,getOriginalName($proddata[0]['variant_name1']));
                    if($proddata[0]['variant_name2']!="")array_push($varidMainarra,getOriginalName($proddata[0]['variant_name2']));
                    if($proddata[0]['variant_name3']!="")array_push($varidMainarra,getOriginalName($proddata[0]['variant_name3']));
                    $varidvalues=array();
                    if($proddata[0]['variant_value1']!="")array_push($varidvalues,$proddata[0]['variant_value1']);
                    if($proddata[0]['variant_value2']!="")array_push($varidvalues,$proddata[0]['variant_value2']);
                    if($proddata[0]['variant_value3']!="")array_push($varidvalues,$proddata[0]['variant_value3']);
                    $getvendordata = $prod->getVendorDetails($items[$i]['vendorId']);
                    $dataItem = array("order_id" =>$ordid, "order_current_Status" =>"Initiated", "product_id" =>$prodid, "product_name" =>trim(addslashes($prodname)), "display_product_name" =>addslashes($prod->getParentName($prodid)), "company" =>addslashes($proddata[0]['prod_company']), "product_image" =>addslashes($items[$i]['image'][0]['img_name']),"hsn_code" =>$proddata[0]['hsn_code'],"sku" =>$proddata[0]['sku'], "vendor" =>$items[$i]['vendorId'], "vendor_nickname" =>$getvendordata[0]['nickname'], "vendor_name" =>$getvendordata[0]['dealer_name'], "vendor_email" =>$getvendordata[0]['email'], "vendor_contact" =>$getvendordata[0]['personalcontactno'], "vendor_city" =>$getvendordata[0]['city'], "disbustment_days"=>($getvendordata[0]['disbursement_cycle_days']!=""?$getvendordata[0]['disbursement_cycle_days']:VENDOR_DISBURSMENT_DAYS), "variation_on" =>implode("|",$varidMainarra), "variation_values" =>implode("|",$varidvalues), "product_sold_on"=>$prodsellOn, "mrp"=>$mrp, "mrpWithoutTax"=>$mrpWithoutGst, "vendor_per_item_price_withoutgst" =>$vendorpriceWithoutGst, "admin_per_item_price_withoutgst"=>$adminpriceWithoutGst, "user_per_unit_withoutgst_price"=>$items[$i]['perUnitPrice'], "tax_percentage" =>$tax, "quantity" =>$quantitiesPurchase, "taxable_without_gst" =>$items[$i]['basictaxable'],  "taxable_without_gst_after_discount"=>$items[$i]['taxable'], "cgst1"=>$items[$i]['cgst1'], "cgst2"=>$items[$i]['cgst2'], "sgst1" =>$items[$i]['sgst1'],"sgst2"=>$items[$i]['sgst2'], "igst1"=>$items[$i]['igst1'], "igst2" =>$items[$i]['igst2'], "total_with_gst" =>($items[$i]['taxable']+$items[$i]['cgst2']+$items[$i]['sgst2']+$items[$i]['igst2']), "isFreeShipping"=> $dataarr['isFreeShipping'], //"Freeshippingreason" => $freeship_reason,
                    "shipping_charges"=>$items[$i]['shipping'],"cod_charges"=>$items[$i]['codCharges'], "total_payable"=>$items[$i]['totalPayable'], "is_cancellation_avail"=>$proddata[0]['is_cancellation_avail'], "return_days"=>$proddata[0]['return_days'], "selected_courier_id"=>$items[$i]['courierid'], "selected_courier_name"=>$items[$i]['courierby'], "expected_delivery"=>$items[$i]['delivery']);
                    
                    if($items[$i]['applicableOn']!="order"){ $dataItem["discount_type"]=($items[$i]['discountCode']!=""?"Promo Code":""); $dataItem["discount_code"]=$items[$i]['discountCode']; $dataItem["discount_amount"]=$items[$i]['discount'];}
                    else if($items[$i]['applicableOn']=="order"){$da=array("discount_type"=>"Promo Code","discount_amount"=>$dataarr['discount'],"discount_code"=>$items[$i]['discountCode']); updateQuery(ORDER,$da,"id=".$ordid); }

                    if($items[$i]['courierby']==SITENAME){ $dataItem['shipping_by']=SITENAME; }
                    if($items[$i]['image'][0]['img_name'] != ""){
                    $file = '../'.$thumb2_path.'/'.$items[$i]['image'][0]['img_name'];
                    $newfile = '../img/order_img/'.$items[$i]['image'][0]['img_name'];
                    copy($file, $newfile);
                    }
                    insertQuery(ORDERSUB,$dataItem);
                    $produp = array("blocked"=>$proddata[0]['blocked']+$quantitiesPurchase);
                    $blockdata = array("transaction"=>$transid,"prod_id"=>$prodid,"quantity"=>$quantitiesPurchase,"blocktime"=>date("Y-m-d H:i:s"));
                    insertQuery(BLOCK,$blockdata);
                    updateQuery(PRODINFO,$produp,"id=".$prodid);
                }
            }else{
                $fail = 1;
                deleteQuery(ORDER,"id=".$ordid);   
                $checkprod = selectQuery(ORDERSUB,"product_id,quantity","order_id=".$ordid);
                for($i=0;$i<count($checkprod);$i++){
                    $rollprodid = $checkprod[$i]['product_id'];
                    $rollproddata = $prod->getselectedAttr($rollprodid,"stock,sold,blocked");
                    $updatedblock = $rollproddata[0]['blocked']-$checkprod[$i]['quantity'];
                    $rollprodup = array("blocked"=>$updatedblock);
                    updateQuery(PRODINFO,$rollprodup,"id=".$rollprodid);
                }
                deleteQuery(ORDERSUB,"order_id=".$ordid);
                if($proddata[0]['prod_name']==""||$proddata[0]['isActive']=="0"){
                    $failProd = $prodid;  $failmessage= $items[$i]['name']." Currently Not Available. Remove It To Proceed";
                }else if($quantitiesPurchase>$currentStock){
                    $failProd = $prodid; $failmessage= $items[$i]['name']." Currently Only have ".$currentStock." items.";
                }
                break;
            }
        }
    }
    if($fail==0){ $res = array("status"=>"success","resid"=>$transid); }else{ $res = array("status"=>"failed","failProd"=>$failProd,"message"=>$failmessage); }
    }else{ $res = array("status"=>"failed","message"=>"Session Logout","action"=>SITEURL."/login"); }
    echo json_encode($res);
}
if($action=="getPromoCodes"){
    $prodid = $_POST['prodid'];
    $getsubcat=selectQuery(PRODINFO,"sub_cat,parent_id","id=".$prodid);
    $product=($getsubcat[0]['parent_id']!=0?$getsubcat[0]['parent_id']:$prodid);
    $wherestr="((applicableOn='Order') OR (applicableOn='All Products') OR (applicableOn='Subcategories Products' AND FIND_IN_SET(".$getsubcat[0]['sub_cat'].", applicableId)) OR  (applicableOn<>'All Products' AND FIND_IN_SET(".$product.", applicableId)))";
        
    $running = selectQuery(COUPON,"couponId,couponCode,description,discountType,discountValue,validTill,minOrderValueRequire,minOrderValue,limitPerUser","showToAll='1' AND validFrom<='".date('Y-m-d H:i:s')."' AND validTill>='".date('Y-m-d H:i:s')."' AND ".$wherestr."");
    if(count($running)){
    for($i=0;$i<count($running);$i++){ ?>
    <div class="border-bottom card-body">
        <h5 class="mb-1 cc-fw-5"> <?=$running[$i]['couponCode'];?> </h5>
        <div class="text-muted mb-2">Offer Valid Till - <?=date("d M Y h:i A",strtotime($running[$i]['validTill']));?></div>
        <div><?=$running[$i]['description'];?></div>
        <ol class="mb-0 pl-3 mt-2">
            <? if($running[$i]['discountType']=="Price"){ ?> <li>Get Flat Rs. <?=$running[$i]['discountValue']; ?> Off</li> <? } ?>
            <? if($running[$i]['discountType']=="Percentage"){ ?> <li>Get <?=$running[$i]['discountValue']; ?>% Off</li> <? } ?>
            <? if($running[$i]['minOrderValueRequire']==1){ ?> <li>Minimum order value <i class="fa fa-inr"></i><?=$running[$i]['minOrderValue'];?></li> <? } ?>
            <? if($running[$i]['limitPerUser']!=0){ ?><li>Coupon is applicable <?=$running[$i]['limitPerUser']; ?> times per user</li> <? } ?>
        </ol>
    </div>
    <? } }else{ ?><div class="card-body">No Active Promocode Available</div> <?
    }
}
if($action=="applyPromocode"){
    $promotype = $_POST['promotype'];$promovalue=$_POST['promovalue'];
    $prodid = $_POST['prodid'];
    $userid = $_SESSION['reguser'];
    $prodarr = array();  $applicable = array();
    $getsubcat=selectQuery(PRODINFO,"sub_cat,parent_id","id=".$prodid);
    $product=($getsubcat[0]['parent_id']!=0?$getsubcat[0]['parent_id']:$prodid);
    $wherestr="((applicableOn='Order') OR (applicableOn='All Products') OR (applicableOn='Subcategories Products' AND FIND_IN_SET(".$getsubcat[0]['sub_cat'].", applicableId)) OR  (applicableOn<>'All Products' AND FIND_IN_SET(".$product.", applicableId)))";

    $running = selectQuery(COUPON,"couponId,couponCode,description,discountType,discountValue,validTill,minOrderValueRequire,minOrderValue,limitPerUser,applicableOn","couponCode='".$promovalue."' AND validFrom<='".date('Y-m-d H:i:s')."' AND validTill>='".date('Y-m-d H:i:s')."' AND ".$wherestr."");
    if(count($running)){
        $limitPerUser = $running[0]['limitPerUser'];
        $chkorder = selectQuery(ORDER." as o join ".ORDERSUB."  as os on o.id= os.order_id ","os.order_id","user_id=".$userid." AND os.discount_code='".$promovalue."' AND (order_current_status!='Initiated' AND order_current_status!='Failed' AND order_current_status!='Cancelled')");
        if($limitPerUser!=0&&$limitPerUser<=count($chkorder)){
            $data = array("status"=> "fail","promoType"=>$promotype,"message"=>"You Have Already Used Selected Promocode");
        }else{
            $data = array("status"=> "success","promoType"=>$promotype,"couponCode"=>$promovalue,"discountType"=>$running[0]['discountType'],"discountValue"=>$running[0]['discountValue'],"minOrderValueRequire"=>$running[0]['minOrderValueRequire'],"minOrderValue"=>$running[0]['minOrderValue'],"applicableOn"=>($running[0]['applicableOn']!="Order"?$prodid:"Order"));
        }
    }else{ $data = array("status"=> "fail","promoType"=>$promotype,"message"=>"Invalid Promocode"); }
    echo json_encode($data,true);
}

if($action=="trackOrder"){
    $shipmentId=$_POST['shipmentId'];
    $trackdata=array();
    if($shipmentId!=""){
     require_once "../classes/shiprocket.php";
     $username = SHIPUSER; $pasword=SHIPPWD;
     $ship = new shiprocket($username,$pasword);
     $getdata=selectQuery(ORDERSUB." as o  inner JOIN ".ORDER." as od on o.order_id= od.id","od.order_date,o.shipped_on,o.shipped_on,o.cancelation_date,o.refund_date,o.delivered_on,o.return_request_date,o.return_action_date,o.return_action_comment,o.return_status, o.shipping_awb_no, o.shipping_order_id, o.shipping_by, o.selected_courier_name, o.tracking_id, o.tracking_link","o.shipping_shipment_id= '".$shipmentId."'");
    $row=$getdata[0];
     $placed=array("date"=>date("d M Y h:i a",strtotime($row['order_date'])),"activity"=> "Order Generated","location"=>"");  array_push($trackdata,$placed);
 
     if($row['cancelation_date']=="0000-00-00 00:00:00"&&$row['return_request_date']==""){ //if not canceled and not returened
       if($row['shipping_by']=="Shiprocket"){ // shiprocket
            $awbno=$row['shipping_awb_no'];
            $shipordid=$row['shipping_order_id']; 
            
            $token = $ship->authenticate();
            $res= $ship-> trackOrder($token,$shipmentId);
        
            $shipdata=$res['tracking_data']['shipment_track_activities'];
    
            if( is_array($shipdata) && count($shipdata)){
            for($i=count($shipdata)-1;$i>=0;$i--){
                $acvdate=$shipdata[$i]['date'];
                $date=date("d M Y h:i a",strtotime($acvdate));$activity=$shipdata[$i]['activity'];$location=$shipdata[$i]['location'];
                $shipact=array("date"=>$date,"activity"=> $activity,"location"=>$location);   array_push($trackdata,$shipact);
            }
            }
       }else{ //manual
        if($row['delivered_on']!="0000-00-00 00:00:00"){
            $data='<span class="d-block d-sm-inline-block mb-1 mb-sm-0"><span class="cc-font-weight-6">Courier By - </span><span class="mr-1">'.$row['selected_courier_name'].'</span></span><br><span class="d-block d-sm-inline-block mb-1 mb-sm-0"><span class="cc-font-weight-6">Tracking ID - </span><span class="mr-1">'.$row['tracking_id'].'</span></span><br><span class="d-block d-sm-inline-block mb-1 mb-sm-0"><span class="cc-font-weight-6">Tracking Link - </span><span class="mr-1"><a href="'.$row['tracking_link'].'" target="_blank">Tracking Link</a></span></span><br>';
            $placed=array("date"=>date("d M Y h:i a",strtotime($row['delivered_on'])),"activity"=> "Order Delivered","location"=>$data);   array_push($trackdata,$placed);
         }
       }  
        
     }else if($row['cancelation_date']!="0000-00-00 00:00:00"){
         if($row['cancelation_date']!="0000-00-00 00:00:00"){ $placed=array("date"=>date("d M Y h:i a",strtotime($row['cancelation_date'])),"activity"=> "Order Canceled","location"=>"");   array_push($trackdata,$placed); }
          if($getdata[0]['refund_date']!="0000-00-00 00:00:00"){ $placed=array("date"=>date("d M Y h:i a",strtotime($row['refund_date'])),"activity"=> "Refund Initiated Against Order","location"=>"");   array_push($trackdata,$placed); }
 
     }else if($row['return_request_date']!=""){
          if($row['return_request_date']!=""){ $placed=array("date"=>date("d M Y h:i a",strtotime($row['return_request_date'])),"activity"=> "Item Return Request Generated","location"=>"");   array_push($trackdata,$placed); }
           if($row['return_action_date']!=""){ $placed=array("date"=>date("d M Y h:i a",strtotime($row['return_action_date'])),"activity"=> $row['return_status'],"location"=>"");   array_push($trackdata,$placed); }
          if($row['refund_date']!="0000-00-00 00:00:00"){ $placed=array("date"=>date("d M Y h:i a",strtotime($row['refund_date'])),"activity"=> "Refund Initiated Against Order","location"=>"");   array_push($trackdata,$placed); }
 
     }
    }
     $final=array_reverse($trackdata);
     echo json_encode($final);
 }
if($action=="trackReturnOrder"){
    $shipmentId = $_POST['shipmentId'];
    $trackdata = array();
    require_once "../classes/shiprocket.php";
    $username = SHIPUSER; $pasword=SHIPPWD;
    $ship = new shiprocket($username,$pasword);
    $getdata = selectQuery(ORDERSUB." as o  inner JOIN ".ORDER." as od on o.order_id= od.id","od.order_date,o.shipped_on,o.cancelation_date,o.refund_date,o.delivered_on,o.return_request_date,o.return_action_date,o.return_action_comment,o.return_status,o.return_reason","o.return_shipment_id= '".$shipmentId."'");
    $placed=array("date"=>date("d M Y h:i a",strtotime($getdata[0]['order_date'])),"activity"=> "Order Generated","location"=>"");
    array_push($trackdata,$placed);
    $placed = array("date"=>date("d M Y h:i a",strtotime($getdata[0]['delivered_on'])),"activity"=> "Order Delivered To User","location"=>"");
    array_push($trackdata,$placed);
    if($getdata[0]['return_request_date']!=""){ $placed=array("date"=>date("d M Y h:i a",strtotime($getdata[0]['return_request_date'])),"activity"=> "Item Return Requested By User - ".$getdata[0]['return_reason'],"location"=>"");  array_push($trackdata,$placed); }
    if($getdata[0]['return_action_date']!=""){ $placed = array("date"=>date("d M Y h:i a",strtotime($getdata[0]['return_action_date'])),"activity" => $getdata[0]['return_status'],"location"=>"");  array_push($trackdata,$placed); }
    $token = $ship->authenticate();
    $res = $ship-> trackOrder($token,$shipmentId);
    $shipdata=$res['tracking_data']['shipment_track_activities'];
    if(count($shipdata)){
        for($i=count($shipdata)-1;$i>=0;$i--){
            $date = date("d M Y h:i a",strtotime($shipdata[$i]['date']));$activity=$shipdata[$i]['activity'];$location=$shipdata[$i]['location'];
            $shipact=array("date"=>$date,"activity"=> $activity,"location"=>$location); array_push($trackdata,$shipact);
        }
    }
    if($getdata[0]['refund_date']!="0000-00-00 00:00:00"){ $placed=array("date"=>date("d M Y h:i a",strtotime($getdata[0]['refund_date'])),"activity"=> "Refund Initiated Against Order","location"=>"");   array_push($trackdata,$placed); }
    $final = array_reverse($trackdata);
    echo json_encode($final);
}


if($action=="cencelOrder"){
    $shippingOrderId = $_POST['shippingOrderId'];
    $txnId = $_POST['txnId'];
    $itemId = $_POST['itemId'];

    require_once "../classes/shiprocket.php";
    $username = SHIPUSER; $pasword=SHIPPWD;
    $ship = new shiprocket($username,$pasword);
    $token = $ship->authenticate();
    if($shippingOrderId!=""){
       $data0 = array("ids"=>array($shippingOrderId));
        $res = $ship-> cancelOrder($token,$data0);
    }else{
        $res=array("status"=>"success");
    }


     if(count($res)){
        if($shippingOrderId!=""){
           $data = $ship-> showOrderDetails($token,$shippingOrderId);
             $status = ucfirst(strtolower($data['data']['status']));
        }else{
             $status = "Canceled";
        }

        $data1 = array("order_current_Status"=>$status);
        updateQuery(ORDERSUB,$data1,"item_id=".$itemId);
         if($status=="Canceled"||$status=="Cancellation Request"||$status=="Cancellation Requested"){
            $getorderData = selectQuery(ORDER,"is_cod,payment_mode,user_id","transaction_id='".$txnId."'");
            $getorderSubData = selectQuery(ORDERSUB,"product_id,product_image,product_name,quantity,total_payable,refundable_amount,vendor_nickname,isFreeShipping,shipping_charges,vendor_email,total_with_gst","item_id=".$itemId);


             $getCOnfig = selectQuery(CONFIG,"cancelation_charge_percentage,cut_shipping_charges_on_cancelation");
            $getbuyer_details = selectQuery(BUYER,"u_id,u_fname,u_lname,u_email,u_mobile","u_id=".$getorderData[0]['user_id']);
            $is_cod = $getorderData[0]['is_cod']; $payment_mode = $getorderData[0]['payment_mode']; $total_payable = $getorderSubData[0]['total_payable'];
            $prodamount_with_gst = $getorderSubData[0]['total_with_gst'];
             $shipping_charges=($getorderSubData[0]['isFreeShipping']==1?0:$getorderSubData[0]['shipping_charges']);
            $cutshipping= $getCOnfig[0]['cut_shipping_charges_on_cancelation'];
             if($canceled_by == "Buyer"){ 
                $cancelation_percent=$getCOnfig[0]['cancelation_charge_percentage'];
                if($cutshipping==0){
                   $cancelation = round(($prodamount_with_gst/100)*$cancelation_percent);
                   $refundable_amount = $total_payable-$cancelation;
                 }
               else{
                 $cancelation =  round((($prodamount_with_gst/100)*$cancelation_percent)  + $shipping_charges);
                $refundable_amount = $total_payable-$cancelation;
                 }
            } 
            else{
             $refundable_amount = $total_payable; 
            }

            $datasub = array("cancelation_date"=>date("Y-m-d H:i:s"),"is_refund_appilicable"=>($status=="Canceled"?1:0),"refundable_amount"=>($payment_mode!="COD"?$refundable_amount:0),"refund_status"=>($payment_mode=="COD"?1:0),"cancel_reason" => $cancel_reason,'cancelled_by'=>$canceled_by);
            if($payment_mode=="COD"){ $datasub['refund_date']=date("Y-m-d H:i:s"); }
            updateQuery(ORDERSUB,$datasub,"item_id=".$itemId);

            $getorderSubData=selectQuery(ORDERSUB,"cancelled_by,cancel_reason,product_id,product_image,product_name,quantity,total_payable,refundable_amount,vendor_nickname,vendor_email,vendor_contact ","item_id=".$itemId);
            //update sold of product
            $getProd = selectQuery(PRODINFO,"sold","id=".$getorderSubData[0]['product_id']);
            $updatedSold = $getProd[0]['sold']-$getorderSubData[0]['quantity'];
            $soldarr = array("sold"=>$updatedSold);
            updateQuery(PRODINFO,$soldarr,"id=".$getorderSubData[0]['product_id']);
            //end of update sold
            //cancelation mail to user
            if($getorderSubData[0]['product_image']){
                $img = SITEURL."/img/order_img/".$getorderSubData[0]['product_image'];
            }else{ $img = SITEURL."/img/projectimage/product-default.png"; }
            $replacement_array = array('siteurl' => SITEURL, 'sitename' => SITENAME, 'smssitename' => SMSSITENAME, "Product_image" =>$img, "product_name"=>$getorderSubData[0]['product_name'], "quantity" => $getorderSubData[0]['quantity'], "Total_amount" => $getorderSubData[0]['total_payable'], "refundable_amount" => ($payment_mode=="COD"?0:$getorderSubData[0]['refundable_amount']), 'username' => $getbuyer_details[0]['u_fname']." ".$getbuyer_details[0]['u_lname'], 'vendor_name' => $getorderSubData[0]['vendor_nickname'], 'cancellationgenerator' =>  $getorderSubData[0]['cancelled_by'], 'cancellationreason' =>  $getorderSubData[0]['cancel_reason'] );
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers .= "From:".SITENAME."<".EMAIL_SENDER.">";
            $user_email =  selectQuery(EMAILTEMPLATE,"subject,body","type='Order Cancellation' and  mail_to= 'Buyer' ");
            $subject_user = convertemailstr($replacement_array,$user_email[0]['subject']);
            $body_user = convertemailstr($replacement_array,$user_email[0]['body']);
            $sentmail_user = mail($getbuyer_details[0]['u_email'], $subject_user, $body_user, $headers);
            $admin_email = selectQuery(EMAILTEMPLATE,"subject,body","type='Order Cancellation' and  mail_to= 'Admin' ");
            $subject_admin = convertemailstr($replacement_array,$admin_email[0]['subject']);
            $body_admin = convertemailstr($replacement_array,$admin_email[0]['body']);
            $sentmail_admin = mail(EMAIL_ORDER, $subject_admin, $body_admin, $headers);
            $vendor_email = selectQuery(EMAILTEMPLATE,"subject,body","type='Order Cancellation' and  mail_to= 'Vendor' ");
            $subject_vendor = convertemailstr($replacement_array,$vendor_email[0]['subject']);
            $body_vendor = convertemailstr($replacement_array,$vendor_email[0]['body']);
            $sentmail_vendor = mail($getorderSubData[0]['vendor_email'], $subject_vendor, $body_vendor, $headers);
            if(SMS_SYSTEM=="ON"){
                $buyer_sms = selectQuery(SMSTEMPLATE,"sms_text,templateId","type='Order Cancellation' and  sms_to = 'Buyer' ");
                $admin_sms = selectQuery(SMSTEMPLATE,"sms_text,templateId","type='Order Cancellation' and  sms_to = 'Admin' ");
                $vendor_sms = selectQuery(SMSTEMPLATE,"sms_text,templateId","type='Order Cancellation' and  sms_to = 'Vendor' ");
                $msg = convertsmsstr($replacement_array,$buyer_sms[0]['sms_text'] );
                $templateId= $buyer_sms[0]['templateId'];
                $sms = sendsms($getbuyer_details[0]['u_mobile'],$msg,WORKINGKEY,SMS_SENDER,$templateId);
                $id = (unserialize($sms));
                $msid = $id['data']['0']['id'];
                $mstatus = $id['data']['0']['status'];
                $datasms = array( "msg_id"=>$msid, "msg_type"=>"Order Cancellation SMS To Buyer", "user_id"=> $getbuyer_details[0]['u_id'], "user_name"=> $getbuyer_details[0]['u_fname']." ".$getbuyer_details[0]['u_lname'], "mobile_no"=>$getbuyer_details[0]['u_mobile'], "message"=>$msg, "date"=>date("Y-m-d H:i:s"), "status"=>$mstatus );
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
                    $datasms1 = array("msg_id"=>$msid1, "msg_type"=>"Order Cancellation SMS To Admin", "user_name"=>"Admin", "mobile_no"=>$tempmob, "message"=>$msg, "date"=>date("Y-m-d H:i:s"), "status"=>$status1,);
                    $insert1 = insertQuery(SMS,$datasms1);
                }
                $msg = convertsmsstr($replacement_array,$vendor_sms[0]['sms_text'] );
                $templateId = $vendor_sms[0]['templateId'];
                $sms = sendsms($getorderSubData[0]['vendor_contact'],$msg,WORKINGKEY,SMS_SENDER,$templateId);
                $id = (unserialize($sms));
                $msid = $id['data']['0']['id'];
                $mstatus = $id['data']['0']['status'];
                $datasms = array( "msg_id"=>$msid, "msg_type"=>"Order Cancellation SMS To Vendor", "user_name"=> $getorderSubData[0]['vendor_nickname'], "mobile_no"=>$getorderSubData[0]['vendor_contact'], "message"=>$msg, "date"=>date("Y-m-d H:i:s"), "status"=>$mstatus);
                $store = insertQuery(SMS,$datasms); 
            }   
        }
        $retdata = array("status"=>$status);
    }else{ $retdata = array("status"=>"failed","message"=>"Please Try Again Later"); }
    echo json_encode($retdata);
} 
if($action=="returnOrder"){
    $itemid = $_POST['itemId'];  $return_reason=$_POST['return_reason'];
    $getdata = selectQuery(ORDERSUB." as o LEFT JOIN ".ORDER." as od on o.order_id= od.id LEFT JOIN ".PRODINFO." as p on o.product_id=p.id LEFT JOIN ".BUYER." as u on od.user_id=u.u_id","*","o.item_id=".$itemid);
    $getpurch = selectQuery(PURCH,"purchase_order_id","reference_order_sub_id =".$itemid);
    $shipping_by = $getdata[0]['shipping_by'];$custname = $getdata[0]['shippingAddr_name'];  $withoutgst=$getdata[0]['user_per_unit_withoutgst_price']; $withoutgst = $getdata[0]['user_per_unit_withoutgst_price']; $disc=$getdata[0]['discount_amount'];
    require_once "../classes/shiprocket.php";
    $username = SHIPUSER; $pasword=SHIPPWD;
    $ship = new shiprocket($username,$pasword);
    $token = $ship->authenticate();
    if($shipping_by!=SITENAME){
        $shippingOrderId =$getdata[0]['shipping_order_id'];
        $odata=$ship-> showOrderDetails($token,$shippingOrderId);
        $channel=$odata['data']['channel_id'];
        $pickups = $ship->getPickups($token);
        $vendor = "v".$getdata[0]['vendor'];
        $pickupid = array_search($vendor, array_column($pickups, 'pickup_location'));
        $pickupdetails = $pickups[$pickupid];
        $custname = explode(" ",$getdata[0]['shippingAddr_name']);
        $data = array("order_id"=> "R_".$getpurch[0]['purchase_order_id'], "order_date"=> date("Y-m-d H:i:s"), "channel_id"=> $channel, "pickup_customer_name"=> $custname[0], "pickup_last_name"=>"", "pickup_address"=> $getdata[0]['shippingAddr_address'], "pickup_address_2"=>$getdata[0]['shippingAddr_location'], "pickup_city"=> $getdata[0]['shippingAddr_city'], "pickup_state"=> $getdata[0]['shippingAddr_state'], "pickup_country"=> $getdata[0]['shippingAddr_country'], "pickup_pincode"=>$getdata[0]['shippingAddr_pincode'], "pickup_email"=> $getdata[0]['u_email'], "pickup_phone"=> $getdata[0]['u_mobile'], "pickup_isd_code"=> "+91", "pickup_location_id"=> $pickupdetails['id'], "shipping_customer_name"=>$pickupdetails['name'], "shipping_last_name"=>"", "shipping_address"=>$pickupdetails['address'], "shipping_address_2"=>$pickupdetails['address_2'], "shipping_city"=>$pickupdetails['city'], "shipping_country"=>$pickupdetails['country'], "shipping_pincode"=>$pickupdetails['pin_code'], "shipping_state"=>$pickupdetails['state'], "shipping_email"=>$pickupdetails['email'], "shipping_isd_code"=> "", "shipping_phone"=>$pickupdetails['phone'], "order_items"=>array(array("sku"=>$getdata[0]['sku'], "name"=> $getdata[0]['product_name'], "units"=> $getdata[0]['quantity'], "selling_price"=>$getdata[0]['user_per_unit_withoutgst_price']+round((($getdata[0]['user_per_unit_withoutgst_price']/100)*$getdata[0]['tax_percentage'])), "discount"=> $getdata[0]['discount_amount'], "hsn"=> "")), "payment_method"=>($getdata[0]['payment_mode']=="COD"?"cod":"prepaid"), "total_discount"=>$getdata[0]['discount_amount'], "sub_total"=> $getdata[0]['total_payable'], "length"=>trim(($getdata[0]['length']==0.00?0.5:$getdata[0]['length']))*$getdata[0]['quantity'], "breadth"=>trim(($getdata[0]['width']==0.00?0.5:$getdata[0]['width']))*$getdata[0]['quantity'], "height"=> trim(($getdata[0]['height']==0.00?0.5:$getdata[0]['height']))*$getdata[0]['quantity'], "weight"=> trim(($getdata[0]['weight']==0.000?0.5:$getdata[0]['weight']))*$getdata[0]['quantity']);
        //echo "<pre>"; print_r($data); echo "</pre>";
        $shiporder = $ship->returnOrder($token,$data);
        $shiporderid = $shiporder['order_id']; $shipmentid= $shiporder['shipment_id']; $status= $status=ucfirst(strtolower($data['data']['status']));
    }else{ $shiporderid==""; $shipmentid="";$status="Return Requested";}
   if($shiporderid!=""||$shipping_by==SITENAME){
        /*$jsondata = array("shipment_id"=>$shipmentid,"courier_id"=>"" ,"status"=>"");
        $arwdata = $ship->createAWBNo($token,$jsondata);
        $awb_status = $arwdata['awb_assign_status'];
        $awbno = $arwdata['response']['data']['awb_code'];
        $courier_id = $arwdata['response']['data']['courier_company_id'];
        $courier_name = $arwdata['response']['data']['courier_name'];
        $pickupdata = array("shipment_id"=>array($shipmentid) ,"status"=>"");
        $pickups = $ship->requestForPickup($token,$pickupdata);
        $pickup_status = $pickups['pickup_status'];
        $pickupno = $pickups['pickup_token_number'];
        $pickupdate = $pickups['response']['pickup_scheduled_date']['date']; */
        // $data1 = array("order_current_Status"=>"Return Requested","is_returned"=>1,"return_reason"=>$return_reason,"return_order_id"=>$shiporderid,"return_shipment_id"=>$shipmentid,"return_awb_no"=>$awbno,"return_pickup_date"=>date("Y-m-d H:i:s",strtotime($pickupdate)),"return_status"=>$status);
        $data1 = array("order_current_Status"=>"Return Requested","is_returned"=>1,"return_request_date"=>date("Y-m-d H:i:s"),"return_reason"=>$return_reason,"return_order_id"=>$shiporderid,"return_shipment_id"=>$shipmentid,"return_status"=>$status);
        updateQuery(ORDERSUB,$data1,"item_id=".$itemid);
       /*---------------------------- return emails here ------------------------------------- */
        $replacement_array = array('siteurl' => SITEURL, 'sitename' => SITENAME, 'smssitename' => SMSSITENAME, "product_name"=>$getdata[0]['product_name'], "order_id" => $getdata[0]['order_id'], 'username' => $getdata[0]['u_fname']." ".$getdata[0]['u_lname'], 'vendor_name' => $getdata[0]['vendor_nickname'],);
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= "From:".SITENAME."<".EMAIL_SENDER.">";
        $user_email = selectQuery(EMAILTEMPLATE,"subject,body","type='Return Request' and  mail_to= 'Buyer' ");
        $subject_user = convertemailstr($replacement_array,$user_email[0]['subject']);
        $body_user = convertemailstr($replacement_array,$user_email[0]['body']);
        $sentmail_user = mail($getdata[0]['u_email'], $subject_user, $body_user, $headers);
        $admin_email = selectQuery(EMAILTEMPLATE,"subject,body","type='Return Request' and  mail_to= 'Admin' ");
        $subject_admin = convertemailstr($replacement_array,$admin_email[0]['subject']);
        $body_admin = convertemailstr($replacement_array,$admin_email[0]['body']);
        $sentmail_admin = mail(EMAIL_ORDER, $subject_admin, $body_admin, $headers);
        $vendor_email = selectQuery(EMAILTEMPLATE,"subject,body","type='Return Request' and  mail_to= 'Vendor' ");
        $subject_vendor = convertemailstr($replacement_array,$vendor_email[0]['subject']);
        $body_vendor = convertemailstr($replacement_array,$vendor_email[0]['body']);
        $sentmail_vendor = mail($getdata[0]['vendor_email'], $subject_vendor, $body_vendor, $headers);
        if(SMS_SYSTEM=="ON"){
            $buyer_sms = selectQuery(SMSTEMPLATE,"sms_text,templateId","type='Return Request' and  sms_to = 'Buyer' ");
            $admin_sms = selectQuery(SMSTEMPLATE,"sms_text,templateId","type='Return Request' and  sms_to = 'Admin' ");
            $vendor_sms = selectQuery(SMSTEMPLATE,"sms_text,templateId","type='Return Request' and  sms_to = 'Vendor' ");
            $msg = convertsmsstr($replacement_array,$buyer_sms[0]['sms_text'] );
            $templateId= $buyer_sms[0]['templateId'];
            $sms = sendsms($getdata[0]['u_mobile'],$msg,WORKINGKEY,SMS_SENDER,$templateId);
            $id = (unserialize($sms));
            $msid = $id['data']['0']['id'];
            $status = $id['data']['0']['status'];
            $datasms = array("msg_id"=>$msid, "msg_type"=>"Order Return Request SMS to Buyer", "user_id"=> $getdata[0]['u_id'], "user_name"=> $getdata[0]['u_fname']." ".$getdata[0]['u_lname'], "mobile_no"=>$getdata[0]['u_mobile'], "message"=>$msg, "date"=>date("Y-m-d H:i:s"), "status"=>$status );
            $store = insertQuery(SMS,$datasms);
            $arr = explode(",",ADMINCONTACT);
            for($k=0;$k<sizeOf($arr);$k++){
                $tempmob = $arr[$k];
                $msg = convertsmsstr($replacement_array,$admin_sms[0]['sms_text'] );
                $templateId= $admin_sms[0]['templateId'];
                $sms1 = sendsms($tempmob,$msg,WORKINGKEY,SMS_SENDER,$templateId);
                $id1 = (unserialize($sms1));
                $msid1 = $id1['data']['0']['id'];
                $status1 = $id1['data']['0']['status'];
                $datasms1 = array("msg_id"=>$msid1,"msg_type"=>"Order Return Request SMS To Admin","user_name"=>"Admin","mobile_no"=>$tempmob,"message"=>$msg,"date"=>date("Y-m-d H:i:s"),"status"=>$status1,);
                $insert1 = insertQuery(SMS,$datasms1);
            }
            $msg = convertsmsstr($replacement_array,$vendor_sms[0]['sms_text'] );
            $templateId= $vendor_sms[0]['templateId'];
            $sms = sendsms($getdata[0]['vendor_contact'],$msg,WORKINGKEY,SMS_SENDER,$templateId);
            $id = (unserialize($sms));
            $msid = $id['data']['0']['id'];
            $status = $id['data']['0']['status'];
            $datasms = array("msg_id"=>$msid, "msg_type"=>"Order Return Request SMS To Vendor", "user_name"=> $getdata[0]['vendor_nickname'], "mobile_no"=>$getdata[0]['vendor_contact'], "message"=>$msg, "date"=>date("Y-m-d H:i:s"), "status"=>$status);
            $store = insertQuery(SMS,$datasms);
        }
        $datares=array("status"=>"success","message"=>"Return Request Initiated");
    }
    else{
        $datares = array("status"=>"failed","message"=>"Error In Returning . Please Try Again Later");
    }
    echo json_encode($datares);
}
//ACTION ON RETURN REQUEST BY ADMIN
if($action=="action_on_return"){
    require_once "../classes/shiprocket.php";
    $username = SHIPUSER; $pasword=SHIPPWD;
    $ship = new shiprocket($username,$pasword);
    $token = $ship->authenticate();
    $itemid = $_POST['itemid'];
    $getdata = selectQuery(ORDERSUB." as o LEFT JOIN ".ORDER." as od on o.order_id= od.id LEFT JOIN ".PRODINFO." as p on o.product_id=p.id LEFT JOIN ".BUYER." as u on od.user_id=u.u_id","*","o.item_id=".$itemid);
    $shipping_by=$getdata[0]['shipping_by'];
    $retorderStatus = selectQuery(ORDERSUB,"return_order_id,return_shipment_id","item_id=".$itemid);
    $replacement_array = array('siteurl' => SITEURL, 'sitename' => SITENAME, 'smssitename' => SMSSITENAME, "product_name"=>$getdata[0]['product_name'], "order_id" => $getdata[0]['order_id'], 'username' => $getdata[0]['u_fname']." ".$getdata[0]['u_lname'], 'vendor_name' => $getdata[0]['vendor_nickname'],);
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers .= "From:".SITENAME."<".EMAIL_SENDER.">";
    $returnOrderId = $retorderStatus[0]['return_order_id'];$shipmentid=$retorderStatus[0]['return_shipment_id'];$actionTaken=$_POST['actionTaken'];
    if($actionTaken=="Initiate"){
       /* if($shipping_by!=SITENEME&&$shipmentid!=""){ */
       if($shipping_by!=SITENEME){
           $jsondata = array("shipment_id"=>$shipmentid,"courier_id"=>"" ,"status"=>"");
            $arwdata = $ship->createAWBNo($token,$jsondata);
            $awb_status = $arwdata['awb_assign_status'];
            $awbno = $arwdata['response']['data']['awb_code'];
            $courier_id = $arwdata['response']['data']['courier_company_id'];
            $courier_name = $arwdata['response']['data']['courier_name'];
       }else{
            $awb_status = "";  $awbno ="";
            $courier_id = "";   $courier_name = "";
       }

        if($awbno!=""||$shipping_by==SITENAME){
           if($shipping_by!=SITENAME){
            $pickupdata = array("shipment_id"=>array($shipmentid) ,"status"=>"");
            $pickups = $ship->requestForPickup($token,$pickupdata);
            $pickup_status = $pickups['pickup_status'];
            $pickupno = $pickups['pickup_token_number'];
            $pickupdate = $pickups['response']['pickup_scheduled_date']['date'];
            }else{
                $pickup_status = "";
                $pickupno = "";
                $pickupdate = "";
            }
            if($pickupdate!=""||$shipping_by==SITENAME){
                $user_email = selectQuery(EMAILTEMPLATE,"subject,body","type='Return Initiated' and  mail_to= 'Buyer' ");
                $subject_user = convertemailstr($replacement_array,$user_email[0]['subject']);
                $body_user = convertemailstr($replacement_array,$user_email[0]['body']);
                $sentmail_user = mail($getdata[0]['u_email'], $subject_user, $body_user, $headers);
                $admin_email = selectQuery(EMAILTEMPLATE,"subject,body","type='Return Initiated' and  mail_to= 'Admin' ");
                $subject_admin = convertemailstr($replacement_array,$admin_email[0]['subject']);
                $body_admin = convertemailstr($replacement_array,$admin_email[0]['body']);
                $sentmail_admin = mail(EMAIL_ORDER, $subject_admin, $body_admin, $headers);
                $vendor_email = selectQuery(EMAILTEMPLATE,"subject,body","type='Return Initiated' and  mail_to= 'Vendor' ");
                $subject_vendor = convertemailstr($replacement_array,$vendor_email[0]['subject']);
                $body_vendor = convertemailstr($replacement_array,$vendor_email[0]['body']);
                $sentmail_vendor = mail($getdata[0]['vendor_email'], $subject_vendor, $body_vendor, $headers);
                if(SMS_SYSTEM=="ON"){
                    $buyer_sms = selectQuery(SMSTEMPLATE,"sms_text,templateId","type='Return Initiated' and  sms_to = 'Buyer' ");
                    $admin_sms = selectQuery(SMSTEMPLATE,"sms_text,templateId","type='Return Initiated' and  sms_to = 'Admin' ");
                    $vendor_sms = selectQuery(SMSTEMPLATE,"sms_text,templateId","type='Return Initiated' and  sms_to = 'Vendor' ");
                    $msg = convertsmsstr($replacement_array,$buyer_sms[0]['sms_text'] );
                    $templateId= $buyer_sms[0]['templateId'];
                    $sms = sendsms($getdata[0]['u_mobile'],$msg,WORKINGKEY,SMS_SENDER,$templateId);
                    $id = (unserialize($sms));
                    $msid = $id['data']['0']['id'];
                    $status = $id['data']['0']['status'];
                    $datasms = array("msg_id"=>$msid, "msg_type"=>"Order Return Initiated SMS To Buyer", "user_id"=> $getdata[0]['u_id'], "user_name"=> $getdata[0]['u_fname']." ".$getdata[0]['u_lname'], "mobile_no"=>$getdata[0]['u_mobile'], "message"=>$msg, "date"=>date("Y-m-d H:i:s"), "status"=>$status );
                    $store = insertQuery(SMS,$datasms);
                    $arr = explode(",",ADMINCONTACT);
                    for($k=0;$k<sizeOf($arr);$k++){
                        $tempmob = $arr[$k];
                        $msg = convertsmsstr($replacement_array,$admin_sms[0]['sms_text'] );
                         $templateId= $admin_sms[0]['templateId'];
                        $sms1 = sendsms($tempmob,$msg,WORKINGKEY,SMS_SENDER,$templateId);
                        $id1 = (unserialize($sms1));
                        $msid1 = $id1['data']['0']['id'];
                        $status1 = $id1['data']['0']['status'];
                        $datasms1 = array("msg_id"=>$msid1, "msg_type"=>"Order Return Initiated SMS To Admin", "user_name"=>"Admin", "mobile_no"=>$tempmob, "message"=>$msg, "date"=>date("Y-m-d H:i:s"), "status"=>$status1,);
                        $insert1 = insertQuery(SMS,$datasms1);
                    }
                    $msg = convertsmsstr($replacement_array,$vendor_sms[0]['sms_text'] );
                    $templateId= $vendor_sms[0]['templateId'];
                    $sms = sendsms($getdata[0]['vendor_contact'],$msg,WORKINGKEY,SMS_SENDER,$templateId);
                    $id = (unserialize($sms));
                    $msid = $id['data']['0']['id'];
                    $status = $id['data']['0']['status'];
                    $datasms = array("msg_id"=>$msid, "msg_type"=>"Order Return Initiated SMS To Vendor", "user_name"=> $getdata[0]['vendor_nickname'], "mobile_no"=>$getdata[0]['vendor_contact'], "message"=>$msg, "date"=>date("Y-m-d H:i:s"), "status"=>$status);
                    $store = insertQuery(SMS,$datasms);
                } 
                $updatedata = array("order_current_Status"=>"Return Initiated","return_awb_no"=>$awbno,"return_pickup_date"=>($pickupdate?date("Y-m-d H:i:s",strtotime($pickupdate)):""),"return_status"=>"Return Initiated","return_action_date"=>date("Y-m-d H:i:s"));
                updateQuery(ORDERSUB,$updatedata,"item_id=".$itemid); 
                echo 1;
            } else{ echo $pickups['message']; }
        }else{ echo $arwdata['message']; }
     /*}else{
      //manual shipping
     }*/
    } else if($actionTaken=="Cancel"){
         if($shipping_by!=SITENAME){
            $data0 = array("ids"=>array($returnOrderId));
            $res = $ship-> cancelOrder($token,$data0);
        }else{$res=array();  }
        if(count($res)||$shipping_by==SITENAME){
            if($shipping_by!=SITENAME){
                $data = $ship-> showOrderDetails($token,$returnOrderId);
                $status = ucfirst(strtolower($data['data']['status']));
            }else{
              $status="Return Rejected";
            }
            $updatedata = array("order_current_Status"=>"Return Rejected","is_returned"=>0,"return_status"=>$status,"return_action_date"=>date("Y-m-d H:i:s"));
            updateQuery(ORDERSUB,$updatedata,"item_id=".$itemid);
            $user_email = selectQuery(EMAILTEMPLATE,"subject,body","type='Return Cancel' and  mail_to= 'Buyer' ");
            $subject_user = convertemailstr($replacement_array,$user_email[0]['subject']);
            $body_user = convertemailstr($replacement_array,$user_email[0]['body']);
            $sentmail_user = mail($getdata[0]['u_email'], $subject_user, $body_user, $headers);
            $admin_email = selectQuery(EMAILTEMPLATE,"subject,body","type='Return Cancel' and  mail_to= 'Admin' ");
            $subject_admin = convertemailstr($replacement_array,$admin_email[0]['subject']);
            $body_admin = convertemailstr($replacement_array,$admin_email[0]['body']);
            $sentmail_admin = mail(EMAIL_ORDER, $subject_admin, $body_admin, $headers);
            $vendor_email = selectQuery(EMAILTEMPLATE,"subject,body","type='Return Cancel' and  mail_to= 'Vendor' ");
            $subject_vendor = convertemailstr($replacement_array,$vendor_email[0]['subject']);
            $body_vendor = convertemailstr($replacement_array,$vendor_email[0]['body']);
            $sentmail_vendor = mail($getdata[0]['vendor_email'], $subject_vendor, $body_vendor, $headers);
            if(SMS_SYSTEM=="ON"){
                $buyer_sms = selectQuery(SMSTEMPLATE,"sms_text,templateId","type='Return Cancel' and  sms_to = 'Buyer' ");
                $admin_sms = selectQuery(SMSTEMPLATE,"sms_text,templateId","type='Return Cancel' and  sms_to = 'Admin' ");
                $vendor_sms = selectQuery(SMSTEMPLATE,"sms_text,templateId","type='Return Cancel' and  sms_to = 'Vendor' ");
                $msg = convertsmsstr($replacement_array,$buyer_sms[0]['sms_text'] );
                $templateId= $buyer_sms[0]['templateId'];
                $sms = sendsms($getdata[0]['u_mobile'],$msg,WORKINGKEY,SMS_SENDER,$templateId);
                $id = (unserialize($sms));
                $msid = $id['data']['0']['id'];
                $status = $id['data']['0']['status'];
                $datasms = array("msg_id"=>$msid, "msg_type"=>"Order Return Rejected SMS To Buyer", "user_id"=> $getdata[0]['u_id'], "user_name" => $getdata[0]['u_fname']." ".$getdata[0]['u_lname'], "mobile_no" => $getdata[0]['u_mobile'], "message" => $msg, "date"=>date("Y-m-d H:i:s"), "status" => $status);
                $store = insertQuery(SMS,$datasms);
                $arr = explode(",",ADMINCONTACT);
                for($k=0;$k<sizeOf($arr);$k++){
                    $tempmob = $arr[$k];
                    $msg = convertsmsstr($replacement_array,$admin_sms[0]['sms_text'] );
                    $templateId= $admin_sms[0]['templateId'];
                    $sms1 = sendsms($tempmob,$msg,WORKINGKEY,SMS_SENDER,$templateId);
                    $id1 = (unserialize($sms1));
                    $msid1 = $id1['data']['0']['id'];
                    $status1 = $id1['data']['0']['status'];
                    $datasms1 = array("msg_id" => $msid1, "msg_type" => "Order Return Rejected SMS To Admin ", "user_name" => "Admin", "mobile_no" => $tempmob, "message" => $msg, "date" => date("Y-m-d H:i:s"), "status" => $status1,);
                    $insert1 = insertQuery(SMS,$datasms1);
                }
                $msg = convertsmsstr($replacement_array,$vendor_sms[0]['sms_text'] );
                $templateId= $vendor_sms[0]['templateId'];
                $sms = sendsms($getdata[0]['vendor_contact'],$msg,WORKINGKEY,SMS_SENDER,$templateId);
                $id=(unserialize($sms));
                $msid=$id['data']['0']['id'];
                $status=$id['data']['0']['status'];
                $datasms=array("msg_id"=>$msid, "msg_type"=>"Order Return Rejected SMS To Vendor", "user_name"=> $getdata[0]['vendor_nickname'], "mobile_no"=>$getdata[0]['vendor_contact'], "message"=>$msg, "date"=>date("Y-m-d H:i:s"), "status"=>$status);
                $store = insertQuery(SMS,$datasms);
            } 
            echo 1;
        } else{ echo "Error In Request Processing.. Please Try Again Later"; }
    }
}
if($action=="manualRefund"){
    if($pay_date != ""){ $pay_date = date("Y-m-d H:i:s", strtotime($pay_date)); } 
    else{ $pay_date = "0000-00-00 00:00:00";}
    $data = array("refund_status" => $payment_made , "refund_date" => date("Y-m-d H:i:s", strtotime($pay_date)), "refund_id" => $ref_id);
    $update = updateQuery(ORDERSUB,$data,"item_id=".$itemid);
    if($update){
        echo "1";   
        if($payment_made == 1){
            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers .= "From:".SITENAME."<".EMAIL_SENDER.">";
            $getrefundable = selectQuery(ORDERSUB." as sub JOIN ".ORDER." as ord on sub.order_id=ord.id JOIN ".BUYER." as usr on ord.user_id=usr.u_id","*","sub.item_id=".$itemid);
            if($getrefundable[0]['cancelation_date'] != "" && $getrefundable[0]['cancelation_date'] != "0000-00-00 00:00:00"){ $req_type = "cancellation"; } else if($getrefundable[0]['return_request_date'] != "" && $getrefundable[0]['return_request_date'] != "0000-00-00 00:00:00" ){ $req_type = "Return"; }
            $replacement_array = array('siteurl' => SITEURL, 'sitename' => SITENAME, 'smssitename' => SMSSITENAME,
            'username' => $getrefundable[0]['u_fname']." ".$getrefundable[0]['u_lname']."", 'request_type' => $req_type, "refund_amount" => $getrefundable[0]['refundable_amount'], "order_id" => $getrefundable[0]['order_id']);
            $user_email = selectQuery(EMAILTEMPLATE,"subject,body","type='Refund Mail' and  mail_to= 'Buyer' ");
            $subject_user = convertemailstr($replacement_array,$user_email[0]['subject']);
            $body_user =convertemailstr($replacement_array,$user_email[0]['body']);
            mail($getrefundable[0]['u_email'], $subject_user, $body_user, $headers);
            $admin_email = selectQuery(EMAILTEMPLATE,"subject,body","type='Refund Mail' and  mail_to= 'Admin' ");
            $subject_admin = convertemailstr($replacement_array,$admin_email[0]['subject']);
            $body_admin = convertemailstr($replacement_array,$admin_email[0]['body']);
            mail(EMAIL_ORDER, $subject_admin, $body_admin, $headers);  
        }
    } else{ echo "0"; }
}

if($action=="deliveredOrder"){
    $oitem=$_POST['item'];
    $ord=selectQuery(ORDER,"id","order_id='".$oitem."'");
    $getsub=selectQuery(ORDERSUB,"item_id","order_id=".$ord[0]['id']." AND order_current_Status='Waiting For Ship'");
    for($i=0;$i<count($getsub);$i++){
        $item=$getsub[$i]['item_id'];
        $orderStatus=selectQuery(ORDERSUB,"disbustment_days,return_days,total_payable","item_id=".$item);

        $disbustment_days=($orderStatus[0]['disbustment_days']!=""?$orderStatus[0]['disbustment_days']:VENDOR_DISBURSMENT_DAYS); $return_days=($orderStatus[0]['return_days']?$orderStatus[0]['return_days']:0);
        $dis_final=$return_days+ $disbustment_days;
        $data=array("order_current_Status"=>"Delivered","delivered_on"=>date("Y-m-d H:i:s"));
         if($return_days!=0){ $data['return_valid_till']=date('Y-m-d', strtotime(' +'.$return_days.' days'));}
         $data['disbustment_date']=date('Y-m-d', strtotime(' +'.$dis_final.' days'));
         updateQuery(ORDERSUB,$data,"item_id=".$item);
    }
   
    echo 1;
}

if($action=="returnDelivered"){
    $item=$_POST['itemid'];
    $retorderStatus=selectQuery(ORDERSUB,"total_payable,isFreeShipping,shipping_charges,order_current_Status","item_id=".$item);
    $total_payable=$retorderStatus[0]['total_payable'];
    $shipping_charges=($retorderStatus[0]['isFreeShipping']=='1'?0.00:$retorderStatus[0]['shipping_charges']);
    if($refundwship==0){  $refundable_amount=$total_payable-$shipping_charges;}
    else{ $refundable_amount=$total_payable;  }
    $updatedata=array();
    $updatedata['order_current_Status']="Return Delivered";$updatedata['is_refund_appilicable']="1";$updatedata['refundable_amount']=$refundable_amount;$updatedata['refund_status']="0";
     updateQuery(ORDERSUB,$updatedata,"item_id=".$item);
    echo 1;
}
?>