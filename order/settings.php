<?php include("../includes/configuration.php");

?>
<!doctype html>
<html lang='en'>
<head>
    <title>Authentication step - Login Settings</title>
    <?php include('commoncss.php') ?>
</head>
<body>
<div class="page-body-wrapper">
    <?php include('header.php') ?>
    <?php include('sidebar.php') ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center">
                    <div><h5 class="card-head-title">Login Settings</h5></div>
                    <div class="btn-actions-pane-right"><button class="btn btn-secondary btn-sm" onclick="goBack()"> Back</button></div>
                </div>
                <div class="card-body">
                    <div class="msgs"></div>
                    <div class="table-responsive">
                        <table class="table loginsetting">
                            <tr>
                                <td class="log-sett-step pl-0"><span class="mb-2">2 Step Authentication For Admin Panel</span> <br>
                                    <?php  $getadmin=selectQuery(USER,"*","utype='Admin'");   ?>
                                    <b>Admin No : <span class="adminmob"><?php if($getadmin[0]['u_mob']!=""){echo $getadmin[0]['u_mob'];}else { echo "[Not Defined]"; } ?></span>  </b>
                                    <!--<button type="button" class="btn btn-link viewchng"> <?php if($getadmin[0]['u_mob']!=""){echo "CHANGE";}else { echo "ADD"; } ?> </button>   -->
                                    <?php if($getadmin[0]['u_mob']!=""){}else { ?><button type="button" class="btn btn-primary viewchng"> ADD </button>  <? } ?>
                                </td>
                                <td>
                                    <input type="radio" name="adminlogin" class="adminlogin" value="1" <? if($getsetting[0]['admin_authentication']==1){echo "checked";} ?>/> Enable &nbsp;&nbsp;
                                    <input type="radio" name="adminlogin" class="adminlogin" value="0" <? if($getsetting[0]['admin_authentication']==0){echo "checked";} ?>/> Disable
                                </td>
                            </tr>
                            <tr class="changemob cc-display-none pl-0">
                                <td colspan="2" class="cc-padding-left-0 form-inline">
                                Enter Mobile <input type="text" name="mobile" id="mobile" class="mobile form-control" maxlength="10" value="<?php echo $getadmin[0]['u_mob']; ?>" onkeyup="numbercheck('mobile')"/>
                                <input type="button" class="btn btn-primary" value="Save" onclick="chngmob()"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="pl-0">2 Step Authentication For Seller Panel</td>
                                <td>
                                    <input type="radio" name="sellerlogin" class="sellerlogin" value="1" <? if($getsetting[0]['seller_authentication']==1){echo "checked";} ?>/> Enable &nbsp;&nbsp;
                                    <input type="radio" name="sellerlogin" class="sellerlogin" value="0" <? if($getsetting[0]['seller_authentication']==0){echo "checked";} ?>/> Disable
                                </td>
                            </tr>
                            <tr>
                                <td class="pl-0">2 Step Authentication For User Panel</td>
                                <td>
                                    <input type="radio" name="userlogin" class="userlogin" value="1" <? if($getsetting[0]['user_authentication']==1){echo "checked";} ?>/> Enable &nbsp;&nbsp;
                                    <input type="radio" name="userlogin" class="userlogin" value="0" <? if($getsetting[0]['user_authentication']==0){echo "checked";} ?>/> Disable
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div><button class="btn btn-primary" onclick="updatesetting()"> Update Setting </button></div>
               </div>
            </div>
        </div>
        <?php include('footer.php');?>
    </div>
</div>
<script>
    $(".viewchng").click(function(){
        $(".changemob").toggle();
    });
    function numbercheck(id){
        var inp=$("#"+id).val();
        if(isNaN(inp)){
            var newstr = inp.slice(0, -1);
            $("#"+id).val(newstr);
        }
    }
    function chngmob(){
        var mob=$("#mobile").val();
        var info={mob:mob,action:"updatemob"};
        $.ajax({
            type:"POST",
            url:"changeloginsettings.php",
            data:info,
            success:function(response){
                if(response==1){
                    $(".adminmob").html(mob);
                    $("#mobile").val(mob);
                    $(".changemob").hide();
                    $('.msgs').fadeIn().addClass("alert alert-success").html('Admin Mobile Updated Successfully').delay(3000).fadeOut();
                }
                else{
                    $('.msgs').fadeIn().addClass("alert alert-danger").html('Admin Mobile Not Updated').delay(3000).fadeOut();
                }
            }
        });
    }
    function updatesetting(){
        var admin=$(".adminlogin:checked").val();
        var seller=$(".sellerlogin:checked").val();
        var user=$(".userlogin:checked").val();
        var info={admin:admin,seller:seller,user:user,action:"updatesetting"};
        $.ajax({
            type:"POST",
            url:"changeloginsettings.php",
            data:info,
            success:function(response){
                if(response==1){
                    $('.msgs').fadeIn().addClass("alert alert-success").html('Settings Updated Successfully').delay(3000).fadeOut();
                }
                else if(response==0){
                    $('.msgs').fadeIn().addClass("alert alert-danger").html('Settings Not Updated').delay(3000).fadeOut();
                }
                else if(response==2){
                    $('.msgs').fadeIn().addClass("alert alert-danger").html('It Seems that Admin mobile number not available. Please add Admin mobile and then again chane settings').delay(3000).fadeOut();
                }
            }
        });
    }
</script>
</body>
</html>