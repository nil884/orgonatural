<?php include("../../includes/configuration.php");
$getdetails = selectQuery(STATIC_PAGE,"*","id= 1"); ?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : About Us</title>
    <?php include('../commoncss.php') ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/summernote/summernote-bs4.css">
</head>
<body>
<div class="page-body-wrapper">
    <? include '../header.php'; ?>
    <? include '../sidebar.php'; ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">About Us</h2></div></div>
                <form>
                    <div class="card-body">
                        <div class="row">
                            <label class="col-md-12 control-label">Description</label>
                            <div class="col-md-12"><textarea class="form-control summernote" id="aboutus_data" maxlength="500" placeholder="Enter information(Max 500 Characters)" required> <?php echo $getdetails[0]['about_us']; ?></textarea></div>
                        </div>
                    </div>
                    <div class="card-footer text-right py-2"><button type="button" class="btn btn-primary" id="about_submit">Submit</button></div>
                </form>
            </div>
        </div>
        <?php include('../footer.php');?>
    </div>
</div>
<script src="<?php echo SITEURL; ?>/summernote/summernote-bs4.min.js"></script>
<script>
$('.summernote').summernote({
    toolbar: [
    ['style', ['style']],
    ['font', ['bold', 'underline', 'clear']],
    ['fontname', ['fontname']],
    ['fontSizes', ['fontsize']],
    ['color', ['color']],
    ['para', ['ul', 'ol', 'paragraph']],
    ['table', ['table']],
    ['insert', ['link', 'picture', 'video']],
    ['view', ['fullscreen', 'codeview', 'help']],
    ],
    fontSizes: ['8', '10', '12', '14', '16', '18', '20', '22', '24', '26' , '28', '30', '32'],
    height: 200,
    callbacks: {
        onPaste: function(e){
            var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
            e.preventDefault();
            //Firefox fix
            setTimeout(function(){
                document.execCommand('insertText', false, bufferText);
            }, 10);
        }
    }
});
$(document).ready(function(){
    $("#about_submit").on("click", function(){
        var aboutus_data = $("#aboutus_data").val();
        var cleandata = $("#aboutus_data").summernote("code").replace(/&nbsp;|<br>/g, '');
        if(cleandata.trim().length){ var data_count=1 }else{var data_count=0 }
        var info = { aboutus_data: aboutus_data, data_count:data_count, action:'aboutus_data' };
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
    });
});
</script>
</body>
</html>