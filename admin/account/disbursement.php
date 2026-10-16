<? include("../../includes/configuration.php");
include("../../classes/order.php"); require_once('../../classes/user.php'); require_once('../../classes/product.php');
$prod = new Product(); $user = new User(); $order = new Order($prod,$user); ?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo SITE_TITLE; ?> : Disbursement Details</title>
    <? include "../commoncss.php"; ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/dataTable/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/datepicker/bootstrap-datetimepicker.min.css">
</head>
<body>
<div class="page-body-wrapper">
    <? include '../header.php'; ?>
    <? include '../sidebar.php'; ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card edit_template mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Disbursement Details</h2></div></div>
                <div class="card-body">
                    <div class="py-2 px-3 alert alert-info">Disbursement Days = Product Delivery Date + Applicable Return Days On Product + Disbursement Cycle Days For Vendor</div>
                    <nav class="dis_link mb-3">
                        <div class="nav nav-tabs disburs-nav" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link active" id="nav-today-tab" data-toggle="tab" href="#nav-today" role="tab" aria-controls="nav-today" aria-selected="true">Today (<?php $today = $order->disbursmentOrdersCount("Today"); echo $today[0]['ordcnt']; ?>)</a>
                            <a class="nav-item nav-link" id="nav-tomorrow-tab" data-toggle="tab" href="#nav-tomorrow" role="tab" aria-controls="nav-tomorrow" aria-selected="true">Tomorrow (<?php $Tomorrow = $order->disbursmentOrdersCount("Tomorrow"); echo $Tomorrow[0]['ordcnt'];  ?>)</a>
                            <a class="nav-item nav-link" id="nav-upcoming-tab" data-toggle="tab" href="#nav-upcoming" role="tab" aria-controls="nav-upcoming" aria-selected="false">Upcoming (<?php $Upcoming = $order->disbursmentOrdersCount("Upcoming"); echo $Upcoming[0]['ordcnt']; ?>)</a>
                            <a class="nav-item nav-link" id="nav-overdue-tab" data-toggle="tab" href="#nav-overdue" role="tab" aria-controls="nav-overdue" aria-selected="false">Overdue (<?php $overdue = $order->disbursmentOrdersCount("overdue"); echo $overdue[0]['ordcnt']; ?>)</a>
                            <a class="nav-item nav-link" id="nav-paid-tab" data-toggle="tab" href="#nav-paid" role="tab" aria-controls="nav-paid" aria-selected="false">Paid (<?php  $Paid = $order->disbursmentOrdersCount("Paid"); echo $Paid[0]['ordcnt'] ?>)</a>
                        </div>
                    </nav>
                    <div class="disburs-daily-update">
                        <div class="tab-content bg-white disburs-tab-cont" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-today" role="tabpanel" aria-labelledby="nav-today-tab">
                                <span class="mb-3 d-inline-block disburs-tot-amt dis-amt-count bg-primary text-white rounded py-1 px-2"><b>Today</b> Total Disbursment Amount : <i class="fa fa-inr"></i><?php echo round($today[0]['total']); ?></span>
                                <table class="display table table-bordered disbursement-table w-100">
                                    <thead><tr><th>#</th><th>Order Date</th><th>Order ID</th><th>Vendor Name</th> <th>Product Details</th><th>Quantity</th><th>Disbursement Amount</th><th>Status</th></tr></thead>
                                    <tbody>
                                        <?php $orderdata = $order->disbursmentOrders("Today");
                                        if(count($orderdata)){
                                        for($j=0;$j<count($orderdata);$j++){  $cnt=$j+1; ?>
                                        <tr id='<?php echo $orderdata[$j]['pur_id'] ?>'>
                                            <td><?=$cnt; ?></td>
                                            <td><?=date("d M Y h:i A",strtotime($orderdata[$j]['purchase_date'])) ?></td>
                                            <td class="text-primary cc-cursor-pointer" onclick="get_item(<?php echo $orderdata[$j]['item_id'] ?>)"><?=$orderdata[$j]['purchase_order_id']; ?></td>
                                            <td><?=$orderdata[$j]['vendor_name']; ?></td>
                                            <td style="width:200px;"><?=$orderdata[$j]['display_product_name']; ?><br>
                                            <? $variationon=$orderdata[$j]['variation_on'];
                                            if($variationon!=""){
                                            $variationonarr = explode("|",$variationon); $variativaluearr = explode("|",$orderdata[$j]['variation_values']);
                                            for($v=0;$v<count($variationonarr);$v++){ ?> <span><?=$variationonarr[$v]; ?>:<?=$variativaluearr[$v]; ?></span><? } } ?>
                                            </td>
                                            <td><?=$orderdata[$j]['quantity'];?></td>
                                            <td><i class="fa fa-inr"></i><?= $orderdata[$j]['quantity']*($orderdata[$j]['vendor_per_item_price_withoutgst']+($orderdata[$j]['vendor_per_item_price_withoutgst']*$orderdata[$j]['tax_percentage']/100));?></td>
                                            <td><button class="btn btn-primary btn-sm" onclick="showmod('<?php echo $orderdata[$j]['pur_id'] ?>','<?=$orderdata[$j]['purchase_order_id']; ?>')">Update</button></td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="nav-tomorrow" role="tabpanel" aria-labelledby="nav-tomorrow-tab">
                                <span class="mb-3 d-inline-block disburs-tot-amt dis-amt-count bg-primary text-white rounded py-1 px-2"><b>Tomorrow</b> Total Disbursment Amount : <i class="fa fa-inr"></i><? echo round($Tomorrow[0]['total']) ?></span>
                                <table class="display table table-bordered disbursement-table w-100" >
                                    <thead><tr><th>#</th><th>Order Date</th><th>Order ID</th><th>Vendor Name</th> <th>Product Details</th><th>Quantity</th><th>Disbursement Amount</th><th>Status</th></tr></thead>
                                    <tbody>
                                        <?php $orderdata = $order->disbursmentOrders("Tomorrow");
                                        if(count($orderdata)){
                                        for($j=0;$j<count($orderdata);$j++){  $cnt=$j+1; ?>
                                        <tr id='<?php echo $orderdata[$j]['pur_id'] ?>'>
                                            <td><?=$cnt; ?></td>
                                            <td><?=date("d M Y h:i A",strtotime($orderdata[$j]['purchase_date'])) ?></td>
                                            <td class="text-primary cc-cursor-pointer" onclick="get_item(<?php echo $orderdata[$j]['item_id'] ?>)"><?=$orderdata[$j]['purchase_order_id']; ?></td>
                                            <td><?=$orderdata[$j]['vendor_name']; ?></td>
                                            <td style="width:200px;"><?=$orderdata[$j]['display_product_name']; ?><br>
                                            <? $variationon=$orderdata[$j]['variation_on'];
                                            if($variationon!=""){
                                            $variationonarr = explode("|",$variationon); $variativaluearr = explode("|",$orderdata[$j]['variation_values']);
                                            for($v=0;$v<count($variationonarr);$v++){ ?> <span><?=$variationonarr[$v]; ?>:<?=$variativaluearr[$v]; ?></span><? } } ?>
                                            </td>
                                            <td><?=$orderdata[$j]['quantity'];?></td>
                                            <td><i class="fa fa-inr"></i><?=$orderdata[$j]['quantity']* ($orderdata[$j]['vendor_per_item_price_withoutgst']+($orderdata[$j]['vendor_per_item_price_withoutgst']*$orderdata[$j]['tax_percentage']/100));?></td>
                                            <td><button class="btn btn-primary btn-sm" onclick="showmod('<?php echo $orderdata[$j]['pur_id'] ?>','<?=$orderdata[$j]['purchase_order_id']; ?>')">Update</button></td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="nav-upcoming" role="tabpanel" aria-labelledby="nav-upcoming-tab">
                                <span class="mb-3 d-inline-block disburs-tot-amt dis-amt-count bg-primary text-white rounded py-1 px-2"><b>Upcoming</b> Total Disbursment Amount : <i class="fa fa-inr"></i><? echo round($Upcoming[0]['total']) ?></span>
                                <table class="display table table-bordered disbursement-table w-100">
                                    <thead><tr><th>#</th><th>Order Date</th><th>Disbursement Date</th><th>Order ID</th><th>Vendor Name</th><th>Product Details</th><th>Quantity</th> <th>Disbursement Amount</th><th>Status</th></tr></thead>
                                    <tbody>
                                        <?php $orderdata = $order->disbursmentOrders("Upcoming");
                                        if(count($orderdata)){
                                        for($j=0;$j<count($orderdata);$j++){  $cnt=$j+1; ?>
                                        <tr id='<?php echo $orderdata[$j]['pur_id'] ?>'>
                                            <td><?=$cnt; ?></td>
                                            <td><?=date("d M Y h:i A",strtotime($orderdata[$j]['purchase_date'])) ?></td>
                                            <td><?=date("d M Y ",strtotime($orderdata[$j]['disbustment_date'])) ?></td>
                                            <td class="text-primary cc-cursor-pointer" onclick="get_item(<?php echo $orderdata[$j]['item_id'] ?>)"><?=$orderdata[$j]['purchase_order_id']; ?></td>
                                            <td><?=$orderdata[$j]['vendor_name']; ?></td>
                                            <td style="width:200px;"><?=$orderdata[$j]['display_product_name']; ?><br>
                                                <? $variationon=$orderdata[$j]['variation_on'];
                                                if($variationon!=""){
                                                $variationonarr = explode("|",$variationon); $variativaluearr = explode("|",$orderdata[$j]['variation_values']);
                                                for($v=0;$v<count($variationonarr);$v++){ ?><span><?=$variationonarr[$v]; ?>:<?=$variativaluearr[$v]; ?></span><? } } ?>
                                            </td>
                                            <td><?=$orderdata[$j]['quantity'];?></td>
                                            <td>
                                            <i class="fa fa-inr"></i><?= $orderdata[$j]['quantity'] *($orderdata[$j]['vendor_per_item_price_withoutgst']+($orderdata[$j]['vendor_per_item_price_withoutgst']*$orderdata[$j]['tax_percentage']/100));?>
                                            </td>
                                            <td><button class="btn btn-primary btn-sm" onclick="showmod('<?php echo $orderdata[$j]['pur_id'] ?>','<?=$orderdata[$j]['purchase_order_id']; ?>')">Update</button></td>   
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="nav-overdue" role="tabpanel" aria-labelledby="nav-overdue-tab">
                                <span class="mb-3 d-inline-block disburs-tot-amt dis-amt-count bg-primary text-white rounded py-1 px-2"><b>Overdue</b> Total Disbursment Amount : <i class="fa fa-inr"></i><? echo round($overdue[0]['total']) ?></span>
                                <table class="display table table-bordered disbursement-table w-100">
                                    <thead><tr><th>#</th><th>Order Date</th><th> Disbursement Date</th><th>Order ID</th><th>Vendor Name</th><th>Product Details</th><th>Quantity</th><th>Disbursement Amount</th><th>Status</th></tr></thead>
                                    <tbody>
                                        <?php $orderdata = $order->disbursmentOrders("overdue");
                                        if(count($orderdata)){
                                        for($j=0;$j<count($orderdata);$j++){  $cnt=$j+1; ?>
                                        <tr id='<?php echo $orderdata[$j]['pur_id'] ?>'>
                                            <td><?=$cnt; ?></td>
                                            <td><?=date("d M Y h:i A",strtotime($orderdata[$j]['purchase_date'])) ?></td>
                                            <td><?=date("d M Y ",strtotime($orderdata[$j]['disbustment_date'])) ?></td>
                                            <td class="text-primary cc-cursor-pointer" onclick="get_item(<?php echo $orderdata[$j]['item_id'] ?>)"><?=$orderdata[$j]['purchase_order_id']; ?></td>
                                            <td><?=$orderdata[$j]['vendor_name']; ?></td>
                                            <td style="width:200px;"><?=$orderdata[$j]['display_product_name']; ?><br>
                                                <? $variationon=$orderdata[$j]['variation_on'];
                                                if($variationon!=""){
                                                $variationonarr=explode("|",$variationon);  $variativaluearr=explode("|",$orderdata[$j]['variation_values']);
                                                for($v=0;$v<count($variationonarr);$v++){ ?> <span> <?=$variationonarr[$v]; ?>:<?=$variativaluearr[$v]; ?></span> <? } } ?>
                                            </td>
                                            <td><?=$orderdata[$j]['quantity'];?></td>
                                            <td>
                                            <i class="fa fa-inr"></i><?=$orderdata[$j]['quantity'] *($orderdata[$j]['vendor_per_item_price_withoutgst']+($orderdata[$j]['vendor_per_item_price_withoutgst']*$orderdata[$j]['tax_percentage']/100));?>
                                            </td>
                                            <td>
                                            <button class="btn btn-primary btn-sm" onclick="showmod('<?php echo $orderdata[$j]['pur_id'] ?>','<?=$orderdata[$j]['purchase_order_id']; ?>')">Update</button></td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="nav-paid" role="tabpanel" aria-labelledby="nav-paid-tab">
                                <span class="mb-3 d-inline-block disburs-tot-amt dis-amt-count bg-primary text-white rounded py-1 px-2"><b>Paid</b> Total Disbursment Amount : <i class="fa fa-inr"></i><? echo round($Paid[0]['total']) ?></span>
                                <div id="tablereload">  
                                <table class="display table table-bordered disbursement-table w-100" id="Paid_order">
                                    <thead><tr><th>#</th><th>Order Date</th><th>Disbursement Date</th><th>Order ID</th><th>Vendor Name</th><th>Product Details</th><th>Quantity</th><th>Disbursement Amount</th><th>Payment mode</th><th>Payment Date</th><th>Remark</th></tr></thead>
                                    <tbody>
                                        <?php $orderdata = $order->disbursmentOrders("Paid");
                                        if(count($orderdata)){
                                        for($j=0;$j<count($orderdata);$j++){ $cnt=$j+1; ?>
                                        <tr> 
                                            <td><?=$cnt; ?></td>
                                            <td><?=date("d M Y h:i A",strtotime($orderdata[$j]['purchase_date'])) ?></td>
                                            <td><?=date("d M Y ",strtotime($orderdata[$j]['disbustment_date'])) ?></td>
                                            <td class="text-primary cc-cursor-pointer" onclick="get_item(<?php echo $orderdata[$j]['item_id'] ?>)"><?=$orderdata[$j]['purchase_order_id']; ?></td>
                                            <td><?=$orderdata[$j]['vendor_name']; ?></td>
                                            <td style="width:200px;"><?=$orderdata[$j]['display_product_name']; ?><br>
                                                <? $variationon=$orderdata[$j]['variation_on'];
                                                if($variationon!=""){
                                                $variationonarr = explode("|",$variationon); $variativaluearr = explode("|",$orderdata[$j]['variation_values']);
                                                for($v=0;$v<count($variationonarr);$v++){ ?> <span><?=$variationonarr[$v]; ?>:<?=$variativaluearr[$v]; ?></span><? } } ?>
                                            </td>
                                            <td><?=$orderdata[$j]['quantity'];?></td>
                                            <td>
                                            <i class="fa fa-inr"></i><?= $orderdata[$j]['quantity']*($orderdata[$j]['vendor_per_item_price_withoutgst']+($orderdata[$j]['vendor_per_item_price_withoutgst']*$orderdata[$j]['tax_percentage']/100));?>
                                            </td>
                                            <td><?=$orderdata[$j]['payment_mode'];?></td>
                                            <td><?=date("d M Y h:i A",strtotime($orderdata[$j]['payment_date']));?> </td>
                                            <td><?=$orderdata[$j]['payment_remark'];?></td>
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
    </div>
    <? include "../footer.php"; ?>
    <?php include '../order/order_common.php'; ?>
    </div>
</div>
<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">&nbsp;</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="pay_form">
                    <div class="msgsform"></div>
                    <div class="form-group row">
                        <label class="col-md-12 control-label">Payment Mode</label>
                        <div class="col-md-12">
                            <input type="hidden" id="purchase_id" value="">
                            <input type="hidden" id="purchase_order_id" value="">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="payment_mode custom-control-input" id="vend-pay-onl" name="payment_mode" value="Online Transfer" required checked=""/>
                                <label class="custom-control-label" for="vend-pay-onl">Online Transfer</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="payment_mode custom-control-input" id="vend-pay-chq" name="payment_mode" value="Cheque Payment" required/>
                                <label class="custom-control-label" for="vend-pay-chq">Cheque Payment</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="payment_mode custom-control-input" id="vend-pay-cash" name="payment_mode" value="Cash Payment" required/>
                                <label class="custom-control-label" for="vend-pay-cash">Cash Payment</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12 control-label">Date</label>
                        <div class="col-md-12"><input type="text" value="" class="datetimepicker1 form-control" id="date<?php echo $i; ?>" required/></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12 control-label">Remark</label>
                        <div class="col-md-12"><textarea name="remark" id="remark" class="form-control remark"></textarea></div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <div class="custom-control custom-checkbox custom-control-inline"><input type="checkbox" value="" id="mark_pay" class="markpaid custom-control-input" checked required disabled="disabled"/><label class="custom-control-label" for="mark_pay"> Mark payment as paid</label></div>
                        </div>
                    </div>
                    <div class="form-group row mb-0"><div class="col-md-12"><input type="button" onclick="savedata()" value="Save" class="btn btn-primary" id="setstat"/></div></div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo SITEURL; ?>/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/datepicker/moment.js"></script>
<script src="<?php echo SITEURL; ?>/js/datepicker/bootstrap-datetimepicker.min.js"></script>
<script>
$(document).ready(function(){
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
    });
    $('.disbursement-table').DataTable({ "scrollX": true });
});
function loaddatatable(){$('.disbursement-table').DataTable();}
$('.datetimepicker1').datetimepicker({ ignoreReadonly: true, maxDate: moment(), format: 'DD-MM-YYYY HH:mm', disabledTimeIntervals:false }); 
function showmod(pur_id,order_id){
    $("#myModal1").modal("show");
    $("#myModalLabel").html("Vendor Payment Of Order "+order_id);
    $("#purchase_id").val(pur_id);
    $("#purchase_order_id").val(order_id);
}
function savedata(){ 
    var mode = $("input[name=payment_mode]:checked").val();
    var dateval = $(".datetimepicker1").val();
    var remark = $("#remark").val();
    var purchase_id = $("#purchase_id").val();
    var purchase_order_id = $("#purchase_order_id").val();
    if(mode == ""){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html('Please select payment mode').delay(5000).fadeOut();
    } else if(dateval == ""){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html('Please select payment date').delay(5000).fadeOut(); 
    } else if(remark == ""){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html('Please enter remark').delay(5000).fadeOut(); 
    } else{
        var info = {mode:mode,dateval:dateval,remark:remark,purchase_id:purchase_id,purchase_order_id:purchase_order_id,action:"savedata"};
        $.ajax({
            type:"POST",
            url:"ajaxdata.php",
            data:info,
            success:function(response){
                response = $.trim(response)
                $("#myModal1").modal('hide');
                if(response == 1){  
                    $("#"+purchase_id).remove();
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html('Payment details saved successfully').delay(5000).fadeOut(); 
                    $(".disburs-daily-update").load(location.href + " .disburs-tab-cont");
                    setTimeout(function(){ loaddatatable();}, 500);
                    $(".dis_link").load(location.href + " .disburs-nav");
                    $('#pay_form')[0].reset();
                } else {
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html('Please try after some time').delay(5000).fadeOut(); 
                }
            }
        });
     }
}
</script>
</body>
</html>