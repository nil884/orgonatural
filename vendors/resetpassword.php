<? include("../includes/configuration.php");?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo SITE_TITLE; ?> : Reset Password</title>
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
        <div class="col-md-12 min-vh-100 d-flex flex-column justify-content-center">
            <div class="row">
                <div class="col-sm-9 col-md-7 col-lg-5 col-xl-4 mx-auto panel-login-form">
                    <div class="card border-0 p-4 shadow-lg">
                        <header class="text-center mb-4"><img src="<?php echo SITEURL; ?>/img/projectimage/<?php if(LOGO != ""){ echo LOGO; } else{ echo "default_logo.png"; } ?>" alt="Logo" width="150" class="img-fluid logo"/></header>
                        <p class="mb-2"><b>Hi <?php
                        $vendorid = $_REQUEST['vendorid'];
                        $decodeid = base64_decode($vendorid);
                        $get_vendor_name = selectQuery(VENDOR,"*","dealer_id='".$decodeid."'");
                        $u_name = $get_vendor_name[0]['dealer_name'];
                        echo ucfirst($u_name);?>,</b></p>
                        <p>We believe, you have requested for password reset, If you are accidentally bumped on this, please ignore this.</p>
                        <div class="pwdmsg"></div>
                        <form id="password_update_form">
                            <div class="form-group mb-2 position-relative">
                                <label>New Password</label>                               
                                <span class="cust-input-group border-right-0 rounded-left"><img src="<?php echo SITEURL; ?>/img/projectimage/icon-password.png" alt="icon-password" width="20" height="20"/></span>
                                <input type="password" id="password" class="form-control cust-group-padd" placeholder="New Password" required minlength="4"/>
                                <div class="invalid-tooltip" id="password_tooltip">Please Enter Valid New Password</div>
                            </div>
                            <div class="form-group mb-4 position-relative">
                                <label>Confirm New Password</label>    
                                <span class="cust-input-group border-right-0 rounded-left"><img src="<?php echo SITEURL; ?>/img/projectimage/icon-password.png" alt="icon-password" width="20" height="20"/></span>
                                <input type="password" id="password1" class="form-control cust-group-padd" placeholder="Confirm New Password" required minlength="4"/>
                                <div class="invalid-tooltip" id="password1_tool">Please Enter Valid New Password</div>
                            </div>
                            <input type="button" class="btn btn-primary btn-block chngepwd" value="Change Password"/>
                        </form>   
                    </div>              
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo SITEURL; ?>/js/jquery.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/bootstrap.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/validation.js"></script>
<script>
$("#password").focus();
$(".chngepwd").click(function(){
   var vendorid = '<?php echo base64_decode($_REQUEST['vendorid']); ?>';
   var password = $("#password").val();
   var password1 = $("#password1").val();
   var form = $("#password_update_form")
   if (form[0].checkValidity() === false){
       event.preventDefault();
       event.stopPropagation();
   }
   form.addClass('was-validated');
   if((password == "") || ( password1 == "") || (password != password1)){
       if(password == ""){ $("#password").addClass("is-invalid"); }
       else{ $("#password").removeClass("is-invalid"); }
       if(password != password1){ $("#password1").addClass("is-invalid"); }
       else{ $("#password1").removeClass("is-invalid"); }
   } else{
       form.addClass('was-validated');
       $("#password1, #password").removeClass("is-invalid");
       $(".chngepwd").html('Processing...');
       var info={vendorid:vendorid,password:password,password1:password1,action:"recover_password" }
       $.ajax({
           type : "post",
           url : "<?php echo VENDORURL; ?>/ajax/ajaxdata.php",
           data : info,
           success : function(response){
                $(".chngepwd").html('Change Password');
                if(response == 2){
                    $("#password_update_form").hide();
                    $(".pwdmsg").fadeIn().removeClass("alert alert-danger").addClass("alert alert-success").html("Your Password is changed successfully").delay(3000).fadeOut();
                    setTimeout(function(){ location.href = '<?php echo VENDORURL; ?>'; }, 3000);
                } if(response == 3){
                    $(".pwdmsg").fadeIn().addClass("alert alert-danger").html("Some Problem Occurs").delay(3000).fadeOut();
                }
           }
       })
   }
});
$("input").keypress(function(){ $(this).removeClass("is-invalid"); });
</script>
</body>
</html>