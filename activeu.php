<?php include("includes/configuration.php"); 
    $uid=base64_decode($_REQUEST['uid']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>User Activation : <?=SITE_TITLE ?></title>
    <meta name="description" content="<?php echo METADESCRIPTION; ?>">
    <meta name="keywords" content="<?php echo METAKEYWORDS; ?>">
    <link rel="canonical" href="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" >
    <?php include "commoncss.php" ?>
</head> 
<body class="bg-light">
<div class="main-wrap">     
    <?php include "menu.php" ?>
    <div class="content">
        <div class="container"> 
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="card border-0 cc-shadow-2">
                        <div class="card-body p-4 text-center">
                            <?php $user=selectQuery(BUYER,"*","u_id=".$uid."");
                            if(count($user)==1) {
                            $email_verified =$user[0]['email_verified'];
                            if($email_verified == 1){ ?>
                            <div class="thanku_register">
                                <img src="<?php echo SITEURL; ?>/img/projectimage/logo.png" alt="Logo" width="150" class="mb-4"/>
                                <h4>Welcome to the family...!</h4>
                                <p class="cc-lead-1 text-muted">Your Email is already verified. please contact with admin.If you have any queris <a href="<?php echo SITEURL ?>" hreflang="en">Click here</a>to go on home page home or wait for 5 seconds we will redirect you automatically.</p>
                            </div>
                            <?php } else {
                            $data = array( "email_verified"=>1, "isActive" =>1);
                            $query = updateQuery(BUYER,$data,"u_id=".$uid);
                           if($query){ ?>
                            <img src="<?php echo SITEURL; ?>/img/projectimage/logo.png" alt="Logo" width="150" class="mb-3"/>
                            <div class="thanku_register"><h2>Welcome to the family...!</h2> Thank you for registering <br>
                            <p class="cc-lead-1 text-muted">Your email address is verified successfully <a href="<?php echo  SITEURL?>" hreflang="en">  Click here </a> to go on home page home or wait for 5 seconds we will redirect you automatically.</p></div>
                            <?php } else{ ?>
                            <img src="<?php echo SITEURL; ?>/img/projectimage/logo.png" alt="Logo" width="150" class="mb-3"/>
                            <div class="thanku_register"><h2>Welcome to the family...!</h2>
                                <p class="cc-lead-1 text-muted">Some Problem occurs while validating your account please contact with admin. <a href="<?php echo  SITEURL?>" hreflang="en"> Click here </a>to go on home page home or wait for 5 seconds we will redirect you automatically.</p>
                            </div>
                            <?php } } } else {?>
                            <img src="<?php echo SITEURL; ?>/img/projectimage/logo.png" alt="Logo" width="150" class="mb-3"/>
                            <div class="thanku_register"><h2>Welcome to the family...!</h2>
                                <p class="cc-lead-1 text-muted">This activation link is Expired. <a href="<?php echo  SITEURL?>" hreflang="en">Click here</a>to go on home page home or wait for 5 seconds we will redirect you automatically.</p>
                            </div>
                            <?php } ?>
                            <div class="activation-count">
                                <span id="countdown" class="timer counter active-user-count d-inline-block border lead rounded-circle text-muted">5</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include "footer.php" ?>
<script>
expireAt = new Date;
expireAt.setMonth(expireAt.getMonth() - 1);
if (document.cookie != ""){
    crumbs = document.cookie.split(";");
    for(i=0; i < crumbs.length; i++){
        crumbName = crumbs[i].split("=")[0];
        document.cookie = crumbName + "=;expires=" + expireAt.toGMTString();
    }
}
</script>
<script>
function setCookie(cname,cvalue,exdays){
    var d = new Date();
    d.setTime(d.getTime()+(exdays*24*60*60*1000));
    var expires = "expires="+d.toGMTString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}
function getCookie(cname){
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++){
        var c = ca[i].trim();
        if (c.indexOf(name)==0) return c.substring(name.length,c.length);
    }
    return "";
}
cook=getCookie("my_cookie");
if(cook==""){
    var seconds = 5;
}else{
     seconds = cook;
     console.log(cook);
}
function secondPassed() {
    var minutes = Math.round((seconds - 30)/60);
    var remainingSeconds = seconds % 60;
    setCookie("my_cookie",seconds,5); //here 5 is expiry days
    document.getElementById('countdown').innerHTML = remainingSeconds +"" ;
    if (seconds == 0) {
     window.location.href = 'index.php';
        clearInterval(countdownTimer);
        //document.getElementById('countdown').innerHTML = "Buzz Buzz";
    } else {
        seconds--;
    }
}
var countdownTimer = setInterval(secondPassed, 1000);
</script>
</body>
</html> 