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
    <? include '../sidebar.php'; ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Return Order</h2></div></div>
                <div class="card-body">
                    <ul class="nav nav-tabs mb-3" role="tablist">
                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#nav-Return-Requested" role="tab" aria-controls="nav-Return-Requested" aria-selected="true">Return Request (<?=$order->getVendorOrdersCount($vendor,"Return Requested"); ?>)</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#nav-Return-inititated" role="tab" aria-controls="nav-Return-inititated" aria-selected="true">Return Initiated (<?=$order->getVendorOrdersCount($vendor,"Return Initiated"); ?>)</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#nav-Returned" role="tab" aria-controls="nav-Returned" aria-selected="false"> Returned Order (<?=$order->getVendorOrdersCount($vendor,"Returned"); ?>)</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#nav-Return-Rejected" role="tab" aria-controls="nav-Return-Rejected" aria-selected="false">Reject Return (<?=$order->getVendorOrdersCount($vendor,"Return Rejected"); ?>)</a></li>
                    </ul>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-Return-Requested" role="tabpanel">
                            <table class="display table table-bordered neworder-table w-100">
                                <thead><tr><th>#</th><th>Order Date</th><th>Order ID</th><th>Product Details</th><th>Quantity</th></tr></thead>
                                <tbody>
                                    <?php $orderdata = $order->getVendorOrders($vendor,"Return Requested");
                                    if(count($orderdata)){
                                    for($j=0;$j<count($orderdata);$j++){
                                    $cnt = $j+1; ?>
                                    <tr>
                                        <td><?=$cnt; ?></td>
                                        <td><?=date("d M Y h:i A",strtotime($orderdata[$j]['purchase_date'])) ?></td>
                                        <td class="text-primary cc-cursor-pointer" onclick="get_item(<?=$orderdata[$j]['item_id'] ?>)"><a> <?=$orderdata[$j]['purchase_order_id']; ?> </a></td>
                                        <td><?=$orderdata[$j]['display_product_name']; ?><br>
                                        <? $variationon=$orderdata[$j]['variation_on'];
                                        if($variationon!=""){
                                        $variationonarr=explode("|",$variationon); $variativaluearr=explode("|",$orderdata[$j]['variation_values']);
                                        for($v=0;$v<count($variationonarr);$v++){ ?> <span><?=$variationonarr[$v]; ?>:<?=$variativaluearr[$v]; ?></span> <? } } ?>
                                        </td>
                                        <td><?=$orderdata[$j]['quantity'];?></td>
                                    </tr>
                                    <?php } } ?>
                                </tbody>
                            </table>
                        </div>   
                        <div class="tab-pane" id="nav-Return-inititated" role="tabpanel">
                            <table class="display table table-bordered neworder-table w-100">
                                <thead><tr><th>#</th><th>Order Date</th> <th>Order ID</th><th>Product Details</th><th>Quantity</th></tr></thead>
                                <tbody>
                                    <?php $orderdata = $order->getVendorOrders($vendor,"Return Initiated");
                                    if(count($orderdata)){
                                    for($j=0;$j<count($orderdata);$j++){
                                    $cnt = $j+1; ?>
                                    <tr>
                                        <td><?=$cnt; ?></td>
                                        <td><?=date("d M Y h:i A",strtotime($orderdata[$j]['purchase_date'])) ?></td>
                                        <td class="text-primary cc-cursor-pointer" onclick="get_item(<?php echo $orderdata[$j]['item_id'] ?>)"><a> <?=$orderdata[$j]['purchase_order_id']; ?> </a></td>
                                        <td><?=$orderdata[$j]['display_product_name']; ?><br>
                                        <? $variationon=$orderdata[$j]['variation_on'];
                                        if($variationon!=""){
                                        $variationonarr=explode("|",$variationon); $variativaluearr=explode("|",$orderdata[$j]['variation_values']);
                                        for($v=0;$v<count($variationonarr);$v++){ ?> <span> <?=$variationonarr[$v]; ?>:<?=$variativaluearr[$v]; ?> </span> <? } } ?>
                                        </td>
                                        <td><?=$orderdata[$j]['quantity'];?></td>
                                    </tr>
                                    <?php } } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="nav-Returned" role="tabpanel">
                            <table class="display table table-bordered neworder-table w-100">
                                <thead><tr><th>#</th><th>Order Date</th><th>Order ID</th><th>Product Details</th><th>Quantity</th></tr></thead>
                                <tbody>
                                    <?php $orderdata = $order->getVendorOrders($vendor," Returned");
                                    if(count($orderdata)){
                                    for($j=0;$j<count($orderdata);$j++){
                                    $cnt = $j+1; ?>
                                    <tr>
                                        <td><?=$cnt; ?></td>
                                        <td><?=date("d M Y h:i A",strtotime($orderdata[$j]['purchase_date'])) ?></td>
                                        <td class="text-primary cc-cursor-pointer" onclick="get_item(<?php echo $orderdata[$j]['item_id'] ?>)"><a> <?=$orderdata[$j]['purchase_order_id']; ?> </a></td>
                                        <td><?=$orderdata[$j]['display_product_name']; ?><br>
                                        <? $variationon=$orderdata[$j]['variation_on'];
                                        if($variationon!=""){
                                        $variationonarr=explode("|",$variationon);  $variativaluearr=explode("|",$orderdata[$j]['variation_values']);
                                        for($v=0;$v<count($variationonarr);$v++){ ?> <span> <?=$variationonarr[$v]; ?>:<?=$variativaluearr[$v]; ?> </span> <? } } ?>
                                        </td>
                                        <td><?=$orderdata[$j]['quantity'];?></td>
                                    </tr>
                                    <?php } } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="nav-Return-Rejected" role="tabpanel">
                            <table class="display table table-bordered neworder-table w-100" >
                                <thead><tr><th>#</th><th>Order Date</th><th>Order ID</th><th>Product Details</th><th>Quantity</th><th>Invoice</th></tr></thead>
                                <tbody>
                                <?php $orderdata = $order->getVendorOrders($vendor,"Return Rejected");
                                if(count($orderdata)){
                                for($j=0;$j<count($orderdata);$j++){
                                $cnt = $j+1; ?>
                                <tr>
                                    <td><?=$cnt; ?></td>
                                    <td><?=date("d M Y h:i A",strtotime($orderdata[$j]['purchase_date'])) ?></td>
                                    <td class="text-primary cc-cursor-pointer" onclick="get_item(<?php echo $orderdata[$j]['item_id'] ?>)"><a> <?=$orderdata[$j]['purchase_order_id']; ?> </a></td>
                                    <td><?=$orderdata[$j]['display_product_name']; ?><br>
                                    <? $variationon = $orderdata[$j]['variation_on'];
                                    if($variationon!=""){
                                    $variationonarr = explode("|",$variationon); $variativaluearr = explode("|",$orderdata[$j]['variation_values']);
                                    for($v=0;$v<count($variationonarr);$v++){ ?> <span><?=$variationonarr[$v]; ?>:<?=$variativaluearr[$v]; ?></span> <? } } ?>
                                    </td>
                                    <td><?=$orderdata[$j]['quantity'];?></td>
                                    <td style="width:18%;"><a class="btn btn-primary btn-sm" href="<?=SITEURL;?>/print/generate_invoice.php?auth=<?=base64_encode("vendor");?>&bill=<?=base64_encode("sale");?>&inv=<?=base64_encode($orderdata[$j]['item_id']); ?>" target="_blank">Print Sale Invoice</a></td>
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
function returnAction(itemId,returnOrderId,shipmentid,actionTaken){
   $.ajax({
        url : "<?=SITEURL; ?>/ajax/order_ajax.php",
        type : "post",
        data : {itemid:itemId,returnOrderId:returnOrderId,shipmentid:shipmentid,actionTaken:actionTaken, action:"action_on_return"},
        success : function(res){ }
    })
}
</script>
</body>
</html>