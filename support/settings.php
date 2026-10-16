<?php include("../includes/configuration.php"); ?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Support Change Password</title>
    <?php include 'commoncss.php';?>
</head>
<body>
<div class="page-body-wrapper">
    <?php include('menu.php');
    $empid = $_SESSION['staff']; ?>
    <? include 'sidebar.php'; ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center">
                    <div><h5 class="card-head-title">Change Password</h5></div>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Enter Current Password</label>
                        <div class="col-sm-4">
                            <input type="text" id="crntpwd" name="crntpwd" class="form-control" onblur="checkpwd();" maxlength="30" placeholder="Enter Current Password">
                        </div>
                    </div>
                    <!--<div class="form-group">
                    <input type="text" id="username" name="username" class="form-control" onkeyup="checkusername();" placeholder="Enter Username" disabled>
                    </div>-->
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Enter Password</label>
                        <div class="col-sm-4">
                            <input type="text" id="password" name="password" class="form-control" onkeyup="checkpassword();" maxlength="30" placeholder="Enter Password" disabled="disabled">
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <label class="col-sm-2 col-form-label">Enter Confirm Password</label>
                        <div class="col-sm-4">
                            <input type="text" id="confpassword" name="confpassword" class="form-control" onkeyup="checkconfpassword();" maxlength="30" placeholder="Confirm Password" disabled="disabled">
                        </div>
                    </div>
                    <span id="errmsg"></span>
                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-primary" onclick="checksubmit();" id="savechanges">Save changes</button>
                </div>
            </div>
        </div>
        <?php include 'footer.php';?>
    </div>
</div>
<script>
function checkpassword(){
    var password = $("#password").val();
    if(password==""){
        $("#errmsg").html("Please Enter Password").css("color","red");
    } else if(password.length<3){
        $("#errmsg").html("Please Enter Atleat 3 characters").css("color","red");
    } else{
        $("#errmsg").html("");
    }
}
    
function checkconfpassword(){
    var confpassword = $("#confpassword").val();
    var password = $("#password").val();
    if(password==""){
        $("#errmsg").html("Please Enter Password").css("color","red");
    } else if(password.length<3){
        $("#errmsg").html("Please Enter Atleat 3 characters").css("color","red");
    } else if(confpassword==""){
        $("#errmsg").html("Please Enter Confirm Password").css("color","red");
    } else if(password!=confpassword){
        $("#errmsg").html("Password Not Matching").css("color","red");
    } else{
        $("#errmsg").html("");
    }
}
    
function checkpwd(){
    var currentpwd = $("#crntpwd").val();
    var empid = "<?php echo $empid; ?>";
    if(currentpwd!="") {
        $.ajax({
             url : "ajax/changelogin.php",
             type : "post",
             data : {action : "changelogin", currentpwd:currentpwd, empid:empid},
             success : function(res){
                if(res==1){
                    $("#password").prop('disabled', false);
                    $("#confpassword").prop('disabled', false);
                } else if(res==2){
                    $("#errmsg").html("Password Incorrect").css("color","red");
                }
             }
        });
    }
}
    
function checksubmit(){
    var password = $("#password").val();
    var confpassword = $("#confpassword").val();
    var empid = "<?php echo $empid; ?>";
    if(password==""){
        $("#errmsg").html("Please Enter Password").css("color","red");
    } else if(password.length<3){
        $("#errmsg").html("Please Enter Atleat 3 characters").css("color","red");
    } else if(confpassword==""){
        $("#errmsg").html("Please Enter Confirm Password").css("color","red");
    } else if(password!=confpassword){
        $("#errmsg").html("Password Not Matching").css("color","red");
    } else{
        $("#savechanges").html("Please wait...");
        $.ajax({
             url : "ajax/changelogin.php",
             type : "post",
             data : {action : "updatepassword", confpassword:confpassword, empid:empid},
             success : function(res){
                if(res==1){
                   $("#errmsg").html("Password Changed Successfully!!!").css("color","green");
                   location.reload();
                }
                else if(res==2){
                   $("#errmsg").html("Password Not Changed").css("color","red");
                }
             }
        });
    }
}
</script>
</body>
</html>