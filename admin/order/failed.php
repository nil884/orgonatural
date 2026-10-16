<? include("../../includes/configuration.php");
include("../../classes/order.php"); require_once('../../classes/user.php'); require_once('../../classes/product.php');
$prod = new Product(); $user = new User(); $order = new Order($prod,$user); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo SITE_TITLE; ?> : Failed Orders</title>
    <? include "../commoncss.php"; ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/dataTable/dataTables.bootstrap4.min.css">
</head>
<body>
<div class="page-body-wrapper">
    <? include '../header.php'; ?>
    <? include '../sidebar.php';
    /* $month=date("Y-m-d H:i:s",strtotime("-30 days"));
    $initiatedcnt=selectQuery(ORDER,"count(id) as cnt","payment_status='Initiated' AND order_date>'".$month."'");  $initiated=selectQuery(ORDER,"id,transaction_id,order_date,payment_mode,user_id,payable_amount,payment_status","payment_status='Initiated' AND order_date>'".$month."' order by order_date DESC"); ?> */
    $month = date("Y-m-d H:i:s",strtotime("-30 days"));
    $failedcnt = selectQuery(ORDER,"count(id) as cnt","payment_status='failure' OR payment_status='failed' "); 
    $failed = selectQuery(ORDER,"id,transaction_id,order_date,payment_mode,user_id,payable_amount,payment_status,gateway_response","payment_status='failure' OR payment_status='failed' order by order_date DESC"); 

    $initiatedcnt = selectQuery(ORDER,"count(id) as cnt","payment_status='Initiated'"); 
    $initiated = selectQuery(ORDER,"id,transaction_id,order_date,payment_mode,user_id,payable_amount,payment_status","payment_status='Initiated' order by order_date DESC"); ?>
    <div class="main-panel">
        <div class="dashbody">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item"><a class="nav-link active" id="failed-tab" data-toggle="tab" href="#failed" role="tab" aria-controls="failed" aria-selected="true">Failed Order (<?=$failedcnt[0]['cnt'];?>)</a></li>
            <li class="nav-item"><a class="nav-link" id="initiated-tab" data-toggle="tab" href="#initiated" role="tab" aria-controls="initiated" aria-selected="false">Payment Pending (<?=$initiatedcnt[0]['cnt'];?>)</a></li></ul>
            <div class="card mb-0 border-top-0 rounded-bottom">
                <div class="card-body">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="failed" role="tabpanel" aria-labelledby="failed-tab">
                            <div class="table-responsive">
                                <table class="display table table-bordered neworder-table" >
                                    <thead><tr><th>#</th><th>Transaction Date</th><th>Transaction ID</th><th>Buyer Name</th><th>Payment Mode</th><th>Amount</th> <th>Status</th><th>Reason</th></tr></thead>
                                    <tbody>
                                        <?php if(count($failed)){
                                        for($j=0;$j<count($failed);$j++){
                                        $row = $failed[$j];
                                        $buyer = selectQuery(BUYER,"u_fname,u_lname,u_mobile","u_id=".$row['user_id']);
                                        $cnt = $j+1; ?>
                                        <tr>
                                            <td><?=$cnt; ?></td>
                                            <td><?=date("d M Y h:i A",strtotime($row['order_date'])) ?></td>
                                            <td class="text-primary cc-cursor-pointer"><a href="<?=ADMINURL ?>order/failed_order_details.php?transid=<?=$row['transaction_id']; ?>" ><?=$row['transaction_id']; ?></a></td>
                                            <td class="cc-cursor-pointer"><a href="<?=ADMINURL ?>buyer/buyersdetails.php?u_id=<?=base64_encode($row['user_id']) ?>" target="_blank"><?=$buyer[0]['u_fname']." ".$buyer[0]['u_lname'];?></a></td>
                                            <td><?=$row['payment_mode']; ?></td>
                                            <td><?=$row['payable_amount']; ?></td>
                                            <td><?=($row['payment_status']=="Initiated"?"Payment Pending":$row['payment_status']);?></td>
                                            <td><?=$row['gateway_response'];?></td> 
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="initiated" role="tabpanel" aria-labelledby="initiated-tab">
                            <div class="table-responsive">
                                <table class="display table table-bordered initiated-table" >
                                    <thead><tr><th>#</th><th>Transaction Date</th><th>Transaction ID</th><th>Buyer Name</th><th>Payment Mode</th><th>Amount</th><th>Status</th></tr></thead>
                                    <tbody>
                                        <?php if(count($initiated)){
                                        for($j=0;$j<count($initiated);$j++){
                                        $row = $initiated[$j];
                                        $buyer = selectQuery(BUYER,"u_fname,u_lname,u_mobile","u_id=".$row['user_id']);
                                        $cnt = $j+1; ?>
                                        <tr>
                                            <td><?=$cnt; ?></td>
                                            <td><?=date("d M Y h:i A",strtotime($row['order_date'])) ?></td>
                                            <td class="text-primary cc-cursor-pointer"><a href="<?=ADMINURL ?>order/failed_order_details.php?transid=<?=$row['transaction_id']; ?>" target="_blank"><?=$row['transaction_id']; ?></a></td>
                                            <td class="cc-cursor-pointer"><a href="<?=ADMINURL ?>buyer/buyersdetails.php?u_id=<?=base64_encode($row['user_id']) ?>" target="_blank"><?=$buyer[0]['u_fname']." ".$buyer[0]['u_lname'];?></a></td>
                                            <td><?=$row['payment_mode']; ?></td>
                                            <td><?=$row['payable_amount']; ?></td>
                                            <td><?=($row['payment_status']=="Initiated"?"Payment Pending":$row['payment_status']);?></td> 
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                </table>
                            </div>
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
<script>$('.neworder-table,.initiated-table').DataTable(); </script>
</body>
</html>