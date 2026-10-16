<?php include("../../includes/configuration.php"); ?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Site Parameter</title>
    <?php include('../commoncss.php') ?>
</head>
<body>
<div class="page-body-wrapper">
    <?php include('../header.php') ?>
    <?php include('../sidebar.php') ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center"><h2 class="card-head-title">Add Site Parameters</h2></div>
                <form>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-3 col-md-3 col-lg-3 col-xl-2 col-form-label cc-mandatorystar">Site Name</label>
                            <div class="col-md-9 col-sm-9 col-lg-6 col-xl-6"><input type="text" name="site_name" id="site_name" class="form-control" placeholder="Enter Site Name" maxlength="50" value="<? echo $getconfigdetails[0]['site_name']; ?>" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-md-3 col-lg-3 col-xl-2 col-form-label cc-mandatorystar">Site Meta Title</label>
                            <div class="col-md-9 col-sm-9 col-lg-6 col-xl-6"><input type="text" name="site_title" id="site_title" class="form-control" placeholder="Enter Site Title" maxlength="50" value="<? echo $getconfigdetails[0]['seo_title']; ?>" /></div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-md-3 col-lg-3 col-xl-2 col-form-label cc-mandatorystar">Site Meta Description</label>
                            <div class="col-md-9 col-sm-9 col-lg-6 col-xl-6"><textarea name="metadesc" id="metadesc" class="form-control" maxlength="160" placeholder="Max character limit 160" rows="5"><?php echo $getconfigdetails[0]['seo_description']; ?></textarea></div>
                        </div>
                        <div class="row">
                            <label class="col-sm-3 col-md-3 col-lg-3 col-xl-2 col-form-label cc-mandatorystar">Site Meta Keywords</label>
                            <div class="col-md-9 col-sm-9 col-lg-6 col-xl-6"><textarea name="keywords" id="keywords" class="form-control" maxlength="255" placeholder="Max Character Limit 255" rows="5"><?php echo $getconfigdetails[0]['seo_keywords']; ?></textarea></div>
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
    var site_name = $("#site_name").val();
    var site_title = $("#site_title").val();
    var keywords = $("#keywords").val();
    var metadesc = $("#metadesc").val();
    if($.trim(site_name).length == 0 ){
        $('.alert_msgs').fadeIn().removeClass("Please enter site Name").addClass("failactionmsg").html("Please enter meta description").delay(3000).fadeOut();
    } if($.trim(site_title).length == 0){
        $('.alert_msgs').fadeIn().removeClass("Please Enter  Page Title").addClass("failactionmsg").html("Please enter meta description").delay(3000).fadeOut();
    } else if($.trim(metadesc).length == 0){
        $('.alert_msgs').fadeIn().removeClass("Please enter keywords").addClass("failactionmsg").html("Please enter meta description").delay(3000).fadeOut();
    } else if($.trim(keywords).length == 0){
        $('.alert_msgs').fadeIn().removeClass("Please Enter Keywords").addClass("failactionmsg").html("Please enter meta keyword").delay(3000).fadeOut();
    } else {
        info = {site_title:site_title,site_name:site_name,keywords:keywords,metadesc:metadesc,action:'sitedetails'}
        $.ajax({
            type:"POST",
            url:"ajaxdata.php",
            data:info,
            success:function(response){
                response=response.trim();
                if(response=="1"){
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Details updated successfully").delay(3000).fadeOut();
                }else if(response=="0"){
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please try after some time").delay(3000).fadeOut();
                }
            }
        })
    }  
    });   
});   
</script>
</body>
</html>