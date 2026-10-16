<?php
    include("../includes/configuration.php");
    $panel="support";
    $getdept=selectQuery(SUPPORTDEPT,"*","isDel='0' and isActive='1'");
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <title><?php echo SITENAME; ?> : Support</title>
    <meta charset="UTF-8">
    <meta name="description" content="<?php echo METADESCRIPTION; ?>">
    <meta name="keywords" content="<?php echo METAKEYWORDS; ?>">
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
    <script>
    history.pushState(null, null, 'index.php');
    window.addEventListener('popstate', function(event) {
        history.pushState(null, null, 'index.php');
    });
    function preventBack(){window.history.forward();}
    setTimeout("preventBack()", 0);
    window.onunload=function(){null};
    function noBack(){ window.history.forward()}
    noBack();
    window.onload = noBack;
    window.onpageshow = function(evt) { if (evt.persisted) noBack() }
    window.onunload = function() { void (0) }
    function back_block() {
        window.history.foward(-1)
    }
    </script>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-12 min-vh-100 d-flex flex-column justify-content-center">
            <div class="row">
                <div class="col-lg-4 col-md-8 mx-auto panel-login-form">
                <?php $getlogo=selectQuery(CONFIG,"*","id= 1");    ?>
                    <header class="text-center mb-4">
                        <img src="<?php echo SITEURL; ?>/img/projectimage/<?php if(LOGO != "") { echo LOGO; } else { echo "default_logo.png"; } ?>" alt="Logo" width="150" class="img-fluid logo"/>
                    </header>
                    <div class="card rounded shadow shadow-sm shadow-lg mb-4 border-0 logform-body">
                        <h2 class="mb-4 text-center">Support Login</h2>
                        <form method="post" class="form" autocomplete="off" id="formLogin" >
                            <div class="form-body">
                                <div class="msgs"></div>
                                <div class="form-group position-relative">
                                    <label>Username</label>
                                    <span class="cust-input-group">
                                        <img src="<?php echo SITEURL; ?>/img/projectimage/icon-username.png" alt="icon-username"/>
                                    </span>
                                    <input type="text"  name="username" class="form-control username cust-group-padd" placeholder="Enter Username"/>
                                    <div class="invalid-feedback">Enter Your Username.</div>
                                </div>
                                <div class="form-group position-relative">
                                    <label>Password</label>
                                    <span class="cust-input-group border-right-0 rounded-left">
                                        <img src="<?php echo SITEURL; ?>/img/projectimage/icon-password.png" alt="icon-password"/>
                                    </span>
                                    <input type="password" name="logkey" class="form-control logkey cust-group-padd"  placeholder="Enter Password" id="support-pass"/>
                                    <div class="invalid-feedback">Enter your password too!</div>
                                </div>
                                <div class="form-group custom-control custom-checkbox mb-4">
                                    <input type="checkbox" class="custom-control-input" id="admshowpass" name="example1">
                                    <label class="custom-control-label" for="admshowpass">Show Password</label>
                                </div>
                                <input type="button" class="btn btn-primary btn-block login" id="btnLogin" name="login" value="Login"/>
                            </div>
                       </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo SITEURL; ?>/js/jquery.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/bootstrap.min.js"></script>
<script>
 function login(){
    username=$(".username").val();
    logkey=$(".logkey").val();
    form_data={username:username,logkey:logkey}
    $.ajax({
        type:"POST",
        url:"login_check1.php",
        data:form_data,
        success:function(response){
            /*alert(response); */
            $(".login").attr("disabled",false);
            if(response){
               if(response==0){
                    $(".msgs").fadeIn().html("Invalid Details").addClass("alert alert-danger small").delay(3000).fadeOut();
                }
                else if(response==1){
                    $(".msgs").fadeIn().html("Insert Username And Password").addClass("alert alert-danger small").delay(3000).fadeOut();
                }
                else{ window.location=response;}
            }
        }
    });
}
$("#btnLogin").click(function(event) {
    var form = $("#formLogin")
    if (form[0].checkValidity() === false) {
        event.preventDefault()
        event.stopPropagation()
    }else{
        login();
    }
    form.addClass('was-validated');
});
$('input[type="text"],input[type="password"]').val("");
$(document).ready(function(){
    $('#admshowpass').on('change',function(){
        var isChecked = $(this).prop('checked');
        if (isChecked) {
            $('#support-pass').attr('type','text');
        } else {
            $('#support-pass').attr('type','Password');
        }
    });
});
</script>
</body>
</html>