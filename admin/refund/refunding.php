<? include("../../includes/configuration.php");
$itemid = base64_decode($_GET['item']); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo SITENAME; ?> : Refunds</title>
    <? include "../commoncss.php"; ?>
   <link rel="stylesheet" href="<?=SITEURL; ?>/css/datepicker/bootstrap-datetimepicker.min.css">
</head>
<body id="bodload">
<div class="page-body-wrapper" id="prod-list-col">
    <? include '../header.php'; ?>
    <? include '../sidebar.php';
    $getrefundable=selectQuery(ORDERSUB." as sub JOIN ".ORDER." as ord on sub.order_id=ord.id JOIN ".BUYER." as usr on ord.user_id=usr.u_id","*","sub.item_id=".$itemid);
    $getvendor=selectQuery(VENDOR,"nickname","dealer_id=".$getrefundable[0]['vendor']); ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="row">
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header sec-card-head justify-content-between align-items-center py-2"><div><h5 class="card-head-title">Refund</h5></div><div class="btn-actions-pane-right"><button onclick="goBack()" class="btn btn-secondary btn-sm">Back</button></div></div>
                        <div class="card-body border-bottom">
                            <div class="row">
                                <div class="col-md-12">
                                    <h6 class="mb-2">Returned Order Item Details</h6>
                                    <div class="row">
                                        <div class="col-sm-4 col-md-4 col-lg-3 py-2 cc-font-weight-5">Order ID</div>
                                        <div class="col-sm-8 col-md-8 col-lg-9 py-2"><?=$getrefundable[0]['order_id']; ?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4 col-md-4 col-lg-3 py-2 cc-font-weight-5">Item</div>
                                        <div class="col-sm-8 col-md-8 col-lg-9 py-2"><?=$getrefundable[0]['display_product_name']; ?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4 col-md-4 col-lg-3 py-2 cc-font-weight-5">Vendor</div>
                                        <div class="col-sm-8 col-md-8 col-lg-9 py-2"><?=$getvendor[0]['nickname']; ?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4 col-md-4 col-lg-3 py-2 cc-font-weight-5">Paid Amount</div>
                                        <div class="col-sm-8 col-md-8 col-lg-9 py-2"><?=$getrefundable[0]['total_payable']; ?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4 col-md-4 col-lg-3 py-2 cc-font-weight-5">Refundable Amount</div>
                                        <div class="col-sm-8 col-md-8 col-lg-9 py-2"><?=$getrefundable[0]['refundable_amount']; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h6 class="mb-2">Buyer Details</h6>
                            <div class="row">
                                <div class="col-sm-4 col-md-4 col-lg-3 py-2 cc-font-weight-5">Name</div>
                                <div class="col-sm-8 col-md-8 col-lg-9 py-2"><?=$getrefundable[0]['u_fname']." ".$getrefundable[0]['u_lname']; ?></div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4 col-md-4 col-lg-3 py-2 cc-font-weight-5">Mobile No</div>
                                <div class="col-sm-8 col-md-8 col-lg-9 py-2"><?=$getrefundable[0]['u_mobile']; ?></div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4 col-md-4 col-lg-3 py-2 cc-font-weight-5">Email</div>
                                <div class="col-sm-8 col-md-8 col-lg-9 py-2"><?=$getrefundable[0]['u_email']; ?></div>
                            </div>
                            <h6 class="my-3 cc-font-weight-6">Banking Details</h6>
                            <div class="row">
                                <div class="col-sm-4 col-md-4 col-lg-3 py-2 cc-font-weight-5">Bank Name</div>
                                <div class="col-sm-8 col-md-8 col-lg-9 py-2"><?=$getrefundable[0]['bank_name']; ?></div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4 col-md-4 col-lg-3 py-2 cc-font-weight-5">Account Name</div>
                                <div class="col-sm-8 col-md-8 col-lg-9 py-2"><?=$getrefundable[0]['account_name']; ?></div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4 col-md-4 col-lg-3 py-2 cc-font-weight-5">Account No</div>
                                <div class="col-sm-8 col-md-8 col-lg-9 py-2"><?=$getrefundable[0]['account_number']; ?></div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4 col-md-4 col-lg-3 py-2 cc-font-weight-5">IFSC </div>
                                <div class="col-sm-8 col-md-8 col-lg-9 py-2"><?=$getrefundable[0]['ifsc_code']; ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card mb-0">
                        <div class="card-header sec-card-head justify-content-between align-items-center"><h5 class="card-head-title">Refund Payment</h5></div>
                       
                            <form>
                                 <div class="card-body">
                                <div class="form-group">
                                    <label>Payment Made</label>
                                    
                                    <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="pay_made_yes" name="Payement_made" value="1" <?php if($getrefundable[0]['refund_status']== 1 ) { echo "checked"; }  ?> required   onchange="changeminval(this)" >
                                            <label class="custom-control-label" for="pay_made_yes">Yes</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="pay_made_no"  name="Payement_made" value="0"  <?php if($getrefundable[0]['refund_status']== 0 ) { echo "checked"; }  ?> required onchange="changeminval(this)">
                                            <label class="custom-control-label" for="pay_made_no">No</label>
                                        </div>     
                                </div>
                                <div class="form-group">
                                    <label>Payment Ref ID</label>
                                    <input type="text" name="ref_id" id="ref_id" class="form-control" value="<?=$getrefundable[0]['refund_id']; ?>" placeholder="Payment Refrence Id" maxlength="20">
                                </div>
                                <div class="form-group">
                                    <label>Payment Date</label>
                                   <input type="text" placeholder="Payment Date" name="pay_date" class="form-control" autocomplete="off" id="pay_date"  value="<?=$getrefundable[0]['refund_date']; ?>">
                                </div>
                                </div>
                                <div class="card-footer text-right py-2">
                                    <button type="button" class="btn btn-primary" onclick="processpay()">Process Refund</button>
                                </div>
                            </form>
                    </div>
                </div>
            </div>
        </div>
        <? include "../footer.php"; ?>
    </div>
</div>

<script src="<?php echo SITEURL; ?>/js/datepicker/moment.js"></script>
<script src="<?php echo SITEURL; ?>/js/datepicker/bootstrap-datetimepicker.min.js"></script>

<script>
    
    $('#pay_date').datetimepicker({
    ignoreReadonly: true,
   maxDate: moment(),
    format: 'DD-MM-YYYY HH:mm',
    disabledTimeIntervals:false
}); 

if($("input[name='Payement_made']:checked").val() == 0) { $("#ref_id , #pay_date").prop('disabled', true); }
function processpay(){
    payment_made=$("input[name='Payement_made']:checked").val();
    ref_id = $("#ref_id").val();
    pay_date = $("#pay_date").val();
    itemid  = '<?php echo $itemid ?>';
    
        
    if(payment_made == ""){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please select payment mode").delay(3000).fadeOut();
    }
    else if(payment_made == 1 && ref_id == ""){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter refrence ID").delay(3000).fadeOut();
    }
    else if(payment_made == 1 && pay_date == ""){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please select payment date").delay(3000).fadeOut();
    }
    else{
        info = {itemid:itemid,payment_made:payment_made,ref_id:ref_id,pay_date:pay_date,action:"manualRefund"}
        $.ajax({
            type:"POST",
            url:"<?=SITEURL;?>/ajax/order_ajax.php",
            data:info,
            success:function(response){
                response=response.trim();
                if(response== "1"){
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Details updated successfully").delay(3000).fadeOut();
                }
                else if(response=="0"){
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error.. Try again later").delay(3000).fadeOut();
                }
            }
        }); 
    }
}
function changeminval(minval){
    var ismin = $(minval).val();
    if(ismin==0){ $("#ref_id , #pay_date").val("").prop('disabled', true); }
    else { $("#ref_id , #pay_date").prop('disabled', false); }
}
</script>
</body>
</html>