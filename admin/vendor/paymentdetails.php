<? include("../../includes/configuration.php");
    $dealerid = base64_decode($_REQUEST['vendor']);
    $payid = base64_decode($_REQUEST['inv']);
    $getpaymentdata = selectQuery(VENDORPAYMENT,"*","pay_id=".$payid);
    $getdealer = selectQuery(VENDOR,"*","dealer_id=".$dealerid);
    $getcurrplan = selectQuery(VENDORPLANSELECTED,"*","invoice_id='".$getpaymentdata[0]['plan_id']."'");
?>
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
                     
                    <div class="btn-actions-pane-right"><a href="<?php echo SITEURL ?>/print/vendor_invoice.php?vendor=<?php echo $_REQUEST['vendor'];?>&&inv=<?php echo $_REQUEST['inv'] ?>" class="btn btn-primary btn-sm"  target="_blank">Invoice PDF</a> <button onclick="goBack()" class="btn btn-secondary btn-sm">Back</button></div>
                </div>
                <div class="card-body pb-2">
                    <div class="row">
                        <div class="col-sm-6 col-md-6 <?php echo $class12; ?>">
                            <div class="form-group row mb-2">
                            <label class="col-5 col-sm-12 col-md-12 col-lg-4 control-label cc-font-weight-5">Invoice ID</label>
                            <div class="col-7 col-sm-12 col-md-12 col-lg-8"><?php echo $getpaymentdata[0]['plan_id']; ?></div>
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
            <?php //if($getpaymentdata[0]['payment_status']=="Received"){ ?>
                <div class="card mb-0">
                    <div class="card-header sec-card-head justify-content-between align-items-center py-2">
                    <div><h2 class="card-head-title">Plan Details</h2></div><div class="btn-actions-pane-right"> <!--<button class="btn btn-secondary updateplan btn-sm">Edit</button>--></div>  </div>
                    <div class="card-body pb-2">
                        <div class="row plandetaildata">
                            <div class="col-sm-6 col-md-6">
                                <div class="form-group row mb-2">
                                    <label class="col-5 col-sm-12 col-md-12 col-lg-4 control-label cc-font-weight-5">Plan Name</label>
                                    <div class="col-7 col-sm-12 col-md-12 col-lg-8">
                                        <?php if($getcurrplan[0]['plan']!="") {
                                            
                                                echo $getcurrplan[0]['plan']."";
                                            }
                                        else{
                                            echo "[Not Defined]";
                                        } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 <?php echo $class12; ?>">
                                <div class="form-group row mb-2">
                                    <label class="col-5 col-sm-12 col-md-12 col-lg-4 control-label cc-font-weight-5">Request Type</label>
                                    <div class="col-7 col-sm-12 col-md-12 col-lg-8">
                                        <?php echo $getcurrplan[0]['plan_type'];?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 <?php echo $class12; ?>">
                                <div class="form-group row mb-2">
                                    <label class="col-5 col-sm-12 col-md-12 col-lg-4 control-label cc-font-weight-5">Plan Duration</label>
                                    <div class="col-7 col-sm-12 col-md-12 col-lg-8">
                                        <?php echo $getcurrplan[0]['plan_from']; ?> - <?php echo $getcurrplan[0]['plan_to']; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 <?php echo $class12; ?>">
                                <div class="form-group row mb-2">
                                    <label class="col-5 col-sm-12 col-md-12 col-lg-4 control-label cc-font-weight-5">Plan Status</label>
                                    <div class="col-7 col-sm-12 col-md-12 col-lg-8">
                                        <? if($getcurrplan[0]['plan_status']=="Active") { ?>
                                        <span class="badge badge-success"><i class="fa fa-check"></i>Active</span>
                                        <? } else if($getcurrplan[0]['plan_status']=="Expired") { ?>
                                        <span class="badge badge-danger">Expired</span>
                                        <? } else {
                                            echo $getcurrplan[0]['plan_status'];
                                        }
?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="plandetailform row cc-display-none">
                            <div class="msg"></div>
                            <div class="col-sm-12 col-md-12 col-lg-8 col-xl-6">
                                <div class="form-group row">
                                    <label class="col-12 col-sm-4 col-md-12 col-lg-4 control-label cc-font-weight-5">Selected Plan</label>
                                    <div class="col-12 col-sm-8 col-md-12 col-lg-8">
                                        <input type="hidden" name="invoiceid" id="invoiceid" class="form-control invoiceid text-capitalize" value="<?php echo $getpaymentdata[0]['plan_id']; ?>" />
                                        <input type="hidden" name="payid" id="payid" class="form-control payid text-capitalize" value="<?php echo $getpaymentdata[0]['pay_id']; ?>" />
                                        <?php $planprice=selectQuery(VENDORPLAN,"*");
                                        for($p=0;$p<count($planprice);$p++){ ?>
                                        <div class="custom-control custom-radio custom-control-inline"><input type="radio" name="plan" id="plan-sel<?=$p;?>" class="plan custom-control-input" <? if($planprice[$p]['plan_name']==$getcurrplan[0]['plan']){echo "checked";} ?> value="<?php echo $planprice[$p]['plan_name']; ?>" />
                                        <label class="custom-control-label" for="plan-sel<?=$p;?>"><?php echo $planprice[$p]['plan_name']." (Rs. ".$planprice[$p]['price'].")"; ?></label>
                                        </div>
                                        <? } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-8 col-xl-6">
                                <div class="form-group row">
                                    <label class="col-12 col-sm-4 col-md-12 col-lg-4 control-label cc-font-weight-5">Payment Status</label>
                                    <div class="col-12 col-sm-8 col-md-12 col-lg-8">
                                        <div class="custom-control custom-radio custom-control-inline"><input type="radio" name="payment" id="pay-stat-rec" class="payment custom-control-input" <?php if($getcurrplan[0]['payment_status']=="Received"){echo "disabled";} ?> <?php if($getcurrplan[0]['payment_status']=="Received"){echo "checked";} ?> value="Received" /><label class="custom-control-label" for="pay-stat-rec">Received</label></div>
                                        <div class="custom-control custom-radio custom-control-inline"><input type="radio" name="payment" id="pay-stat-pend" class="payment custom-control-input" <?php if($getcurrplan[0]['payment_status']=="Received"){echo "disabled";} ?> <?php if($getcurrplan[0]['payment_status']=="Pending"){echo "checked";} ?> value="Pending" /><label class="custom-control-label" for="pay-stat-pend">Pending</label></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-lg-8 col-xl-6">
                                <div class="form-group row">
                                    <label class="col-12 col-sm-4 col-md-12 col-lg-4 control-label cc-font-weight-5">Plan Start On</label>
                                    <div class="col-12 col-sm-8 col-md-12 col-lg-8">
                                        <div class="input-group">
                                            <input class="form-control" name='date1' id="date1" size="16" type="text" value="<?php echo $getcurrplan[0]['plan_from']; ?>" readonly >
                                            <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span></div>
                                        </div>
                                        <span id="startdate"></span>
                                        <input type="hidden" id="dtp_input1" value="<?php echo $getcurrplan[0]['plan_from']; ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-lg-8 col-xl-6">
                                <div class="form-group row">
                                    <label class="col-12 col-sm-4 col-md-12 col-lg-4 control-label cc-font-weight-5">Plan Ends On</label>
                                    <div class="col-12 col-sm-8 col-md-12 col-lg-8">
                                        <div class="input-group date form_date">
                                            <input class="form-control" name='date2' id="date2" onchange="getdate()" size="16" type="text" value="<?php echo $getcurrplan[0]['plan_to']; ?>" readonly>
                                            <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span></div>
                                        </div>
                                        <span id="enddate"></span>
                                        <input type="hidden" id="dtp_input2" value="<?php echo $getcurrplan[0]['plan_to']; ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group"><div class="col-md-8 col-sm-12"><button class="btn btn-primary updatebtn" onclick="updateplan()">Update Plan</button></div></div>
                        </div>  -->
                         <?php // } ?> 
                    </div>
                </div>
            </div>
        <? include "../footer.php"; ?>
    </div>
</div>  
<script src="<?php echo SITEURL; ?>/js/datepicker/moment.js"></script> 
<script src="<?php echo SITEURL; ?>/js/datepicker/bootstrap-datetimepicker.min.js"></script>
<script>
$('#date1').datetimepicker({ ignoreReadonly: true, maxDate:moment(), format: 'YYYY-MM-DD', disabledTimeIntervals:false, });
$('#date2').datetimepicker({ ignoreReadonly: true, minDate:moment(), format: 'YYYY-MM-DD', disabledTimeIntervals:false, });
var currentdate = new Date();
var datetime = currentdate.getFullYear()+ "/"
+(currentdate.getMonth()+1) + "/"
+currentdate.getDate();
$(".actiontype").prop("checked",false);
function updateplan(){
    var plan = $(".plan:checked").val();
    var payment = $(".payment:checked").val();
    var start = $("#date1").val();
    var end = $("#date2").val();
    var invoiceid = $("#invoiceid").val();
    var payid = $("#payid").val();
    var form_data = {payid:payid,invoiceid:invoiceid,plan:plan,payment:payment,start:start,end:end,action:"update_invoice"}
    $.ajax({
        type: "POST",
        url: "ajaxdata.php",
        data: form_data,
        success: function(response){
            if(response == 0){ }
            if(response == 1){
                $('.msg').fadeIn().addClass("alert alert-success").html("Details updated successfully").delay(2000).fadeOut();
                setTimeout(function(){ location.reload(); }, 1000);
            }
        }
    });
}
$(document).ready(function(){
    $(".updateplan").click(function(){
        $(".plandetaildata").toggle(); $(".plandetailform").toggle();
    });
});
</script>      
</body>
</html>