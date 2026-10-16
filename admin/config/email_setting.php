<?php include("../../includes/configuration.php");?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Email Setting</title>
    <?php include('../commoncss.php') ?>
</head>
<body>
<div class="page-body-wrapper">
    <?php include('../header.php') ?>
    <?php include('../sidebar.php') ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2"><div><h2 class="card-head-title">Email Setting</h2></div><div class="btn-actions-pane-right"><a href="<?php echo ADMINURL; ?>sms_email_setting/email.php" target="_blank" class="btn btn-primary btn-sm">Email Templates</a></div></div>
                <form>
                    <div class="card-body">
                        <div class="col-xl-9 px-0">
                            <h6>Incomimg Email</h6>
                            <div class="form-group row">
                                <label class="col-sm-3 col-md-3 col-lg-3 col-xl-3 col-form-label cc-mandatary-field">Admin Email ID</label>
                                <div class="col-sm-7 col-md-6 col-lg-6 col-xl-5"><input type="text" name="admin_email" id="admin_email" class="form-control" placeholder="Admin Email ID" onblur="mailchk('admin_email')" maxlength="50" value="<? echo $getconfigdetails[0]['admin_email']; ?>" /></div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-md-3 col-lg-3 col-xl-3 col-form-label cc-mandatary-field">Footer Email ID</label>
                                <div class="col-sm-7 col-md-6 col-lg-6 col-xl-5">
                                    <input type="text" name="footer_email" id="footer_email" class="form-control" placeholder="Footer Email ID" maxlength="50" onblur="mailchk('footer_email')" value="<? echo $getconfigdetails[0]['footer_email']; ?>" />
                                </div>
                            </div>                         
                            <div class="form-group row">
                                <label class="col-sm-3 col-md-3 col-lg-3 col-xl-3 col-form-label cc-mandatary-field">Registration Email ID</label>
                                <div class="col-sm-7 col-md-6 col-lg-6 col-xl-5">
                                    <input type="text" name="registration_email" id="registration_email" class="form-control" placeholder="Registration Email ID" maxlength="50" onblur="mailchk('registration_email')" value="<? echo $getconfigdetails[0]['registration_email']; ?>" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-md-3 col-lg-3 col-xl-3 col-form-label cc-mandatary-field">Order Email ID</label>
                                <div class="col-sm-7 col-md-6 col-lg-6 col-xl-5">
                                    <input type="text" name="order_email" id="order_email" class="form-control" placeholder="Order Email ID" maxlength="50" onblur="mailchk('order_email')" value="<? echo $getconfigdetails[0]['order_email']; ?>" />
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-3 col-md-3 col-lg-3 col-xl-3 col-form-label cc-mandatary-field">Contact Email ID</label>
                                <div class="col-sm-7 col-md-6 col-lg-6 col-xl-5">
                                    <input type="text" name="contact_email" id="contact_email" class="form-control" placeholder="Contact Email ID" maxlength="50" onblur="mailchk('contact_email')" value="<? echo $getconfigdetails[0]['contact_email']; ?>" />
                                </div>
                            </div>
                        </div>
                        <div class="row"><div class="col-12 p-0"><hr></div></div>
                        <div class="col-xl-9 px-0">
                            <h6>Outgoing Email</h6>
                            <div class="form-group row mb-0">
                                <label class="col-sm-3 col-md-3 col-lg-3 col-xl-3 col-form-label cc-mandatary-field">Sender Email ID</label>
                                <div class="col-sm-7 col-md-6 col-lg-6 col-xl-5">
                                    <input type="text" name="sender_email" id="sender_email" class="form-control" placeholder="Sender Email ID" maxlength="50" onblur="mailchk('sender_email')" value="<? echo $getconfigdetails[0]['sender_email']; ?>" />
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
    $("#submit").on("click", function() {
        var admin_email = $("#admin_email").val();
        var footer_email = $("#footer_email").val();
        var sender_email = $("#sender_email").val();
        var registration_email = $("#registration_email").val();
        var order_email = $("#order_email").val();
        var contact_email = $("#contact_email").val();
        if (admin_email != "" && footer_email != ""  && sender_email != "" && registration_email != "" && order_email != ""  && contact_email != "") {
            var info = {
                admin_email: admin_email, footer_email: footer_email, sender_email:sender_email, registration_email:registration_email, order_email:order_email, contact_email:contact_email, action:'email_setting'
            };
            $.ajax({
                type: "POST",
                url: "ajaxdata.php",
                data: info,
                success: function(response) {
                    $(".loader").html('');
                    if(response == 1){
                        $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Details updated successfully").delay(3000).fadeOut();
                    } if(response == 0){
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