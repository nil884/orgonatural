<? include("../../includes/configuration.php");
$prodid = base64_decode($_REQUEST['prodid']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Upload Image</title>
    <? include "../commoncss.php"; ?>
    <link rel="stylesheet" href="<?=SITEURL;?>/css/bootstrap4-toggle.min.css" />
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/uploadImagepopup/cropper.css">
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/uploadImagepopup/main.css">
</head>
<body>
<div class="page-body-wrapper">
    <? include '../header.php'; ?>
    <? include '../sidebar.php';
    $getproduct_detail = selectQuery(PRODINFO,"vendor,parent_id,variant_name1,variant_name2,variant_name3,variant_value1,variant_value2,variant_value3","id = '".$prodid."'");
    $getconfingdetails = json_decode(getimgconfig('product'));
    $img_location =  $getconfingdetails[0]->imgs_location; // Access Object data
    $img_location =  $getconfingdetails[0]->imgs_location; // Access Object data
    $get_selectedimg_img_count =  selectQuery(PRODIMG,"count(id) as img_count","prod_id = ".$prodid." ");
    // echo $get_selectedimg_img_count[0]['img_count']; ?>
    <div class="main-panel">
        <div class="dashbody"> 
            <div class="card card-body pb-0">
                <div>
                    <label class="btn btn-primary btn-upload btn-sm mb-3" for="inputImage" title="Upload image file">
                        <input type="file" class="sr-only" id="inputImage" name="file" accept="image/*" onclick="checkcount(event)" onchange="validateimg(this)">
                        <span class="docs-tooltip" data-toggle="tooltip"><span class="fa fa-upload"></span> Upload Image  
                        <?php echo getproductOriginalName($getproduct_detail[0]['parent_id'] ) ; echo "    ";
                        if($getproduct_detail[0]['variant_name1'] != ""){
                        echo "For ".getOriginalName($getproduct_detail[0]['variant_name1'])."-".$getproduct_detail[0]['variant_value1']; }
                        if($getproduct_detail[0]['variant_name2'] != ""){
                        echo " || ".getOriginalName($getproduct_detail[0]['variant_name2'])."-".$getproduct_detail[0]['variant_value2']; }
                        if($getproduct_detail[0]['variant_name3'] != ""){
                        echo " || ".getOriginalName($getproduct_detail[0]['variant_name3'])."-".$getproduct_detail[0]['variant_value3']; } ?>
                        </span>
                    </label>
                    <button type="button" onclick="goBack()" class="btn btn-secondary btn-sm mb-3">Back</button>
                </div>
            </div>
            <div class="progress cc-display-none"><div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div></div>
            <div class="alert cc-display-none" role="alert"></div>
            <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalLabel">Crop the image</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <div class="img-container"><img id="image" src="#">
        </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="crop">Crop</button>
                        </div>
                    </div>
                </div>
            </div>
            <div id="imgdata">
                <input type="hidden" id="img_count" value='<?php echo $get_selectedimg_img_count[0]['img_count'] ?>' >
                <?php if($getproduct_detail[0]['parent_id'] != "0") {
                $getinv = selectQuery(PRODINFO,"id,parent_id,variant_name1,variant_name2,variant_name3,variant_value1,variant_value2,variant_value3","parent_id = ".$getproduct_detail[0]['parent_id']." and vendor='".$getproduct_detail[0]['vendor']."'order by id asc");
                } else {
                    $getinv = selectQuery(PRODINFO,"*","id = ".$prodid." ");
                } ?>
                <?php if(count($getinv)) { ?>
                <?php for($i=0;$i<count($getinv);$i++){?>
                <div class="card p-3">
                    <div class="mb-2">
                        <h6>
                            <b>Inventory -</b><a href="uploadimg.php?prodid=<?php echo  base64_encode($getinv[$i]['id']) ?>"><?php echo getproductOriginalName($getinv[$i]['parent_id'] ) ; echo " ";
                            if($getinv[$i]['variant_name1'] != ""){
                            echo getOriginalName($getinv[0]['variant_name1'])."-".$getinv[$i]['variant_value1'];
                            }
                            if($getinv[$i]['variant_name2'] != ""){
                            echo " | ".getOriginalName($getinv[0]['variant_name2'])."-".$getinv[$i]['variant_value2'];
                            }
                            if($getinv[$i]['variant_name3'] != ""){
                            echo " | ".getOriginalName($getinv[0]['variant_name3'])."-".$getinv[$i]['variant_value3'];       
                            } ?>
                            </a>
                        </h6>
                    </div>
                    <div class="row">
                        <?php $getimagesinv = selectQuery(PRODIMG,"id,img_name,prod_id,apply_to_all"," prod_id = '".$getinv[$i]['id']."' order by id DESC");
                        if(count($getimagesinv)){
                        for($j=0;$j<count($getimagesinv);$j++){ ?>
                        <div class="col-6 col-sm-4 col-md-3 col-lg-3 col-xl-2">
                            <div class="position-relative uped-prod-thumb border p-2 mb-2">
                                <img src="<? echo SITEURL."/".$img_location."/".$getimagesinv[$j]['img_name']; ?>" alt="" class="img-fluid"/>
                                <span class="del-upload-pic text-center removeopt pro-attr-badge-action shadow-sm btn btn-danger p-1 rounded-circle" onclick="del_prod_img('<?php echo $getimagesinv[$j]['id'] ?>','<?php echo $getimagesinv[$j]['img_name'] ?>')"><i class="fa fa-trash"></i></span>
                            </div>                        
                            <div class="custom-control custom-checkbox">
                                <?php if($getproduct_detail[0]['parent_id'] != 0  && (count($getinv)>1) ){?> <input type="checkbox"  onclick="applyto_all('<?php echo $getimagesinv[$j]['img_name'] ?>','<?php echo $getproduct_detail[0]['parent_id'] ?>','<?php echo $getimagesinv[$j]['prod_id'] ?>')" <?php if($getimagesinv[$j]['apply_to_all'] ==1 ) { echo " checked disabled";} ?> class="custom-control-input" id="<?='apl'.$i.$j; ?>"> 
                                <label class="custom-control-label cc-cursor-pointer" for="<?='apl'.$i.$j; ?>">Use for all Variation</label>
                                <?php } ?>
                            </div>
                        </div>
                        <? } }  else {
                            echo "<div class='col-md-12 text-muted'>No Image Available</div>";
                        } ?>
                    </div>
                </div>
                <?php } } ?>
            </div>
            <div class="d-none">
                <img class="rounded" id="avatar" src="">
                <input type="file" class="sr-only" id="input" name="image" accept="image/*">
            </div>            
            <!--<button type="button" class="btn btn-primary"  onclick="finish()">Finish</button>-->
        </div>
        <? include "../footer.php"; ?>
    </div>
</div>
<script>
    var imgconf='<?=getimgconfig('product'); ?>';
    jsonarr=JSON.parse(imgconf);
</script>
<script src="<?=SITEURL; ?>/cropimg/dist/cropper.js"></script>
<script src="<?=SITEURL; ?>/cropimg/img_upload.js"></script>
<script>
function applyto_all(img_name,parent_id,mainprod){
    info = {parent_id:parent_id,mainprod:mainprod,action:"applyto_all",img_name:img_name}
    $.ajax({
        type:"POST",
        url:"<?php echo VENDORURL ?>product/ajaxdata.php",
        data:info,
        success:function(response){
            response=response.trim();
            if(response== "1"){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Image applied to all inventort successfully").delay(3000).fadeOut();
                $("#imgdata").load(  " #imgdata");
            }
            else if(response=="0"){
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error.. Try again later").delay(3000).fadeOut();
            }
        }
    })
}


window.addEventListener('DOMContentLoaded', function () {
    document.getElementById('crop').addEventListener('click', function (){
        $modal.modal('hide');
        if (cropper) {
            canvas = cropper.getCroppedCanvas({ width: crop_width, height: crop_height});
            initialAvatarURL = avatar.src;
            avatar.src = canvas.toDataURL();
            $progress.show();
            $alert.removeClass('alert-success alert-warning');
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
                formData.append('prod_id','<?php echo base64_decode($_REQUEST['prodid']) ?>'); 
                formData.append('avatar', blob, 'avatar.jpg');
               /* var myReader = new FileReader();
                myReader.onload = function(event){ console.log(JSON.stringify(myReader.result)); };
                myReader.readAsText(blob);*/
                $.ajax('<?php echo VENDORURL ?>product/ajaxdata.php', {
                    method: 'POST', data: formData,  processData: false, contentType: false,
                    xhr: function () {
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
                    success: function(status) {
                    if(status=="Upload Success"){ $alert.show().addClass('alert-success').text(status); $("#imgdata").load(  " #imgdata");}else{
                        $alert.show().addClass('alert-danger').text(status);
                    }},
                    error: function() { avatar.src = initialAvatarURL; $alert.show().addClass('alert-warning').text('Upload error'); },
                    complete: function() { $progress.hide(); },
                });
            });
        }
    });
});

function checkcount(e){
    allowedcount=max_image_count;
    var img_count= $("#img_count").val();
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
    
function del_prod_img(imgid,type){
    del_alertbox("Do you really want to delete this Image?", imgid,"del_image_db",type);
}

function del_image_db(id,type) {
    info = {imgid:id,action:"Delete_image",img_name:type}
    $.ajax({
        type:"POST",
        url:"<?php echo VENDORURL ?>product/ajaxdata.php",
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
    });
}

function finish(){
    location.href = '<? echo  ADMINURL ?>product/viewproduct.php ';
}
</script>
</body>
</html>