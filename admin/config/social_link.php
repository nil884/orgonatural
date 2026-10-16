<?php include("../../includes/configuration.php"); ?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Social Media Links </title>
    <?php include('../commoncss.php') ?>
</head>
<body>
<div class="page-body-wrapper">
    <?php include('../header.php') ?>
    <?php include('../sidebar.php') ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Add Social Media Links</h2></div></div>
                <form>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-3 col-md-3 col-lg-2 col-form-label">Facebook Link</label>
                            <div class="col-sm-9 col-md-6 col-lg-5"><input type="text" name="fb_link" id="fb_link" class="form-control" placeholder="Add Facebook Link" maxlength="100" value="<? echo $getconfigdetails[0]['fb_link']; ?>" /></div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-md-3 col-lg-2 col-form-label">Instagram Link</label>
                            <div class="col-sm-9 col-md-6 col-lg-5"><input type="text" name="ins_link" id="ins_link" class="form-control" placeholder="Add Instagram Link" maxlength="100" value="<? echo $getconfigdetails[0]['instagram_link']; ?>" /></div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-md-3 col-lg-2 col-form-label">LinkedIn Link</label>
                            <div class="col-sm-9 col-md-6 col-lg-5"><input type="text" name="ldin_link" id="ldin_link" class="form-control" placeholder="Add LinkedIn Link" maxlength="100" value="<? echo $getconfigdetails[0]['linkedIn_link']; ?>" /></div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-md-3 col-lg-2 col-form-label">Pinterest Link</label>
                            <div class="col-sm-9 col-md-6 col-lg-5"><input type="text" name="Pinterest_link" id="Pinterest_link" class="form-control" placeholder="Add Pinterest Link" maxlength="100" value="<? echo $getconfigdetails[0]['Pinterest_link']; ?>" /></div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-md-3 col-lg-2 col-form-label">Youtube Link</label>
                            <div class="col-sm-9 col-md-6 col-lg-5"><input type="text" name="Youtube_link" id="youtube_link" class="form-control" placeholder="Add Youtube Link" maxlength="100" value="<? echo $getconfigdetails[0]['youtube_link']; ?>" /></div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-md-3 col-lg-2 col-form-label">Twitter Link</label>
                            <div class="col-sm-9 col-md-6 col-lg-5"><input type="text" name="Twitter_link" id="twitter_link" class="form-control" placeholder="Add Twitter Link" maxlength="100" value="<? echo $getconfigdetails[0]['twitter_link']; ?>" /></div>
                        </div>
                        <div class="row">
                            <label class="col-sm-3 col-md-3 col-lg-2 col-form-label">Google Play Store</label>
                            <div class="col-sm-9 col-md-6 col-lg-5"><input type="text" name="googleplay_link" id="googleplay_link" class="form-control" placeholder="Add Google Play Store Link" maxlength="200" value="<? echo $getconfigdetails[0]['playstore_link']; ?>" /></div>
                        </div>
                    </div>
                    <div class="card-footer py-2 text-right"><button type="button" name="create" id="submit" class="btn btn-primary">Save</button></div>
                </form>
            </div>
        </div>
        <?php include('../footer.php');?>
    </div>
</div>
<script src="<?php echo SITEURL ?>/js/validation.js"></script>
<script>
$(document).ready(function(){
    $("#submit").on("click", function() {
        var fb_link = $("#fb_link").val();
        var ins_link = $("#ins_link").val();
        var ldin_link = $("#ldin_link").val();
        var Pinterest_link = $("#Pinterest_link").val();
        var youtube_link = $("#youtube_link").val();
        var twitter_link = $("#twitter_link").val();
        var playstore_link = $("#googleplay_link").val();
        var info = {
            fb_link : fb_link, ins_link : ins_link, ldin_link : ldin_link, Pinterest_link : Pinterest_link, youtube_link : youtube_link, twitter_link : twitter_link, playstore_link : playstore_link, action:'social_link'
        };
        $.ajax({
            type: "POST",
            url: "ajaxdata.php",
            data: info,
            success: function(response){
                if(response == 1) {
                    $('.alert_msgs').removeClass("failactionmsg").fadeIn().addClass("successactionmsg").html("Details updated successfully").delay(3000).fadeOut();
                } if(response == 0) {
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please try after some time").delay(3000).fadeOut();
                }
            }
        });
    });
});
</script>
</body>
</html>