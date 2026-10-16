<?php include ("../../includes/configuration.php"); ?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Update Logo Images</title>
    <?php include('../commoncss.php'); ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/uploadImagepopup/cropper.css">
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/uploadImagepopup/main.css">
</head>
<body>
<div class="page-body-wrapper">
    <?php include ('../header.php'); include ('../sidebar.php');
    $getconfingdetails = json_decode(getimgconfig('logo'));
    $img_location = $getconfingdetails[0]->imgs_location; // Access Object data
    $crop_width = $getconfingdetails[0]->crop_width;
    $crop_height = $getconfingdetails[0]->crop_height; 
    $max_image_size = $getconfingdetails[0]->max_image_size ;
    $default_image_width = $getconfingdetails[0]->default_image_width ;
    $default_image_height = $getconfingdetails[0]->default_image_height ;
    $crop_enabled = $getconfingdetails[0]-> crop_enabled; ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card card-body">
                <div class="alert-info rounded p-3">
                    <h6><b>Image Upload Criteria</b></h6>
                    <ul class="mb-0 pl-3">
                        <li class="mb-1">Image Size Should Be Less Than <b><?php echo $max_image_size ?> MB</b></li><li>Minimum Dimenssions Should Be <b>Width :
                        <?php if($crop_enabled == 1){ echo $crop_width." Px And Height : ".$crop_height; } else{ echo $default_image_width." Px And Height : ".$default_image_height;} ?>
                         Px</b></li><li>Supported Filetype = <b>Only .PNG Images</b></li>
                    </ul>
                </div>
            </div>
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2">
                    <div><h2 class="card-head-title">Logo Image</h2></div>
                    <div class="btn-actions-pane-right"><label class="btn btn-primary btn-sm btn-upload" for="inputImage" title="Upload image file"><input type="file" class="sr-only" id="inputImage" name="file" accept="image/*" onchange="validateimg(this)"><span class="docs-tooltip" data-toggle="tooltip"><span class="fa fa-upload"></span> Update Logo Image</span></label></div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12"><div class="progress cc-display-none mb-2"><div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div></div></div>
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
                    <div id="imgdata" class="imgdata">
                        <input type="hidden" id="img_count" value='1'>
                        <div class="row">
                            <?php if($getconfigdetails[0]['logo'] != "" ){ ?>
                            <div class="col-6 col-sm-4 col-md-3 col-lg-3 col-xl-3">
                                <div class="position-relative border p-3 mb-2 w-75 mw-100 bg-light"><img src="<? echo SITEURL."/".$img_location."/".$getconfigdetails[0]['logo']; ?>" alt="upload-img" class="img-fluid"/></div>
                            </div>
                            <? } else{ echo "<div class='col-md-12 text-muted'>No Image Available</div>"; } ?>
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
    var imgconf='<?=getimgconfig('logo'); ?>';
    jsonarr=JSON.parse(imgconf);
</script>
<script src="<?=SITEURL; ?>/cropimg/dist/cropper.js"></script>
<script src="<?=SITEURL; ?>/cropimg/img_upload.js"></script>
<script>
window.addEventListener('DOMContentLoaded', function(){
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
            formData.append('thumbnail_required',thumbnail_required);   formData.append('img_extension',img_extension);
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
                    success: function (status) {
                        if(status=="Upload Success"){
                            $("#imgdata").load(  " #imgdata");							
                            $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html(status).delay(3000).fadeOut();
                            location.reload();
						}
                        else{ $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html(status).delay(3000).fadeOut();
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
        $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Max "+max_image_size+" MB size is allowed").delay(3000).fadeOut();
        $(file).val("");
        e.preventDefault();
    }
}
</script>
</body>
</html>