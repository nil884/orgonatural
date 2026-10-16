<? include("../includes/configuration.php");
    $vendor = base64_decode($_REQUEST['vendor']); $plan = base64_decode($_REQUEST['plan']); $requesttype = base64_decode($_REQUEST['type']); $vendordata = selectQuery(VENDOR,"*" ,"dealer_id=".$vendor);
    if($requesttype!=""){ $reqtype = $requesttype; }
    else{ $reqtype="New"; }
    if($plan||$requesttype=="Renew"){
        if($plan){ $plan = $plan; }
        else{
            //$plandata1 = selectQuery(VENDORPLANSELECTED,"*","dealer_id=".$vendor." and plan_status='Active' order by sel_id DESC LIMIT 1");
            //$plan = $plandata1[0]['sel_id'];
            $plan = $vendordata[0]['plan'];
        }
        $plandata = selectQuery(VENDORPLANSELECTED,"*","sel_id=".$plan);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo SITE_TITLE; ?> : Payment Details</title>
    <? include "commoncss.php"; ?>
    <? if($reqtype=="Renew"){ ?> <style>.paymentdetails{ display: none; } .requestdetails{ display:block; }</style> <? } else { ?> <style> .paymentdetails{ display: block; } .requestdetails{ display:none; }</style> <? } ?>
</head>
<body>
<div class="page-body-wrapper">
    <? include 'header.php'; ?>
    <? include 'sidebar.php'; ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="row">
                <div class="paymentdetails col-md-12">
                    <div class="card">
                        <div class="card-header sec-card-head justify-content-between align-items-center py-2"><div><h2 class="card-head-title">Plan Upgrade</h2></div><div class="btn-actions-pane-right"><button onclick="goBack()" class="btn btn-secondary btn-sm">Back</button></div></div>
                        <div class="card-body pb-0">
                            <div class="row">
                                <input type="hidden" name="requesttype" id="requesttype" value="<?php echo $reqtype; ?>"/>
                                <input type="hidden" name="planid" id="planid" value="<?php echo $plan; ?>"/>
                                <input type="hidden" name="dealerid" id="dealerid" value="<?php echo $vendor; ?>" />
                                <?php $planprice=selectQuery(VENDORPLAN,"*","isDel= '0' and isActive = '1' " );
                                for($p=0;$p<count($planprice);$p++) { ?>
                                <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3">
                                    <div class="plan-pay-details shadow-lg mb-4 card cc-cursor-pointer border-0   <? if($plandata[0]['plan']==$planprice[$p]['plan_name']){echo "plan-selected";} ?>">
                                        <div class="p-3">
                                            <div class="media">
                                                <img src="<?php echo SITEURL; ?>/img/projectimage/package-icon.svg" alt="package-icon" class="package-icon mr-3" width="25"/>
                                                <div class="media-body">
                                                    <input type="radio" name="plan" class="cc-display-none" <? if($reqtype=="Renew"){echo "disabled";} ?> value="<?php echo $planprice[$p]['plan_name']; ?>" <? if($plandata[0]['plan']==$planprice[$p]['plan_name']){echo "checked";} ?>/>
                                                    <h5 class="text-capitalize"><?php echo $planprice[$p]['plan_name']."";?></h5>
                                                    <div class="currencysymbol lead"><?php if($planprice[$p]['price']==0){echo "Free";}else{echo " <i class='fa fa-inr'></i>".$planprice[$p]['price']."";} ?></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer"><?php echo $planprice[$p]['plan_duration'] ?> DAYS</div>
                                    </div>
                                </div>
                                <? } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="requestdetails col-md-12 mb-3">
                    <div class="card mb-0">
                        <div class="card-header sec-card-head justify-content-between align-items-center py-2"><div><h2 class="card-head-title">Plan Renewal</h2></div><div class="btn-actions-pane-right"><button onclick="goBack()" class="btn btn-secondary btn-sm">Back</button></div></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-10">
                                    <div class="row form-group">
                                        <div class="col-sm-3 col-md-3 col-lg-2"><b>Request</b></div>
                                        <div class="col-sm-8 col-md-8 col-lg-8">Plan Renewal</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-3 col-md-3 col-lg-2"><b>Current Plan</b></div>
                                        <div class="col-sm-8 col-md-8 col-lg-8"><?php echo $plandata[0]['plan']; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>                        
                    </div>                    
                </div>
                <div class="col-12">
                    <button type="button" onclick="updateplan()" class="btn btn-primary" id="update">Confirm</button>   
                </div>
            </div>            
        </div>
        <? include "footer.php"; ?>
    </div>
</div>
<script>
function cancelpay(){ history.back(); }
function updateplan(){
    var dealerid = $("#dealerid").val();
    var planid = $("#planid").val();
    var requesttype = $("#requesttype").val();
    var plan = $('input[name=plan]:checked').val();
    var info = {dealerid:dealerid,planid:planid,plan:plan,requesttype:requesttype,action:"Updateplan"}
    if((typeof plan == 'undefined')){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Plan is not Selected").delay(3000).fadeOut();
    } else{  
        $.ajax({
            type: "POST",
            url: "ajax/ajaxdata.php",
            data:info,
            success: function(response){
                if(response==0){ $(".loader").html(''); }else{ location.href= $.trim(response); }
            }
        });
    }
}
$('.plan-pay-details').click(function(){
    $(this).find('input').prop('checked', true);
    $('.plan-pay-details').removeClass('plan-selected');
    $(this).toggleClass('plan-selected');
});
</script>
</body>
</html>