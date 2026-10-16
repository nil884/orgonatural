<? include("../../includes/configuration.php");
include("../../classes/order.php"); require_once('../../classes/user.php'); require_once('../../classes/product.php');
$prod = new Product(); $user = new User(); $order = new Order($prod,$user);
$conf=$getconfigdetails[0]; 
if($conf['fraud_detection']=='1'&&($conf['fraud_apikey']!=''&&$conf['fraud_apisecrete']!='')){$verifyfraud=1;}else{$verifyfraud=0;}
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo SITE_TITLE; ?> : Orders Details</title>
    <? include "../commoncss.php"; ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/dataTable/dataTables.bootstrap4.min.css">
</head>
<body>
<div class="page-body-wrapper">
    <? include '../header.php'; ?>
    <? include '../sidebar.php'; ?>
    <div class="main-panel"> 
        <div class="dashbody">
           <!-- <div class="card card-body">
                <div class="alert alert-info mb-0">Failed — Payment failed or was declined by the payment gateway (unpaid)</div>
            </div>-->
            <?php $txnid = $_GET['transid'];
            $orderdata = $order->getOrderFullDetails($txnid);
            $all_item_detail = ""; $free_ship = "";
            $item_detail = $order->getVendororder_item($orderdata['id']); ?>
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2">
                    <div><h5 class="card-head-title">Order Details</h5></div>
                    <div class="btn-actions-pane-right"><? if($orderdata['payment_status']!="failure"){?><a class="btn btn-primary btn-sm" href="<?=SITEURL;?>/print/generate_invoice.php?auth=<?=base64_encode("admin");?>&bill=<?=base64_encode("sale");?>&billno=<?=base64_encode($orderdata['order_id']); ?>" target="_blank">Print Sale Invoice</a><?} ?>
                    <button onclick="goBack()" class="btn btn-secondary btn-sm">Back</button></div>
                </div>
                <div class="card-body p-0">
                    <div class="row m-0">
                        <div class="ord-short-det-col border-right px-0">
                            <div class="p-3">
                                <div class="cc-fw-5 text-primary">Order Date</div>
                                <div><?php echo date("d M Y h:i A",strtotime($orderdata['order_date'])); ?></div>
                            </div>
                        </div>  
                        <div class="ord-short-det-col border-right px-0">
                            <div class="p-3">
                                <div class="cc-fw-5 text-primary">Order ID</div>
                                <div><?=$orderdata['order_id'];?></div>
                            </div>
                        </div>  
                        <div class="ord-short-det-col border-right px-0">
                            <div class="p-3">
                                <div class="cc-fw-5 text-primary">Payment Mode</div>
                                <div><?=$orderdata['payment_mode']; ?></div>
                            </div>
                        </div>
                        <div class="ord-short-det-col border-right px-0">
                            <div class="p-3">
                                <div class="cc-fw-5 text-primary">Payment ID</div>
                                <div><?=($orderdata['payment_mode']!="COD"?$orderdata['payment_id']:"-");?></div>
                            </div>
                        </div>
                         <div class="ord-short-det-col border-right px-0">
                            <div class="p-3">
                                <div class="cc-fw-5 text-primary">Transaction ID</div>
                                <div><?=$orderdata['transaction_id'];?></div>
                            </div>
                        </div>

                        <? if($orderdata['discount_amount']!=0){ ?>
                            <div class="ord-short-det-col border-right px-0">
                                <div class="p-3">
                                    <div class="cc-fw-5 text-primary">Discount </div>
                                    <div><?=$orderdata['discount_amount']; ?></div>
                                </div>
                            </div>
                        <? } ?>
                        <div class="ord-short-det-col border-right px-0">
                            <div class="p-3">
                                <div class="cc-fw-5 text-primary">Total Amount</div>
                                <div><?=$orderdata['payable_amount'];?></div>
                            </div>
                        </div>
                                                <div class="ord-short-det-col px-0">
                            <div class="p-3">
                                <div class="cc-fw-5 text-primary">Payment Status</div>
                                <div><?=($orderdata['payment_mode']=="COD"?"COD":$orderdata['payment_status']); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-lg-12 col-xl-3 pr-xl-0">
                    <div class="card mb-3">
                        <div class="card-body border-bottom">
                            <h6 class="cc-fw-5 mb-2 text-primary">Buyer Detail</h6>
                            <? if($orderdata['user_id']!=0){?> <a href="<?=ADMINURL; ?>buyer/buyersdetails.php?u_id=<?=base64_encode($orderdata['user_id']) ?>" target="_blank"> <? } ?>
                            <div class="mb-1 cc-font-weight-5"> <?php echo $orderdata['u_fname'].' '.$orderdata['u_lname'];?></div><div class="mb-2"><?=$orderdata['u_email'];?></div>
                            <? if($orderdata['user_id']!=0){?></a> <?}?>
                            <div class="cc-font-weight-5">Phone Number</div>
                            <div class="mb-1"><?=$orderdata['u_mobile'] ?></div>
                            <? if($verifyfraud==1){?><div class="fraud1"><button type="button" class="btn btn-sm btn-outline-dark fbtn1" onclick="verifyfraud('<?=$orderdata['u_mobile'];?>','fraud1','fbtn1')">Verify Fraud</button></div> <? } ?>
                            <?php if($orderdata['company_name'] != "") {echo "<div class='cc-font-weight-5'>Company name</div><div class='mb-2'>".$orderdata['company_name']."</div>"; } ?> 
                            <?php if($orderdata['tax_no'] != "") {echo "<div class='cc-font-weight-5'>GST NO</div><div >".$orderdata['tax_no']."</div>"; }  ?>
                        </div>
                        <div class="card-body">
                            <h6 class="cc-fw-5 mb-2 text-primary">Shipping  Detail</h6>
                            <div class="mb-1 cc-fw-5"><?php echo $orderdata['shipping_name'].' ('.$orderdata['shipping_type'].')';?></div>
                            <div class="cc-font-weight-5">Phone Number</div>
                            <div class="mb-1"><?=$orderdata['shipping_mobile'];?></div>
                            <? if($verifyfraud==1){?><div class="fraud2"><button type="button" class="btn btn-sm btn-outline-dark fbtn2" onclick="verifyfraud('<?=$orderdata['shipping_mobile'];?>','fraud2','fbtn2')">Verify Fraud</button></div> <? } ?>
                            <div class="pt-2"><?=$orderdata['address'].' '.$orderdata['landmark']."  ".$orderdata['city']." ".$orderdata['state'].", ".$orderdata['country']." ".$orderdata['pincode']; ?>.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-12 col-xl-9">
                    <!--<div><? echo $all_item_detail ?></div>-->
                    <? for($i=0;$i<count($item_detail);$i++){
                    $order_item = $item_detail[$i]; 
                    $getprodid=selectQuery(PRODINFO,"parent_id","id=".$order_item['product_id']);
                   $pid=($getprodid[0]['parent_id']!=0?$getprodid[0]['parent_id']:$order_item['product_id']);
                    $itemid=$order_item['item_id'];
                    $status=$order_item['order_current_Status'];
                    if($order_item['product_image']){
                        $img = SITEURL."/img/order_img/".$order_item['product_image'];
                    }else{
                        $img = SITEURL."/img/projectimage/product-default.png";
                    }
                    if($order_item['isFreeShipping'] ==1  && $order_item['shipping_charges'] != 0 ){ $free_ship = " <div class='my-1'><div class='alert alert-success my-1 mb-0 py-1 px-2 d-inline-block'>".$orderdata['Freeshippingreason']."</div> </div>"; } ?>
                
                    <div class="card border-bottom order-succ-row">

                        <div class='row mx-0'>
                            <div class='col-lg-2 py-3 del-suc-data-col'><img src='<?=$img;?>' alt="order-det-thumb" height="120" class="mw-100 img-thumbnail"></div>
                            <div class='col-lg-10 py-3 del-suc-data-col'>
                                <div class='row'>
                                    <div class='col-lg-7'>
                                    <? if(count($getprodid)){?><span class='cc-fw-5'><a href="<?=ADMINURL; ?>product/view-details.php?prod_id=<?=base64_encode($pid); ?>" target="_blank"><?=$order_item['product_name'];?></a><br> (<a href="<?=SITEURL."/".getUrl("Product",$order_item['product_id']); ?>" target="_blank">Store Preview</a>)</span>    
                                     <?}else{?><span class='cc-fw-5'><?=$order_item['product_name'];?><br> (Deleted Product)</span><?} ?>
                                      
                                       <?=$free_ship;?>
                                        <div class="mb-2">Quantity : <?=$order_item['quantity'];?></div>
                                        <div class="mb-1">HSN Code : <? if($order_item['hsn_code'] == "") { echo "NA"; } else { echo $order_item['hsn_code']; }?></div>
                                        <div class="mb-1">SKU Code : <? if($order_item['sku'] == "") { echo "NA";} else { echo $order_item['sku']; } ?></div>

                                        <? if($order_item['discount_code']!=""){ ?>
                                        <div class='alert alert-success mt-2 mb-0 py-2 px-3 d-inline-block small'>
                                        Promocode '<?=$order_item['discount_code'];?>' Applied</div>
                                        <? } ?>
                                        
                                        <?php if ($status == "Canceled"){ ?>
                                                <div class="mb-2"> Cancel Reason : <?php echo $order_item['cancel_reason'] ?>  </div>
                                                <div class="mb-2"> Cancelled by : <?php echo $order_item['cancelled_by'] ?>  </div>
                                              <?php }  ?>  

                                    </div>
                                    <div class='col-lg-5 tot-amt-tab'>
                                        <table>
                                            <tr><td class="pb-1 pr-4">Taxable</td><td class="pb-1"><i class="fa fa-inr"></i> <?=$order_item['taxable_without_gst'];?></td></tr>
                                            <? if($order_item['discount_code']!=""){ ?>
                                            <tr><td class="pb-1">Discount </td><td class="pb-1"><i class="fa fa-inr"></i> -<?=$order_item['discount_amount'];?></td></tr>
                                            <? } 
                                            ?><tr><td class="pb-1 pr-4">CGST <small>(<?=$order_item['cgst1'];?>% )</small></td><td class="pb-1"><i class="fa fa-inr"></i> <?=$order_item['cgst2'];?></td></tr>
                                            <tr><td class="pb-1 pr-4">SGST <small>(<?=$order_item['sgst1'];?>%)</small></td><td class="pb-1"><i class="fa fa-inr"></i> <?=$order_item['sgst2'];?></td></tr>
                                            <tr><td class="pb-1 pr-4">IGST <small>(<?=$order_item['igst1'];?>%)</small></td><td class="pb-1"><i class="fa fa-inr"></i> <?=$order_item['igst2'];?></td></tr>
                                            

                                            <tr><td class="pr-4">Total <?=($orderdata['payment_mode']=="COD"?"Payable":"Paid"); ?> </td><td><i class="fa fa-inr"></i> <?=$order_item['total_payable'];?></td></tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="border-top">  
                            <div class="col-md-12 py-2 order-status-col">
                                <span class="mr-2">Current Status - <?=$order_item['order_current_Status'];?></span>
                                <? if($order->getOrderItemDetatils($itemid,"return_request_date")==""){?><button type="button" class="track-ord-status btn btn-link btn-xs p-0 text-link" onclick="track('<?=$order_item['shipping_shipment_id']; ?>','tracking-details<?=$i;?>')">View More</button><?}
                                else{?><button type="button" class="track-ord-status btn btn-link btn-xs p-0 text-link" onclick="trackReturn('<?=$order_item['return_shipment_id']; ?>','tracking-details<?=$i;?>')">View More</button><?} ?>
                                <? if($orderdata['payment_status']!="failure"){?><a class="btn btn-primary btn-sm" href="<?=SITEURL;?>/print/generate_invoice.php?auth=<?=base64_encode("admin");?>&bill=<?=base64_encode("purchase");?>&inv=<?=base64_encode($order_item['item_id']); ?>" target="_blank">Print Purchase Invoice</a>&nbsp;&nbsp;<?} ?>
                                <? if($order_item['order_current_Status']!="Delivered"&&$order_item['order_current_Status']!="Canceled"){?> <button type="button" class="btn btn-primary btn-sm" onclick="confirmCancel('<?=$order_item['shipping_order_id']; ?>','<?=$order_item['transaction_id']; ?>','<?=$order_item['item_id']; ?>',0)">Cancel Item</button><?} ?>
                            </div>
                            <div id="tracking-details<?=$i;?>" class="tracking-details col-md-12 px-0 border-top cc-display-none"></div>
                            <div> <? if($order->getOrderItemDetatils($itemid,"refund_status")=="1"){ ?> <div class="alert success">Amount of Rs <?=(($orderdata['payment_mode']!="COD"&&$order_item['order_current_Status']=="Canceled")||$order_item['order_current_Status']!="Canceled"?$order->getOrderItemDetatils($itemid,"refundable_amount"):0.00); ?> Initiated <?=($order->getOrderItemDetatils($itemid,"refund_id")!=""?"with refund ID ".$order->getOrderItemDetatils($itemid,"refund_id"):""); ?><?=($order->getOrderItemDetatils($itemid,"refund_date")!="0000-00-00 00:00:00"?" on ".date("d M Y H:i:s",strtotime($order->getOrderItemDetatils($itemid,"refund_date"))):""); ?> <?=($order->getOrderItemDetatils($itemid,"refund_id")!=""?"<b>(Refund Status: ".$order->getOrderItemDetatils($itemid,"refund_response").")</b>":""); ?></div> <? } ?> </div>
                            
                        </div>
                    </div>     
                    <? } ?>
                </div>
            </div>
        </div>
        <? include "../footer.php"; ?>
    </div>
</div>
<?php include 'order_common.php'; ?>
<script>
   function verifyfraud(mnumber,spanclass,btnclass){
    $(".btnclass").html("Verifying").attr("disabled",true);
    $.ajax({
        type: "POST",
        url: "<?=SITEURL; ?>/admin/verifyfraud.php",
        data: {m:mnumber},
        success: function(response){
           json = JSON.parse(response);
           if(json['status']=="success"){
            $("."+spanclass).html("<div class='alert p-2 mb-0 alert-"+(json['isFraud']==1?"danger":"success")+"'>"+json['message']+" By <a href='https://www.hellofraud.com/' target='_blank'>HelloFraud</a>");
           }else{
            $("."+spanclass).html('<div class="alert alert-danger p-2">'+json['message']+' By <a href="https://www.hellofraud.com/" target="_blank">HelloFraud</a></div>');
           }
        }
    });
}
</script>
</body>
</html>