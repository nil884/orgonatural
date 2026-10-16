<?php include "../../includes/configuration.php";?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Social Media Login</title>
    <?php include('../commoncss.php') ?>
</head>
<body>
<div class="page-body-wrapper">
    <?php include('../header.php') ?>
    <?php include('../sidebar.php') ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2"><div><h2 class="card-head-title">Add Facebook Login Details</h2></div><div class="btn-actions-pane-right"><a href="<?=DOCUMENTURL;?>facebook-login.php" class="btn btn-primary btn-sm" target="_blank">Help?</a></div></div>
                <form>
                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="col-lg-12 col-xl-11">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-md-4 col-lg-3 col-form-label cc-mandatorystar">Facebook App ID</label>
                                    <div class="col-sm-8 col-md-8 col-lg-5"><input type="text" name="fb_appId" id="fb_appId" class="form-control" placeholder="Enter Facebook App ID" maxlength="25" value="<?php echo $getconfigdetails[0]['fb_appId']; ?>" /></div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-md-4 col-lg-3 col-form-label cc-mandatorystar">Facebook Secret Key</label>
                                    <div class="col-sm-8 col-md-8 col-lg-5"><input type="text" name="fb_secretKey" id="fb_secretKey" class="form-control" placeholder="Enter Facebook Secret Key" maxlength="45" value="<?php echo $getconfigdetails[0]['fb_secretKey']; ?>" /></div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-md-4 col-lg-3 col-form-label cc-mandatorystar">Facebook Call Back URL</label>
                                    <div class="col-sm-8 col-md-8 col-lg-5"><input type="text" name="fb_callbackURL" id="fb_callbackURL" class="form-control" placeholder="Facebook Call Back URL" maxlength="50" value="<?php echo $getconfigdetails[0]['fb_callbackURL']; ?>" disabled/></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer py-2 text-right"><button type="button" name="create" id="submit" class="btn btn-primary">Save</button></div>
                </form>
            </div>
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2"><div><h2 class="card-head-title">Add Google Login Details</h2></div><div class="btn-actions-pane-right"><a href="<?=DOCUMENTURL;?>google-login.php" class="btn btn-primary btn-sm" target="_blank">Help?</a></div></div>
                <form>
                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="col-lg-12 col-xl-11">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-md-4 col-lg-3 col-form-label cc-mandatorystar">Google App Name</label>
                                    <div class="col-sm-8 col-md-8 col-lg-5"><input type="text" name="gp_callbackURL" id="gp_appname" class="form-control" placeholder="Enter Google App Name" maxlength="90" value="<?php echo $getconfigdetails[0]['gp_appname']; ?>" /></div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-md-4 col-lg-3 col-form-label cc-mandatorystar">Google Client ID </label>
                                    <div class="col-sm-8 col-md-8 col-lg-5"><input type="text" name="gp_clientId" id="gp_clientId" class="form-control" placeholder="Enter Google Client ID" maxlength="140" value="<?php echo $getconfigdetails[0]['gp_clientId']; ?>" /></div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-md-4 col-lg-3 col-form-label cc-mandatorystar">Google Secret Key</label>
                                    <div class="col-sm-8 col-md-8 col-lg-5"><input type="text" name="gp_secretKey" id="gp_secretKey" class="form-control" placeholder="Enter Google Secret Key" maxlength="40" value="<?php echo $getconfigdetails[0]['gp_secretKey']; ?>" /></div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-md-4 col-lg-3 col-form-label cc-mandatorystar">Google Call Back URL</label>
                                    <div class="col-sm-8 col-md-8 col-lg-5"><input type="text" name="gp_callbackURL" id="gp_callbackURL" class="form-control" placeholder="Google Call Back URL" maxlength="50" value="<?php echo $getconfigdetails[0]['gp_callbackURL']; ?>" disabled /></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer py-2 text-right"><button type="button" name="create" id="submit1" class="btn btn-primary">Save <span class="loader1"></span></button></div>
                </form>
            </div>
        </div>
        <?php include '../footer.php';?>
    </div>
</div>
<script>
$(document).ready(function(){
    $("#submit").on("click", function(){
        var fb_appId = $("#fb_appId").val(); var fb_secretKey = $("#fb_secretKey").val(); var fb_callbackURL = $("#fb_callbackURL").val();
        if(fb_appId != "" && fb_secretKey != "" && fb_callbackURL!= ""){
            var info = {fb_appId: fb_appId, fb_secretKey: fb_secretKey, fb_callbackURL:fb_callbackURL, action:'social_login' };
            $.ajax({
                type: "POST",
                url: "ajaxdata.php",
                data: info,
                success: function(response){
                    if(response == 1){
                        $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Details updated successfully").delay(3000).fadeOut();
                    }if(response == 0){
                        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please try after some time").delay(3000).fadeOut();
                    }
                }
            });
        } else{
            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please fill all mandatory fields").delay(3000).fadeOut();
        }
    });
    
    $("#submit1").on("click", function(){
        var gp_clientId = $("#gp_clientId").val(); var gp_secretKey = $("#gp_secretKey").val(); var gp_callbackURL = $("#gp_callbackURL").val(); var gp_appname = $("#gp_appname").val();
        if(gp_clientId != "" && gp_secretKey != "" && gp_callbackURL!= ""  && gp_appname != ""){
        var info = {gp_clientId: gp_clientId, gp_secretKey: gp_secretKey, gp_callbackURL:gp_callbackURL, gp_appname :gp_appname, action:'social_login_gp'};
        $.ajax({
            type: "POST",
            url: "ajaxdata.php",
            data: info,
            success: function(response) {
                if(response == 1){
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Details updated successfully").delay(3000).fadeOut();
                }if(response == 0){
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please try after some time").delay(3000).fadeOut();
               }
            }
        });
        } else {
            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please fill all mandatory fields").delay(3000).fadeOut();
        }
    });
});
</script>
</body>
</html>