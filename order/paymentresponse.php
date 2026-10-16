<?php
    include("../includes/configuration.php");
    include_once('../easebuzz/easebuzz-lib/easebuzz_payment_gateway.php');
     include("../classes/order.php");
    require_once('../classes/user.php');
    require_once('../classes/product.php');
    include_once "../classes/shiprocket.php";
  
    $username=SHIPUSER; $pasword=SHIPPWD;
    $ship=new shiprocket($username,$pasword);
    $prod = new Product();  $user = new User(); $order = new Order($prod,$user);

   if (!empty($_GET)) {
      include "../instamojo/Instamojo.php";

        $api = new Instamojo\Instamojo(INSTAAPIKEY,INSTATOKEN, INSTAURL);
        $payid=$_GET['payment_request_id'];
            try {
                $response = $api->paymentRequestStatus($payid);

                $status=($response['status']=="Completed"||$response['status']=="success"?"success":($response['status']=="failed"?"failure":$response['status']));    $status1=($status);
                $firstname=$response['buyer_name']; $amount=$response['amount'];  $txnid=$response['id'];
                $email=$response['email'];  $paymentid=$response['payments'][0]['payment_id'];
            }
            catch (Exception $e) {
               $error= $e->getMessage();
            }
  }else{
      if($_POST['payment_source']=="Easebuzz"){
         $SALT = EASESALT;
         $easebuzzObj = new Easebuzz($MERCHANT_KEY = null, $SALT, $ENV = null);
         $result = $easebuzzObj->easebuzzResponse($_POST);
         $response =json_decode($result);
         $data=$response->data;
        
         $status=($data->status=="success"?$data->status:"failure");    $status1=($status);
         $firstname=$data->firstname; $amount=$data->amount;  $txnid=$data->txnid;
         $email=$data->email;  $paymentid=$data-> easepayid;
      }else if($_POST['payment_source']=="COD"){
          $txnid=$_POST['txnid'];
           $status="success"; $paymentid=""; $status1="success";
      }else if($_POST['mode']=="razorpay"){
          $success = true;
          $error = "Payment Failed";

          require('../razorpay-php/Razorpay.php');

          $api = new Razorpay\Api\Api(RAZORAPIKEY, RAZORSECRETE);
          if($_POST['razorpay_payment_id']){
               try{
                $attributes = array(
                    'razorpay_order_id' => $_SESSION['razorpay_order_id'],
                    'razorpay_payment_id' => $_POST['razorpay_payment_id'],
                    'razorpay_signature' => $_POST['razorpay_signature']
                );

                $api->utility->verifyPaymentSignature($attributes);
            }
            catch(SignatureVerificationError $e){
                $success = false;
                $error = $e->getMessage();
            }
          }else{
               $success = false; $error = "Canceled By User";
          }

          $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => "https://api.razorpay.com/v1/orders/".$_POST['transaction_id']."/payments",
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
         /*    echo "<pre>"; print_r($array); */
            $itemarr= array_reverse($array['items']);
          /*   echo '<pre>'; print_r($itemarr);
            exit(); */
           if($itemarr){
            $paymentid=$itemarr[0]['id'];
            $status=($itemarr[0]['status']?$itemarr[0]['status']:"failure");  
            $unmappedstatus=($itemarr[0]['error_description']?$itemarr[0]['error_description']: $status);
           }else{
            $unmappedstatus= "";
           }

            if ($success === true){
                 $txnid=$_POST['transaction_id'];    $status="success"; $paymentid=$_POST['razorpay_payment_id'];
                  $error="";
            }
            else
            {
                $txnid=$_POST['transaction_id'];   $status="failure"; $paymentid=$_POST['razorpay_payment_id'];
               $error = $error;
            }
            $status1 = ($unmappedstatus?$unmappedstatus:$error);
      }
      else{
        $status=$_POST["status"]; $firstname=$_POST["firstname"];
        $amount=$_POST["amount"]; $txnid=$_POST["txnid"];
        $posted_hash=$_POST["hash"]; $key=$_POST["key"];
        $email=$_POST["email"];  $status1=$_POST['unmappedstatus'];
        $error=$_POST['error']; $salt=SALT;
        $paymentid=$_POST['payuMoneyId'];
      
     }
  }


     function createOrderId($txnid){
           global $getconfigdetails;
        $ord=selectQuery(ORDER,"MAX(ordno) as ordno","order_id<>''");
        $userOrderSeries=($getconfigdetails[0]['order_id']!=""?$getconfigdetails[0]['order_id']:"ORD");
       // $serieslength=strlen($userOrderSeries);
        $orderstart=$userOrderSeries."-";
       // $orderlength=strlen($orderstart);
        if($ord[0]['ordno']==NULL){ $orid=1; $orderid=$orderstart.$orid; }
        else { 
            /* $orid=$ord[0]['order_id'];
            $numcnt=substr($orid,$orderlength);
            $a= ++$numcnt;
            $orderid= $orderstart."".$a; */
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

    $orderstatus=($status=="success"?"Generated":$status);
     $isblank=selectQuery(ORDER,"order_id","transaction_id='".$txnid."'");
  
      if($isblank[0]['order_id']==""){
        if($status=="success"){
          $generatedOrderId= createOrderId($txnid);
        }
          
        
            /* remove and update block */
            $getblockiems=selectQuery(BLOCK,"prod_id,quantity","transaction='".$txnid."'");
            if(count($getblockiems)){
              for($i=0;$i<count($getblockiems);$i++){
                $prodid=$getblockiems[$i]['prod_id'];$qtys=$getblockiems[$i]['quantity'];
                $proddata=$prod->getselectedAttr($prodid,"blocked");
                $updateblock=array("blocked"=>($proddata[0]['blocked']-$qtys));
                updateQuery(PRODINFO,$updateblock,"id=".$prodid);
              }
            }
      }else{
           $generatedOrderId= $isblank[0]['order_id'];
      }
 
  
    $data=array("payment_status"=>$status,"payment_id"=>$paymentid,"gateway_response"=>$status1);
    updateQuery(ORDER,$data,"transaction_id='".$txnid."'");
     $orderdata=$order->getOrderFullDetails($txnid);   /*$_SESSION['reguser']=$orderdata['user_id'];  */
     $ordid=$orderdata['id'];
     $data1=array("order_current_Status"=>$orderstatus);
    updateQuery(ORDERSUB,$data1,"order_id=".$ordid);


   deleteQuery(BLOCK,"transaction='".$txnid."'");
    /*end for remove and update block */
    $token=$ship->authenticate();
   if($status=="success"){

       /* 1. update stock  2. create shipment order 3. create purchase invoices*/
     $getdata=selectQuery(ORDERSUB,"*","order_id=".$ordid);
     for($i=0;$i<count($getdata);$i++){
         $idcnt=$i+1;
        $subordid=$generatedOrderId."-".$idcnt;
      $productid=$getdata[$i]['product_id']; $quantity=$getdata[$i]['quantity']; $vendorid=$getdata[$i]['vendor'];
      $shipping_by=$getdata[$i]['shipping_by'];
      
      /* update stock */
         $proddata=$prod->getselectedAttr($productid,"sold,hsn_code,weight,length,height,width,sku,affiliation_price");
        $updatestock=array("sold"=>($proddata[0]['sold']+$quantity));
        updateQuery(PRODINFO,$updatestock,"id=".$productid);
       /* update stock end*/

        /* create purchase order */
        $purchasedata=array("reference_order_id"=>$ordid,"reference_order_sub_id"=>$getdata[$i]['item_id'],"purchase_order_id"=>$subordid,"purchase_date"=>date("Y-m-d H:i:s"),"purchase_from_vendor"=>$vendorid);
        /* Affiliation */
        if($getdata[$i]['affiliated_id']){ 
          $getaff = selectQuery(AFFILIATE ,"id,product_id,user_id,visits_count","id='".$getdata[$i]['affiliated_id']."' and is_active='1'");
          if(count($getaff)){ 
            $purchasedata['is_affiliation']='1';
            $purchasedata['affiliation_id']=$getdata[$i]['affiliated_id'];
            $purchasedata['affiliation_user']=$getdata[$i]['affiliated_user'];
            $purchasedata['affiliation_amount']=$proddata[0]['affiliation_price'];
            $purchasedata['is_affiliation_paid']='0';
          }
        }
        /* End Affiliation */
        $chkord=selectQuery(PURCH,"pur_id","reference_order_id=".$ordid." AND reference_order_sub_id=".$getdata[$i]['item_id']);
        if(!count($chkord)){insertQuery(PURCH,$purchasedata);}


        /* get vendor warehouse pickup loaction */
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
      /*  echo "<pre>"; print_r($shipmentarray); echo "</pre>";     */
        $shiporder= $ship->createOrder($token,$shipmentarray);
        // print_r($shiporder);
     /*     echo "<pre>"; print_r($shiporder); echo "</pre>";  */
        $shiporderid= $shiporder['order_id'];  $shipmentid= $shiporder['shipment_id'];  $awbcode="";
     //   $arwdata= $ship->createAWBNo($token,$jsondata);
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
       
     $checkcart=selectQuery(CART,"cart_id","user_id=".$orderdata['user_id']." and prod_id=".$productid." and type='CART'");
        if(count($checkcart)){ deleteQuery(CART,"cart_id=".$checkcart[0]['cart_id']); unset($_SESSION['shopping_cart']); }

     }

     require_once "order_mail.php";
     sendmail($txnid,$order);
       ?><script>window.location='<?php echo SITEURL."/paymentend/".$txnid; ?>'</script><?
   }else{
     /*    $getdata=selectQuery(ORDERSUB,"*","order_id=".$ordid);
         for($i=0;$i<count($getdata);$i++){
             $idcnt=$i+1;
            $subordid=$generatedOrderId."-".$idcnt;
          $productid=$getdata[$i]['product_id']; $quantity=$getdata[$i]['quantity']; $vendorid=$getdata[$i]['vendor'];
         
            $purchasedata=array("reference_order_id"=>$ordid,"reference_order_sub_id"=>$getdata[$i]['item_id'],"purchase_order_id"=>$subordid,"purchase_date"=>date("Y-m-d H:i:s"),"purchase_from_vendor"=>$vendorid);
            $chkord=selectQuery(PURCH,"pur_id","reference_order_id=".$ordid." AND reference_order_sub_id=".$getdata[$i]['item_id']);
            if(!count($chkord)){insertQuery(PURCH,$purchasedata);}
      } */
          ?><script>window.location='<?php echo SITEURL."/paymentend/".$txnid; ?>'</script><? 
   }

?>