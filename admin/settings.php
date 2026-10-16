<?php include("../includes/configuration.php"); ?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Authentication Step</title>
    <?php include('commoncss.php') ?>
</head>
<body>
<div class="page-body-wrapper">
    <?php include('header.php') ?>
    <?php include('sidebar.php') ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2"><div><h2 class="card-head-title">Login Settings</h2></div><div class="btn-actions-pane-right"><button class="btn btn-secondary btn-sm" onclick="goBack()">Back</button></div></div>
                <div class="card-body pb-0">
                    <div class="msgs"></div>
                    <div class="loginsetting">
                        <div class="row mb-3">
                            <div class="log-sett-step col-sm-6 col-md-6 col-lg-5 col-xl-4">
                                <span class="mb-2">2 Step Authentication For Admin Panel</span><br>
                                <?php $getadmin=selectQuery(USER,"*","utype='Admin'"); ?>
                                <b>Admin No : <span class="adminmob"><?php if($getadmin[0]['u_mob']!=""){echo $getadmin[0]['u_mob'];}else { echo "[ Not Defined ]"; } ?></span></b>
                                <!--<button type="button" class="btn btn-link viewchng"> <?php if($getadmin[0]['u_mob']!=""){echo "CHANGE";}else { echo "ADD"; } ?> </button>   -->
                                <div><?php if($getadmin[0]['u_mob']!=""){}else{ ?><button type="button" class="btn btn-primary btn-sm px-3 mt-2 viewchng">ADD</button> <? } ?></div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-7 col-xl-4">
                                <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" name="adminlogin" id="ad-pan-auth-enb" class="adminlogin custom-control-input" value="1" <? if($getsetting[0]['admin_authentication']==1){echo "checked";} ?>/> <label class="custom-control-label" for="ad-pan-auth-enb">Enable</label></div>
                                <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" name="adminlogin" id="ad-pan-auth-dis" class="adminlogin custom-control-input" value="0" <? if($getsetting[0]['admin_authentication']==0){echo "checked";} ?>/><label class="custom-control-label" for="ad-pan-auth-dis">Disable</label></div>
                            </div>
                        </div>
                        <div class="row changemob cc-display-none mb-3">
                            <div class="col-md-12 col-lg-7 col-xl-6">
                            <div class="form-inline px-3 pt-2 border bg-light">
                            <label class="mr-2 mb-2">Enter Mobile</label> <input type="text" name="mobile" id="mobile" class="mobile form-control mr-2 mb-2" maxlength="10" value="<?php echo $getadmin[0]['u_mob']; ?>" onkeyup="numbercheck('mobile')"/> <input type="button" class="btn btn-primary mb-2" value="Save" onclick="chngmob()"/>
                            </div></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-6 col-md-6 col-lg-5 col-xl-4 mb-2">2 Step Authentication For Seller Panel</div>
                            <div class="col-sm-6 col-md-6 col-lg-7 col-xl-4">
                                <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" name="sellerlogin" id="seller-pan-auth-enb" class="sellerlogin custom-control-input" value="1" <? if($getsetting[0]['seller_authentication']==1){echo "checked";} ?>/><label class="custom-control-label" for="seller-pan-auth-enb">Enable</label></div>
                                <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" name="sellerlogin" id="seller-pan-auth-dis" class="sellerlogin custom-control-input" value="0" <? if($getsetting[0]['seller_authentication']==0){echo "checked";} ?>/><label class="custom-control-label" for="seller-pan-auth-dis">Disable</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-6 col-md-6 col-lg-5 col-xl-4 mb-2">2 Step Authentication For User Panel</div>
                            <div class="col-sm-6 col-md-6 col-lg-7 col-xl-4">
                                <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" name="userlogin" id="user-pan-auth-enb" class="userlogin custom-control-input" value="1" <? if($getsetting[0]['user_authentication']==1){echo "checked";} ?>/><label class="custom-control-label" for="user-pan-auth-enb">Enable</label></div>
                                <div class="custom-control custom-radio custom-control-inline"><input type="radio" name="userlogin" id="user-pan-auth-dis" class="userlogin custom-control-input" value="0" <? if($getsetting[0]['user_authentication']==0){echo "checked";} ?>/><label class="custom-control-label" for="user-pan-auth-dis">Disable</label></div>
                            </div>
                        </div>
                    </div>                    
                </div>
                <div class="card-footer py-2 text-right"><button class="btn btn-primary" onclick="updatesetting()">Update Setting </button></div>
            </div>
        </div>
        <?php include('footer.php');?>
    </div>
</div>
<script>
$(".viewchng").click(function(){ $(".changemob").slideToggle(); });
function numbercheck(id){
    var inp = $("#"+id).val();
    if(isNaN(inp)){
        var newstr = inp.slice(0, -1);
        $("#"+id).val(newstr);
    }
}
function chngmob(){
    var mob = $("#mobile").val(); var info = {mob:mob,action:"updatemob"};
    $.ajax({
        type:"POST",
        url:"changeloginsettings.php",
        data:info,
        success:function(response){
            if(response==1){
                $(".adminmob").html(mob);
                $("#mobile").val(mob);
                $(".changemob").hide();
                $('.msgs').fadeIn().addClass("alert alert-success").html('Admin mobile updated successfully').delay(3000).fadeOut();
            } else{
                $('.msgs').fadeIn().addClass("alert alert-danger").html('Admin mobile not updated').delay(3000).fadeOut();
            }
        }
    });
}
function updatesetting(){
    var admin = $(".adminlogin:checked").val(); var seller = $(".sellerlogin:checked").val(); var user = $(".userlogin:checked").val(); var info = {admin:admin,seller:seller,user:user,action:"updatesetting"};
    $.ajax({
        type:"POST",
        url:"changeloginsettings.php",
        data:info,
        success:function(response){
            if(response==1){
                $('.msgs').fadeIn().addClass("alert alert-success").html('Settings updated successfully').delay(3000).fadeOut();
            } else if(response==0){
                $('.msgs').fadeIn().addClass("alert alert-danger").html('Settings not updated').delay(3000).fadeOut();
            } else if(response==2){
                $('.msgs').fadeIn().addClass("alert alert-danger").html('It seems that admin mobile number not available. Please add admin mobile and then again chane settings').delay(3000).fadeOut();
            }
        }
    });
}
</script>
</body>
</html>