<?php include("../includes/configuration.php"); include("../classes/order.php"); require_once('../classes/user.php'); require_once('../classes/product.php'); $prod = new Product();  $user = new User(); $order = new Order($prod,$user); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Orders : <?php echo SITE_TITLE; ?></title>
    <meta name="description" content="<?php echo METADESCRIPTION; ?>">
    <meta name="keywords" content="<?php echo METAKEYWORDS; ?>">
    <link rel="canonical" href="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>">
    <?php include "../commoncss.php" ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/uploadImagepopup/cropper.css">
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/uploadImagepopup/main.css">
</head>
<body>
<div class="main-wrap"> 
<?php include "notification_account.php" ?>    
    <?php include "../menu.php" ?>
    <div class="container pt-3"><div class="row"><div class="col-12"><ul class="breadcrumb p-0 mb-0"><li class="breadcrumb-item"><a href="<?=SITEURL;?>">Home</a></li><li class="breadcrumb-item active">My Orders</li></ul></div></div></div>
    <div class="content py-3">
        <div class="container">
            <h2 class="mb-0 cc-fw-5 h5">My Orders</h2>
            <p class="mb-3 text-muted">View and track your orders</p>
            <div class="card cc-shadow-1">
                <div class="row mx-0">
                    <?php include('sidebar.php')?>
                    <div class="col-md-12 col-lg-9 px-0">
                        <?php $orderdata = $order->getUserOrders($getbuyer_details[0]['u_id']);
                        if(count($orderdata)){
                        for($j=0;$j<count($orderdata);$j++){
                        $orderdata_detail = $orderdata[$j];
                        $seprate_order = $orderdata_detail['id'];
                        $item_detail = $order->getVendororder_item($seprate_order); ?>
                        <div class="rec-order-short-info"> 
                            <div class="card-header d-flex justify-content-between align-items-center bg-light py-2">
                                <div class="align-content-center cc-primary-color"><b>Order ID :</b> <?php echo $orderdata_detail['order_id'] ?></div>
                                <div><span><b>Total Amount : <i class="fa fa-inr"></i>  <?php echo $orderdata_detail['payable_amount']; ?></b></span>&nbsp;&nbsp;<a class="btn btn-primary btn-sm px-3" href="<? echo SITEURL ?>/account/order/<?php echo $orderdata_detail['transaction_id']; ?>">Details</a></div>
                            </div>
                            <?php for($i=0;$i<count($item_detail);$i++){ 
                            $order_item = $item_detail[$i];
                            if($order_item['variation_on'] != ""){
                                $varation_on = explode(",",$order_item['variation_on']);
                                $varation_values = explode(",",$order_item['variation_values']);   
                            } 
                            if($order_item['product_image']){
                                $img = SITEURL."/img/order_img/".$order_item['product_image'];
                            } else{
                                $img = SITEURL."/img/projectimage/product-default.png";
                            } ?>
                            <div class="card-body rec-order-list border-bottom">
                                <div class="row m-0">
                                    <div class="col-3 col-sm-3 col-md-2 col-lg-2 col-xl-2 pl-0 pl-sm-0 px-md-0"><div class="pro-list-thumb"><img src="<?php echo $img ?>" alt="order-prod-thumb" class="rounded img-fluid img-thumbnail"></div></div>
                                    <div class="col-9 col-sm-9 col-md-10 col-lg-10 col-xl-10 px-0">
                                        <div class="row m-0">
                                            <div class="col-md-8 col-lg-8 col-xl-8 px-0">
                                                <h2 class="cc-fw-5 h6"><?php echo $order_item['product_name'] ?></h2>
                                                <? if($order_item['variation_on'] != ""){ ?>
                                                <ul class="list-unstyled order-item-spec mb-2">
                                                    <?php for($v=0;$v<count($varation_on);$v++){?>
                                                    <li class="mr-2"><span class="text-muted"><? echo $varation_on[$v] ?></span> : <? echo $varation_values[$v] ?></li>
                                                    <?php } ?>
                                                </ul>
                                                <?php } ?>
                                                <div class="mb-1">Quantity : <?php echo $order_item['quantity'] ?></div>
                                                <div class="mb-1">Current status : <span class="badge badge-success"><?php echo $order_item['order_current_Status'] ?></span></div>
                                            </div>
                                            <div class="col-md-4 col-lg-4 col-xl-4 px-0 tot-amt-tab">
                                                <table>
                                                    <?php/* if($order_item['total_with_gst'] - $order_item['taxable_without_gst'] != 0 ){ ?>
                                                    <tr><td class="pb-2 cc-fw-5">Total GST</td><td class="pl-3 pb-2"><i class="fa fa-inr"></i> <?php echo $order_item['total_with_gst'] - $order_item['taxable_without_gst'] ?></td></tr>
                                                    <?php }*/ ?>
                                                    <!--<tr class="h6"><td class="cc-fw-5">Total Paid</td><td class="pl-3"><i class="fa fa-inr"></i> <?php echo $order_item['total_payable'] ?></td></tr>-->
                                                </table>
                                            </div>
                                        </div>
                                    </div>                                
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                      <?php }
                        }else{
                           ?> 
                        <div class="pt-5 pb-5 text-center">
                            <img src="<?php echo SITEURL; ?>/img/projectimage/cart-empty.png" alt="address-not-found" width="120" class="m-auto">
                            <p class="lead mt-3 text-muted">No recent orders found</p>
                            <a href='<?=SITEURL;?>' class='btn btn-primary py-2'>Start Shopping</a></div>
                         <?
                        } ?>
                    </div>   
                </div>
            </div>
        </div>
    </div>
</div>
<?php include "../footer.php" ?> 
<?php include "profilejs.php" ?> 
</body>
</html>