<?php include("../includes/configuration.php");
 $getstaticpagedetails=selectQuery(STATIC_PAGE,"vendor_terms_condition_data","id= 1");
?>
<!DOCTYPE html>
<html lang="en"> 
<head>
    <title><?php echo SITE_TITLE; ?> : Vendor Panel Login</title>
    <meta charset="UTF-8">
        <meta name="description" content="<?=METADESCRIPTION; ?>">
    <meta name="keyword" content="<?=METAKEYWORDS; ?>">
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
        <div class="col-md-12 min-vh-100 d-flex flex-column justify-content-center py-4">
            <div class="row">
                <div class="col-sm-9 col-md-7 col-lg-5 col-xl-4 mx-auto panel-login-form">
                    <header class="text-center mb-4"><a href="<?php echo SITEURL ?>" target="_blank"><img src="<?php echo SITEURL; ?>/img/projectimage/<?php if(LOGO != ""){ echo LOGO; }else{ echo "default_logo.png"; } ?>" alt="Logo" width="170" class="img-fluid logo"/></a></header>
                    <ul class="nav nav-tabs nav-justified lognavtab border-bottom-0 position-relative"><li class="nav-item"><a class="nav-link active border-0 px-4 text-dark" data-toggle="tab" href="#home">Login</a></li><?php if($getconfigdetails[0]['Multi_Seller'] == "ON"){ ?><li class="nav-item"><a class="nav-link border-0 px-4 text-dark" data-toggle="tab" href="#menu1">Register</a></li><?php } ?></ul>
                    <div class="tab-content card shadow-lg mb-3 border-0 logform-body">
                        <h2 class="text-center mb-4">Vendor Panel</h2>
                        <div id="home" class="tab-pane active">
                            <div class="alert_msgs1"></div>
                            <form id="formLogin" action ="<?php if($getconfigdetails[0]['vendor_authentication']==1 ){ echo 'verifyotpform.php'; }else{ echo '#'; } ?>" method="post">
                                <div class="form-group position-relative">
                                    <label>Username</label>
                                    <span class="cust-input-group"><img src="<?php echo SITEURL; ?>/img/projectimage/icon-username.png" alt="icon-username"/></span>
                                    <input type="text" name="username" placeholder="Email" id="luname" required class="form-control cust-group-padd" onblur="mailchk('luname')"/>
                                    <div class="invalid-feedback">Enter Your Email-Id</div>
                                </div>
                                <div class="form-group position-relative">
                                    <label>Password</label>
                                    <span class="cust-input-group border-right-0 rounded-left"><img src="<?php echo SITEURL; ?>/img/projectimage/icon-password.png" alt="icon-password"/></span>
                                    <input type="password" name="logkey" class="form-control cust-group-padd"   id="lpassword" required placeholder="Password"/>
                                    <div class="invalid-feedback">Enter your password too!</div>
                                </div>
                                <div class="form-group custom-control custom-checkbox mb-4 float-left cc-fs-13">
                                    <input type="checkbox" class="custom-control-input" id="vendshowpass" name="example1">
                                    <label class="custom-control-label" for="vendshowpass">Show Password</label>
                                </div>
                                <div class="form-group float-right cc-fs-13"><a href="#" data-toggle="modal" data-target="#myModal" class="text-right">Forgot Password?</a></div>
                                <div>
                                    <input type="button" class="btn btn-primary btn-block" name="login" value="LOGIN"  id="btnLogin" onclick="<?php if($getconfigdetails[0]['vendor_authentication']==0) { echo 'myFunction()'; } else { echo 'validete()'; } ?>"  />
                                </div>
                            </form>
                        </div>
                        <?php if($getconfigdetails[0]['Multi_Seller'] == "ON" ){ ?> 
                        <div id="menu1" class="tab-pane fade">
                            <div class="alert_msgs"></div>
                            <div class="msgsn"></div>
                            <form action="#" method="post" name="regform" id="dealerform">
                                <div class="registerForm">
                                    <div class="msgs"></div>
                                    <div class="form-group mb-2">
                                        <label class="cc-mandatary-field">Full Name</label>
                                        <input type="text" name="name1" id="name1" class="form-control" placeholder="Name" required />
                                        <div class="invalid-feedback">Please Enter name</div>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label class="cc-mandatary-field">Email ID</label>
                                        <input type="text" name="email" id="email" class="form-control" placeholder="Email Id" required onblur="mailchk('email')"/>
                                        <div class="invalid-feedback">Please Enter Email Adress</div>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label class="cc-mandatary-field">Contact</label>
                                        <input type="text" name="contactNop" id="contactNop" class="form-control" placeholder="Mobile No" minlength="10" maxlength="10" required  onkeyup="mobnumbercheck('contactNop')"/>
                                        <div class="invalid-feedback">Please Enter Contact No</div>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label class="cc-mandatary-field">Password</label>
                                        <input type="password" name="pwd" id="pwd" class="form-control" placeholder="Password" required minlength="4"/>
                                        <div class="invalid-feedback">Please Enter Password</div>
                                    </div>
                                    <div class="form-group">
                                        <label class="cc-mandatary-field">Confirm Password</label>
                                        <input type="password" name="cnfpwd" id="cnfpwd" class="form-control" placeholder="Retype Password" required minlength="4"/>
                                        <div class="invalid-feedback">Please Confirm Password</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                            <?php  if($getstaticpagedetails[0]['vendor_terms_condition_data'] == 1){ ?>
                                        By clicking Sign Up, you agree to our <a href="<?php echo SITEURL; ?>/vendor-terms-condition"  target="_blank"> Terms and Condition </a></div>
                                        
                                            <?php } ?>
                                        <button type="button" id="btnregisration" class="btn btn-primary btn-block"  onclick="insert_vendor()">Sign Up</button>
                                </div>
                            </form>
                        </div>
                        <?php } ?>
                    </div>
                    <footer class="text-center cc-fs-13"><div class="mb-1">Copyright &copy; <?php echo date("Y"); ?> <?php echo SITENAME; ?></div><div>Website Intelligence By - <a href="https://www.surun.in/" target="_blank">Surun Infocore System</a></div></footer>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog">
    <div class="sellerpop modal-dialog" role="document">
        <div class="modal-content">            
            <div class="modal-body p-4">
                <h4 class="modal-title mb-3">Password Recovery</h4><button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="forgetpass_msg"></div>
                <div class="forgotPassword">
                    <h6>Forgot Password?</h6>
                    <p class="text-muted">Provide a valid Email address, we will send you an email with instructions to reset your password.</p>
                    <form id="formforgetpassword">
                        <div class="form-group position-relative">
                            <input type="text" class="form-control" id="mailrecover" placeholder="Enter Email ID"  onblur="mailchk('mailrecover')" required>
                            <div class="invalid-tooltip">Please provide a valid Email</div>
                        </div>
                        <button type="button" class="btn btn-primary px-3" id="forget_password_button">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo SITEURL; ?>/js/jquery.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/bootstrap.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/validation.js"></script>
<script>
$("#lpassword").on('keyup', function(event){
    if (event.keyCode == 13){
        $("#btnLogin").trigger("click");
    }
});
$(document).ready(function(){$("#luname").focus();});  
$("#btnregisration").click(function(event){
    //Fetch form to apply custom Bootstrap validation
    var form = $("#dealerform")
    if (form[0].checkValidity() === false){
        event.preventDefault()
        event.stopPropagation()
    }
    form.addClass('was-validated'); 
});
$("#btnLogin").click(function(event){
    //Fetch form to apply custom Bootstrap validation
    var form = $("#formLogin")
    if (form[0].checkValidity() === false){
        event.preventDefault()
        event.stopPropagation()
    }
    form.addClass('was-validated');
});
function myFunction() {
    var luname = $("#luname").val();
    var lpassword = $("#lpassword").val();
    if(luname != "" && lpassword != ""){
        var formdata = {luname : luname, lpassword: lpassword,action:"uservalidatewithoutotp"};
        $.ajax({
            url : "ajax/login_ajax.php",
            type : "post",
            data : formdata,
            success: function(res){
                if(res==0){
                    $('.alert_msgs1').fadeIn().removeClass("alert alert-success small").addClass("alert alert-danger small").html("Invalid Details").delay(3000).fadeOut();
                } else{
                    window.location="<?php echo VENDORURL ?>home.php?s="+res;
                }
            }
        });
    }
}
function validete(){
    var user=$("#luname").val();
    var pwd=$("#lpassword").val();
    if(user!=""&&pwd!=""){
        var info={user:user,pwd:pwd,action:"uservalidate"};
        $.ajax({
            type:"POST",
            url:"ajax/login_ajax.php",
            data:info,
            success:function(response){
                if(response==1){  
                    $("#formLogin").submit();
                } else{
                    $('.alert_msgs1').fadeIn().removeClass("alert alert-success small").addClass("alert alert-danger small").html("Invalid Details").delay(3000).fadeOut();    
                }
            }
        });
    }
}
function insert_vendor(){
    var name1 = $("#name1").val();
    var email = $("#email").val();
    var contactNop = $("#contactNop").val();
    var patt1 = new RegExp(/^[7-9][0-9]{9}$/);
    var res2 = patt1.test(contactNop);
    var pwd = $("#pwd").val();
    var cnfpwd = $("#cnfpwd").val();
    if((name1== "") || (email =="") || (contactNop == "") || (contactNop.length != 10) || (res2== false) || (pwd == "") ||  (cnfpwd == "") || (pwd!=cnfpwd) ){
        $('.alert_msgs').fadeIn().removeClass("alert alert-success small").addClass("alert alert-danger small").html("Please Fill all Data Correctly").delay(3000).fadeOut();
        if(pwd !== cnfpwd){
            $("#cnfpwd").css("border-color","#dc3545");
            $("#cnfpwd").next().css("display","block");
        }
    } else {
        $("input[type=text]").css("border-color","#28a745");
        $("#cnfpwd").css("display","none");
        $("#btnregisration").html("Submitting...");
        var info1 = {name1:name1,email:email,contactNop:contactNop,pwd:pwd,action:"vendor_insert"};
        $.ajax({
            type: "POST",
            url: "ajax/ajaxdata.php",
            data: info1,
            success: function(response){
                $("#btnregisration").html("SUMBIT");
                if(response==0){
                    $('.alert_msgs').fadeIn().removeClass("alert alert-success small").addClass("alert alert-danger small").html("Error..Please Try After Some Time").delay(3000).fadeOut();
                } if(response==2){  
                    $('.alert_msgs').fadeIn().removeClass("alert alert-success small").addClass("alert alert-danger small").html("Email Already Exist").delay(3000).fadeOut();
                } else{
                    $("#dealerform").hide();
                    $('.msgsn').fadeIn().addClass("alert alert-success").html("Welcome to the family, Please Wait While Auto Login To Your Account");
                    setTimeout(function() {
                        window.location = "<?php echo VENDORURL; ?>home.php?s="+$.trim(response)
                    }, 1000);      
                }
            }
        });
    }   
} 
$(document).ready(function(){
    $('#vendshowpass').on('change',function(){
        var isChecked = $(this).prop('checked');
        if (isChecked){ $('#lpassword').attr('type','text'); } 
        else{ $('#lpassword').attr('type','Password'); }
    });
});
$("#forget_password_button").on('click', function(){
    var form = $("#formforgetpassword")
    if (form[0].checkValidity() === false){
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
            url: "<?=VENDORURL; ?>/ajax/ajaxdata.php",
            data: info,
            success: function(response) {
                $("#forget_password_button").html('Submit').attr("disabled",false);
                  if(response == 3){
                    $(".forgetpass_msg").fadeIn().html("Please try After Some Time").addClass("alert alert-danger").delay(2000).fadeOut();
                } else{
                    $("#formforgetpassword")[0].reset();
                    $(".forgotPassword").hide();
                    $(".forgetpass_msg").fadeIn().html("If you are registered Vendor you will receive e-mail").removeClass("alert alert-danger").addClass("alert alert-success");
                }
            }
        })
    } 
});
$("input").keypress(function(){ $(this).removeClass("is-invalid"); });
</script>
</body>
</html>