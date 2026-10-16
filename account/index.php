<?php include("../includes/configuration.php");?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Account : <?php echo SITE_TITLE; ?></title>
    <meta name="description" content="<?php echo METADESCRIPTION; ?>">
    <meta name="keywords" content="<?php echo METAKEYWORDS; ?>">
    <link rel="canonical" href="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" >
    <?php include "../commoncss.php" ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/uploadImagepopup/cropper.css">
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/uploadImagepopup/main.css">
</head>
<body>
<div class="main-wrap"> 
<?php include "notification_account.php" ?>    
    <?php include "../menu.php" ?>
    <div class="container pt-3"><div class="row"><div class="col-12"><ul class="breadcrumb p-0 mb-0"><li class="breadcrumb-item"><a href="<?=SITEURL;?>">Home</a></li><li class="breadcrumb-item active">Profile</li></ul></div></div></div>
    <div class="content py-3">
        <div class="container">
            <div class="row">
                <?php include('sidebar.php')?>
                <div class="col-sm-12 col-md-12 col-lg-9 pl-md-0">  
                    <div class="card">
                        <div class="editProfile personal-info border-bottom">
                            <div class="col-sm-12 col-md-8 p-3">
                                <h6 class="mb-4 cc-fw-5">Personal Information</h6>
                                <form id="basic_details">
                                    <div class="acntmsg"></div>
                                    <input type="hidden" name="uid" id="uid" value="<?php echo $getbuyer_details[0]['u_id']; ?>" />
                                    <div class="form-group row position-relative">
                                        <label class="col-sm-4 col-md-4 col-form-label cc-mandatary-field">First Name</label>
                                        <div class="col-sm-8 col-md-8"><input type="text" name="u_fname" class="form-control" value="<?php echo $getbuyer_details[0]['u_fname'];?>" id="u_fname" required placeholder="Enter First Name"/>
                                        <div class="invalid-tooltip">Please Enter First Name</div></div>
                                    </div>
                                    <div class="form-group row position-relative">
                                        <label class="col-sm-4 col-md-4 col-form-label cc-mandatary-field">Last Name</label>
                                        <div class="col-sm-8 col-md-8"><input type="text" name="u_lname" class="form-control" value="<?php echo $getbuyer_details[0]['u_lname'];?>" id="u_lname" required placeholder="Enter Last Name"/>
                                        <div class="invalid-tooltip">Please Enter Last Name</div></div>
                                    </div>
                                    <div class="form-group row position-relative">
                                        <label class="col-sm-4 col-md-4 col-form-label cc-mandatary-field">Gender</label>
                                        <div class="col-sm-8 col-md-8 mt-1">
                                            <div class="custom-control custom-radio custom-control-inline"><input type="radio" class="custom-control-input" id="mem-log-fem" name="gender" value="Female" <?php if($getbuyer_details[0]['u_gender'] == "Female"){ echo "checked"; } ?> required>
                                            <label class="custom-control-label" for="mem-log-fem">Female</label></div>
                                            <div class="custom-control custom-radio custom-control-inline"><input type="radio" class="custom-control-input" id="mem-log-mal" name="gender" value="Male" <?php if($getbuyer_details[0]['u_gender'] == "Male"){ echo "checked"; } ?> required>
                                            <label class="custom-control-label" for="mem-log-mal">Male</label></div>     
                                            <div class="invalid-tooltip" id="gender_tooltip">Please Select Gender</div>
                                        </div>
                                    </div>
                                    <div class="form-group row position-relative">
                                        <label class="col-sm-4 col-md-4 col-form-label cc-mandatary-field">Contact Details</label>
                                        <div class="col-sm-8 col-md-8"><input type="tel" name="umbl" class="form-control" id="umbl" value="<?php echo $getbuyer_details[0]['u_mobile'];?>" placeholder="Enter Your Mobile No" maxlength="10" onkeyup="mobnumbercheck('umbl')" required/>
                                        <div class="invalid-tooltip" id="mobile_tooltip">Please Enter Valid Mobile No</div></div>
                                    </div>
                                    <div class="form-group row position-relative">
                                        <label class="col-sm-4 col-md-4 col-form-label cc-mandatary-field">Email</label>
                                        <div class="col-sm-8 col-md-8"><input type="text" name="uemail" class="form-control" id="uemail" value="<?php echo $getbuyer_details[0]['u_email'];?>" readonly/>
                                        <div class="invalid-tooltip" id="mobile_tooltip">Please Enter Valid Mobile No</div></div>
                                    </div>
                                    <div class="row">
                                        <label class="col-sm-4 col-md-4 mb-0"></label>
                                        <div class="col-sm-8 col-md-8"><input type="button" value="Save Changes" class="btn btn-primary edit"></div>
                                    </div>
                                </form>  
                            </div>
                        </div>
                        <div class="changePassword p-3 card border-0 cc-shadow-1">
                            <h6 class="mb-4 cc-fw-5">Change Password</h6>
                            <div class="pwdmsg"></div>
                            <form id="password_update_form" class="needs-validation">
                                <div class="row">
                                    <div class="col-sm-12 col-md-8">
                                        <div class="form-group row position-relative">
                                            <label class="col-sm-4 col-md-4 col-form-label cc-mandatary-field">Old Password</label>
                                            <div class="col-sm-8 col-md-8"><input type="password" id="oldpwd" class="form-control" placeholder=" Current Password" required/>
                                            <div class="invalid-tooltip">Please enter old password</div></div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-md-4 col-form-label cc-mandatary-field">New Password</label>
                                            <div class="col-sm-8 col-md-8 position-relative"><input type="password" id="password" class="form-control" placeholder="New Password" required/>
                                            <div class="invalid-tooltip" id="password_tooltip">Please enter valid new password</div></div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-md-4 col-form-label cc-mandatary-field">Confirm New Password</label>
                                            <div class="col-sm-8 col-md-8 position-relative"><input type="password" id="password1" class="form-control" placeholder="Confirm New Password" required/>
                                            <div class="invalid-tooltip" id="password1_tool">Please enter valid new password</div></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-8 offset-sm-4 col-md-4 offset-md-0">
                                        <table class="passvalidation">
                                            <tr><td><span id="min" class="mr-1"></span></td><td>&nbsp;Min. 6 characters</td></tr><tr><td><span id="digit" class="mr-1"></span></td><td>&nbsp;Min. one digit</td></tr><tr><td><span id="specialchar" class="mr-1"></span></td><td>&nbsp;Min. one special character</td></tr><tr class="h"><td><span id="sign" class="mr-1"></span></td><td id="chckpass"></td></tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                     <div class="col-sm-12 col-md-8">
                                        <div class="row">
                                            <label class="col-sm-4 col-md-4 mb-0"></label>
                                            <div class="col-sm-8 col-md-8"><input type="button" class="btn btn-primary chngepwd" value="Change Password"/></div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>   
            </div>
        </div>
    </div>
</div>
<?php include "../footer.php" ?> 
<?php include "profilejs.php" ?> 
<script>
wrongimg = '<img src=<?php echo SITEURL ?>/img/projectimage/wrong.png >';
green_check ='<img src=<?php echo SITEURL ?>/img/projectimage/GreenCheck.png >';
$('#password').keyup(function(){
    password = $("#password").val();       
    if (password.length < 6){ $('#min').html(wrongimg);} else{ $('#min').html(green_check); }
    if ( password.match(/([0-9])/)) { $('#digit').html(green_check); } else{ $('#digit').html(wrongimg); }
    if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)){ $('#specialchar').html(green_check); } else{ $('#specialchar').html(wrongimg);}
});   
$('#password1').on('click',function(){
    var mainpass =  $('#password').val();
    if(mainpass == ""){
        $(".msgs1").fadeIn().html("Please fill main password field").addClass("alert alert-danger").delay(3000).fadeOut();
        $('#password1').val("")
    }
}); 
$('#password1, #password').on('keyup',function(){
    var mainpass = $('#password').val();
    var conpass = $('#password1').val();
    if(mainpass == conpass){
        $('#sign').html(green_check);
        $('#chckpass').html("Password matched");
    } else{
        $('#sign').html(wrongimg);
        $('#chckpass').html("Password doesn't match");
    }
}); 
$(".edit").click(function(){
    var uid = $("#uid").val(); var uname = $("#u_fname").val(); var ulname = $("#u_lname").val(); var gender = $("input[name='gender']:checked").val(); var umbl = $("#umbl").val(); var form = $("#basic_details")
    if (form[0].checkValidity() === false){
        event.preventDefault()
        event.stopPropagation()
    }
    form.addClass('was-validated');
    if(uname == ""  || ulname == "" || gender == "" || gender == undefined || umbl == "" || umbl.length<10){ 
        if(gender == "" || gender == undefined){
            $("#gender_tooltip").show();
        } else{
            $("#gender_tooltip").hide();
        } if(umbl=="" || umbl.length<10){
            $("#umbl").addClass("is-invalid");
        } else{
            $("#umbl").removeClass("is-invalid");
        }
    } else{
        form.addClass('was-validated');
        $("#mobile_tooltip,#gender_tooltip").css("display","none");
        $('.acntmsg').html("");
        $(".edit").val("Processing...");
        var info = {uid:uid,uname:uname,ulname:ulname,gender:gender,umbl:umbl,action:"update_user_basic"};
        $.ajax({
            type : "post",
            url : "<?php echo SITEURL; ?>/ajax/common_ajax.php",
            data : info,
            success : function(response){
                $(".edit").val("Save Changes");
                if(response == 1){
                    $(".acntmsg").fadeIn().addClass("alert alert-success").html("Details updated successfully").delay(3000).fadeOut();
                    location.reload(); 
                } if(response == 0){
                    $(".acntmsg").fadeIn().addClass("alert alert-danger").html("Problem occurs while updating").delay(3000).fadeOut();
                }
            }
        });
    }
});
$(".chngepwd").click(function(){
    var uid = $("#uid").val(); var oldpwd = $("#oldpwd").val(); var password = $("#password").val(); var password1 = $("#password1").val(); var patpass = new RegExp(/([!,%,&,@,#,$,^,*,?,_,~])/); var respass = patpass.test(password); var patpassnum = new RegExp(/([0-9])/); var respassnum = patpassnum.test(password);
    var form = $("#password_update_form");
    if(form[0].checkValidity() === false){ event.preventDefault(); event.stopPropagation(); }
    form.addClass('was-validated');
    if((oldpwd == "") || (password == "") || ( password1 == "") || (respassnum == false) || (respass == false) || (password.length < 6) || (password != password1)){
        if(respassnum == false || respass == false || password.length < 6){ $("#password").addClass("is-invalid"); }
        else{ $("#password").removeClass("is-invalid"); } if(password != password1){ $("#password1").addClass("is-invalid"); } else{ $("#password1").removeClass("is-invalid"); }
    } else{ $(".chngepwd").html('Processing...');
        var info = {uid:uid,oldpwd:oldpwd,password:password,password1:password1,action:"change_password" }
        $.ajax({
            type : "post",
            url : "<?php echo SITEURL; ?>/ajax/common_ajax.php",
            data : info,
            success : function(response){
                $(".chngepwd").html('Change password');
                if(response == 0){ $("#oldpwd").addClass("is-invalid").next().html("Current password doesn't match."); } if(response == 2){ $(".pwdmsg").fadeIn().removeClass("alert alert-danger").addClass("alert alert-success").html("Your password is changed successfully").delay(3000).fadeOut(); location.reload(); } if(response == 3){ $(".pwdmsg").fadeIn().addClass("alert alert-danger").html("Some problem occurs").delay(3000).fadeOut(); }
            }
        })
    }
});
$("input").keypress(function(){ $(this).removeClass("is-invalid"); });
</script>
</body>
</html>