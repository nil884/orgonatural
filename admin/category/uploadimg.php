<?php include ("../../includes/configuration.php");
$cat_id = base64_decode($_REQUEST['id']);
$prod_cat = selectQuery(PRODCAT,"*","id=".$cat_id."  order by priority"); ?>
<!doctype html> 
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Update Category Images</title>
    <?php include('../commoncss.php'); ?>
    <link rel="stylesheet" href="<?=SITEURL;?>/css/bootstrap4-toggle.min.css" />
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/uploadImagepopup/cropper.css">
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/uploadImagepopup/main.css">
</head>
<body>
<div class="page-body-wrapper">
    <?php include ('../header.php'); include ('../sidebar.php');
    $getconfingdetails = json_decode(getimgconfig('category'));
   
    $img_location = $getconfingdetails[0]->imgs_location;
    $getconfingdetails = getimgconfigpaths('category'); ?>
    <div class="main-panel">        
        <div class="dashbody">
            <?php include "flownav.php"; ?>
            <div class="card card-body">
                <div class="alert-info rounded p-3">
                    <h6><b>Image Upload Criteria</b></h6>
                    <ul class="mb-0 pl-3">
                        <li class="mb-1">Image Size Should Be Less Than <b><?php echo $getconfingdetails[0]['max_image_size'] ?> MB</b></li><li class="mb-1">Minimum Dimenssions Should Be <b>Width : 
                        <?php if($getconfingdetails[0]['crop_enabled'] == 1){ echo $getconfingdetails[0]['crop_width']." Px And Height : ".$getconfingdetails[0]['crop_height']; } else{ echo $getconfingdetails[0]['default_image_width']." Px And Height : ".$getconfingdetails[0]['default_image_height'];} ?>
                         Px</b></li>
                        <li>Maximum Images Allowed = 1</li>
                    </ul>
                </div>
            </div>
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2">
                    <div><h2 class="card-head-title mb-2 mb-sm-0">Images - <?php echo $prod_cat[0]['cat_name'];?></h2></div>
                    <div class="btn-actions-pane-right">
                        <label class="btn btn-primary btn-sm btn-upload" for="inputImage" title="Upload image file">
                            <input type="file" class="sr-only" id="inputImage" name="file" accept="image/*" onclick="checkcount(event)" onchange="validateimg(this)">
                            <span class="docs-tooltip" data-toggle="tooltip" ><span class="fa fa-upload"></span> Upload Image</span>
                        </label>
                        <button onclick="window.history.back();" class="btn btn-secondary btn-sm">Back</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12"><div class="progress cc-display-none my-2"><div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div></div></div>
                        <div class="alert cc-display-none" role="alert"></div>
                        <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header"><h5 class="modal-title" id="modalLabel">Upload Image</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span></button>
                                    </div>
                                    <div class="modal-body"><div class="img-container"><img id="image" src="#" alt="uploadimg"></div></div>
                                    <div class="modal-footer text-right"><button type="button" class="btn btn-primary ml-auto" id="crop">Save</button><button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="imgdata">
                        <input type="hidden" id="img_count" value='<?php if($prod_cat[0]['img_name'] == "") { echo 0; } else { echo 1; } ?>'>
                        <div class="row">
                            <!-- <a href="uploadimg.php?prodid=<?php echo  base64_encode($getinv[$i]['id']) ?>">text</a>-->
                            <?php  if($prod_cat[0]['img_name'] != ""){ ?>
                            <div class="col-6 col-sm-4 col-md-3 col-lg-3 col-xl-2">
                                <div class="position-relative uped-prod-thumb border p-2 mb-2">
                                    <img src="<? echo SITEURL."/".$img_location."/".$prod_cat[0]['img_name']; ?>" alt="" class="img-fluid"/>
                                    <span class="removeopt pro-attr-badge-action del-upload-pic shadow-sm btn btn-danger p-1 rounded-circle" onclick="del_cat_img('<?php echo $prod_cat[0]['id'] ?>','<?php echo $prod_cat[0]['img_name'] ?>') "><i class="fa fa-trash"></i></span>
                                </div>
                            </div>
                            <? } else{ echo "<div class='col-md-12 text-muted'>No Image Available</div>"; }?>
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
    var imgconf='<?=getimgconfig('category'); ?>';
    jsonarr=JSON.parse(imgconf);
</script>
<script src="<?=SITEURL; ?>/cropimg/dist/cropper.js"></script>
<script src="<?=SITEURL; ?>/cropimg/img_upload.js"></script>
<script>
window.addEventListener('DOMContentLoaded', function(){
    document.getElementById('crop').addEventListener('click', function (){
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
                formData.append('imgs_location',imgs_location);    formData.append('webp_quality',webp_quality); 
                formData.append('catid','<?php echo $cat_id; ?>');
                formData.append('avatar', blob, 'avatar.jpg');
                $.ajax('ajaxdata.php', {
                    method: 'POST', data: formData,  processData: false, contentType: false,
                    xhr: function(){
                        var xhr = new XMLHttpRequest();
                        xhr.upload.onprogress = function (e){
                            var percent = '0'; var percentage = '0%';
                            if(e.lengthComputable){
                                percent = Math.round((e.loaded / e.total) * 100);
                                percentage = percent + '%';
                                $progressBar.width(percentage).attr('aria-valuenow', percent).text(percentage);
                            }
                        };
                        return xhr;
                    },
                    success: function(status){
                        if(status=="Upload Success"){       $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html(status).delay(3000).fadeOut(); $("#imgdata").load(  " #imgdata");}
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
    var FileSize = file.files[0].size / 1024 / 1024;
    if(max_image_size<FileSize){
        $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Max "+max_image_size+" MB size is allowed").delay(3000).fadeOut();
        $(file).val("");
        e.preventDefault();
    }
}
function del_cat_img(imgid,type){ del_alertbox("Do you really want to delete this Image?", imgid,"del_image_db",type); }
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
                $("#imgdata").load(  " #imgdata");                
                $("#del_popup").modal("hide");
            }
            else if(response=="0"){
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error.. Try again later").delay(3000).fadeOut();
            }
        }
    })
}
    
$(".nav-list-1").find(".dropdownMenu").slideToggle();
   
$(document).ready(function(){
    var path = window.location.href; 
    var lastChar = path[path.length - 2];
    if(lastChar == "/"){var path = path.slice(0,-1);}
    var target = $(".com-step-nav ul li a[href='"+path+"']");
    target.addClass('step-active');
});
</script>
</body>
</html>