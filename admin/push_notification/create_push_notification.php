<?php include("../../includes/configuration.php"); ?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Create Push Notification</title>
    <?php include('../commoncss.php') ?>
    <link rel="stylesheet" href="<?=SITEURL;?>/css/bootstrap4-toggle.min.css" />
    <link rel="stylesheet" type="text/css" href="emojionearea-master/dist/emojionearea.min.css" media="screen">
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/uploadImagepopup/cropper.css">
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/uploadImagepopup/main.css">
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/datepicker/bootstrap-datetimepicker.min.css"/>
</head>
<body>
<div class="page-body-wrapper">
    <?php include('../header.php') ?>
    <?php include('../sidebar.php');
    $conf=$getconfigdetails[0];
    $status=$conf['isActive_oneSignal'];
    $appid=$conf['oneSignal_appId'];
    $apikey=$conf['oneSignal_apiKey'];
    $getconfingdetails = json_decode(getimgconfig('pushnotification')); ?>
    <div class="main-panel">
        <div class="dashbody">
            <?  
            if($status==1&&$appid!=""&&$apikey!=""){?>
           <!-- <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2"><div><h2 class="card-head-title">1. Audience</h2></div></div>
                <div class="push-notif-body">
                    <form>
                        <div class="card-body">
                            <div class="custom-control custom-radio custom-control-inline mb-2">
                                <input type="radio" class="custom-control-input" id="sendsubuser" name="sendsubuser" value="customEx">
                                <label class="custom-control-label" for="sendsubuser">Send To Subscribed Users</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline mb-2">
                                <input type="radio" class="custom-control-input" id="sendpartsegment" name="sendsubuser" value="customEx">
                                <label class="custom-control-label" for="sendpartsegment">Send To Particular Segment(s)</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="senduserfilter" name="sendsubuser" value="customEx">
                                <label class="custom-control-label" for="senduserfilter">Send Using Filters</label>
                            </div>
                        </div>
                    </form>
                </div>
            </div>-->
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Push Notification</h2></div></div>
                <div class="push-notif-body card-body">
                    <form>
                        <div class="form-group row">
                            <label class="col-sm-3 col-md-4 col-lg-3 col-xl-2 col-form-label">Enter Title</label>
                            <div class="col-sm-9 col-md-7 col-lg-5 col-xl-4"><input type="text" class="form-control not-title" placeholder="Enter Title" /></div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-md-4 col-lg-3 col-xl-2 col-form-label">Message</label>
                            <div class="col-sm-9 col-md-7 col-lg-5 col-xl-4"><input type="text" class="form-control not-message emojionearea-editor" placeholder="Enter Message" /></div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-md-4 col-lg-3 col-xl-2 col-form-label">Launch URL</label>
                            <div class="col-sm-9 col-md-7 col-lg-5 col-xl-4"><input type="text" class="form-control not-url" placeholder="Enter Launch URL" /></div>
                        </div>
                        <div class="row row">
                            <label class="col-sm-3 col-md-4 col-lg-3 col-xl-2 col-form-label">Image</label>
                            <div class="col-sm-9 col-md-7 col-lg-5 col-xl-4"> <label class="btn btn-primary btn-sm btn-upload" for="inputImage" title="Upload image file">
                            <input type="file" class="sr-only" id="inputImage" name="file" accept="image/*" onclick="checkcount(event)" onchange="validateimg(this)">
                            <span class="docs-tooltip" data-toggle="tooltip" ><span class="fa fa-upload"></span> Upload Image</span>
                            </label></div>
                        </div>
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
                        <div class="d-none"><img class="rounded" id="avatar" src="#" alt="uploadimg"><input type="file" class="sr-only" id="input" name="image" accept="image/*"></div>
                        <div id="imgdata">
                            <input type="hidden" id="img_count" value='0'>
                            <input type="hidden" id="img_url" value=''>
                            <div class="row">
                                <label class="col-sm-3 col-md-4 col-lg-3 col-xl-2 col-form-label"></label>
                                <div class="col-sm-9 col-md-7 col-lg-5 col-xl-4"><div class="row"><div class="upbox col-md-12"><img src="#" class="uploadedimg img-fluid mt-2 cc-display-none" alt="img"></div></div></div>
                            </div>
                        </div>
                        <!--<div class="form-group row">
                            <label class="col-sm-3 col-md-4 col-lg-3 col-xl-2 col-form-label">Collapsed Key/ID</label>
                            <div class="col-sm-9 col-md-7 col-lg-5 col-xl-4"><input type="text" class="form-control" placeholder="Enter Collapsed Key/ID" /></div>
                            </div>
                            <div class="form-group row">
                            <label class="col-sm-3 col-md-4 col-lg-3 col-xl-2 col-form-label">Priority</label>
                            <div class="col-sm-9 col-md-7 col-lg-5 col-xl-4">
                            <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="notprihigh" name="notifpriority" value="customEx">
                            <label class="custom-control-label" for="notprihigh">High</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="notprinormal" name="notifpriority" value="customEx">
                            <label class="custom-control-label" for="notprinormal">Normal</label>
                            </div>
                            </div>
                        </div>-->
                    </form>
                <div class="push-notif-body">
                    <form>
                        <div class="row form-group">
                            <label class="col-sm-3 col-md-4 col-lg-3 col-xl-2 col-form-label">Delivery</label>
                            <div class="col-sm-9 col-md-7 col-lg-5 col-xl-4">
                                <div class="custom-control custom-radio custom-control-inline mb-2">
                                    <input type="radio" class="custom-control-input" id="notifscheduleimme" name="notifschedule" value="Immediate" checked="checked" onchange="changetime()"><label class="custom-control-label" for="notifscheduleimme"> Send Immediately</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" id="notifschedulepartime" name="notifschedule" value="After" onchange="changetime()"><label class="custom-control-label" for="notifschedulepartime"> Send at a perticular time</label>
                                </div>
                            </div>
                        </div>
                        <div class="timelater d-none">
                            <div class="row form-group">
                                <label class="col-sm-3 col-md-4 col-lg-3 col-xl-2 col-form-label d-none d-sm-block"></label>
                                <div class="col-sm-9 col-md-7 col-lg-5 col-xl-4"><input type="text" class="form-control aftersend" placeholder="Select A Perticular Time" /></div>
                            </div>
                        </div>
                    </form>
                    <div class="pt-2 pb-0 row border-top"><div class="col-12 text-right"><button type="button" id="submit" class="btn btn-primary" onclick="sendnotification()">Send</button></div></div>
                </div>
                </div>
            </div>
            <?}else{?>
            <div class="push-notif-body alert alert-info">
                <h5 class="cc-font-weight-6">Push Notifications Are Not Active OR Credentials Are Not Set</h5>
                <div>Please configure your setting to use this functionality. <a href="<?=SITEURL; ?>/admin/config/push_notification.php">click here to configure</a></div>
            </div>
            <?} ?>
        </div>
        <?php include('../footer.php');?>
    </div>
</div>
<script>
    var imgconf='<?=getimgconfig('pushnotification'); ?>';
    jsonarr=JSON.parse(imgconf);
</script>
<script src="<?=SITEURL; ?>/cropimg/dist/cropper.js"></script>
<script src="<?=SITEURL; ?>/cropimg/img_upload.js"></script>
<script src="<?php echo SITEURL; ?>/js/datepicker/moment.js"></script>
<script src="<?php echo SITEURL; ?>/js/datepicker/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="emojionearea-master/dist/emojionearea.min.js"></script>
<script>
$(".aftersend").datetimepicker({ format: 'DD-MM-YYYY HH:mm', minDate: moment(), });
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
                formData.append('img_ratio',img_ratio); formData.append('crop_enabled',crop_enabled);
                formData.append('thumbnail_required',thumbnail_required);   formData.append('img_extension',img_extension);
                formData.append('default_image_width',default_image_width); formData.append('default_image_height',default_image_height);
                formData.append('thumb1_width',thumb1_width); formData.append('thumb2_width',thumb2_width); formData.append('thumb3_width',thumb3_width);
                formData.append('thumb4_width',thumb4_width); formData.append('thumb5_width',thumb5_width);
                formData.append('thumb1_path',thumb1_path); formData.append('thumb2_path',thumb2_path); formData.append('thumb3_path',thumb3_path);
                formData.append('thumb4_path',thumb4_path); formData.append('thumb5_path',thumb5_path);
                formData.append('imgs_location',imgs_location);
                formData.append('notification_id','1');
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
                        stat=JSON.parse(status);
                        if(stat['status']=="success"){ $("#img_url").val(stat['image']); $(".uploadedimg").show().attr("src",stat['imagesrc'])}
                        else{ $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html(stat['message']).delay(3000).fadeOut();
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
function sendnotification(){
    not_title = $(".not-title").val(); not_message = $(".not-message").val(); not_url = $(".not-url").val(); img_url = $("#img_url").val();
    notifschedule = $("input[name=notifschedule]:checked").val(); timelive = $(".aftersend").val();
    info = { not_title:not_title,not_message:not_message,not_url:not_url,img_url:img_url,notifschedule:notifschedule,timelive:timelive,action:"sendnotification" }
    $.ajax({
        type:"POST",
        url:"ajaxdata.php",
        data:info,
        success:function(response){
           if(response==1){
                $(".not-title,.not-message,.not-url,#img_url,.aftersend").val("");  $(".uploadedimg").hide().attr("src","#");
                var $radio = $('input[name=notifschedule]'); $radio[0].checked = true;
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Notification Created").delay(3000).fadeOut();
           }else{
               $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html(response).delay(3000).fadeOut();
           }
        }
    })
}
function changetime(){
    notifschedule = $("input[name=notifschedule]:checked").val();
    if(notifschedule=="After"){ $(".timelater").removeClass("d-none"); } else{ $(".timelater").addClass("d-none"); }
}
if($("#notifschedulepartime").is(':checked')){ $(".timelater").removeClass("d-none"); }


$(document).ready(function() {
    $(".emojionearea-editor").emojioneArea({pickerPosition: "bottom"});
  });
</script>
</body>
</html>