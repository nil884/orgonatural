<?php include ("../../includes/configuration.php"); ?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Main Slider</title>
    <?php include('../commoncss.php'); ?>
    <link rel="stylesheet" href="<?=SITEURL;?>/css/bootstrap4-toggle.min.css" />
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/uploadImagepopup/cropper.css">
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/uploadImagepopup/main.css">
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/dataTable/dataTables.bootstrap4.min.css">
</head>
<body>
<div class="page-body-wrapper">
    <?php include ('../header.php'); include ('../sidebar.php');
    $getconfingdetails = getimgconfigpaths('main slider');
    $getsliderimg = selectQuery(MAIN_SLIDER,"img_id,img_name"," 1 order by priority ASC");
    $img_location = $getconfingdetails[0]['imgs_location']; // Access Object data ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card card-body">
                <div class="alert-info rounded p-3">
                    <h6><b>Image Upload Criteria</b></h6>
                    <ul class="mb-0 pl-3">
                        <li class="mb-1">Image Size Should Be Less Than <b><?php echo $getconfingdetails[0]['max_image_size'] ?> MB</b></li><li class="mb-1">Minimum Dimenssions Should Be <b>Width : 
                        <?php if($getconfingdetails[0]['crop_enabled'] == 1){ echo $getconfingdetails[0]['crop_width']." Px And Height : ".$getconfingdetails[0]['crop_height']; } else{ echo $getconfingdetails[0]['default_image_width']." Px And Height : ".$getconfingdetails[0]['default_image_height'];} ?>
                         Px</b></li><li>You can upload only <b><?php echo $getconfingdetails[0]['max_image_count'] ?>  images</b></li>
                    </ul>
                </div>
            </div>
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2">
                    <div><h2 class="card-head-title">Slider Images</h2></div>
                    <div class="btn-actions-pane-right">
                        <label class="btn btn-primary btn-sm btn-upload" for="inputImage" title="Upload image file"><input type="file" class="sr-only" id="inputImage" name="file" accept="image/*" onclick="checkcount(event)" onchange="validateimg(this)"><span class="docs-tooltip" data-toggle="tooltip" title="Import image with Blob URLs"><span class="fa fa-upload"></span> Upload Image</span></label>
                    </div>
                </div>
                <div class="card-body">                     
                    <div class="row">
                        <div class="col-md-12"><div class="progress cc-display-none mb-3"><div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div></div></div>
                        <div class="alert cc-display-none" role="alert"></div>
                        <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalLabel">Upload Image</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    </div>
                                    <div class="modal-body"><div class="img-container"><img id="image" src="#" alt="uploadimg"></div></div>
                                    <div class="modal-footer text-right">
                                        <button type="button" class="btn btn-primary ml-auto" id="crop">Save</button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mainslide-list" id="mainsliereload">
                        <div id="imgdata">
                            <input type="hidden" id="img_count" value='<?php echo count($getsliderimg); ?>'>
                            <table class="table table-bordered mb-0 hm-slider-list w-100">
                                <thead><tr><th>#</th><th>Image</th><th>Add Link</th><th>Delete</th></tr></thead>
                                <tbody class="tbodyrow">
                                    <?php for($j=0;$j<count($getsliderimg);$j++){ ?>
                                    <tr id="<? echo $getsliderimg[$j]['img_id']; ?>"  class="mainslider_list">
                                        <td><?php echo $j+1; ?></td>
                                        <td><div class="position-relative"><img src="<? echo SITEURL."/".$img_location."/".$getsliderimg[$j]['img_name']; ?>" alt="banner-img-thumb" class="img-fluid" width="200"/>
                                        </div></td>
                                        <td><a href="updateimgdata.php?img=<?php echo base64_encode($getsliderimg[$j]['img_id']); ?>" class="btn btn-primary btn-sm">Add Link</a></td>
                                        <td class="del-btn-tr"><button type="button" class="removeopt btn btn-danger btn-sm" onclick="del_img('<?php echo $getsliderimg[$j]['img_id'] ?>','<?php echo $getsliderimg[$j]['img_name'] ?>') "><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                    <? } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-none"><img class="rounded" id="avatar" src="#" alt="uploadimg"><input type="file" class="sr-only" id="input" name="image" accept="image/*"></div>
        <?php include '../footer.php'; ?>
    </div>
</div>
<script>
    var imgconf='<?=getimgconfig('main slider'); ?>';
    jsonarr = JSON.parse(imgconf);
</script>
<script src="<?=SITEURL; ?>/cropimg/dist/cropper.js"></script>
<script src="<?=SITEURL; ?>/cropimg/img_upload.js"></script>
<script src="<?php echo SITEURL; ?>/js/jquery-ui.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script>
$('.hm-slider-list').DataTable({ "scrollX": true, "searching": false });
$("table tbody").sortable({
    update: function(){
        str = "";
        $(this).children().each(function(index) { str += $(this).attr('id') + ","; });
        str = str.replace(/,\s*$/, "");
        var info = { action: "priority_image",str: str };
        $.ajax({
            data: info,
            type: 'POST',
            url: 'ajaxdata.php',
            success: function(result){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Priority updated successfully").delay(3000).fadeOut();
            }
        })
    }
});  
window.addEventListener('DOMContentLoaded', function(){
    var image = document.querySelector('#image');
    document.getElementById('crop').addEventListener('click', function(){
        $modal.modal('hide');
        if (cropper){
            canvas = cropper.getCroppedCanvas({ width: crop_width, height: crop_height});
            initialAvatarURL = avatar.src;
            avatar.src = canvas.toDataURL();
            $progress.show();
            canvas.toBlob(function (blob){
                var formData = new FormData();
                formData.append('action','upload image');
                formData.append('img_ratio',img_ratio);   formData.append('crop_enabled',crop_enabled);
                formData.append('thumbnail_required',thumbnail_required);   formData.append('img_extension',img_extension); formData.append('webp_quality',webp_quality);
                formData.append('default_image_width',default_image_width); formData.append('default_image_height',default_image_height);
                formData.append('thumb1_width',thumb1_width); formData.append('thumb2_width',thumb2_width); formData.append('thumb3_width',thumb3_width);
                formData.append('thumb4_width',thumb4_width); formData.append('thumb5_width',thumb5_width);
                formData.append('thumb1_path',thumb1_path); formData.append('thumb2_path',thumb2_path); formData.append('thumb3_path',thumb3_path);
                formData.append('thumb4_path',thumb4_path); formData.append('thumb5_path',thumb5_path);
                formData.append('imgs_location',imgs_location);
                formData.append('avatar', blob, 'avatar.jpg');
                $.ajax('ajaxdata.php', {
                    method: 'POST', data: formData,  processData: false, contentType: false,
                    xhr: function() {
                        var xhr = new XMLHttpRequest();
                        xhr.upload.onprogress = function (e){
                            var percent = '0'; var percentage = '0%';
                            if (e.lengthComputable) {
                                percent = Math.round((e.loaded / e.total) * 100);
                                percentage = percent + '%';
                                $progressBar.width(percentage).attr('aria-valuenow', percent).text(percentage);
                            }
                        };
                        return xhr;
                    },
                    success: function (status){
                        if(status=="Upload Success"){           $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html(status).delay(3000).fadeOut();
                            $("#mainsliereload").load(location.href + " #imgdata");
                        } else{             $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html(status).delay(3000).fadeOut();
                    }},
                    error: function(){ avatar.src = initialAvatarURL; $alert.show().addClass('alert-warning').text('Upload error'); },
                    complete: function(){ $progress.hide(); },
                });
            });
        }
    });
});
function checkcount(e){
    allowedcount = max_image_count;
    var img_count = $("#img_count").val();
    if(parseInt(img_count)>=parseInt(allowedcount)){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg ").html("Only "+allowedcount+" images are allowed").delay(3000).fadeOut();
        e.preventDefault();
    }
}
function validateimg(file){
    var FileSize = file.files[0].size / 1024 / 1024; // in MB
    if(max_image_size<FileSize){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg ").html("Max "+max_image_size+" MB size is allowed").delay(3000).fadeOut();
        $(file).val("");
        e.preventDefault();
    }
} 
function del_img(imgid,type){ del_alertbox("Do you really want to delete this Image?", imgid,"del_image_db",type); }
function del_image_db(id,type){
    info  = {imgid:id,action:"Delete_image",img_name:type}
    $.ajax({
        type:"POST",
        url:"ajaxdata.php",
        data:info,
        success:function(response){
            response=response.trim();
            if(response== "1"){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Image deleted successfully").delay(3000).fadeOut();
                $("#mainsliereload").load(location.href + " #imgdata");
                $("#del_popup").modal("hide");
            } else if(response=="0"){
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error.. Try again later").delay(3000).fadeOut();
            }
        }
    })
}
</script>
</body>
</html>