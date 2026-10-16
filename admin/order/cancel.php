<? include("../../includes/configuration.php");
include("../../classes/order.php"); require_once('../../classes/user.php'); require_once('../../classes/product.php');
$prod = new Product(); $user = new User(); $order = new Order($prod,$user); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo SITE_TITLE; ?> : Cancelled Orders</title>
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
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Cancelled Order (<?=$order->getVendorOrdersCount("","'Cancellation Request','Cancellation Requested','Canceled'"); ?>)</h2></div></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="display table table-bordered neworder-table" >
                            <thead><tr><th>#</th><th>Order Date</th><th>Order ID</th><th>Sub-Order ID</th> <th>Buyer Name</th> <th>Vendor Name</th><th>Product Details</th><th>Quantity</th><th>Payment Mode</th><th>Status</th><th>Refund ID</th><th>Refund Status</th></tr></thead>
                            <tbody>
                                <?php $orderdata = $order->getVendorOrders("","'Cancellation Request','Cancellation Requested','Canceled'");
                                if(count($orderdata)){
                                for($j=0;$j<count($orderdata);$j++){
                                    $row=$orderdata[$j];
                                    $getmode=selectQuery(ORDER,"payment_mode","order_id='".$row['order_id']."'");
                                $cnt = $j+1; ?>
                                <tr>
                                    <td><?=$cnt; ?></td>
                                    <td><?=date("d M Y h:i A",strtotime($row['purchase_date'])) ?></td>
                                    <td class="text-primary cc-cursor-pointer"><a href="order_details.php?transid=<? echo $row['transaction_id']; ?>"> <?php echo $row['order_id'] ?></a> </td>
                                    <td class="text-primary cc-cursor-pointer" onclick="get_item(<?php echo $row['item_id'] ?>)"><a> <?=$row['purchase_order_id']; ?> </a></td>
                                    <td class="cc-cursor-pointer"><? if($row['user_id']){?><a href="<?=ADMINURL ?>buyer/buyersdetails.php?u_id=<?=base64_encode($row['user_id']) ?>" target="_blank"><? echo $row['uname'] ?></a><?}else{echo $row['shippingAddr_name'];}  ?></td>
                                    <td><a href="<?php echo ADMINURL ?>vendor/editvendor.php?vendor=<?php echo base64_encode($row['vendor'])  ?>"><?php echo $row['vendor_name'] ?></a></td>
                                    <td><?=$row['display_product_name']; ?><br>
                                    <? $variationon=$row['variation_on'];
                                    if($variationon!=""){
                                    $variationonarr = explode("|",$variationon); $variativaluearr = explode("|",$row['variation_values']);
                                    for($v=0;$v<count($variationonarr);$v++){ ?> <span><?=$variationonarr[$v]; ?>:<?=$variativaluearr[$v]; ?></span> <? } } ?>
                                    </td>
                                    <td><?=$row['quantity'];?></td>
                                    <td><?=$getmode[0]['payment_mode'];?></td>
                                    <td><?=$row['order_current_Status'];?></td>
                                    <td><?=$order->getOrderItemDetatils($row['item_id'],"refund_id");?></td>
                                    <td><?=$order->getOrderItemDetatils($row['item_id'],"refund_response");?></td>
                                </tr>
                                <?php } } ?>
                            </tbody>
                        </table>
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
<script>$('.neworder-table').DataTable(); </script>
</body>
</html>