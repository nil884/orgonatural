<?php include ("../../includes/configuration.php"); 
$getyoutuvevideo = selectQuery(YOUTUBEVIDEO,"*","yvideo_type='story'"); ?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Story Video</title>
    <?php include('../commoncss.php'); ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/dataTable/dataTables.bootstrap4.min.css">
</head>
<body>
<div class="page-body-wrapper">
    <?php include ('../header.php'); include ('../sidebar.php'); ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card card-body">
                <div class="alert-info rounded p-3">
                    <h6><b>Video Upload Criteria</b></h6>
                    <ul class="mb-0 pl-3">
                        <li>Go to YouTube.</li>
                        <li>Navigate to the video you wish to embed.</li>
                        <li>Click the Share link below the video, then click the Embed link.</li>
                        <li>You will need to copy only <b>src</b> link in order to add it to your page.</li>
                        <li>You can upload only <b>1 videos</b></li>
                    </ul>
                </div>
            </div>
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Story Video</h2></div><div class="btn-actions-pane-right"><button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" <?php if(count($getyoutuvevideo)>0){ echo "disabled"; } else{  echo 'data-target="#story-video-modal"'; } ?>>Add Video</button>
                    </div></div>
                <div class="card-body" id="ytvideobody"> 
                    <div class="table-responsive" id="vtvideo-table">
                        <table class="table table-bordered mb-0 youvidlink w-100">
                            <thead><tr><th>#</th><th>Youtube Link</th><th>Delete</th></tr></thead>
                            <tbody class="tbodyrow">
                                <?php for($i=0;$i<count($getyoutuvevideo);$i++){ ?>
                                <tr>
                                    <td class="row-index-tr"><?php echo $i+1; ?></td>
                                    <td><a href="<?php echo $getyoutuvevideo[$i]['yvideo_link']; ?>" target="_blank"><?php echo $getyoutuvevideo[$i]['yvideo_link']; ?></a></td>
                                    <td class="del-btn-tr"><button type="button" onclick="del_yvideo('<?php echo $getyoutuvevideo[$i]['yvideo_id']; ?>')" class="btn btn-danger btn-sm deletebtn"><i class="fa fa-trash-o"></i></button></td>
                                </tr>
                                <? } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php include '../footer.php'; ?>
    </div>
</div>
<div class="modal" id="story-video-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Modal Heading</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label>Story Video Link</label>
                        <input type="text" class="form-control" id="story-video-link" placeholder="Enter Video Embed Link">
                    </div>
                    <div class="row border-top"><div class="col-12 text-right pt-2"><button type="button" class="btn btn-primary" onClick="addyotubevideo()">Add Link</button></div></div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo SITEURL; ?>/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script>
function loaddatatable(){ $('.youvidlink').DataTable({ "scrollX": true, "searching": false }); }
loaddatatable();
function addyotubevideo(){
    storyvideolink = $("#story-video-link").val();
    var form_data = new FormData();
    form_data.append("storyvideolink", storyvideolink);
    form_data.append("action", "storyvidlink");
    if((storyvideolink == "")){
        $('.alert_msgs').fadeIn().addClass("failactionmsg").html("Please fill all details").delay(3000).fadeOut(); 
    } else{
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            success: function(response){
                $('.alert_msgs').fadeIn().addClass("successactionmsg").html("Youtube Video Link Added successfully").delay(3000).fadeOut();
                $("#story-video-link").val("");
                $("#story-video-modal").modal("hide");
                location.reload();
            }
        })
    }
}
function del_yvideo(vidid){ del_alertbox("Do you really want to delete this Image?", vidid,"del_yvideo_db"); }
function del_yvideo_db(id){
    var yvideId = id;
    if(id != ""){
        var info = { yvideId: yvideId, action: "delytvideo" };
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: info,
            success: function(response){
                if (response == 1) {
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Youtube Video Link deleted successfully").delay(3000).fadeOut();
                    $("#del_popup").modal("hide");
                    location.reload();
                } else{
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error.. Try again later").delay(3000).fadeOut();
                }
            }
        });
    }
}
</script>
</body>
</html>