<?php include("../../includes/configuration.php");?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Whatsapp Message</title>
    <?php include('../commoncss.php') ?>
    <link rel="stylesheet" href="<?=SITEURL;?>/css/bootstrap-toggle.min.css" />
</head>
<body>
<div class="page-body-wrapper">
    <?php include('../header.php') ?>
    <?php include('../sidebar.php');
     $code= ($getconfigdetails[0]['wp_phone_code']!=""?$getconfigdetails[0]['wp_phone_code']:"+91");
     $contries=selectQuery(COUNTRY,"name,phonecode","1 order by name ASC");  ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center">
                    <div><h2 class="card-head-title">Whatsapp Message</h2></div>
                </div>
                <form class="sms_gateway_form sms-gat-sec">
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-3 col-md-12 col-lg-3 col-xl-2 col-form-label ">Whatsapp Number</label>
                            <div class="col-sm-9 col-md-12 col-lg-7 col-xl-5"><div class="input-group"><span class="input-group-addon mr-3" style="width:150px"><select class="form-control countrycode rounded-left"><? for($i=0;$i<count($contries);$i++){ ?><option value="+<?=$contries[$i]['phonecode']; ?>" <?=($code=="+".$contries[$i]['phonecode']?"selected":""); ?>><?="(+".$contries[$i]['phonecode'].") ".$contries[$i]['name']; ?></option><? } ?></select></span><input type="text" name="wp_number" id="wp_number" class="form-control rounded-left" onblur="numbercheck('wp_number')" placeholder="Enter Whatspp Number" maxlength="10" value="<?php echo $getconfigdetails[0]['wp_number']; ?>" /></div><div class="mt-2">To disable this functionality keep whatsapp number field blank</div></div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-md-12 col-lg-3 col-xl-2 col-form-label cc-mandatorystar">Whatsapp Button Name</label>
                            <div class="col-sm-9 col-md-12 col-lg-7 col-xl-5"><div class="input-group"><input type="text" name="wp_button" id="wp_button" class="form-control" placeholder="Enter Whatsapp Button Name" maxlength="50" value="<?php echo $getconfigdetails[0]['wp_button_name']; ?>" /></div></div>
                        </div>
                        <div class="form-group row mb-0">
                            <label class="col-sm-3 col-md-12 col-lg-3 col-xl-2 col-form-label cc-mandatorystar">Message Template</label>
                            <div class="col-sm-9 col-md-12 col-lg-7 col-xl-5"><div class="input-group"><textarea name="wp_message" id="wp_message" class="form-control "  maxlength="200" ><?php echo $getconfigdetails[0]['wp_message']; ?></textarea></div><div class="mt-2">Use<br><b>{{URL}}</b> To add product URL in template AND <br><b>{{PRODUCT}}</b> to add product name in template</div></div>
                        </div>
                    </div>
                    <div class="card-footer py-2 text-right"><button type="button" name="create" id="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
        <?php include('../footer.php');?>
    </div>
</div>
<script src="<?php echo SITEURL ?>/js/validation.js"></script>
<script>
$("#submit").on("click", function(){
    var wp_number = $("#wp_number").val();  var wp_button = $("#wp_button").val(); var wp_message = $("#wp_message").val(); var country_code = $(".countrycode option:selected").val();
    if((wp_button != ""  && wp_message != "")){
        var info = {country_code:country_code, wp_number: wp_number, wp_button: wp_button, wp_message:wp_message, action:'whatsapp_message'};
        $.ajax({
            type: "POST", url: "ajaxdata.php", data: info,
            success: function(response){
                $(".loader").html('');
                if(response == '1'){
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Whatsapp details are updated successfully").delay(3000).fadeOut();
                } else{
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Whatsapp details not updated").delay(3000).fadeOut();
                }
            }
        });
    } else{
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please fill all mandatory fields ").delay(3000).fadeOut();
    }
});
</script>
</body>
</html>