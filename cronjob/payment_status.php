<?php
 require_once("../includes/configuration.php");
 require_once "../classes/shiprocket.php";
 require_once("../classes/order.php");
 require_once('../classes/user.php');
 require_once('../classes/product.php');
 include_once('../easebuzz/easebuzz-lib/easebuzz_payment_gateway.php');
 $username = SHIPUSER; $pasword=SHIPPWD;
 $payuSALT = SALT;   $payuMerchant=MARCHANTKEY;  $payuUrl=PAYURL;
 $ship = new shiprocket($username,$pasword);
 $token = $ship->authenticate();
 $prod = new Product();  $user = new User(); $order = new Order($prod,$user); 

 function createOrderId($txnid){
  global $getconfigdetails;
$ord=selectQuery(ORDER,"MAX(ordno) as ordno","order_id<>''");
$userOrderSeries=($getconfigdetails[0]['order_id']!=""?$getconfigdetails[0]['order_id']:"ORD");
// $serieslength=strlen($userOrderSeries);
$orderstart=$userOrderSeries."-";
// $orderlength=strlen($orderstart);
if($ord[0]['ordno']==NULL){ $orid=1; $orderid=$orderstart.$orid; }
else { 
  
   $orid=$ord[0]['ordno']+1;
   $orderid=$orderstart.$orid;
}
$chkord=selectQuery(ORDER,"order_id","order_id='".$orderid."'");
if(count($chkord)){  return createOrderId($txnid); }
else{
    $ordiddata=array("ordseq"=>$userOrderSeries,"ordno"=>$orid,"order_id"=>$orderid);
    updateQuery(ORDER,$ordiddata,"transaction_id='".$txnid."'");
     return $orderid;
}
}
  /* create ship order if not created */
  $datefrom=date("Y-m-d H:i:s",strtotime('-30 minutes'));
  $dateto=date("Y-m-d H:i:s",strtotime('-15 minutes'));
  $datetwo=date("Y-m-d H:i:s",strtotime("-48 hours"));
  $initiated=selectQuery(ORDER,"id,transaction_id,order_date,payment_mode,user_id,payable_amount,payment_status","(payment_status='Initiated' OR payment_status='failure' OR payment_status='failed' OR payment_status='authorized' OR payment_status='created')  AND (order_date < '".$dateto."' AND order_date > '".$datetwo."') order by order_date DESC");
 
  //$initiated=selectQuery(ORDER,"id,transaction_id,order_date,payment_mode,user_id,payable_amount,payment_status","payment_status='authorized'"); 
 // $initiated=selectQuery(ORDER,"id,transaction_id,order_date,payment_mode,user_id,payable_amount,payment_status","transaction_id='order_HhQRbxikCCysiN'"); 
   for($o=0;$o<count($initiated);$o++){
       $row=$initiated[$o];
       $txnid=$row['transaction_id'];
       echo "Row - ".$row['id']."<br>Txn - ".$txnid."<br>Pay - ".$row['payment_mode'];
       $udata=selectQuery(BUYER,"*","u_id=".$row['user_id']);
       if($row['payment_mode']=="PayU Money"){
           //echo "<br>https://www.payumoney.com/payment/op/getPaymentResponse?merchantKey=".$payuMerchant."&merchantTransactionIds=".$row['transaction_id'];
        $ch1 = curl_init();
        curl_setopt($ch1, CURLOPT_URL, "https://www.payumoney.com/payment/op/getPaymentResponse?merchantKey=".$payuMerchant."&merchantTransactionIds=".$txnid);
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
        
        curl_setopt($ch1, CURLOPT_POST, true); 
        
         curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Authorization:'.PAYUHEADER));
        $output = curl_exec($ch1);
        if (curl_errno($ch1)) {
            $error_msg = curl_error($ch1);
            echo $error_msg;
        }
        $array=json_decode($output,true);
        curl_close($ch1); 
     // echo "<pre>"; print_r($array);
      if($array['result'][0]){
        $status=$array['result'][0]['postBackParam']['status'];
        $unmappedstatus=$array['result'][0]['postBackParam']['unmappedstatus'];
        $amount=$array['result'][0]['postBackParam']['amount'];
        $addedon=$array['result'][0]['postBackParam']['addedon'];
        $paymentid=$array['result'][0]['postBackParam']['mihpayid'];
      }else{
        $status="failure"; $unmappedstatus="failure";$paymentid="";
      }
        
      
       }else if($row['payment_mode']=="Easebuzz"){
        $SALT = EASESALT; $easemrch=EASEMARCHANTID;
       
          $hash=hash(sha512,strtolower($easemrch."|".$txnid."|".$row['payable_amount']."|".$udata[0]['u_email']."|".$udata[0]['u_mobile']."|".$SALT));
         
       
          $curl = curl_init();

          curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://dashboard.easebuzz.in/transaction/v1/retrieve',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'txnid='.$txnid.'&amount='.$row["payable_amount"].'&email='.$udata[0]["u_email"].'&phone='.$udata[0]["u_mobile"].'&key='.$easemrch.'&hash='.$hash,
            CURLOPT_HTTPHEADER => array( 'Content-Type: application/x-www-form-urlencoded'),
          ));

          $response = curl_exec($curl);

          curl_close($curl);
         
          $array=json_decode($response,true);
          echo "<pre>"; print_r($array);
         $apstatus=$array['status'];
          if($apstatus==true){
            $status=$array['msg']['status'];
            $unmappedstatus=$array['msg']['unmappedstatus'];
            $amount=$array['msg']['amount'];
            $addedon=$array['msg']['addedon'];
            $firstname=$array['msg']['firstname'];
            $paymentid=$array['msg']['easepayid'];
           
          }else{
            echo "<br>".$array['msg'];
          } 
        }else if($row['payment_mode']=="Razorpay"){
           
            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => "https://api.razorpay.com/v1/orders/".$txnid."/payments",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'GET',
              CURLOPT_HTTPHEADER => array(
                "Authorization: Basic ".base64_encode(RAZORAPIKEY.":".RAZORSECRETE),
              ),
            ));
            $response = curl_exec($curl);
//echo '<pre>'; print_r($response);
            curl_close($curl);
            $array=json_decode($response,true);
            echo "<pre>"; print_r($array);
            $itemarr= array_reverse($array['items']);
          /*   echo '<pre>'; print_r($itemarr);
            exit(); */
           if($itemarr){
            $paymentid=$itemarr[0]['id'];
            $status=($itemarr[0]['status']?$itemarr[0]['status']:"failure");  
            $unmappedstatus=($itemarr[0]['error_description']?$itemarr[0]['error_description']: $status);
           }else{
            $paymentid="";
            $status="failure";  
            $unmappedstatus="failed";
           }
       
           
        }else if($row['payment_mode']=="Instamojo"){ 
            $ch = curl_init();
           
           // curl_setopt($ch, CURLOPT_URL, 'https://www.instamojo.com/api/1.1/payment-requests/');
           curl_setopt($ch, CURLOPT_URL, 'https://www.instamojo.com/api/1.1/payment-requests/'.$txnid.'/');
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
            curl_setopt($ch, CURLOPT_HTTPHEADER,
                        array("X-Api-Key:".INSTAAPIKEY,
                              "X-Auth-Token:".INSTATOKEN));
            $response = curl_exec($ch);
           
            curl_close($ch);
            $array=json_decode($response,true);
          //  echo "<pre>"; print_r($array);
            if($array['payment_requests']['payments'][0]){
              $status=$array['payment_requests']['payments'][0]['status'];
              $unmappedstatus=$status;
              $amount=$array['payment_requests']['payments'][0]['amount'];
              $addedon=$array['payment_requests']['payments'][0]['created_at'];
              $paymentid="";
            }else{
              $status="failure";
              $unmappedstatus=$status;
              $paymentid="";
            }
        }

     
        if($status!=""){
          
          $orderstatus=($status=="success"||$status=="captured"?"Generated":"failure");
          if($status=="success"||$status=="captured"){
            $generatedOrderId= createOrderId($txnid);
          }
          $data=array("payment_status"=>$status,"payment_id"=>$paymentid,"gateway_response"=>$unmappedstatus);
          updateQuery(ORDER,$data,"transaction_id='".$txnid."'");
           $orderdata=$order->getOrderFullDetails($txnid);   
           $ordid=$orderdata['id'];
           $data1=array("order_current_Status"=>$orderstatus);
          updateQuery(ORDERSUB,$data1,"order_id=".$ordid);
      
          
          $getblockiems=selectQuery(BLOCK,"prod_id,quantity","transaction='".$txnid."'");
          if(count($getblockiems)){
            for($i=0;$i<count($getblockiems);$i++){
              $prodid=$getblockiems[$i]['prod_id'];$qtys=$getblockiems[$i]['quantity'];
              $proddata=$prod->getselectedAttr($prodid,"blocked");
              $updateblock=array("blocked"=>($proddata[0]['blocked']-$qtys));
              updateQuery(PRODINFO,$updateblock,"id=".$prodid);
            }
          }
         deleteQuery(BLOCK,"transaction='".$txnid."'");
         $token=$ship->authenticate();

 
        if($status=="success"||$status=="captured"){
         $getdata=selectQuery(ORDERSUB,"*","order_id=".$ordid);
          for($i=0;$i<count($getdata);$i++){
              $idcnt=$i+1;
              $subordid=$generatedOrderId."-".$idcnt;
            $productid=$getdata[$i]['product_id']; $quantity=$getdata[$i]['quantity']; $vendorid=$getdata[$i]['vendor'];
            $shipping_by=$getdata[$i]['shipping_by'];
             
              $proddata=$prod->getselectedAttr($productid,"sold,hsn_code,weight,length,height,width,sku");
              $updatestock=array("sold"=>($proddata[0]['sold']+$quantity));
              updateQuery(PRODINFO,$updatestock,"id=".$productid);
          

              $purchasedata=array("reference_order_id"=>$ordid,"reference_order_sub_id"=>$getdata[$i]['item_id'],"purchase_order_id"=>$subordid,"purchase_date"=>date("Y-m-d H:i:s"),"purchase_from_vendor"=>$vendorid);
            
              $chkord=selectQuery(PURCH,"pur_id","reference_order_id=".$ordid." AND reference_order_sub_id=".$getdata[$i]['item_id']);
             
              if(!count($chkord)){  insertQuery(PURCH,$purchasedata);}


              $checkcart=selectQuery(CART,"cart_id","user_id=".$orderdata['user_id']." and prod_id=".$productid." and type='CART'");
              if(count($checkcart)){ deleteQuery(CART,"cart_id=".$checkcart[0]['cart_id']);  }


            if($shipping_by!=SITENAME){
              $vendor="v".$vendorid;
              $pickups=$ship->getPickups($token);
              $pickupid = array_search($vendor, array_column($pickups, 'pickup_location'));
              $pickupdetails=$pickups[$pickupid];
              $itemprice=$getdata[$i]['user_per_unit_withoutgst_price']+round((($getdata[$i]['user_per_unit_withoutgst_price']/100)*$getdata[$i]['tax_percentage']),2);

            $shipmentarray=array(
                "order_id"=> $subordid, "order_date"=>date("Y-m-d H:i"),
                "pickup_location"=> trim($pickupdetails['pickup_location']),
                "channel_id"=> "Custom", "comment"=> "",
                "billing_customer_name"=>trim($orderdata['u_fname']),"billing_last_name"=>trim($orderdata['u_lname']),
                "billing_address"=> trim($orderdata['address']),"billing_address_2"=> trim($orderdata['landmark']),
                "billing_city"=>trim($orderdata['city']),"billing_pincode"=>trim($orderdata['pincode']),
                "billing_state"=>trim($orderdata['state']), "billing_country"=> trim($orderdata['country']),
                "billing_email"=> trim($orderdata['u_email']), "billing_phone"=> trim($orderdata['u_mobile']),
                "shipping_is_billing"=> true,
                "shipping_customer_name"=> "",  "shipping_last_name"=> "","shipping_address"=> "","shipping_address_2"=> "","shipping_city"=> "", "shipping_pincode"=> "","shipping_country"=> "","shipping_state"=> "","shipping_email"=> "","shipping_phone"=> "",
                "order_items"=>array(array(
                    "name"=>trim($getdata[$i]['display_product_name']),
                    "sku"=>trim($proddata[0]['sku']),"units"=> trim($getdata[$i]['quantity']),
                    "selling_price"=> trim($itemprice),
                    "discount"=>  trim($getdata[$i]['discount_amount']+round((($getdata[$i]['discount_amount']/100)*$getdata[$i]['tax_percentage']),2)),   "tax"=>trim($getdata[$i]['tax_percentage']),"hsn"=> ""
                  )),
                "payment_method"=> ($orderdata['payment_mode']=="COD"?"COD":"Prepaid"),
                "shipping_charges"=> ($getdata[$i]['isFreeShipping']==0?$getdata[$i]['shipping_charges']:0)+$getdata[$i]['cod_charges'],
                "giftwrap_charges"=> 0, "transaction_charges"=> 0,"total_discount"=> trim($getdata[$i]['discount_amount']+round((($getdata[$i]['discount_amount']/100)*$getdata[$i]['tax_percentage']),2)),
                "sub_total"=> trim($itemprice)*trim($getdata[$i]['quantity']),
                "length"=>trim(($proddata[0]['length']==0.00?0.5:$proddata[0]['length']))*$getdata[$i]['quantity'] ,"breadth"=>trim(($proddata[0]['width']==0.00?0.5:$proddata[0]['width']))*$getdata[$i]['quantity'] ,"height"=> trim(($proddata[0]['height']==0.00?0.5:$proddata[0]['height']))*$getdata[$i]['quantity'],"weight"=> trim(($proddata[0]['weight']==0.000?0.5:$proddata[0]['weight']))*$getdata[$i]['quantity']
              );
          
              $shiporder= $ship->createOrder($token,$shipmentarray);
             
              $shiporderid= $shiporder['order_id'];  $shipmentid= $shiporder['shipment_id'];  $awbcode="";
          
            if($shiporderid!=""){
                  $shipdata=array("shipping_order_id"=>$shiporderid,"shipping_shipment_id"=>$shipmentid);
                  updateQuery(ORDERSUB,$shipdata,"item_id=".$getdata[$i]['item_id']);
            }else{
                  $shipdata=array("shipping_order_error"=>json_encode($shiporder));
                  updateQuery(ORDERSUB,$shipdata,"item_id=".$getdata[$i]['item_id']);

                    $headersmain  = 'MIME-Version: 1.0' . "\r\n";
                    $headersmain .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                    $headersmain .= "From:".SITENAME."<".EMAIL_SENDER.">" . "\r\n";
                    $headersmain .= 'Cc: support@surun.in,surunclients@gmail.com';
                    $subjectmain="Alert! Shipping Order Not Generated For ".SITENAME;
                    $bodymain='<div style="font-family:calibri; background-color:#fff;width:100%;border:1px solid #e6e6e6;margin-bottom:20px;">
                          <div style="padding:0 20px 20px 20px;">
                              Shipping oder not created for order id '.$subordid.' because of bellow errors -

                          </div>
                          <div>'.json_encode($shiporder).'</div>
                      </div>';
                      $mainmailto=MAIN_ADMIN;
                    mail($mainmailto, $subjectmain, $bodymain, $headersmain);

            }
            }
             
             
          }

          require_once "../order/order_mail.php";
          sendmail($txnid,$order);
        }else if($status=="failure"){
        /*   $getdata=selectQuery(ORDERSUB,"*","order_id=".$ordid);
          for($i=0;$i<count($getdata);$i++){
              $idcnt=$i+1;
              $productid=$getdata[$i]['product_id']; $quantity=$getdata[$i]['quantity']; $vendorid=$getdata[$i]['vendor'];
              $shipping_by=$getdata[$i]['shipping_by'];
               
             $subordid=$generatedOrderId."-".$idcnt;
            $purchasedata=array("reference_order_id"=>$ordid,"reference_order_sub_id"=>$getdata[$i]['item_id'],"purchase_order_id"=>$subordid,"purchase_date"=>date("Y-m-d H:i:s"),"purchase_from_vendor"=>$vendorid);
            $chkord=selectQuery(PURCH,"pur_id","reference_order_id=".$ordid." AND reference_order_sub_id=".$getdata[$i]['item_id']);
            if(!count($chkord)){insertQuery(PURCH,$purchasedata);}
          } */
        } 
      } 
   }
?>