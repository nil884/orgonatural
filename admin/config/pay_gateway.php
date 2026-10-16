<?php include("../../includes/configuration.php"); ?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Payment Gateway</title>
    <?php include('../commoncss.php') ?>
</head>
<body>
<div class="page-body-wrapper">
    <?php include('../header.php') ?>
    <?php include('../sidebar.php') ?>
    <div class="main-panel">
        <div class="dashbody">
             <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2">
                    <div><h5 class="card-head-title">Razorpay Details</h5></div>
                    <div class="btn-actions-pane-right"><a href="https://dashboard.razorpay.com/#/access/signup?coupon_code=SURUN" target="_blank" class="btn btn-primary btn-sm">Create Razorpay Account</a> <label class="switch btn btn-primary"><input type="checkbox" class="razorpay-tog razorpay_enable_disable custom-control-input" id="razorpay_enable_disable"  name="razorpay-tog" data-toggle="toggle" data-on="Enabled" data-off="Disabled" data-width="100" data-height="30" <? if($getconfigdetails[0]['razorpay_enable'] == "1"){ echo "checked"; } else{ echo ""; } ?>><span class="slider round"><span class="on">Active</span><span class="off">Deactive</span></span></label></div>
                </div>
                <div class="card-body">
                    <div class="razorpay-det <? if($getconfigdetails[0]['razorpay_enable'] == "0"){ echo "cc-display-none"; } ?>">
                    <form>
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-xl-7">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-md-3 col-form-label pt-0 cc-mandatary-field">Environment</label>
                                    <div class="col-md-9 col-sm-9 col-xs-12 razorpaypayradio">
                                        <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" id="razorpayenvtest" name="razorpay_env" value="test" <? if($getconfigdetails[0]['razorpay_environment'] == "test"){ echo "checked"; } ?>><label class="custom-control-label" for="razorpayenvtest">Test</label></div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" id="razorpayenvpro" name="razorpay_env" value="prod" <? if($getconfigdetails[0]['razorpay_environment'] == "prod"){ echo "checked"; } ?>> <label class="custom-control-label" for="razorpayenvpro">Production</label></div>
                                    </div>
                                </div>
                                <div class="rezorpay-det razorpay_livediv <? if($getconfigdetails[0]['razorpay_environment'] == "test"){ echo "cc-display-none"; } ?> ">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-md-3 col-form-label cc-mandatary-field">API Key</label><div class="col-md-9 col-sm-9 col-xs-12"><input type="text" name="insta_api_Key" id="razorpay_api_Key" class="form-control" placeholder="Enter API Key" maxlength="50" value="<? echo $getconfigdetails[0]['razorpay_liveApiKey']; ?>" /></div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-md-3 col-form-label cc-mandatary-field">API Secrete</label><div class="col-md-9 col-sm-9 col-xs-12"><input type="text" name="insta_auth_token" id="razorpay_auth_token" class="form-control" placeholder="Enter API Secrete" maxlength="50" value="<? echo $getconfigdetails[0]['razorpay_liveSecrete']; ?>" /></div>
                                    </div>
                                </div>
                                <div class="rezorpay-det razorpay_testdiv <? if($getconfigdetails[0]['razorpay_environment'] == "prod"){ echo "cc-display-none"; } ?>">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-md-3 col-form-label cc-mandatary-field">API Key</label><div class="col-md-9 col-sm-9 col-xs-12"><input type="text" name="test_insta_api_Key" id="test_razorpay_api_Key" class="form-control" placeholder="Enter API Key" maxlength="50" value="<? echo $getconfigdetails[0]['razorpay_testApiKey']; ?>" disabled/></div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-md-3 col-form-label cc-mandatary-field">API Secrete</label><div class="col-md-9 col-sm-9 col-xs-12"><input type="text" name="test_insta_auth_token" id="test_razorpay_auth_token" class="form-control" placeholder="Enter API Secrete" maxlength="50" value="<? echo $getconfigdetails[0]['razorpay_testSecrete']; ?>" disabled/></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-12 col-xl-5">
                                <div class="alert alert-info p-0 cc-fs-13 razorpaysampledetails cc-display-none">
                                    <div class="card-body">
                                        <h6 class="cc-font-weight-5">Razorpay Test Card Details</h6>
                                        <table><tr><td class="pr-3 pb-1">Card Number</td><td class="pb-1">4111 1111 1111 1111</td></tr><tr><td class="pr-3 pb-1">Card Validity</td><td class="pb-1">8/21 (OR Any Future MM/YY)</td></tr><tr><td class="pr-3">Card CVV</td><td>111</td></tr></table>
                                    </div>
                                    <div class="card-footer text-muted py-2">If above details are not working, then please take latest details from your Razorpay Panel.</div>
                                </div>
                            </div>
                        </div>
                        <div class="row border-top pt-2"><div class="col-12 text-right"><button type="button" name="create" id="submit4" class="btn btn-primary">Save</button></div></div>
                    </form>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2">
                    <div><h2 class="card-head-title">Payu Money Details</h2></div>
                    <div class="btn-actions-pane-right">
                        <a href="https://onboarding.payumoney.com/app/account?partner_name=Surun&partner_source=Affiliate+Links&partner_uuid=5bd4-ce02-c5f34709-a014-8a9efaf909e3&source=Partner" target="_blank" class="btn btn-primary btn-sm">Create PayU Account</a>
                        <label class="switch btn btn-primary"><input type="checkbox" class="pay-mon-tog payu_enable_disble" id="payu_enable_disble" name="pay-mon-tog" data-toggle="toggle" data-on="Enabled" data-off="Disabled" data-width="100" data-height="30" <? if($getconfigdetails[0]['payu_enable'] == "1"){ echo "checked"; } else{ echo ""; }?>><span class="slider round"><span class="on">Active</span><span class="off">Deactive</span></span></label>
                    </div>
                </div>                
                <form>
                    <div class="card-body pb-2">
                        <div class="payu-details <? if($getconfigdetails[0]['payu_enable'] == "0"){ echo "cc-display-none"; } ?>">
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-xl-7">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-md-3 col-form-label pt-0 cc-mandatary-field">Environment</label>
                                        <div class="col-md-8 col-sm-9 col-xs-12 payuhandradio">
                                            <div class="custom-control custom-radio custom-control-inline"><input type="radio" class="custom-control-input" id="payuenvtest" name="pay_url" value="https://test.payu.in" <? if($getconfigdetails[0]['payu_url'] == "https://test.payu.in"){ echo "checked"; } ?>><label class="custom-control-label" for="payuenvtest">Test</label></div>
                                            <div class="custom-control custom-radio custom-control-inline"><input type="radio" class="custom-control-input" id="payuenvpro" name="pay_url" value="https://secure.payu.in" <? if($getconfigdetails[0]['payu_url'] == "https://secure.payu.in"){ echo "checked"; } ?>><label class="custom-control-label" for="payuenvpro">Production</label></div>
                                        </div>
                                    </div>
                                    <div class="payu_livediv <? if($getconfigdetails[0]['payu_url'] == "https://test.payu.in"){ echo "cc-display-none"; } ?>"> 
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-md-3 col-form-label cc-mandatary-field">Merchant Key</label>
                                            <div class="col-md-9 col-sm-9 col-xs-12"><input type="text" name="Merchant_Key" id="Merchant_Key" class="form-control" placeholder="Enter Merchant Key" maxlength="50" value="<? echo $getconfigdetails[0]['live_payu_merchant_key']; ?>" /></div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-md-3 col-form-label cc-mandatary-field">Salt Key</label>
                                            <div class="col-md-9 col-sm-9 col-xs-12"><input type="text" name="salt_key" id="salt_key" class="form-control" placeholder="Enter Salt Key" maxlength="50" value="<?php echo $getconfigdetails[0]['live_payu_salt_key']; ?>" /></div>
                                        </div>
                                    </div> 
                                    <div class="payu_testdiv <? if($getconfigdetails[0]['payu_url'] == "https://secure.payu.in"){ echo "cc-display-none"; } ?>"> 
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-md-3 col-form-label cc-mandatary-field">Merchant Key</label>
                                            <div class="col-md-9 col-sm-9 col-xs-12"><input type="text" name="test_Merchant_Key" id="test_Merchant_Key" class="form-control" placeholder="Enter Merchant Key" maxlength="50" value="<? echo $getconfigdetails[0]['test_payu_merchant_key']; ?>" disabled/></div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-md-3 col-form-label cc-mandatary-field">Salt Key</label>
                                            <div class="col-md-9 col-sm-9 col-xs-12"><input type="text" name="test_salt_key" id="test_salt_key" class="form-control" placeholder="Enter Salt Key" maxlength="50" value="<?php echo $getconfigdetails[0]['test_payu_salt_key']; ?>" disabled/></div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-4 row">
                                        <label class="col-sm-3 col-md-3 col-form-label">Auth Header</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12"><div class="input-group"><input type="text" name="auth_header" id="auth_header" class="form-control" placeholder="Enter Auth Header" maxlength="200" value="<?php echo $getconfigdetails[0]['payu_auth_header']; ?>" /><div class="input-group-append"><span class="input-group-text bg-white cc-cursor-pointer" data-toggle="modal" data-target="#payumodal"><i class="fa fa-question-circle"></i></span></div></div></div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-12 col-xl-5">
                                    <div class="alert alert-info p-0 cc-fs-13 paysampledetails cc-display-none">
                                        <div class="card-body">
                                        <h6 class="cc-font-weight-5">PayU Test Credit Card Details</h6>
                                        <table><tr><td class="pr-3 pb-1">Card Number</td><td class="pb-1">5123 4567 8901 2346</td></tr><tr><td class="pr-3 pb-1">Card Validity</td><td class="pb-1">05/21 (OR Any Future MM/YY)</td></tr><tr><td class="pr-3">Card CVV</td><td>123</td></tr></table>
                                        </div>
                                        <div class="card-footer text-muted py-2">If above details are not working, then please take latest details from your PayU Panel.</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row border-top pt-2"><div class="col-12 text-right"><button type="button" name="create" id="submit" class="btn btn-primary">Save</button></div></div>
                        </div>
                    </div>                    
                </form>
            </div>
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2">
                    <div><h5 class="card-head-title">Easebuzz Details</h5></div>
                    <div class="btn-actions-pane-right"><a href="https://easebuzz.in/merchant/signup/sarveshFQJ" target="_blank" class="btn btn-primary btn-sm">Create Easebuzz Account</a> <label class="switch btn btn-primary"><input type="checkbox" class="ease-buz-tog easebuzz_enable_disable custom-control-input" id="easebuzz_enable_disable"  name="ease-buz-tog" data-toggle="toggle" data-on="Enabled" data-off="Disabled" data-width="100" data-height="30" <? if($getconfigdetails[0]['ease_enable'] == "1"){ echo "checked"; } else{ echo ""; } ?>><span class="slider round"><span class="on">Active</span><span class="off">Deactive</span></span></label></div>
                </div>
                <div class="card-body pb-2">
                    <div class="ease-buz-det <? if($getconfigdetails[0]['ease_enable'] == "0"){ echo "cc-display-none"; } ?> ">
                    <form>
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-xl-7">
                            <div class="form-group row">
                                    <label class="col-sm-3 col-md-3 col-form-label pt-0 cc-mandatary-field">Easebuzz ID</label>
                                    <div class="col-md-9 col-sm-9 col-xs-12 ">
                                        <input type="text" class="form-control" maxlength="20" placeholder="Easebuzz ID" id="ease_id">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-md-3 col-form-label pt-0 cc-mandatary-field">Environment</label>
                                    <div class="col-md-9 col-sm-9 col-xs-12 easebuzzpayradio">
                                        <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" id="easebuzenvtest" name="ease_env" value="test" <? if($getconfigdetails[0]['ease_environment'] == "test"){ echo "checked"; } ?>><label class="custom-control-label" for="easebuzenvtest">Test</label></div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" id="easebuzenvpro" name="ease_env" value="prod" <? if($getconfigdetails[0]['ease_environment'] == "prod"){ echo "checked"; } ?>> <label class="custom-control-label" for="easebuzenvpro">Production</label></div>
                                    </div>
                                </div>
                                <div class="ease_livediv <? if($getconfigdetails[0]['ease_environment'] == "test"){ echo "cc-display-none"; } ?> "> 
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-md-3 col-form-label cc-mandatary-field">Merchant Key</label><div class="col-md-9 col-sm-9 col-xs-12"><input type="text" name="ease_Merchant_Key" id="ease_Merchant_Key" class="form-control" placeholder="Enter Merchant Key" maxlength="50" value="<? echo $getconfigdetails[0]['live_ease_merchant_key']; ?>" /></div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-md-3 col-form-label cc-mandatary-field">Salt Key</label><div class="col-md-9 col-sm-9 col-xs-12"><input type="text" name="ease_salt_key" id="ease_salt_key" class="form-control" placeholder="Enter Salt Key" maxlength="50" value="<? echo $getconfigdetails[0]['live_ease_salt_key']; ?>" /></div>
                                    </div>
                                </div>
                                <div class="ease_testdiv <? if($getconfigdetails[0]['ease_environment'] == "prod"){ echo "cc-display-none"; } ?>"> 
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-md-3 col-form-label cc-mandatary-field">Merchant Key</label><div class="col-md-9 col-sm-9 col-xs-12"><input type="text" name="test_ease_Merchant_Key" id="test_ease_Merchant_Key" class="form-control" placeholder="Enter Merchant Key" maxlength="50" value="<? echo $getconfigdetails[0]['test_ease_merchant_key']; ?>" disabled/></div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-md-3 col-form-label cc-mandatary-field">Salt Key</label><div class="col-md-9 col-sm-9 col-xs-12"><input type="text" name="test_ease_salt_key" id="test_ease_salt_key" class="form-control" placeholder="Enter Salt Key" maxlength="50" value="<? echo $getconfigdetails[0]['test_ease_salt_key']; ?>" disabled/></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-12 col-xl-5">
                                <div class="alert alert-info p-0 cc-fs-13 easebuzzsampledetails cc-display-none">
                                    <div class="card-body">
                                        <h6 class="cc-font-weight-5">Easebuzz Test Credit Card Details</h6>
                                        <table><tr><td class="pr-3 pb-1">Card Number</td><td class="pb-1">5123 4567 8901 2346</td></tr><tr><td class="pr-3 pb-1">Card Validity</td><td class="pb-1">7/21 (OR Any Future MM/YY)</td></tr><tr><td class="pr-3">Card CVV</td><td>123</td></tr></table>
                                    </div>
                                    <div class="card-footer text-muted py-2">If above details are not working, then please take latest details from your Easebuzz Panel.</div>
                                </div>
                            </div>
                        </div>
                        <div class="row border-top pt-2"><div class="col-12 text-right"><button type="button" name="create" id="submit2" class="btn btn-primary">Save</button></div></div>
                    </form>
                    </div>
                </div>
            </div>
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2">
                    <div><h5 class="card-head-title">Instamojo Details</h5></div>
                    <div class="btn-actions-pane-right"><a href="https://imjo.in/kxDrCP" target="_blank" class="btn btn-primary btn-sm">Create Instamojo Account</a> <label class="switch btn btn-primary"><input type="checkbox" class="instamojo-tog instamojo_enable_disable custom-control-input" id="instamojo_enable_disable"  name="instamojo-tog" data-toggle="toggle" data-on="Enabled" data-off="Disabled" data-width="100" data-height="30" <? if($getconfigdetails[0]['instamojo_enable'] == "1"){ echo "checked"; } else{ echo ""; } ?>><span class="slider round"><span class="on">Active</span><span class="off">Deactive</span></span></label></div>
                </div>
                <div class="card-body pb-2">
                    <div class="instamojo-det <? if($getconfigdetails[0]['instamojo_enable'] == "0"){ echo "cc-display-none"; } ?> ">
                    <form>
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-xl-7">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-md-3 col-form-label pt-0 cc-mandatary-field">Environment</label>
                                    <div class="col-md-9 col-sm-9 col-xs-12 instamojopayradio">
                                        <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" id="instamojoenvtest" name="instamojo_env" value="test" <? if($getconfigdetails[0]['instamojo_environment'] == "test"){ echo "checked"; } ?>><label class="custom-control-label" for="instamojoenvtest">Test</label></div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" id="instamojoenvpro" name="instamojo_env" value="prod" <? if($getconfigdetails[0]['instamojo_environment'] == "prod"){ echo "checked"; } ?>> <label class="custom-control-label" for="instamojoenvpro">Production</label></div>
                                    </div>
                                </div>
                                <div class="instamojo_livediv <? if($getconfigdetails[0]['instamojo_environment'] == "test"){ echo "cc-display-none"; } ?> ">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-md-3 col-form-label cc-mandatary-field">API Key</label><div class="col-md-9 col-sm-9 col-xs-12"><input type="text" name="insta_api_Key" id="insta_api_Key" class="form-control" placeholder="Enter API Key" maxlength="50" value="<? echo $getconfigdetails[0]['instamojo_liveApiKey']; ?>" /></div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-md-3 col-form-label cc-mandatary-field">Auth Token</label><div class="col-md-9 col-sm-9 col-xs-12"><input type="text" name="insta_auth_token" id="insta_auth_token" class="form-control" placeholder="Enter Auth Token" maxlength="50" value="<? echo $getconfigdetails[0]['instamojo_liveAuthToken']; ?>" /></div>
                                    </div>
                                </div>
                                <div class="instamojo_testdiv <? if($getconfigdetails[0]['instamojo_environment'] == "prod"){ echo "cc-display-none"; } ?>">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-md-3 col-form-label cc-mandatary-field">API Key</label><div class="col-md-9 col-sm-9 col-xs-12"><input type="text" name="test_insta_api_Key" id="test_insta_api_Key" class="form-control" placeholder="Enter API Key" maxlength="50" value="<? echo $getconfigdetails[0]['instamojo_testApiKey']; ?>" disabled/></div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-md-3 col-form-label cc-mandatary-field">Auth Token</label><div class="col-md-9 col-sm-9 col-xs-12"><input type="text" name="test_insta_auth_token" id="test_insta_auth_token" class="form-control" placeholder="Enter Auth Token" maxlength="50" value="<? echo $getconfigdetails[0]['instamojo_testAuthToken']; ?>" disabled/></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-12 col-xl-5">
                                <div class="alert alert-info p-0 cc-fs-13 instamojosampledetails cc-display-none">
                                    <div class="card-body">
                                        <h6 class="cc-font-weight-5">Instamojo Test Card Details</h6>
                                        <table><tr><td class="pr-3 pb-1">Card Number</td><td class="pb-1">4242 4242 4242 4242</td></tr><tr><td class="pr-3 pb-1">Card Validity</td><td class="pb-1">8/21 (OR Any Future MM/YY)</td></tr><tr><td class="pr-3">Card CVV</td><td>111</td></tr><tr><td class="pr-3">OTP</td><td>1221</td></tr></table>
                                    </div>
                                    <div class="card-footer text-muted py-2">If above details are not working, then please take latest details from your Instamojo Panel.</div>
                                </div>
                            </div>
                        </div>
                        <div class="row border-top pt-2"><div class="col-12 text-right"><button type="button" name="create" id="submit3" class="btn btn-primary">Save</button></div></div>
                    </form>
                    </div>
                </div>
            </div>

           </div>
        <?php include('../footer.php');?>
     </div>
</div>
<div class="modal fade" id="payumodal"><div class="modal-dialog modal-sm"><div class="modal-content"><div class="modal-header"><h4 class="modal-title">PayU Auth Header</h4><button type="button" class="close" data-dismiss="modal">×</button></div><div class="modal-body text-muted">Please get <b>Auth Header</b> from PayU panel</div></div></div></div>
<script src="<?php echo SITEURL ?>/js/validation.js"></script>
<script>
$(document).ready(function(){
    $(".payu_enable_disble").on("change",function(){
        var getid = $(this).attr('id');
        var c = $("#"+getid+":checked").val();
        if(c=="on"){ status = "1"; msg = "enabled"; } else{ status = 0; msg = "disabled"; }
        var info = {status:status,action:"payu_enable_disble"};
        $.ajax({
            type:"POST",
            url:"ajaxdata.php",
            data:info,
            success:function(response){
                if(response==1){
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Payu money payment gateway "+msg+" successfully").delay(3000).fadeOut();
                } else{
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please try after some time").delay(3000).fadeOut();
                }
            }
        });
    });
    $(".easebuzz_enable_disable").on("change",function(){
        var getid = $(this).attr('id');
        var c = $("#"+getid+":checked").val();
        if(c=="on"){  status = "1"; msg = "enabled"; } else { status = 0; msg = "disabled"; }
        var info = {status:status,action:"easebuzz_enable_disable"};
        $.ajax({
            type:"POST",
            url:"ajaxdata.php",
            data:info,
            success:function(response){
                if(response==1){
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Easebuzz payment gateway "+msg+" successfully").delay(3000).fadeOut();
                    if(status ==0){
                        $('.hidesection').hide();
                        $('#a').click(function(){$('#content1').toggle();});
                    }
                } else{
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please try after some time").delay(3000).fadeOut();
                }
            }
        });
    });
    $(".instamojo_enable_disable").on("change",function(){
        var getid = $(this).attr('id');
        var c = $("#"+getid+":checked").val();
        if(c=="on"){  status = "1"; msg = "enabled"; } else { status = 0; msg = "disabled"; }
        var info = {status:status,action:"instamojo_enable_disable"};
        $.ajax({
            type:"POST",
            url:"ajaxdata.php",
            data:info,
            success:function(response){
                if(response==1){
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Instamojo payment gateway "+msg+" successfully").delay(3000).fadeOut();
                    if(status ==0){
                        $('.hidesection').hide();
                        $('#a').click(function(){$('#content1').toggle();});
                    }
                } else{
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please try after some time").delay(3000).fadeOut();
                }
            }
        });
    });
    $(".razorpay_enable_disable").on("change",function(){
        var getid = $(this).attr('id');
        var c = $("#"+getid+":checked").val();
        if(c=="on"){  status = "1"; msg = "enabled"; } else { status = 0; msg = "disabled"; }
        var info = {status:status,action:"razorpay_enable_disable"};
        $.ajax({
            type:"POST",
            url:"ajaxdata.php",
            data:info,
            success:function(response){
                if(response==1){
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Razorpay payment gateway "+msg+" successfully").delay(3000).fadeOut();
                    if(status ==0){
                        $('.hidesection').hide();
                        $('#a').click(function(){$('#content1').toggle();});
                    }
                } else{
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please try after some time").delay(3000).fadeOut();
                }
            }
        });
    });
});
$("[name='pay-mon-tog']").change(function() {$(".payu-details").slideToggle();});
$("[name='ease-buz-tog']").change(function() {$(".ease-buz-det").slideToggle();});
$("[name='instamojo-tog']").change(function() {$(".instamojo-det").slideToggle();});
$("[name='razorpay-tog']").change(function() {$(".razorpay-det").slideToggle();});
$('input:radio[name = "pay_url"]').click(function(){   
    pay_url = $("input[name='pay_url']:checked").val();
    if(pay_url == "https://test.payu.in"){ $(".payu_testdiv").removeClass("cc-display-none"); $(".payu_livediv").addClass("cc-display-none"); } else{ $(".payu_testdiv").addClass("cc-display-none"); $(".payu_livediv").removeClass("cc-display-none"); }
});
$('input:radio[name = "ease_env"]').click(function(){
    pay_url = $("input[name='ease_env']:checked").val();
    if(pay_url == "test"){ $(".ease_testdiv").removeClass("cc-display-none"); $(".ease_livediv").addClass("cc-display-none"); } else{ $(".ease_testdiv").addClass("cc-display-none"); $(".ease_livediv").removeClass("cc-display-none"); }
});
$('input:radio[name = "instamojo_env"]').click(function(){
    pay_url = $("input[name='instamojo_env']:checked").val();
    if(pay_url == "test"){ $(".instamojo_testdiv").removeClass("cc-display-none"); $(".instamojo_livediv").addClass("cc-display-none");
    } else{ $(".instamojo_testdiv").addClass("cc-display-none"); $(".instamojo_livediv").removeClass("cc-display-none"); }
});
    
$('input[name="razorpay_env"]').click(function(){
    pay_url = $(this).val();
    if(pay_url == "test"){ $(".razorpay_testdiv").removeClass("cc-display-none"); $(".razorpay_livediv").addClass("cc-display-none"); } else{ $(".razorpay_livediv").removeClass("cc-display-none"); $(".razorpay_testdiv").addClass("cc-display-none"); }
});
    
$(document).ready(function(){
    $("#submit").on("click", function(){
        var pay_url = $("input[name = 'pay_url']:checked").val();
        if(pay_url == "https://test.payu.in"){ var Merchant_Key = $("#test_Merchant_Key").val(); var salt_key = $("#test_salt_key").val(); }
        else{ var Merchant_Key = $("#Merchant_Key").val(); var salt_key = $("#salt_key").val(); }
        auth_header = $("#auth_header").val();
        if(Merchant_Key != "" && salt_key != "" && pay_url!= ""){
            var info = {Merchant_Key: Merchant_Key, salt_key: salt_key, pay_url:pay_url, action:'Payu_money_details',auth_header:auth_header };
            $.ajax({
                type: "POST",
                url: "ajaxdata.php",
                data: info,
                success: function(response){
                    if(response == 1){
                        $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Details updated successfully").delay(3000).fadeOut();
                    } if(response == 0){
                        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please try after some time").delay(3000).fadeOut();
                    }
                }
            });
        } else{
            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please fill all mandatory fields").delay(3000).fadeOut();
        }
    });
    $("#submit2").on("click", function(){
        var ease_id= $("#ease_id").val();
        var ease_env = $("input[name = 'ease_env']:checked").val();
        if(ease_env == "prod"){
            var ease_Merchant_Key = $("#ease_Merchant_Key").val(); var ease_salt_key = $("#ease_salt_key").val();
        } else{
            var ease_Merchant_Key = $("#test_ease_Merchant_Key").val(); var ease_salt_key = $("#test_ease_salt_key").val();
        }
        if(ease_id!="" && ease_Merchant_Key != "" && ease_salt_key != "" && ease_env!= "" ){
            var info = {ease_id:ease_id, ease_Merchant_Key: ease_Merchant_Key, ease_salt_key: ease_salt_key, ease_env:ease_env, action:'ease_money_details' };
            $.ajax({
                type: "POST",
                url: "ajaxdata.php",
                data: info,
                success: function(response){
                    if(response == 1){
                        $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Details updated successfully").delay(3000).fadeOut();
                    } if(response == 0){
                        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please try after some time").delay(3000).fadeOut();
                    }
                }
            });
        } else{
            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please fill all mandatory fields").delay(3000).fadeOut();
        }
    });
    $("#submit3").on("click", function(){
        var instamojo_env = $("input[name = 'instamojo_env']:checked").val();
        var insta_api_Key = $("#insta_api_Key").val();
        var insta_auth_token = $("#insta_auth_token").val();
        var test_insta_api_Key = $("#test_insta_api_Key").val();
        var test_insta_auth_token = $("#test_insta_auth_token").val();
        if(instamojo_env=="prod" && (insta_api_Key == "" || insta_auth_token == "")){
            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please fill all mandatory fields").delay(3000).fadeOut();
        } else{
            var info = { instamojo_env:instamojo_env, insta_api_Key: insta_api_Key, insta_auth_token: insta_auth_token,test_insta_api_Key:test_insta_api_Key, test_insta_auth_token:test_insta_auth_token, action:'insta_money_details' };
            $.ajax({
                type: "POST",
                url: "ajaxdata.php",
                data: info,
                success: function(response){
                    if(response == 1){
                        $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Details updated successfully").delay(3000).fadeOut();
                    } if(response == 0){
                        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please try after some time").delay(3000).fadeOut();
                    }
                }
            });
        }
    });
    $("#submit4").on("click", function(){
        var razorpay_env = $("input[name = 'razorpay_env']:checked").val();
        var razorpay_api_Key = $("#razorpay_api_Key").val();
        var razorpay_secrete = $("#razorpay_auth_token").val();
        var test_razorpay_api_Key = $("#test_razorpay_api_Key").val();
        var test_razorpay_secrete = $("#test_razorpay_auth_token").val();
        if(razorpay_env=="prod" && (insta_api_Key == "" || insta_auth_token == "")){
            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please fill all mandatory fields").delay(3000).fadeOut();
        } else{
              var info = {razorpay_env:razorpay_env, razorpay_api_Key: razorpay_api_Key, razorpay_secrete: razorpay_secrete,test_razorpay_api_Key:test_razorpay_api_Key, test_razorpay_secrete:test_razorpay_secrete, action:'razorpay_money_details' };
            $.ajax({
                type: "POST", url: "ajaxdata.php", data: info,
                success: function(response){
                    if(response == 1){
                        $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Details updated successfully").delay(3000).fadeOut();
                    } if(response == 0){
                        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please try after some time").delay(3000).fadeOut();
                    }
                }
            });
        }
    });
});
$(document).ready(function(){
    if($('.payuhandradio input[type="radio"]').attr('id') == 'payuenvtest'){ $(".paysampledetails").show(); }
    if($('.easebuzzpayradio input[type="radio"]').attr('id') == 'easebuzenvtest'){ $(".easebuzzsampledetails").show(); }
    if($('.instamojopayradio input[type="radio"]').attr('id') == 'instamojoenvtest'){ $(".instamojosampledetails").show(); }
    if($('.razorpaypayradio input[type="radio"]').attr('id') == 'razorpayenvtest'){ $(".razorpaysampledetails").show(); }
    $('.payuhandradio input[type="radio"]').click(function(){
        if($(this).attr('id') == 'payuenvtest'){ $(".paysampledetails").show(); } else{ $(".paysampledetails").hide(); }
    });
    $('.easebuzzpayradio input[type="radio"]').click(function(){
        if($(this).attr('id') == 'easebuzenvtest'){ $(".easebuzzsampledetails").show(); } else{ $(".easebuzzsampledetails").hide(); }
    });
    $('.instamojopayradio input[type="radio"]').click(function(){
        if($(this).attr('id') == 'instamojoenvtest'){ $(".instamojosampledetails").show(); } else{ $(".instamojosampledetails").hide(); }
    });
    $('.razorpaypayradio input[type="radio"]').click(function(){
        if($(this).attr('id') == 'razorpayenvtest'){ $(".razorpaysampledetails").show(); } else{ $(".razorpaysampledetails").hide(); }
    });
});
</script>
</body>
</html>