<? include("../../includes/configuration.php");
include("../../classes/order.php"); require_once('../../classes/user.php'); require_once('../../classes/product.php');
$prod = new Product(); $user = new User(); $order = new Order($prod,$user); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo SITE_TITLE; ?> : Delivered Orders</title>
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
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Delivered Order (<?=$order->getVendorOrdersCount($vendor,"'Delivered','Fulfilled','Archived','Partial Delivered'"); ?>)</h2></div></div>
                <div class="card-body">
                    <table class="display table table-bordered neworder-table w-100">
                        <thead><tr><th>#</th><th>Order Date</th> <th>Order ID</th><th>Buyer Name</th><th>Product Details</th><th>Quantity</th><th>Status</th><th>Invoice</th></tr></thead>
                        <tbody>
                            <?php $orderdata = $order->getVendorOrders($vendor,"'Delivered','Fulfilled','Archived','Partial Delivered'");
                            if(count($orderdata)){
                            for($j=0;$j<count($orderdata);$j++){
                                $row=$orderdata[$j];
                              
                            $cnt = $j+1; ?>
                            <tr>
                                <td><?=$cnt; ?></td>
                                <td><?=date("d M Y h:i A",strtotime($row['purchase_date'])) ?></td>
                                <td class="text-primary cc-cursor-pointer" onclick="get_item(<?php echo $row['item_id'] ?>)"><a> <?=$row['purchase_order_id']; ?></a></td>
                                <td ><? echo ($row['uname']?$row['uname']:$row['shippingAddr_name']); ?></td>
                               
                                <td><?=$row['display_product_name']; ?><br>
                                <? $variationon=$row['variation_on'];
                                if($variationon!=""){
                                $variationonarr=explode("|",$variationon);  $variativaluearr=explode("|",$row['variation_values']);
                                for($v=0;$v<count($variationonarr);$v++){ ?> <span><?=$variationonarr[$v]; ?>:<?=$variativaluearr[$v]; ?></span> <? } } ?></td>
                                <td><?=$row['quantity'];?></td>
                               
                                <td><?=$row['order_current_Status'];?></td>
                                <td><div class=" text-right pl-0"><a class="btn btn-primary btn-sm" href="<?=SITEURL;?>/print/generate_invoice.php?auth=<?=base64_encode("vendor");?>&bill=<?=base64_encode("sale");?>&inv=<?=base64_encode($row['item_id']); ?>" target="_blank">Print Sale Invoice</a></div></td>
                            </tr>
                            <?php } } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
       <? include "../footer.php"; ?>
    </div>
</div>
<?php include 'order_common.php'; ?>
<script src="<?php echo SITEURL; ?>/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script>$('.neworder-table').DataTable({ "scrollX": true });</script>
</body>
</html>