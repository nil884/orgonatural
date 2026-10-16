<?php include("../../includes/configuration.php"); ?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Push Notification</title>
    <?php include('../commoncss.php') ?>
</head>
<body>
<div class="page-body-wrapper">
    <?php include('../header.php'); ?>
    <?php include('../sidebar.php');
    $conf=$getconfigdetails[0]; ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2"><div><h2 class="card-head-title">Android App</h2></div><div class="btn-actions-pane-right"><a href="<?=DOCUMENTURL;?>publishing-android-apk.php" target="_blank" class="btn btn-primary btn-sm">Publish To PlayStore</a></div></div>
                  <div class="card-body">      
                   <?
                    if(glob("../../img/projectimage/*.apk")){
                        foreach (glob("../../img/projectimage/*.apk") as $filename) {
                         ?> <a class="btn btn-primary" href="<?=$filename; ?>" download>Download Android App</a> <?
                    }
                   }else{
                       echo "No Android App Found";
                   }
                   ?>
                </div>
            </div>

            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2"><div><h2 class="card-head-title">Push Notification</h2></div><div class="btn-actions-pane-right"><a href="<?=DOCUMENTURL;?>one-signal-api.php" target="_blank" class="btn btn-primary btn-sm">One Signal API</a> <label class="switch btn btn-primary"><input type="checkbox" class="push-notif" name="pushNotif" data-toggle="toggle" data-on="Enabled" data-off="Disabled" data-width="100" data-height="30" <?=($conf['isActive_oneSignal']==1?"checked":""); ?>><span class="slider round"><span class="on">Active</span><span class="off">Deactive</span></span></label></div></div>
                <div class="push-notif-body">
                    <form>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-sm-3 col-md-4 col-lg-3 col-xl-2 col-form-label">Onesignal App ID</label>
                                <div class="col-sm-9 col-md-7 col-lg-5 col-xl-4"><input type="text" class="form-control appid" placeholder="Enter Onesignal App ID" maxlength="100" value="<?=$conf['oneSignal_appId']; ?>"/></div>
                            </div>
                            <div class="row">
                                <label class="col-sm-3 col-md-4 col-lg-3 col-xl-2 col-form-label">Rest API Key</label>
                                <div class="col-sm-9 col-md-7 col-lg-5 col-xl-4"><input type="text" class="form-control apikey" placeholder="Enter Rest API Key" maxlength="100" value="<?=$conf['oneSignal_apiKey']; ?>" /></div>
                            </div>
                        </div>
                        <div class="card-footer py-2 text-right"><button type="button" id="submit" class="btn btn-primary" onclick="savedata()">Save</button></div>
                    </form>
                </div>
            </div>
        </div>
        <?php include('../footer.php');?>
    </div>
</div>
<script>
    stat="<?=$conf['isActive_oneSignal']; ?>"
   if(stat==0){$(".push-notif-body").hide();}else{ $(".push-notif-body").show(); }
    $("[name='pushNotif']").change(function(){ pushstat=($("[name='pushNotif']:checked").val()=="on"?1:0);
      var info = {pushstat: pushstat,action:"oneSignalStatus"}
       $.ajax({
        type: "POST", url: "ajaxdata.php", data: info,
            success: function(response){ response = $.trim(response);
                $(".push-notif-body").slideToggle();
            }
        });
   });

    function savedata(){
        appid=$(".appid").val(); apikey=$(".apikey").val();
        var info = {appid: appid,apikey:apikey,action:"oneSignalSUpdate"}
        if($("[name='pushNotif']:checked").val()=="on"&&(appid==""||apikey=="")){
            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("All details are mandatory").delay(3000).fadeOut();
        }else{
           $.ajax({
            type: "POST", url: "ajaxdata.php", data: info,
            success: function(response){ response = $.trim(response);
                if(response == 1){   $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Details updated").delay(3000).fadeOut();  }
                else if(response == 0){  $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please try after some time").delay(3000).fadeOut();  }
            }
           });
        }

    }
</script>
</body>
</html>