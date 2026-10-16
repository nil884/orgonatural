<?php include ("../includes/configuration.php"); ?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Stock Alert</title>
    <?php include('commoncss.php'); ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/dataTable/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/datepicker/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/dataTable/dataTable.buttons.min.css" />
</head>
<body>
<div class="page-body-wrapper">
    <?php include ('header.php'); include ('sidebar.php');
    $stockalert = selectQuery(PRODINFO,"id,prod_name,stock,sold", "stock-sold <= ".STOCK_ALERT." and stock <> 0 and vendor = '".$_SESSION['seller']."'  order by stock-sold asc"); ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2"><div><h2 class="card-head-title mb-2 mb-sm-0">Stock alerts ( Less than <?php echo STOCK_ALERT ?> products )</h2></div><div class="btn-actions-pane-right"> <a class="btn btn-secondary btn-sm" href="home.php"> Back</a></div></div>
                <div class="card-body">
                    <table id="recent-orders2" class="table table-bordered ps-container ps-theme-default w-100">
                        <thead><tr><th>#</th><th>Products</th><th class="text-center">Stock</th></tr></thead>
                        <tbody>
                            <?php if(count($stockalert)){
                            for($i=0;$i<count($stockalert);$i++){ ?>
                            <tr> <td><?php echo $i+1 ?> </td><td><?php echo $stockalert[$i]['prod_name'] ?></td>
                            <td class="text-center"><?php echo $stockalert[$i]['stock']- $stockalert[$i]['sold'] ?></td></tr>
                            <?php } } else{
                            echo "<tr class='text-center'><td colspan='3'>All product have sufficient stock</td></tr>";
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
    var table = $('#recent-orders2').DataTable({ "scrollX": true, buttons: ['excel'] });
    table.buttons().container().appendTo('.btn-actions-pane-right');
})
</script>
</body>
</html>