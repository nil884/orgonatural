<? include("../../includes/configuration.php");
include("../../classes/order.php"); require_once('../../classes/user.php'); require_once('../../classes/product.php');
$prod = new Product(); $user = new User(); $order = new Order($prod,$user); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo SITE_TITLE; ?> : Inprocess Orders</title>
    <? include "../commoncss.php"; ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/dataTable/dataTables.bootstrap4.min.css">
</head>
<body>
<div class="page-body-wrapper">
    <? include '../header.php'; ?>
    <? include '../sidebar.php'; ?>
    <div class="main-panel"> 
        <div class="dashbody">
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Inprocess Order</h2></div></div>
                <div class="card-body">
                    <ul class="nav nav-tabs mb-3" role="tablist">
                        <li class="nav-item"><a class="nav-link active" id="nav-new-tab" data-toggle="tab" href="#nav-new" role="tab" aria-controls="nav-new" aria-selected="true">New Orders (<?=$order->getVendorOrdersCount($vendor,"'Generated','New Order','New','Invoiced'"); ?>)</a></li>
                        <li class="nav-item"><a class="nav-link" id="nav-pickup-tab" data-toggle="tab" href="#nav-pickup" role="tab" aria-controls="nav-pickup" aria-selected="true">Waiting For Pickups (<?=$order->getVendorOrdersCount($vendor,"'Waiting For Ship','Picked Up','Ready To Ship','Pickup Scheduled','Pickup Queue','Pickup Rescheduled','Pickup Error','Pickup Exception','Out For Pickup','Awb Assigned','Label Generated','Manifest Generated'"); ?>)</a></li>
                        <li class="nav-item"><a class="nav-link" id="nav-Shipped-tab" data-toggle="tab" href="#nav-Shipped" role="tab" aria-controls="nav-Shipped" aria-selected="false">Shipped Order (<?=$order->getVendorOrdersCount($vendor,"'Generated','New Order','New','Invoiced','Waiting For Ship','Picked Up','Ready To Ship','Pickup Scheduled','Pickup Queued','Pickup Queue','Pickup Rescheduled','Pickup Error','Pickup Exception','Out For Pickup','Awb Assigned','Label Generated','Manifest Generated','Delivered','Fulfilled','Archived','Partial Delivered','Cancellation Request','Cancellation Requested','Canceled','Return Requested','Return Initiated','Return Pending','Return Pickup Queued','Return Pickup Error','Return In Transit','Return Pickup Generated','Return Cancellation Requested','Return Pickup Cancelled','Return Pickup Resheduled','Return Pickedup','Return Delivered','Returned','Return Rejected','Return Cancelled','Rto Acknowledged','Rto Initiated','Rto Delivered','Rto Ofd','Rto Ndr','failure','Initiated',''","NOT"); ?>)</a></li>
                    </ul>
                    <div class="tab-content tablereload" id="nav-tabContent alltab">
                        <div class="tab-pane fade show active" id="nav-new" role="tabpanel" aria-labelledby="nav-new-tab">
                            <table class="display table table-bordered neworder-table mb-0 w-100"  id="inprocess" >
                                <thead><tr><th>#</th><th>Order Date</th><th>Order ID</th><th>Buyer Name</th><th>Amount</th><th>Status</th><!--<th>Invoice</th>--><th>Action</th></tr></thead>
                                <tbody>
                                    <?php $orderdata = $order->getVendorOrders($vendor,"'Generated','New Order','New','Invoiced'");
                                    if(count($orderdata)){
                                    for($j=0;$j<count($orderdata);$j++){
                                        $row=$orderdata[$j];

                                    $cnt=$j+1; ?>
                                    <tr>
                                        <td><?=$cnt; ?></td>
                                        <td><?=date("d M Y h:i A",strtotime($row['purchase_date'])) ?></td>
                                        <td class="text-primary cc-cursor-pointer"><a href="order_details.php?transid=<? echo $row['transaction_id']; ?>"> <?php echo $row['order_id']; ?></a></td>
                                            <!--<td><?=$row['display_product_name']; ?><br>
                                        <? $variationon=$row['variation_on'];
                                        if($variationon!=""){
                                        $variationonarr=explode("|",$variationon); $variativaluearr=explode("|",$row['variation_values']);
                                        for($v=0;$v<count($variationonarr);$v++){ ?> <span><?=$variationonarr[$v]; ?>:<?=$variativaluearr[$v]; ?></span> <? } } ?>
                                        </td>
                                        <td><?=$row['quantity'];?></td>-->
                                        <td class="cc-cursor-pointer"><? echo $row['uname'] ?></td>
                                        <td><? echo $row['total_bill_amount']; ?></td>
                                        <td><?=$row['order_current_Status'];?></td>
                                        <!--<td><div class=" text-right pl-0"><a class="btn btn-primary btn-sm" href="<?=SITEURL;?>/print/generate_invoice.php?auth=<?=base64_encode("vendor");?>&bill=<?=base64_encode("sale");?>&inv=<?=base64_encode($row['item_id']); ?>" target="_blank">Print Sale Invoice</a></div></td>-->
                                        <td><div><button type="button" class="removeopt pro-attr-badge-action btn btn-primary btn-sm mb-2" id="pickbtn<?=$row['item_id']; ?>" onclick="request_pickup_confirm('<?=$row['order_id']; ?>','pickbtn<?=$row['item_id'] ?>')">Request For Pickup</button></div>
                                        <div><!--<button type="button" class="btn btn-primary btn-sm" onclick="confirmCancel('<?=$row['shipping_order_id']; ?>','<?=$row['transaction_id']; ?>','<?=$row['item_id']; ?>',0)">Cancel Order</button>--></div>

                                        </td>
                                    </tr>
                                    <?php } } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="nav-pickup" role="tabpanel" aria-labelledby="nav-pickup-tab">
                            <table class="display table table-bordered neworder-table w-100" id="wait_for_pickup">
                                <thead><tr><th>#</th><th>Order Date</th> <th>Order ID</th><th>Buyer Name</th><th>Amount</th><th>Label</th><th>Status</th><th>Cancel</th></tr></thead>
                                <tbody class="text-muted">
                                    <?php $orderdata = $order->getVendorOrders($vendor,"'Waiting For Ship','Picked Up','Ready To Ship','Pickup Scheduled','Pickup Queue','Pickup Rescheduled','Pickup Error','Pickup Exception','Out For Pickup','Awb Assigned','Label Generated','Manifest Generated'");
                                    if(count($orderdata)){
                                    for($j=0;$j<count($orderdata);$j++){
                                        $row=$orderdata[$j];
                                    $cnt = $j+1;   $itemid=$row['item_id'];
                                    $label=$order->getOrderItemDetatils($itemid,"label");
                                    $manifest=$order->getOrderItemDetatils($itemid,"manifest");
                                     $shipping_by=$order->getOrderItemDetatils($itemid,"shipping_by");     
                                     ?>
                                    <tr>
                                        <td><?=$cnt; ?></td>
                                        <td><?=date("d M Y h:i A",strtotime($row['purchase_date'])) ?></td>
                                        <td class="text-primary cc-cursor-pointer"><a href="order_details.php?transid=<? echo $row['transaction_id']; ?>"> <?php echo $row['order_id']; ?></a></td>
                                        <td class="cc-cursor-pointer"><? echo $row['uname'] ?></td>
                                        <td><? echo $row['total_bill_amount']; ?></td> 
                                        <td> 
                                          <? if($label!=""){
                                            ?><a href="<?=$order->getOrderItemDetatils($itemid,"label");?>" class="removeopt pro-attr-badge-action btn btn-primary btn-sm" target="_blank">Print Label</a><br><br><?
                                          }else{
                                            ?><button type="button" class="btn btn-primary btn-sm" onclick="generate('label','<?=$itemid; ?>')">Generate Label</button><br><br><?
                                          }
                                          if($shipping_by!=SITENAME){
                                               if($manifest!=""){
                                                  ?><a href="<?=$order->getOrderItemDetatils($itemid,"manifest");?>" class="removeopt pro-attr-badge-action btn btn-primary btn-sm" target="_blank">Print Manifest</a><br><?
                                              }else{
                                                 ?><button type="button" class="btn btn-primary btn-sm" onclick="generate('manifest','<?=$itemid; ?>')">Generate Manifest</button><?
                                              }
                                          }
                                          ?>

                                          </td>
                                           <td><?=$orderdata[$j]['order_current_Status'];?></td>

                                          <td> <button type="button" class="btn btn-primary btn-sm" onclick="confirmCancel('<?=$orderdata[$j]['shipping_order_id']; ?>','<?=$orderdata[$j]['transaction_id']; ?>','<?=$itemid; ?>',0)">Cancel Order</button>
                                        </td>

                                    </tr>
                                    <?php } } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="nav-Shipped" role="tabpanel" aria-labelledby="nav-Shipped-tab">
                                <table class="display table table-bordered neworder-table w-100" id="Shipped" >
                                    <thead><tr><th>#</th><th>Order Date</th> <th>Order ID</th><th>Buyer Name</th><th>Amount</th><th>Product Details</th><th>Quantity</th><th>Status</th><th>Invoice</th><th>Cancel</th></tr></thead>
                                    <tbody class="text-muted">
                                        <?php $orderdata = $order->getVendorOrders($vendor,"'Generated','New Order','New','Invoiced','Waiting For Ship','Picked Up','Ready To Ship','Pickup Scheduled','Pickup Queued','Pickup Queue','Pickup Rescheduled','Pickup Error','Pickup Exception','Out For Pickup','Awb Assigned','Label Generated','Manifest Generated','Delivered','Fulfilled','Archived','Partial Delivered','Cancellation Request','Cancellation Requested','Canceled','Return Requested','Return Initiated','Return Pending','Return Pickup Queued','Return Pickup Error','Return In Transit','Return Pickup Generated','Return Cancellation Requested','Return Pickup Cancelled','Return Pickup Resheduled','Return Pickedup','Return Delivered','Returned','Return Rejected','Return Cancelled','Rto Acknowledged','Rto Initiated','Rto Delivered','Rto Ofd','Rto Ndr','failure','Initiated',''","NOT");
                                        if(count($orderdata)){
                                        for($j=0;$j<count($orderdata);$j++){
                                        $cnt = $j+1; ?>
                                        <tr>
                                            <td><?=$cnt; ?></td>
                                            <td><?=date("d M Y h:i A",strtotime($orderdata[$j]['purchase_date'])) ?></td>
                                            <td class="text-primary cc-cursor-pointer" onclick="get_item(<?=$orderdata[$j]['item_id'] ?>)"><a><?=$orderdata[$j]['purchase_order_id']; ?> </a></td>
                                            <td class="cc-cursor-pointer"><? echo $row['uname'] ?></td>
                                            <td><? echo $row['total_bill_amount']; ?></td>
                                            <td><?=$orderdata[$j]['display_product_name']; ?><br>
                                            <? $variationon=$orderdata[$j]['variation_on'];
                                            if($variationon!=""){
                                            $variationonarr=explode("|",$variationon); $variativaluearr=explode("|",$orderdata[$j]['variation_values']);
                                            for($v=0;$v<count($variationonarr);$v++){ ?> <span><?=$variationonarr[$v]; ?>:<?=$variativaluearr[$v]; ?></span> <? } } ?>
                                            </td>
                                            <td><?=$orderdata[$j]['quantity'];?></td>
                                             <td><?=$orderdata[$j]['order_current_Status'];?></td>
                                             
                                             <td><div class=" text-right pl-0"><a class="btn btn-primary btn-sm" href="<?=SITEURL;?>/print/generate_invoice.php?auth=<?=base64_encode("vendor");?>&bill=<?=base64_encode("sale");?>&inv=<?=base64_encode($orderdata[$j]['item_id']); ?>" target="_blank">Print Sale Invoice</a></div></td>
                                            <td> <button type="button" class="btn btn-primary btn-sm" onclick="confirmCancel('<?=$orderdata[$j]['shipping_order_id']; ?>','<?=$orderdata[$j]['transaction_id']; ?>','<?=$orderdata[$j]['item_id']; ?>',0)">Cancel Order</button>
                                             </td>

                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <? include "../footer.php"; ?>
    </div>
</div>
<?php include 'order_common.php';  ?> 
<script src="<?=SITEURL; ?>/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?=SITEURL; ?>/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function(){
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
    });
    $('.neworder-table').DataTable({ "scrollX": true });
});
function request_pickup_confirm(i,buttonId){
     msg = "Do you want to continue?"; del_alertbox(msg, i,"request_pickup",buttonId);
}
function request_pickup(itemid,buttonId){
    
    $("#"+buttonId).attr("disabled",true).html("Requesting..");
    $("#popup_ok").attr("disabled",true);
    $.ajax({
        type:"POST", 
        url:"ajaxdata.php",
        data:{itemid:itemid,action:"request_pickup"},
        success:function(response){
             $("#"+buttonId).attr("disabled",false).html("Request For Pickup");  
             $("#popup_ok").attr("disabled",false);
            response = JSON.parse(response);
            if(response['status'] == "success"){   $("#del_popup").modal("hide"); $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html(response['msg']).delay(3000).fadeOut(); /*$(".tablereload").load(location.href + " #inprocess");*/location.reload(); }
            else { $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html(response['msg']).delay(3000).fadeOut();
            }
        }
    });
}
function generate(reqType,itemid){
   $.ajax({
        type:"POST",
        url:"ajaxdata.php",
        data:{itemid:itemid,action:"request_generate",reqType:reqType},
        success:function(response){
             response = $.trim(response);
            if(response == 1){ location.reload(); }
            else { $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html(response).delay(3000).fadeOut();
            }
        }
    });
}
</script>
</body>
</html>