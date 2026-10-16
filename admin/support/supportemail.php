<?php include("../../includes/configuration.php");
    $support=selectQuery(SUPPORTEMAIL,"*");
    $getstaff=selectQuery(SUPPORTSTAFF,"*","acc_type!='Client' AND isdel='0' order by emp_name ASC");
?>
<!doctype html> 
<html lang='en'>
<head>
    <title>Customer Helpdesk - Master Configuration</title>
    <meta charset='utf-8'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="<?php echo METAKEYWORDS; ?>"  >
    <meta name="description" content ="<?php echo METADESCRIPTION; ?>">
    <?php include('../commoncss.php') ?>
</head>
<body>
<div class="page-body-wrapper">
    <?php include('../header.php') ?>
    <?php include('../sidebar.php') ?>
      <div class="main-panel">
        <div class="dashbody">
            <?php include('supp-nav.php') ?> 
            <div class="card">
                    <div class="card-header sec-card-head justify-content-between align-items-center">
                        <div><h5 class="card-head-title">Master Configuration</h5></div>
                        <div class="btn-actions-pane-right"><button class="btn btn-default btn-sm" onclick="goBack()"> Back</button></div>
                    </div>

                <div class="card-body">
                    <div class="row">
                        <label class="col-md-2 label-control">Department Isolation</label>
                        <div class="col-md-10">
                            <div class="paddingfourteen">
                            <label class="radio-inline"><input type="radio" name="dept_iso" class="dept_iso" <? if($support[0]['department_issolation']==1){echo "checked";}else{} ?> value="1"/> Enable</label>
                            <label class="radio-inline"><input type="radio" name="dept_iso" class="dept_iso" <? if($support[0]['department_issolation']==0){echo "checked";}else{} ?> value="0"/> Disable</label>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <label class="col-md-2 label-control">New Ticket Alert</label>
                        <div class="col-md-10">
                            <div class="paddingfourteen">
                                <label class="radio-inline"><input type="radio" name="new_tkt" class="new_tkt" <? if($support[0]['new_tkt_alert']==1){echo "checked";}else{} ?> value="1"/> Enable</label>
                                <label class="radio-inline"><input type="radio" name="new_tkt" class="new_tkt" <? if($support[0]['new_tkt_alert']==0){echo "checked";}else{} ?> value="0"/> Disable</label>
                            </div>
                            <div>
                                <label class="checkbox-inline"><input type="checkbox" name="new_tkt_alerts[]" class="new_tkt_alerts" <? if($support[0]['new_tkt_alert_client']==1){echo "checked";}else{} ?> value="Client"/> Client</label>
                                <label class="checkbox-inline"><input type="checkbox" name="new_tkt_alerts[]" class="new_tkt_alerts" <? if($support[0]['new_tkt_alert_admin']==1){echo "checked";}else{} ?> value="Admin"/> Admin</label>
                                <label class="checkbox-inline"><input type="checkbox" name="new_tkt_alerts[]" class="new_tkt_alerts" <? if($support[0]['new_tkt_alert_mgr']==1){echo "checked";}else{} ?> value="Manager"/> Dept Manager</label>
                                <label class="checkbox-inline"><input type="checkbox" name="new_tkt_alerts[]" class="new_tkt_alerts" <? if($support[0]['new_tkt_alert_members']==1){echo "checked";}else{} ?> value="Staff"/> Dept Staff</label>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <label class="col-md-2 label-control">New Message Alert</label>
                        <div class="col-md-10">
                            <div class="paddingfourteen">
                                <label class="radio-inline"><input type="radio" name="new_msg" class="new_msg" <? if($support[0]['new_msg_alert']==1){echo "checked";}else{} ?> value="1"/> Enable</label>
                                <label class="radio-inline"><input type="radio" name="new_msg" class="new_msg" <? if($support[0]['new_msg_alert']==0){echo "checked";}else{} ?> value="0"/> Disable</label>
                             </div>
                             <div class="text-muted">
                                <label class="checkbox-inline"><input type="checkbox" name="new_msg_alerts[]" class="new_msg_alerts" <? if($support[0]['new_msg_alert_client']==1){echo "checked";}else{} ?> value="Client"/> Client</label>
                                 <label class="checkbox-inline"><input type="checkbox" name="new_msg_alerts[]" class="new_msg_alerts" <? if($support[0]['new_msg_alert_admin']==1){echo "checked";}else{} ?> value="Admin"/> Admin</label>
                                  <label class="checkbox-inline"><input type="checkbox" name="new_msg_alerts[]" class="new_msg_alerts" <? if($support[0]['new_msg_alert_mgr']==1){echo "checked";}else{} ?> value="Manager"/> Dept Manager</label>
                                   <label class="checkbox-inline"><input type="checkbox" name="new_msg_alerts[]" class="new_msg_alerts" <? if($support[0]['new_msg_alert_respondent']==1){echo "checked";}else{} ?> value="Respondent"/> Respondent</label>
                             </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <label class="col-md-2 label-control">Overdue Ticket Alert</label>
                        <div class="col-md-10">
                            <div class="paddingfourteen">
                                <label class="radio-inline"><input type="radio" name="overdue_tkt" class="overdue_tkt" <? if($support[0]['overdue_tkt']==1){echo "checked";}else{} ?> value="1"/> Enable</label>
                                <label class="radio-inline"><input type="radio" name="overdue_tkt" class="overdue_tkt" <? if($support[0]['overdue_tkt']==0){echo "checked";}else{} ?> value="0"/> Disable</label>
                            </div>
                            <div class="text-muted">
                                <label class="checkbox-inline"><input type="checkbox" name="overdue_alerts[]" class="overdue_alerts" <? if($support[0]['overdue_tkt_admin']==1){echo "checked";}else{} ?> value="Admin"/> Admin</label>
                                <label class="checkbox-inline"><input type="checkbox" name="overdue_alerts[]" class="overdue_alerts" <? if($support[0]['overdue_tkt_mgr']==1){echo "checked";}else{} ?> value="Manager"/> Dept Manager</label>
                                <label class="checkbox-inline"> <input type="checkbox" name="overdue_alerts[]" class="overdue_alerts" <? if($support[0]['overdue_tkt_staff']==1){echo "checked";}else{} ?> value="Staff"/> Dept Staff</label>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <label class="col-md-2 label-control">Allowed Attachment File Format & size</label>
                        <div class="col-md-10">
                            <div class="paddingfourteen">
                                <h5 class="cc-margin-top-0 cc-margin-bottom-15">Attachment Max Size</h5>
                                <input type="text" name="maxsize" id="maxsize" maxlength="1" onkeyup="numbercheck('maxsize')" value="<?php echo ($support[0]['attachment_size']/1024)/1024; ?>"/> MB
                            </div>
                            <div class="paddingfourteen">
                                <h5>Image File Format</h5>
                                <?php $imgtypes=selectQuery(MIME,"distinct extension","file_type='Image' order by extension ASC");
                                for($m=0;$m<sizeOf($imgtypes);$m++){ ?>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="application_format[]" class="application_format" value="<?php echo $imgtypes[$m]['extension']; ?>" <? if (strpos($support[0]['application_types'], $imgtypes[$m]['extension']) !== false) {echo 'checked';}else{} ?>/> <?php echo $imgtypes[$m]['extension']; ?>&nbsp;&nbsp;
                                </label>
                                <? } ?>
                            </div>
                            <div>
                                <h5 class="cc-margin-top-0 cc-margin-bottom-15">Application File Format</h5>
                                <div class="row cc-margin-bottom-15">
                                    <?php 
                                      $application_array = explode("," , $support[0]['application_types']);
                                   
                                    $applicationtype=selectQuery(MIME,"distinct extension","file_type='Application' order by extension ASC");
                                    for($m=0;$m<sizeOf($applicationtype);$m++) { ?>
                                    <div class="col-md-2 col-sm-2 col-xs-6">
                                        <input type="checkbox" name="application_format[]" class="application_format"  value="<?php echo $applicationtype[$m]['extension']; ?>" <?   if (in_array($applicationtype[$m]['extension'], $application_array))  { echo 'checked'; } else { } ?>  /> <?php echo $applicationtype[$m]['extension']; ?>&nbsp;&nbsp;
                                    </div>
                                    <? } ?>
                                </div>
                            </div>
                            <div>
                                <h5 class="cc-margin-top-0 cc-margin-bottom-15">Media File Format</h5>
                                <div class="row">
                                    <?php $mediatype=selectQuery(MIME,"distinct extension","file_type='Media' order by extension ASC");
                                    for($m=0;$m<sizeOf($mediatype);$m++) { ?>
                                    <div class="col-md-2 col-sm-2 col-xs-6">
                                    <input type="checkbox" name="application_format[]" class="application_format"  value="<?php echo $mediatype[$m]['extension']; ?>" <? if (strpos($support[0]['application_types'], $mediatype[$m]['extension']) !== false) {echo 'checked';}else{} ?>/> <?php echo $mediatype[$m]['extension']; ?>&nbsp;&nbsp;
                                    </div>
                                    <? } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-6">
                            <button type="button" class="btn btn-primary" id="updatedata" onclick="updatedate()"> Update </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include('../footer.php');?>
    </div>
</div>
 <script>
    function numbercheck(id) {
        var entered = $("#" + id).val();
        if (isNaN(entered)) {
            $("#" + id).val("");
        } else {}
    }

    function updatedate() {
        var dept_iso = $(".dept_iso:checked").val();
        var new_tkt = $(".new_tkt:checked").val();
        var new_msg = $(".new_msg:checked").val();
        var overdue_tkt = $(".overdue_tkt:checked").val();
        var new_tkt_alerts1 = new Array();
        $(".new_tkt_alerts:checked").each(function() {
            new_tkt_alerts1.push($(this).val());
        });
        var new_tkt_alerts = new_tkt_alerts1.toString();
        var new_msg_alerts1 = new Array();
        $(".new_msg_alerts:checked").each(function() {
            new_msg_alerts1.push($(this).val());
        });
        var new_msg_alerts = new_msg_alerts1.toString();
        var overdue_alerts1 = new Array();
        $(".overdue_alerts:checked").each(function() {
            overdue_alerts1.push($(this).val());
        });
        var overdue_alerts = overdue_alerts1.toString();
        var attachmax = $("#maxsize").val();
        var imgallowedarr = new Array();
        var appallowedarr = new Array();
        $(".img_format:checked").each(function() {
            imgallowedarr.push($(this).val());
        });
        $(".application_format:checked").each(function() {
            appallowedarr.push($(this).val());
        });
        var imgallowed = imgallowedarr.toString();
        var appallowed = appallowedarr.toString();
        var info = {
            dept_iso: dept_iso,
            new_tkt: new_tkt,
            new_msg: new_msg,
            overdue_tkt: overdue_tkt,
            new_tkt_alerts: new_tkt_alerts,
            new_msg_alerts: new_msg_alerts,
            overdue_alerts: overdue_alerts,
            imgallowed: imgallowed,
            appallowed: appallowed,
            attachmax: attachmax
        };
        $.ajax({
            type: "POST",
            url: "updateemailsetting.php",
            data: info,
            success: function(msg) {
                if (msg == 1) {
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Updated Successfully").delay(1000).fadeOut();
                    setTimeout(function() {
                        window.location = window.location;
                    }, 1000);
                }
            }
        });
    }
</script>
</body>
</html>