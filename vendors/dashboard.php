<? include("../includes/configuration.php");
include("../classes/order.php"); require_once('../classes/user.php'); require_once('../classes/product.php');
$prod = new Product(); $user = new User(); $order = new Order($prod,$user);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo SITE_TITLE; ?> : Dashboard</title>
    <? include "commoncss.php"; ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/datepicker/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/dataTable/dataTables.bootstrap4.min.css">
</head>
<body>
<div class="page-body-wrapper">
    <? include 'header.php'; ?>
    <? include 'sidebar.php'; 
    $where = "p.vendor='".$vendor."' and p.isActive='1' ";
    $live_order = selectQuery(ORDERSUB,"count(item_id) as live_order","order_current_Status IN ('Generated','Invoiced','New Order','New', 'Waiting For Ship' ,'Ready To Ship','Pickup Scheduled','Pickup Queue','Pickup Rescheduled','Pickup Error','Pickup Exception','Out For Pickup','Shipped','Out for Delivery','In Transit','Reached Destination Hub','Misrouted','Lost','Undelivered','Delayed','Picked Up','Awb Assigned','Label Generated','Manifest Generated','Pending') and vendor = '".$vendor."' ");
    $cancelled_order = selectQuery(ORDERSUB,"count(item_id) as total_order","order_current_Status In ('Cancellation Request','Cancellation Requested','Canceled') and vendor = '".$vendor."' ");
    $get_disbursment_data = selectQuery(PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id ","sum(quantity *( vendor_per_item_price_withoutgst+(vendor_per_item_price_withoutgst*tax_percentage /100))) as total,count(item_id) as ordcnt","(o.order_current_Status='Delivered' OR o.order_current_Status='Return Rejected' or o.order_current_Status='Return Cancelled'  or  o.order_current_Status='Fulfilled' or o.order_current_Status='Archived' or o.order_current_Status='Partial Delivered') and o.vendor='".$vendor."' and ispaid = '0'");
    $getdata=selectQuery(PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id","o.product_name,o.display_product_name, o.quantity,sum(o.quantity) as tot","p.purchase_from_vendor=".$vendor." and ( MONTH(purchase_date) = MONTH(CURRENT_DATE()) AND YEAR(purchase_date) = YEAR(CURRENT_DATE())) group by product_id order by tot DESC limit 15");
    $stockalert = selectQuery(PRODINFO,"id,prod_name,stock,sold", "stock-sold <= ".STOCK_ALERT." and stock <> 0  and vendor = '".$vendor."'  order by stock-sold asc limit 15");
    $total_active_product=$prod->getcount($where,"","","");
    $month_delivered = selectQuery(ORDERSUB,"count(*) as totalOrder,MONTH(delivered_on)as month","delivered_on != '0000-00-00 00:00:00' and vendor= '".$vendor."' GROUP BY DATE_FORMAT(delivered_on, '%Y%m')  ");
    $month_canceled = selectQuery(ORDERSUB,"count(*) as totalOrder,MONTH(cancelation_date)as month","cancelation_date != '0000-00-00 00:00:00' and vendor= '".$vendor."' GROUP BY DATE_FORMAT(cancelation_date, '%Y%m')");
    $month_returned = selectQuery(ORDERSUB,"count(*) as totalOrder,MONTH(return_request_date )as month","return_request_date  != '0000-00-00 00:00:00'  and vendor= '".$vendor."' GROUP BY DATE_FORMAT(return_request_date , '%Y%m')  ");
    //delivered data
    $graphDataTemp = array();
    foreach($month_delivered as $value){
        $graphDataTemp[$value['month']]=$value['totalOrder'];
    }
    for($i=1; $i<=12; $i++){
        if(isset($graphDataTemp[$i]))
        $graphData[]= $graphDataTemp[$i];
        else
        $graphData[] = 0;
    }
    //cancelled data 
    $graphcancelDataTemp = array();
    foreach($month_canceled as $value){
        $graphcancelDataTemp[$value['month']]=$value['totalOrder'];
    }
    for($i=1; $i<=12; $i++){
        if(isset($graphcancelDataTemp[$i]))
        $graphcancelData[]= $graphcancelDataTemp[$i];
        else
        $graphcancelData[] = 0;
    } 
    // returned
    $graphreturnDataTemp = array();
    foreach($month_returned as $value){
    $graphreturnDataTemp[$value['month']]=$value['totalOrder'];
    }
    for($i=1; $i<=12; $i++){
        if(isset($graphreturnDataTemp[$i]))
        $graphreturnData[]= $graphreturnDataTemp[$i];
        else
        $graphreturnData[] = 0;
    } ?>
    <div class="main-panel">
        <div class="dashbody">            
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-sm-6 col-md-6 col-lg-3">
                            <div class="dash-update-tiles vendor-dail-update card mb-3 p-3 bg-success text-white">
                                <a href="<?php echo VENDORURL; ?>product/viewproduct.php">
                                    <div class="d-flex flex-wrap justify-content-between justify-content-xl-center justify-content-xl-between align-items-center">
                                        <span class="fa fa-cube position-absolute bg-white text-success"></span>
                                        <div class="h3 mb-0"><?php echo $total_active_product; ?></div>
                                    </div><h6 class="mt-3 mb-0">Total Active Product</h6>
                                </a>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-3">
                            <div class="dash-update-tiles vendor-dail-update card mb-3 p-3 bg-warning text-white">
                                <a href="<?php echo VENDORURL; ?>order/cancel.php">
                                    <div class="d-flex flex-wrap justify-content-between justify-content-xl-center justify-content-xl-between align-items-center">
                                    <span class="fa fa-truck position-absolute bg-white text-warning"></span>
                                    <div class="h3 mb-0"><?php echo $cancelled_order[0]['total_order'] ?></div>
                                    </div><h6 class="mt-3 mb-0">Cancelled Orders</h6>
                                </a>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-3">
                            <div class="dash-update-tiles vendor-dail-update card mb-3 p-3 bg-primary  text-white">
                                <a href="<?php echo VENDORURL; ?>order">
                                    <div class="d-flex flex-wrap justify-content-between justify-content-xl-center justify-content-xl-between align-items-center">
                                    <span class="fa fa-refresh position-absolute bg-white text-primary"></span>
                                    <div class="h3 mb-0"><?php echo $live_order[0]['live_order'] ?></div>
                                    </div><h6 class="mt-3 mb-0">Inprocess Orders</h6>
                                </a>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-3">
                            <div class="dash-update-tiles vendor-dail-update card mb-3 p-3 bg-danger text-white">
                                <a href="<?php echo VENDORURL; ?>myaccount/disbursement.php">
                                    <div class="d-flex flex-wrap justify-content-between justify-content-xl-center justify-content-xl-between align-items-center">
                                    <span class="fa fa-inr position-absolute bg-white text-danger"></span>
                                    <div class="h3 mb-0"><?php echo round($get_disbursment_data[0]['total']) ?></div>
                                    </div><h6 class="mt-3 mb-0">Pending Amount</h6>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header sec-card-head justify-content-between align-items-center"><div><h5 class="card-head-title">Product Updates</h5></div></div>
                        <div class="card-body"><div class="pro-update-chart"></div></div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h5 class="card-head-title">Disbursment Reports</h5></div></div>
                <div class="card-body">
                    <div class="row"> 
                        <div class="form-group col-sm-4 col-md-6 col-lg-5">
                            <div class="input-group">
                                <input type="text" id="fromdt" class="form-control form-control-lg border-right-0" placeholder="Enter From Date">
                                <div class="input-group-append"><span class="input-group-text bg-white"><span class="fa fa-calendar"></span></span></div>
                            </div>
                        </div>
                        <div class="form-group col-sm-4 col-md-6 col-lg-5">
                            <div class="input-group">
                                <input type="text" id="todt" class="form-control form-control-lg border-right-0" placeholder="Enter To Date">
                                <div class="input-group-append"><span class="input-group-text bg-white"><span class="fa fa-calendar"></span></span></div>
                            </div>
                        </div>
                        <div class="form-group col-sm-4 col-md-6 col-lg-2"><button type="button" class="btn btn-primary btn-block" onclick="generatereport()">View Report</button></div>         
                    </div>
                    <div class="row ser-cout-dis border-top">
                        <div class="col-6 col-sm-6 col-md-6 col-lg-3 pt-3">
                            <div class="media">
                                <i class="fa fa-shopping-bag mr-3 bg-primary text-white"></i>
                                <div class="media-body">
                                    <div class="h4 mb-0"><b id="order_date_range"> 0</b></div>
                                    <h6 class="mb-0">Total Orders</h6>
                                </div>
                            </div>
                        </div> 
                        <div class="col-6 col-sm-6 col-md-6 col-lg-3 pt-3">
                            <div class="media">
                                <i class="fa fa-inr mr-3 bg-success text-white"></i>
                                <div class="media-body">
                                    <div class="h4 mb-0"><b id="vendor_date_range">0</b></div>
                                    <h6 class="mb-0">Total Sale</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-6 col-md-6 col-lg-3 pt-3">
                            <div class="media">
                                <i class="fa fa-inr mr-3 bg-success text-white"></i>
                                <div class="media-body">
                                    <div class="h4 mb-0"><b id="vendor_total_recieved">0</b></div>
                                    <h6 class="mb-0">Total Received</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-6 col-md-6 col-lg-3 pt-3">
                            <div class="media">
                                <i class="fa fa-clock-o mr-3 bg-danger text-white"></i>
                                    <div class="media-body"> 
                                    <div class="h4 mb-0"><b id="vendor_total_pending">0</b></div>
                                    <h6 class="mb-0">Total Pending</h6>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-lg-6">
                    <div class="card">
                        <div class="card-header sec-card-head justify-content-between align-items-center"><div><h5 class="card-head-title">Highest Selling Products - For <? echo date('M')." ".date('Y'); ?></h5></div><a href="highest_selling.php" class="btn btn-primary btn-sm" target="_blank">View</a></div>
                        <div class="card-body limited-cont-overflow">
                            <table id="recent-orders" class="table table-bordered mb-0 ps-container ps-theme-default">
                                <thead><tr><th>Products</th><th style="width:40px;" class="text-center">Sales</th>
                                </tr></thead>
                                <tbody>
                                    <?php if(count($getdata)){
                                    for($i=0;$i<count($getdata);$i++){ ?>
                                    <tr>
                                        <td><?php echo $getdata[$i]['product_name'] ?></td>
                                        <td class="text-center"><?php echo $getdata[$i]['tot'] ?></td>
                                    </tr>
                                    <?php } }else{
                                        echo "<tr><td colspan='2'>No Product sold in this month</td></tr>";
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-6">
                    <div class="card">
                        <div class="card-header sec-card-head justify-content-between align-items-center"><div><h5 class="card-head-title">Stock Alerts (Less Than <?php echo STOCK_ALERT ?>)</h5></div><a href="stock_alert.php" class="btn btn-primary btn-sm" target="_blank">View</a></div>
                        <div class="card-body limited-cont-overflow">
                            <table id="recent-orders2" class="table table-bordered mb-0 ps-container ps-theme-default">
                                <thead><tr><th>Products</th><th style="width:40px;" class="text-center">Stock</th></tr></thead>
                                <tbody>
                                    <?php if(count($stockalert)){
                                    for($i=0;$i<count($stockalert);$i++){ ?>
                                    <tr>
                                        <td><?php echo $stockalert[$i]['prod_name'] ?></td>
                                        <td class="text-center"><?php echo $stockalert[$i]['stock']- $stockalert[$i]['sold'] ?></td>
                                    </tr>
                                    <?php } }else{
                                    echo "<tr><td colspan='2'>All product have sufficient stock </td></tr>";
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="card edit_template mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2">
                    <div><h5 class="card-head-title">Disbursement Details</h5></div>
                    <div class="btn-actions-pane-right"><a href="<?php echo VENDORURL; ?>myaccount/disbursement.php" class="btn btn-primary btn-sm">View</a></div>
                </div>
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
                            <div class="table-responsive">
                                <table class="display table table-bordered disbursement-table">
                                    <thead><tr><th>#</th><th>Order Date</th><th>Order ID</th><th>Product Details</th><th>Quantity</th><th>Disbursements Amount</th><th>Status</th></tr></thead>
                                    <tbody>
                                        <?php $orderdata = $order->disbursmentOrders("Vendor_today",$vendor);
                                        if(count($orderdata)){
                                        for($j=0;$j<count($orderdata);$j++){ $cnt=$j+1; ?>
                                        <tr id='<?php echo $orderdata[$j]['pur_id'] ?>'>
                                            <td><?=$cnt; ?></td>
                                            <td><?=date("d M Y h:i A",strtotime($orderdata[$j]['purchase_date'])) ?></td>
                                            <td class="text-primary cc-cursor-pointer" onclick="get_item(<?php echo $orderdata[$j]['item_id'] ?>)"><?=$orderdata[$j]['purchase_order_id']; ?></td>
                                            <td style="width:200px;">
                                                <?=$orderdata[$j]['display_product_name']; ?><br>
                                                <? $variationon=$orderdata[$j]['variation_on'];
                                                if($variationon!=""){
                                                $variationonarr=explode("|",$variationon); $variativaluearr=explode("|",$orderdata[$j]['variation_values']);
                                                for($v=0;$v<count($variationonarr);$v++){ ?><span> <?=$variationonarr[$v]; ?>:<?=$variativaluearr[$v]; ?></span> <? } } ?>
                                            </td>
                                            <td><?=$orderdata[$j]['quantity'];?></td>
                                            <td><i class="fa fa-inr"></i><?= $orderdata[$j]['quantity'] * ($orderdata[$j]['vendor_per_item_price_withoutgst']+($orderdata[$j]['vendor_per_item_price_withoutgst']*$orderdata[$j]['tax_percentage']/100));?>
                                            </td>
                                            <td><?php if($orderdata[$j]['ispaid'] == '0'){ echo "Pending"; } else{ echo "Recieved"; } ?></td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade show" id="nav-pending" role="tabpanel" aria-labelledby="nav-pending-tab">
                            <span class="mb-3 d-inline-block disburs-tot-amt dis-amt-count bg-primary text-white rounded py-1 px-2"><b>Pending</b> Total Disbursment Amount : <i class="fa fa-inr"></i><?php echo round($Vendor_pending[0]['total']); ?></span>
                            <div class="table-responsive">
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
                                            <td style="width:200px;">
                                                <?=$orderdata[$j]['display_product_name']; ?><br>
                                                <? $variationon=$orderdata[$j]['variation_on'];
                                                if($variationon!=""){
                                                $variationonarr=explode("|",$variationon); $variativaluearr=explode("|",$orderdata[$j]['variation_values']);
                                                for($v=0;$v<count($variationonarr);$v++){ ?> <span> <?=$variationonarr[$v]; ?>:<?=$variativaluearr[$v]; ?></span> <? } } ?>
                                            </td>
                                            <td><?=$orderdata[$j]['quantity'];?></td>
                                            <td><?=date("d M Y h:i A",strtotime($orderdata[$j]['disbustment_date'])) ?></td>
                                            <td>
                                            <i class="fa fa-inr"></i><?= $orderdata[$j]['quantity'] * ($orderdata[$j]['vendor_per_item_price_withoutgst']+($orderdata[$j]['vendor_per_item_price_withoutgst']*$orderdata[$j]['tax_percentage']/100));?>
                                            </td>
                                            <td ><?php if($orderdata[$j]['ispaid'] == '0'){ echo "Pending"; } else{ echo "Recieved"; } ?></td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade show " id="nav-recieved" role="tabpanel" aria-labelledby="nav-recieved-tab">
                            <span class="mb-3 d-inline-block disburs-tot-amt dis-amt-count bg-primary text-white rounded py-1 px-2"><b>Recieved</b> Total Disbursment Amount : <i class="fa fa-inr"></i><?php echo round($Vendor_recieved[0]['total']); ?></span>
                            <div class="table-responsive">
                                <table class="display table table-bordered disbursement-table" >
                                    <thead><tr><th>#</th><th>Order Date</th><th>Order ID</th> <th>Product Details</th><th>Quantity</th><th>Disbursements Date</th> <th>Disbursements Amount</th> <th>Status </th></tr></thead>
                                    <tbody>
                                        <?php $orderdata = $order->disbursmentOrders("Vendor_recieved",$vendor);
                                        if(count($orderdata)){
                                        for($j=0;$j<count($orderdata);$j++){ $cnt=$j+1; ?>
                                        <tr id='<?php echo $orderdata[$j]['pur_id'] ?>'>
                                            <td><?=$cnt; ?></td>
                                            <td><?=date("d M Y h:i A",strtotime($orderdata[$j]['purchase_date'])) ?></td>
                                            <td class="text-primary cc-cursor-pointer" onclick="get_item(<?php echo $orderdata[$j]['item_id'] ?>)"><?=$orderdata[$j]['purchase_order_id']; ?></td>
                                            <td style="width:200px;">
                                                <?=$orderdata[$j]['display_product_name']; ?><br>
                                                <? $variationon=$orderdata[$j]['variation_on'];
                                                if($variationon!=""){
                                                    $variationonarr = explode("|",$variationon); $variativaluearr = explode("|",$orderdata[$j]['variation_values']);
                                                    for($v=0;$v<count($variationonarr);$v++){ ?> <span><?=$variationonarr[$v]; ?>:<?=$variativaluearr[$v]; ?></span> <? }
                                                } ?>
                                            </td>
                                            <td><?=$orderdata[$j]['quantity'];?></td>
                                            <td><?=date("d M Y h:i A",strtotime($orderdata[$j]['disbustment_date'])) ?></td>
                                            <td>
                                                <i class="fa fa-inr"></i><?= $orderdata[$j]['quantity'] * ( $orderdata[$j]['vendor_per_item_price_withoutgst']+($orderdata[$j]['vendor_per_item_price_withoutgst']*$orderdata[$j]['tax_percentage']/100));?>
                                            </td>
                                            <td ><?php if($orderdata[$j]['ispaid'] == '0'){ echo "Pending"; } else{ echo "Recieved"; } ?></td>
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
        <? include "footer.php"; ?>
    </div>
</div>
<?php include 'order/order_common.php'; ?>
<script src="<?php echo SITEURL; ?>/js/backend/chart.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/backend/utils.js"></script>
<script src="<?php echo SITEURL; ?>/js/datepicker/moment.js"></script>
<script src="<?php echo SITEURL; ?>/js/datepicker/bootstrap-datetimepicker.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script> 
$('.disbursement-table').DataTable();
$('#fromdt').datetimepicker({
    ignoreReadonly: true,
    format: 'DD-MM-YYYY HH:mm',
    disabledTimeIntervals:false
}).on("dp.change", function (e) {
    $("#todt").data("DateTimePicker").minDate(e.date);
});
$('#todt').datetimepicker({
    ignoreReadonly: true,
    format: 'DD-MM-YYYY HH:mm',
    disabledTimeIntervals:false
});
function generatereport(){
    fromdt = $("#fromdt").val();
    todt = $("#todt").val();
    new_fromdt = fromdt.split("-").reverse().join("-");
    new_todt = todt.split("-").reverse().join("-");
    if(fromdt == "" || todt == ""){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please fill Details").delay(3000).fadeOut();
    } else if(new_fromdt > new_todt ){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please Check Dates").delay(3000).fadeOut();
    } else{ 
        var info = {fromdt:fromdt, todt:todt,action:"date_range_search"};
        $.ajax({
            type: "POST",
            url: "searchdata.php", 
            data: info,
            success: function(response){
                jsondata = JSON.parse(response);
                vendor_total_recieved = jsondata['vendor_total_recieved'];
                vendor_total_pending = jsondata['vendor_total_Pending'];
                vendor_total_sale = jsondata['vendor_total_sale'];
                order = jsondata['order']; 
                $("#order_date_range").html(order);
                $("#vendor_total_recieved").html(vendor_total_recieved); 
                 
                $("#vendor_date_range").html(vendor_total_sale);
                $("#vendor_total_pending").html(vendor_total_pending); 
            }
        });
    }
}     
var color = Chart.helpers.color;
function createConfig(){
    return {
        type: 'line',
        data: {
            labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','August','Sept','Oct','Nov','Dec'],
            datasets: [{
                label: 'Delivered',
                backgroundColor: "#28A745",
                borderColor: "#28A745",
                data: <?=json_encode($graphData); ?>,
                fill: false,
                },{
                label: 'Cancelled',
                borderColor: "#DC3545",
                backgroundColor: "#DC3545",
                data: <?php echo json_encode($graphcancelData)  ?>,
                fill: false,
            },{
                label: 'Return',
                borderColor: "#FFC107",
                backgroundColor: "#FFC107",
                data: <?php echo json_encode($graphreturnData)  ?>,
                fill: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            title: {
                display: false,
                text: 'Sample tooltip with border',
                position: "top",
            },
            legend: {
                    position: "top",
                    
                    
                    labels: {
                boxWidth: 13,
                      defaultFontFamily: "Roboto",
            }
                    
                    
                    
                },
            scales: {
						x: {
							display: true,
							scaleLabel: {
								display: true,
								labelString: 'Month'
							}
						},
						y: {
							display: true,
							scaleLabel: {
								display: true,
								labelString: 'Value'
							}
						}, yAxes: [{ticks: {min: 0}}]
					}
        }
    };
}
window.onload = function(){
    var linegrcontain = document.querySelector('.pro-update-chart');
    var ininsdiv = document.createElement('div');
    ininsdiv.classList.add('chart-container');
    var canvas = document.createElement('canvas');
    ininsdiv.appendChild(canvas);
    linegrcontain.appendChild(ininsdiv);
    var linegracanvas = canvas.getContext('2d');
    var lingraconfig = createConfig();
    new Chart(linegracanvas, lingraconfig);
};
</script>
</body>
</html>