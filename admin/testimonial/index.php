<?php include ("../../includes/configuration.php");?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Update Testimonial</title>
    <?php include('../commoncss.php'); ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/uploadImagepopup/cropper.css">
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/uploadImagepopup/main.css">
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/dataTable/dataTables.bootstrap4.min.css">
</head>
<body>
<div class="page-body-wrapper">
    <?php include ('../header.php'); include ('../sidebar.php');
    $testimonials = selectQuery(TESTIMONIALS,"id,img_name,isActive"," 1 order by priority ");
    $testimonialpath = getimgconfigpaths('testimonials'); ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card card-body">
                <div class="alert-info rounded p-3">
                    <h6><b>Image Upload Criteria</b></h6>
                    <ul class="mb-0 pl-3">
                        <li class="mb-1">Image Size Should Be Less Than <b><?php echo $testimonialpath[0]['max_image_size'] ?> MB</b></li><li>Minimum Dimenssions Should Be <b>Width : 
                        <?php if($testimonialpath[0]['crop_enabled'] == 1){ echo $testimonialpath[0]['crop_width']." Px And Height : ".$testimonialpath[0]['crop_height']; } else{ echo $testimonialpath[0]['default_image_width']." Px And Height : ".$testimonialpath[0]['default_image_height'];} ?>
                         Px</b></li>
                    </ul>
                </div>
            </div>
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2">
                    <div><h2 class="card-head-title">Testimonial</h2></div>
                    <div class="btn-actions-pane-right"><label class="btn btn-primary btn-upload btn-sm" for="inputImage" title="Upload image file"><input type="file" class="sr-only" id="inputImage" name="file" accept="image/*" onclick="checkcount(event)" onchange="validateimg(this)"><span class="docs-tooltip" data-toggle="tooltip" title="Import image with Blob URLs"><span class="fa fa-upload"></span> Upload Image</span></label></div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12"><div class="progress cc-display-none my-2"><div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div></div></div>
                        <div class="alert cc-display-none" role="alert"></div>
                        <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalLabel">Upload Image</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    </div>
                                    <div class="modal-body"><div class="img-container"><img id="image" src="#" alt="uploadimg"></div></div>
                                    <div class="modal-footer text-right"><button type="button" class="btn btn-primary ml-auto" id="crop">Save</button><button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="testlistcol" id="testreload">
                        <div id="imgdata">
                            <input type="hidden" id="img_count" value='<?php echo count($testimonials); ?>'>
                            <table class="table table-bordered mb-0 testimonial-data-table w-100">
                                <thead><tr><th>#</th><th>Image</th><th>View/Edit</th><th>Active/De-active</th><th class="text-center">Delete</th></tr></thead>
                                <tbody class="tbodyrow">
                                    <?php for($i=0;$i<count($testimonials);$i++){ ?>
                                    <tr id="<? echo $testimonials[$i]['id']; ?>" class="mainslider_list">
                                        <td class="row-index-tr"><?php echo $i+1; ?></td>
                                        <td><div class="testim-thumb"><img src="<? echo SITEURL."/".$testimonialpath[0]['imgs_location']."/".$testimonials[$i]['img_name']; ?>" alt="testimonial-thumb" class="img-fluid img-thumbnail"/></div>
                                    </td>
                                    <td><button class="btn btn-primary btn-sm" onclick="view('<?php echo $testimonials[$i]['id']; ?>')">View/Edit</button></td>
                                    <td>
                                        <label class="switch btn btn-primary"><input type="checkbox" data-id="<?php echo $testimonials[$i]['id']; ?>" id="act_deact<?php echo $testimonials[$i]['id']; ?>" name="checkbox_<?php echo $testimonials[$i]['id']; ?>" <?php if($testimonials[$i]['isActive']==1){echo "checked";}else{echo "";} ?> onchange="act_deact('<?php echo $testimonials[$i]['id'] ?>')"> <span class="slider round"><span class="on">Active</span><span class="off">Deactive</span></span></label>
                                    </td>
                                    <td class="text-center del-btn-tr">
                                        <button type="button" onclick="del_img('<?php echo $testimonials[$i]['id']; ?>','<?php echo $testimonials[$i]['img_name']; ?>')" class="deletebtn btn btn-danger btn-sm"><i class="fa fa-trash-o"></i></button></td>
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
<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">                
                <h4 class="modal-title" id="myModalLabel">&nbsp;</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" ><span aria-hidden="true">&times;</span></button>
            </div>
            <div id="modal-body"></div>
        </div>
    </div>
</div>
<script src="<?php echo SITEURL; ?>/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script>
    var imgconf='<?=getimgconfig('testimonials'); ?>';
    jsonarr=JSON.parse(imgconf);
</script>
<script src="<?=SITEURL; ?>/cropimg/dist/cropper.js"></script>
<script src="<?=SITEURL; ?>/cropimg/img_upload.js"></script>
<script src="<?php echo SITEURL; ?>/js/jquery-ui.min.js"></script>
<script>

    $(".testimonial-data-table tbody").sortable({
    update: function(){
        str = "";
        $(this).children().each(function(index) { str += $(this).attr('id') + ","; });
        str = str.replace(/,\s*$/, "");
        var info = { action: "priority",str: str };
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
$('.testimonial-data-table').DataTable({
  "searching": false, "scrollX": true
});
function view(imgid){
    $.ajax({
        type:"POST",
        url:"ajaxdata.php",
        data:{img_id:imgid,action:"getdetails"},
        success:function(response){
            $("#myModalLabel").html("Update Testimonial Details");
            $("#modal-body").html(response);
            $("#myModal1").modal("show");
            
            var set = 200;
            var loadtestcomval = $('#testimonials').val(),
            loadtestcomvallength = loadtestcomval.length,
            loadtestcomvalremain = parseInt(set - loadtestcomvallength);
            $('.charlimitmessage').text(loadtestcomvalremain);
            
            $('#testimonials').keypress(function(e){
                var tval = $('#testimonials').val(),
                tlength = tval.length,
                remain = parseInt(set - tlength);
                $('.charlimitmessage').text(remain);
                if (remain <= 0 && e.which !== 0 && e.charCode !== 0){
                    $('#testimonials').val((tval).substring(0, tlength - 1))
                }
            });
        }
    });
}
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
            formData.append('imgs_location',imgs_location); formData.append('webp_quality',webp_quality);
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
                        if(status=="Upload Success"){       $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html(status).delay(3000).fadeOut(); location.reload(); $/*("#testreload").load(location.href + " #imgdata");*/ }
                        else{ $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html(status).delay(3000).fadeOut();
                    }},
                    error: function() { avatar.src = initialAvatarURL; $alert.show().addClass('alert-warning').text('Upload error'); },
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
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Only "+allowedcount+" images are allowed").delay(3000).fadeOut();
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
function del_image_db(id,type) {
    info = {imgid:id,action:"Delete_image",img_name:type}
    $.ajax({
        type:"POST",
        url:"ajaxdata.php",
        data:info,
        success:function(response){
            response=response.trim();
            if(response == "1"){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Testimonial deleted successfully").delay(3000).fadeOut();                
                $("#del_popup").modal("hide");
                location.reload(); $/*("#testreload").load(location.href + " #imgdata");*/
            } else if(response=="0"){
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error.. Try again later").delay(3000).fadeOut();
            }
        }
    })
}
function act_deact(v1){
    var requestedid = v1;
    var c = $("#act_deact"+requestedid+":checked").val();
    if(c=="on"){ status = 1; res="activated";} else {status = 0; res="Deactivated"; }
    var info = {requestedid:requestedid,status:status,action:"active_deactive"};
    $.ajax({
        type:"POST",
        url:"ajaxdata.php",
        data:info,
        success:function(response){
            if(response==1){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Testimonial " +res).delay(3000).fadeOut();
            } else {
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("ERROR..").delay(3000).fadeOut();
            }
        }
    });
}
function updatedata(){
    var client_name = $("#client_name").val(); var prod_name = $("#prod_name").val(); var testimonials = $("#testimonials").val(); var testimcomlength = $("#testimonials").val().length; var img_id = $("#img_id").val();
    if(client_name == ""){
        $('.popupmsg').fadeIn().addClass("alert alert-danger").html("Please enter client name").delay(3000).fadeOut();
    } else if(prod_name == ""){
        $('.popupmsg').fadeIn().addClass("alert alert-danger").html("Please enter product name").delay(3000).fadeOut();
    } else if(testimonials == ""){
        $('.popupmsg').fadeIn().addClass("alert alert-danger").html("Please enter testimonial details").delay(3000).fadeOut();
    } else if(testimcomlength > 200){
        $('.popupmsg').fadeIn().addClass("alert alert-danger").html("Please enter max 200 characters testimonials").delay(3000).fadeOut();
    } else{
        $.ajax({
            type:"POST",
            url:"ajaxdata.php",
            data:{img_id:img_id,client_name:client_name,prod_name:prod_name,testimonials:testimonials,action:"update_client_detail"},
            success:function(response){
                if(response==1){
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Testimonial  detail updated successfully").delay(3000).fadeOut();
                    $("#myModal1").modal("hide");
                    $("#testreload").load(location.href + " #imgdata");
                } else{
                    $('.popupmsg').fadeIn().addClass("alert alert-danger").html("ERROR..").delay(3000).fadeOut().modal("hide");
                }
            }
        });
    }
}
</script>
</body>
</html>