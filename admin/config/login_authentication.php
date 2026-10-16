<?php include("../../includes/configuration.php");?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Login Authentication</title>
    <?php include('../commoncss.php') ?>
</head>
<body>
<div class="page-body-wrapper">
    <?php include('../header.php') ?>
    <?php include('../sidebar.php') ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card card-body">
                <div class="alert alert-info mb-0">
                    <h6><b>Important - Before Activating The Multi-Factor Authentication</b></h6>
                    <ul class="mb-0 pl-3"><li class="mb-1">Please make sure that SMS Gateway is configured properly in Admin Panel - <a href="<?php echo ADMINURL; ?>config/smsgateway.php" target="_blank"><b>Click Here</b></a></li><li>SMS delivery is subjected to network congestion, government rules, and regulations, network carrier limitations, so sometimes timely delivery of SMS is not possible in situations.</li></ul>
                </div>
            </div>
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Multi-factor Login Authentication  Details</h2></div></div>
                <form>
                    <div class="card-body">
                        <? if(SMS_SYSTEM=="OFF"){?> <div class="alert alert-danger">
                            <h6><b>Important - SMS System Is Disabled.</b></h6>
                            <ul class="mb-0 pl-3"><li class="mb-1">Please don't activate multi-factor authentication. It will make an impact on the login system.</li></ul>
                        </div>
                        <?}  ?>
                        <div class="row">
                            <div class="col-md-9">
                                <div class="form-group row">
                                    <label class="col-sm-5 col-md-6 col-lg-5 col-xl-4 col-form-label cc-mandatorystar pt-0">Buyer Login Authentication</label>
                                    <div class="col-sm-7 col-md-6 col-lg-6 col-xl-5">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="bierlogauthenyes" name="user_login_authentication" value="1" <? if($getconfigdetails[0]['user_authentication'] == "1"){ echo "checked"; } ?>> <label class="custom-control-label" for="bierlogauthenyes">Enable</label> 
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="bierlogauthenno" name="user_login_authentication" value="0" <? if($getconfigdetails[0]['user_authentication'] == "0"){ echo "checked"; } ?>> <label class="custom-control-label" for="bierlogauthenno">Disable</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-md-6 col-lg-5 col-xl-4 col-form-label cc-mandatorystar pt-0">Admin Login Authentication</label>
                                    <div class="col-sm-7 col-md-6 col-lg-6 col-xl-5">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="adminlogauthenyes" name="admin_login_authentication" value="1" <? if($getconfigdetails[0]['admin_authentication'] == "1"){ echo "checked"; } ?>> <label class="custom-control-label" for="adminlogauthenyes">Enable</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="adminlogauthenno" name="admin_login_authentication" value="0" <? if($getconfigdetails[0]['admin_authentication'] == "0"){ echo "checked"; } ?>> <label class="custom-control-label" for="adminlogauthenno">Disable</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-5 col-md-6 col-lg-5 col-xl-4 col-form-label cc-mandatorystar pt-0">Vendor Login Authentication</label>
                                    <div class="col-sm-7 col-md-6 col-lg-6 col-xl-5">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="vendlogauthenyes" name="vendor_login_authentication" value="1" <? if($getconfigdetails[0]['vendor_authentication'] == "1"){ echo "checked"; } ?>> <label class="custom-control-label" for="vendlogauthenyes">Enable</label> 
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="vendlogauthenno" name="vendor_login_authentication" value="0" <? if($getconfigdetails[0]['vendor_authentication'] == "0"){ echo "checked"; } ?>> <label class="custom-control-label" for="vendlogauthenno">Disable</label>
                                        </div>
                                    </div>
                                </div>                                
                            </div>
                        </div>
                    </div>
                    <div class="card-footer py-2 text-right"><button type="button" name="create" id="submit" class="btn btn-primary">Save</button></div>
                </form>
            </div>
        </div>
        <?php include('../footer.php');?>
    </div>
</div>
<script src="<?php echo SITEURL ?>/js/validation.js"></script>
<script>
$(document).ready(function(){
    $("#submit").on("click", function(){
        var user_login_authentication = $("input[name='user_login_authentication']:checked").val();
        var admin_login_authentication = $("input[name='admin_login_authentication']:checked").val();
        var vendor_login_authentication = $("input[name='vendor_login_authentication']:checked").val();
        if(user_login_authentication != "" && admin_login_authentication != "" && vendor_login_authentication != ""){
            var info = {
                user_login_authentication: user_login_authentication, admin_login_authentication:admin_login_authentication, vendor_login_authentication:vendor_login_authentication,
                action:'login_authentication'
            };
            $.ajax({
                type: "POST",
                url: "ajaxdata.php",
                data: info,
                success: function(response) {
                    if(response == 1){
                        $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Details updated successfully").delay(3000).fadeOut();
                    }if(response == 0) {
                        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please try after some time").delay(3000).fadeOut();
                    }
                }
            });
        } else{
            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please fill all mandatory fields correctly").delay(3000).fadeOut();
        }
    });
});
</script>
</body>
</html>