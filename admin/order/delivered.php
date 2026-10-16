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
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Delivered Order (<?=$order->getVendorOrdersCount("","'Delivered','Fulfilled','Archived','Partial Delivered'"); ?>)</h2></div></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="display table table-bordered neworder-table">
                            <thead><tr><th>#</th><th>Order Date</th><th>Order ID</th><th>Buyer Name</th><th>Vendor Name</th><th>Status</th></tr></thead>
                            <tbody>
                                <?php $orderdata = $order->getVendorOrders("","'Delivered','Fulfilled','Archived','Partial Delivered'");
                                if(count($orderdata)){
                                for($j=0;$j<count($orderdata);$j++){
                                $cnt = $j+1;
                                $getorderType=selectQuery(ORDER,"orderType","transaction_id='".$orderdata[$j]['transaction_id']."'");
                                $orderType= $getorderType[0]['orderType']; ?>
                                <tr>
                                    <td><?=$cnt; ?></td>
                                    <td><?=date("d M Y h:i A",strtotime($orderdata[$j]['purchase_date'])) ?></td>
                                    <td class="text-primary cc-cursor-pointer"> <a href="order_details.php?transid=<? echo $orderdata[$j]['transaction_id']; ?>"><?php echo $orderdata[$j]['order_id'].($orderType=="POS"?" (POS)":""); ?></a> </td> 
                                    <td class=" cc-cursor-pointer"><? if($orderdata[$j]['user_id']){?><a href="<?=ADMINURL ?>buyer/buyersdetails.php?u_id=<?=base64_encode($orderdata[$j]['user_id']) ?>" target="_blank"><? echo $orderdata[$j]['uname'] ?></a><?}else{echo $orderdata[$j]['shippingAddr_name'];}  ?></td>
                                    <td><a href="<?php echo ADMINURL ?>vendor/editvendor.php?vendor=<?php echo base64_encode($orderdata[$j]['vendor'])  ?>"><?php  echo $orderdata[$j]['vendor_name'] ?></a></td>
                                   
                                    <td><?=$orderdata[$j]['order_current_Status'];?></td>
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