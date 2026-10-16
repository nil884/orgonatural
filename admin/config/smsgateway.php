<?php include("../../includes/configuration.php");?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : SMS Gateway</title>
    <?php include('../commoncss.php') ?>
</head>
<body>
<div class="page-body-wrapper">
    <?php include('../header.php') ?>
    <?php include('../sidebar.php');
    $getblankTemp = selectQuery(SMSTEMPLATE,"count(id) as blanktemp","templateId=''");
    $blankcnt=$getblankTemp[0]['blanktemp']; ?>
    <div class="main-panel">
        <div class="dashbody">
            <? $getblankTemp = selectQuery(SMSTEMPLATE,"count(id) as blanktemp","templateId=''");
            if($getblankTemp[0]['blanktemp']&&SMS_SYSTEM=="ON"){
                ?> <div id="smsimortnote"><div id="smstempmsg" class="alert alert-danger"> As per new rule by TRAI you need to provide approved Template ID for each SMS and your <?=$getblankTemp[0]['blanktemp']; ?> templates do not have Template ID.  <a href="<?=ADMINURL;?>sms_email_setting/templates.php">Click Here To Add Template ID</a> </div></div> <? } ?>
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2">
                    <div><h2 class="card-head-title mb-2 mb-lg-0">SMS Gateway Details</h2></div>
                    <div class="btn-actions-pane-right">
                        <a href="<?=DOCUMENTURL;?>sms-registration.php" target="_blank" class="btn btn-primary btn-sm smsserreg mb-2 mb-md-0">SMS Service Registration</a>
                        <a href="<?php echo ADMINURL; ?>sms_email_setting/sms.php" target="_blank" class="btn btn-primary btn-sm mb-2 mb-md-0">SMS Templates</a>
                        <label class="switch btn btn-primary mb-2 mb-md-0"> <input onchange="valueChanged()" type="checkbox" class="pay-mon-tog  sms_enable_disble" id="sms_enable_disble"  name="sms-tog" data-toggle="toggle" data-on="Enabled" data-off="Disabled" data-width="100" data-height="30" <? if($getconfigdetails[0]['sms_enable'] == "ON"){ echo "checked"; } else{ echo ""; }?>><span class="slider round"><span class="on">Active</span><span class="off">Deactive</span></span></label>
                    </div>
                </div>      
                <form class="sms_gateway_form sms-gat-sec cc-display-none">
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-3 col-md-12 col-lg-3 col-xl-2 col-form-label cc-mandatorystar">SMS URL</label>
                            <div class="col-sm-9 col-md-12 col-lg-7 col-xl-5"><div class="input-group"><input type="text" name="sms_url" id="sms_url" class="form-control border-right-0" placeholder="Enter SMS URL" maxlength="50" value="<? echo $getconfigdetails[0]['sms_url']; ?>" disabled/><div class="input-group-prepend cc-cursor-pointer rounded-right" data-toggle="modal" data-target="#smsUrl"><span class="input-group-text"><i class="fa fa-question-circle" aria-hidden="true"></i></span></div></div></div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-md-12 col-lg-3 col-xl-2 col-form-label cc-mandatorystar">SMS Sender</label>
                            <div class="col-sm-9 col-md-12 col-lg-7 col-xl-5"><div class="input-group"><input type="text" name="sms_sender" id="sms_sender" class="form-control border-right-0" placeholder="Enter SMS Sender" maxlength="50" value="<? echo $getconfigdetails[0]['sms_sender']; ?>" /><div class="input-group-prepend cc-cursor-pointer rounded-right" data-toggle="modal" data-target="#smssender"><span class="input-group-text bg-white"><i class="fa fa-question-circle" aria-hidden="true"></i></span></div></div></div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-md-12 col-lg-3 col-xl-2 col-form-label cc-mandatorystar">Working Key</label>
                            <div class="col-sm-9 col-md-12 col-lg-7 col-xl-5"><div class="input-group"><input type="text" name="sms_working_key" id="sms_working_key" class="form-control border-right-0" placeholder="Enter Working Key" maxlength="50" value="<? echo $getconfigdetails[0]['sms_working_key']; ?>" /><div class="input-group-prepend cc-cursor-pointer rounded-right" data-toggle="modal" data-target="#workingkey"><span class="input-group-text bg-white"><i class="fa fa-question-circle" aria-hidden="true"></i></span></div></div></div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-md-12 col-lg-3 col-xl-2 col-form-label cc-mandatorystar">SMS Site Name</label>
                            <div class="col-sm-9 col-md-12 col-lg-7 col-xl-5">
                                <div class="input-group">
                                    <input type="text" name="sms_site_name" id="sms_site_name" class="form-control border-right-0" placeholder="Enter SMS Site Name" maxlength="50" value="<? echo $getconfigdetails[0]['sms_site_name']; ?>" />
                                    <div class="input-group-prepend cc-cursor-pointer rounded-right" data-toggle="modal" data-target="#smssitename"><span class="input-group-text bg-white"><i class="fa fa-question-circle" aria-hidden="true"></i></span></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-3 col-md-12 col-lg-3 col-xl-2 col-form-label cc-mandatorystar">SMS Balence</label>
                            <div class="col-sm-9 col-md-12 col-lg-7 col-xl-5">
                                <div class="py-2" id='bal'><?php echo $credit; ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer py-2 text-right"><button type="button" name="create" id="submit" class="btn btn-primary">Save</button>
                        <?php if($getconfigdetails[0]['sms_sender'] ==""){ ?>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#sendeidapply">Apply For Sender ID</button>
                        <?php } ?>
                    </div>
                </form>
            </div>
            <div class="card mb-0 sms-gat-sec cc-display-none">
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">SMS Status Code</h2></div></div>
                <div class="card-body">
                <table class="table table-bordered mb-0">
                    <tr><td>NO-CREDITS</td><td>Insufficient credits</td></tr>
                    <tr><td>SERIES-BLOCK</td><td>Mobile number series blocked</td></tr>
                    <tr><td>INV-NUMBER</td><td>Invalid number</td></tr>
                    <tr><td>SERVER-ERR</td><td>Server error</td></tr>
                    <tr><td>SPAM</td><td>Spam SMS</td></tr>
                    <tr><td>SNDRID-NOT-ALLOTED</td><td>Sender ID not allocated</td></tr>
                    <tr><td>BLACKLIST</td><td>Blacklisted number</td></tr>
                    <tr><td>TEMPLATE-NOT-FOUND</td><td>Template not mapped</td></tr>
                    <tr><td>INV-TEMPLATE-MATCH</td><td>Template not matching approved text</td></tr>
                    <tr><td>SENDER-ID-NOT-FOUND</td><td>Sender ID not found</td></tr>
                    <tr><td>DNDNUMB</td><td>DND registered number</td></tr>
                    <tr><td>NOT-OPTIN</td><td>Not subscribed for opt-in group</td></tr>
                    <tr><td>TIME-OUT-PROM</td><td>Time out for promotional SMS</td></tr>
                    <tr><td>DELIVRD SMS</td><td>Successfully delivered</td></tr>
                    <tr><td>INVALID-SUB</td><td>Number does not exist/Failed to locate the number in HLR database</td></tr>
                    <tr><td>ABSENT-SUB</td><td>Telecom services not providing service for the particular number. / Mobile Subscriber not reachable</td></tr>
                    <tr><td>HANDSET-ERR</td><td>Problem with Handset or handset failed to get the complete message. / Handset doesn’t support the incoming messages.</td></tr>
                    <tr><td>BARRED</td><td>End user has enabled message barring system. / Subscriber only accepts messages from Closed User Group [CUG]</td></tr>
                    <tr><td>NET-ERR</td><td>Subscriber’s operator not supported. / Gateway mobile switching error</td></tr>
                    <tr><td>MEMEXEC</td><td>Handset memory full</td></tr>
                    <tr><td>FAILED SMS</td><td>Expired due to roaming limitation. / Failed to process the message at operator level</td></tr>
                    <tr><td>MOB-OFF</td><td>Mobile handset in switched off mode</td></tr>
                    <tr><td>HANDSET-BUSY</td><td>Subscriber is in busy condition.</td></tr>
                    <tr><td>SERIES-BLK</td><td>Series blocked by the operator</td></tr>
                    <tr><td>EXPIRED SMS</td><td>Expired after multiple re-try</td></tr>
                    <tr><td>REJECTED SMS</td><td>Rejected as the number is blacklisted by operator</td></tr>
                    <tr><td>OUTPUT-REJ</td><td>Unsubscribed from the group</td></tr>
                    <tr><td>REJECTED-MULTIPART</td><td>Validation fail [SMS over 160 characters]</td></tr>
                    <tr><td>UNDELIV</td><td>Failed due to network errors</td></tr>
                    <tr><td>NO-DLR-OPTR</td><td>In case operator has not acknowledge on status report of the SMS</td></tr>
                    <tr><td>AWAITED-DLR</td><td>Mobile number has been accepted and submitted to the operator.Awaiting delivery report from the operator</td></tr>
                    <tr><td>OPTOUT-REJ</td><td>Opt out from subscription.</td></tr>
                    <tr><td>INVALID-NUM</td><td>In case any invalid number present along with the valid numbers.</td></tr>
                    <tr><td>INV-TEMPLATE- MATCH</td><td>In case the message given in any one of the nodes does not match the template approved for the sender ID in the same node</td></tr>
                    <tr><td>MAX-LENGTH</td><td>In case the message given in any one of the nodes exceeds the maximum of 1000 characters</td></tr>
                </table>
            </div>
            </div>            
        </div>
        <?php include('../footer.php');?>
    </div>
</div>
<div id="smsUrl" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h4 class="modal-title">SMS Gateway Details</h4><button type="button" class="close" data-dismiss="modal">&times;</button></div>
            <div class="modal-body"><ol class="mb-0 pl-3 cc-list-line"><li>SMS URL - URL Provided By The SMS Service Provider</li></ol></div>
        </div>
    </div>
</div>
<div id="smssender" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h4 class="modal-title">SMS Gateway Details</h4><button type="button" class="close" data-dismiss="modal">&times;</button></div>
            <div class="modal-body"><ol class="mb-0 pl-3 cc-list-line"><li>SMS Sender - Strictly 6 Character (Special Characters, Spaces Are <b><u>NOT</u></b> Allowed)</li></ol></div>
        </div>
    </div>
</div>
<div id="workingkey" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h4 class="modal-title">SMS Gateway Details</h4><button type="button" class="close" data-dismiss="modal">&times;</button></div>
            <div class="modal-body"><ol class="mb-0 pl-3 cc-list-line"><li>Working Key - Provided By SMS Service Provider</li></ol></div>
        </div>
    </div>
</div>
<div id="smssitename" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h4 class="modal-title">SMS Gateway Details</h4><button type="button" class="close" data-dismiss="modal">&times;</button></div>
            <div class="modal-body"><ol class="mb-0 pl-3 cc-list-line"><li>SMS Site Name - Will Appear In Each SMS</li></ol></div>
        </div>
    </div>
</div>
<div class="modal" id="sendeidapply">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Apply For Sender ID</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
            <input type="text" class="form-control text-uppercase" id="sendid" placeholder="Appy Sender ID"  maxlength="6"  onkeyup="namechk('sendid')">
            </div>
            <div class="modal-footer text-right"><button type="button"  id="aply" class="btn btn-primary">Apply</button></div>
        </div>
    </div>
</div>
<script src="<?php echo SITEURL ?>/js/validation.js"></script>
<script>
$(document).ready(function(){
  $("#aply").on("click", function(){
    var sendid = $("#sendid").val();
    if(sendid==""   || sendid.length != "6"){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter valid sender id").delay(3000).fadeOut();
    }
    else {
        var info = {sendid: sendid,action:"sendmailtoadmin"}
        $.ajax({
        type: "POST",
            url: "ajaxdata.php",
            data: info,
            success: function(response){
                response = $.trim(response);
                if(response == 1){
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Your Request is submitted, please wait").delay(3000).fadeOut();
                    $("#sendeidapply").modal('hide');
                    $("#sendid").val("");
                }
                else if(response == 0){
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please try after some time").delay(3000).fadeOut();
                }
            }
        }); 
    }

  })

    $("#submit").on("click", function(){
        var sms_url = $("#sms_url").val();
        var sms_sender = $("#sms_sender").val();
        var sms_working_key = $("#sms_working_key").val();
        var sms_site_name = $("#sms_site_name").val();
        if (sms_sender != "" && sms_working_key != ""  && sms_site_name != ""  && sms_url != "") {
        var info = {sms_sender: sms_sender, sms_working_key: sms_working_key, sms_site_name:sms_site_name, sms_url:sms_url, action:'sms_gateway'};
        $.ajax({
            type: "POST",
            url: "ajaxdata.php",
            data: info,
            success: function(response){
                $(".loader").html('');
                var obj = jQuery.parseJSON(response);
                if(obj.status == 'OK')
                {
                    $('#bal').text(obj.credit); 
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("SMS Gateway details are added successfully, and you have SMS Balance of : "+obj.credit).delay(3000).fadeOut();
                }
                else if(obj.status == 'A404'){
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Unfortunately, the provided credentials are not matching, please enter correct details").delay(3000).fadeOut();
                }
            }
        });
        } else {
            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please fill all mandatory fields").delay(3000).fadeOut();
        }
    });
});

$(".sms_enable_disble").on("change",function(){
    var getid=$(this).attr('id');
    var c = $("#"+getid+":checked").val();
    if(c=="on"){
        var info = {status:"ON",action:"sms_enable_disble"};
        $.ajax({
            type:"POST",
            url:"ajaxdata.php",
            data:info,
            success:function(response){
                if(response==1){
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Enabled successfully").delay(3000).fadeOut();
                    $("#smsimortnote").load(location.href + " #smstempmsg");
                } else{
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please try after some time").delay(3000).fadeOut();
                }
            }
        });
    } else{
        var info = {status:"OFF",action:"sms_enable_disble"};
        $.ajax({
            type:"POST",
            url:"ajaxdata.php",
            data:info,
            success:function(response){
                if(response==1){
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Disabled successfully").delay(3000).fadeOut();
                    $("#smsimortnote").load(location.href + " #smstempmsg");
                } else{
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please try after some time").delay(3000).fadeOut();
                }
            }
        });
    }
 });
function valueChanged(){
    blankcnt="<?=$blankcnt; ?>"

if($('.sms_enable_disble').is(":checked")){ $(".sms-gat-sec").removeClass(".cc-display-none").show();  $("#trairule").removeClass("cc-display-none");  }
else{ $(".sms-gat-sec").addClass(".cc-display-none").hide(); if(blankcnt){$("#trairule").addClass("cc-display-none"); }  }
}
valueChanged();
</script>
</body>
</html>