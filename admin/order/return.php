<? include("../../includes/configuration.php");
include("../../classes/order.php"); require_once('../../classes/user.php'); require_once('../../classes/product.php');
$prod = new Product(); $user = new User(); $order = new Order($prod,$user); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo SITE_TITLE; ?> : Return Orders</title>
    <? include "../commoncss.php"; ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/dataTable/dataTables.bootstrap4.min.css">
</head>
<body>
<div class="page-body-wrapper">
    <? include '../header.php'; ?>
    <? include '../sidebar.php';

     $getmanual=selectQuery(MANUAL,"count(pincode) as pins","1 order by id DESC");           ?>
    <div class="main-panel">
        <div class="dashbody" id="dashbody">
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Return Order</h2></div></div>
                <div class="card-body">
                    <nav>
                        <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" data-toggle="tab" href="#nav-Return-Requested" role="tab" aria-controls="nav-Return-Requested" aria-selected="true">Return Request (<?=$order->getVendorOrdersCount("","'Return Requested'"); ?>)</a>
                         <? if($getmanual[0]['pins']>0){ ?><a class="nav-item nav-link" id="nav-hyperlocal-tab" data-toggle="tab" href="#nav-hyperlocal" role="tab" aria-controls="nav-hyperlocal" aria-selected="false"> Hyperlocal Return Initiated(<?=$order->getVendorOrdersCount("","'Return Initiated'","","shipping_by='".SITENAME."'"); ?>)</a><? } ?>
                        <a class="nav-item nav-link" data-toggle="tab" href="#nav-Return-inititated" role="tab" aria-controls="nav-Return-inititated" aria-selected="true">Return Initiated (<?=$order->getVendorOrdersCount("","'Return Initiated','Return Pending','Return Pickup Queued','Return Pickup Error','Return In Transit','Return Pickup Generated','Return Cancellation Requested','Return Pickup Cancelled','Return Pickup Resheduled','Return Pickedup'"); ?>)</a>
                        <a class="nav-item nav-link" data-toggle="tab" href="#nav-Returned" role="tab" aria-controls="nav-Returned" aria-selected="false"> Returned Order (<?=$order->getVendorOrdersCount("","'Return Delivered','Returned'"); ?>)</a>
                        <a class="nav-item nav-link" data-toggle="tab" href="#nav-Return-Rejected" role="tab" aria-controls="nav-Return-Rejected" aria-selected="false">Reject Return (<?=$order->getVendorOrdersCount("","'Return Rejected','Return Cancelled'"); ?>)</a>
                        </div> 
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-Return-Requested" role="tabpanel" aria-labelledby="nav-new-tab">
                            <table class="display table table-bordered neworder-table w-100">
                                <thead><tr><th>#</th><th>Order Date</th><th>Order ID</th><th>Sub-Order ID</th><th>Vendor Name</th><th>Product Details</th><th>Quantity</th><th>Status</th><th>Reason</th><th>Action</th></tr></thead>
                                <tbody>
                                    <?php $orderdata = $order->getVendorOrders("","'Return Requested'");
                                    if(count($orderdata)){
                                    for($j=0;$j<count($orderdata);$j++){
                                    $cnt = $j+1; ?>
                                    <tr>
                                        <td><?=$cnt; ?></td>
                                        <td><?=date("d M Y h:i A",strtotime($orderdata[$j]['purchase_date'])) ?></td>
                                        <td class="text-primary cc-cursor-pointer"><a href="order_details.php?transid=<? echo $orderdata[$j]['transaction_id']; ?>"><?php echo $orderdata[$j]['order_id'] ?></a></td>
                                        <td class="text-primary cc-cursor-pointer" onclick="get_item(<?=$orderdata[$j]['item_id'] ?>)"><a><?=$orderdata[$j]['purchase_order_id']; ?></a></td>
                                        <td><a href="<?php echo ADMINURL ?>vendor/editvendor.php?vendor=<?php echo base64_encode($orderdata[$j]['vendor'])  ?>"><?php echo $orderdata[$j]['vendor_name'] ?></a></td>
                                        <td><?=$orderdata[$j]['display_product_name']; ?><br>
                                        <? $variationon=$orderdata[$j]['variation_on'];
                                        if($variationon!=""){
                                        $variationonarr = explode("|",$variationon); $variativaluearr = explode("|",$orderdata[$j]['variation_values']);
                                        for($v=0;$v<count($variationonarr);$v++){ ?> <span><?=$variationonarr[$v]; ?>:<?=$variativaluearr[$v]; ?></span> <? } } ?>
                                        </td>
                                        <td><?=$orderdata[$j]['quantity'];?></td>
                                        <td><?=$orderdata[$j]['order_current_Status'];?></td>
                                        <td><?= $order->getOrderItemDetatils($orderdata[$j]['item_id'],"return_reason");?> </td>
                                        <td><button type="button" class="removeopt btn btn-primary btn-sm mb-2" onclick="returnAction('<?=$orderdata[$j]['item_id']; ?>','Initiate')">Initiate Return</button><button type="button" class="removeopt btn btn-primary btn-sm" onclick="returnAction('<?=$orderdata[$j]['item_id']; ?>','Cancel')">Cancel Return</button></td>
                                    </tr>
                                    <?php } } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="nav-hyperlocal" role="tabpanel" aria-labelledby="nav-hyperlocal-tab">
                            <table class="display table table-bordered neworder-table w-100"  id="hyperlocal" >
                                <thead><tr><th>#</th><th>Order Date</th><th>Order ID</th><th>Sub-Order ID</th> <th>Vendor Name</th><th>Product Details</th><th>Quantity</th><th>Status</th><th>Delivered/Cancel</th></tr></thead>
                                <tbody>
                                    <?php $orderdata = $order->getVendorOrders("","'Return Initiated'","","shipping_by='".SITENAME."'");
                                    if(count($orderdata)){
                                    for($j=0;$j<count($orderdata);$j++){
                                    //echo "<pre>"; print_r($orderdata[$j]);
                                    $cnt = $j+1; ?>
                                    <tr>
                                        <td><?=$cnt; ?></td>
                                        <td><?=date("d M Y h:i A",strtotime($orderdata[$j]['purchase_date'])) ?></td>
                                        <td class="text-primary cc-cursor-pointer"><a href="order_details.php?transid=<? echo $orderdata[$j]['transaction_id']; ?>"><?php echo $orderdata[$j]['order_id'] ?></a></td>
                                        <td class="text-primary cc-cursor-pointer" onclick="get_item(<?php echo $orderdata[$j]['item_id'] ?>)"><a><?=$orderdata[$j]['purchase_order_id']; ?> </a></td>
                                        <td><a href="<?php echo ADMINURL ?>vendor/editvendor.php?vendor=<?php echo base64_encode($orderdata[$j]['vendor'])  ?>"><?php echo $orderdata[$j]['vendor_name'] ?></a></td>
                                        <td><?=$orderdata[$j]['display_product_name']; ?><br>
                                        <? $variationon=$orderdata[$j]['variation_on'];
                                        if($variationon!=""){
                                        $variationonarr = explode("|",$variationon); $variativaluearr = explode("|",$orderdata[$j]['variation_values']);
                                        for($v=0;$v<count($variationonarr);$v++){ ?><span><?=$variationonarr[$v]; ?>:<?=$variativaluearr[$v]; ?></span><? } } ?>
                                        </td>
                                        <td><?=$orderdata[$j]['quantity'];?></td>
                                        <td><?=$orderdata[$j]['order_current_Status'];?></td>
                                        <td><button type="button" class="btn btn-primary btn-sm" onclick="confirmdelivered('<?=$orderdata[$j]['item_id']; ?>')">Mark As Completed</button>
                                        </td>
                                    </tr>
                                    <?php } } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="nav-Return-inititated" role="tabpanel" aria-labelledby="nav-Return-inititated">
                            <table class="display table table-bordered neworder-table w-100">
                                <thead><tr><th>#</th><th>Order Date</th><th>Order ID</th><th>Sub-Order ID</th><th>Vendor Name</th><th>Product Details</th><th>Quantity</th><th>Status</th></tr></thead>
                                <tbody>
                                    <?php $orderdata = $order->getVendorOrders("","'Return Initiated','Return Pending','Return Pickup Queued','Return Pickup Error','Return In Transit','Return Pickup Generated','Return Cancellation Requested','Return Pickup Cancelled','Return Pickup Resheduled','Return Pickedup'");
                                    if(count($orderdata)){
                                    for($j=0;$j<count($orderdata);$j++){
                                    $cnt = $j+1; ?>
                                    <tr>
                                        <td><?=$cnt; ?></td>
                                        <td><?=date("d M Y h:i A",strtotime($orderdata[$j]['purchase_date'])) ?></td>
                                        <td class="text-primary cc-cursor-pointer"> <a href="order_details.php?transid=<? echo $orderdata[$j]['transaction_id']; ?>"> <?php echo $orderdata[$j]['order_id'] ?> </a> </td>
                                        <td class="text-primary cc-cursor-pointer" onclick="get_item(<?php echo $orderdata[$j]['item_id'] ?>)"><a><?=$orderdata[$j]['purchase_order_id']; ?> </a></td>
                                        <td><a href="<?php echo ADMINURL ?>vendor/editvendor.php?vendor=<?php echo base64_encode($orderdata[$j]['vendor'])  ?>"><?php echo $orderdata[$j]['vendor_name'] ?></a></td>
                                        <td><?=$orderdata[$j]['display_product_name']; ?><br>
                                        <? $variationon=$orderdata[$j]['variation_on'];
                                        if($variationon!=""){
                                        $variationonarr = explode("|",$variationon); $variativaluearr = explode("|",$orderdata[$j]['variation_values']);
                                        for($v=0;$v<count($variationonarr);$v++){ ?> <span> <?=$variationonarr[$v]; ?>:<?=$variativaluearr[$v]; ?> </span> <? } } ?>
                                        </td>
                                        <td><?=$orderdata[$j]['quantity'];?></td>
                                         <td><?=$orderdata[$j]['order_current_Status'];?></td>
                                    </tr>
                                    <?php } } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="nav-Returned" role="tabpanel" aria-labelledby="nav-Returned">
                            <table class="display table table-bordered neworder-table w-100">
                                <thead><tr><th>#</th><th>Order Date</th><th>Order ID</th><th>Sub-Order ID</th><th>Vendor Name</th><th>Product Details</th><th>Quantity</th><th>Payment Mode</th><th>Status</th><th>Refund ID</th><th>Refund Status</th></tr></thead>
                                <tbody>
                                    <?php $orderdata = $order->getVendorOrders("","'Return Delivered','Returned'");
                                    if(count($orderdata)){
                                    for($j=0;$j<count($orderdata);$j++){
                                    $cnt = $j+1;
                                    $getmode=selectQuery(ORDER,"payment_mode","order_id='".$orderdata[$j]['order_id']."'"); ?>
                                    <tr>
                                        <td><?=$cnt; ?></td>
                                        <td><?=date("d M Y h:i A",strtotime($orderdata[$j]['purchase_date'])) ?></td>
                                        <td class="text-primary cc-cursor-pointer"> <a href="order_details.php?transid=<? echo $orderdata[$j]['transaction_id']; ?>"> <?php echo $orderdata[$j]['order_id'] ?> </a> </td>
                                        <td class="text-primary cc-cursor-pointer" onclick="get_item(<?php echo $orderdata[$j]['item_id'] ?>)"><a> <?=$orderdata[$j]['purchase_order_id']; ?> </a></td>
                                        <td><a href="<?php echo ADMINURL ?>vendor/editvendor.php?vendor=<?php echo base64_encode($orderdata[$j]['vendor'])  ?>"><?php  echo $orderdata[$j]['vendor_name'] ?></a></td>
                                        <td><?=$orderdata[$j]['display_product_name']; ?><br>
                                        <? $variationon=$orderdata[$j]['variation_on'];
                                        if($variationon!=""){
                                        $variationonarr = explode("|",$variationon); $variativaluearr = explode("|",$orderdata[$j]['variation_values']);
                                        for($v=0;$v<count($variationonarr);$v++){ ?> <span> <?=$variationonarr[$v]; ?>:<?=$variativaluearr[$v]; ?></span> <? } } ?>
                                        </td>
                                        <td><?=$orderdata[$j]['quantity'];?></td>
                                        <td><?=$getmode[0]['payment_mode'];?></td>
                                         <td><?=$orderdata[$j]['order_current_Status'];?></td>
                                         <td><?=$order->getOrderItemDetatils($orderdata[$j]['item_id'],"refund_id");?></td>
                                    <td><?=$order->getOrderItemDetatils($orderdata[$j]['item_id'],"refund_response");?></td>
                                    </tr>
                                    <?php } } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="nav-Return-Rejected" role="tabpanel" aria-labelledby="nav-Return-Rejected">
                            <table class="display table table-bordered neworder-table w-100">
                            <thead><tr><th>#</th><th>Order Date</th><th>Order ID</th><th>Sub-Order ID</th><th>Vendor Name</th><th>Product Details</th><th>Quantity</th><th>Status</th></tr></thead>
                            <tbody>
                                <?php $orderdata = $order->getVendorOrders("","'Return Rejected','Return Cancelled'");
                                if(count($orderdata)){
                                for($j=0;$j<count($orderdata);$j++){
                                $cnt = $j+1; ?>
                                <tr>
                                    <td><?=$cnt; ?></td>
                                    <td><?=date("d M Y h:i A",strtotime($orderdata[$j]['purchase_date'])) ?></td>
                                    <td class="text-primary cc-cursor-pointer"><a href="order_details.php?transid=<? echo $orderdata[$j]['transaction_id']; ?>"><?php echo $orderdata[$j]['order_id'] ?></a></td> 
                                    <td class="text-primary cc-cursor-pointer" onclick="get_item(<?php echo $orderdata[$j]['item_id'] ?>)"><a><?=$orderdata[$j]['purchase_order_id']; ?></a></td>
                                    <td><a href="<?php echo ADMINURL ?>vendor/editvendor.php?vendor=<?php echo base64_encode($orderdata[$j]['vendor'])  ?>"><?php echo $orderdata[$j]['vendor_name'] ?></a></td>
                                    <td><?=$orderdata[$j]['display_product_name']; ?><br>
                                    <? $variationon=$orderdata[$j]['variation_on'];
                                    if($variationon!=""){
                                    $variationonarr = explode("|",$variationon); $variativaluearr = explode("|",$orderdata[$j]['variation_values']);
                                    for($v=0;$v<count($variationonarr);$v++){ ?> <span><?=$variationonarr[$v]; ?>:<?=$variativaluearr[$v]; ?></span> <? } } ?>
                                    </td>
                                    <td><?=$orderdata[$j]['quantity'];?></td>
                                      <td><?=$orderdata[$j]['order_current_Status'];?></td>
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
<?php include 'order_common.php'; ?>
<script src="<?=SITEURL; ?>/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?=SITEURL; ?>/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function(){
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
    });
    $('.neworder-table').DataTable({ "scrollX": true });
});
function returnAction_db(itemId,actionTaken){
   $.ajax({
     url : "<?=SITEURL; ?>/ajax/order_ajax.php",
        type : "post",
        data : {itemid:itemId,actionTaken:actionTaken, action:"action_on_return"},
        success : function(res){
            $("#del_popup").modal("hide");
            if(res==1){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Request initiated").delay(3000).fadeOut();
                $("#dashbody").load(" #dashbody");
            }else{
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html(res).delay(3000).fadeOut()
            }
        }
    })
}
function returnAction(itemId,actionTaken){
    del_alertbox("Do you really want to "+actionTaken+"?",itemId,'returnAction_db',actionTaken);
}
function confirmdelivered(itemid){
       del_alertbox("Do you really want to Complete?",itemid,'markdelivered',"");

}
function  markdelivered(item){
        $.ajax({
         url : "<?=SITEURL; ?>/ajax/order_ajax.php",
            type : "post",
            data : {itemid:item, action:"returnDelivered"},
            success : function(res){
                $("#del_popup").modal("hide");
                if(res==1){
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Request Completed").delay(3000).fadeOut();
                    $("#dashbody").load(" #dashbody");
                }else{
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html(res).delay(3000).fadeOut()
                }
            }
        })
}
</script>
</body>
</html>