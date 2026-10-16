<?php include ("../../includes/configuration.php");
$getbrand=selectQuery(BRAND,"*"," 1 order by priority"); ?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : brand</title>
    <?php include('../commoncss.php'); ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/uploadImagepopup/cropper.css">
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/uploadImagepopup/main.css">
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/datepicker/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/summernote/summernote-bs4.css">
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/dataTable/dataTables.bootstrap4.min.css">
</head>
<body>
<div class="page-body-wrapper">
    <?php include ('../header.php'); include ('../sidebar.php');
    $brandpath = getimgconfigpaths('brand'); 
    $img_location =  $brandpath[0]['imgs_location']; // Access Object data ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card card-body">
                <div class="alert-info rounded p-3">
                    <h6><b>Image Upload Criteria</b></h6>
                    <ul class="mb-0 pl-3">
                        <li class="mb-1">Image Size Should Be Less Than <b><?php echo $brandpath[0]['max_image_size'] ?> MB</b></li><li>Minimum Dimenssions Should Be <b>Width : 
                        <?php if($brandpath[0]['crop_enabled'] == 1){ echo $brandpath[0]['crop_width']." Px And Height : ".$brandpath[0]['crop_height']; } else{ echo $brandpath[0]['default_image_width']." Px And Height : ".$brandpath[0]['default_image_height'];} ?>
                         Px</b></li>
                    </ul>
                </div>
            </div>
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2">
                    <div><h2 class="card-head-title">Brands In Focus</h2></div>
                    <div class="btn-actions-pane-right">
                        <label class="btn btn-primary btn-sm btn-upload" for="inputImage" title="Upload image file">
                            <input type="file" class="sr-only" id="inputImage" name="file" accept="image/*" onclick="checkcount(event)" onchange="validateimg(this)">
                            <span class="docs-tooltip" data-toggle="tooltip"><span class="fa fa-upload"></span> Upload Image</span>
                        </label>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="progress cc-display-none my-2">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                            </div>
                        </div>
                        <div class="alert cc-display-none" role="alert"></div>
                        <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalLabel">Upload Image</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body"><div class="img-container"><img id="image" src="#" alt="uploadimg"></div></div>
                                    <div class="modal-footer text-right"><button type="button" class="btn btn-primary ml-auto" id="crop">Save</button><button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button></div>
                                </div>
                            </div>
                        </div>
                    </div>                    
                    <div class="feabrand-list" id="feabrandreload">
                        <div id="imgdata">
                            <input type="hidden" id="img_count" value='<?php echo count($getbrand); ?>' >
                            <div class="table-responsive" id="brandreload">
                                <table class="table table-bordered brand-tab-list mb-0 w-100">
                                    <thead><tr><th>#</th><th>Image</th><th>Brand Name</th><th>Edit Brand</th><th>Active / Deactive</th><th>Delete</th></tr></thead>
                                    <tbody>
                                        <?php for($i=0;$i<count($getbrand);$i++) { ?>
                                        <tr id="<?php echo $getbrand[$i]['br_id'];  ?>">
                                            <td class="row-index-tr"><?php echo $i+1; ?></td>
                                            <td>
                                             <div class="testim-thumb"><img src="<?php echo SITEURL."/".$brandpath[0]['imgs_location']."/".$getbrand[$i]['img']; ?>" class="img-fluid imgdivclass" id ="img<?php echo $i+1 ;?>" alt="productimage"></div>
                                            </td>
                                            <td><?php if($getbrand[$i]['brand_name']!=""){echo $getbrand[$i]['brand_name'];}else{echo "<span class='text-danger'>Not Defined</span>";} ?></td>
                                            <td><button onclick="view('<?php echo $getbrand[$i]['br_id']; ?>')" class="btn btn-primary btn-sm">View/Edit</button></td>
                                            <td>
                                                <label class="switch btn btn-primary">
                                                    <input type="checkbox" data-id="<?php echo $getbrand[$i]['br_id']; ?>" id="act_deact<?php echo $getbrand[$i]['br_id']; ?>" name="checkbox_<?php echo $getbrand[$i]['br_id']; ?>" <?php if($getbrand[$i]['isActive']==1){echo "checked";}else{echo "";} ?>  onchange="act_deact('<?php echo $getbrand[$i]['br_id'] ?>')">
                                                    <span class="slider round"><span class="on">Active</span><span class="off">Deactive</span></span>
                                                </label>
                                            </td>
                                            <td class="del-btn-tr">
                                                <button type="button" onclick="del_brand('<?php echo $getbrand[$i]['br_id']; ?>','<?php echo $getbrand[$i]['img']; ?>')" class="deletebtn btn btn-danger btn-sm"><i class="fa fa-trash-o"></i></button>
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
            <div class="viewbrand"></div>
        </div>
    </div>
</div>
<script>
    var imgconf='<?=getimgconfig('brand'); ?>';
    jsonarr=JSON.parse(imgconf);
</script>
<script src="<?=SITEURL; ?>/cropimg/dist/cropper.js"></script>
<script src="<?=SITEURL; ?>/cropimg/img_upload.js"></script>
<script src="<?php echo SITEURL; ?>/js/jquery-ui.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/datepicker/moment.js"></script>
<script src="<?php echo SITEURL; ?>/summernote/summernote-bs4.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/datepicker/bootstrap-datetimepicker.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script>
$(".brand-tab-list tbody").sortable({
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
$('.brand-tab-list').DataTable({ "scrollX": true });
$(".buttons-excel").addClass("ml-1"); $(".dataTables_scroll").addClass("mb-2");
$('.summernote').summernote({ height: 100});
function view(brandid){
    $.ajax({
        type : "POST",
        url : "ajaxdata.php",
        data : {brandid:brandid,action:"get_form_detail"},
        success : function(response){
            $("#myModalLabel").html("Edit Brand")
            $(".viewbrand").html(response);
            $("#myModal1").modal('show');
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
                formData.append('action','upload_image');
                formData.append('img_ratio',img_ratio);   formData.append('crop_enabled',crop_enabled);
                formData.append('thumbnail_required',thumbnail_required);   formData.append('img_extension',img_extension);
                formData.append('default_image_width',default_image_width); formData.append('default_image_height',default_image_height);
                formData.append('thumb1_width',thumb1_width); formData.append('thumb2_width',thumb2_width); formData.append('thumb3_width',thumb3_width);
                formData.append('thumb4_width',thumb4_width); formData.append('thumb5_width',thumb5_width);
                formData.append('thumb1_path',thumb1_path); formData.append('thumb2_path',thumb2_path); formData.append('thumb3_path',thumb3_path);
                formData.append('thumb4_path',thumb4_path); formData.append('thumb5_path',thumb5_path);
                formData.append('imgs_location',imgs_location);  formData.append('webp_quality',webp_quality);       
                formData.append('blog_id','<?php echo base64_decode($_REQUEST['blogid']) ?>');
                formData.append('avatar', blob, 'avatar.jpg');
                $.ajax('ajaxdata.php', {
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
                        if(status=="Upload Success"){                       
                             $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html(status).delay(3000).fadeOut(); $("#feabrandreload").load(location.href + " #imgdata");  }
                        else{                         
                            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html(status).delay(3000).fadeOut();
                            $("#feabrandreload").load(location.href + " #imgdata"); 
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
function del_brand(brandid,type){ del_alertbox("Do you really want to delete this brand?", brandid,"del_brand_db",type); }
function del_brand_db(brandid,type){
    info  = {brandid:brandid,action:"Delete_brand",img_name:type}
    $.ajax({
        type:"POST",
        url:"ajaxdata.php",
        data:info,
        success:function(response){
            response=response.trim();
            if(response== "1"){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Brand deleted successfully").delay(3000).fadeOut();
                $("#feabrandreload").load(location.href + " #imgdata");
                $("#del_popup").modal("hide");
            } else if(response=="0"){
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error.. Try again later").delay(3000).fadeOut();
            }
        }
    })
}
function act_deact(v1){
    var requestedid = v1; var c = $("#act_deact"+requestedid+":checked").val();
    if(c=="on"){ status = 1; res="activated";} else {status = 0; res="deactivated"; }
    var info = {requestedid:requestedid,status:status,action:"active_deactive"};
    $.ajax({
        type:"POST",
        url:"ajaxdata.php",
        data:info,
        success:function(response){
            if(response==1){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Brand " +res).delay(3000).fadeOut();
            } else{
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("ERROR..").delay(3000).fadeOut();
            }
        }
    });
}
function link(){
    var link_type = $("input[name='link_type']:checked"). val(); var brand_name = $("#brand_name").val(); var brandid =  $("#brandid").val();
    if(brand_name!="" && link_type != ""){
        $.ajax({
            type : "POST",
            url : "ajaxdata.php",
            data : {brandid:brandid,brand_type:link_type,brand_name:brand_name,action:"generate_link"},
            success : function(response){
                response = $.trim(response);
                if(response == 2){
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Allready exist").delay(3000).fadeOut();
                } else {
                    $(".generatedlink").html("<div class='divlink'>Preview Product Url : <a href='"+response+"' target='_blank'>"+response+"</a></div>")
                    view(brandid);
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Details updated").delay(3000).fadeOut();
                    $("#feabrandreload").load(location.href + " #imgdata");
                }
            }
        });
    } else{
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please fill data").delay(3000).fadeOut();
    }
}
function updatedata(){
    var link_type = $("input[name='link_type']:checked"). val(); var brand_name = $("#brand_name").val(); var brandid =  $("#brandid").val();
    if(brand_name!="" && link_type != ""){
        $.ajax({
            type : "POST",
            url : "ajaxdata.php",
            data : {brandid:brandid,brand_type:link_type,brand_name:brand_name,action:"updatebranddata"},
            success : function(response){
                response = $.trim(response);
                if(response==1){
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Details updated").delay(3000).fadeOut();
                    view(brandid);
                } else if(response==0){
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error please try after some time").delay(3000).fadeOut();
                } else if(response == 2){
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Allready exist").delay(3000).fadeOut();
                }
            }
        });
    } else{
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please fill data").delay(3000).fadeOut();
    }
}    
function clear_field(){ $("#brand_name").val(""); $(".generatedlink, .remove_url").toggleClass("d-none"); }
function remove(){
    var brandid = $("#brandid").val();
    $.ajax({
        type : "POST",
        url : "ajaxdata.php",
        data : {brandid:brandid,action:"Remove_link"},
        success : function(response){
            response = $.trim(response);
            if(response == 1){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Link removed successfully").delay(3000).fadeOut();
                $(".generatedlink").html("");
                view(brandid);
            } else{
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error.. Please try after some time").delay(3000).fadeOut();
            }
        }
    })    
}
</script>
</body>
</html>