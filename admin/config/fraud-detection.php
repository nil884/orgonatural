<?php include("../../includes/configuration.php"); ?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Fraud Detection</title>
    <?php include('../commoncss.php') ?>
</head>
<body>
<div class="page-body-wrapper">
    <?php include('../header.php'); ?>
    <?php include('../sidebar.php');
    $conf = $getconfigdetails[0];  $limit = 0;
    if($conf['fraud_detection']=='1'&&($conf['fraud_apikey']!=''&&$conf['fraud_apisecrete']!='')){ $verifyfraud=1; } else{ $verifyfraud=0; }
    $data = array();
    if($verifyfraud==1){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://www.hellofraud.com/api/balance/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array( "Accept:application/json", "Authorization: Basic ".base64_encode($conf['fraud_apikey'].":".$conf['fraud_apisecrete'])),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $array = json_decode($response,true);
        $limit = $array['limit'];$consume = $array['consume'];
    } ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2"><div><h2 class="card-head-title">Fraud Detection</h2></div><div class="btn-actions-pane-right"><a href="https://www.hellofraud.com/" target="_blank" class="btn btn-primary btn-sm">Fraud Detection API</a> <label class="switch btn btn-primary"><input type="checkbox" class="push-notif" name="pushNotif" data-toggle="toggle" data-on="Enabled" data-off="Disabled" data-width="100" data-height="30" <?=($conf['fraud_detection']==1?"checked":""); ?>><span class="slider round"><span class="on">Active</span><span class="off">Deactive</span></span></label></div></div>
                <div class="push-notif-body">
                    <form>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-sm-3 col-md-4 col-lg-3 col-xl-2 col-form-label">API Key</label>
                                <div class="col-sm-9 col-md-7 col-lg-5 col-xl-4"><input type="text" class="form-control apikey" placeholder="Enter API Key" maxlength="100" value="<?=$conf['fraud_apikey']; ?>"/></div>
                            </div>
                            <div class="row">
                                <label class="col-sm-3 col-md-4 col-lg-3 col-xl-2 col-form-label">API Secrete</label>
                                <div class="col-sm-9 col-md-7 col-lg-5 col-xl-4"><input type="text" class="form-control apisecrete" placeholder="Enter API Secrete" maxlength="100" value="<?=$conf['fraud_apisecrete']; ?>" /></div>
                            </div>
                            <? if($limit!=0){ ?>
                            <div class="row mt-2">
                                <label class="col-sm-3 col-md-4 col-lg-3 col-xl-2 col-form-label">Balance</label>
                                <div class="col-sm-9 col-md-7 col-lg-5 col-xl-4 py-2"><?=$consume; ?>/<?=$limit; ?></div>
                            </div>
                            <? } ?>
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
stat="<?=$conf['fraud_detection']; ?>"
if(stat==0){$(".push-notif-body").hide();}else{ $(".push-notif-body").show(); }
$("[name='pushNotif']").change(function(){ pushstat=($("[name='pushNotif']:checked").val()=="on"?1:0);
    var info = { pushstat: pushstat,action:"fraudDetectionStatus" }
    $.ajax({
        type: "POST", url: "ajaxdata.php", data: info,
        success: function(response){ response = $.trim(response);
            $(".push-notif-body").slideToggle();
        }
    });
});
function savedata(){
    apisecrete = $(".apisecrete").val(); apikey = $(".apikey").val();
    var info = { apisecrete: apisecrete, apikey:apikey, action:"fraudUpdate" }
    if($("[name='pushNotif']:checked").val()=="on"&&(apisecrete==""||apikey=="")){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("All details are mandatory").delay(3000).fadeOut();
    } else{
        $.ajax({
            type: "POST", url: "ajaxdata.php", data: info,
            success: function(response){ response = $.trim(response);
            if(response == 1){                          $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Details updated").delay(3000).fadeOut();
            } else if(response == 0){       $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please try after some time").delay(3000).fadeOut();  }
            }
        });
    }
}
</script>
</body>
</html>