<?php include("../../includes/configuration.php"); require_once "../../classes/shiprocket.php";
include("../../classes/order.php"); require_once('../../classes/user.php'); require_once('../../classes/product.php');
$prod = new Product();  $user = new User(); $order = new Order($prod,$user);
if($action == "get_order_item_detail"){
$orderdata= $order->getVendor_seprate_order($itemid); ?>
<div class="modal-header">          
    <h5 class="mb-0 modal-title"><?php 
    if($orderdata[0]['order_current_Status'] == "Generated"){ echo "New"; }
    else if($orderdata[0]['order_current_Status'] == "Waiting For Ship"){ echo "Waiting For Pickups"; }
    else if($orderdata[0]['order_current_Status'] == "Shipped"){ echo "Shipped"; }
    else{ echo $orderdata[0]['order_current_Status'];} ?> order details of <?php echo $orderdata[0]['purchase_order_id'] ?></h5>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body chq">
    <div class='row'>
        <?php if($orderdata[0]['product_image']){
            $img = SITEURL."/img/order_img/".$orderdata[0]['product_image'];
        } else{
            $img = SITEURL."/img/projectimage/product-default.png";
        }
        if($orderdata[0]['order_current_Status'] == "Canceled"){ $refund = "<div class='mb-1 text-danger border border-danger py-1 px-2 rounded d-inline-block'>Cancel Reason : ".$orderdata[0]['cancel_reason']."</div><br><div class='mb-1'> Cancelled by : ".$orderdata[0]['cancelled_by']."</div>" ; } else { $refund = ""; } 
        echo "<div class='col-md-4 mb-3 text-center'>"
        . "<img src='".$img."' class='mw-100 mb-2' alt='ord-thumb' height='130'></div>"
                . "<div class='col-md-8'><h6 class='text-primary'>".
        $orderdata[0]['display_product_name']."</h6>
        <div class='mb-1'>Order Status : ".$orderdata[0]['order_current_Status']."</div>".$refund."<div class='mb-1'>";
        $variationon = $orderdata[0]['variation_on'];
        if($variationon!=""){
            $variationonarr = explode("|",$variationon); 
            $variativaluearr = explode("|",$orderdata[0]['variation_values']);
            for($v=0;$v<count($variationonarr);$v++){ ?>
            <span class="mr-2"><?=$variationonarr[$v]; ?> : <?=$variativaluearr[$v]; ?></span> <? } echo "";
        }
        echo '<div class="mb-1">Order Date : '; echo date("d M Y h:i A",strtotime($orderdata[0]['purchase_date']))."</div>". "<span class='mr-2'>Qunatity : ".$orderdata[0]['quantity']."</span>";
        echo "<div class='h6 mt-1 mb-0'> Total Price  : <i class='fa fa-inr'></i>". $orderdata[0]['quantity'] *($orderdata[0]['vendor_per_item_price_withoutgst']+($orderdata[0]['vendor_per_item_price_withoutgst'] * $orderdata[0]['tax_percentage']/100))."</div>"; ?>
    </div>
    </div>
   </div>

    <div class="row">
        <div class="order-status-col col-md-12 border-top pt-2 mt-3">  
            <span class="mr-2">Current Status - <?=$orderdata[0]['order_current_Status'];?></span><button type="button" class="track-ord-status btn btn-link btn-xs p-0 text-link" onclick="track('<?=$orderdata[0]['shipping_shipment_id']; ?>','tracking-details')">View More</button>          
            <div id="tracking-details" class="tracking-details border-top mt-2 row cc-display-none"></div>
        </div>
    </div>
</div>
<?php }  
if($action=="request_pickup"){
    $itemid = $_POST['itemid'];
    $shipmentId = $order->getShipmentId($itemid);
    $courierId = $order->getCourierId($itemid);
    $awbNo = $order->getAWBNO($itemid);
    $username = SHIPUSER; $pasword=SHIPPWD;
    $ship = new shiprocket($username,$pasword);
    $token = $ship->authenticate();

    if($shipmentId!=""){
        if($awbNo==""){
            $jsondata = array("shipment_id"=>$shipmentId,"courier_id"=>$courierId ,"status"=>"");
            $arwdata = $ship->createAWBNo($token,$jsondata);
             $awb_status=$arwdata['awb_assign_status'];

            if($awb_status){
                $awbno = $arwdata['response']['data']['awb_code'];
                $courier_id = $arwdata['response']['data']['courier_company_id'];
                $courier_name = $arwdata['response']['data']['courier_name'];
                $data = array("shipping_awb_no"=>$awbno);
                $order->updateItem($itemId,$data);
                  $data = array("shipment_id"=>array($shipmentId) ,"status"=>"");
                    $pickups = $ship->requestForPickup($token,$data);

                    $pickup_status = $pickups['pickup_status'];
                    if($pickup_status!=""){
                        $pickupno = $pickups['pickup_token_number'];
                        $pickupdate = $pickups['response']['pickup_scheduled_date']['date'];
                        $data1 = array("order_current_Status"=>"Waiting For Ship","pickup_token_number"=>$pickupno,"pickup_schedule_date"=>date("Y-m-d H:i:s",strtotime($pickupdate)));
                        $order->updateItem($itemId,$data1);
                         $manifest = array("shipment_id"=>array($shipmentId));
                         $manifestdata = $ship->generateManifest($token,$manifest);
                         $manifestStatus = $manifestdata['status'];
                         if($manifestStatus==1){
                             $data1=array("manifest"=>$manifestdata['manifest_url']);
                            $order->updateItem($itemId,$data1);
                         }
                         $label = array("shipment_id"=>array($shipmentId));
                         $labeldata = $ship->generateLabel($token,$label);
                         $labelStatus = $labeldata['label_created'];
                         if($labelStatus==1){
                             $data1 = array("label"=>$labeldata['label_url']);
                            $order->updateItem($itemId,$data1);
                         }
                        echo 1;
                    }else{ $msg=$pickups['message']; echo $msg; }
            }else{
                $msg = $arwdata['message'];
                echo $msg;
                exit();
            }
        }else{
              $data = array("shipment_id"=>array($shipmentId) ,"status"=>"");
                $pickups = $ship->requestForPickup($token,$data);
                   print_r($pickups);
                $pickup_status = $pickups['pickup_status'];
                if($pickup_status!=""){
                    $pickupno = $pickups['pickup_token_number'];
                    $pickupdate = $pickups['response']['pickup_scheduled_date']['date'];
                    $data1 = array("order_current_Status"=>"Waiting For Ship","pickup_token_number"=>$pickupno,"pickup_schedule_date"=>date("Y-m-d H:i:s",strtotime($pickupdate)));
                    $order->updateItem($itemId,$data1);
                     $manifest = array("shipment_id"=>array($shipmentId));
                     $manifestdata = $ship->generateManifest($token,$manifest);
                     $manifestStatus = $manifestdata['status'];
                     if($manifestStatus==1){
                         $data1=array("manifest"=>$manifestdata['manifest_url']);
                        $order->updateItem($itemId,$data1);
                     }
                     $label = array("shipment_id"=>array($shipmentId));
                     $labeldata = $ship->generateLabel($token,$label);
                     $labelStatus = $labeldata['label_created'];
                     if($labelStatus==1){
                         $data1 = array("label"=>$labeldata['label_url']);
                        $order->updateItem($itemId,$data1);
                     }
                    echo 1;
                }else{ $msg=$pickups['message']; echo $msg; }
        }

    }else{
        echo "Shipment Not Found";
    }
}
if($action=="request_generate"){
   $itemid = $_POST['itemid'];
   $reqType = $_POST['reqType'];
    $shipmentId = $order->getShipmentId($itemid);
    $shippingOrderId=$orderStatus[$i]['shipping_order_id'];
    $courierId = $order->getCourierId($itemid);
    $awbNo = $order->getAWBNO($itemid);
    $username = SHIPUSER; $pasword=SHIPPWD;
    $ship = new shiprocket($username,$pasword);
    $token = $ship->authenticate();
    if($reqType=="label"){
         $label = array("shipment_id"=>array($shipmentId));
         $labeldata = $ship->generateLabel($token,$label);
         $labelStatus = $labeldata['label_created'];
         if($labelStatus==1){
             $data1 = array("label"=>$labeldata['label_url']);
            $order->updateItem($itemid,$data1);
         }
         if($labelStatus!=1){ echo "Error in Label Creation"; }else{ echo 1; }
    }
    if($reqType=="manifest"){
        $manifest = array("shipment_id"=>array($shipmentId));
        $manifestdata = $ship->generateManifest($token,$manifest);
        // print_r($manifestdata);
        $manifestStatus = $manifestdata['status'];
        $msg=$manifestdata['message'];
        if($manifestStatus==1){
        $data1=array("manifest"=>$manifestdata['manifest_url']);
        $order->updateItem($itemid,$data1); $status=1;
        }else if($msg=="Manifest already generated."){
            $getdata = selectQuery(ORDERSUB,"shipping_order_id","item_id =".$itemid);
            $shippingOrderId = $getdata[0]['shipping_order_id'];
            $ids = array("order_ids"=>array($shippingOrderId));
            $manifestdata1 = $ship->printManifest($token,$ids);
            $data2 = array("manifest"=>$manifestdata1['manifest_url']);
            $order->updateItem($itemid,$data2); $status = 1;
        }else{
            $status=0;
        } if($status!=1){
            echo "Error In Manifest Creation";
        } else{
            echo 1;
        }
    }
} ?>