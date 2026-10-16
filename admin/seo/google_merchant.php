<?php include("../../includes/configuration.php"); ?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Google Merchant</title>
    <?php include('../commoncss.php') ?>
</head>
<body>
<div class="page-body-wrapper">
    <?php include('../header.php'); ?>
    <?php include('../sidebar.php'); 
    $merchant = file_get_contents("../../shopping-samples/content/merchant-info.json");
    $account = file_get_contents("../../shopping-samples/content/service-account.json");
    $statuses = file_get_contents("../../shopping-samples/status.json");
    $merchArr = json_decode($merchant,true);
    $stArr = json_decode($statuses,true); ?>
    <div class="main-panel">
        <div class="dashbody">
            <? if($merchArr['merchantId']!=""){?>
            <div class="card card-body">
                <div class="alert-info rounded p-3">
                    <h6><b>Important Note :</b></h6>
                    <ul class="mb-0 pl-3">
                        <li class="mb-1">Cron job runs <b>Once-In-A-Day</b> and automatically submits all available active SKUs to the Google Merchant Center.</li>
                        <li>So, there will be a difference in the below counts if you have added products after cron job is executed.</li>
                        <li>Also difference in Google Merchant Center Count will be observed as per data received from Google</li>
                    </ul>
                </div>
            </div>
            <div class="row mr-0">
                <div class="col-sm-6 col-md-6 col-lg-3 pr-0 pb-3">
                    <div class="dash-update-tiles rounded bg-info p-3 text-white position-relative h-100">
                        <a href="https://www.google.com/retail/solutions/merchant-center/" target="_blank">
                            <span class="fa fa-cube position-absolute bg-white text-danger"></span>
                            <div class="dash-update-body mr-5 pr-3">
                                <h5 class="mb-2"><?=$stArr['activeSku']; ?></h5>
                                <h6 class="text-white mb-0">Total Active SKU's As Of Now</h6>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-3 pr-0 pb-3">
                    <div class="dash-update-tiles rounded bg-warning p-3 text-white position-relative h-100">
                        <a href="https://www.google.com/retail/solutions/merchant-center/" target="_blank">
                            <span class="fa fa-cube position-absolute bg-white text-warning"></span>
                            <div class="dash-update-body mr-5 pr-3">
                                <h5 class="mb-2"><?=$stArr['deactiveSku']; ?></h5>
                                <h6 class="text-white mb-0">Total Deactive SKU's As Of Now</h6>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-3 pr-0 pb-3">
                    <div class="dash-update-tiles rounded bg-success p-3 text-white position-relative">
                        <a href="https://www.google.com/retail/solutions/merchant-center/" target="_blank">
                            <span class="fa fa-check-circle position-absolute bg-white text-success"></span>
                            <div class="dash-update-body mr-5 pr-3">
                                <h5 class="mb-2"><?=$stArr['approved']; ?></h5>
                                <h6 class="text-white mb-0">SKU Approved By Google Merchant Center</h6>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-3 pr-0 pb-3">
                    <div class="dash-update-tiles rounded bg-danger p-3 text-white position-relative">
                        <a href="https://www.google.com/retail/solutions/merchant-center/" target="_blank">
                            <span class="fa fa-times-circle position-absolute bg-white text-info"></span>
                            <div class="dash-update-body mr-5 pr-3">
                                <h5 class="mb-2"><?=$stArr['disapproved']; ?></h5>
                                <h6 class="text-white mb-0">SKU Disapproved / Pending By Google Merchant Center</h6>
                            </div>
                        </a>
                    </div>
                </div>
            </div>    
            <? } ?>
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2"><div><h2 class="card-head-title">Google Merchant Center</h2></div><div class="btn-actions-pane-right"><a href="<?=DOCUMENTURL;?>google-merchant-center.php" class="btn btn-primary btn-sm" target="_blank">Help?</a> <button type="button" class="btn btn-dark btn-sm merchnatshow">Show</button></div></div>
                <div class="google-merchant cc-display-none">
                    <form id="merchform">
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-sm-3 col-md-12 col-lg-2 col-xl-2 col-form-label cc-mandatorystar">Merchant ID</label>
                                <div class="col-sm-9 col-md-12 col-lg-6 col-xl-4"><input type="text" class="form-control" id="merchant" maxlength="10" value="<?=$merchArr['merchantId']; ?>"></div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-md-12 col-lg-2 col-xl-2 col-form-label cc-mandatorystar">Content Details</label>
                                <div class="col-sm-9 col-md-12 col-lg-9 col-xl-10"><textarea class="form-control" rows="30" id="content"><?=$account; ?></textarea></div>
                            </div>
                        </div>
                        <div class="card-footer py-2 text-right"><button type="button" name="create" id="submit" class="btn btn-primary">Save</button></div>
                    </form>
                </div>
            </div>
        </div>
        <?php include('../footer.php');?>
    </div>
</div>
<script>
$(document).ready(function(){
    $("#submit").on("click", function(){
        var merchant = $("#merchant").val(); var content = $("#content").val();
        if(merchant != ""&&content != ""){
            var info = { merchant: merchant, content:content, action:'google_merchant' };
            $.ajax({
                type: "POST", url: "ajax_googleMerchant.php", data: info,
                success: function(response){
                    if(response == 1){
                        $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Details updated successfully").delay(3000).fadeOut();
                        $("#merchform").load(" #merchform > *");
                    } if(response == 0){
                        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please try after some time").delay(3000).fadeOut();
                    }
                }
            });
        } else{
            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter all details").delay(3000).fadeOut();
        }
    });
});
$(".merchnatshow").click(function(){
    $(".google-merchant").slideToggle();
    if($(this).text('Show')){ $(this).text('Hide'); } else if($(this).text('Hide')){ $(this).text('Show'); } else{ $(this).text('Show'); }
})
    
$('.merchnatshow').click(function(){
    var $this = $(this);
    $this.toggleClass('merchnatshow');
    if($this.hasClass('merchnatshow')){ $this.text('Show'); } else{ $this.text('Hide'); }
});
</script>
</body>
</html>