<?php include("../../includes/configuration.php"); ?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Analytics</title>
    <?php include('../commoncss.php') ?>
</head>
<body>
<div class="page-body-wrapper">
    <?php include('../header.php') ?>
    <?php include('../sidebar.php') ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2"><div><h2 class="card-head-title">Add Google Analytics ID</h2></div><div class="btn-actions-pane-right"><a href="/img/projectimage/google-analytics.pdf" class="btn btn-primary btn-sm" target="_blank">Help?</a></div></div>

                <form>
                    <div class="card-body">
                        <div class="row">
                            <label class="col-sm-3 col-md-3 col-lg-2 col-xl-2 col-form-label cc-mandatorystar">Analytics ID</label>
                            <div class="col-sm-9 col-md-9 col-lg-6 col-xl-4">
                                <input type="text" name="Analytics_Id" id="Analytics_Id" class="form-control" placeholder="Add Google Analytics ID" maxlength="50"  value="<? echo $getconfigdetails[0]['Analytics_Id']; ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="card-footer py-2 text-right"><button type="button" name="create" id="submit" class="btn btn-primary">Save</button></div>
                </form>
            </div>
        </div>
        <?php include('../footer.php');?>
    </div>
</div>

<script>
$(document).ready(function(){
    $("#submit").on("click", function(){
        var Analytics_Id = $("#Analytics_Id").val();
        if (Analytics_Id != ""){
        var info = { Analytics_Id: Analytics_Id, action:'Analytics_Id' };
        $.ajax({
            type: "POST",
            url: "ajaxdata.php",
            data: info,
            success: function(response){
                if(response == 1){
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Details updated successfully").delay(3000).fadeOut();
                } if(response == 0){
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please try after some time").delay(3000).fadeOut();
                }
            }
        });
        } else {
            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter analytics ID").delay(3000).fadeOut();
        }
    });
});
</script>
</body>
</html>