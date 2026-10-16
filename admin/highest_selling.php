<?php include ("../includes/configuration.php"); ?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Highest Selling</title>
    <?php include('commoncss.php'); ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/dataTable/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/datepicker/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/dataTable/dataTable.buttons.min.css" />
</head>
<body>
<div class="page-body-wrapper">
    <?php include ('header.php'); include ('sidebar.php');
    $getdata = selectQuery(PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id","o.product_id,o.product_name,o.display_product_name, o.quantity,sum(o.quantity) as tot","(o.order_current_status<>'failure' AND o.order_current_status<>'Canceled') AND (MONTH(p.purchase_date) = MONTH(CURRENT_DATE()) AND YEAR(p.purchase_date) = YEAR(CURRENT_DATE())) group by o.product_id order by tot DESC"); ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card mb-0">
                 <div class="card-header sec-card-head justify-content-between align-items-center py-2"><div><h2 class="card-head-title mb-2 mb-sm-0">Highest Selling Products - For <? echo date('M')." ".date('Y'); ?></h2></div><div class="btn-actions-pane-right"><a class="btn btn-secondary btn-sm" href="home.php"> Back</a></div></div>
                <div class="card-body">
                    <table id="recent-orders" class="table table-bordered ps-container ps-theme-default w-100">
                        <thead><tr><th class="row-index-tr">#</th><th>Products</th><th>Sales</th></tr></thead>
                        <tbody>
                                    <?php if(count($getdata)){
                                    for($i=0;$i<count($getdata);$i++){ $r=$getdata[$i]; ?>
                                    <tr><td><?=$i+1; ?></td><td><a href="<?=ADMINURL; ?>product/view-details.php?prod_id=<?=base64_encode($r['product_id']); ?>" target="_blank"><?php echo $r['product_name']; ?></a></td><td class="text-center"><?php echo $r['tot']; ?></td></tr>
                                    <?php } } else{
                                        echo "<tr><td colspan='2'>No product sold in this month</td></tr>";
                                    } ?>
                                </tbody>
                    </table>  
                </div>
            </div>
        </div>
        <?php include 'footer.php'; ?>
    </div>
</div>
<script src="<?php echo SITEURL; ?>/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/dataTables.buttons.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/jszip.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/buttons.html5.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/buttons.colVis.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/buttons.bootstrap4.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/datepicker/moment.js"></script>
<script src="<?php echo SITEURL; ?>/js/datepicker/bootstrap-datetimepicker.min.js"></script>
<script>
$(document).ready(function(){
    var table = $('#recent-orders').DataTable({ "scrollX": true, buttons: ['excel'] });
    table.buttons().container().appendTo('.btn-actions-pane-right');
})
</script> 
</body>
</html>