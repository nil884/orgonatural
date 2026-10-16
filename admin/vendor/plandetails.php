<?php include("../../includes/configuration.php");
$vendor_id = base64_decode($_REQUEST['vendor']);
$getvendor = selectQuery(VENDOR,"*","dealer_id=".$vendor_id);
$getcurrplan = selectQuery(VENDORPLANSELECTED,"*","sel_id=".$getvendor[0]['plan']);
$getpaymentdata = selectQuery(VENDORPAYMENT,"*","dealer_id=".$vendor_id." order by pay_id DESC"); ?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <title><?php echo SITENAME; ?> : Payment History</title>
    <?php include('../commoncss.php') ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/dataTable/dataTables.bootstrap4.min.css">
</head>
<body>
<div class="page-body-wrapper">
    <? include '../header.php'; ?>
    <? include '../sidebar.php'; ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2">
                <div><h5 class="card-head-title">Payment History - <?php echo $getvendor[0]['nickname']; ?></h5></div><div class="btn-actions-pane-right"><button type="button" onclick="goBack()" class="btn btn-secondary btn-sm">Back</button></div></div>
                <div class="card-body pb-0">
                    <div class="row">
                          <div class="form-group mb-2 col-sm-6 col-md-12 col-lg-5 col-xl-4 <?php echo $class12; ?>">
                            <div class="row">
                                <label class="col-5 col-sm-5 col-md-4 col-lg-5 control-label">Current Plan</label>
                                <div class="col-7 col-sm-7 col-md-8 col-lg-7"><?php if($getcurrplan[0]['plan']!=""){ echo $getcurrplan[0]['plan']; }
                                else{ echo "[Not Defined]"; } ?></div>
                            </div>
                        </div>
                        <?php if($getcurrplan[0]['plan']!=""||$getcurrplan[0]['healer']=="1"){ ?>
                        <div class="form-group mb-2 col-sm-6 col-md-12 col-lg-5 col-xl-4 <?php echo $class12; ?>">
                            <div class="row">
                                <label class="col-5 col-sm-5 col-md-4 col-lg-5 control-label">Amount</label>
                                <div class="col-7 col-sm-7 col-md-8 col-lg-7"><?php if($getcurrplan[0]['price']!="NULL"){?><span class="text-success"> <i class="fa fa-inr"></i> <?php echo $getcurrplan[0]['price'];?>/-&nbsp;</span><?}else{?><span class="text-danger">Pending</span><? } ?></div>
                            </div>
                        </div>
                        <div class="form-group mb-2 col-sm-6 col-md-12 col-lg-5 col-xl-4 <?php echo $class12; ?>">
                            <div class="row">
                                <label class="col-5 col-sm-5 col-md-4 col-lg-5 control-label">Payment Status</label>
                                <div class="col-7 col-sm-7 col-md-8 col-lg-7"><?php if($getcurrplan[0]['payment_status']=="Received"){?><span class="bg-success p-1 rounded text-white small"><i class="fa fa-check"></i> Paid</span><?}else{?><span class="bg-danger p-1 rounded text-white small"><i class="fa fa-times"></i> Pending</span><? } ?></div>
                            </div>
                        </div>
                        <?php if($getcurrplan[0]['payment_status']=="Received"){ ?>
                        <div class="form-group mb-2 col-sm-6 col-md-12 col-lg-5 col-xl-4 <?php echo $class12; ?>">
                            <div class="row">
                                <label class="col-5 col-sm-5 col-md-4 col-lg-5 control-label">Plan Duration</label>
                                <div class="col-7 col-sm-7 col-md-8 col-lg-7"><?php echo date("d-m-Y", strtotime($getcurrplan[0]['plan_from'])) ; ?> - <?php echo date("d-m-Y", strtotime($getcurrplan[0]['plan_to'])) ; ?></div>
                            </div>
                        </div>
                        <? } ?>
                        <div class="form-group mb-2 col-sm-6 col-md-12 col-lg-5 col-xl-4 <?php echo $class12; ?>">
                            <div class="row">
                                <label class="col-5 col-sm-5 col-md-4 col-lg-5 control-label">Plan Status</label>
                                <div class="col-7 col-sm-7 col-md-8 col-lg-7">
                                <? if($getcurrplan[0]['plan_status']=="Active") { ?>
                                <span class="bg-success p-1 rounded text-white small"><i class="fa fa-check"></i> Active</span>
                                <? } else if($getcurrplan[0]['plan_status']=="Expired") { ?>
                                <span class="bg-danger p-1 rounded text-white small"><i class="fa fa-times"></i> Expired</span>
                                <? } else { ?>
                                <span class="bg-danger p-1 rounded text-white small"><i class="fa fa-times"></i> Pending</span>
                                <? } ?>
                                </div>
                            </div>
                        </div>
                        <? } ?>
                    </div>
                </div>
                <div class="card-footer bg-white pb-2">
                    <?php if($getcurrplan[0]['payment_status'] !="Received"){ ?>
                    <button class="btn btn-secondary mb-2" id='markrec' onclick="markrecived('<? echo $vendor_id ?>','<?php echo $getvendor[0]['plan'] ?>')"> Mark Current Plan's  Payment recived </button>
                    <?php } ?>
                    <?php if($getvendor[0]['plan_status'] != "Expired" ){ ?>
                    <a href="planpayment.php?vendor=<?php echo base64_encode($vendor_id); ?>&type=<?php echo base64_encode('Renew'); ?>" class="btn btn-secondary mb-2"> Renew Plan</a><?php } ?>
                    <a href="planpayment.php?vendor=<?php echo base64_encode($vendor_id); ?>&type=<?php echo base64_encode('Upgrate - Immediate'); ?>" class="btn btn-secondary mb-2"> Upgrade (Immediately)</a>
                    <?php if($getvendor[0]['plan_status'] != "Expired" ){ ?>
                    <a href="planpayment.php?vendor=<?php echo base64_encode($vendor_id); ?>&type=<?php echo base64_encode('Upgrate - Normal'); ?>" class="btn btn-secondary mb-2">Upgrade (After Plan Expiry)</a>
                    <?php } ?>
                </div>
            </div>
            <div class="card history mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2"><div><h5 class="card-head-title">Payment History</h5></div></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered pay-history">
                            <thead><tr><th>#</th><th>Payment Date</th><th>Invoice ID</th><th>Payment Status</th><th>Plan Details</th></tr></thead>
                            <tbody> 
                                <?php for($i=0;$i<count($getpaymentdata);$i++){
                                $getplandata=selectQuery(VENDORPLANSELECTED,"*","invoice_id='".$getpaymentdata[$i]['plan_id']."'"); ?>
                                <tr>
                                    <td><?php echo $i+1; ?></td>
                                    <td><?php echo date("d-m-Y", strtotime($getpaymentdata[$i]['payment_date'])) ;  ; ?></td>
                                    <td><a href="paymentdetails.php?inv=<?php echo base64_encode($getpaymentdata[$i]['pay_id']); ?>&vendor=<?php echo base64_encode($vendor_id); ?>"><?php echo $getpaymentdata[$i]['plan_id']; ?></a><br>
                                    (<?php echo $getplandata[0]['plan_type']; ?>)</td>
                                    <td>
                                        <?php if($getpaymentdata[$i]['transaction_id']==""){ echo "Offline<br>"; }
                                        else{
                                            echo "Online <br> ";
                                            echo "Txn ID : ".$getpaymentdata[$i]['transaction_id']."<br>";
                                        } if($getpaymentdata[$i]['payment_status']=="Received"){
                                            echo "<span style='color:green'>".ucfirst($getpaymentdata[$i]['payment_status'])."</span>";
                                        } else{
                                            echo "<span style='color:red'>".ucfirst($getpaymentdata[$i]['payment_status'])."</span>";
                                        } ?>
                                    </td>
                                    <td>  
                                        <?php echo $getplandata[0]['plan'].""; ?><br>
                                        <?php ?>
                                        <?php
                                          if($getplandata[0]['plan_from'] != "") {
                                         echo date("d-m-Y", strtotime($getplandata[0]['plan_from']))  ; ?> - <?php echo date("d-m-Y", strtotime($getplandata[0]['plan_to']))  ;   } ?>
                                        <br>
                                        <span style="<?php if($getplandata[0]['plan_status']=="Expired"){ ?>color:red<? }else if($getplandata[0]['plan_status']=="Active"){?> color:green <?}?>"><?php echo $getplandata[0]['plan_status']; ?></span>
                                        <? ?>
                                    </td>
                                </tr>
                                <? } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php include '../footer.php'; ?>
    </div>
</div>
<script src="<?php echo SITEURL; ?>/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script>
$('.pay-history').DataTable();
function markrecived(vendor_id,plan_id){
    $(".markrec").attr("disabled", true);
    var info={vendor_id:vendor_id,plan_id:plan_id,action:"mark_recived"};
    $.ajax({
        type: "POST",
        url: "ajaxdata.php",
        data:info,
        success: function(response){
            if(response){
                $("#markrec").addClass("cc-display-none");
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Marked payment received successfully").delay(3000).fadeOut();
            }
        }
    });
}
</script>
</body>
</html>  