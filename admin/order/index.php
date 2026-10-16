<? include("../../includes/configuration.php");
include("../../classes/order.php"); require_once('../../classes/user.php'); require_once('../../classes/product.php');
$prod = new Product(); $user = new User(); $order = new Order($prod,$user); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo SITE_TITLE; ?> : Inprocess Orders</title>
    <? include "../commoncss.php"; ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/dataTable/dataTables.bootstrap4.min.css">
</head>
<body>
<div class="page-body-wrapper">
    <? include '../header.php'; ?>
    <? include '../sidebar.php';
     $getmanual=selectQuery(MANUAL,"count(pincode) as pins","1 order by id DESC");
     ?>
    <div class="main-panel"> 
        <div class="dashbody">
             <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Inprocess Order</h2></div>
                <div> <a href="liveorder-excel.php" class="btn btn-primary">Live Orders Excel</a>  <a href="itemwise_orders.php" class="btn btn-primary">Itemwise Live Orders</a> </div>
            </div>
                <div class="card-body">
                    <nav>
                        <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link active" id="nav-new-tab" data-toggle="tab" href="#nav-new" role="tab" aria-controls="nav-new" aria-selected="true">New Orders (<?=$order->getVendorOrdersCount("","'Generated','New Order','New','Invoiced'"); ?>)</a>
                            <? if($getmanual[0]['pins']>0){ ?><a class="nav-item nav-link" id="nav-hyperlocal-tab" data-toggle="tab" href="#nav-hyperlocal" role="tab" aria-controls="nav-hyperlocal" aria-selected="false"> Hyperlocal Pickup Requests(<?=$order->getVendorOrdersCount("","'Waiting For Ship'","","shipping_by='".SITENAME."'"); ?>)</a><? } ?>
                            <a class="nav-item nav-link " id="nav-pickup-tab" data-toggle="tab" href="#nav-pickup" role="tab" aria-controls="nav-pickup" aria-selected="true">Waiting For Pickups (<?=$order->getVendorOrdersCount("","'Waiting For Ship','Picked Up','Ready To Ship','Pickup Scheduled','Pickup Queued','Pickup Queue','Pickup Rescheduled','Pickup Error','Pickup Exception','Out For Pickup','Awb Assigned','Label Generated','Manifest Generated'"); ?>)</a>
                            <a class="nav-item nav-link" id="nav-Shipped-tab" data-toggle="tab" href="#nav-Shipped" role="tab" aria-controls="nav-Shipped" aria-selected="false"> Shipped Order (<?=$order->getVendorOrdersCount("","'Generated','New Order','New','Invoiced','Waiting For Ship','Picked Up','Ready To Ship','Pickup Scheduled','Pickup Queued','Pickup Queue','Pickup Rescheduled','Pickup Error','Pickup Exception','Out For Pickup','Awb Assigned','Label Generated','Manifest Generated','Delivered','Fulfilled','Archived','Partial Delivered','Cancellation Request','Cancellation Requested','Canceled','Return Requested','Return Initiated','Return Pending','Return Pickup Queued','Return Pickup Error','Return In Transit','Return Pickup Generated','Return Cancellation Requested','Return Pickup Cancelled','Return Pickup Resheduled','Return Pickedup','Return Delivered','Returned','Return Rejected','Return Cancelled','Rto Acknowledged','Rto Initiated','Rto Delivered','Rto Ofd','Rto Ndr','failure','Initiated',''","NOT"); ?>)</a>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-new" role="tabpanel" aria-labelledby="nav-new-tab">
                            <table class="display table table-bordered neworder-table w-100"  id="inprocess">
                                <thead><tr><th>#</th><th>Order Date</th><th>Order ID</th><th>Buyer Name</th><th>Transaction ID</th> <th>Order Amount</th><th>Status</th>  </tr></thead>
                                <tbody>
                                    <?php $orderdata = $order->getVendorOrders("","'Generated','New Order','New','Invoiced'");
                                    if(count($orderdata)){
                                    for($j=0;$j<count($orderdata);$j++){
                                        $row=$orderdata[$j];
                                        $getorderType=selectQuery(ORDER,"orderType","transaction_id='".$row['transaction_id']."'");
                                        $orderType= $getorderType[0]['orderType']; 
                                        $cnt = $j+1; ?>
                                        <tr>
                                            <td><?=$cnt; ?></td>
                                            <td><?=date("d M Y h:i A",strtotime($row['purchase_date'])) ?></td>
                                            <td class="text-primary cc-cursor-pointer"><a href="order_details.php?transid=<? echo $row['transaction_id']; ?>"> <?php echo $row['order_id'].($orderType=="POS"?" (POS)":"") ?></a></td>
                                            <td class="cc-cursor-pointer"><? if($row['user_id']){?><a href="<?=ADMINURL ?>buyer/buyersdetails.php?u_id=<?=base64_encode($row['user_id']) ?>" target="_blank"><? echo $row['uname'] ?></a><?}else{echo $row['shippingAddr_name'];}  ?></td>
                                            <td><? echo $row['transaction_id']; ?></td>
                                            <td><? echo $row['total_bill_amount']; ?></td>
                                            <td><?
                                          
                                                if($row['shipping_order_id']!=""||$row['shipping_by']==SITENAME){
                                                    echo $row['order_current_Status'];
                                                    if($row['shipping_by']==SITENAME){ ?> <span class="badge badge-info">Hyper Local Order</span><? }
                                                }else{
                                                $data=json_decode($row['shipping_order_error'],true);
                                                $errors=$data['errors'];
                                                $errstr="<ol class='pl-3 mb-0'>";
                                                foreach($errors as $key=>$val){
                                                    foreach($val as $key1=>$val1){ $errstr.="<li>".$key."-".$val1."</li>"; }
                                                } $errstr.="</ol>";
                                                ?><div class="text-danger mb-1">Errors in shipping creation</div> <? echo $data['message']."<br>". $errstr;
                                                }?>
                                            </td>
                                           <!-- <td><button type="button" class="btn btn-primary btn-sm" onclick="confirmCancel('<?=$orderdata[$j]['shipping_order_id']; ?>','<?=$orderdata[$j]['transaction_id']; ?>','<?=$orderdata[$j]['item_id']; ?>',0)">Cancel Order</button> </td> -->


                                        </tr>
                                    <?php } } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="nav-hyperlocal" role="tabpanel" aria-labelledby="nav-hyperlocal-tab">
                            <table class="display table table-bordered neworder-table w-100"  id="hyperlocal" >
                                <thead><tr><th>#</th><th>Order Date</th><th>Order ID</th><th>Buyer Name<th>Vendor Name</th><th>Status</th><th>Delivered/Cancel</th></tr></thead>
                                <tbody>
                                    <?php $orderdata = $order->getVendorOrders("","'Waiting For Ship','Picked Up','Ready To Ship','Pickup Scheduled','Pickup Queued','Pickup Queue','Pickup Rescheduled','Pickup Error','Pickup Exception','Out For Pickup','Awb Assigned','Label Generated','Manifest Generated'","","shipping_by='".SITENAME."'");
                                    if(count($orderdata)){
                                    for($j=0;$j<count($orderdata);$j++){
                                    //echo "<pre>"; print_r($orderdata[$j]);
                                    $cnt = $j+1; ?>
                                    <tr>
                                        <td><?=$cnt; ?></td>
                                        <td><?=date("d M Y h:i A",strtotime($orderdata[$j]['purchase_date'])) ?></td>
                                        <td class="text-primary cc-cursor-pointer"><a href="order_details.php?transid=<? echo $orderdata[$j]['transaction_id']; ?>"><?php echo $orderdata[$j]['order_id'] ?></a></td>
                                        <td class="cc-cursor-pointer"><? if($orderdata[$j]['user_id']){?><a href="<?=ADMINURL ?>buyer/buyersdetails.php?u_id=<?=base64_encode($orderdata[$j]['user_id']) ?>" target="_blank"><? echo $orderdata[$j]['uname'] ?></a><?}else{echo $orderdata[$j]['shippingAddr_name'];}  ?></td>
                                        <td><a href="<?php echo ADMINURL ?>vendor/editvendor.php?vendor=<?php echo base64_encode($orderdata[$j]['vendor'])  ?>"><?php echo $orderdata[$j]['vendor_name'] ?></a></td>

                                        <td><?=$orderdata[$j]['order_current_Status'];?></td>
                                        <td><button type="button" class="btn btn-primary btn-sm" onclick="confirmdelivered('<?=$orderdata[$j]['order_id']; ?>')">Mark As Delivered</button><!--<br><br><button type="button" class="btn btn-primary btn-sm" onclick="confirmCancel('<?=$orderdata[$j]['shipping_order_id']; ?>','<?=$orderdata[$j]['transaction_id']; ?>','<?=$orderdata[$j]['item_id']; ?>',0)">Cancel Order</button> -->
                                        </td>
                                    </tr>
                                    <?php } } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="nav-pickup" role="tabpanel" aria-labelledby="nav-pickup-tab">
                            <table class="display table table-bordered neworder-table w-100"  id="wait_for_pickup" >
                                <thead><tr><th>#</th><th>Order Date</th><th>Order ID</th><th>Sub-Order ID</th> <th>Buyer Name</th> <th>Vendor Name</th><th>Product Details</th><th>Quantity</th><th>Status</th><th>Cancel</th></tr></thead>
                                <tbody>
                                    <?php $orderdata = $order->getVendorOrders("","'Waiting For Ship','Picked Up','Ready To Ship','Pickup Scheduled','Pickup Queued','Pickup Queue','Pickup Rescheduled','Pickup Error','Pickup Exception','Out For Pickup','Awb Assigned','Label Generated','Manifest Generated'");
                                    if(count($orderdata)){
                                    for($j=0;$j<count($orderdata);$j++){
                                    //echo "<pre>"; print_r($orderdata[$j]);
                                    $cnt = $j+1; ?>
                                    <tr>
                                        <td><?=$cnt; ?></td>
                                        <td><?=date("d M Y h:i A",strtotime($orderdata[$j]['purchase_date'])) ?></td>
                                        <td class="text-primary cc-cursor-pointer"><a href="order_details.php?transid=<? echo $orderdata[$j]['transaction_id']; ?>"><?php echo $orderdata[$j]['order_id'] ?></a></td>
                                        <td class="text-primary cc-cursor-pointer" onclick="get_item(<?php echo $orderdata[$j]['item_id'] ?>)"><a><?=$orderdata[$j]['purchase_order_id']; ?> </a></td>
                                        <td class="cc-cursor-pointer"><? if($orderdata[$j]['user_id']){?><a href="<?=ADMINURL ?>buyer/buyersdetails.php?u_id=<?=base64_encode($orderdata[$j]['user_id']) ?>" target="_blank"><? echo $orderdata[$j]['uname'] ?></a><?}else{echo $orderdata[$j]['shippingAddr_name'];}  ?></td>
                                        <td><a href="<?php echo ADMINURL ?>vendor/editvendor.php?vendor=<?php echo base64_encode($orderdata[$j]['vendor'])  ?>"><?php echo $orderdata[$j]['vendor_name'] ?></a></td>
                                        <td><?=$orderdata[$j]['display_product_name']; ?><br>
                                        <? $variationon=$orderdata[$j]['variation_on'];
                                        if($variationon!=""){
                                        $variationonarr = explode("|",$variationon); $variativaluearr = explode("|",$orderdata[$j]['variation_values']);
                                        for($v=0;$v<count($variationonarr);$v++){ ?><span><?=$variationonarr[$v]; ?>:<?=$variativaluearr[$v]; ?></span><? } } ?>
                                        </td>
                                        <td><?=$orderdata[$j]['quantity'];?></td>
                                        <td><?=$orderdata[$j]['order_current_Status'];?></td>
                                        <td><button type="button" class="btn btn-primary btn-sm" onclick="confirmCancel('<?=$orderdata[$j]['shipping_order_id']; ?>','<?=$orderdata[$j]['transaction_id']; ?>','<?=$orderdata[$j]['item_id']; ?>',0)">Cancel Order</button>
                                        </td>
                                    </tr>
                                    <?php } } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="nav-Shipped" role="tabpanel" aria-labelledby="nav-Shipped-tab">
                            <table class="display table table-bordered neworder-table w-100" id="Shipped" >
                                <thead><tr><th>#</th><th>Order Date</th><th>Order ID</th><th>Sub-Order ID</th> <th>Buyer Name</th> <th>Vendor Name</th><th>Product Details</th><th>Quantity</th><th>Status</th><th>Cancel</th></tr></thead>
                                <tbody>
                                    <?php $orderdata = $order->getVendorOrders("","'Generated','New Order','New','Invoiced','Waiting For Ship','Picked Up','Ready To Ship','Pickup Scheduled','Pickup Queued','Pickup Queue','Pickup Rescheduled','Pickup Error','Pickup Exception','Out For Pickup','Awb Assigned','Label Generated','Manifest Generated','Delivered','Fulfilled','Archived','Partial Delivered','Cancellation Request','Cancellation Requested','Canceled','Return Requested','Return Initiated','Return Pending','Return Pickup Queued','Return Pickup Error','Return In Transit','Return Pickup Generated','Return Cancellation Requested','Return Pickup Cancelled','Return Pickup Resheduled','Return Pickedup','Return Delivered','Returned','Return Rejected','Return Cancelled','Rto Acknowledged','Rto Initiated','Rto Delivered','Rto Ofd','Rto Ndr','failure','Initiated',''","NOT");
                                    if(count($orderdata)){
                                    for($j=0;$j<count($orderdata);$j++){
                                    $cnt = $j+1; ?>
                                    <tr>
                                        <td><?=$cnt; ?></td>
                                        <td><?=date("d M Y h:i A",strtotime($orderdata[$j]['purchase_date'])) ?></td>
                                        <td class="text-primary cc-cursor-pointer" ><a href="order_details.php?transid=<? echo $orderdata[$j]['transaction_id']; ?>"> <?php echo $orderdata[$j]['order_id'] ?> </a></td>
                                        <td class="text-primary cc-cursor-pointer" onclick="get_item(<?php echo $orderdata[$j]['item_id'] ?>)"><a> <?=$orderdata[$j]['purchase_order_id']; ?> </a></td>
                                        <td class="cc-cursor-pointer"><? if($orderdata[$j]['user_id']){?><a href="<?=ADMINURL ?>buyer/buyersdetails.php?u_id=<?=base64_encode($orderdata[$j]['user_id']) ?>" target="_blank"><? echo $orderdata[$j]['uname'] ?></a><?}else{echo $orderdata[$j]['shippingAddr_name'];}  ?></td>
                                        <td><a href="<?php echo ADMINURL ?>vendor/editvendor.php?vendor=<?php echo base64_encode($orderdata[$j]['vendor'])  ?>"><?php  echo $orderdata[$j]['vendor_name'] ?></a></td>
                                        <td><?=$orderdata[$j]['display_product_name']; ?><br>
                                        <? $variationon=$orderdata[$j]['variation_on'];
                                        if($variationon!=""){
                                        $variationonarr=explode("|",$variationon);  $variativaluearr=explode("|",$orderdata[$j]['variation_values']);
                                        for($v=0;$v<count($variationonarr);$v++){ ?> <span> <?=$variationonarr[$v]; ?>:<?=$variativaluearr[$v]; ?> </span> <? } } ?>
                                        </td>
                                        <td><?=$orderdata[$j]['quantity'];?></td>
                                        <td><?=$orderdata[$j]['order_current_Status'];?></td>
                                        <td><button type="button" class="btn btn-primary btn-sm" onclick="confirmCancel('<?=$orderdata[$j]['shipping_order_id']; ?>','<?=$orderdata[$j]['transaction_id']; ?>','<?=$orderdata[$j]['item_id']; ?>',0)">Cancel Order</button>
                                        </td>
                                    </tr>
                                    <?php } } ?>
                                </tbody>
                            </table>
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
<script>
$(document).ready(function(){
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
    });
    $('.neworder-table').DataTable({ "scrollX": true });
});
</script>
</body>
</html>
