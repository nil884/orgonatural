<?php include("../../includes/configuration.php"); ?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Tax Details</title>
    <?php include('../commoncss.php') ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/summernote/summernote-bs4.css">
</head>
<body>
<div class="page-body-wrapper">
    <?php include('../header.php') ?>
    <?php include('../sidebar.php') ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Add Tax Details</h2></div></div>
                <form>
                    <div class="card-body">
                        <div class="form-group row <?php if(TAXTYPE == "VAT"){ echo "cc-display-none";}  ?>">
                            <label class="col-sm-3 col-md-3 col-lg-2 col-form-label cc-mandatorystar">GST No</label>
                            <div class="col-sm-9 col-md-9 col-lg-5"><input type="text" name="gst_no" id="gst_no" class="form-control" placeholder="Enter GST No" maxlength="20" value="<? echo $getconfigdetails[0]['gst_no']; ?>" /></div>
                        </div>
                        <div class="form-group row <?php if(TAXTYPE == "GST"){ echo "cc-display-none";}  ?>">
                            <label class="col-sm-3 col-md-3 col-lg-2 col-form-label cc-mandatorystar">VAT No</label>
                            <div class="col-sm-9 col-md-9 col-lg-5"><input type="text" name="vat_no" id="vat_no" class="form-control" placeholder="Enter Vat No" maxlength="20" value="<? echo $getconfigdetails[0]['vat_no']; ?>" /></div>
                        </div>
                        <div class="form-group row mb-0">
                            <label class="col-sm-3 col-md-3 col-lg-2 col-form-label  cc-mandatorystar">Invoice Footer Disclaimer Note</label>
                            <div class="col-sm-9 col-md-9 col-lg-10"><textarea id="gst_desc" rows="4" class="form-control summernote" placeholder="Enter Disclaimer" name="gst_desc" maxlength="150"><? echo $getconfigdetails[0]['gst_desc']; ?></textarea></div>
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
<script src="<?php echo SITEURL; ?>/summernote/summernote-bs4.min.js"></script>
<script>
$(function(){
    $('.summernote').summernote({height: 200,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ],
        callbacks: {
            onPaste: function(e){
                var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('text/html');
                e.preventDefault();
                var div = $('<div />');
                div.append(bufferText);
                div.find('*').removeAttr('style class face color');
                setTimeout(function () {
                    document.execCommand('insertHtml', false, div.html());
                }, 10);
            }
        }
    });
});
    
$(document).ready(function(){
    $("#submit").on("click", function(){
        var gst_no = $("#gst_no").val();
        var vat_no = $("#vat_no").val()
        var gst_desc = $("#gst_desc").val();
        var taxtype= "<?php  echo TAXTYPE ?>"
        if((taxtype == "GST" && gst_no != "" && gst_desc != "") || (taxtype == "VAT" && vat_no != ""  && gst_desc != "")){
            var info = { gst_no: gst_no, vat_no:vat_no, gst_desc:gst_desc, action:'gst_details' };
            $.ajax({
                type: "POST",
                url: "ajaxdata.php",
                data: info,
                success: function(response){
                    $(".loader").html('');
                    if(response == 1){
                        $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Details updated successfully").delay(3000).fadeOut();
                    } if(response == 0){
                        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please try after some time").delay(3000).fadeOut();
                    }
                }
            });
        } else {
            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please fill all mandatory fields correctly").delay(3000).fadeOut();
        }
    });
});
</script>
</body>
</html>