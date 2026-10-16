<?php include ("../includes/configuration.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo SITE_TITLE; ?> : Confirm Payment Details</title>
    <? include "commoncss.php"; ?>
</head>
<body onLoad="submitPayuForm()">
<?php
    $vendor = base64_decode($_REQUEST['vendor']);
    $plan = base64_decode($_REQUEST['plan']);
    $vendordata = selectQuery(VENDOR,"*" ,"dealer_id=".$vendor);
    $plandata = selectQuery(VENDORPLANSELECTED,"*","sel_id=".$plan);
    $planduration = selectQuery(VENDORPLAN,"*","plan_name= '".$plandata[0]['plan']."'");
   // $action = $PAYU_BASE_URL . '/_payment';
    $MERCHANT_KEY = MARCHANTKEY;
    $SALT = SALT;
    $PAYU_BASE_URL = PAYURL; 
    $action = "";
    $posted = array();
    if(!empty($_POST)){ foreach($_POST as $key => $value){ $posted[$key] = $value; } }
    $formError = 0;
    if(empty($posted['txnid'])){
        // Generate random transaction id
        $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
    } else { $txnid = $posted['txnid']; }
    $hash = '';
    // Hash Sequence
    $hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
    if(empty($posted['hash']) && sizeof($posted) > 0) {
        if(empty($posted['key']) || empty($posted['txnid']) || empty($posted['amount']) || empty($posted['firstname']) || empty($posted['email']) || empty($posted['phone']) || empty($posted['productinfo']) || empty($posted['surl']) || empty($posted['furl']) || empty($posted['service_provider'])){
            $formError = 1;
        } else{
            //$posted['productinfo'] = json_encode(json_decode('[{"name":"tutionfee","description":"","value":"500","isRequired":"false"},{"name":"developmentfee","description":"monthly tution fee","value":"1500","isRequired":"false"}]'));
            $hashVarsSeq = explode('|', $hashSequence);
            $hash_string = '';
            foreach($hashVarsSeq as $hash_var) {
                $hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
                $hash_string .= '|';
            }
            $hash_string .= $SALT;
            $hash = strtolower(hash('sha512', $hash_string));
            $action = $PAYU_BASE_URL . '/_payment';
        }
    } elseif(!empty($posted['hash'])){
    $hash = $posted['hash'];
    $action = $PAYU_BASE_URL . '/_payment';
} ?>
<div class="page-body-wrapper">
    <? include 'header.php'; ?>
    <? include 'sidebar.php'; ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Confirm Payment Details</h2></div></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row m-0">
                                <div class="col-5 col-sm-3 col-md-3 col-lg-4 col-xl-3 cc-col-pad-view pl-0 cc-font-weight-5">Plan</div>
                                <div class="col-7 col-sm-8 col-md-9 col-lg-6 col-xl-6 cc-col-pad-view"><?php echo $plandata[0]['plan'].""; ?></div>
                            </div>
                            <div class="row  m-0">
                                <div class="col-5 col-sm-3 col-md-3 col-lg-4 col-xl-3 cc-col-pad-view pl-0 cc-font-weight-5">Price</div>
                                <div class="col-7 col-sm-8 col-md-9 col-lg-6 col-xl-6 cc-col-pad-view"><i class="fa fa-inr"></i> <?php echo $plandata[0]['price']; ?></div>
                            </div>
                            <div class="row m-0">
                                <div class="col-5 col-sm-3 col-md-3 col-lg-4 col-xl-3 cc-col-pad-view pl-0 cc-font-weight-5">Validity</div>
                                <div class="col-7 col-sm-8 col-md-9 col-lg-6 col-xl-6 cc-col-pad-view"><?php echo $planduration[0]['plan_duration']; ?> Days</div>
                            </div>
                            <div class="totalSeat">
                                <div class="pay viewDetails list-view row m-0">
                                    <div class="col-5 col-sm-3 col-md-3 col-lg-4 col-xl-3 cc-col-pad-view cc-font-weigh-5 pl-0 cc-font-weight-5">Total Payable</div>
                                    <div class="col-7 col-sm-8 col-md-9 col-lg-6 col-xl-6 cc-col-pad-view">
                                    <?php if($plandata[0]['plan']!=""){ ?>
                                    <i class="fa fa-inr"></i> <?php echo $plandata[0]['price']; ?>
                                    <?php } else{ ?> <i class="fa fa-inr"></i> <?php echo "0"; ?> <? } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <form <?php if($action != "" ){ ?> action="<?php echo $action; ?>" <?php } ?> method="POST" name="payuForm" id="payuForm" class="margin0">
                        <input type="hidden" name="key" value="<?php echo $MERCHANT_KEY ?>" />
                        <input type="hidden" name="hash" value="<?php echo $hash ?>"/>
                        <input type="hidden" id="txnid" name="txnid" value="<?php echo $txnid ?>" />
                        <input type="hidden" name="amount" value="<?php echo $plandata[0]['price']; ?>"  />
                        <input type="hidden" name="firstname" id="firstname" value="<?php echo $vendordata[0]['dealer_name']; ?>" />
                        <input type="hidden" name="email" id="email" value="<?php echo $vendordata[0]['email']; ?>" />
                        <input type="hidden" name="phone" value="<?php echo $vendordata[0]['personalcontactno']; ?>" />
                        <input type="hidden" name="udf1" id="udf1" value="<?php echo $plandata[0]['invoice_id']; ?>" />
                        <input type="hidden" name="udf2" id="udf2" value="payu_paisa" />
                        <input type="hidden" name="productinfo" id="productinfo" value="Vendor plan" />
                        <input type="hidden" name="surl" value="<?php echo VENDORURL; ?>success.php" />
                        <input type="hidden" name="furl" value="<?php echo VENDORURL; ?>failure.php" />
                        <input type="hidden" name="service_provider" value="payu_paisa" />
                        <?php if($plandata[0]['price'] != 0 ){
                            if(EASE_ENABLE == 1){ ?>
                            <button type="button" onclick="validandsubmit4('<?= base64_encode($plandata[0]['invoice_id']); ?>','<?php echo $txnid ?>','<?=SITEURL ?>/easebuzz/view/initiate_payment.php')"  class="mp btn btn-primary"> <img src="<?=SITEURL; ?>/easebuzz/assets/images/eb-logo.svg" width="75"> PAY </button>
                            <?php } ?>
                            <?php if($plandata[0]['plan']!=""){
                                if(!$hash){
                                if(PAYU_ENABLE == 1){ ?>
                                    <input type="button" onclick="validandsubmit('<?php  echo base64_encode($plandata[0]['invoice_id']); ?>','<?php echo $txnid ?>')" value="Pay With payU money" class="btn btn-primary" />
                                    <?php }     
                                }
                            }
                        } else{ ?>
                            <button type="button" onclick="validandsubmit5('<?php echo base64_encode($plandata[0]['invoice_id']);  ?>')" class="btn btn-primary submit4">Submit</button>
                        <?php } ?>
                        <button type="button" onclick="goBack()" class="btn btn-danger">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
        <? include "footer.php"; ?>
    </div>
</div>
<script>
function validandsubmit4(plan,txn,action){
    $.ajax({
        type:"POST",
        url:"ajax/ajaxdata.php",
        data:{planid:plan,txnid:txn,action:"onlinepay"},
        success:function(response){
            if(response==0){
                alert("fail");
            } else{   
                $("#payuForm").attr("action",action);
                var payuForm = document.forms.payuForm;
                payuForm.submit();
            }
        }
    });
}
function validandsubmit(plan,txn){
    $.ajax({
        type:"POST",
        url:"ajax/ajaxdata.php",
        data:{planid:plan,txnid:txn,action:"onlinepay"},
        success:function(response){
            if(response==0){ alert("fail"); }
            else{    
                var payuForm = document.forms.payuForm;
                payuForm.submit();
            }
        }
    });
}
function validandsubmit5(plan,txn,action){
    sellid = "<?php echo  base64_encode($vendor); ?>"
    $.ajax({
        type:"POST",
        url:"ajax/ajaxdata.php",
        data:{planid:plan,txnid:txn,action:"offline_pay"},
        success:function(response){
            if(response==0){ alert("fail"); } else{ location.href="home.php?s="+sellid; }
        }
    });
}
var hash = '<?php echo $hash ?>';
function submitPayuForm() {
    if(hash == ''){ return; }
    var payuForm = document.forms.payuForm;
    payuForm.submit();
}
</script>
</body>
</html>