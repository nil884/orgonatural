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
                    <div class="btn-actions-pane-right"><button type="button" class="btn btn-primary btn-sm" onclick="showform()">Edit</button>
                    <button class="btn btn-secondary btn-sm" onclick="goBack()">Back</button></div>
                </div>
                <div class="card-body profileinfo">
                    <div class="mobErr"></div><div class="pwd1Err"></div><div class="pwdErr"></div>
                    <div class="row">
                        <div class="info col-lg-8 col-xl-6">
                            <div class="row">
                                <label class="col-4 col-sm-3 col-md-3 col-xl-3 cc-col-pad-view">Username</label>
                                <div class="col-8 col-sm-6 col-md-8 col-xl-8 cc-col-pad-view text-secondary"><?php echo $get_admin_details[0]['username']; ?></div>
                            </div>
                            <div class="row">
                                <label class="col-4 col-sm-3 col-md-3 col-xl-3 cc-col-pad-view">Mobile</label>
                                <div class="col-8 col-sm-6 col-md-8 col-xl-8 cc-col-pad-view text-secondary"><?php if($get_admin_details[0]['u_mob']){echo $get_admin_details[0]['u_mob'];}else{echo "[Not Defined]"; } ?></div>
                            </div>
                            <div class="row">
                                <label class="col-4 col-sm-3 col-md-3 col-xl-3">Password</label>
                                <div class="col-8 col-sm-6 col-md-8 col-xl-8 text-secondary"><?php echo "<b>******</b>"; ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <form class="profileform pt-3 cc-display-none">
                    <div class="info col-lg-12 col-xl-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-md-3 col-lg-3 col-xl-4 col-form-label">Username</label>
                            <div class="col-sm-8 col-md-6 col-lg-5 col-xl-8">
                                <input type="hidden" name="userid" id="userid" value="<?php echo base64_encode($get_admin_details[0]['u_id']); ?>"/>
                                <input type="text" name="uname" class="form-control" id="username" value="<?php  echo $get_admin_details[0]['username']; ?>" placeholder="Username" maxlength="15"/>
                            </div>
                            <div class="col-md-5 unameErr"></div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-md-3 col-lg-3 col-xl-4 col-form-label">Mobile</label>
                            <div class="col-sm-8 col-md-6 col-lg-5 col-xl-8">
                                <input type="text" name="umbl" class="form-control number" id="umbl" value="<?php echo $get_admin_details[0]['u_mob']; ?>" onkeyup="mobnumbercheck('umbl')" placeholder="Mobile Number" maxlength="10"/>
                            </div>
                        </div>
                        <div class="form-group row chngpwdbtn">
                            <label class="col-sm-4 col-md-3 col-lg-3 col-xl-4 col-form-label pt-0">Password</label>
                            <div class="col-sm-8 col-md-6 col-lg-5 col-xl-8"><a class="form-control-static chngpwd text-primary cc-cursor-pointer" onclick="showpwd()">I Want To Change My Password</a></div>
                        </div>
                        <div class="pwd cc-display-none">
                            <div class="form-group row">
                                <label class="col-sm-4 col-md-3 col-lg-3 col-xl-4 col-form-label">New Password</label>
                                <div class="col-sm-8 col-md-6 col-lg-5 col-xl-8">
                                    <input type="password" name="pwd" class="form-control" id="pwd" placeholder="New Password" maxlength="15"/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-md-3 col-lg-3 col-xl-4 col-form-label">Confirm Password</label>
                                <div class="col-sm-8 col-md-6 col-lg-5 col-xl-8"><input type="password" name="pwd1" class="form-control" id="pwd1" placeholder="Confirm New Password" maxlength="15"/></div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right py-2">
                        <button class='btn btn-primary' onclick="chkdetails('<?=$getconfigdetails[0]['admin_authentication']; ?>')" type="button">Update</button>
                        <button class='btn btn-secondary' onclick="canceledit()" type="button">Cancel</button>
                    </div>
                </form>
                <div class="pro-otp-col cc-display-none"><form class="form-horizontal otpform"></form></div>
            </div>
        </div>
        <?php include('footer.php');?>
    </div>
</div>
<script src="<?php echo SITEURL ?>/js/validation.js"></script>
<script>
function numbercheck(id){
    var inp=$("#"+id).val();
    if(isNaN(inp)){
        var newstr = inp.slice(0, -1);
        $("#"+id).val(newstr);
    }
}
function showpwd(){ $(".pwd, .chngpwdbtn").toggle(); }
function showform(){ $(".profileform, .profileinfo").toggle(); }
function canceledit(){
    info = {action:"cancel"}
    $.ajax({
        type:"post",
        url:"ajax/login_ajax.php",
        data: info,
        success:function(response){ location.reload(); }
    });
}
function chkdetails(auth){
    var username = $("#username").val(); var mobile = $("#umbl").val(); var pwd = $("#pwd").val(); var pwd1 = $("#pwd1").val(); var userid = $("#userid").val(); var patt = new RegExp(/^[7-9][0-9]{9}$/); var res = patt.test(mobile);
    if(res==false || mobile.length < 10 ){
       $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Invalid mobile number").delay(3000).fadeOut()
    } if(pwd!="" && pwd1==""){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please confirm your password").delay(3000).fadeOut()
    } if(pwd1!="" && pwd==""){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Password field not be blank").delay(3000).fadeOut()
    } if((pwd1!="" && pwd!="")&&(pwd!==pwd1)){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Confirm password not matched").delay(3000).fadeOut()
    } if(res==true && ((pwd1=="" && pwd=="")||((pwd1!="" && pwd!="") && (pwd===pwd1)))){

       if(auth==0){
            info = {userid:userid,username:username,mobile:mobile,pwd:pwd,pwd1:pwd1,action:"update_profile"}
           $.ajax({
            type:"post",
            url:"ajax/login_ajax.php",
            data: info,
            success:function(response){

                    if(response==1){
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("OTP expired").delay(3000).fadeOut()
                    } else if(response==0){
                        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Invalid OTP").delay(3000).fadeOut()
                    } else{
                        $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Profile Updated..").delay(3000).fadeOut()
                        setTimeout(function(){ location.href=response; }, 3000);
                    }

            }
        });
       }else{
           info = {userid:userid,username:username,mobile:mobile,pwd:pwd,pwd1:pwd1,action:"profile_sendotp"}
            info1 = {userid:userid,username:username,mobile:mobile,pwd:pwd,pwd1:pwd1,action:"getedit_form"}
            $.ajax({
                type:"post",
                url:"ajax/login_ajax.php",
                data: info,
                success:function(response){
                    if(response==1){
                        $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("OTP is sent").delay(3000).fadeOut()
                        $.ajax({
                            type:"post",
                            url:"ajax/login_ajax.php",
                            data: info1,
                            success:function(response){ $(".profileform").hide(); $(".pro-otp-col").show(); $(".otpform").replaceWith(response); }
                        });
                    } else{
                        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please try after some time").delay(3000).fadeOut()
                    }
                }
            });
       }

       /* $.ajax({
            type:"post",
            url:"ajax/login_ajax.php",
            data: info,
            success:function(response){
                if(response==1){
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("OTP is sent").delay(3000).fadeOut()
                    $.ajax({
                        type:"post",
                        url:"ajax/login_ajax.php",
                        data: info1,
                        success:function(response){ $(".profileform").hide(); $(".pro-otp-col").show(); $(".otpform").replaceWith(response); }
                    });
                } else{
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please try after some time").delay(3000).fadeOut()
                }
            }
        });*/
    }
}
function resendotp(val1){
    var info = {mob:val1,action:"profile_resendotp"};
    $(".resendloader").html("Please wait.. Resending..");
    $.ajax({
        type:"POST",
        url:"ajax/login_ajax.php",
        data:info,
        success:function(response){
            $(".resendloader").html('<button class="btn btn-link resendbtn" onclick="resendotp('+val1+')"> Resend Now </button>');
            $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("OTP is sent").delay(3000).fadeOut()
        }
    });
}
function verifyotp(){
    var username = $("#username").val(); var mobile = $("#umbl").val(); var pwd = $("#pwd").val(); var pwd1 = $("#pwd1").val(); var userid = $("#userid").val(); var otp = $("#otp").val();
    if(otp == ""){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter OTP first").delay(3000).fadeOut()
    }
    else{
        var info = {userid:userid,username:username,mobile:mobile,pwd:pwd,pwd1:pwd1,otp:otp,action:"Profile_verifyotp"};
        $.ajax({
            type:"POST",
            url:"ajax/login_ajax.php",
            data:info,
            success:function(response){
                if(response==1){
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("OTP expired").delay(3000).fadeOut()
                } else if(response==0){
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Invalid OTP").delay(3000).fadeOut()
                } else{
                    setTimeout(function(){ location.href=response; }, 3000);
                }
            }
        });
    } 
}
</script>
</body>
</html>