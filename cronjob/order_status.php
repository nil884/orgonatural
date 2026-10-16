<?
 require_once("../includes/configuration.php");
 require_once "../classes/shiprocket.php";
 require_once("../classes/order.php");
 require_once('../classes/user.php');
 require_once('../classes/product.php');
 $username = SHIPUSER; $pasword=SHIPPWD;
 $ship = new shiprocket($username,$pasword);
 $token = $ship->authenticate();
 $prod = new Product();  $user = new User(); $order = new Order($prod,$user);

  /* create ship order if not created */
  $orderblanked=selectQuery(ORDERSUB,"distinct order_id","order_current_Status='Generated' and shipping_by<>'".SITENAME."' and (shipping_order_id='' OR shipping_order_id IS NULL)");
   for($j=0;$j<count($orderblanked);$j++){
        $ordid=$orderblanked[$j]['order_id'];

        $orderData=selectQuery(ORDER,"order_id,transaction_id","id=".$ordid);
         $orderdata=$order->getOrderFullDetails($orderData[0]['transaction_id']);
       $getdata=selectQuery(ORDERSUB,"*","order_id=".$ordid);
        for($i=0;$i<count($getdata);$i++){
         $idcnt=$i+1;
        $subordid=$orderData[0]['order_id']."-".$idcnt;
      $productid=$getdata[$i]['product_id']; $quantity=$getdata[$i]['quantity']; $vendorid=$getdata[$i]['vendor'];

         $proddata=$prod->getselectedAttr($productid,"sold,hsn_code,weight,length,height,width,sku");

        $vendor="v".$vendorid;
        $pickups=$ship->getPickups($token);
         $pickupid = array_search($vendor, array_column($pickups, 'pickup_location'));
         $pickupdetails=$pickups[$pickupid];
          $itemprice=$getdata[$i]['user_per_unit_withoutgst_price']+round((($getdata[$i]['user_per_unit_withoutgst_price']/100)*$getdata[$i]['tax_percentage']),2);
      $shipmentarray=array(
          "order_id"=> $subordid, "order_date"=>date("Y-m-d H:i"),
          "pickup_location"=> $pickupdetails['pickup_location'],
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
              "discount"=> trim($getdata[$i]['discount_amount']+round((($getdata[$i]['discount_amount']/100)*$getdata[$i]['tax_percentage']),2)), "tax"=>trim($getdata[$i]['tax_percentage']),"hsn"=> ""
            )),
          "payment_method"=> ($orderdata['payment_mode']=="COD"?"COD":"Prepaid"),
          "shipping_charges"=> ($getdata[$i]['isFreeShipping']==0?$getdata[$i]['shipping_charges']:0),
          "giftwrap_charges"=> 0, "transaction_charges"=> 0,"total_discount"=> trim($getdata[$i]['discount_amount']+round((($getdata[$i]['discount_amount']/100)*$getdata[$i]['tax_percentage']),2)),
          "sub_total"=> trim($itemprice)*trim($getdata[$i]['quantity']),
          "length"=>($proddata[0]['length']==0.00?0.5:$proddata[0]['length']) ,"breadth"=>($proddata[0]['width']==0.00?0.5:$proddata[0]['width']) ,"height"=> ($proddata[0]['height']==0.00?0.5:$proddata[0]['height']),"weight"=> ($proddata[0]['weight']==0.000?0.5:$proddata[0]['weight'])
        );

        $shiporder= $ship->createOrder($token,$shipmentarray);
      /*  echo "\n\r --------------------------------------------- \n\r";
         echo "<pre>"; print_r($shipmentarray);             print_r($shiporder);   echo "</pre>";
         echo "\n\r --------------------------------------------- \n\r";*/

        $shiporderid= $shiporder['order_id'];  $shipmentid= $shiporder['shipment_id'];  $awbcode="";
       if($shiporderid!=""){
             $shipdata=array("shipping_order_id"=>$shiporderid,"shipping_shipment_id"=>$shipmentid,"shipping_order_error"=>"");
            updateQuery(ORDERSUB,$shipdata,"item_id=".$getdata[$i]['item_id']);
       }else{
            $shipdata=array("shipping_order_error"=>json_encode($shiporder));
            updateQuery(ORDERSUB,$shipdata,"item_id=".$getdata[$i]['item_id']);
       }
     }
   }


 /* for checking order status */
 $orderStatus=selectQuery(ORDERSUB,"item_id,order_id,order_current_Status,shipping_order_id,disbustment_days,return_days,shipping_awb_no,shipping_shipment_id,total_payable","shipping_by<>'".SITENAME."'  AND is_returned='0' AND (order_current_Status<>'Initiated' AND order_current_Status<>'Delivered' AND order_current_Status<>'Canceled' AND order_current_Status<>'failure') ");


 if(count($orderStatus)){

     for($i=0;$i<count($orderStatus);$i++){
          $ordid=$orderblanked[$i]['order_id'];

        $paymode=selectQuery(ORDER,"payment_mode","id=".$ordid);
        $itemId=$orderStatus[$i]['item_id']; $shippingOrderId=$orderStatus[$i]['shipping_order_id'];  $disbustment_days=($orderStatus[$i]['disbustment_days']!=""?$orderStatus[$i]['disbustment_days']:VENDOR_DISBURSMENT_DAYS); $return_days=($orderStatus[$i]['return_days']?$orderStatus[$i]['return_days']:0);
         $dis_final=$return_days+ $disbustment_days;
          if($shippingOrderId!=""){
                 $odata=$ship-> showOrderDetails($token,$shippingOrderId);

                 $status=ucwords(strtolower($odata['data']['status']));
                 if($status!=""&&$orderStatus[$i]['order_current_Status']!=$status){
                 $shipedDate=$odata['data']['shipments']['shipped_date'];
                 $stat=array('order_current_Status'=>$status);
                  updateQuery(ORDERSUB,$stat,"item_id=".$itemId);
                if($status=="Shipped"||$status=="Delivered"||$status=="Canceled"||$status=="Out For Delivery"||$status=="In Transit"){

                $data=array();
                  if($status=="Shipped"){   
                       $data['shipped_on']=date("Y-m-d H:i:s",strtotime($shipedDate));
                  }else if($status=="Delivered"){
                    $data['delivered_on']=date("Y-m-d H:i:s"); if($return_days!=0){  $data['return_valid_till']=date('Y-m-d', strtotime(' +'.$return_days.' days'));}  $data['disbustment_date']=date('Y-m-d', strtotime(' +'.$dis_final.' days'));
                  }
                  else if($status=="Canceled"){
                     $getCOnfig = selectQuery(CONFIG,"cancelation_charge_percentage,cut_shipping_charges_on_cancelation");

                        $getorderSubData = selectQuery(ORDERSUB,"product_id,product_image,product_name,quantity,total_payable,refundable_amount,vendor_nickname,isFreeShipping,shipping_charges,vendor_email,total_with_gst","item_id=".$itemId);

                        $is_cod = $paymode[0]['is_cod'];
                        $payment_mode = $paymode[0]['payment_mode'];
                        $total_payable = $getorderSubData[0]['total_payable'];
                        $prodamount_with_gst = $getorderSubData[0]['total_with_gst'];
                         $shipping_charges=($getorderSubData[0]['isFreeShipping']==1?0:$getorderSubData[0]['shipping_charges']);
                        $cutshipping= $getCOnfig[0]['cut_shipping_charges_on_cancelation'];
                       /*  if($canceled_by == "Buyer"){     */
                            $cancelation_percent=$getCOnfig[0]['cancelation_charge_percentage'];
                            if($cutshipping==0){
                               $cancelation = round(($prodamount_with_gst/100)*$cancelation_percent);
                               $refundable_amount = $total_payable-$cancelation;
                             }
                           else{
                                $cancelation =  round((($prodamount_with_gst/100)*$cancelation_percent)  + $shipping_charges);
                                $refundable_amount = $total_payable-$cancelation;
                             }

                             $data['cancelation_date']=date("Y-m-d H:i:s");
                             $data['refundable_amount']=($payment_mode!="COD"?$refundable_amount:0);
                             $data['is_refund_appilicable']=1;
                            // $data['refund_status']=($payment_mode=="COD"?1:0);
                            $data['refund_status']=1;
                             if($payment_mode=="COD"){ $data['refund_date']=date("Y-m-d H:i:s"); }
                              $data['cancel_reason']="Other";   $data['cancelled_by']="Other";
                   }
                 if(count($data)){ updateQuery(ORDERSUB,$data,"item_id=".$itemId);}
                }else if(($status=="Pickup Scheduled"||$status=="Ready To Ship")&&$orderStatus[$i]['shipping_awb_no']==""){
                    // $jsondata = array("shipment_id"=>$shipmentId,"courier_id"=>$courierId ,"status"=>"");
                   $awb=$odata['data']['shipments']['awb'];
                   $courier=$odata['data']['shipments']['courier'];
                   $courierId=$odata['data']['shipments']['courier_id'];
                    $data1 = array("shipping_awb_no"=>$awb,"order_current_Status"=>"Waiting For Ship","pickup_token_number"=>"","pickup_schedule_date"=>"");

                    $order->updateItem($itemId,$data1);
                    $label = array("shipment_id"=>array($shipmentId));
                     $labeldata = $ship->generateLabel($token,$label);

                     $labelStatus = $labeldata['label_created'];
                     if($labelStatus==1){
                         $data2 = array("label"=>$labeldata['label_url']);
                        $order->updateItem($itemId,$data2);
                     }

                    $manifest = array("shipment_id"=>array($shipmentId));
                     $manifestdata = $ship->generateManifest($token,$manifest);
                     $manifestStatus = $manifestdata['status'];
                     if($manifestStatus==1){
                         $data2=array("manifest"=>$manifestdata['manifest_url']);
                        $order->updateItem($itemId,$data2);
                     }else{
                         $ids = array("order_ids"=>array($shippingOrderId));
                         $manifestdata1 = $ship->printManifest($token,$ids);
                          $data2=array("manifest"=>$manifestdata1['manifest_url']);
                        $order->updateItem($itemId,$data2);
                     }

                }else if($status=="RTO Delivered"){  // RTO
                 $data=array();
                    $data['is_refund_appilicable']=1; $data['refundable_amount']=$orderStatus[$i]['total_payable']; if($paymode[0]['payment_mode']=="COD"){$data['refund_status']=1;$data['refund_date']=date("Y-m-d H:i:s");}else{$data['refund_status']=0;}
                }
            }
          } //end of shipment blank
    }
 }

 /* for checking return order status */
$retorderStatus=selectQuery(ORDERSUB,"item_id,return_order_id,return_shipment_id,total_payable,isFreeShipping,shipping_charges,order_current_Status","shipping_by<>'".SITENAME."' and is_returned='1' and (order_current_Status<>'Return Delivered' AND order_current_Status<>'Return Rejected')");
  $refundwship=$getconfigdetails[0]['refund_with_shipping'];

 if(count($retorderStatus)){
     for($i=0;$i<count($retorderStatus);$i++){
         $shippingOrderId=$retorderStatus[$i]['return_order_id'];
         $total_payable=$retorderStatus[$i]['total_payable'];
         $shipping_charges=($retorderStatus[$i]['isFreeShipping']=='1'?0.00:$retorderStatus[$i]['shipping_charges']);
        if($refundwship==0){  $refundable_amount=$total_payable-$shipping_charges;}
        else{ $refundable_amount=$total_payable;  }
         if($shippingOrderId!=""){
             $data=$ship-> showOrderDetails($token,$shippingOrderId);

             $status=ucwords(strtolower($data['data']['status']));
              if($status!=""&&$orderStatus[$i]['order_current_Status']!=$status){
             $updatedata=array();
            $updatedata['return_status']=$status;
              $updatedata['order_current_Status']=$status;
            if($status=="Return Delivered"){
               $updatedata['is_refund_appilicable']="1";$updatedata['refundable_amount']=$refundable_amount;$updatedata['refund_status']="0";
            }
            updateQuery(ORDERSUB,$updatedata,"item_id=".$retorderStatus[$i]['item_id']);
            }
        }
    }

 }

?>