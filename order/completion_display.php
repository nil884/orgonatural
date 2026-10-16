<?php include("../includes/configuration.php");
include("../classes/order.php");  require_once('../classes/user.php');  require_once('../classes/product.php');
$prod = new Product(); $user = new User(); $order = new Order($prod,$user);
$imgtype = "product";
include("../getimgpath.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?=($pagetitle!=""?$pagetitle:SITE_TITLE); ?></title>
    <meta name="description" content="<?=($categorydetails[0]['metadescription']!=""?$categorydetails[0]['metadescription']:METADESCRIPTION); ?>">
    <meta name="keyword" content="<?=($categorydetails[0]['keywords']!=""?$categorydetails[0]['keywords']:METAKEYWORDS); ?>">
    <?php include "../commoncss.php"; ?>
</head>
<body>
<div class="main-wrap">
    <?php include "../menu.php"; ?>
    <div class="container pt-4 pb-4">
        <div class="row">
            <div class="col-md-12">
                <?php $txnid = $_GET['transid'];
                $orderdata = $order->getOrderFullDetails($txnid);
                $gateway = selectQuery(ORDER,"gateway_response","transaction_id='".$txnid."'");
               
                if($orderdata['payment_status'] == "success"){
                $all_item_detail = ""; $free_ship = "";
                //$item_detail=selectQuery(BUYER,"u_fname,u_lname,u_email","u_id=".$orderdata['user_id']);
                $item_detail = $order->getVendororder_item($orderdata['id']); ?>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="cc-fw-5 mb-0">Your order is placed successfuly</h5>
                    <div><a href="<?=SITEURL; ?>/account/myorder.php" class="btn btn-primary px-3">My Orders</a></div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-lg-3">
                        <div class="card mb-3">
                            <div class="row">
                                <div class="col-md-6 col-lg-12">
                                    <div class="card-body border-bottom ord-suc-user-det">
                                        <h6 class="cc-fw-5 mb-2 text-primary">Your Detail</h6>
                                        <div class="mb-1 cc-fw-5"><?php echo $orderdata['u_fname'].' '.$orderdata['u_lname'].'</div> <div class="mb-2 text-muted">'.$orderdata['u_email'].'</div>
                                        <div class="cc-fw-5">Phone Number</div>
                                        <div class="mb-2 text-muted">'.$orderdata['u_mobile'] ?></div>
                                        <?php if($orderdata['company_name'] != ""){echo "<div class='cc-fw-5'>Company name </div><div class='mb-2 text-muted'>".$orderdata['company_name']."</div>"; } ?> 
                                        <?php if($orderdata['tax_no'] != ""){echo "<div class='cc-fw-5'>GST NO</div> <div class='text-muted'>".$orderdata['tax_no']."</div>"; } ?>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-12">
                                    <div class="card-body">
                                        <h6 class="cc-fw-5 mb-2 text-primary">Shipping  Detail</h6>
                                        <div class="mb-1 cc-fw-5">
                                            <?php echo $orderdata['shipping_name'].' ('.$orderdata['shipping_type'].')</div><div class="cc-fw-5">Phone Number</div> <div class="mb-2 text-muted">'.$orderdata['shipping_mobile'].'</div>
                                            <div class="text-muted">'.$orderdata['address'].' '.$orderdata['landmark'].",  ".$orderdata['city'].", ".$orderdata['state'].", ".$orderdata['country']." - ".$orderdata['pincode']; ?>.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-9">
                        <div class="card mb-3">
                            <div class="row m-0">
                                <div class="col-sm-6 col-md-3 border-right px-0 ord-suc-trans">
                                    <div class="p-3">
                                        <div class="cc-fw-5">Order ID</div>
                                        <div class="text-muted"><?=$orderdata['order_id'];?></div>
                                    </div>
                                </div>  
                                <div class="col-sm-6 col-md-3 border-right px-0 ord-suc-trans">
                                    <div class="p-3">
                                        <div class="cc-fw-5">Payment Mode</div>
                                        <div><?=($orderdata['payment_mode']=="COD"?$orderdata['payment_mode']:"Online"); ?></div>
                                    </div>  
                                </div>
                                <div class="col-sm-6 col-md-3 border-right px-0 ord-suc-trans">
                                    <div class="p-3">
                                        <div class="cc-fw-5">Transaction ID</div>
                                        <div class="text-muted"><?=$orderdata['transaction_id'];?></div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3 px-0 ord-suc-trans">
                                    <div class="p-3">
                                        <div class="cc-fw-5">Total Amount</div>
                                        <div class="text-muted"><?=$orderdata['payable_amount'];?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <!-- <div><? echo $all_item_detail ?></div> -->
                            <div>
                                <? for($i=0;$i<count($item_detail);$i++){
                                $order_item = $item_detail[$i];
                                if($order_item['product_image']){
                                    $img = SITEURL."/img/order_img/".$order_item['product_image'];
                                }else{
                                    $img = SITEURL."/img/projectimage/product-default.png";
                                }
                                if($order_item['isFreeShipping'] ==1  && $order_item['shipping_charges'] != 0 ){ $free_ship = "Free Shipping"; } ?>
                                <div class='card mb-3 order-succ-row'>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class='col-3 col-sm-3 col-md-2 col-lg-2 del-suc-data-col'><div class="pro-list-thumb"><img src='<?=$img;?>' alt="order-prod-thumb" class="rounded img-fluid img-thumbnail"></div></div>
                                            <div class='col-9 col-sm-9 col-md-10 col-lg-10 del-suc-data-col'>
                                                <div class='row'>
                                                    <div class='col-md-6 col-lg-7'>
                                                        <span class='cc-fw-5'><?=$order_item['product_name'];?></span>
                                                        <div class='text-success my-1'><?=$free_ship;?></div>
                                                        <div>Quantity : <?=$order_item['quantity'];?></div>
                                                        <? if($order_item['discount_code']!=""){  ?>
                                                        <div class='alert alert-success mt-2 mb-0 py-2 px-3 d-inline-block small'>Promocode '<?=$order_item['discount_code'];?>' Applied</div>
                                                        <? } ?>
                                                    </div>
                                                    <div class='col-md-6 col-lg-5 tot-amt-tab'>
                                                        <table>
                                                            <tr><td class="pb-1 pr-4">Taxable</td><td class="pb-1"><i class="fa fa-inr"></i> <?=$order_item['taxable_without_gst'];?></td></tr>
                                                            <? if($order_item['discount_code']!=""){ ?>
                                                            <tr><td class="pb-1">Discount </td><td class="pb-1"><i class="fa fa-inr"></i>  -<?=$order_item['discount_amount'];?></td></tr>
                                                            <? }
                                                            if($order_item['cgst1']!="0.00"){
                                                            ?><tr><td class="pb-1 pr-4">CGST <small>(<?=$order_item['cgst1'];?>% )</small></td><td class="pb-1"><i class="fa fa-inr"></i> <?=$order_item['cgst2'];?></td></tr>
                                                            <tr><td class="pb-1 pr-4">SGST <small>(<?=$order_item['sgst1'];?>%)</small></td><td class="pb-1"><i class="fa fa-inr"></i> <?=$order_item['sgst2'];?></td></tr>
                                                            <?}else{
                                                            ?><tr><td class="pb-1 pr-4">IGST <small>(<?=$order_item['igst1'];?>%)</small></td><td class="pb-1"><i class="fa fa-inr"></i> <?=$order_item['igst2'];?></td></tr>
                                                            <? }?>
                                                            <tr><td class="pb-1 pr-4">Shipping Charges </td><td class="pb-1"><i class="fa fa-inr"></i> <? if($order_item['isFreeShipping'] == 1 && $order_item['shipping_charges'] != 0 ) { echo "0";  echo "<del>".$order_item['shipping_charges']."</del>"; }  else { echo $order_item['shipping_charges']; }?></td></tr>
                                                            <tr class="<?php ($order_item['cod_charges']!=0?'':'d-none'); ?>"><td class="pb-1 pr-4">COD Charges </td><td class="pb-1"><i class="fa fa-inr"></i> <? echo $order_item['cod_charges']; ?></td></tr>
                                                            <tr><td class="pr-4">Total <?=($orderdata['payment_mode']=="COD"?"Payable":"Paid"); ?> </td><td><i class="fa fa-inr"></i> <?=$order_item['total_payable'];?></td></tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <? } ?>
                            </div>
                        </div>
                    </div>
                    <?php } else{ ?>
                    <div class="d-flex justify-content-between mb-3">
                        <h5 class="cc-fw-5">Your Order is not placed</h5>
                        <div><a href="<?=SITEURL; ?>/account/myorder.php" class="btn btn-secondary btn-sm">My Orders</a></div>
                    </div>
                    <? echo "<h3>Your Payment status is ". $orderdata['payment_status'] .".</h3>";
                    echo $gateway[0]['gateway_response'];
                    if($orderdata['payment_mode'] != "COD")
                    echo "<h4 class='lightText'>Your transaction id for this transaction is ".$txnid.".<br> You may try again.</h4>"; }?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include "../footer.php"; ?>
</body>
</html>