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
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Delivered Order (<?=$order->getVendorOrdersCount("","Delivered"); ?>)</h2></div></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="display table table-bordered neworder-table" >
                            <thead><tr><th>#</th><th>Order Date</th><th>Order ID</th><th>Sub-Order ID</th><th>Vendor Name</th><th>Product Details</th><th>Quantity</th></tr></thead>
                            <tbody>
                                <?php $orderdata = $order->getVendorOrders("","Delivered");
                                if(count($orderdata)){
                                for($j=0;$j<count($orderdata);$j++){
                                $cnt=$j+1; ?>
                                <tr>
                                    <td><?=$cnt; ?></td>
                                    <td><?=date("d M Y h:i A",strtotime($orderdata[$j]['purchase_date'])) ?></td>
                                    <td class="text-primary cc-cursor-pointer"> <a href="order_details.php?transid=<? echo $orderdata[$j]['transaction_id']; ?>"> <?php echo $orderdata[$j]['order_id'] ?> </a> </td> 
                                    <td class="text-primary cc-cursor-pointer" onclick="get_item(<?php echo $orderdata[$j]['item_id'] ?>)"><a> <?=$orderdata[$j]['purchase_order_id']; ?> </a></td>
                                    <td><a href="<?php echo ADMINURL ?>vendor/editvendor.php?vendor=<?php echo base64_encode($orderdata[$j]['vendor'])  ?>"><?php echo $orderdata[$j]['vendor_name'] ?></a></td>
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
$('.neworder-table').DataTable(); 
</script>
</body>
</html>