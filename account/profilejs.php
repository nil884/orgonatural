<script>
    var imgconf='<?=getimgconfig('buyer profile'); ?>';
    jsonarr = JSON.parse(imgconf);
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
                $.ajax('<?php echo SITEURL; ?>/ajax/common_ajax.php', {
                    method: 'POST', data: formData,  processData: false, contentType: false,
                    xhr: function(){
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
    allowedcount=max_image_count;
    var img_count= $("#img_count").val();
    if(parseInt(img_count)>=parseInt(allowedcount)){
        alert("Only "+allowedcount+" images are allowed");
        e.preventDefault();
    }
}
function validateimg(file){
    var FileSize = file.files[0].size / 1024 / 1024; // in MB
    if(max_image_size<FileSize){
        alert("Max "+max_image_size+" MB size is allowed");
        $(file).val("");
        e.preventDefault();
    }
} 
function del_Profile_img(imgid,type){
    del_alertbox("Do you really want to delete this Image?", imgid,"del_image_db",type);
}
function del_alertbox(msg,i,funct,type){
    $("#del_popup").modal("show"); 
    $("#del_popup .modal-dialog").addClass("modal-sm");
    $("#del_message").html(msg);
    $("#popup_ok").attr("onclick", funct+"('" + i + "','" + type + "')");
}
function del_image_db(id,type){
    info = {profile_id:id,action:"Delete_profile_image",img_name:type}
    $.ajax({
        type:"POST",
        url:"<?php echo SITEURL; ?>/ajax/common_ajax.php",
        data:info,
        success:function(response){
            response=response.trim();
            if(response== "1"){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Image Deleted Successfully").delay(3000).fadeOut();
                $("#imgdata").load(  " #imgdata");
                $("#del_popup").modal("hide");
                location.reload();
            } else if(response=="0"){
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error.. Try Again Later").delay(3000).fadeOut();
            }
        }
    })
}
$('.account-user-poc').on('hidden.bs.modal', function (){ location.reload(); });
</script>