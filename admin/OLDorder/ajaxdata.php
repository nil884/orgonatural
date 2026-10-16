<?php include("../../includes/configuration.php");
include("../../classes/order.php"); require_once('../../classes/user.php'); require_once('../../classes/product.php');
$prod = new Product();  $user = new User(); $order = new Order($prod,$user);
if($action == "get_order_item_detail"){ $orderdata= $order->getVendor_seprate_order($itemid);  ?>
<div class="modal-header">
    <h5 class="mb-0 modal-title"><?php 
    if($orderdata[0]['order_current_Status'] == "Generated"){ echo "New"; }
    else if($orderdata[0]['order_current_Status'] == "Waiting For Ship"){ echo "Waiting For Pickups";}
    else if($orderdata[0]['order_current_Status'] == "Shipped"){ echo "Shipped"; }
    else{ echo $orderdata[0]['order_current_Status']; }    
    ?> order details of <?php echo $orderdata[0]['purchase_order_id'] ?></h5>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
    <div class='item_detail row'>            
    <?php $variationon = $orderdata[0]['variation_on'];
    if($orderdata[0]['product_image']){
        $img = SITEURL."/img/order_img/".$orderdata[0]['product_image'];
    }else{
        $img = SITEURL."/img/projectimage/product-default.png";
    }
    
     if($orderdata[0]['isFreeShipping'] ==1  && $orderdata[0]['shipping_charges'] != 0 ){ $free_ship = "<div class='alert alert-success my-1 mb-0 py-1 px-2 d-inline-block'>".$orderdata[0]['Freeshippingreason']."</div>"; } 
    if($orderdata[0]['order_current_Status'] == "Canceled") { $refund = "<div class='mb-1 text-danger border border-danger py-1 px-2 rounded d-inline-block'>Cancel Reason : ".$orderdata[0]['cancel_reason']."</div><br><div class='mb-1'> Cancelled by : ".$orderdata[0]['cancelled_by']."</div>" ;  } else { $refund = ""; }                             
    echo "<div class='col-md-12 col-lg-8'><div class='row'><div class='col-md-3 col-lg-3'><img src='".$img."' class='img-fluid img-thumbnail'></div>
    <div class='col-md-9 col-lg-9 mt-3 mt-md-0'>
    <h6 class='text-primary'>".$orderdata[0]['display_product_name'];
    if($variationon!=""){
        $variationonarr = explode("|",$variationon); 
        $variativaluearr = explode("|",$orderdata[0]['variation_values']);
        for($v=0;$v<count($variationonarr);$v++){ ?> 
        <span><?=$variationonarr[$v]; ?>:<?=$variativaluearr[$v]; ?></span> <? }
    }; echo"</h6>";
    echo "<div class='mb-1'>Order Status : ".$orderdata[0]['order_current_Status']."</div>".$refund;
    echo "<div class='mb-1'>Order Date : "; echo date("d M Y h:i A",strtotime($orderdata[0]['purchase_date']))."</div>";
    if($orderdata[0]['order_current_Status'] == "Delivered"){
        echo "<div class='mb-1'>Delivery Date : ".date("d M Y h:i A",$orderdata[0]['delivered_on'])." </div>";      
    }     
    echo $free_ship; 
    echo "<div class='mb-1'>Quantity : ".$orderdata[0]['quantity']."</div>"; ?>
    <? if($orderdata[0]['discount_code']!=""){ ?>
    <div class='alert alert-success mt-1 mb-2 mb-0 py-1 px-3 d-inline-block'>
    Promocode '<?=$orderdata[0]['discount_code'];?>' Applied</div>
    <? } ?>
    <table>
        <tr><td class="pb-1 pr-4">Taxable</td><td class="pb-1"><i class="fa fa-inr"></i> <?=$orderdata[0]['taxable_without_gst'];?></td></tr>
        <? if($orderdata[0]['discount_code']!=""){ ?>
        <tr><td class="pb-1">Discount </td><td class="pb-1"><i class="fa fa-inr"></i> -<?=$orderdata[0]['discount_amount'];?></td></tr>
        <? } if($order_item['cgst1']!="0.00"){ ?>
        <tr><td class="pb-1 pr-4">CGST <small>(<?=$orderdata[0]['cgst1'];?>% )</small></td><td class="pb-1"><i class="fa fa-inr"></i> <?=$orderdata[0]['cgst2'];?></td></tr>
        <tr><td class="pb-1 pr-4">SGST <small>(<?=$orderdata[0]['sgst1'];?>%)</small></td><td class="pb-1"><i class="fa fa-inr"></i> <?=$orderdata[0]['sgst2'];?></td></tr>
        <? } else{
        ?><tr><td class="pb-1 pr-4">IGST <small>(<?=$orderdata[0]['igst1'];?>%)</small></td><td class="pb-1"><i class="fa fa-inr"></i> <?=$orderdata[0]['igst1'];?></td></tr>
        <? } ?> 
        <tr><td class="pb-1 pr-4">Shipping Charges </td><td class="pb-1"><i class="fa fa-inr"></i> <? if($orderdata[0]['isFreeShipping'] == 1 && $orderdata[0]['shipping_charges'] != 0 ){ echo "0"; echo "<del>".$orderdata[0]['shipping_charges']."</del>"; } else{ echo $orderdata[0]['shipping_charges']; };?></td></tr>
        <tr><td class="pr-4">Total <?=($orderdata[0]['payment_mode']=="COD"?"Payable":"Paid"); ?> </td><td><i class="fa fa-inr"></i> <?=$orderdata[0]['total_payable'];?></td></tr>
    </table>                                         
    <?php echo "</div></div></div>";
    echo "<div class='col-md-12 col-lg-4 mt-3 mt-lg-0'><h6 class='text-primary'>Shipping Address</h6>".$orderdata[0]['shippingAddr_type'].'<div>'.$orderdata[0]['shippingAddr_address'].' '.$orderdata[0]['shippingAddr_landmark'].",".$orderdata[0]['shippingAddr_city'].", ".$orderdata[0]['shippingAddr_state'].", ".$orderdata[0]['shippingAddr_country']." - ".$orderdata[0]['shippingAddr_pincode']."</div><div class='mt-1'>Phone Number - ".$orderdata[0]['shippingAddr_mobile']."</div></div>";                      
    "</div></div>";
} ?>