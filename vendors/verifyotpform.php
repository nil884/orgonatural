<?php include("../includes/configuration.php");
$uname = $_REQUEST['username']; $pwd = $_REQUEST['logkey']; $getuser = selectQuery(VENDOR,"*","email='".$uname."' and password='".password_encrypt($pwd)."'");
if(count($getuser) == 1){ $mobile = $getuser[0]['personalcontactno']; } ?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <title><?php echo SITE_TITLE; ?> : Admin OTP Verification</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?=SITEURL;?>/css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?=SITEURL;?>/css/backend/loginpage.css" />
    <link rel="stylesheet" href="<?=FONTFAMILY;?>" />
    <link rel="apple-touch-icon" sizes="57x57" href="<?=SITEURL;?>/img/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="<?=SITEURL;?>/img/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?=SITEURL;?>/img/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="<?=SITEURL;?>/img/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?=SITEURL;?>/img/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="<?=SITEURL;?>/img/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="<?=SITEURL;?>/img/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?=SITEURL;?>/img/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?=SITEURL;?>/img/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="<?=SITEURL;?>/img/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?=SITEURL;?>/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="<?=SITEURL;?>/img/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?=SITEURL;?>/img/favicon/favicon-16x16.png">
    <link rel="shortcut icon" type="image/x-icon" href="<?=SITEURL;?>/favicon.ico" />
    <link rel="manifest" href="<?=SITEURL;?>/img/favicon/manifest.json">
</head>
<body class="bg-light">
<div class="container">
    <div class="row">
        <div class="col-12 min-vh-100 d-flex flex-column justify-content-center">
            <div class="row">
                <div class="col-sm-9 col-md-7 col-lg-5 col-xl-4 mx-auto">                    
                    <div class="card rounded shadow shadow-sm shadow-lg mb-4 border-0 logform-body">
                        <header class="text-center mb-4"><img src="<?php echo SITEURL; ?>/img/projectimage/<?php if(LOGO != ""){ echo LOGO; } else{ echo "default_logo.png"; } ?>" alt="Logo" width="150" class="img-fluid logo"/></header>
                        <div class="mb-3">Verification Code Sent On Your Mobile Number <?php echo "********".substr($mobile,8,2); ?></div>
                        <div class="msgs"></div>
                        <div class="form-group"><input type="text" name="otp" id="otp" class="form-control" placeholder="Verification Code"/></div>
                        <input type="hidden" name="username" id="user" class="form-control" value="<?php echo $uname; ?>"/>
                        <input type="hidden" name="logkey" id="password" class="form-control" autocomplete="off" value="<?php echo $pwd; ?>"/>
                        <div class="mb-3">
                            <span>Not Getting?</span>
                            <span class="resendloader cc-cursor-pointer text-info resendbtn float-right" onclick="resendotp('<?php echo $mobile; ?>')">Resend Now</span
                        ></div>
                        <div class="form-group"><input type="submit" class="btn btn-primary btn-block" name="login" value="Validate And Login" onclick="verifyotp();"/></div>
                        <input type="button" class="btn btn-danger btn-block" name="back" value="Cancel" onclick="logincancel();"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo SITEURL; ?>/js/jquery.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/bootstrap.min.js"></script>
<script>
function resendotp(val1){
    var user = $("#user").val(); var pwd = $("#password").val();
    var info = {mob:val1,user:user,pwd1:pwd,action:"resendotp"};
    $(".resendloader").html("Please Wait.. Resending..");
    $.ajax({
        type:"POST",
        url:"ajax/login_ajax.php",
        data:info,
        success:function(response){
            $(".resendloader").html('<button class="btn btn-link resendbtn" onclick="resendotp('+val1+')"> Resend Now </button>');
            if(response==1){ } else{ }
        }
    })
}
function logincancel(){
    var info = {action:"logincancel"};
    $.ajax({
        type:"POST",
        url:"ajax/login_ajax.php",
        data:info,
        success:function(response){ location.href="index.php"; }
    });
}
function verifyotp(){
    var user = $("#user").val(); var pwd = $("#password").val(); var otp = $("#otp").val();
    if(otp != ""){
        var info = {user:user, pwd1:pwd, otp:otp, action:"verifyotp"};
        $.ajax({
            type:"POST",
            url:"ajax/login_ajax.php",
            data:info,
            success:function(response){
                response = $.trim(response);
                if(response==1){
                    $(".msgs").fadeIn().html("OTP Expired").addClass("alert alert-danger").delay(3000).fadeOut();
                } else if(response==0){
                    $(".msgs").fadeIn().html("Invalid OTP").addClass("alert alert-danger").delay(3000).fadeOut();
                } else if(response==2){
                    $(".msgs").fadeIn().html("Please try after Some Time").addClass("alert alert-danger").delay(3000).fadeOut();
                } else{ location.href=response; }
            }
        })
    } else { $(".msgs").fadeIn().html("Please Enter OTP").addClass("alert alert-danger").delay(3000).fadeOut(); }   
}
$('input[type="text"],input[type="password"]').val("");
</script>
</body>
</html>