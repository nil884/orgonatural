<? include "../../../includes/configuration.php";
    $action=$_REQUEST['action'];
    $vendor=($getconfigdetails[0]['default_vendor_for_pos']==0?1:$getconfigdetails[0]['default_vendor_for_pos']);

if($action=="getqualityrates"){
    $qualityname=$_POST['qualityname'];
     if($qualityname!=""){  /* if quality name is not blank then process*/
        $getQCode=selectQuery(PRODINFO,"id,final_price,hsn_code,tax,stock,sold,blocked","prod_name ='".$qualityname."' and vendor=".$vendor);
        if(count($getQCode)){    /* if quality name is valid then process*/
         $qualitysaleRate=$getQCode[0]['final_price']; $tax=$getQCode[0]['tax'];
         $stk= $getQCode[0]['stock']-($getQCode[0]['sold']+$getQCode[0]['blocked']);
         $priceWithGst = $qualitysaleRate;
         $taxonadminprice=round(($qualitysaleRate*($tax/(100+$tax))));
         $priceWithoutGst = round($qualitysaleRate-$taxonadminprice);
         $qualitycode=$getQCode[0]['id'];  $hsncode=$getQCode[0]['hsn_code'];

         $data=array("rate"=>$priceWithoutGst,"hsn"=>$hsncode, "tax"=>$tax, "stock"=>$stk);

         echo json_encode($data);
        }
    }
}

if($action=="validateitem"){
      require_once "../../../classes/shiprocket.php";
     include_once("../../../classes/surunapi.php");
    $srn=new surun();
    $username = SHIPUSER; $pasword=SHIPPWD;

   $qualityname=$_POST['qualityname']; $pincode=$_POST['pincode'];
   $vSubQCode=$_POST['vSubQCode'];$vSubRate=$_POST['vSubRate'];$vsubid=$_POST['vsubid'];$vSubQty=$_POST['vSubQty'];$vSubTaxable=$_POST['vSubTaxable'];$vSubTax=$_POST['vSubTax'];$vSubcgst=$_POST['vSubcgst'];$vSubsgst=$_POST['vSubsgst'];$vSubigst=$_POST['vSubigst'];$vSubTotal=$_POST['vSubTotal'];

    if($qualityname!=""){  /* if quality name is not blank then process*/
        $getQCode=selectQuery(PRODINFO,"id","prod_name ='".$qualityname."'  and vendor=".$vendor);
        if(count($getQCode)){
              $adminpin = $getconfigdetails[0]['pincode'];
               $UserPin = array("postcode"=>$pincode);  $AdminPin = array("postcode"=>$adminpin);
           if($username!=""&&$pasword!=""){
                $ship = new shiprocket($username,$pasword);
                $token = $ship->authenticate();

                $resUPin = $ship->getPincodeData($token,$UserPin);
                 $res = $ship->getPincodeData($token,$AdminPin);
            }else{
               $resUPin=$srn->getPincodeData($UserPin);
                $res = $srn->getPincodeData($AdminPin);
            }
             /* user */
                $pinResUser= $resUPin['postcode_details'];   $UserState = $pinResUser['state'];
                  /* admin */
                $pinRes = $res['postcode_details']; $adminState=$pinRes['state'];

            if($UserState==$adminState){   $igst="false"; }else{ $igst="true";  }

            $getstock=selectQuery(PRODINFO,"(stock-sold-blocked) as stock","id =".$getQCode[0]['id']);

            $data=array("id"=>$getQCode[0]['id'],"stock"=>$getstock[0]['stock'],"igst"=>$igst,"vsubid"=>$vsubid,"vSubQCode"=>$vSubQCode,"vSubRate"=>$vSubRate,"vSubQty"=>$vSubQty,"vSubTaxable"=>$vSubTaxable,"vSubTax"=>$vSubTax,"vSubcgst"=>$vSubcgst,"vSubsgst"=>$vSubsgst,"vSubigst"=>$vSubigst,"vSubTotal"=>$vSubTotal);
            echo json_encode($data);
        }else{echo 0;}
    }else{
        echo 0;
    }
}

if($action=="addData"){

     require_once("../../../classes/user.php"); require_once("../../../classes/product.php");
     $imgtype = "product";
    include("../../../getimgpath.php");
        $usercl = new User(); $prod = new Product();
        $ord=selectQuery(ORDER,"MAX(ordno) as ordno","order_id<>''");
        $userOrderSeries=($getconfigdetails[0]['order_id']!=""?$getconfigdetails[0]['order_id']:"ORD");
        $orderstart=$userOrderSeries."-";
       
         if($ord[0]['ordno']==NULL){ $orid=1; $orderid=$orderstart.$orid; }
         else { 
            
             $orid=$ord[0]['ordno']+1;
             $orderid=$orderstart.$orid;
         }
         $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
    $dataorder = array(
        "orderType"=>"POS",
        "ordseq"=>$userOrderSeries,"ordno"=>$orid,
        "order_id"=> $orderid,
       // "transaction_id"=>($_POST['transactionId']?$_POST['transactionId']:$txnid),
       "transaction_id"=>$txnid,
        "order_date" => date("Y-m-d H:i:s"),
        "user_id" => 0,
        "is_cod" => ($_POST['paymentMode']=="COD"?'1':'0'),
        "payment_mode" => $_POST['paymentMode'],
        "isFreeShipping" => ($_POST['deliveryCharge']!=0?'0':'1'),
        "total_shipping_charges" => $_POST['deliveryCharge'],
         "total_bill_amount" => $_POST['billAmt'],
         "discount_type"=>($_POST['discount']!=0?"Discount":""),
         "discount_code"=>($_POST['discount']!=0?"POS":""),
         "discount_amount"=>$_POST['discount'],
         "payable_amount" => $_POST['payable'],
        "payment_status" => "success",
        "payment_id"=>$_POST['transactionId'],
        "shippingAddr_id" =>0,
        "shippingAddr_type" => "Home",
         "gst_no" => $_POST['gstno'],
        "shippingAddr_name" => $_POST['username'],
        "shippingAddr_mobile" => $_POST['phoneno'],
        "shippingAddr_address" =>$_POST['address'],
        "shippingAddr_pincode"=>$_POST['pincode']
      );
        $ordid=insertQuery(ORDER,$dataorder);
        $taxable=0; $gst=0;
         for($i=0;$i<$_POST['vsubrowcnt'];$i++){
            $ctrcnt=$i+1;
            $subordid=$orderid."-".$ctrcnt;

         if($i==0){
              $prodid=$_POST['vsubid'];   $vSubRate=$_POST['vSubRate'];  $vSubQty=$_POST['vSubQty'];  $vSubTaxable=$_POST['vSubTaxable']; $vSubcgst=$_POST['vSubcgst']; $vSubsgst=$_POST['vSubsgst'];$vSubigst=$_POST['vSubigst'];   $vSubAmount=$_POST['vSubTotal'];
           }else{ $j=$i+1;
             $prodid=$_POST['vsubid'.$j];  $vSubRate=$_POST['vSubRate'.$j];  $vSubQty=$_POST['vSubQty'.$j];  $vSubTaxable=$_POST['vSubTaxable'.$j]; $vSubcgst=$_POST['vSubcgst'.$j]; $vSubsgst=$_POST['vSubsgst'.$j];$vSubigst=$_POST['vSubigst'.$j]; $vSubAmount=$_POST['vSubTotal'.$j];
          }
          $taxable+=$vSubTaxable; $gst+=($vSubcgst+$vSubsgst);
          $getimg=selectQuery(PRODIMG,"img_name","prod_id=".$prodid." order by id ASC  LIMIT 1");
            $proddata = $prod->getselectedAttr($prodid,"prod_name,prod_company,parent_id,variant_name1,variant_name2,variant_name3,variant_value1,variant_value2,variant_value3,return_days,is_cancellation_avail,admin_price,mrp,stock,sold,blocked,tax,hsn_code,sku");
            $prodname = $proddata[0]['prod_name'];
            $tax = $proddata[0]['tax']; $quantitiesPurchase = $vSubQty;
            $vendorprice = $prod->getVendorProductPriceForOrder($prodid);  $adminprice = $proddata[0]['admin_price']; $mrp = $proddata[0]['mrp'];
            $vendorpriceWithGst = $vendorprice; $adminpriceWithGst = $adminprice;
            $getPriceDetails = $prod->getProductPrice($prodid);
            $prodsellOn = $getPriceDetails['priceon'];
            $vendorpriceWithoutGst = round($vendorpriceWithGst-(($vendorpriceWithGst*($tax/(100+$tax)))));
            $adminpriceWithoutGst = round($adminpriceWithGst-(($adminpriceWithGst*($tax/(100+$tax)))));
            $soldonprice = $vSubRate;
            $taxonmrp = ($mrp*($tax/(100+$tax)));
            $mrpWithoutGst = round($mrp-$taxonmrp);
            if($prodsellOn=="MRP"){ $perwithoutgst = $mrpWithoutGst; }else if($prodsellOn=="Admin Price"){ $perwithoutgst = $adminprice; }else if($prodsellOn=="Vendor Price"){ $perwithoutgst = $vendorprice; }
            $currentStock = (($proddata[0]['stock']!=""?$proddata[0]['stock']:0)-(($proddata[0]['sold']!=""?$proddata[0]['sold']:0)+($proddata[0]['blocked']!=""?$proddata[0]['blocked']:0)));
             if($proddata[0]['prod_name']!=""){ //&&($quantitiesPurchase<=$currentStock)
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
                    $getvendordata = $prod->getVendorDetails($vendor);
                    $dataItem = array(
                        "order_id" =>$ordid,
                        "order_current_Status" =>"Generated",
                        "product_id" =>$prodid,
                        "product_name" =>trim(addslashes($prodname)),
                        "display_product_name" =>addslashes($prod->getParentName($prodid)),
                        "company" =>addslashes($proddata[0]['prod_company']),
                        "product_image" =>addslashes($getimg[0]['img_name']),
                        "hsn_code" =>$proddata[0]['hsn_code'],
                        "sku" =>$proddata[0]['sku'],
                        "vendor" =>$vendor,
                        "vendor_nickname" =>$getvendordata[0]['nickname'],
                        "vendor_name" =>$getvendordata[0]['dealer_name'],
                        "vendor_email" =>$getvendordata[0]['email'],
                        "vendor_contact" =>$getvendordata[0]['personalcontactno'],
                        "vendor_city" =>$getvendordata[0]['city'],
                        "disbustment_days"=>($getvendordata[0]['disbursement_cycle_days']!=""?$getvendordata[0]['disbursement_cycle_days']:VENDOR_DISBURSMENT_DAYS),
                        "variation_on" =>implode("|",$varidMainarra),
                        "variation_values" =>implode("|",$varidvalues),
                        "product_sold_on"=>"Admin Price",
                        "mrp"=>$mrp,
                        "mrpWithoutTax"=>$mrpWithoutGst,
                        "vendor_per_item_price_withoutgst" =>$vendorpriceWithoutGst,
                        "admin_per_item_price_withoutgst"=>$adminpriceWithoutGst,
                        "user_per_unit_withoutgst_price"=>$vSubRate,
                        "tax_percentage" =>$tax,
                        "quantity" =>$quantitiesPurchase,
                        "taxable_without_gst" =>$vSubTaxable,
                        "taxable_without_gst_after_discount"=>$vSubTaxable,
                        "cgst1"=>($vSubigst==0?($tax/2):0), "cgst2"=>$vSubcgst,
                        "sgst1" =>($vSubigst==0?($tax/2):0),"sgst2"=>$vSubsgst,
                        "igst1"=>($vSubigst!=0?$tax:0), "igst2" =>$vSubigst,
                        "total_with_gst" =>($vSubTaxable+$vSubcgst+$vSubsgst+$vSubigst),
                        "total_payable"=>$vSubAmount,
                        "delivered_on"=>date("Y-m-d H:i:s"),
                        "shipping_by"=>SITENAME
                       );
                    if($getimg[0]['img_name'] != ""){
                    $file = '../../../'.$thumb2_path.'/'.$getimg[0]['img_name'];
                    $newfile = '../../../img/order_img/'.$getimg[0]['img_name'];
                    copy($file, $newfile);
                    }
                    $subin=insertQuery(ORDERSUB,$dataItem);


                    $purchasedata=array("reference_order_id"=>$ordid,"reference_order_sub_id"=>$subin,"purchase_order_id"=>$subordid,"purchase_date"=>date("Y-m-d H:i:s"),"purchase_from_vendor"=>$vendor,"ispaid"=>"1","payment_date"=>date("Y-m-d H:i:s"));
                    insertQuery(PURCH,$purchasedata);

                    $produp = array("sold"=>$proddata[0]['sold']+$quantitiesPurchase);

                    updateQuery(PRODINFO,$produp,"id=".$prodid);
                }
            }

        }

        $updatedata=array( "total_taxable_amount" => $taxable,"total_gst" => $gst);
        updateQuery(ORDER,$updatedata,"id=".$ordid);
    echo SITEURL."/admin/order/order_details.php?transid=".($_POST['transactionId']?$_POST['transactionId']:$txnid);
}

if($action=="getcustdata"){
    $phone = $_POST['phoneno'];
    $get = selectQuery(ORDER,"shippingAddr_name,gst_no,shippingAddr_address,shippingAddr_pincode","shippingAddr_mobile='".$phone."' LIMIT 1");
   if(count($get)){
       $data=array("name"=>$get[0]['shippingAddr_name'],"gstno"=>$get[0]['gst_no'],"address"=>$get[0]['shippingAddr_address'],"pincode"=>$get[0]['shippingAddr_pincode']);
   }else{
    $data=array("name"=>"","gstno"=>"","address"=>"","pincode"=>"");
   }
   echo json_encode($data);
}

?>