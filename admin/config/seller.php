<?php include("../../includes/configuration.php"); ?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Seller Details</title>
    <?php include('../commoncss.php') ?>
</head>
<body>
<div class="page-body-wrapper">
    <?php include('../header.php') ?>
    <?php include('../sidebar.php') ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card card-body">
                <div class="alert alert-info mb-0">
                    <h6><b>Important Note : </b></h6>
                    <ul class="mb-0 pl-3">
                        <li>Sell your own products or allow multiple vendors to register and sell their products or services using your website equal easily with intelligent Multi vendor or Single Vendor Switch</li>
                    </ul>
                </div>
            </div>
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Site Mode Details</h2></div></div>
                <form>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-10">
                                <div class="row">
                                    <label class="col-md-4 col-sm-4 col-lg-3 col-form-label pt-0 cc-mandatorystar">Site Mode</label>
                                    <div class="col-md-8 col-sm-8 col-lg-5">
                                        <div class="custom-control custom-radio custom-control-inline">
                                           <input type="radio" class="custom-control-input" id="multiSeller" name="Multi_Seller" value="ON" <? if($getconfigdetails[0]['Multi_Seller'] == "ON"){ echo "checked"; } ?>><label class="radio-inline custom-control-label" for="multiSeller"> Multi-Vendor</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                           <input type="radio" class="custom-control-input" id="singleSeller" name="Multi_Seller" value="OFF" <? if ($getconfigdetails[0]['Multi_Seller'] == "OFF"){ echo "checked"; } ?>><label class="radio-inline custom-control-label" for="singleSeller">Single-Vendor</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer py-2 text-right"><button type="button" name="create" id="submit" class="btn btn-primary">Save</button></div>
                </form>
            </div>
            <?php if ($getconfigdetails[0]['Multi_Seller'] == "ON"){ ?>
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Add Multi-Vendor Subscription Plan</h2></div></div>
                    <form id="plan_form">
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                    <label class="col-md-12 col-sm-4 col-lg-5 col-xl-5 col-form-label cc-mandatorystar">Plan Name</label>
                                    <div class="col-md-12 col-sm-8 col-lg-7 col-xl-7"><input type="text" name="plan_name" id="plan_name" class="form-control" placeholder="Plan Name" maxlength="50" /></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                    <label class="col-md-12 col-sm-4 col-lg-5 col-xl-5 col-form-label cc-mandatorystar">Plan Price</label>
                                    <div class="col-md-12 col-sm-8 col-lg-7 col-xl-7"><input type="text" name="plan_price" id="plan_price" class="form-control" placeholder="Plan Price" maxlength="4" onkeyup="numbercheck('plan_price')" /></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-md-12 col-sm-4 col-lg-5 col-xl-5 col-form-label cc-mandatorystar">Plan Duration In Days</label>
                                        <div class="col-md-12 col-sm-8 col-lg-7 col-xl-7"><input type="text" name="plan_duration" id="plan_duration" class="form-control" placeholder="Plan Duration" maxlength="54" onkeyup="numbercheck('plan_duration')" /></div>
                                    </div>
                                </div>                              
                            </div>
                        </div> 
                        <div class="card-footer py-2 text-right"><button type="button" name="create" id="submit1" class="btn btn-primary">Save</button></div>
                    </form>
                </div>
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Multi-Vendor Subscription Plan Details</h2></div></div>
                <div class="card-body">
                    <div class="table-responsive" id="plantable">
                        <table class="table table-bordered mb-0">
                            <tr><th>Plan Name</th><th>Plan Price</th><th>Plan Duration</th><th>Delete</th></tr>
                            <?php $planprice=selectQuery(VENDORPLAN,"*","isDel= '0' and isActive= '1' ");
                            for($p=0;$p<count($planprice);$p++){  ?>
                            <tr id="plan<?php echo $planprice[$p]['p_id'] ?>">
                                <td><?php echo  $planprice[$p]['plan_name'];  ?></td>
                                <td><?php echo $planprice[$p]['price']; ?></td>
                                <td><?php echo $planprice[$p]['plan_duration']; ?> Days</td>
                                <td class="del-btn-tr"><button type="button" onclick="delplan('<?=$planprice[$p]['plan_name']; ?>','<?=$planprice[$p]['p_id']; ?>')" class="deletebtn btn btn-danger btn-sm"><i class="fa fa-trash-o"></i></button>
                                <!--<a onclick="delplan('<?=$planprice[$p]['plan_name']; ?>','<?=$planprice[$p]['p_id']; ?>')" class="btn btn-default btn-xs" ><i class="fa fa-trash-o"></i></a></td>-->
                            </tr>
                            <?php } ?>
                        </table>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
        <?php include('../footer.php');?>
    </div>
</div>
<script src="<?php echo SITEURL ?>/js/validation.js"></script>
<script>
function delplan(planname,planid){ del_alertbox("Do you really want to delete this plan?", planname,"del_plan",planid); }
function del_plan(planname,planid){
    var info = {planname:planname,planid:planid,action:"del_plan"};
    $.ajax({
        type:"POST",
        url:"ajaxdata.php",
        data:info,
        success:function(response){
            if(response==1){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Plan deleted successfully").delay(3000).fadeOut();
                $("#plan"+planid).hide();
                $("#del_popup").modal("hide"); 
            } else if(response==2){
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("This plan is in use").delay(3000).fadeOut();
            } else{
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please try after some time").delay(3000).fadeOut();
            }
        }
    });
}

$(document).ready(function(){
    $("#submit1").on("click", function(){
        var plan_name = $("#plan_name").val();
        var plan_price = $("#plan_price").val();
        var plan_duration = $("#plan_duration").val();
        if (plan_name != "" && plan_price !=""  && plan_duration != "" ) {
        var info = {plan_name: plan_name, plan_price:plan_price, plan_duration:plan_duration, action:'add_plan'};
        $.ajax({
            type: "POST",
            url: "ajaxdata.php",
            data: info,
            success: function(response){
                if(response == 1){
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Plan added successfully").delay(3000).fadeOut();
                    $("#plan_form").trigger('reset');
                    $("#plantable").load( " #plantable");
                } else if(response == 2){
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Admin can add maximum 4 plan").delay(3000).fadeOut();
                } else if (response == 0){
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please try after some time").delay(3000).fadeOut();
                } else if(response == 3){
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Plan with same name allready exist").delay(3000).fadeOut();
                }
            }
        });
        } else {
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter manadatory field").delay(3000).fadeOut();
        }
    });

    $("#submit").on("click", function(){
        var Multi_Seller = $("input[name='Multi_Seller']:checked").val();
        if(Multi_Seller != ""){var info = {Multi_Seller: Multi_Seller, action:'multiseller'};
        $.ajax({
            type: "POST",
            url: "ajaxdata.php",
            data: info,
            success: function(response){
                $(".loader").html('');
                if(response == 1){
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Details updated successfully").delay(3000).fadeOut();
                    window.setTimeout(function(){ location.reload(); }, 500);
                }
                if (response == 0){
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please try after some time").delay(3000).fadeOut();
                }
            }
        });
        } else {
            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter mandatory field").delay(3000).fadeOut();
        }
    });
});
</script>
</body>
</html>