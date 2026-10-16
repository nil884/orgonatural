<?php  include("includes/configuration.php");
$actual_link = ($_SERVER['HTTPS'] == 'on'?"https://":"http://").$_SERVER[HTTP_HOST]."".$_SERVER[REQUEST_URI]; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Member Register : <?php echo SITE_TITLE; ?></title>
    <meta name="description" content="<?php echo METADESCRIPTION; ?>">
    <meta name="keywords" content="<?php echo METAKEYWORDS; ?>">
    <!--Open Graph / Facebook-->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>">
    <meta property="og:title" content="Member Register : <?php echo SITE_TITLE; ?>">
    <meta property="og:description" content="<?php echo METADESCRIPTION; ?>">
    <meta property="og:image" content="<?=(OGIMAGE!=""?SITEURL."/img/ogimage/".OGIMAGE:SITEURL."/img/projectimage/".(LOGO != ""?LOGO:"default_logo.png")); ?>"> 
    <!--Twitter-->
    <meta property="twitter:card" content="<?=(OGIMAGE!=""?SITEURL."/img/ogimage/".OGIMAGE:SITEURL."/img/projectimage/".(LOGO != ""?LOGO:"default_logo.png")); ?>">
    <meta property="twitter:url" content="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>">
    <meta property="twitter:title" content="Member Register : <?php echo SITE_TITLE; ?>">
    <meta property="twitter:description" content="<?php echo METADESCRIPTION; ?>">
    <meta property="twitter:image" content="<?=(OGIMAGE!=""?SITEURL."/img/ogimage/".OGIMAGE:SITEURL."/img/projectimage/".(LOGO != ""?LOGO:"default_logo.png")); ?>">
    <link rel="canonical" href="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" >
    <?php include "commoncss.php" ?>
</head>
<body class="bg-light">
<div class="main-wrap">     
    <?php include "menu.php";?>
    <div class="container"> 
        <div class="row justify-content-center">
            <div class="col-lg-7 memb-login-body">             
                <div class="card border-0 cc-shadow-2 new-user-log">
                    <h2 class="mb-4 cc-fw-4 h5">Create new customer account</h2>
                    <div id="successfullreg" class="alert alert-success cc-display-none h6">Warm welcome to the family. Thank you for registration !! Please check your email for activation link.</div>
                    <div id="user_reg">
                        <form action="#" id="formregister">
                            <div class="row">
                                <div class="col-md-6">
                                    <p>Personal details</p>
                                    <div class="row">
                                        <div class="col-sm-6 col-md-12">
                                            <div class="form-group"><?php $key=rand(0000,9999); $_SESSION['key']=$key; ?><input type="hidden" name="key" id="key" value="<?php echo $_SESSION['key'];?>" /><input type="text" class="form-control form-control-lg" placeholder="First name" required id="name" onkeyup="charcheck('name')">   </div>
                                        </div>
                                        <div class="col-sm-6 col-md-12">
                                            <div class="form-group"><input type="text" class="form-control form-control-lg" placeholder="Last name" required id="lname" onkeyup="charcheck('lname')"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="mem-log-fem" name="gender" value="Female">
                                            <label class="custom-control-label" for="mem-log-fem">Female</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="mem-log-mal" name="gender" value="Male">
                                            <label class="custom-control-label" for="mem-log-mal">Male</label>
                                        </div>         
                                    </div>
                                    <div class="form-group">                            
                                        <input type="email" class="form-control form-control-lg" placeholder="Email ID" required id="user_email" onblur="mailchk('user_email')">
                                    </div>
                                    <div class="form-group">                            
                                        <input type="text" class="form-control form-control-lg" placeholder="Mobile No" required id="user_mobile" onkeyup="mobnumbercheck('user_mobile')" maxlength="10">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <p>Your password</p>
                                    <div class="form-group"><input type="password" id="password" class="form-control form-control-lg" placeholder="Password" required></div>
                                    <div class="form-group"><input type="password" id="password1" class="form-control form-control-lg" placeholder="Repeat password" required></div>
                                    <div class="form-group">
                                        <table class="passvalidation"><tbody><tr><td><span id="min"></span></td><td>Min. 6 characters</td></tr><tr><td><span id="digit"></span></td><td>Min. one digit</td></tr><tr><td><span id="specialchar"></span></td><td>Min. one special character</td></tr><tr><td><span id="sign"></span></td><td id="chckpass"></td></tr></tbody></table>        
                                    </div>
                                </div>
                            </div>
                            <? $getstaticpagedetails = selectQuery(STATIC_PAGE,"terms_condition_data,privacy_policy_data","id= 1");  ?>
                            <?php if($getstaticpagedetails[0]['terms_condition_data'] == "" && $getstaticpagedetails[0]['privacy_policy_data'] == ""){ } else{ ?>
                            <p>By clicking register, I agree to terms including the <?php if($getstaticpagedetails[0]['terms_condition_data'] != ""){ ?>
                            <a href="<? echo SITEURL ?>/terms-condition" target="_blank" hreflang="en">payment terms </a><?php } ?>
                            <?php if($getstaticpagedetails[0]['terms_condition_data'] != "" && strip_tags($getstaticpagedetails[0]['privacy_policy_data']) != ""){ echo 'and ';} ?>
                            <?php if($getstaticpagedetails[0]['privacy_policy_data'] != ""){ ?>
                            <a href="<?php echo SITEURL;?>/privacy-policy" target="_blank" hreflang="en">privacy policy</a>
                            <?php } ?></p>
                            <?php } ?>
                            <div id="msgs1"></div>
                            <button type="button" class="btn btn-primary" id="user_registerbt"  onclick="user_register()">Register</button>
                        </form> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include "footer.php" ?>
<script src="<?php echo SITEURL?>/js/validation.js"></script>
<script>
$("#user_registerbt").click(function(event) {
    //Fetch form to apply custom Bootstrap validation
    var form = $("#formregister")
    if(form[0].checkValidity() === false){
        event.preventDefault()
        event.stopPropagation()
    }
    form.addClass('was-validated');
});
var password = $('#password').val();
wrongimg = '<img src=<?php echo SITEURL ?>/img/projectimage/wrong.png >';
green_check ='<img src=<?php echo SITEURL ?>/img/projectimage/GreenCheck.png >';
$('#password').keyup(function(){
    password = $("#password").val();       
    if(password.length < 6){ $('#min').html(wrongimg);} else{ $('#min').html(green_check); }
    if( password.match(/([0-9])/)){ $('#digit').html(green_check); } else{ $('#digit').html(wrongimg); }
    if(password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)){ $('#specialchar').html(green_check); } else{ $('#specialchar').html(wrongimg);}
});  
$('#password1').on('click',function(){
var mainpass =  $('#password').val();
    if(mainpass == ""){
        $(".msgs1").fadeIn().html("Please Fill main password field").addClass("alert alert-danger").delay(3000).fadeOut();
        $('#password1').val("")
    }
});
$('#password1, #password').on('keyup',function(){
    var mainpass = $('#password').val();
    var conpass = $('#password1').val();
    if(mainpass == conpass){
        $('#sign').html(green_check);
        $('#chckpass').html("Password Matched");
    } else {
        $('#sign').html(wrongimg);
        $('#chckpass').html("Password Doesnt Match");
    }
});       
function user_register(){
    var key = $("#key").val(); var name = $('#name').val(); var lname = $('#lname').val(); var gender = $("input[name='gender']:checked").val(); var mail1 = $('#user_email').val(); var mob = $('#user_mobile').val(); var mainpass = $('#password').val(); var conpass = $('#password1').val(); var patpass= new RegExp(/([!,%,&,@,#,$,^,*,?,_,~])/); var pass_res = patpass.test(mainpass); var patpassnum = new RegExp(/([0-9])/); var respassnum = patpassnum.test(mainpass);
    if(name == ""){
        $('#msgs1').fadeIn().html("Please Enter First Name").addClass("alert alert-danger").delay(3000).fadeOut();
    } else if(lname == ""){
        $('#msgs1').fadeIn().html("Please Enter Last Name").addClass("alert alert-danger").delay(3000).fadeOut();
    } else if((gender == "") || (gender == undefined)){
        $('#msgs1').fadeIn().html("Please Select Gender").addClass("alert alert-danger").delay(3000).fadeOut();
    } else if((mail1 == "") ) {
        $('#msgs1').fadeIn().html("Please Enter Correct Email Adress").addClass("alert alert-danger").delay(3000).fadeOut();
    } else if( (mob == "") || (mob.length<10)) {
        $('#msgs1').fadeIn().html("Please Enter Correct Mobile No ").addClass("alert alert-danger").delay(3000).fadeOut();
    } else if((mainpass == "")||(mainpass.length < 6) || (pass_res == false ) || (respassnum  == false )){
        $('#msgs1').fadeIn().html("Please Enter Correct Passoword").addClass("alert alert-danger").delay(3000).fadeOut();
    } else if(mainpass != conpass){
        $('#msgs1').fadeIn().html("Please Fill Repeat Password field Correctly").addClass("alert alert-danger").delay(3000).fadeOut();
    } else{
        $("#user_registerbt").html("Processing...").attr("disabled",true);
        var info1 = { key:key,name:name,lname:lname,gender:gender,mail1:mail1,mob:mob,mainpass:mainpass,action:"insert_user"};
        $.ajax({
            type: "POST",
            url: "ajax/login_ajax.php",
            data: info1,
            success: function(response){
            $("#user_registerbt").html("Register").attr("disabled",false);
            if(response==1){
                $("#msgs1").fadeIn().html("This email adress already exist please register with other Email ").addClass("alert alert-danger").delay(3000).fadeOut();
            } if(response==3){
                $("#msgs1").fadeIn().html("Some problem occurs please try again").addClass("alert alert-danger").delay(3000).fadeOut();
            } if(response==0){
                $("#successfullreg").show();
                $("#user_reg").hide();
            }
        }
        });
    }
}
</script>
</body>
</html> 