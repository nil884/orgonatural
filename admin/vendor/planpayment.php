<?php include("../../includes/configuration.php");
$vendor = base64_decode($_REQUEST['vendor']);
$plan = base64_decode($_REQUEST['plan']);
$requesttype = base64_decode($_REQUEST['type']);
$vendordata = selectQuery(VENDOR,"*" ,"dealer_id=".$vendor);
if($requesttype!=""){ $reqtype=$requesttype; }
else{ $reqtype="New"; }
if($plan||$requesttype=="Renew"){
    if($plan){ $plan=$plan; }
    else{
        // $plandata1=selectQuery(VENDORPLANSELECTED,"sel_id","dealer_id=".$vendor." and plan_status='Active' order by sel_id DESC LIMIT 1",1);
       
        $plan = $vendordata[0]['plan'];
        
    }
    $plandata = selectQuery(VENDORPLANSELECTED,"plan","sel_id=".$plan);
} ?>
<!DOCTYPE HTML>  
<html lang="en">
<head>
    <title><?php echo SITENAME; ?> : Vendor Profile</title>
    <?php include('../commoncss.php') ?>
    <? if($reqtype=="Renew") { ?>
    <style>.highlight{display: none;} .requestdetails{display:block;}</style>
    <? } else{ ?>
    <style>.highlight{display: block;}.requestdetails{display:none;}</style>
    <? } ?>
</head>
<body>
<div class="page-body-wrapper">
    <? include '../header.php'; ?>
    <? include '../sidebar.php'; ?>
    <div class="main-panel">
        <div class="dashbody">
        <div class="card">
            <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Plan Renewal</h2></div></div>
                <div class="card-body">
                    <div class="list-view row">
                        <div class="col-md-2"><b>Payment Status</b></div>
                        <div class="col-md-8 col-sm-12 col-xs-12">
                            <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" name="payment" id="Received" class="payment custom-control-input"  value="Received"/>  <label class="custom-control-label" for="Received">Received</label> &nbsp;&nbsp;&nbsp;&nbsp;
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" name="payment" id="Pending" class="payment custom-control-input"  value="Pending" checked/><label class="custom-control-label" for="Pending">Pending</label> 
                        </div> <!--<input type="radio" name="payment" class="payment" value="Received" /> Received  &nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="radio" name="payment" class="payment" value="Pending" checked/> Pending-->
                        </div>
                    </div>
                    </div>
                    <div class="paymentdetails card-body pb-0">
                    <div class="row">
                        <input type="hidden" name="requesttype" id="requesttype" value="<?php echo $reqtype; ?>"/>
                        <input type="hidden" name="planid" id="planid" value="<?php echo $plan; ?>"/>
                        <input type="hidden" name="vendorid" id="vendorid" value="<?php echo $vendor; ?>" />
                        <?php $planprice=selectQuery(VENDORPLAN,"*","isActive = '1' " );   
                        for($p=0;$p<count($planprice);$p++){ ?>
                        <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3  <? if($reqtype=="Renew" &&  ($plandata[0]['plan']!=$planprice[$p]['plan_name'])){echo "highlight";}   ?>">
                            <div class="plan-pay-details shadow-lg mb-4 card cc-cursor-pointer border-0 <?php if( $reqtype=="Renew" &&  ($plandata[0]['plan'] == $planprice[$p]['plan_name'])) { echo "plan-selected"; }   ?>">
                            <div class="media p-3">
                                <img src="<?php echo SITEURL; ?>/img/projectimage/package-icon.svg" alt="package-icon" class="package-icon mr-3" width="25"/>
                                <div class="media-body">
                                    <input type="radio" name="plan" <? if($reqtype=="Renew"){echo "disabled";} ?> value="<?php echo $planprice[$p]['plan_name']; ?>" class="cc-display-none" <? if($plandata[0]['plan']==$planprice[$p]['plan_name']){echo "checked";} ?>/><h5 class="text-capitalize"><?php echo $planprice[$p]['plan_name']."";?></h5>
                                    <div class="currencysymbol lead"><?php if($planprice[$p]['price']==0){echo "Free";}else{echo "<i class='fa fa-inr'></i>".$planprice[$p]['price']."";} ?></div>
                                </div>
                            </div>
                            <div class="card-footer"><?php echo $planprice[$p]['plan_duration'] ?> Days</div>   </div>
                        </div>
                        <? } ?>
                    </div>
                </div>
                <div class="card-footer py-2 text-right"><button type="button" onclick="updateplan()" class="btn btn-primary submit2">Confirm</button></div>
            </div>
        </div>
        <?php include '../footer.php' ?>
    </div>
</div>
<script>
function cancelpay(){ var id='<?php echo base64_encode($vendor); ?>'; location.href="plandetails.php?vendor="+id; }
function updateplan(i){  
    msg= "Please Confirm before Updating Plan";
    var plan = $('input[name=plan]:checked').val();
    var requesttype = $("#requesttype").val();
    if( (plan == "" || plan == undefined) &&  requesttype != "Renew" ){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please Select Plan").delay(3000).fadeOut();
    } else{ del_alertbox(msg, i,"updateplan_db");}
}
function updateplan_db(){
    var vendorid = $("#vendorid").val();
    var planid = $("#planid").val();
    var requesttype = $("#requesttype").val();
    var plan = $('input[name=plan]:checked').val();
    var payment = $(".payment:checked").val();
    var info = {vendor:vendorid,planid:planid,plan:plan,requesttype:requesttype,payment:payment,action:"updateplan"};
    $(".submit2").prop('disabled', true);
    $.ajax({
        type: "POST",
        url: "ajaxdata.php",
        data:info,
        success: function(response){
            $(".submit2").prop('disabled', false);
            if(response==0){
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please try After Some Time").delay(3000).fadeOut();
            } else{ location.href="plandetails.php?vendor="+response; 
                
                } 
        }
    });
}
$('.plan-pay-details').click(function(){
    $(this).find('input').prop('checked', true);
    $('.plan-pay-details').removeClass('plan-selected');
    $(this).toggleClass('plan-selected');
});
</script>
</body>
</html>  