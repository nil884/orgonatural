<? include("../../includes/configuration.php");
$dealerid = base64_decode($_REQUEST['vendor']);
$payid = base64_decode($_REQUEST['inv']);
$getpaymentdata = selectQuery(VENDORPAYMENT,"*","pay_id=".$payid);
$getdealer = selectQuery(VENDOR,"*","dealer_id=".$dealerid);
$getcurrplan = selectQuery(VENDORPLANSELECTED,"*","invoice_id='".$getpaymentdata[0]['plan_id']."'"); ?>
<!DOCTYPE html> 
<html lang="en">
<head>
    <title><?php echo SITENAME; ?> : Payment Details</title>
    <? include "../commoncss.php"; ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/datepicker/bootstrap-datetimepicker.min.css"/>
   </head>
<body>
<div class="page-body-wrapper">
    <? include '../header.php'; ?>
    <? include '../sidebar.php'; ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2">
                    <div><h2 class="card-head-title">Payment Details - <?php echo $getpaymentdata[0]['plan_id']."  (".$getcurrplan[0]['plan_type'].")"; ?></h2></div>
                    <div class="btn-actions-pane-right">
                      <a href="<?php echo SITEURL ?>/print/vendor_invoice.php?vendor=<?php echo $_REQUEST['vendor'];?>&&inv= <?php echo $_REQUEST['inv'] ?>" class="btn btn-primary btn-sm"  target="_blank">Invoice PDF</a>
                        <a href="plandetails.php?dealer=<?php echo base64_encode($dealerid); ?>" class="btn btn-secondary btn-sm">Back</a></div>
                </div>
                <div class="card-body pb-2">
                    <div class="row">
                        <div class="col-sm-6 col-md-6 <?php echo $class12; ?>">
                            <div class="form-group row mb-2">
                                <label class="col-5 col-sm-12 col-md-12 col-lg-4 control-label cc-font-weight-5">Invoice ID</label>
                                <div class="col-7 col-sm-12 col-md-12 col-lg-8"><?php echo $getpaymentdata[0]['plan_id']; ?></div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group row mb-2">
                                <label class="col-5 col-sm-12 col-md-12 col-lg-4 control-label cc-font-weight-5">Admin GST No</label>
                                <div class="col-7 col-sm-12 col-md-12 col-lg-8"><?php echo $getconfigdetails[0]["gst_no"] ?></div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group row mb-2">
                                <label class="col-5 col-sm-12 col-md-12 col-lg-4 control-label cc-font-weight-5">Vendor GST No</label>
                                <div class="col-7 col-sm-12 col-md-12 col-lg-8"><?php echo $getdealer[0]["tanno"] ?></div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group row mb-2">
                                <label class="col-5 col-sm-12 col-md-12 col-lg-4 control-label cc-font-weight-5">Vendor Name</label>
                                <div class="col-7 col-sm-12 col-md-12 col-lg-8"><?php echo $getdealer[0]["dealer_name"] ?></div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group row mb-2">
                                <label class="col-5 col-sm-12 col-md-12 col-lg-4 control-label cc-font-weight-5">Vendor Company</label>
                                <div class="col-7 col-sm-12 col-md-12 col-lg-8"><?php echo $getdealer[0]["shopname"] ?></div>
                            </div>
                        </div>
                        <?php if($getcurrplan[0]['plan']!="") {?>
                        <div class="col-sm-6 col-md-6 <?php echo $class12; ?>">
                            <div class="form-group row mb-2">
                                <label class="col-5 col-sm-12 col-md-12 col-lg-4 control-label cc-font-weight-5">Amount</label>
                                <div class="col-7 col-sm-12 col-md-12 col-lg-8">
                                    <?php if($getcurrplan[0]['price']!="NULL"){?><span class="text-success"> <i class="fa fa-inr"></i> <?php echo $getcurrplan[0]['price'];?>/-&nbsp;</span><?}else{?><span class="text-danger">Pending</span><?}?>
                                </div>
                            </div>
                        </div>
                        <?php if($getpaymentdata[0]['payment_status']=="Received"||$getpaymentdata[0]['payment_status']=="Failed"){?>
                        <div class="col-sm-6 col-md-6 <?php echo $class12; ?>">
                            <div class="form-group row mb-2">
                                <label class="col-5 col-sm-12 col-md-12 col-lg-4 control-label cc-font-weight-5">Payment Date</label>
                                <div class="col-7 col-sm-12 col-md-12 col-lg-8"><?php echo $getpaymentdata[0]['payment_date']; ?></div>
                            </div>
                        </div>
                        <? } ?>
                        <div class="col-sm-6 col-md-6 <?php echo $class12; ?>">
                            <div class="form-group row mb-2">
                                <label class="col-5 col-sm-12 col-md-12 col-lg-4 control-label cc-font-weight-5">Payment Mode</label>
                                <div class="col-7 col-sm-12 col-md-12 col-lg-8"><?php if($getpaymentdata[0]['transaction_id']!=""){ echo "Online"; } else{ echo "Offline"; } ?></div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 <?php echo $class12; ?>">
                            <div class="form-group row mb-2">
                                <label class="col-5 col-sm-12 col-md-12 col-lg-4 control-label cc-font-weight-5">Payment Status</label>
                                <div class="col-7 col-sm-12 col-md-12 col-lg-8"><?php echo $getpaymentdata[0]['payment_status']; ?></div>
                            </div>
                        </div>
                        <?php if($getpaymentdata[0]['transaction_id']!="") { ?>
                        <div class="col-sm-6 col-md-6 <?php echo $class12; ?>">
                            <div class="form-group row mb-2">
                                <label class="col-5 col-sm-12 col-md-12 col-lg-4 control-label cc-font-weight-5">Txn ID</label>
                                <div class="col-7 col-sm-12 col-md-12 col-lg-8"><?php echo $getpaymentdata[0]['transaction_id']; ?></div>
                            </div>
                        </div> 
                        <? } else{ } ?>
                        <?php if($getpaymentdata[0]['payment_status']=="Received"){?> <? } } ?>
                    </div>
                </div>
            </div>
            <?php if($getpaymentdata[0]['payment_status']=="Received"){ ?>
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2">
                <div><h2 class="card-head-title">Plan Details</h2></div><div class="btn-actions-pane-right"></div></div>
                <div class="card-body pb-2">
                    <div class="row plandetaildata">
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group row mb-2">
                                <label class="col-5 col-sm-12 col-md-12 col-lg-4 control-label cc-font-weight-5">Plan Name</label>
                                <div class="col-7 col-sm-12 col-md-12 col-lg-8">
                                    <?php if($getcurrplan[0]['plan']!=""){ echo $getcurrplan[0]['plan'].""; }
                                    else{ echo "[Not Defined]"; } ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 <?php echo $class12; ?>">
                            <div class="form-group row mb-2">
                                <label class="col-5 col-sm-12 col-md-12 col-lg-4 control-label cc-font-weight-5">Request Type</label>
                                <div class="col-7 col-sm-12 col-md-12 col-lg-8"><?php echo $getcurrplan[0]['plan_type'];?></div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 <?php echo $class12; ?>">
                            <div class="form-group row mb-2">
                                <label class="col-5 col-sm-12 col-md-12 col-lg-4 control-label cc-font-weight-5">Plan Duration</label>
                                <div class="col-7 col-sm-12 col-md-12 col-lg-8"><?php echo $getcurrplan[0]['plan_from']; ?> - <?php echo $getcurrplan[0]['plan_to']; ?></div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 <?php echo $class12; ?>">
                            <div class="form-group row mb-2">
                                <label class="col-5 col-sm-12 col-md-12 col-lg-4 control-label cc-font-weight-5">Plan Status</label>
                                <div class="col-7 col-sm-12 col-md-12 col-lg-8">
                                    <? if($getcurrplan[0]['plan_status']=="Active"){ ?>
                                    <span class="badge badge-success"><i class="fa fa-check"></i>Active</span>
                                    <? } else if($getcurrplan[0]['plan_status']=="Expired"){ ?>
                                    <span class="badge badge-danger">Expired</span>
                                    <? } else{ echo $getcurrplan[0]['plan_status']; } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <? include "../footer.php"; ?>
    </div>
</div>  
</body>
</html>