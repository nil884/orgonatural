<?php include("../../includes/configuration.php");?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <title><?php echo SITENAME; ?> : Change Password</title>
    <?php include('../commoncss.php') ?>
</head>
<body>
<div class="page-body-wrapper">
    <? include '../header.php'; ?>
    <? include '../sidebar.php'; ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2">
                    <div><h2 class="card-head-title">Change Password</h2></div>
                    <div class="btn-actions-pane-right"><button type="button" onclick="goBack()" class='btn btn-secondary btn-sm'>Back</button></div>
                </div>
                <div class="card-body">
                    <div class="pwdmsg"></div>
                    <form action="#" method="post" id="chngpwd" enctype="multipart/form-data">
                        <input type="hidden" value="<?php echo $_SESSION['seller']; ?> " id="uid"/>
                        <div class="form-group row">
                            <label class="col-sm-4 col-md-4 col-lg-3 col-xl-2 col-form-label cc-mandatary-field">Current Password</label>
                            <div class="col-sm-8 col-md-6 col-lg-5 col-xl-4"><input type="password" id="oldpwd" class="form-control" placeholder=" Current Password"/></div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-md-4 col-lg-3 col-xl-2 col-form-label cc-mandatary-field">New Password</label>
                            <div class="col-sm-8 col-md-6 col-lg-5 col-xl-4"><input type="password" id="passwordn" class="form-control" placeholder="New Password"/></div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-md-4 col-lg-3 col-xl-2 col-form-label cc-mandatary-field">Confirm New Password</label>
                            <div class="col-sm-8 col-md-6 col-lg-5 col-xl-4"><input type="password" id="password1n" class="form-control" placeholder="Confirm New Password"/></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-5 offset-sm-4 col-md-5 offset-md-4 col-lg-4 offset-lg-3 col-xl-4 offset-xl-2"><input type="button" class="btn btn-primary chngepwd" value="Change Password"/></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php include '../footer.php'?>
    </div>
</div>
<script>
$(document).ready(function(){
    $(".chngepwd").click(function(){
        var uid = $("#uid").val();
        var oldpwd = $("#oldpwd").val();
        var password = $("#passwordn").val();
        var password1 = $("#password1n").val();
        if((oldpwd == "") || (password == "") || ( password1 == "")){
            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please fill all required  fields").delay(3000).fadeOut()
        } else if(password!=password1){
            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Confirm password and New password fileds are not matching !").delay(3000).fadeOut()
        } else{
            var info = {uid:uid,oldpwd:oldpwd,password:password,password1:password1,action:"update_password"};
            $.ajax({
                type:"post",
                url:"../ajax/ajaxdata.php",
                data: info,
                success:function(response){
                    if(response == 0){
                        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Entered Current Password is Wrong!").delay(3000).fadeOut()
                    } if(response == 2){
                        $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Your Password is changed successfully.<br> You need to relogin to your account.").delay(3000).fadeOut()
                        setTimeout(function(){
                            window.location.href="../logout.php";
                        }, 4000);
                    } if(response == 3){
                        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please Try After Some Time").delay(3000).fadeOut()
                    }
                }
            })
        }
    })
});
</script>
</body>
</html>
