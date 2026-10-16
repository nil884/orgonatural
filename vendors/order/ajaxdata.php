<?php include("../../includes/configuration.php"); require_once "../../classes/shiprocket.php";
include("../../classes/order.php"); require_once('../../classes/user.php'); require_once('../../classes/product.php');
$prod = new Product();  $user = new User(); $order = new Order($prod,$user);
if($action == "get_order_item_detail"){
$orderdata= $order->getVendor_seprate_order($itemid); ?>
<div class="modal-header">          
    <h5 class="mb-0 modal-title">
        <?php 
 echo $orderdata[0]['order_current_Status'];?> order details of <?php echo $orderdata[0]['purchase_order_id'] ?></h5>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
    <div class='row'>
        <?php if($orderdata[0]['product_image']){
            $img = SITEURL."/img/order_img/".$orderdata[0]['product_image'];
        }else{
            $img = SITEURL."/img/projectimage/product-default.png";
        }
        if($orderdata[0]['order_current_Status'] == "Canceled"){ $refund = "<div class='mb-1 text-danger border border-danger py-1 px-2 rounded d-inline-block'>Cancel Reason : ".$orderdata[0]['cancel_reason']."</div><br><div class='mb-1'> Cancelled by : ".$orderdata[0]['cancelled_by']."</div>" ;  } else { $refund = ""; } 
        echo "<div class='col-md-4 mb-3'><img src='".$img."' class='img-fluid mb-2 mb-md-0' alt='ord-thumb'></div><div class='col-md-8'><h6 class='text-primary'>".
        $orderdata[0]['display_product_name']."</h6>
        <div class='mb-1'>Order Status : ".$orderdata[0]['order_current_Status']."</div>".$refund."<div class='mb-1'>";
        $variationon = $orderdata[0]['variation_on'];
        if($variationon!=""){
            $variationonarr = explode("|",$variationon); 
            $variativaluearr = explode("|",$orderdata[0]['variation_values']);
            for($v=0;$v<count($variationonarr);$v++){ ?>
            <span class="mr-2"><?=$variationonarr[$v]; ?> : <?=$variativaluearr[$v]; ?></span> <? } } echo "</div>";
        
        echo '<div class="mb-1">Order Date : '; echo date("d M Y h:i A",strtotime($orderdata[0]['purchase_date']))."</div>". "<div class='mb-1'>Qunatity : ".$orderdata[0]['quantity']."</div>";
        if($orderdata[0]['hsn_code'] == "") { $hsn = "NA"; }  else { $hsn = $orderdata[0]['hsn_code']; }
        if($orderdata[0]['sku'] == "") { $sku = "NA"; }  else { $sku = $orderdata[0]['sku']; }
        echo "<div class='mb-1'>HSN Code : ".$hsn."</div>";
        echo "<div class='mb-1'>SKU Code : ".$sku."</div>";
        echo "<div class='h6 mt-1 mb-0'>Total Price : <i class='fa fa-inr'></i>". $orderdata[0]['quantity'] *($orderdata[0]['vendor_per_item_price_withoutgst']+($orderdata[0]['vendor_per_item_price_withoutgst'] * $orderdata[0]['tax_percentage']/100))."</div></div></div>"; ?>
    </div>
    <div class="border-top">
        <div class="mt-3 order-status-col pb-3 col-md-12">  
            <span class="mr-2">Current Status - <?=$orderdata[0]['order_current_Status'];?></span><button type="button" class="track-ord-status btn btn-link btn-xs p-0 text-link" onclick="track('<?=$orderdata[0]['shipping_shipment_id']; ?>','tracking-details')">View More</button>          
            <div id="tracking-details" class="tracking-details border-top mt-2 row cc-display-none"></div>
        </div>
    </div>
</div>
<?php }  
if($action=="request_pickup"){
    $itemid = $_POST['itemid'];
     $getordid=selectQuery(ORDER,"id","order_id='".$itemid."'");
     $getSubOrd=selectQuery(ORDERSUB,"item_id,product_name,quantity,product_image","order_id=".$getordid[0]['id']." AND order_current_Status<>'Canceled'");
     $itm=$getSubOrd[0]['item_id'];
    $shipmentId = $order->getShipmentId($itm);
     $courierId = $order->getCourierId($itm);
    $awbNo = $order->getAWBNO($itm);
    $username = SHIPUSER; $pasword=SHIPPWD;
    $ship = new shiprocket($username,$pasword);
    $token = $ship->authenticate();
    $shipby=$order->getOrderItemDetatils($itm,"shipping_by");

    if($shipby!=SITENAME){
        //not working as order combining
        if($awbNo==""){
            $jsondata = array("shipment_id"=>$shipmentId,"courier_id"=>$courierId ,"status"=>"");
            $arwdata = $ship->createAWBNo($token,$jsondata);
             $awb_status=$arwdata['awb_assign_status'];

            if($awb_status){
                $awbno = $arwdata['response']['data']['awb_code'];
                $courier_id = $arwdata['response']['data']['courier_company_id'];
                $courier_name = $arwdata['response']['data']['courier_name'];
                $data = array("shipping_awb_no"=>$awbno);
                $order->updateItem($itemid,$data);
                  $data = array("shipment_id"=>array($shipmentId) ,"status"=>"");
                    $pickups = $ship->requestForPickup($token,$data);

                    $pickup_status = $pickups['pickup_status'];
                    if($pickup_status!=""){
                        $pickupno = $pickups['response']['pickup_token_number'];
                        $pickupdate = $pickups['response']['pickup_scheduled_date'];
                         $res = $pickups['response']['data'];
                        $data1 = array("order_current_Status"=>"Waiting For Ship","pickup_token_number"=>$pickupno,"pickup_schedule_date"=>date("Y-m-d H:i:s",strtotime($pickupdate)));
                        $order->updateItem($itemid,$data1);
                         $manifest = array("shipment_id"=>array($shipmentId));
                         $manifestdata = $ship->generateManifest($token,$manifest);
                         $manifestStatus = $manifestdata['status'];
                         if($manifestStatus==1){
                             $data1=array("manifest"=>$manifestdata['manifest_url']);
                            $order->updateItem($itemid,$data1);
                         }
                         $label = array("shipment_id"=>array($shipmentId));
                         $labeldata = $ship->generateLabel($token,$label);
                         $labelStatus = $labeldata['label_created'];
                         if($labelStatus==1){
                             $data1 = array("label"=>$labeldata['label_url']);
                            $order->updateItem($itemid,$data1);
                         }
                   $r=array("status"=>"success","msg"=>$res);     echo json_encode($r,true);
                    }else{ $msg=$pickups['message'];  $r=array("status"=>"fail","msg"=>$msg);     echo json_encode($r,true);  }
            }else{
                $msg = $arwdata['message'];
                 $r=array("status"=>"fail","msg"=>$msg);     echo json_encode($r,true);
            }
        }else{
              $data = array("shipment_id"=>array($shipmentId) ,"status"=>"");
                $pickups = $ship->requestForPickup($token,$data);
                $pickup_status = $pickups['pickup_status'];
                if($pickup_status!=""){
                    $pickupno = $pickups['response']['pickup_token_number'];
                    $pickupdate = $pickups['response']['pickup_scheduled_date'];
                     $res = $pickups['response']['data'];
                    $data1 = array("order_current_Status"=>"Waiting For Ship","pickup_token_number"=>$pickupno,"pickup_schedule_date"=>date("Y-m-d H:i:s",strtotime($pickupdate)));
                    $order->updateItem($itemid,$data1);
                     $manifest = array("shipment_id"=>array($shipmentId));
                     $manifestdata = $ship->generateManifest($token,$manifest);
                     $manifestStatus = $manifestdata['status'];
                     if($manifestStatus==1){
                         $data1=array("manifest"=>$manifestdata['manifest_url']);
                        $order->updateItem($itemid,$data1);
                     }
                     $label = array("shipment_id"=>array($shipmentId));
                     $labeldata = $ship->generateLabel($token,$label);
                     $labelStatus = $labeldata['label_created'];
                     if($labelStatus==1){
                         $data1 = array("label"=>$labeldata['label_url']);
                        $order->updateItem($itemid,$data1);
                     }
                    $r=array("status"=>"success","msg"=>$res);     echo json_encode($r,true);
                }else{ $msg=$pickups['message']; $r=array("status"=>"fail","msg"=>$msg);     echo json_encode($r,true);  }
        }

    }else if($shipby==SITENAME){   //hyperlocal order
        $getordid=selectQuery(ORDER,"id","order_id='".$itemid."'");
        $getSubOrd=selectQuery(ORDERSUB,"item_id,product_name,quantity,product_image","order_id=".$getordid[0]['id']." AND order_current_Status<>'Canceled'");
        $products="";
          for($i=0;$i<count($getSubOrd);$i++){
             $data1 = array("order_current_Status"=>"Waiting For Ship");
            $order->updateItem($getSubOrd[$i]['item_id'],$data1);
          $products.="<tr> <td>".$getSubOrd[$i]['product_name']."</td><td>".$getSubOrd[$i]['quantity']."</td> </tr>";
         }


        $getdata = selectQuery(PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id  inner JOIN ".ORDER." as od on o.order_id= od.id inner JOIN ".VENDOR." as v on o.vendor= v.dealer_id","o.product_name,o.product_image,o.vendor_name,o.quantity,o.vendor,p.purchase_order_id,od.shippingAddr_name,od.shippingAddr_mobile,od.shippingAddr_address,od.shippingAddr_landmark,od.shippingAddr_city,od.shippingAddr_state,od.shippingAddr_country,od.shippingAddr_pincode,v.dealer_name,v.shopname,v.officeadress,v.locality,v.city,v.state,v.country,v.pin,v.personalcontactno","o.item_id =".$getSubOrd[0]['item_id']);
         $g=$getdata[0];
         $pickup="<div><b>Contact Person</b> : ".$g['dealer_name']."</div><div><b>Address</b> : ".$g['shopname']."<br>".$g['officeadress']."<br>".$g['locality'].",".$g['city']."<br>".$g['state'].",".$g['country']."-".$g['pin']."</div><div><b>Mobile No :".$g['personalcontactno']." </b></div>";
         $deliver="<div><b>Contact Person</b> : ".$g['shippingAddr_name']."</div><div><b>Address</b> : ".$g['shippingAddr_address']."<br>".$g['shippingAddr_landmark'].",".$g['shippingAddr_city']."<br>".$g['shippingAddr_state'].",".$g['shippingAddr_country']."-".$g['shippingAddr_pincode']."</div><div><b>Mobile No :".$g['shippingAddr_mobile']." </b></div>";
         $replacement_array = array(
        'siteurl' => SITEURL,
        'sitename' => SITENAME,
        'vendorname' => $g['vendor_name'],
        'order_no' => $itemid,

        'products'=>$products,
        'pickup_address' =>$pickup,
        'delivery_address'=>$deliver
       );

    $headers  = 'MIME-Version: 1.0' . "\r\n";
     $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
     $headers .= "From:".SITENAME."<".EMAIL_SENDER.">";

     $admin_email =  selectQuery(EMAILTEMPLATE,"subject,body","type='Hyperlocal Pickup' and  mail_to= 'Admin' ");
     $subject_admin=convertemailstr($replacement_array,$admin_email[0]['subject']);
     $body_admin=convertemailstr($replacement_array,$admin_email[0]['body']);

      $sentmail_admin = mail(EMAIL_SENDER, $subject_admin, $body_admin, $headers);

       $r=array("status"=>"success","msg"=>"Pickup Request Sent To Admin");     echo json_encode($r,true);
    }else{
        $r=array("status"=>"fail","msg"=>"Shipment Not Found");     echo json_encode($r,true);
    }
}

if($action=="request_generate"){
   $itemid = $_POST['itemid'];
   $reqType=$_POST['reqType'];
    $shipmentId = $order->getShipmentId($itemid);
    $shippingOrderId=$orderStatus[$i]['shipping_order_id'];
    $courierId = $order->getCourierId($itemid);
    $awbNo = $order->getAWBNO($itemid);
    $username = SHIPUSER; $pasword=SHIPPWD;
    $ship = new shiprocket($username,$pasword);
    $token = $ship->authenticate();
    $shipby=$order->getOrderItemDetatils($itemid,"shipping_by");

    if($reqType=="label"){
        if($shipby!=SITENAME){
            $label = array("shipment_id"=>array($shipmentId));
            $labeldata = $ship->generateLabel($token,$label);
            $labelStatus = $labeldata['label_created'];
            if($labelStatus==1){
                $data1 = array("label"=>$labeldata['label_url']);
                $order->updateItem($itemid,$data1);
            }
            if($labelStatus!=1){  echo "Error in Label Creation";}else{ echo 1;  }
        }else{
           ini_set('memory_limit', '128M');
            $margin_left = 1; $margin_right = 1;   $margin_top = 1; $margin_bottom = 0;

               $getdata = selectQuery(PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id  inner JOIN ".ORDER." as od on o.order_id= od.id inner JOIN ".VENDOR." as v on o.vendor= v.dealer_id","o.product_name,o.product_image,o.vendor_name,o.quantity,o.vendor,p.purchase_order_id,od.shippingAddr_name,od.shippingAddr_mobile,od.shippingAddr_address,od.shippingAddr_landmark,od.shippingAddr_city,od.shippingAddr_state,od.shippingAddr_country,od.shippingAddr_pincode,v.dealer_name,v.shopname,v.officeadress,v.locality,v.city,v.state,v.country,v.pin,v.personalcontactno","o.item_id =".$itemid);
               $g=$getdata[0];
               $pickup="<div> ".$g['dealer_name']."</div><div> ".$g['shopname']."<br>".$g['officeadress']."<br>".$g['locality'].",".$g['city']."<br>".$g['state'].",".$g['country']."-".$g['pin']."</div><div><b>Mobile No :".$g['personalcontactno']." </b></div>";
               $deliver="<div> ".$g['shippingAddr_name']."</div><div> ".$g['shippingAddr_address']."<br>".$g['shippingAddr_landmark'].",".$g['shippingAddr_city']."<br>".$g['shippingAddr_state'].",".$g['shippingAddr_country']."-".$g['shippingAddr_pincode']."</div><div><b>Mobile No :".$g['shippingAddr_mobile']." </b></div>";

               $content = '<div class="pdf-container">
                            <div class="maindiv" style="border:2px solid black;width:70%;">
                               <div style="width:50%;float:left;font-size:12px;"><div style="padding-left:1px"><b>Deliver To:  </b>
                                   '.$deliver.'
                               </div> </div>
                                <div style="width:50%;float:left;font-size:12px;"><div><b>Shipped By (If Undelivered, Return To):</b>
                                '.$pickup.'</div>
                            </div>
                            <div><hr></div>
                            <div style="font-size:10px;">Order #: '.$g['purchase_order_id'].'</div>

                            <div><hr></div>
                        </div>
                        ';

                   $content = $content . '';


            try {
              require_once("../../mpdf/vendor/autoload.php");

             $mpdf = new \Mpdf\Mpdf([
              	'mode' => 'c',
              	'margin_top' => $margin_top,
              	'margin_bottom' => $margin_bottom,
              	'margin_header' => $margin_header,
              	'margin_footer' => $margin_footer,
              ]);
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->showWatermarkImage = true;
              $stylesheet = file_get_contents('../../css/pdf.css');
                $mpdf->WriteHTML($stylesheet, 1);
              $mpdf->WriteHTML($content,2);
              $mpdf->Output('../../pdfFiles/label/label-'.$g['purchase_order_id'].'.pdf', 'F');
              $data1 = array("label"=>SITEURL."/pdfFiles/label/label-".$g['purchase_order_id'].".pdf");
                $order->updateItem($itemid,$data1);
              echo 1;
            } catch(\Mpdf\MpdfException $e) {
              echo $e->getMessage();
            }
        }
    }

     if($reqType=="manifest"){
        if($shipby!=SITENAME){
            $manifest = array("shipment_id"=>array($shipmentId));
            $manifestdata = $ship->generateManifest($token,$manifest);
            // print_r($manifestdata);
            $manifestStatus = $manifestdata['status'];
            $msg=$manifestdata['message'];
            if($manifestStatus==1){
                $data1=array("manifest"=>$manifestdata['manifest_url']);
                $order->updateItem($itemid,$data1); $status=1;
            }else if($msg=="Manifest already generated."){
                $getdata=selectQuery(ORDERSUB,"shipping_order_id","item_id =".$itemid);
                $shippingOrderId=$getdata[0]['shipping_order_id'];
                $ids = array("order_ids"=>array($shippingOrderId));
                $manifestdata1 = $ship->printManifest($token,$ids);
                $data2=array("manifest"=>$manifestdata1['manifest_url']);
                $order->updateItem($itemid,$data2);  $status=1;
            }else{$status=0;  }
            if($status!=1){ echo "Error In Manifest Creation";  }else{  echo 1; }
        }else{

        }
    }
} ?>