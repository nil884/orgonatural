<? include("../../includes/configuration.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo SITENAME; ?> : Refund Pendings</title>
    <? include "../commoncss.php"; ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/dataTable/dataTables.bootstrap4.min.css">
</head>
<body id="bodload">
<div class="page-body-wrapper" id="prod-list-col">
    <? include '../header.php'; ?>
    <? include '../sidebar.php';
    $getrefundable=selectQuery(ORDERSUB." as sub JOIN ".ORDER." as ord on sub.order_id=ord.id JOIN ".BUYER." as usr on ord.user_id=usr.u_id","ord.user_id,ord.order_id,ord.transaction_id,usr.u_fname,usr.u_lname,sub.refundable_amount,sub.item_id","sub.is_refund_appilicable='1' and sub.refund_status='0' and ord.payment_mode='COD'"); ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center"><h5 class="card-head-title">Refund Pendings</h5></div>
                <div class="card-body">
                    <div id="tablereload">
                        <table class="table table-bordered product-data-table w-100" id="Vendor-product">
                            <thead><tr><th>#</th><th>Order ID</th><th>User</th><th>Refundable Amount</th><th>Action</th></tr></thead>
                            <tbody class="text-muted" id="loaddatatabel">
                                <?php for($i=0;$i<count($getrefundable);$i++){ ?>
                                    <tr>
                                        <td><?=$i+1; ?></td><td><a href="<?=ADMINURL ?>order/order_details.php?transid=<? echo $getrefundable[$i]['transaction_id']; ?>" target="_blank"><?=$getrefundable[$i]['order_id']; ?></a></td><td><a href="<?=ADMINURL ?>buyer/buyersdetails.php?u_id=<?=base64_encode($getrefundable[$i]['user_id']) ?>" target="_blank"><?=$getrefundable[$i]['u_fname']." ".$getrefundable[$i]['u_lname'];?></a></td><td><?=$getrefundable[$i]['refundable_amount']; ?></td><td><a class="btn btn-primary" href="refunding.php?item=<?=base64_encode($getrefundable[$i]['item_id']); ?>">Process</a></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <? include "../footer.php"; ?>
    </div>
</div>
<script src="<?php echo SITEURL; ?>/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script>$('.product-data-table').DataTable({ "scrollX": true });</script>
</body>
</html>