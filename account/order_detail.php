<?php include("../includes/configuration.php"); include("../classes/order.php"); require_once('../classes/user.php'); require_once('../classes/product.php'); $prod = new Product(); $user = new User(); $order = new Order($prod,$user);
$getCOnfig = selectQuery(CONFIG,"cancelation_charge_percentage,cut_shipping_charges_on_cancelation");
$cancelation_percent = $getCOnfig[0]['cancelation_charge_percentage']; $cutshipping = $getCOnfig[0]['cut_shipping_charges_on_cancelation']; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Order Details : <?php echo SITE_TITLE; ?></title>
    <meta name="description" content="<?php echo METADESCRIPTION; ?>">
    <meta name="keywords" content="<?php echo METAKEYWORDS; ?>">
    <link rel="canonical" href="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>">
    <?php include "../commoncss.php" ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/uploadImagepopup/cropper.css">
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/uploadImagepopup/main.css">
</head>
<body>
<div class="main-wrap"> 
    <?php include "notification_account.php" ?>    
    <?php include "../menu.php" ?>
    <div class="container pt-3"><div class="row"><div class="col-12"><ul class="breadcrumb p-0 mb-0"><li class="breadcrumb-item"><a href="<?=SITEURL;?>">Home</a></li><li class="breadcrumb-item active">Orders Details</li></ul></div></div></div>
    <div class="content pt-3 pb-4">
        <div class="container"> 
            <h2 class="mb-3 cc-fw-5 h5">Order Details</h2>
            <div class="card cc-shadow-1">
                <div class="row m-0">
                    <?php include('sidebar.php')?>
                    <div class="col-sm-12 col-md-12 col-lg-9 px-0">
                        <?php $txnid = $_GET['transid'];
                        $orderdata = $order->getOrderFullDetails($txnid);

                        $all_item_detail = ""; $free_ship = ""; 
                        //$item_detail = selectQuery(BUYER,"u_fname,u_lname,u_email","u_id=".$orderdata['user_id']); 
                        $item_detail = $order->getVendororder_item($orderdata['id']); ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="border-bottom">
                                    <div class="d-flex card-header bg-light sec-card-head justify-content-between align-items-center border-bottom-0">
                                    <div><h6 class="card-head-title mb-0 cc-primary-color">Order ID : <?=$orderdata['order_id'];?> | Orderded On <?=date("d M Y h:i a",strtotime($orderdata['order_date'])); ?></h6></div><div class="btn-actions-pane-right ml-3"> <a class="btn btn-primary btn-sm" href="<?=SITEURL;?>/print/generate_invoice.php?auth=<?=base64_encode("user");?>&bill=<?=base64_encode("purchase");?>&billno=<?=base64_encode($orderdata['order_id']); ?>" target="_blank">Print Invoice</a> <button onclick="goBack()" class="btn btn-primary btn-sm">Back</button></div>
                                    </div>
                                </div>
                                <div class="border-bottom">
                                    <div class="card-body">
                                        <div class="row">
                                        <div class="col-md-7 col-sm-12 col-12">
                                            <h6>Deliver at :</h6>
                                            <div class="row">
                                                <div class="col-md-9">
                                                    <div class="mb-1 cc-fw-5">
                                                        <?php echo $orderdata['shipping_name']."</div><div class='text-muted'><span class='cc-fw-5'>Contact No : </span>".$orderdata['shipping_mobile']."</div><div class='text-muted'>".$orderdata['address']." ".$orderdata['landmark'].", ".$orderdata['city'].", ".$orderdata['state'].", ".$orderdata['country']." - ".$orderdata['pincode']; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                         <div class="col-md-5 col-sm-12 col-12">
                                            <table class="table table-bordered">
                                                <? if($orderdata['discount_amount']!=0){?>
                                                <tr><td><b>Total Order Amount</b></td><td><?=$orderdata['total_bill_amount']; ?></td></tr>
                                                <tr><td><b>Discount</b></td><td><?=$orderdata['discount_amount']; ?></td></tr>
                                                <tr><td><b>Total Amount</b></td><td><?=$orderdata['payable_amount']; ?></td></tr>
                                                <?}else{
                                                  ?><tr><td><b>Total Amount</b></td><td><?=$orderdata['payable_amount']; ?></td></tr>     <?
                                                }  ?>

                                            </table>
                                         </div>
                                         </div>
                                    </div>
                                </div>
                            </div>
                            <div id="itembox" class="itembox col-md-12">
                                <? for($i=0;$i<count($item_detail);$i++){
                                $order_item = $item_detail[$i];
                                $itemid = $order_item['item_id'];
                                $status = $order_item['order_current_Status'];
                                if($order_item['product_image']){
                                $img = SITEURL."/img/order_img/".$order_item['product_image'];
                                } else{ $img = SITEURL."/img/projectimage/product-default.png"; }
                                if($order_item['isFreeShipping'] == 1 && $order_item['shipping_charges'] != 0 ){ $free_ship = $orderdata['Freeshippingreason']; } ?>
                                <div class="ord-succ-det-list">
                                    <div class="card-body border-bottom">
                                        <div class='row order-succ-row m-0'>
                                            <div class='col-3 col-sm-3 col-md-2 col-lg-2 col-xl-2 pl-0 pr-3 pr-md-0'><div class="pro-list-thumb"><img src='<?=$img;?>' alt="order-thumb" class="rounded img-fluid img-thumbnail"></div></div>
                                            <div class="col-9 col-sm-9 col-md-10 col-lg-10 col-xl-10 px-0">
                                                <div class="row m-0">
                                                    <div class="col-md-6 col-lg-7 col-xl-8 px-0">
                                                        <h2 class="cc-fw-5 h6"><?=$order_item['product_name'];?></h2>
                                                        <?php if($free_ship != ""){?>
                                                        <div class='alert alert-success d-inline-block py-1 px-2 my-1'><?=$free_ship;?></div><?php } ?>
                                                        <div class="mb-1">Quantity : <?=$order_item['quantity'];?></div>
                                                        <div class="mb-1">HSN Code : <? if($order_item['hsn_code'] == ""){ echo "NA"; } else { echo $order_item['hsn_code'];} ?></div>
                                                        <div class="mb-1">SKU Code : <? if($order_item['sku'] == ""){ echo "NA"; } else { echo $order_item['sku'];} ?></div>
                                                        <?php if($status == "Canceled"){ ?>
                                                        <div class="mb-1">Cancel Reason : <?php echo $order_item['cancel_reason'] ?></div>
                                                        <div class="mb-1">Cancelled by : <?php echo $order_item['cancelled_by'] ?></div>
                                                        <?php } ?>  
                                                        <? if($order_item['discount_code']!=""){ ?>
                                                        <div class='alert alert-success mb-0 py-1 px-2 d-inline-block'>
                                                        Promocode '<?=$order_item['discount_code'];?>' Applied</div>
                                                        <? } ?>
                                                        <?php if($order_item['order_current_Status'] == "Generated" || $order_item['order_current_Status'] == "Waiting For Ship"  ||  $order_item['order_current_Status'] == "Shipped"){ ?>
                                                        <div class="mt-1 <?=($order_item['expected_delivery']==""?"d-none":"");?>">Expected Delivery : <?=$order_item['expected_delivery']; ?></div>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="col-md-6 col-lg-5 col-xl-4 pl-0 pl-md-3 pr-0 tot-amt-tab">
                                                        <table>
                                                            <tr><td class="pb-1">Taxable</td><td class="pl-3"><i class="fa fa-inr"></i> <?=$order_item['taxable_without_gst'];?></td></tr>
                                                            <? if($order_item['discount_code']!=""){ ?>
                                                            <tr><td class="pb-1">Discount</td><td class="pb-1 pl-3"><i class="fa fa-inr"></i> -<?=$order_item['discount_amount'];?></td></tr>
                                                            <? } if($order_item['cgst1']!="0.00"){
                                                            ?><tr class='gst-ship-col'><td class="pb-1">CGST<small>(<?=$order_item['cgst1'];?>% )</small></td><td class="pl-3"><i class="fa fa-inr"></i> <?=$order_item['cgst2'];?></td></tr>
                                                            <tr class='gst-ship-col'><td class="pb-1">SGST<small>(<?=$order_item['sgst1'];?>%)</small></td><td class="pl-3"><i class="fa fa-inr"></i> <?=$order_item['sgst2'];?></td></tr><?
                                                            }else{
                                                            ?><tr class='gst-ship-col'><td class="pb-1">IGST<small>(<?=$order_item['igst1'];?>%)</small></td><td class="pl-3"><i class="fa fa-inr"></i> <?=$order_item['igst2'];?></td></tr>
                                                            <? } ?> 

                                                            <tr class="h6"><td>Total</td><td class="pl-3"><i class="fa fa-inr"></i> <?=$order_item['total_payable'];?></td></tr>
                                                            <?php $shipping_charges = ($order_item['isFreeShipping']==1?0:$order_item['shipping_charges']);
                                                            if($cutshipping==0){
                                                            $cancelation = round(($order_item['total_with_gst']/100)*$cancelation_percent);
                                                            }else{
                                                               $cancelation = round((($order_item['total_with_gst']/100)*$cancelation_percent)  + $shipping_charges);
                                                            } ?>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>                                    
                                        </div>
                                    </div>
                                    <div class="col-md-12 py-1 order-status-col">
                                        <div class="row d-flex align-items-center py-1">
                                            <div class="col-12 col-sm-12 col-md-6 mb-2 mb-md-0">
                                                <span class="mr-2">Current Status : <span class="badge badge-success"><?=$status;?></span></span>  
                                                <? if($order->getOrderItemDetatils($itemid,"return_request_date")==""){?>
                                                <button type="button" class="track-ord-status btn btn-link btn-xs p-0 text-link" onclick="track('<?=$order_item['shipping_shipment_id']; ?>','tracking-details<?=$i;?>')">View More</button>
                                                <?php } else{ ?>
                                                <button type="button" class="track-ord-status btn btn-link btn-xs p-0 text-link" onclick="trackReturn('<?=$order_item['return_shipment_id']; ?>','tracking-details<?=$i;?>')">View More</button>
                                                <?php } ?>
                                            </div>
                                            <div class="col-12 col-sm-12 col-md-6 pl-md-0 ord-prt-invoice">
                                                <? $cancel_array = array('Generated','New Order','New','Invoiced','Waiting For Ship','Picked Up','Ready To Ship','Pickup Scheduled','Pickup Queue','Pickup Rescheduled','Pickup Error','Pickup Exception','Out For Pickup','Awb Assigned','Label Generated','Manifest Generated','Shipped','Shipped','Out for Delivery','In Transit','Reached Destination Hub','Misrouted','Lost','Undelivered','Delayed','Pending','Rto Acknowledged','Rto Initiated','Rto Delivered','Rto Ofd','Rto Ndr');
                                                if($order_item['is_cancellation_avail']==1&& ( in_array($status,$cancel_array))){?><button type="button" class="btn btn-primary btn-sm" onclick="confirmCancel('<?=$order_item['shipping_order_id']; ?>','<?=$txnid; ?>','<?=$order_item['item_id']; ?>','<?=$cancelation.($cutshipping==1?"":""); ?>')">Cancel Item</button><?} ?>
                                                <? if($order_item['return_valid_till']!=""){if(($status=="Delivered"&&date("Y-m-d H:i:s")<=date("Y-m-d H:i:s",strtotime($order_item['return_valid_till'])))){?><button type="button" class="btn btn-primary btn-sm" onclick="confirmReturn('<?=$order_item['item_id']; ?>')">Return Item</button><? } } ?>

                                            </div>
                                        </div>
                                    </div>
                                    <div id="tracking-details<?=$i;?>" class="tracking-details cc-display-none"></div>
                                    <div class="col-12"> 
                                        <div><? if($order->getOrderItemDetatils($itemid,"refund_status")=="1"){ ?> <div class="alert success">Amount of Rs <?=(($orderdata['payment_mode']!="COD"&&$order_item['order_current_Status']=="Canceled")||$order_item['order_current_Status']!="Canceled"?$order->getOrderItemDetatils($itemid,"refundable_amount"):0.00); ?> Initiated <?=($order->getOrderItemDetatils($itemid,"refund_id")!=""?" with refund ID ".$order->getOrderItemDetatils($itemid,"refund_id"):""); ?><?=($order->getOrderItemDetatils($itemid,"refund_date")!="0000-00-00 00:00:00"?" on ".date("d M Y H:i:s",strtotime($order->getOrderItemDetatils($itemid,"refund_date"))):""); ?> <?=($order->getOrderItemDetatils($itemid,"refund_id")!=""?"<b>(Refund Status: ".$order->getOrderItemDetatils($itemid,"refund_response").")</b>":""); ?></div> <? } ?> </div>
                                    </div>
                                </div>
                                <? } ?>
                                <div>
                                    <div class="col-md-4 offset-md-8">
                                        <!--<table class="table">
                                            <?php echo "<tr>
                                            <td class='pl-0 pt-2 pb-2 border-top-0'>Total Taxable</td><td class='text-right pr-0 pt-2 pb-2 border-top-0'>".$orderdata['total_taxable_amount']."</td></tr><tr><td class='pl-0 pt-2 pb-2'>Total GST</td><td class='text-right pr-0 pt-2 pb-2'>".$orderdata['total_gst']."</td></tr>"  ?>
                                            <tr><td class='pl-0 pt-2 pb-2'>Shipping Charges</td><td class='text-right pr-0 pt-2 pb-2'> <?php if($orderdata['isFreeShipping'] == "0") { echo $orderdata['total_shipping_charges']; } else { echo "0"; echo "<del>".$orderdata['total_shipping_charges']."</del></td>";  }  ?> </tr>
                                            <tr class="lead cc-fw-5"><td class='pl-0 pb-0'>Total</td><td class='text-right pr-0 pb-0'><?php echo $orderdata['payable_amount']; ?></td></tr>
                                        </table>-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
    </div>   
</div>
<div class="modal confirm-modal px-4" id="confirm_popup">
    <div class="modal-dialog mt-5">
        <div class="modal-content">
            <div class="modal-body p-3">
                <h5 id="confirm_message" class="cc-fw-5 mb-3">&nbsp;</h5>
                <div class="h6 mt-2 mb-3">Order Cancellation Charges are <i class="fa fa-inr"></i> <b id="canc_charge"></b> </div>
                <form>
                    <div class="custom-control custom-radio mb-2"><input class="custom-control-input" id="prc-to-high" name="cancel_reason" type="radio" value="Price Too High"><label class="custom-control-label" for="prc-to-high">Price Too High</label></div>
                    <div class="custom-control custom-radio mb-2"><input class="custom-control-input" id="ord-mistake" name="cancel_reason" type="radio" value="Ordered By Mistake"><label class="custom-control-label" for="ord-mistake">Ordered By Mistake</label></div>
                    <div class="custom-control custom-radio mb-4"><input class="custom-control-input" id="ord-can-other" name="cancel_reason" type="radio" value="Other"><label class="custom-control-label" for="ord-can-other">Other</label></div>
                    <button type="button" class="btn btn-primary px-3" id="confirm_ok">Yes</button>
                    <button type="button" class="btn btn-secondary px-3" data-dismiss="modal">No</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal return-modal px-4" id="return_popup">
    <div class="modal-dialog mt-5">
        <div class="modal-content">
            <div class="modal-body p-4">
                <h5 id="confirm_message_return" class="cc-fw-5 mb-3">&nbsp;</h5>
                <h6 class="mt-2 mb-3">Reason For Retutning Order</h6>
                <form>
                    <div class="custom-control custom-radio mb-2">
                      <input class="custom-control-input" id="wrg-pro-del" name="return_reason" type="radio" value="Wrong Product Delivered"><label class="custom-control-label" for="wrg-pro-del">Wrong Product Delivered</label>
                    </div>
                    <div class="custom-control custom-radio mb-2">
                        <input class="custom-control-input" id="qty-satisfact" name="return_reason" type="radio" value="Quality Not Satisfactory"><label class="custom-control-label" for="qty-satisfact">Quality Not Satisfactory</label>
                    </div>
                    <div class="custom-control custom-radio mb-2">
                        <input class="custom-control-input" id="damaged-product" name="return_reason" type="radio" value="Damaged Product">
                        <label class="custom-control-label" for="damaged-product">Damaged Product</label>
                    </div>
                    <div class="custom-control custom-radio mb-2">
                        <input class="custom-control-input" id="prc-to-high1" name="return_reason" type="radio" value="Price Too High">
                        <label class="custom-control-label" for="prc-to-high1">Price Too High</label>
                    </div>
                    <div class="custom-control custom-radio mb-4">
                        <input class="custom-control-input" id="ord-ret-other" name="return_reason" type="radio" value="Other">
                        <label class="custom-control-label" for="ord-ret-other">Other</label>
                    </div>
                    <div>
                    <div class="custom-control custom-radio mb-4">
                        <input class="custom-control-input" id="term-read" name="term_read" type="checkbox" value="1">
                        <label class="custom-control-label" for="term-read">I have read all <a href="">terms and condition</a> ,And item is fit for return.</label>
                    </div>
                    </div>
                    <button type="button" class="btn btn-primary px-3" id="return_ok">Yes</button>
                    <button type="button" class="btn btn-secondary px-3" data-dismiss="modal">No</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include "../footer.php"; ?>
<?php include "profilejs.php"; ?>
<script>
function track(shipmentId,divid){
    var info={shipmentId:shipmentId,action:"trackOrder"};
    $.ajax({
        type: "POST",
        url: "<?=SITEURL; ?>/ajax/order_ajax.php",
        data: info,
        success: function(response){
            if(response){
                jssonarr = JSON.parse(response);
                str='<div class="border-top">';
                for(i=0;i<jssonarr.length;i++){
                    str+='<div class="col-sm-12 py-2 border-bottom order-ship-status"><span class="small">'+jssonarr[i]["date"]+'</span><div>'+jssonarr[i]["activity"]+'</div> </div>'
                }
                str+='</div>';
                $("#"+divid).html(str)
            }
        }
    });
}
function confirmCancel(shipmentId,txnId,itemId,canc_charge){ confirm_alertbox(shipmentId,txnId,itemId,canc_charge) }
function confirmReturn(itemId){ confirm_alertbox1(itemId) }
function confirm_alertbox(id,txnId,itemId,canc_charge){
    $("#canc_charge").html(canc_charge);
    $("#confirm_popup").modal("show");
    $("#confirm_popup").find(".modal-dialog").addClass("order-cancel-popup");
    $("#confirm_message").html("Do You Want To Cancel This Item?");
    $("#confirm_ok").attr("onclick", "cancelOrder('"+id+"','"+txnId+"','"+itemId+"')");
}
function confirm_alertbox1(itemId){
    $("#return_popup").modal("show");
    $("#return_popup").find(".modal-dialog").addClass("order-cancel-popup");
    $("#confirm_message_return").html("Do You Want To Return This Item?");        
    $("#return_ok").attr("onclick", "returnOrder('"+itemId+"')");
}
function returnOrder(itemId){
    return_reason = $("input[name='return_reason']:checked"). val();
    terms = $("#term-read:checked").val();
    if(return_reason  == "" || return_reason == undefined ){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please Select Reason For Returning").delay(1000).fadeOut();
    } else if(terms!=1){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please Accept and Verify Terms And Conditions").delay(1000).fadeOut();

    } else{
        var info = {itemId:itemId,return_reason:return_reason,action:"returnOrder"};
        $.ajax({
            type: "POST",url: "<?=SITEURL; ?>/ajax/order_ajax.php",data:info,
            success: function(response){
                $("#return_popup").modal("hide");
                if(response){
                    jsondata = JSON.parse(response);
                    if(jsondata['status']=="failed"){   $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html(jsondata['message']).delay(1000).fadeOut();
                    } else{
                        $(".itembox").load(" .itembox"); $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html(jsondata['message']).delay(1000).fadeOut();
                    }
                }
            }
        });
    }
}
function cancelOrder(shippingOrderId,txnId,itemId){
    cancel_reason = $("input[name='cancel_reason']:checked"). val();
    if(cancel_reason  == "" || cancel_reason == undefined ){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please select Cancellation Reason").delay(1000).fadeOut();
    }else {
        var info ={shippingOrderId:shippingOrderId,txnId:txnId,itemId:itemId,cancel_reason:cancel_reason,action:"cencelOrder",canceled_by:'Buyer'};
        $.ajax({
            type: "POST",
            url: "<?=SITEURL; ?>/ajax/order_ajax.php",
            data:info,
            success: function(response){
                $("#confirm_popup").modal("hide");
                if(response){
                    jsondata = JSON.parse(response);
                    if(jsondata['status']=="failed"){
                        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Your Order "+jsondata['status']+"").delay(1000).fadeOut();
                    } else{
                        $(".itembox").load(" .itembox");
                        $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Your Order "+jsondata['status']+" Successfully").delay(1000).fadeOut();
                    }
                }
            } 
        });
    }
}   
$(".track-ord-status").click(function(){ $(this).parents(".order-status-col").next().slideToggle(); });
function goBack(){ window.history.back(-1) }
function trackReturn(shipmentId,divid){
    var info = {shipmentId:shipmentId,action:"trackReturnOrder"};
    $.ajax({
        type: "POST",
        url: "<?=SITEURL; ?>/ajax/order_ajax.php",
        data: info,
        success: function(response){
            if(response){
                jssonarr = JSON.parse(response);
                str = '<div class="row m-0">';
                for(i=0;i<jssonarr.length;i++){
                    str+='<div class="col-sm-12 py-2 border-bottom"><span class="small">'+jssonarr[i]["date"]+'</span><div>'+jssonarr[i]["activity"]+'</div> </div>'
                }
                str+='</div>';
                $("#"+divid).html(str)
            }
        }
    });
}
</script>
</body>
</html>