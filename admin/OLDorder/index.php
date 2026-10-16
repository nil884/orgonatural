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
                    <ul class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
                    <li class="nav-item"><a class="nav-link active" id="nav-new-tab" data-toggle="tab" href="#nav-new" role="tab" aria-controls="nav-new" aria-selected="true">New Orders (<?=$order->getVendorOrdersCount("","Generated"); ?>)</a></li>
                    <li class="nav-item"><a class="nav-link " id="nav-pickup-tab" data-toggle="tab" href="#nav-pickup" role="tab" aria-controls="nav-pickup" aria-selected="true">Waiting For Pickups (<?=$order->getVendorOrdersCount("","Waiting For Ship"); ?>)</a></li>
                    <li class="nav-item"><a class="nav-link" id="nav-Shipped-tab" data-toggle="tab" href="#nav-Shipped" role="tab" aria-controls="nav-Shipped" aria-selected="false"> Shipped Order (<?=$order->getVendorOrdersCount("","Shipped"); ?>)</a></li>
                    </ul>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-new" role="tabpanel" aria-labelledby="nav-new-tab">
                            <table class="display table table-bordered neworder-table w-100"  id="inprocess">
                                <thead><tr><th>#</th><th>Order Date</th> <th> Order ID </th><th>Sub-Order ID</th> <th>Vendor Name</th><th>Product Details</th><th>Quantity</th><th>Cancel</th></tr></thead>
                                <tbody>
                                    <?php $orderdata = $order->getVendorOrders("","Generated");
                                    if(count($orderdata)){
                                    for($j=0;$j<count($orderdata);$j++){
                                    $cnt=$j+1; ?>
                                    <tr>
                                        <td><?=$cnt; ?></td>
                                        <td><?=date("d M Y h:i A",strtotime($orderdata[$j]['purchase_date'])) ?></td>
                                        <td class="text-primary cc-cursor-pointer"> <a href="order_details.php?transid=<? echo $orderdata[$j]['transaction_id']; ?>"> <?php echo $orderdata[$j]['order_id'] ?></a> </td> 
                                        <td class="text-primary cc-cursor-pointer" onclick="get_item(<?php echo $orderdata[$j]['item_id'] ?>)"><a> <?=$orderdata[$j]['purchase_order_id']; ?> </a></td>
                                        <td><a href="<?php echo ADMINURL ?>vendor/editvendor.php?vendor=<?php echo base64_encode($orderdata[$j]['vendor'])  ?>"> <?php  echo $orderdata[$j]['vendor_name'] ?></a></td>
                                        <td><?=$orderdata[$j]['display_product_name']; ?><br>
                                        <? $variationon=$orderdata[$j]['variation_on'];
                                        if($variationon!=""){
                                        $variationonarr=explode("|",$variationon);  $variativaluearr=explode("|",$orderdata[$j]['variation_values']);
                                        for($v=0;$v<count($variationonarr);$v++){ ?> <span> <?=$variationonarr[$v]; ?>:<?=$variativaluearr[$v]; ?> </span> <? } } ?>
                                        </td>
                                        <td><?=$orderdata[$j]['quantity'];?></td>
                                        <td><button type="button" class="btn btn-primary btn-sm" onclick="confirmCancel('<?=$orderdata[$j]['shipping_order_id']; ?>','<?=$orderdata[$j]['transaction_id']; ?>','<?=$orderdata[$j]['item_id']; ?>',0)">Cancel Order</button>
                                        </td>
                                    </tr>
                                    <?php } } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="nav-pickup" role="tabpanel" aria-labelledby="nav-pickup-tab">
                            <table class="display table table-bordered neworder-table w-100" id="wait_for_pickup">
                                <thead><tr><th>#</th><th>Order Date</th><th> Order ID</th><th>Sub-Order ID</th> <th>Vendor Name</th><th>Product Details</th><th>Quantity</th><th>Cancel</th></tr></thead>
                                <tbody class="text-secondary">
                                    <?php $orderdata = $order->getVendorOrders("","Waiting For Ship");
                                    if(count($orderdata)){
                                    for($j=0;$j<count($orderdata);$j++){
                                    $cnt=$j+1; ?>
                                    <tr>
                                        <td><?=$cnt; ?></td>
                                        <td><?=date("d M Y h:i A",strtotime($orderdata[$j]['purchase_date'])) ?></td>
                                        <td class="text-primary cc-cursor-pointer"> <a href="order_details.php?transid=<? echo $orderdata[$j]['transaction_id']; ?>"> <?php echo $orderdata[$j]['order_id'] ?></a> </td> 
                                        <td class="text-primary cc-cursor-pointer" onclick="get_item(<?php echo $orderdata[$j]['item_id'] ?>)"><a> <?=$orderdata[$j]['purchase_order_id']; ?> </a></td>
                                        <td><a href="<?php echo ADMINURL ?>vendor/editvendor.php?vendor=<?php echo base64_encode($orderdata[$j]['vendor'])  ?>"><?php  echo $orderdata[$j]['vendor_name'] ?>  </a></td>
                                        <td><?=$orderdata[$j]['display_product_name']; ?><br>
                                        <? $variationon=$orderdata[$j]['variation_on'];
                                        if($variationon!=""){
                                        $variationonarr=explode("|",$variationon);  $variativaluearr=explode("|",$orderdata[$j]['variation_values']);
                                        for($v=0;$v<count($variationonarr);$v++){ ?> <span> <?=$variationonarr[$v]; ?>:<?=$variativaluearr[$v]; ?> </span> <? } } ?>
                                        </td>
                                        <td><?=$orderdata[$j]['quantity'];?></td>
                                        <td><button type="button" class="btn btn-primary btn-sm" onclick="confirmCancel('<?=$orderdata[$j]['shipping_order_id']; ?>','<?=$orderdata[$j]['transaction_id']; ?>','<?=$orderdata[$j]['item_id']; ?>',0)">Cancel Order</button>

                                        </td>
                                    </tr>
                                    <?php } } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="nav-Shipped" role="tabpanel" aria-labelledby="nav-Shipped-tab">
                            <table class="display table table-bordered neworder-table w-100"  id="Shipped">
                                <thead><tr><th>#</th><th>Order Date</th><th>Order ID</th><th>Sub-Order ID</th>  <th>Vendor Name</th><th>Product Details</th><th>Quantity</th><th>Cancel</th></tr></thead>
                                <tbody class="text-secondary">
                                    <?php $orderdata = $order->getVendorOrders("","Shipped");
                                    if(count($orderdata)){
                                    for($j=0;$j<count($orderdata);$j++){
                                    $cnt=$j+1; ?>
                                    <tr>
                                        <td><?=$cnt; ?></td>
                                        <td><?=date("d M Y h:i A",strtotime($orderdata[$j]['purchase_date'])) ?></td>
                                        <td class="text-primary cc-cursor-pointer" ><a href="order_details.php?transid=<? echo $orderdata[$j]['transaction_id']; ?>"> <?php echo $orderdata[$j]['order_id'] ?> </a></td> 
                                        <td class="text-primary cc-cursor-pointer" onclick="get_item(<?php echo $orderdata[$j]['item_id'] ?>)"><a> <?=$orderdata[$j]['purchase_order_id']; ?> </a></td>
                                        <td><a href="<?php echo ADMINURL ?>vendor/editvendor.php?vendor=<?php echo base64_encode($orderdata[$j]['vendor'])  ?>"><?php  echo $orderdata[$j]['vendor_name'] ?></a></td>
                                        <td><?=$orderdata[$j]['display_product_name']; ?><br>
                                        <? $variationon=$orderdata[$j]['variation_on'];
                                        if($variationon!=""){
                                        $variationonarr=explode("|",$variationon);  $variativaluearr=explode("|",$orderdata[$j]['variation_values']);
                                        for($v=0;$v<count($variationonarr);$v++){ ?> <span> <?=$variationonarr[$v]; ?>:<?=$variativaluearr[$v]; ?> </span> <? } } ?>
                                        </td>
                                        <td><?=$orderdata[$j]['quantity'];?></td>
                                        <td><button type="button" class="btn btn-primary btn-sm" onclick="confirmCancel('<?=$orderdata[$j]['shipping_order_id']; ?>','<?=$orderdata[$j]['transaction_id']; ?>','<?=$orderdata[$j]['item_id']; ?>',0)">Cancel Order</button>
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
<?php include 'order_common.php'; ?>
<script src="<?php echo SITEURL; ?>/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function(){
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
    });
    $('.neworder-table').DataTable({ "scrollX": true });
});
</script>
</body>
</html>
