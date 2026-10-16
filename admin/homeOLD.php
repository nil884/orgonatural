<? include("../includes/configuration.php");
include("../classes/order.php"); require_once('../classes/user.php'); require_once('../classes/product.php');require_once "../classes/shiprocket.php";
$prod = new Product(); $user = new User(); $order = new Order($prod,$user);
$username = SHIPUSER; $pasword = SHIPPWD;
$ship = new shiprocket($username,$pasword);
$token = $ship->authenticate(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo SITENAME; ?> : Dashboard</title>
    <? include "commoncss.php"; ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/datepicker/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/dataTable/dataTables.bootstrap4.min.css">
</head>
<body>
<div class="page-body-wrapper">
    <? include 'header.php'; ?>
    <? include 'sidebar.php';
  // $live_order = selectQuery(ORDERSUB,"count(item_id) as live_order","order_current_Status IN ('Generated','Invoiced','New Order','New', 'Waiting For Ship', 'Ready To Ship','Pickup Scheduled','Pickup Queue','Pickup Rescheduled','Pickup Error','Pickup Exception','Out For Pickup','Shipped','Out for Delivery','In Transit','Reached Destination Hub','Misrouted','Lost','Undelivered','Delayed','Picked Up','Awb Assigned','Label Generated','Manifest Generated' ,'Pending') ");
  $live_order = selectQuery(ORDERSUB,"count(item_id) as live_order","order_current_Status NOT IN ('Delivered','Canceled','Initiated','failure','failed','Cancelation requested','Cancellation requested','Cancellation Request', 'Canceled') AND order_current_Status NOT Like '%Return%' AND order_current_Status NOT Like '%Rto%'");
 
    $total_users = selectQuery(BUYER,"count(u_id) as total_users","1");
    $Pending_vendor = selectQuery(VENDOR,"count(dealer_id) as vendor","isApproved='0'");
    $pending_review = selectQuery(REVIEW,"count(review_id) as review","isApproved='0'");
    $pending_product = selectQuery(PRODINFO,"count(id) as product","isApproved = '0' and parent_id = '0' order by id desc"); 
    $all_pending_count = $pending_review[0]['review'] + $Pending_vendor[0]['vendor'] + $pending_product[0]['product']  + count($getrefundable)  + $order->getVendorOrdersCount("","'Return Requested'");
   $getdata=selectQuery(PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id","o.product_id,o.product_name,o.display_product_name, o.quantity,sum(o.quantity) as tot","(o.order_current_status<>'failure' AND o.order_current_status<>'Canceled') AND (MONTH(p.purchase_date) = MONTH(CURRENT_DATE()) AND YEAR(p.purchase_date) = YEAR(CURRENT_DATE())) group by o.product_id order by tot DESC limit 15");
    $stockalert = selectQuery(PRODINFO,"id,prod_name,stock,sold", "stock-sold <= ".STOCK_ALERT." and stock <> 0   order by stock-sold asc limit 15");
    /*$month_delivered = selectQuery(ORDERSUB,"count(*) as totalOrder,MONTH(delivered_on)as month","delivered_on != '0000-00-00 00:00:00' GROUP BY DATE_FORMAT(delivered_on, '%Y%m')");
    $month_canceled = selectQuery(ORDERSUB,"count(*) as totalOrder,MONTH(cancelation_date)as month","cancelation_date != '0000-00-00 00:00:00' GROUP BY DATE_FORMAT(cancelation_date, '%Y%m')");
    $month_returned = selectQuery(ORDERSUB,"count(*) as totalOrder,MONTH(return_request_date )as month","return_request_date  != '0000-00-00 00:00:00' GROUP BY DATE_FORMAT(return_request_date , '%Y%m')  ");
   */
  $month_inprocess = selectQuery(ORDERSUB." as sub JOIN ".ORDER." as ord on sub.order_id=ord.id","count(*) as totalOrder,MONTH(ord.order_date) as month","order_current_Status NOT IN ('Delivered','Canceled','Initiated','failure','failed','Cancelation requested','Cancellation requested','Cancellation Request', 'Canceled') AND order_current_Status NOT Like '%Return%' AND order_current_Status NOT Like '%Rto%' AND YEAR(ord.order_date) = YEAR(CURDATE())  GROUP BY MONTH(ord.order_date)");
    $month_delivered = selectQuery(ORDERSUB." as sub JOIN ".ORDER." as ord on sub.order_id=ord.id","count(*) as totalOrder,MONTH(ord.order_date) as month","sub.order_current_Status='Delivered' AND YEAR(ord.order_date) = YEAR(CURDATE())  GROUP BY MONTH(ord.order_date)");
    $month_canceled = selectQuery(ORDERSUB." as sub JOIN ".ORDER." as ord on sub.order_id=ord.id","count(*) as totalOrder,MONTH(ord.order_date) as month","sub.order_current_Status='Canceled' AND YEAR(ord.order_date) = YEAR(CURDATE())  GROUP BY MONTH(ord.order_date)");
    $month_returned = selectQuery(ORDERSUB." as sub JOIN ".ORDER." as ord on sub.order_id=ord.id","count(*) as totalOrder,MONTH(ord.order_date) as month","sub.order_current_Status Like '%Return%' AND YEAR(ord.order_date) = YEAR(CURDATE())  GROUP BY MONTH(ord.order_date)");
    $getrefundable=selectQuery(ORDERSUB." as sub JOIN ".ORDER." as ord on sub.order_id=ord.id JOIN ".BUYER." as usr on ord.user_id=usr.u_id","ord.order_id,usr.u_fname,usr.u_lname,sub.refundable_amount,sub.item_id","sub.is_refund_appilicable='1' and sub.refund_status='0' and ord.payment_mode='COD'");
    //delivered data
    $graphDataTemp = array();
    foreach($month_delivered as $value){
        $graphDataTemp[$value['month']]=$value['totalOrder'];
    } for($i=1; $i<=12; $i++){
        if(isset($graphDataTemp[$i]))
        $graphData[]= $graphDataTemp[$i];
        else
        $graphData[] = 0;
    }
    //cancelled data 
    $graphcancelDataTemp = array();
    foreach($month_canceled as $value){
        $graphcancelDataTemp[$value['month']]=$value['totalOrder'];
    } for($i=1; $i<=12; $i++){
        if(isset($graphcancelDataTemp[$i]))
        $graphcancelData[]= $graphcancelDataTemp[$i];
        else
        $graphcancelData[] = 0;
    } 
    // returned
    $graphreturnDataTemp = array();
    foreach($month_returned as $value){
        $graphreturnDataTemp[$value['month']]=$value['totalOrder'];
    } for($i=1; $i<=12; $i++){
        if(isset($graphreturnDataTemp[$i]))
        $graphreturnData[]= $graphreturnDataTemp[$i];
        else
        $graphreturnData[] = 0;
    }
    //inprocess data
    $graphinprocessDataTemp = array();
    foreach($month_inprocess as $value){
        $graphinprocessDataTemp[$value['month']]=$value['totalOrder'];
    } for($i=1; $i<=12; $i++){
        if(isset($graphinprocessDataTemp[$i]))
        $graphinprocessData[]= $graphinprocessDataTemp[$i];
        else
        $graphinprocessData[] = 0;
    }
    $get_todays_visit_list = selectQuery(VISITORLIST,"ip,device,browser,details,date","date like '%".date('Y-m-d')."%' order by id DESC"); //VISITORLIST,"device","1 order by id DESC"
    $linuxcnt = 0; $Windows7cnt= 0; $Windows10cnt = 0; $androidcnt = 0; $ioscnt = 0;
    for($i=0;$i<count($get_todays_visit_list);$i++){
        $device = $get_todays_visit_list[$i]['device'];
        if($device=='Windows 7'){
            $Windows7cnt++;
        }else if($device=='Windows 10'){
            $Windows10cnt++;
        } else if($device=='android'){
            $androidcnt++;
        }else if($device=='iphone'||$device=='ipad'){
            $ioscnt++;
        }else if($device=='Linux'){
            $linuxcnt++;
        }
    }
    //$fromdt = "21-05-2020 00:00";
    //$todt = "22-05-2020 23:59";         
    //$str = (m.order_date between '".date("Y-m-d H:i",strtotime($fromdt))."' AND '".date("Y-m-d H:i",strtotime($todt))."') AND ;
    //$str1 = (p.purchase_date between '".date("Y-m-d H:i",strtotime($fromdt))."' AND '".date("Y-m-d H:i",strtotime($todt))."') AND ;
    $total_sale = selectQuery(ORDERSUB." as o JOIN ".ORDER." as m on o.order_id=m.id","count(o.item_id) as total_count , sum(o.total_payable) as total_payable","o.order_current_Status='Delivered' OR o.order_current_Status='Partial Delivered' OR o.order_current_Status='Return Canceled'");
    $get_purchase = selectQuery(PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id","o.tax_percentage,p.purchase_order_id,p.purchase_date,p.purchase_from_vendor,o.vendor_per_item_price_withoutgst,o.display_product_name,o.quantity,o.cgst2,o.sgst2,o.igst2,o.shipping_charges","o.order_current_Status='Delivered' OR o.order_current_Status='Partial Delivered' OR o.order_current_Status='Return Canceled'");
    $adminpin = $getconfigdetails[0]['pincode'];
    for($i=0;$i<count($get_purchase);$i++){
        $purchaseId = $get_purchase[$i]['purchase_order_id'];
        $perPrice = $get_purchase[$i]['vendor_per_item_price_withoutgst'];
        $quantity = $get_purchase[$i]['quantity'];
        $tax = $get_purchase[$i]['tax_percentage'];
        $without_gst = round($perPrice*$quantity);
        $taxamount = round(($without_gst/100)* $tax);
        $withgstval = $without_gst + $taxamount;
       
        $total_purchase = $total_purchase + $withgstval;
        $without_gst_purchase_total = $without_gst_purchase_total + $without_gst;
        $sale_tax_amount = $get_purchase[$i]['cgst2']+$get_purchase[$i]['sgst2']+$get_purchase[$i]['igst2'];
        $total_tax = $sale_tax_amount - $taxamount;
        $shipping_charges = $shipping_charges + $get_purchase[$i]['shipping_charges'];
        $net = $total_tax + $get_purchase[$i]['shipping_charges'];
        $total_net =  $net + $total_net; 
       
    }        
    $vendor_payment_total = selectQuery(VENDORPAYMENT,"sum(price) as total_price","payment_status = 'Received'  order by pay_id DESC"); ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="row">
                <div class="col-md-12">
                    <? $getblankTemp = selectQuery(SMSTEMPLATE,"count(id) as blanktemp","templateId=''");
                    if($getblankTemp[0]['blanktemp']&&SMS_SYSTEM=="ON"){
                        ?> <div class="alert alert-danger"> As per new rule by TRAI you need to provide approved Template ID for each SMS and your <?=$getblankTemp[0]['blanktemp']; ?> templates do not have Template ID.  <a href="<?=ADMINURL;?>sms_email_setting/templates.php">Click Here To Add Template ID</a> </div> <?
                    }  ?>
                    <div class="row mr-0">
                        <div class="col-sm-6 col-md-6 col-lg-4 reg-update-dash pr-0">
                            <div class="dash-update-tiles rounded bg-danger mb-3 p-3 text-white position-relative">
                                <a href="<?php echo ADMINURL?>account/gst.php">
                                    <span class="fa fa-line-chart position-absolute bg-white text-danger"></span>
                                    <div class="dash-update-body"><div class="h5"><?php  if($total_sale[0]['total_payable'] != "" ) { echo $total_sale[0]['total_payable']; }  else {  echo "0";} ?></div><small>Total</small><h6 class="mb-0">Sale</h6></div>
                                </a>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-4 reg-update-dash pr-0">
                            <div class="dash-update-tiles rounded bg-warning mb-3 p-3 text-white position-relative">
                                <a href="<?php echo ADMINURL?>account/gst.php">
                                    <span class="fa fa-shopping-cart position-absolute bg-white text-warning"></span>
                                    <div class="dash-update-body"><div class="h5"><?php if($total_purchase != ""){ echo round($total_purchase,2); } else { echo "0";} ?></div><small>Total</small><h6 class="mb-0">Purchase</h6></div>
                                </a>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-4 reg-update-dash pr-0">
                            <div class="dash-update-tiles rounded bg-info mb-3 p-3 text-white position-relative">
                                <a href="<?php echo ADMINURL?>account/profit.php">
                                    <span class="fa fa-inr position-absolute bg-white text-info"></span>
                                    <div class="dash-update-body"><div class="h5"><?php echo $gross=round($total_sale[0]['total_payable'] - $total_purchase,2); ?></div><small>Total</small><h6 class="mb-0">Gross Profit</h6></div>
                                </a>
                            </div>
                        </div>   
                       <div class="col-sm-6 col-md-6 col-lg-4 reg-update-dash pr-0">
                            <div class="dash-update-tiles rounded bg-success mb-3 p-3 text-white position-relative">
                                <a href="<?php echo ADMINURL?>account/profit.php">
                                    <span class="fa fa-inr position-absolute bg-white text-info"></span>
                                    <div class="dash-update-body"><div class="h5"><?php //echo $without_gst_sale_total - $without_gst_purchase_total 
                                    echo round($gross - $total_net,2); ?></div><small>Total</small><h6 class="mb-0">Net Profit</h6></div>
                                </a>
                            </div>
                        </div> 
                        <div class="col-sm-6 col-md-6 col-lg-4 reg-update-dash pr-0">
                            <div class="dash-update-tiles rounded bg-primary mb-3 p-3 text-white position-relative">
                                <a href="<?php echo ADMINURL?>account/vendor_report.php">
                                    <span class="fa fa-inr position-absolute bg-white text-info"></span>
                                    <div class="dash-update-body"><div class="h5"><?php echo ($vendor_payment_total[0]['total_price']?$vendor_payment_total[0]['total_price']:0); ?></div><small>Total</small><h6 class="mb-0">Vendor Plan Sale</h6></div>
                                </a>
                            </div>
                        </div>
                         <div class="col-sm-6 col-md-6 col-lg-4 reg-update-dash pr-0">
                            <div class="dash-update-tiles rounded bg-success mb-3 p-3 text-white position-relative">
                                <a href="<?php echo ADMINURL; ?>order">
                                    <span class="fa fa-file-text position-absolute bg-white text-success"></span>
                                    <div class="dash-update-body"><div class="h5"><?php  echo $live_order[0]['live_order'] ?></div><small>Total</small><h6 class="mb-0">Live Orders</h6></div>
                                </a>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-4 reg-update-dash pr-0">
                            <div class="dash-update-tiles rounded bg-primary text-white mb-3 p-3 position-relative">
                                <a href="<?php echo ADMINURL; ?>order/delivered.php">
                                    <span class="fa fa-cube position-absolute bg-white text-primary"></span>
                                    <div class="dash-update-body"><div class="h5"><?=$order->getVendorOrdersCount("","'Delivered','Fulfilled','Archived','Partial Delivered'"); ?></div><small>Total</small><h6 class="mb-0">Delivered Order</h6></div>
                                </a>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-4 reg-update-dash pr-0">
                            <div class="dash-update-tiles rounded bg-danger text-white mb-3 p-3 position-relative">
                                <a href="<?php echo ADMINURL; ?>order/cancel.php">
                                    <span class="fa fa-truck position-absolute bg-white text-danger"></span>
                                    <div class="dash-update-body"><div class="h5"><?=$order->getVendorOrdersCount("","'Cancellation Request','Cancellation Requested','Canceled'"); ?></div><small>Total</small><h6 class="mb-0">Cancelled Order</h6></div>
                                </a>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-4 reg-update-dash pr-0">
                            <div class="dash-update-tiles rounded bg-warning text-white mb-3 p-3 position-relative">
                                <a href="<?php echo ADMINURL; ?>order/return.php">
                                    <span class="fa fa-reply position-absolute bg-white text-warning"></span>
                                    <div class="dash-update-body"><div class="h5"><?=$order->getVendorOrdersCount("","'Return Requested','Return Initiated','Return Pending','Return Pickup Queued','Return Pickup Error','Return In Transit','Return Pickup Generated','Return Cancellation Requested','Return Pickup Cancelled','Return Pickup Resheduled','Return Pickedup','Return Delivered','Returned'"); ?></div><small>Total</small><h6 class="mb-0">Return Request</h6></div>
                                </a>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-4 reg-update-dash pr-0">
                            <div class="dash-update-tiles rounded bg-info mb-3 p-3 text-white position-relative">
                                <a href="<?php echo ADMINURL; ?>buyer">
                                    <span class="fa fa-users position-absolute bg-white text-info"></span>
                                    <div class="dash-update-body"><div class="h5"><?php echo $total_users[0]['total_users'] ?></div><small>Total</small><h6 class="mb-0">Users</h6></div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php if($all_pending_count > 0){ ?>
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Pending For Approval</h2></div></div> 
                <div class="card-body pb-0 pb-xl-2">
                    <div class="mb-3">You have <span class="label label-default"><?php echo $all_pending_count; ?></span> things for approval</div>
                    <div>
                        <a href="<?php echo ADMINURL; ?>vendor" target="_blank" class="mb-2 mb-xl-0 mr-3 pr-3 py-1 border-right d-inline-block"><i class="fa fa-user mr-1"></i> Vendor - <b><?php echo $Pending_vendor[0]['vendor']; ?></b></a>
                        <a href="<?php echo ADMINURL; ?>product/pendingprod.php" target="_blank" class="mb-2 mb-xl-0 mr-3 pr-3 py-1 border-right d-inline-block"><i class="fa fa-shopping-cart mr-1"></i> Product - <b><?php echo $pending_product[0]['product']; ?></b></a>
                        <a href="<?php echo ADMINURL; ?>review/" target="_blank" class="mb-2 mb-xl-0 mr-3 pr-3 py-1 border-right d-inline-block"><i class="fa fa-star mr-1"></i> Review - <b><?php echo $pending_review[0]['review']; ?></b></a>
                        <a href="<?php echo ADMINURL; ?>order/return.php" target="_blank" class="mb-2 mb-xl-0 mr-3 pr-3 py-1 border-right d-inline-block"><i class="fa fa-reply mr-1"></i> Return Request - <b><?=$order->getVendorOrdersCount("","'Return Requested'"); ?></b></a>
                        <a href="<?php echo ADMINURL; ?>refund" target="_blank" class="mb-2 mb-xl-0 pr-3 py-1 d-inline-block"><i class="fa fa-cube mr-1"></i> Manual Refund Request - <b><?= count($getrefundable); ?></b></a>
                    </div>
                </div>
            </div>
            <?php } ?>            
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Universal Search</h2></div></div> 
                <div class="card-body">
                    <form><input type="search" name="search" id="search" autocomplete="off" class="form-control" placeholder="Search By User Name / User Email / User Mobile / Order ID / Order Date YYYY-MM-DD"/></form>
                    <div class="searchresult"></div>
                </div>
            </div>
            <? $livecartdata = selectQuery(CART." as c JOIN ".BUYER." as b on c.user_id=b.u_id JOIN ".PRODINFO." as p on c.prod_id=p.id ","p.id,p.prod_name,b.u_id,b.u_fname,b.u_lname,b.u_email,c.quantity,c.insert_date","c.type='CART'  ORDER BY c.insert_date DESC LIMIT 11");
            if(count($livecartdata)){
            $getconfingdetails = json_decode(getimgconfig('product'));
            $img_location = $getconfingdetails[0]->imgs_location; // Access Object data
            $img_location = $getconfingdetails[0]->thumb2_path; ?>
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2">
                    <h2 class="card-head-title">Live Cart <?=(count($livecartdata)>10?"(Latest 10)":""); ?></h2>
                    <div class="btn-actions-pane-right"><a href="livecart.php" class="btn btn-primary btn-sm">View All</a></div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead><tr><th>Product</th><th>User</th><th>Product Name</th><th>Qty</th><th>Price</th><th>Added On</th></tr></thead>
                            <tbody>
                                <? for($i=0;$i<(count($livecartdata)>10?9:count($livecartdata));$i++){
                                $row = $livecartdata[$i]; $price = $prod->getProductFullDetails($row['id']); $img = $prod->getProductImageForDisplay($row['id']); ?>
                                <tr><td class="text-center"><img src="<? echo SITEURL."/".$img_location."/".$img[0]['img_name']; ?>" alt="pro-upload-img" class="rounded" height="80"/></td><td><a href="<?=ADMINURL; ?>buyer/buyersdetails.php?u_id=<?=base64_encode($row['u_id']); ?>" target="_blank"><?=$row['u_fname']." ".$row['u_lname']; ?><br><?=$row['u_email']; ?></a></td><td><a href="<?=ADMINURL; ?>product/view-details.php?prod_id=<?=base64_encode($row['id']); ?>" target="_blank"><?=$row['prod_name']; ?></a></td><td><?=$row['quantity']; ?></td><td><?=$price['price']; ?></td><td><?=date("d M Y",strtotime($row['insert_date'])); ?></td></tr>
                                <? } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <? } ?>
            <div class="row mr-0">
                <div class="col-md-4 pr-0 mb-3">
                    <div class="card mb-0">
                    <div class="card-header sec-card-head justify-content-between align-items-center"><div><h5 class="card-head-title">Today's Visitors Count</h5></div></div>
                    <div class="card-body">
                    <div id="chart" class="mb-3"><canvas id="myChart" height="280"></canvas></div>
                    <div id="legend"></div>
                    </div></div>
                </div>
                <div class="col-md-8 pr-0 mb-3">
                    <div class="card h-100">
                        <div class="card-header sec-card-head justify-content-between align-items-center"><div><h5 class="card-head-title">Orders Graph ( Year - <?php echo date("Y"); ?> )</h5></div></div>
                        <div class="card-body"><div class="pro-update-chart"></div></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header sec-card-head justify-content-between align-items-center"><div><h5 class="card-head-title">Reports</h5></div></div>
                        <div class="card-body">
                            <div class="row border-bottom"> 
                                <div class="form-group col-sm-6 col-md-4 col-lg-4 col-xl-5">
                                    <div class="input-group">
                                        <input type="text" id="fromdt" class="form-control form-control-lg border-right-0" placeholder="Enter From Date">
                                        <div class="input-group-append"><span class="input-group-text bg-white"><span class="fa fa-calendar"></span></span></div>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6 col-md-4 col-lg-4 col-xl-5">
                                    <div class="input-group">
                                        <input type="text" id="todt" class="form-control form-control-lg border-right-0" placeholder="Enter To Date">
                                        <div class="input-group-append"><span class="input-group-text bg-white"><span class="fa fa-calendar"></span></span></div>
                                    </div>
                                </div>
                                <div class="form-group col-md-4 col-lg-4 col-xl-2"><button type="button" class="btn btn-primary btn-block genreport" onclick="generatereport()">View Report</button></div>         
                            </div>
                            <div class="row ser-cout-dis">
                                <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-3 pt-3">
                                    <div class="media">
                                        <i class="fa fa-pencil mr-4 bg-primary text-white"></i>
                                        <div class="media-body">
                                            <div class="h4 mb-0"><b id="user_date_range">0</b></div>
                                            <h6 class="mb-0">Users</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-3 pt-3">
                                    <div class="media">
                                        <i class="fa fa-shopping-bag mr-4 bg-warning text-white"></i>
                                        <div class="media-body">
                                            <div class="h4 mb-0"><b id="order_date_range"> 0</b></div>
                                            <h6 class="mb-0">Orders</h6>
                                        </div>
                                    </div>
                                </div> 
                                <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-3 pt-3">
                                    <div class="media">
                                        <i class="fa fa-users mr-4 bg-success text-white"></i>
                                        <div class="media-body">
                                            <div class="h4 mb-0"><b id="vendor_date_range">0</b></div>
                                            <h6 class="mb-0">Vendor</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mr-0">
                <div class="col-md-12 col-lg-6 pr-0">
                    <div class="card mb-3">
                        <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title mb-2 mb-xl-0">15 Highest Selling Products - For <? echo date('M')." ".date('Y'); ?></h2></div><a href="highest_selling.php" class="btn btn-primary btn-sm" target="_blank">View</a></div>
                        <div class="card-body limited-cont-overflow">
                            <table id="recent-orders" class="table table-bordered ps-container ps-theme-default">
                                <thead><tr><th>Products</th><th class="text-right">Sales</th></tr></thead>
                                <tbody>
                                    <?php if(count($getdata)){
                                    for($i=0;$i<count($getdata);$i++){ $r=$getdata[$i]; ?>
                                    <tr><td><a href="<?=ADMINURL; ?>product/view-details.php?prod_id=<?=base64_encode($r['product_id']); ?>" target="_blank"><?php echo $r['product_name']; ?></a></td><td class="text-center"><?php echo $r['tot']; ?></td></tr>
                                    <?php } } else{
                                        echo "<tr><td colspan='2'>No product sold in this month</td></tr>";
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-6 pr-0">
                    <div class="card mb-3">
                        <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title mb-2 mb-xl-0">15 Stock Alerts ( Less Than <?php echo STOCK_ALERT ?> Products )</h2></div><a href="stock_alert.php" class="btn btn-primary btn-sm" target="_blank">View</a></div>
                        <div class="card-body limited-cont-overflow">
                            <table id="recent-orders2" class="table table-bordered ps-container ps-theme-default">
                                <thead><tr><th>Products</th><th style="width:40px;" class="text-center">Stock</th></tr></thead>
                                <tbody>
                                    <?php if(count($stockalert)){
                                    for($i=0;$i<count($stockalert);$i++){  $r=$stockalert[$i]; ?>
                                    <tr><td><a href="<?=ADMINURL; ?>product/view-details.php?prod_id=<?=base64_encode($r['id']); ?>" target="_blank"><?php echo $r['prod_name'] ?></a></td>
                                    <td class="text-center"><?php echo $r['stock']- $r['sold'] ?></td></tr>
                                    <?php } } else{ echo "<tr><td colspan='2'>All Product have sufficient stock</td></tr>"; } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
             <div class="card mb-0">
                 <div class="card-header sec-card-head justify-content-between align-items-center py-2"><div><h2 class="card-head-title">Vendor Payment Disbursement Details</h2></div><div class="btn-actions-pane-right"><a href="<?php echo ADMINURL; ?>account/disbursement.php" class="btn btn-primary btn-sm">View</a></div></div>
                 <div class="card-body">
                    <nav class="dis_link mb-3">
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-today-tab" data-toggle="tab" href="#nav-today" role="tab" aria-controls="nav-today" aria-selected="true">Today (<?php $today = $order->disbursmentOrdersCount("Today"); echo $today[0]['ordcnt']; ?>)</a>
                        <a class="nav-item nav-link" id="nav-tomorrow-tab" data-toggle="tab" href="#nav-tomorrow" role="tab" aria-controls="nav-tomorrow" aria-selected="true">Tomorrow (<?php $Tomorrow = $order->disbursmentOrdersCount("Tomorrow"); echo $Tomorrow[0]['ordcnt']; ?>)</a>
                        <a class="nav-item nav-link" id="nav-upcoming-tab" data-toggle="tab" href="#nav-upcoming" role="tab" aria-controls="nav-upcoming" aria-selected="false">Upcoming (<?php $Upcoming = $order->disbursmentOrdersCount("Upcoming"); echo $Upcoming[0]['ordcnt']; ?>)</a>
                        <a class="nav-item nav-link" id="nav-overdue-tab" data-toggle="tab" href="#nav-overdue" role="tab" aria-controls="nav-overdue" aria-selected="false">Overdue (<?php $overdue = $order->disbursmentOrdersCount("overdue"); echo $overdue[0]['ordcnt']; ?>)</a>
                        </div>
                    </nav>
                    <div class="tab-content bg-white" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-today" role="tabpanel" aria-labelledby="nav-today-tab">
                            <span class="mb-3 d-inline-block disburs-tot-amt dis-amt-count bg-primary text-white rounded py-1 px-2"><b>Today</b> Total Disbursment Amount : <i class="fa fa-inr"></i><?php echo round($today[0]['total']); ?></span>
                            <div class="table-responsive">
                                <table class="display table table-bordered disbursement-table">
                                    <thead><tr><th>#</th><th>Order Date</th><th>Order ID</th><th>Vendor Name</th><th>Product Details</th><th>Quantity</th><th>Disbursement Amount</th></tr></thead>
                                    <tbody>
                                        <?php $orderdata = $order->disbursmentOrders("Today");
                                        if(count($orderdata)){
                                        for($j=0;$j<count($orderdata);$j++){  $cnt=$j+1; $r=$orderdata[$j]; ?>
                                        <tr id='<?php echo $r['pur_id'] ?>'>
                                            <td><?=$cnt; ?></td>
                                            <td><?=date("d M Y h:i A",strtotime($r['purchase_date'])) ?></td>
                                            <td class="text-primary cc-cursor-pointer" onclick="get_item(<?php echo $r['item_id'] ?>)"><?=$r['purchase_order_id']; ?></td>
                                            <td> <a href="<?=ADMINURL; ?>vendor/editvendor.php?vendor=<?=base64_encode($r['vendor']); ?>" target="_blank"> <?=$r['vendor_name']; ?></a></td>
                                            <td><a href="<?=ADMINURL; ?>product/view-details.php?prod_id=<?=base64_encode($r['product_id']); ?>" target="_blank"><?=$r['display_product_name']; ?></a><br>
                                            <? $variationon=$r['variation_on'];
                                            if($variationon!=""){
                                            $variationonarr = explode("|",$variationon); $variativaluearr = explode("|",$r['variation_values']);
                                            for($v=0;$v<count($variationonarr);$v++){ ?> <span><?=$variationonarr[$v]; ?>:<?=$variativaluearr[$v]; ?> </span> <? } } ?>
                                            </td>
                                            <td><?=$r['quantity'];?></td>
                                            <td><?=$r['quantity']*($r['vendor_per_item_price_withoutgst']+($r['vendor_per_item_price_withoutgst']*$r['tax_percentage']/100));?></td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-tomorrow" role="tabpanel" aria-labelledby="nav-tomorrow-tab">
                            <span class="mb-3 d-inline-block disburs-tot-amt dis-amt-count bg-primary text-white rounded py-1 px-2"><b>Tomorrow</b> Total Disbursment Amount : <i class="fa fa-inr"></i><? echo round($Tomorrow[0]['total']) ?></span>
                            <div class="table-responsive">
                                <table class="display table table-bordered disbursement-table">
                                <thead><tr><th>#</th><th>Order Date</th><th>Order ID</th><th>Vendor Name</th><th>Product Details</th><th>Quantity</th><th>Disbursement Amount</th></tr></thead>
                                <tbody>
                                    <?php $orderdata = $order->disbursmentOrders("Tomorrow");
                                    if(count($orderdata)){
                                    for($j=0;$j<count($orderdata);$j++){ $cnt=$j+1; $r=$orderdata[$j]; ?>
                                    <tr id='<?php echo $r['pur_id'] ?>'>
                                    <td><?=$cnt; ?></td>
                                    <td><?=date("d M Y h:i A",strtotime($r['purchase_date'])) ?></td>
                                    <td class="text-primary cc-cursor-pointer" onclick="get_item(<?php echo $r['item_id'] ?>)"><?=$r['purchase_order_id']; ?></td>
                                    <td> <a href="<?=ADMINURL; ?>vendor/editvendor.php?vendor=<?=base64_encode($r['vendor']); ?>" target="_blank"> <?=$r['vendor_name']; ?></a></td>
                                    <td><a href="<?=ADMINURL; ?>product/view-details.php?prod_id=<?=base64_encode($r['product_id']); ?>" target="_blank"><?=$r['display_product_name']; ?></a><br>
                                    <? $variationon = $r['variation_on'];
                                    if($variationon!=""){
                                    $variationonarr=explode("|",$variationon); $variativaluearr=explode("|",$r['variation_values']);
                                    for($v=0;$v<count($variationonarr);$v++){ ?> <span> <?=$variationonarr[$v]; ?>:<?=$variativaluearr[$v]; ?></span> <? } } ?></td>
                                    <td><?=$r['quantity'];?></td>
                                    <td><?=$r['quantity']* ($r['vendor_per_item_price_withoutgst']+($r['vendor_per_item_price_withoutgst']*$r['tax_percentage']/100));?></td>
                                    </tr>
                                    <?php } } ?>
                                </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-upcoming" role="tabpanel" aria-labelledby="nav-upcoming-tab">
                            <span class="mb-3 d-inline-block disburs-tot-amt dis-amt-count bg-primary text-white rounded py-1 px-2"><b>Upcoming</b> Total Disbursment Amount : <i class="fa fa-inr"></i><? echo round($Upcoming[0]['total']) ?></span>
                            <div class="table-responsive">
                                <table class="display table table-bordered disbursement-table">
                                    <thead><tr><th>#</th><th>Order Date</th><th> Disbursement
                                    Date</th><th>Order ID</th><th>Vendor Name</th><th>Product Details</th><th>Quantity</th><th>Disbursement Amount</th></tr></thead>
                                    <tbody>
                                        <?php $orderdata = $order->disbursmentOrders("Upcoming");
                                        if(count($orderdata)){
                                        for($j=0;$j<count($orderdata);$j++){  $cnt=$j+1; $r3=$orderdata[$j]; ?>
                                        <tr id='<?php echo $r3['pur_id'] ?>'>
                                            <td><?=$cnt; ?></td>
                                            <td><?=date("d M Y h:i A",strtotime($r3['purchase_date'])) ?></td>
                                            <td><?=date("d M Y ",strtotime($r3['disbustment_date'])) ?></td>
                                            <td class="text-primary cc-cursor-pointer" onclick="get_item(<?php echo $r3['item_id'] ?>)"><?=$r3['purchase_order_id']; ?></td>
                                            <td> <a href="<?=ADMINURL; ?>vendor/editvendor.php?vendor=<?=base64_encode($r3['vendor']); ?>" target="_blank"> <?=$r3['vendor_name']; ?></a></td>
                                            <td><a href="<?=ADMINURL; ?>product/view-details.php?prod_id=<?=base64_encode($r3['product_id']); ?>" target="_blank"><?=$r3['display_product_name']; ?></a><br>
                                            <? $variationon = $orderdata[$j]['variation_on'];
                                            if($variationon!=""){
                                            $variationonarr = explode("|",$variationon); $variativaluearr = explode("|",$r3['variation_values']);
                                            for($v=0;$v<count($variationonarr);$v++){ ?> <span><?=$variationonarr[$v]; ?>:<?=$variativaluearr[$v]; ?></span><? } } ?></td>
                                            <td><?=$r3[$j]['quantity'];?></td>
                                            <td><?=$r3['quantity'] *($orderdata[$j]['vendor_per_item_price_withoutgst']+($r3['vendor_per_item_price_withoutgst']*$r3['tax_percentage']/100));?></td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-overdue" role="tabpanel" aria-labelledby="nav-overdue-tab">
                            <span class="mb-3 d-inline-block disburs-tot-amt dis-amt-count bg-primary text-white rounded py-1 px-2"><b>Overdue</b> Total Disbursment Amount : <i class="fa fa-inr"></i><? echo round($overdue[0]['total']) ?></span>
                            <div class="table-responsive">
                                <table class="display table table-bordered disbursement-table" >
                                <thead><tr><th>#</th><th>Order Date</th><th>Disbursement Date</th><th>Order ID</th><th>Vendor Name</th><th>Product Details</th><th>Quantity</th><th>Disbursement Amount</th></tr></thead>
                                <tbody>
                                    <?php $orderdata = $order->disbursmentOrders("overdue");
                                    if(count($orderdata)){
                                    for($j=0;$j<count($orderdata);$j++){ $cnt=$j+1; $r4=$orderdata[$j]; ?>
                                    <tr id='<?php echo $r4['pur_id'] ?>'>
                                        <td><?=$cnt; ?></td>
                                        <td><?=date("d M Y h:i A",strtotime($r4['purchase_date'])) ?></td>
                                        <td><?=date("d M Y ",strtotime($r4['disbustment_date'])) ?></td>
                                        <td class="text-primary cc-cursor-pointer" onclick="get_item(<?php echo $r4['item_id'] ?>)"><?=$r4['purchase_order_id']; ?></td>
                                        <td> <a href="<?=ADMINURL; ?>vendor/editvendor.php?vendor=<?=base64_encode($r4['vendor']); ?>" target="_blank"> <?=$r4['vendor_name']; ?></a></td>
                                        <td><a href="<?=ADMINURL; ?>product/view-details.php?prod_id=<?=base64_encode($r4['product_id']); ?>" target="_blank"><?=$r4['display_product_name']; ?></a><br>
                                        <? $variationon=$r4['variation_on'];
                                        if($variationon!=""){
                                        $variationonarr=explode("|",$variationon); $variativaluearr=explode("|",$r4['variation_values']);
                                        for($v=0;$v<count($variationonarr);$v++){ ?> <span><?=$variationonarr[$v]; ?>:<?=$variativaluearr[$v]; ?></span><? } } ?></td>
                                        <td><?=$r4['quantity'];?></td>
                                        <td><?=$r4['quantity'] *($r4['vendor_per_item_price_withoutgst']+($r4['vendor_per_item_price_withoutgst']*$r4['tax_percentage']/100));?></td>
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
<?php  include 'order/order_common.php'; ?>
<script src="<?php echo SITEURL; ?>/js/backend/chart.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/backend/utils.js"></script>
<script src="<?php echo SITEURL; ?>/js/datepicker/moment.js"></script>
<script src="<?php echo SITEURL; ?>/js/datepicker/bootstrap-datetimepicker.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script>
$('.disbursement-table').DataTable();
var date = new Date();
var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
$('#fromdt').datetimepicker({ ignoreReadonly: true, format: 'DD-MM-YYYY', disabledTimeIntervals:false, maxDate: moment(), defaultDate:firstDay});
$('#todt').datetimepicker({ ignoreReadonly: true, format: 'DD-MM-YYYY', disabledTimeIntervals:false, maxDate: moment(), defaultDate:moment() });
function generatereport(){
    fromdt = $("#fromdt").val();
    todt = $("#todt").val();
    new_fromdt = fromdt.split("-").reverse().join("-");
    new_todt = todt.split("-").reverse().join("-");
    if(fromdt == "" || todt == ""){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please fill details").delay(3000).fadeOut();
    } else if(new_fromdt > new_todt ){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please check dates").delay(3000).fadeOut();
    } else{
        var info = {fromdt:fromdt, todt:todt,action:"date_range_search"};
        $.ajax({
            type: "POST",
            url: "searchdata.php", 
            data: info,
            success: function(response) {
                jsondata = JSON.parse(response);
                user = jsondata['user'];
                vendor = jsondata['vendor'];
                order = jsondata['order'];
                $("#user_date_range").html(user); 
                $("#order_date_range").html(order); 
                $("#vendor_date_range").html(vendor);
            }
        });
    }
}
$("document").ready(function(){
    $(".genreport").trigger('click');
    $("#search").on("keyup", function() {
        var search = $("#search").val();
        var info = {
            search: search,
            action:"search",
        };
        $.ajax({
            type: "POST",
            url: "searchdata.php",
            data: info,
            success: function(response) {
                $(".searchresult").html(response);
                $("#hidedata").show();
            }
        });
    });
    $("#hidedata").click(function(){ $(".searchresult").html(""); });
});
function createConfig(){
    return {
    type: 'line',
    data: {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul','August','Sept','Oct','Nov','Dec'],
    datasets: [{
        label: '  Inprocess',
        backgroundColor: "#007bff",
        borderColor: "#007bff",
        data: <?=json_encode($graphinprocessData); ?>,
        fill: false,
    },{
        label: '  Delivered',
        backgroundColor: "#28A745",
        borderColor: "#28A745",
        data: <?=json_encode($graphData); ?>,
        fill: false,
    },{
        label: '  Cancelled',
        borderColor: "#DC3545",
        backgroundColor: "#DC3545",
        data: <?php echo json_encode($graphcancelData)  ?>,
        fill: false,
    },{
        label: '  Return',
        borderColor: "#DF9200",
        backgroundColor: "#FFC107",
        data: <?php echo json_encode($graphreturnData)  ?>,
        fill: false,
    }]
    }, options: {
    responsive: true,
    tooltips: {
        callbacks: {
         title: function(){ }
      },
        position: 'nearest', mode: 'index', intersect: false, yPadding: 10, xPadding: 10, caretSize: 8, backgroundColor: 'rgba(0,0,0,0.8)', bodyFontColor: "#fff", borderColor: 'rgba(0,0,0,0.8)', borderWidth: 1, bodySpacing: 7
    },
    legend: { position: "top", labels: { boxWidth: 13, defaultFontFamily: "Roboto", } }, scales: { yAxes: [{ ticks: { min: 0, stepSize: 10, } }] } } };
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
<script>
var ctx = document.getElementById("myChart").getContext('2d');
var chartData = [<?php echo $Windows7cnt; ?>,<?php echo $Windows10cnt; ?>,<?php echo $androidcnt; ?>,<?php echo $ioscnt; ?>,<?php echo $linuxcnt; ?>];
var chartLabels = ["Windows 7","Windows 10","Android","IOS","Linux"];
var chart = new Chart(ctx, {
  type: 'pie',
  data: {
    labels: chartLabels,
    datasets: [{
      backgroundColor: ["#17A2B8", "#007BFF", "#28A745", "#DF9200", "#dc3545"],
      data: chartData
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: true,
    legend: {
      display: false
    },
    legendCallback: function(chart) {
      var text = [];
      text.push('<ul class="chart-legend list-unstyled mb-0 text-center">');
      var ds = chart.data.datasets[0];
      var sum = ds.data.reduce(function add(a, b) { return a + b; }, 0);
      for (var i=0; i<ds.data.length; i++) {
        text.push('<li>');
        var perc = Math.round(100*ds.data[i]/sum,0);
        text.push('<span style="background-color:' + ds.backgroundColor[i] + '">' + '</span>' + chart.data.labels[i] + ' <b>('+ds.data[i]+')</b>'); //('+perc+'%)
        text.push('</li>');
      }
      text.push('</ul>');
      return text.join("");
    }
  }
});
var myLegendContainer = document.getElementById("legend");
// generate HTML legend
myLegendContainer.innerHTML = chart.generateLegend();
// bind onClick event to all LI-tags of the legend
var legendItems = myLegendContainer.getElementsByTagName('li');
for(var i = 0; i < legendItems.length; i += 1){
    legendItems[i].addEventListener("click", legendClickCallback, false);
}
function legendClickCallback(event){
    event = event || window.event;
    var target = event.target || event.srcElement;
    while(target.nodeName !== 'LI'){
        target = target.parentElement;
    }
    var parent = target.parentElement;
    var chartId = parseInt(parent.classList[0].split("-")[0], 10);
    var chart = Chart.instances[chartId];
    var index = Array.prototype.slice.call(parent.children).indexOf(target);
    var meta = chart.getDatasetMeta(0);
    console.log(index);
    var item = meta.data[index];
    if (item.hidden === null || item.hidden === false){
        item.hidden = true;
        target.classList.add('hidden');
    } else{
        target.classList.remove('hidden');
        item.hidden = null;
    }
    chart.update();
}
</script>
</body>
</html>