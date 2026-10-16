<?php include ("../../includes/configuration.php");
$basicdetail = selectQuery(BLOG,"*","id='".base64_decode($_REQUEST['blogid'])."' "); ?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Blog SEO</title>
    <?php include('../commoncss.php'); ?>
</head>
<body>
<div class="page-body-wrapper">
    <?php include ('../header.php'); include ('../sidebar.php');?>
    <div class="main-panel">        
        <div class="dashbody">
            <?php include "flownav.php"; ?>
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2"><div><h2 class="card-head-title mb-2 mb-sm-0">SEO Details - <?php echo $basicdetail[0]['title'];?></h2></div><div class="btn-actions-pane-right"><button onclick="window.history.back();" class="btn btn-secondary btn-sm">Back</button></div>
                </div>
                <div class="card-body pb-0">          
                    <div class="row form-group">
                        <label class="col-md-12 col-lg-3 col-xl-2 col-form-label cc-mandatorystar">Page Title</label>
                        <div class="col-md-12 col-lg-9 col-xl-6"><input type="text" name="pagetitle" id="pagetitle" class="form-control" value="<?php echo $basicdetail[0]['page_title']; ?>" maxlength="30" placeholder="Max. Character Limit 30"/></div> 
                    </div>
                    <div class="row form-group">
                        <label class="col-md-12 col-lg-3 col-xl-2 col-form-label cc-mandatorystar">Meta Description</label>
                        <div class="col-md-12 col-lg-9 col-xl-6"><input type="text" name="metadesc" id="metadesc" class="form-control" value="<?php echo $basicdetail[0]['metadescription'];?>" maxlength="150" placeholder="max character limit 150" ></div>
                    </div>
                    <div class="row form-group">             
                        <label class="col-md-12 col-lg-3 col-xl-2 col-form-label cc-mandatorystar">Meta Keywords</label>
                        <div class="col-md-12 col-lg-9 col-xl-6"><textarea name="keywords" id="keywords" class="form-control" maxlength="300" rows="5" placeholder="Max Character Limit 300"><?php echo $basicdetail[0]['keywords']; ?></textarea></div>
                    </div>                    
                </div>
                <div class="card-footer text-right py-2"><button type="button" class="btn btn-primary updateseo" onclick="checkval1()">Save</button></div>
            </div>
        </div>
        <?php include '../footer.php'; ?>
    </div>
</div>
<script>
function checkval1(){
    var pagetitle = $("#pagetitle").val();
    var keywords = $("#keywords").val();
    var metadesc = $("#metadesc").val();
    blogid = "<?php echo base64_decode($_REQUEST['blogid']); ?>";
    var info = {blogid:blogid,pagetitle:pagetitle,keywords:keywords,metadesc:metadesc,action:"updateseo",};
    if(pagetitle.length==0){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html('Please Enter  Page Title').delay(5000).fadeOut();
    } else if(keywords.length==0){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html('Please Enter Keywords').delay(5000).fadeOut();
    } else if(metadesc.length==0){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html('Please Enter Meta Description').delay(5000).fadeOut();
    } else{
        $.ajax({
            type:"POST",
            url:"ajaxdata.php",
            data:info,
            success:function(response){
                $(".updateseo").attr('disabled',false)
                if(response==0){
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Not updated. Please Try Again Later").addClass("alert alert-danger").delay(3000).fadeOut();
                } else{
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("SEO Details Updated Successfully.").delay(5000).fadeOut();
                    location.reload();
                }
            }
        });
    }
}
function catchng(){
    var selectedval = $("#category option:selected").val();
    if(selectedval=="addnewcat"){
        $("#myModal2").modal('show');
        $('#category').prop('selectedIndex',0);
    } else{
        $("#myModal2").modal('hide');
        var info = {cat:selectedval};
        $.ajax({
            type:"POST",
            url:"getsubcategory.php",
            data:info,
            success:function(response){
                if(response==0){
                    $('.msgs1').fadeIn().css("background","red").html("ERROR..").delay(5000).fadeOut();
                } else{ $("#subcategory").replaceWith(response);   }
            }
        });
    }
}
$(".blgseo").addClass('step-active');
</script>
</body>
</html>