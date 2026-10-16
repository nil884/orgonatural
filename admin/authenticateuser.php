<?php include("../includes/configuration.php");?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Edit Admin Profile</title>
    <?php include('commoncss.php') ?>
</head>
<body>
<div class="page-body-wrapper">
    <? include 'header.php'; ?>
    <? include 'sidebar.php'; ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2">
                    <div><h2 class="card-head-title">Authentication</h2></div>
                    <div class="btn-actions-pane-right"><button class="btn btn-secondary btn-sm" onclick="goBack()"> Back</button></div>
                </div>
                <div class="info">
                    <div class="card-body pb-0"><span>Please Re-authenticate Yourself</span></div>
                    <form>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="row">
                                        <label class="col-sm-3 col-md-3 col-lg-3 col-xl-2 col-form-label">Password</label>
                                        <div class="col-sm-6 col-md-6 col-lg-6 col-xl-5">
                                            <input type="hidden" name="userid" id="userid" value="<?php echo base64_encode($_SESSION['admin']); ?>"/>
                                            <input type="password" name="pwd" id="pwd" class="form-control" maxlength="20" placeholder="Enter Current Password" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer py-2 text-right"><button class="btn btn-primary" type="button" onclick="authenticateuser()">Next</button></div>
                    </form>
                </div>
            </div>
        </div>
        <?php include('footer.php');?>
    </div>
</div>
<script>
$("#pwd").val("");
function authenticateuser(){
    var userid = $("#userid").val();
    var pwd = $("#pwd").val();
    if(pwd!=""){
        var info = {userid1:userid,pwduser:pwd,action:"authenticateuser"};
        $.ajax({
            type:"POST",
            url:"ajax/login_ajax.php",
            data:info,
            success:function(response){
                if(response==1){ location.href="editprofile.php";
                } else{
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Invalid password").delay(3000).fadeOut();
                }
            }
        });
    } else{
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter password").delay(3000).fadeOut();
    }
}
</script>
</body>
</html>