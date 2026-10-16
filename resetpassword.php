<?php include("includes/configuration.php");?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Reset Password : <?php echo SITE_TITLE; ?></title>
    <meta name="description" content="<?php echo METADESCRIPTION; ?>">
    <meta name="keywords" content="<?php echo METAKEYWORDS; ?>">
     <!--Open Graph / Facebook-->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>">
    <meta property="og:title" content="Reset Password : <?php echo SITE_TITLE; ?>">
    <meta property="og:description" content="<?php echo METADESCRIPTION; ?>">
    <meta property="og:image" content="<?=(OGIMAGE!=""?SITEURL."/img/ogimage/".OGIMAGE:SITEURL."/img/projectimage/".(LOGO != ""?LOGO:"default_logo.png")); ?>"> 
    <!--Twitter-->
    <meta property="twitter:card" content="<?=(OGIMAGE!=""?SITEURL."/img/ogimage/".OGIMAGE:SITEURL."/img/projectimage/".(LOGO != ""?LOGO:"default_logo.png")); ?>">
    <meta property="twitter:url" content="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>">
    <meta property="twitter:title" content="Reset Password : <?php echo SITE_TITLE; ?>">
    <meta property="twitter:description" content="<?php echo METADESCRIPTION; ?>">
    <meta property="twitter:image" content="<?=(OGIMAGE!=""?SITEURL."/img/ogimage/".OGIMAGE:SITEURL."/img/projectimage/".(LOGO != ""?LOGO:"default_logo.png")); ?>">
    <link rel="canonical" href="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" >
    <?php include "commoncss.php" ?>
</head>
<body class="bg-light">
<div class="main-wrap">     
    <?php include "menu.php" ?>
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3 changePassword">
                    <div class="card p-4 card border-0 cc-shadow-2">
                        <h5 class="mb-2">Change Password</h5>
                        <div class="pwdmsg"></div>
                        <form id="password_update_form" class="needs-validation">                                
                            <div class="row">
                                <div class="col-sm-12 col-md-12">
                                    <div class="form-group row mb-2">
                                        <label class="col-md-12 col-form-label">New Password</label>
                                        <div class="col-md-12 position-relative">
                                            <input type="password" id="password" class="form-control" placeholder="New Password" required/>
                                            <div class="invalid-tooltip" id="password_tooltip">Please Enter Valid New Password</div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-12 col-form-label">Confirm New Password</label>
                                        <div class="col-md-12 position-relative">
                                            <input type="password" id="password1" class="form-control" placeholder="Confirm New Password" required/>
                                            <div class="invalid-tooltip" id="password1_tool">Please Enter Valid New Password</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <table class="passvalidation">
                                        <tr><td><span id="min" class="mr-1"></span></td><td>&nbsp;Min. 6 characters</td></tr>
                                        <tr><td><span id="digit" class="mr-1"></span></td><td>&nbsp;Min. one digit</td></tr>
                                        <tr><td><span id="specialchar" class="mr-1"></span></td><td>&nbsp;Min. one special char</td></tr>
                                        <tr class="h"><td><span id="sign" class="mr-1"></span></td><td id="chckpass"></td></tr>
                                    </table>
                                </div>
                            </div>
                            <input type="button" class="btn btn-primary chngepwd" value="Change Password"/>
                        </form>
                    </div>
                </div>
            </div>    
        </div>
    </div>
</div>
<?php include "footer.php" ?> 
<script>
wrongimg = '<img src=<?php echo SITEURL ?>/img/projectimage/wrong.png >';
green_check ='<img src=<?php echo SITEURL ?>/img/projectimage/GreenCheck.png >';
$('#password').keyup(function(){
    password = $("#password").val();       
    if(password.length < 6) { $('#min').html(wrongimg);} else { $('#min').html(green_check); }
    if( password.match(/([0-9])/)) { $('#digit').html(green_check); } else { $('#digit').html(wrongimg); }
    if(password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)){ $('#specialchar').html(green_check); } else { $('#specialchar').html(wrongimg);}
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
    } else{
        $('#sign').html(wrongimg);
        $('#chckpass').html("Password Doesnt Match");
    }
}); 
$(".chngepwd").click(function(){
    var uid = '<?php echo base64_decode($_REQUEST['uid']); ?>';
    var password = $("#password").val();
    var password1 = $("#password1").val();
    var patpass = new RegExp(/([!,%,&,@,#,$,^,*,?,_,~])/);
    var respass = patpass.test(password);
    var patpassnum = new RegExp(/([0-9])/);
    var respassnum = patpassnum.test(password);
    var form = $("#password_update_form")
    if (form[0].checkValidity() === false) {
        event.preventDefault();
        event.stopPropagation();
    }
    form.addClass('was-validated');
    if( (password == "") || ( password1 == "") || (respassnum == false) ||  (respass == false) || (password.length < 6) || (password != password1)){
        //$(".pwdmsg").fadeIn().addClass("alert alert-danger").html("Please fill all required  field correctly").delay(3000).fadeOut();
        if(respassnum == false || respass == false || password.length < 6){
            $("#password").addClass("is-invalid");
        } else {
            $("#password").removeClass("is-invalid");
        } if(password != password1){
            $("#password1").addClass("is-invalid");
        } else {
            $("#password1").removeClass("is-invalid");
        }
    } else{
        form.addClass('was-validated');
        $("#password1, #password").removeClass("is-invalid");
        $(".chngepwd").html('Processing...');
        var info={uid:uid,password:password,password1:password1,action:"recover_password" }
        $.ajax({
            type : "post",
            url : "<?php echo SITEURL; ?>/ajax/user_ajax.php",
            data : info,
            success : function(response){
                $(".chngepwd").html('Change Password');
                if(response == 2){
                    $("#password_update_form").hide();
                    $(".pwdmsg").fadeIn().removeClass("alert alert-danger").addClass("alert alert-success").html("Your Password is changed successfully").delay(3000).fadeOut();
                    setTimeout(function(){ location.href = siteurl+'/login'; }, 3000);
                } if(response == 3){
                    $(".pwdmsg").fadeIn().addClass("alert alert-danger").html("Some Problem Occurs").delay(3000).fadeOut();
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