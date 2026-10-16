<?php include("../includes/configuration.php");
    $get_config_details = selectQuery(CONFIG,"*","id= '1'");
    if($get_config_details[0]['admin_authentication']==0) {
    $callnext = "validate1()";$action="#";
} else{
    $action = "verifyotpform.php"; $callnext = "validate()";
} ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo SITENAME; ?> : Admin Login</title>
    <meta charset="UTF-8">
    <meta name="description" content="<?php echo METADESCRIPTION; ?>">
    <meta name="keywords" content="<?php echo METAKEYWORDS; ?>">
    <link rel="canonical" href="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?=SITEURL;?>/css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?=SITEURL;?>/css/backend/loginpage.css" />
    <link rel="stylesheet" href="<?=FONTFAMILY;?>" />
    <link rel="apple-touch-icon" sizes="57x57" href="<?=SITEURL;?>/img/favicon/apple-touch-icon-57x57.png"><link rel="apple-touch-icon" sizes="60x60" href="<?=SITEURL;?>/img/favicon/apple-touch-icon-60x60.png"><link rel="apple-touch-icon" sizes="72x72" href="<?=SITEURL;?>/img/favicon/apple-touch-icon-72x72.png"><link rel="apple-touch-icon" sizes="76x76" href="<?=SITEURL;?>/img/favicon/apple-touch-icon-76x76.png"><link rel="apple-touch-icon" sizes="114x114" href="<?=SITEURL;?>/img/favicon/apple-touch-icon-114x114.png"><link rel="apple-touch-icon" sizes="120x120" href="<?=SITEURL;?>/img/favicon/apple-touch-icon-120x120.png"><link rel="apple-touch-icon" sizes="144x144" href="<?=SITEURL;?>/img/favicon/apple-touch-icon-144x144.png"><link rel="apple-touch-icon" sizes="152x152" href="<?=SITEURL;?>/img/favicon/apple-touch-icon-152x152.png"><link rel="apple-touch-icon" sizes="180x180" href="<?=SITEURL;?>/img/favicon/apple-touch-icon-180x180.png"><link rel="icon" type="image/png" sizes="192x192"  href="<?=SITEURL;?>/img/favicon/android-chrome-192x192.png"><link rel="icon" type="image/png" sizes="32x32" href="<?=SITEURL;?>/img/favicon/favicon-32x32.png"><link rel="icon" type="image/png" sizes="96x96" href="<?=SITEURL;?>/img/favicon/favicon-96x96.png"><link rel="icon" type="image/png" sizes="16x16" href="<?=SITEURL;?>/img/favicon/favicon-16x16.png"><link rel="manifest" href="<?=SITEURL;?>/img/favicon/manifest.json">
</head>
<body class="bg-light">
<div class="container">
    <div class="row">
        <div class="col-md-12 min-vh-100 d-flex flex-column justify-content-center">
            <div class="row">
                <div class="col-lg-4 col-md-8 mx-auto panel-login-form">
                    <header class="text-center mb-4">
                        <a href="<?php echo SITEURL ?>" target="_blank"><img src="<?php echo SITEURL; ?>/img/projectimage/<?php if(LOGO != ""){ echo LOGO; } else{ echo "default_logo.png"; } ?>" alt="Logo" width="170" class="img-fluid logo"/></a>
                    </header>
                    <div class="card rounded shadow shadow-sm shadow-lg mb-3 border-0 logform-body">
                        <h2 class="mb-4 text-center">Admin Login</h2>
                        <div class="msgs"></div>
                        <form method="post" class="form" autocomplete="off" id="formLogin" action="<? echo $action ?>">
                            <div class="form-group position-relative">
                                <label>Username</label>
                                <span class="cust-input-group"><img src="<?php echo SITEURL; ?>/img/projectimage/icon-username.png" alt="icon-username"/></span>
                                <input type="text" class="form-control cust-group-padd" name="admin_username" id="admin_username" required="" placeholder="Enter Username">
                                <div class="invalid-feedback">Enter Your Username.</div>
                            </div>
                            <div class="form-group position-relative">
                                <label>Password</label>
                                <span class="cust-input-group border-right-0 rounded-left"><img src="<?php echo SITEURL; ?>/img/projectimage/icon-password.png" alt="icon-password"/></span>
                                <input type="password" class="form-control cust-group-padd" name="admin_password" id="admin_password" required="" placeholder="Enter Password">
                                <div class="invalid-feedback">Enter your password too!</div>
                            </div>
                            <div class="form-group custom-control custom-checkbox mb-4 cc-fs-13">
                                <input type="checkbox" class="custom-control-input" id="admshowpass" name="example1">
                                <label class="custom-control-label" for="admshowpass">Show Password</label>
                            </div>
                            <button type="button" class="btn btn-primary btn-block" id="btnLogin" onclick='<?php echo $callnext ?>'>LOGIN</button>
                        </form>
                    </div>
                    <footer class="text-center cc-fs-13">
                        <div class="mb-1">Copyright &copy; <?php echo date("Y"); ?> <?php echo SITENAME; ?></div>
                        <div>Website Intelligence By - <a href="https://www.surun.in/" target="_blank">Surun Infocore System</a></div>
                    </footer>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo SITEURL; ?>/js/jquery.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/bootstrap.min.js"></script>
<script>
$("#admin_password").on('keyup', function(event){
    if (event.keyCode == 13) {
        chk = "<?php echo $get_config_details[0]['admin_authentication']; ?>";
        if (chk == "0"){ validate1(); } else if(chk == "1"){ validate(); }
    }
});
function checkkey(event,nextcall){
    if (event.keyCode === 13) {
        if(nextcall=="validate1"){ validate1(); } else{ validate(); }
    }
}
$("#btnLogin").click(function(event){
    //Fetch form to apply custom Bootstrap validation
    var form = $("#formLogin")
    if (form[0].checkValidity() === false) {
        event.preventDefault()
        event.stopPropagation()
    }
    form.addClass('was-validated');
});
function validate1(){
    var user = $("#admin_username").val();
    var pwd = $("#admin_password").val();
    if (user != "" && pwd != ""){
        var info = {
            user: user, pwd: pwd, action: "uservalidatewithoutotp"
        };
        $.ajax({
            type: "POST",
            url: "ajax/login_ajax.php",
            data: info,
            success: function(response){
                if (response == 0) {
                    $(".msgs").fadeIn().html("Invalid details").addClass('alert alert-danger small').delay(3000).fadeOut();
                } else { location.href= response; }
            }
        });
    } 
}
function validate() {
    var user = $("#admin_username").val();
    var pwd = $("#admin_password").val();
    if (user != "" && pwd != "") {
        var info = {
            user: user,
            pwd: pwd,
            action: "uservalidate"
        };
        $.ajax({
            type: "POST",
            url: "ajax/login_ajax.php",
            data: info,
            success: function(response) {
                if (response == 1) { $("#formLogin").submit();
                } else {
                    $(".msgs").fadeIn().html("Invalid user details").addClass('alert alert-danger small').delay(3000).fadeOut();
                }
            }
        });
    } 
}
$(document).ready(function(){
    $('#admshowpass').on('change',function(){
        var isChecked = $(this).prop('checked');
        if (isChecked){ $('#admin_password').attr('type','text');}
        else { $('#admin_password').attr('type','Password'); }
    });
});
</script>
</body>
</html>