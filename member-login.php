<?php include("includes/configuration.php");
$actual_link = ($_SERVER['HTTPS'] == 'on'?"https://":"http://").$_SERVER[HTTP_HOST]."".$_SERVER[REQUEST_URI];
if(SOCIALLOGIN=="ON"){ include_once 'google/gpconfig.php'; } ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Member Login : <?php echo SITE_TITLE; ?></title>
    <meta name="description" content="<?=METADESCRIPTION; ?>">
    <meta name="keywords" content="<?=METAKEYWORDS; ?>">
    <!--Open Graph / Facebook-->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>">
    <meta property="og:title" content="Member Login : <?php echo SITE_TITLE; ?>">
    <meta property="og:description" content="<?php echo METADESCRIPTION; ?>">
    <meta property="og:image" content="<?=(OGIMAGE!=""?SITEURL."/img/ogimage/".OGIMAGE:SITEURL."/img/projectimage/".(LOGO != ""?LOGO:"default_logo.png")); ?>"> 
    <!--Twitter-->
    <meta property="twitter:card" content="<?=(OGIMAGE!=""?SITEURL."/img/ogimage/".OGIMAGE:SITEURL."/img/projectimage/".(LOGO != ""?LOGO:"default_logo.png")); ?>">
    <meta property="twitter:url" content="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>">
    <meta property="twitter:title" content="Member Login : <?php echo SITE_TITLE; ?>">
    <meta property="twitter:description" content="<?php echo METADESCRIPTION; ?>">
    <meta property="twitter:image" content="<?=(OGIMAGE!=""?SITEURL."/img/ogimage/".OGIMAGE:SITEURL."/img/projectimage/".(LOGO != ""?LOGO:"default_logo.png")); ?>">
    <link rel="canonical" href="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" >
    <?php include "commoncss.php"; ?>
</head>
<body class="bg-light">
<div class="main-wrap">
    <?php include "menu.php"; ?>
    <div class="container">
        <div class="row">
            <div class="col-md-10 offset-md-1 memb-login-body">
                <div class="row justify-content-center">
                    <div class="col-lg-5 d-none d-lg-block">
                        <img src="<?=SITEURL;?>/img/projectimage/mem-login-banner.png" alt="mem-login-banner" class="img-responsive" width="350">
                        <h2 class="h5">Get access to your Orders, Wishlist and Recommendations</h2>
                    </div>
                    <div class="col-lg-6 offset-lg-1">
                        <div class="card border-0 cc-shadow-2 new-user-log mem-log-form">
                            <h2 class="mb-4 text-center cc-fw-4 h4">Login to your account</h2>
                            <div id="all_login">
                                <? if(SOCIALLOGIN=="ON"){ ?>
                                <div class="user-soc-login">
                                    <?php if((FB_APPID != "") && (FB_SECRETEKEY != "") && (FB_CALLBACKURL != "")){ ?>
                                    <a hreflang="en" href="<?=SITEURL; ?>/facebook/getaccesstoken.php?fb=<?=base64_encode($_SERVER['REQUEST_URI']); ?>" class="btn border text-dark"><span><img src="<?=SITEURL;?>/img/projectimage/facebook-login.svg" alt="Facebook" width="20" class="mr-2"></span>Facebook</a>
                                    <? }
                                    if((GP_CLIENTID != "") && (GP_SECRETEKEY!= "") && (GP_CALLBACKURL != "") && (GP_appname!= "")){
                                    if(isset($_REQUEST['code'])){
                                        $gClient->authenticate($_REQUEST['code']);
                                        $_SESSION['token'] = $gClient->getAccessToken();
                                        header('Location: ' .filter_var($redirectUrl, FILTER_SANITIZE_URL) );
                                    }
                                    if(isset($_SESSION['token'])){ $gClient->setAccessToken($_SESSION['token']);}
                                    if($gClient->getAccessToken()){
                                        $userProfile = $google_oauthV2->userinfo->get(); $gUser = checkUser('google',$userProfile['id'],$userProfile['given_name'],$userProfile['family_name'],$userProfile['email'],$userProfile['gender'],$userProfile['locale'],$userProfile['link'],$userProfile['picture']);
                                        $_SESSION['google_data'] = $userProfile;
                                        $_SESSION['token'] = $gClient->getAccessToken();
                                        if($_SESSION['setuser']!='setted'){
                                            $_SESSION['setuser']='setted';
                                            // echo '<script>window.location.href="'.SITEURL.'";</script>';
                                            header('Location: ' .SITEURL);
                                        }
                                    } else{  $authUrl = $gClient->createAuthUrl(); }
                                    if(isset($authUrl)){ ?>
                                    <a href="<?=$authUrl; ?>" class="btn border text-dark float-right" hreflang="en"><span><img src="<?=SITEURL;?>/img/projectimage/google-login.svg" alt="google" width="20" class="mr-2"></span> Google</a>
                                    <? } } ?>
                                </div>
                                <div class="pt-3 pb-3 text-center text-muted small">- OR USING EMAIL -</div>
                                <?} ?>
                                <div class="msgsl"></div>
                                <form action="#" id="formLogin">
                                    <div class="form-group">                                     
                                        <span class="input-group-text border-0"><i class="fa fa-envelope" aria-hidden="true"></i></span>
                                        <input type="email" class="form-control" placeholder="Enter Email" id="user_email" required onblur="mailchk('user_email')">
                                        <div class="invalid-tooltip">Please provide a valid Email</div>
                                    </div>
                                    <div class="form-group">
                                        <span class="input-group-text border-0"><i class="fa fa-lock" aria-hidden="true"></i></span>
                                        <input type="password" class="form-control" placeholder="Enter Password" id="user_password" required>
                                        <div class="invalid-tooltip">Please provide a valid Password</div>
                                        <div class="text-right"><a href="javascript:void(0);" data-toggle="modal" data-target="#myModal" class="mt-2 d-inline-block">Forgot Password?</a></div>
                                    </div>
                                    <div class="form-group">
                                        <?php if($getconfigdetails[0]['user_authentication']==0){  ?>
                                        <button type="button" class="btn btn-primary btn-block btn-lg" id="user_login" onclick='login_to_account()'>Log In</button>
                                        <? } else { ?>
                                        <button type="button" class="btn btn-primary btn-block btn-lg" name="login1"   id="user_login" onclick='validete()'>Log In</button>
                                        <? } ?>
                                    </div>
                                    <div class="form-group">
                                        <?php if($getstaticpagedetails[0]['terms_condition_data'] == "1" || $getstaticpagedetails[0]['privacy_policy_data'] == "1"){ ?>
                                        <p class="mb-0">By clicking Register, I agree to terms including the 
                                        <?php if($getstaticpagedetails[0]['terms_condition_data'] == "1"){?><a href="<?=SITEURL ?>/terms-condition" target="_blank">payment terms</a> <?php }
                                        if($getstaticpagedetails[0]['terms_condition_data'] == "1" && $getstaticpagedetails[0]['privacy_policy_data'] == "1"){ echo " and "; }
                                        if($getstaticpagedetails[0]['privacy_policy_data'] == "1"){ ?> <a href="<?= SITEURL;?>/privacy-policy" target="_blank"> privacy policy</a><?php } ?></p>
                                        <?php } ?>
                                    </div>
                                    <div class="login-link-container">New Member? <a href="<?=SITEURL; ?>/register">Create Account</a></div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" >
    <div class="sellerpop modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header px-4">
                <h4 class="modal-title">Password Recovery</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body py-4 px-4">
                <div class="forgetpass_msg"></div>
                <div class="forgotPassword">
                    <p>Provide us the Email ID of your account and we will send you an Email with instructions to reset your password.</p>
                    <form id="formforgetpassword">
                        <div class="form-group">
                            <input type="text" class="form-control" id="mailrecover" placeholder="Enter Email ID"  onblur="mailchk('mailrecover')" required>
                            <div class="invalid-tooltip">Please provide a valid Email</div>
                        </div>
                        <button type="button" class="btn btn-primary" id="forget_password_button">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include "footer.php" ?>
<script src="<?php echo SITEURL?>/js/validation.js"></script>
<script>
$("#user_login").click(function(event){
    var form = $("#formLogin");
    if (form[0].checkValidity() === false){
        event.preventDefault()
        event.stopPropagation()
    }
    form.addClass('was-validated');
});

function login_to_account(){
    var loginu = $("#user_email").val();
    var loginp = $("#user_password").val();
    var referrer = document.referrer;
    if((loginu == "") || (loginp =="")){
        $("#formLogin").addClass('was-validated');
    }
    else{
        $("#login").attr("disabled",true).html("Processing...");
        var info1 = {loginu:loginu,loginp:loginp,action:"loginwithoutOTP"};
        $.ajax({
            type: "POST",
            url: "<?=SITEURL; ?>/ajax/login_ajax.php",
            data: info1,
            success: function(response){
                $("#login").attr("disabled",false).html("Log In");
                response = $.trim(response);
                if(response == 0){
                    $(".msgsl").fadeIn().html("Invalid Credential,try Again").addClass("alert alert-danger").delay(3000).fadeOut();
                }
                else if(response == 3){
                    $(".msgsl").fadeIn().html("You have not activated your account ,Please check your registered email for activation link").addClass("alert alert-danger").delay(3000).fadeOut();
                }
                else if(response == 2){
                    $(".msgsl").fadeIn().html("Your account is deactivated by admin please contact with admin ").addClass("alert alert-danger").delay(3000).fadeOut();
                }
                else if(response == 1){
                    $(".msgsl").fadeIn().html("Successfully Login").removeClass("alert alert-danger").addClass("alert alert-success").delay(3000).fadeOut();
                    setTimeout(function(){
                        window.location.href = referrer;
                        window.location.href = "<?=SITEURL; ?>";
                    },2000);
                }
            }
        });
    }
}

function validete(){
    var user = $("#user_email").val();
    var pwd = $("#user_password").val();
    if(user!=""&&pwd!=""){
        var info = {user:user,pwd:pwd,action:"uservalidate"};
        var info1 = {username:user,logkey:pwd,action:"getotpform"};
        $.ajax({
            type:"POST",
            url:"<?=SITEURL; ?>/ajax/login_ajax.php",
            data:info,
            success:function(response){
                if(response==1){
                    $.ajax({
                        type:"POST",
                        url:"<?=SITEURL; ?>/ajax/login_ajax.php",
                        data:info1,
                        success:function(response){ $("#all_login").replaceWith(response); }
                    });
                } else{
                    $(".msgsl").html("Invalid User Details").addClass("alert alert-danger").delay(3000).fadeOut();
                }
            }
        });
    }
}
  
function resendotp(val1){
    var info = {mob:val1,action:"resendotp"};
    $(".resendloader").html("Please Wait.. Resending..");
    $.ajax({
        type:"POST",
        url:"<?=SITEURL; ?>/ajax/login_ajax.php",
        data:info,
        success:function(response){
            $(".resendloader").html('<button class="btn btn-link resendbtn" onclick="resendotp('+val1+')"> Resend Now </button>');
            if(response==1){ } else{ }
        }
    });
}
    
function verifyotp(){
    var user = $("#user").val();
    var pwd = $("#password").val();
    var otp = $("#otp").val();
    if(otp != "") {
        var info = {user:user,pwd1:pwd,otp:otp,action:"verifyotp"};
        $.ajax({
            type:"POST",
            url:"<?=SITEURL; ?>/ajax/login_ajax.php",
            data:info,
            success:function(response){
                response = $.trim(response)
                if(response==1){
                    $(".msgsl").fadeIn().html("OTP Expired").addClass("alert alert-danger").delay(3000).fadeOut();
                } else if(response==0){
                    $(".msgsl").fadeIn().html("Invalid OTP").addClass("alert alert-danger").delay(3000).fadeOut();
                } else if(response==2){
                    $(".msgsl").fadeIn().html("Invalid Details").addClass("alert alert-danger").delay(3000).fadeOut();
                } else{
                    window.location.href = "<?=SITEURL; ?>";
                }
            }
        });
    }  else{
        $(".msgsl").fadeIn().html("Please Enter OTP").addClass("alert alert-danger").delay(3000).fadeOut();
    }  
}

function logincancel(){
    var info={action:"logincancel"};
    $.ajax({
        type:"POST",
        url:"<?=SITEURL; ?>/ajax/login_ajax.php",
        data:info,
        success:function(response){
            location.href="memberlogin.php";
        }
    });
}
$("#forget_password_button").on('click', function(){
    var form = $("#formforgetpassword")
    if(form[0].checkValidity() === false){
        event.preventDefault()
        event.stopPropagation()
    }
    form.addClass('was-validated')
    var email1 = $("#mailrecover").val();
    if(email1 == ""){
        $("#mailrecover").addClass("is-invalid");
    } else{
       $("#forget_password_button").html('Submitting...').attr("disabled",true);
        var info = {email1: email1,action:"forgetpassword"};
            $.ajax({
                type: "POST",
                url: "<?=SITEURL; ?>/ajax/user_ajax.php",
                data: info,
                success: function(response){
                    $("#forget_password_button").html('Submit').attr("disabled",false);
                      if(response == 3){
                        $(".forgetpass_msg").fadeIn().html("Please try After Some Time").addClass("alert alert-danger").delay(2000).fadeOut();
                    }
                    else {
                        $("#formforgetpassword")[0].reset();
                        $(".forgotPassword").hide();
                        $(".forgetpass_msg").fadeIn().html("If you are registered verified user you will receive e-mail").removeClass("alert alert-danger").addClass("alert alert-success");
                    }
                }
            })
        } 
    });
    $("input").keypress(function(){
    $(this).removeClass("is-invalid");
});
</script>   
</body>
</html> 