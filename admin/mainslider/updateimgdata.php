<? include("../../includes/configuration.php");
    $getimg = selectQuery(MAIN_SLIDER,"*","img_id=".base64_decode($_REQUEST['img']));
    $getconfingdetails = json_decode(getimgconfig('main slider'));
    $img_location = $getconfingdetails[0]->imgs_location; // Access Object data
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo SITENAME; ?> : Update Mainslider</title>
    <? include "../commoncss.php"; ?>
</head>
<body>
<div class="page-body-wrapper">
    <? include '../header.php'; ?>
    <? include '../sidebar.php'; ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2">
                    <div><h2 class="card-head-title">Update Image Data</h2></div>
                    <div class="btn-actions-pane-right"><button onclick="goBack()" class="btn btn-secondary btn-sm">Back</button></div>
                </div>
                <div class="card-body">
                    <div class="msgs"></div>
                    <div><img src="<?php echo SITEURL."/".$img_location."/".$getimg[0]['img_name']; ?>" class="img-fluid" alt="mainsliderimage"></div>
                </div>
            </div>
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Destination Page URL</h2></div></div>
                <div class="card-body pb-0">
                    <div class="form-group"><input type="text" name="btn_link" id="btn_link" class="form-control btn_link" maxlength="500" placeholder="Enter Page Link"  value="<?php echo $getimg[0]['btn_link']; ?>"/></div>
                    <div class="row"><div class="col-md-12"><div class="msgs12"></div></div></div>                    
                </div>
                <div class="card-footer py-2 text-right"><input type="button" name="savedata" id="savedata" class="btn btn-primary" value="Save" onclick="updatedata()"/></div>
            </div>      
        </div>
        <? include "../footer.php"; ?>
    </div>
</div>
<script>
function updatedata(){
    var btn_link = $("#btn_link").val();
    var img_id = '<?php echo base64_decode($_REQUEST['img']); ?>';
    $.ajax({
        type : "POST",
        url : "ajaxdata.php",
        data : {img_id : img_id, btn_link : btn_link, action : "update_data",},
        success: function(response) {
            if (response == 1){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Details updated successfully").delay(3000).fadeOut();
            } else{
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error.. Please try after Some time").delay(3000).fadeOut();
            }
        }
    });
}
    
$(".nav-list-1 .dropdownMenu").slideToggle();
$(".nav-list-1 .dropdownMenu .nav-list-0 a").addClass("menuactive");
</script>
</body>
</html>