<!DOCTYPE HTML>
<? include("../../includes/configuration.php");
include("../../classes/order.php"); require_once('../../classes/user.php'); require_once('../../classes/product.php');
$prod = new Product(); $user = new User(); $order = new Order($prod,$user); ?> 
<html lang="en">
<head>
    <title><?php echo SITENAME; ?> : Disbursment</title>
    <?php include('../commoncss.php') ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/dataTable/dataTables.bootstrap4.min.css">
</head>
<body>
<div class="page-body-wrapper">
    <? include '../header.php'; ?>
    <? include '../sidebar.php'; ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card edit_template">
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Disbursement Details</h2></div></div>
                <div class="card-body">
                    <div class="py-2 px-3 alert alert-info">Delivery Date + Applicable Return Days + Disbursement Cycle Days</div>
                    <nav class="dis_link mb-3">
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link active" id="nav-today-tab" data-toggle="tab" href="#nav-today" role="tab" aria-controls="nav-today" aria-selected="true">Today (<?php $Vendor_today = $order->disbursmentOrdersCount("Vendor_today",$vendor); echo $Vendor_today[0]['ordcnt']; ?>)</a>
                            <a class="nav-item nav-link" id="nav-pending-tab" data-toggle="tab" href="#nav-pending" role="tab" aria-controls="nav-pending" aria-selected="true">Pending (<?php $Vendor_pending = $order->disbursmentOrdersCount("Vendor_pending",$vendor); echo $Vendor_pending[0]['ordcnt']; ?>)</a>
                            <a class="nav-item nav-link" id="nav-recieved-tab" data-toggle="tab" href="#nav-recieved" role="tab" aria-controls="nav-recieved" aria-selected="false">Recieved (<?php $Vendor_recieved = $order->disbursmentOrdersCount("Vendor_recieved",$vendor); echo $Vendor_recieved[0]['ordcnt']; ?>)</a>
                        </div>
                    </nav>
                    <div class="tab-content bg-white" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-today" role="tabpanel" aria-labelledby="nav-today-tab">
                            <span class="mb-3 d-inline-block disburs-tot-amt dis-amt-count bg-primary text-white rounded py-1 px-2"><b>Today</b> Total Disbursment Amount : <i class="fa fa-inr"></i><?php echo round($Vendor_today[0]['total']); ?></span>
                            <table class="display table table-bordered disbursement-table w-100">
                                <thead><tr><th>#</th><th>Order Date</th><th>Order ID</th><th>Product Details</th><th>Quantity</th><th>Disbursements Amount</th><th>Status</th></tr></thead>
                                <tbody>
                                    <?php $orderdata = $order->disbursmentOrders("Vendor_today",$vendor);
                                    if(count($orderdata)){
                                    for($j=0;$j<count($orderdata);$j++){ $cnt=$j+1; ?>
                                    <tr id='<?php echo $orderdata[$j]['pur_id'] ?>'>
                                        <td><?=$cnt; ?></td>
                                        <td><?=date("d M Y h:i A",strtotime($orderdata[$j]['purchase_date'])) ?></td>
                                        <td class="text-primary cc-cursor-pointer" onclick="get_item(<?php echo $orderdata[$j]['item_id'] ?>)"><?=$orderdata[$j]['purchase_order_id']; ?></td>
                                        <td style="width:200px;"><?=$orderdata[$j]['display_product_name']; ?><br>
                                            <? $variationon=$orderdata[$j]['variation_on'];
                                            if($variationon!=""){
                                            $variationonarr=explode("|",$variationon); $variativaluearr=explode("|",$orderdata[$j]['variation_values']);
                                            for($v=0;$v<count($variationonarr);$v++){ ?> <span><?=$variationonarr[$v]; ?>:<?=$variativaluearr[$v]; ?></span> <? } } ?>
                                        </td>
                                        <td><?=$orderdata[$j]['quantity'];?></td>
                                        <td><i class="fa fa-inr"></i><?= $orderdata[$j]['quantity'] * ($orderdata[$j]['vendor_per_item_price_withoutgst']+($orderdata[$j]['vendor_per_item_price_withoutgst']*$orderdata[$j]['tax_percentage']/100));?>
                                        </td>
                                        <td><?php if($orderdata[$j]['ispaid'] == '0'){ echo "<span class='badge badge-danger'>Pending</span>"; } else{ echo "<span class='badge badge-success'>Recieved</span>"; } ?></td>
                                    </tr>
                                    <?php } } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade show" id="nav-pending" role="tabpanel" aria-labelledby="nav-pending-tab">
                            <span class="mb-3 d-inline-block disburs-tot-amt dis-amt-count bg-primary text-white rounded py-1 px-2"><b>Pending</b> Total Disbursment Amount : <i class="fa fa-inr"></i><?php echo round($Vendor_pending[0]['total']); ?></span>
                            <table class="display table table-bordered disbursement-table">
                                <thead><tr><th>#</th><th>Order Date</th><th>Order ID</th><th>Product Details</th><th>Quantity</th><th>Disbursements Date</th><th>Disbursements Amount</th><th>Status </th></tr></thead>
                                <tbody>
                                    <?php $orderdata = $order->disbursmentOrders("Vendor_pending",$vendor);
                                    if(count($orderdata)){
                                    for($j=0;$j<count($orderdata);$j++){  $cnt=$j+1; ?>
                                    <tr id='<?php echo $orderdata[$j]['pur_id'] ?>'>
                                        <td><?=$cnt; ?></td>
                                        <td><?=date("d M Y h:i A",strtotime($orderdata[$j]['purchase_date'])) ?></td>
                                        <td class="text-primary cc-cursor-pointer" onclick="get_item(<?php echo $orderdata[$j]['item_id'] ?>)"><?=$orderdata[$j]['purchase_order_id']; ?></td>
                                        <td style="width:200px;"><?=$orderdata[$j]['display_product_name']; ?><br>
                                        <? $variationon=$orderdata[$j]['variation_on'];
                                        if($variationon!=""){
                                        $variationonarr=explode("|",$variationon);  $variativaluearr=explode("|",$orderdata[$j]['variation_values']);
                                        for($v=0;$v<count($variationonarr);$v++){ ?> <span><?=$variationonarr[$v]; ?>:<?=$variativaluearr[$v]; ?></span> <? } } ?></td>
                                        <td><?=$orderdata[$j]['quantity'];?></td>
                                        <td><?=date("d M Y h:i A",strtotime($orderdata[$j]['disbustment_date'])) ?></td>
                                        <td>
                                        <i class="fa fa-inr"></i><?= $orderdata[$j]['quantity'] * ($orderdata[$j]['vendor_per_item_price_withoutgst']+($orderdata[$j]['vendor_per_item_price_withoutgst']*$orderdata[$j]['tax_percentage']/100));?>
                                        </td>
                                        <td ><?php if($orderdata[$j]['ispaid'] == '0'){ echo "<span class='badge badge-danger'>Pending</span>"; } else { echo "<span class='badge badge-success'>Recieved</span>"; } ?></td>
                                    </tr>
                                    <?php } } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade show " id="nav-recieved" role="tabpanel" aria-labelledby="nav-recieved-tab">
                            <span class="mb-3 d-inline-block disburs-tot-amt dis-amt-count bg-primary text-white rounded py-1 px-2"><b>Recieved</b> Total Disbursment Amount : <i class="fa fa-inr"></i><?php echo round($Vendor_recieved[0]['total']); ?></span>
                            <table class="display table table-bordered disbursement-table">
                                <thead><tr><th>#</th><th>Order Date</th><th>Order ID</th> <th>Product Details</th><th>Quantity</th><th>Disbursements Date</th> <th>Disbursements Amount</th> <th>Status </th></tr></thead>
                                <tbody>
                                    <?php $orderdata = $order->disbursmentOrders("Vendor_recieved",$vendor);
                                    if(count($orderdata)){
                                    for($j=0;$j<count($orderdata);$j++){  $cnt=$j+1; ?>
                                    <tr id='<?php echo $orderdata[$j]['pur_id'] ?>'>
                                        <td><?=$cnt; ?></td>
                                        <td><?=date("d M Y h:i A",strtotime($orderdata[$j]['purchase_date'])) ?></td>
                                        <td class="text-primary cc-cursor-pointer" onclick="get_item(<?php echo $orderdata[$j]['item_id'] ?>)"><?=$orderdata[$j]['purchase_order_id']; ?></td>
                                        <td style="width:200px;"><?=$orderdata[$j]['display_product_name']; ?><br>
                                        <? $variationon=$orderdata[$j]['variation_on'];
                                        if($variationon!=""){
                                        $variationonarr=explode("|",$variationon); $variativaluearr=explode("|",$orderdata[$j]['variation_values']);
                                        for($v=0;$v<count($variationonarr);$v++){ ?> <span><?=$variationonarr[$v]; ?>:<?=$variativaluearr[$v]; ?></span><? } } ?></td>
                                        <td><?=$orderdata[$j]['quantity'];?></td>
                                        <td><?=date("d M Y h:i A",strtotime($orderdata[$j]['disbustment_date'])) ?></td>
                                        <td><i class="fa fa-inr"></i><?= $orderdata[$j]['quantity'] * (  $orderdata[$j]['vendor_per_item_price_withoutgst']+($orderdata[$j]['vendor_per_item_price_withoutgst']*$orderdata[$j]['tax_percentage']/100));?></td>
                                        <td ><?php if($orderdata[$j]['ispaid'] == '0'){ echo "<span class='badge badge-danger'>Pending</span>"; } else{ echo "<span class='badge badge-success'>Recieved</span>"; } ?></td>
                                    </tr>
                                    <?php } } ?>
                                </tbody>
                            </table>
                        </div>
                   </div>        
               </div>
           </div>       
        </div>        
        <?php include '../footer.php' ?>
    </div>
</div>
<?php include '../order/order_common.php'; ?> 
<script src="<?php echo SITEURL; ?>/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function(){
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
    });
    $('.disbursement-table').DataTable({ "scrollX": true });
});
</script>
</body>
</html>