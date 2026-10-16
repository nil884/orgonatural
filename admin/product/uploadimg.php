<? include("../../includes/configuration.php");
$prodid = base64_decode($_REQUEST['prodid']); ?>
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
    $getconfingdetails = getimgconfigpaths('product');
    $img_location = $getconfingdetails[0]['imgs_location'];
    $get_selectedimg_img_count = selectQuery(PRODIMG,"count(id) as img_count","prod_id = ".$prodid." ");
    // echo $get_selectedimg_img_count[0]['img_count'];
$url = SITEURL."/vendors/product/save_basic.php";
     ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center <?php if($_SERVER['HTTP_REFERER'] != $url) { echo "py-2"; } ?>">
                    <div><h5 class="card-head-title">Upload Image</h5></div><div class="btn-actions-pane-right">
              <?php  
                      if($_SERVER['HTTP_REFERER'] != $url) {
               ?>
                    <button onclick="goBack()" class="btn btn-secondary btn-sm">Back</button>
                    <?php  }  ?>
                </div></div>
                <div class="card-body">
                <div class="alert-info rounded p-3">
                    <h6><b>Image Upload Criteria</b></h6>
                    <ul class="mb-0 pl-3">
                        <li class="mb-1">Image Size Should Be Less Than <b><?php echo $getconfingdetails[0]['max_image_size'] ?> MB</b></li><li>Minimum Dimenssions Should Be <b>Width : 
                        <?php if($getconfingdetails[0]['crop_enabled'] == 1){ echo $getconfingdetails[0]['crop_width']." Px And Height : ".$getconfingdetails[0]['crop_height']; } else{ echo $getconfingdetails[0]['default_image_width']." Px And Height : ".$getconfingdetails[0]['default_image_height'];} ?>
                         Px</b></li><li class="mb-1">You can upload only <b><?php echo $getconfingdetails[0]['max_image_count'] ?>  images</b></li>
                    </ul>
                </div>
                </div>
            </div>
            <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header"><h5 class="modal-title" id="modalLabel">Upload Image</h5><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
                        <div class="modal-body"><div class="img-container"><img id="image" src="#" alt="upload-img"></div></div>
                        <div class="modal-footer py-2"><button type="button" class="btn btn-primary ml-auto" id="crop" data-id= "">Save</button><button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button></div>
                    </div>
                </div>
            </div>
            <div id="imgdata">
                <?php if($getproduct_detail[0]['parent_id'] != "0"){
                $getinv = selectQuery(PRODINFO,"id,parent_id,variant_name1,variant_name2,variant_name3,variant_value1,variant_value2,variant_value3","parent_id = ".$getproduct_detail[0]['parent_id']." and vendor='".$getproduct_detail[0]['vendor']."'order by id asc");
                } else { $getinv = selectQuery(PRODINFO,"*","id = ".$prodid." "); } ?>
                <?php if(count($getinv)) { ?> 
                <?php for($i=0;$i<count($getinv);$i++){
                $varcnt = $i;
                $getimagesinv = selectQuery(PRODIMG,"id,img_name,prod_id,apply_to_all"," prod_id = '".$getinv[$i]['id']."' order by id DESC"); ?>
                <div class="card">
                    <input type="hidden" id="img_count<?php echo $i ?>" value='<?php echo count($getimagesinv); ?>' >
                    <div class="card-header sec-card-head justify-content-between align-items-center py-2">
                            <?php $varstr="";
                            if($getinv[$i]['variant_name1']!=""||$getinv[$i]['variant_name2']!=""||$getinv[$i]['variant_name3']!=""){
                                $varstr.="(";
                                 if($getinv[$i]['variant_name1'] != ""){
                                    $varstr.= getOriginalName($getinv[0]['variant_name1'])." - ".$getinv[$i]['variant_value1'];
                                } if($getinv[$i]['variant_name2'] != ""){
                                    $varstr.=" | ".getOriginalName($getinv[0]['variant_name2'])." - ".$getinv[$i]['variant_value2'];
                                } if($getinv[$i]['variant_name3'] != ""){
                                   $varstr.= " | ".getOriginalName($getinv[0]['variant_name3'])." - ".$getinv[$i]['variant_value3'];
                                }
                                 $varstr.=")";
                            }

                        ?>
                        <div><h5 class="card-head-title mb-2 mb-lg-0"><b>Inventory - </b><a href="uploadimg.php?prodid=<?php echo base64_encode($getinv[$i]['id']) ?>"><?php echo getproductOriginalName($getinv[$i]['parent_id'] ) ; echo " ".$varstr;  ?></a></h5></div>
                        <div class="btn-actions-pane-right"><label class="btn btn-primary btn-sm btn-upload" for="inputImage<?php echo $i ?>" title="Upload image file"><input type="file" class="sr-only" id="inputImage<?=$i; ?>" name="file" accept="image/*" data-cnt="<?=$i; ?>" onclick="checkcount(event,'<?=$i; ?>')" onchange="validateimg(this)" data-id='<?php echo $getinv[$i]['id'] ?>'><span class="docs-tooltip" data-toggle="tooltip"><span class="fa fa-upload"></span> Upload Image</span></label></div>
                    </div>
                    <div class="card-body">
                    <div><div class="mb-3 progress p<?php echo $getinv[$i]['id']  ?> cc-display-none"><div class="progress-bar p1<?php echo $getinv[$i]['id']  ?> progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div></div><div class="alert a1<?php echo $getinv[$i]['id'] ?> cc-display-none" role="alert"></div></div>
                    <div class="row mx-0 sortable">
                        <?php $getimagesinv = selectQuery(PRODIMG,"id,img_name,prod_id,apply_to_all"," prod_id = '".$getinv[$i]['id']."' order by priority ASC,id DESC");
                        if(count($getimagesinv)){
                        for($j=0;$j<count($getimagesinv);$j++){ ?>
                        <div class="mb-3 mr-1 mr-sm-2 inven-chk-col" id='<?=$getimagesinv[$j]['id']; ?>' data-prod='<?=$getinv[$i]['id']; ?>'>
                            <div class="p-2 mb-2 position-relative iven-thumb-col border d-flex flex-wrap align-content-center rounded uped-prod-thumb">
                                <img src="<? echo SITEURL."/".$img_location."/".$getimagesinv[$j]['img_name']; ?>" alt="" class="img-fluid" alt="inventory image"/>
                                <span class="del-upload-pic text-center removeopt pro-attr-badge-action shadow-sm btn btn-danger btn-sm p-1" onclick="del_prod_img('<?php echo $getimagesinv[$j]['id'] ?>','<?php echo $getimagesinv[$j]['img_name'] ?>')"><i class="fa fa-trash"></i></span>
                            </div>                        
                            <div class="custom-control custom-checkbox">
                                <?php if($getproduct_detail[0]['parent_id'] != 0  && (count($getinv)>1) ){?> <input type="checkbox" onclick="applyto_all('<?php echo $getimagesinv[$j]['img_name'] ?>','<?php echo $getproduct_detail[0]['parent_id'] ?>','<?php echo $getimagesinv[$j]['prod_id'] ?>')" <?php if($getimagesinv[$j]['apply_to_all'] ==1 ){ echo " checked disabled";} ?> class="custom-control-input" id="<?='apl'.$i.$j; ?>"> 
                                <label class="custom-control-label cc-cursor-pointer" for="<?='apl'.$i.$j; ?>">Use for all Variation</label>
                                <?php } ?>
                            </div>
                        </div>
                        <? } } else{ echo "<div class='col-md-12 text-muted'>No image available</div>"; } ?>
                    </div>
                </div>
                </div>
                <?php } } ?>
            </div>
            <div class="d-none"><img class="rounded" id="avatar" src="#" alt="upload-img"><input type="file" class="sr-only" id="input" name="image" accept="image/*"></div>            
        </div>
        <? include "../footer.php"; ?>
    </div>
</div>
<script>
    var imgconf='<?=getimgconfig('product'); ?>';
    jsonarr=JSON.parse(imgconf);
</script>
<script src="<?=SITEURL; ?>/cropimg/img_upload.js"></script>
<script src="<?=SITEURL; ?>/cropimg/dist/cropper.js"></script>
<script src="<?php echo SITEURL; ?>/js/jquery-ui.min.js"></script>
<script>
$(".sortable").sortable({
    update: function(){
      
        str = "";
        prodid="<?=$getinv[$i]['id']; ?>"
        $(this).children().each(function(index) { str += $(this).attr('id') + ","; });
        str = str.replace(/,\s*$/, "");
        var info = { action: "priority_image",str: str };
      
         $.ajax({
            data: info,
            type: 'POST',
            url: '<?=SITEURL; ?>/ajax/product_ajax.php',
            success: function(result){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Priority updated successfully").delay(3000).fadeOut();
            }
        })
    }
});  
$(document).ajaxSuccess(function() {
    $(".sortable").sortable({
    update: function(){
        str = "";
        prodid="<?=$getinv[$i]['id']; ?>"
        $(this).children().each(function(index) { str += $(this).attr('id') + ","; });
        str = str.replace(/,\s*$/, "");
        var info = { action: "priority_image",str: str };
      
         $.ajax({
            data: info,
            type: 'POST',
            url: '<?=SITEURL; ?>/ajax/product_ajax.php',
            success: function(result){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Priority updated successfully").delay(3000).fadeOut();
            }
        })
    }
});  
})

jsonarr = JSON.parse(imgconf);
var crop_enabled = jsonarr[0]['crop_enabled'];
var crop_height = jsonarr[0]['crop_height'];var crop_width = jsonarr[0]['crop_width'];
var default_image_height = jsonarr[0]['default_image_height']; var default_image_width = jsonarr[0]['default_image_width'];
var img_extension = jsonarr[0]['img_extension'];
function gcd(a, b){ return (b == 0) ? a : gcd (b, a%b); }
var r = gcd (crop_width, crop_height);
aspect1 = parseInt(crop_width)/r; aspect2 = parseInt(crop_height)/r;
var img_type = jsonarr[0]['img_type']; var max_image_count = jsonarr[0]['max_image_count']; var max_image_size=jsonarr[0]['max_image_size']; var imgs_location = jsonarr[0]['imgs_location']; var thumbnail_required = jsonarr[0]['thumbnail_required']; var thumb1_width = jsonarr[0]['thumb1_width']; var thumb2_width = jsonarr[0]['thumb2_width']; var thumb3_width = jsonarr[0]['thumb3_width']; var thumb4_width = jsonarr[0]['thumb4_width']; var thumb5_width = jsonarr[0]['thumb5_width']; var thumb1_path = jsonarr[0]['thumb1_path']; var thumb2_path = jsonarr[0]['thumb2_path']; var thumb3_path = jsonarr[0]['thumb3_path'];  var thumb4_path = jsonarr[0]['thumb4_path']; var thumb5_path = jsonarr[0]['thumb5_path']; var avatar = document.getElementById('avatar'); var image = document.getElementById('image'); //var input = document.getElementById('inputImage');
/*var input = document.getElementById('aspratio');*/
 var $modal = $('#modal'); var cropper;
var img_ratio = aspect1+":"+ aspect2;
window.addEventListener('DOMContentLoaded', function(){
$modal.on('shown.bs.modal', function(){
        cropper = new Cropper(image, {
            aspectRatio: parseInt(aspect1)/parseInt(aspect2), restore: false, guides: false, center: false,autoCrop:(crop_enabled==0?false:true),
            dragMode: 'move',
            cropBoxMovable: false,
            cropBoxResizable: false,
            toggleDragModeOnDblclick: false,
        });
    }).on('hidden.bs.modal', function(){ cropper.destroy();cropper = null; });
});
</script>
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
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Image applied to all inventort Successfully").delay(3000).fadeOut();
                $("#imgdata").load(  " #imgdata>*");
            } else if(response=="0"){
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error.. Try Again Later").delay(3000).fadeOut();
            }
        }
    })
}



window.addEventListener('DOMContentLoaded', function(){
document.getElementById('crop').addEventListener('click', function (){
        $modal.modal('hide');
        if (cropper){
        	invid = $("#crop").attr('data-id');
            var $progress = $('.p'+invid); var $progressBar = $('.p1'+invid);  var $alert = $('.a1'+invid);
            canvas = cropper.getCroppedCanvas({ width: crop_width, height: crop_height,imageSmoothingEnabled: true,imageSmoothingQuality: 'high'});
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
                formData.append('imgs_location',imgs_location);  formData.append('webp_quality',webp_quality);
                formData.append('prod_id',invid); 
                formData.append('avatar', blob, 'avatar.jpg');
                $.ajax('<?php echo VENDORURL ?>product/ajaxdata.php', {
                    method: 'POST', data: formData,  processData: false, contentType: false, 
                    xhr: function () {
                        var xhr = new XMLHttpRequest();
                        xhr.upload.onprogress = function (e){
                            var percent = '0'; var percentage = '0%';
                            if (e.lengthComputable){
                                percent = Math.round((e.loaded / e.total) * 100);
                                percentage = percent + '%';
                                $progressBar.width(percentage).attr('aria-valuenow', percent).text(percentage);
                            }
                        };
                        return xhr;
                    },
                    success: function(status) {
                    if(status=="Upload Success"){ $alert.show().addClass('alert-success').text(status); $("#imgdata").load(  " #imgdata>*");}else{
                        $alert.show().addClass('alert-danger').text(status);
                    }},
                    error: function(){ avatar.src = initialAvatarURL; $alert.show().addClass('alert-warning').text('Upload error'); },
                    complete: function(){ $progress.hide(); },

                });
            });   /* , 'image/jpeg', 1 */
        }
    });
});
function checkcount(e,vari){
    allowedcount = max_image_count;
    var img_count = $("#img_count"+vari).val();
    if(parseInt(img_count)>=parseInt(allowedcount)){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg ").html("Only "+allowedcount+" images are allowed").delay(3000).fadeOut();
        e.preventDefault();
    }
}
function validateimg(file){
    var FileSize = file.files[0].size / 1024 / 1024; // in MB
    var idname = $(file).attr("id");
    var inv_id = $(file).attr("data-id");
    if(max_image_size<FileSize){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg ").html("Max "+max_image_size+" MB size is allowed").delay(3000).fadeOut();
        $(file).val("");
        e.preventDefault();
    } else{
     var input = document.getElementById(idname);
     var files = file.files;
     $("#crop").attr('data-id',inv_id)
        var done = function (url){
            input.value = ''; image.src = url; //$alert.hide();
            $modal.modal('show');
        };
        var reader;var file; var url;
        if(files && files.length > 0){
            file = files[0];
            if(URL){ done(URL.createObjectURL(file)); 
            } else if(FileReader){
                reader = new FileReader();
                reader.onload = function(e){ done(reader.result);};
                reader.readAsDataURL(file);
            }
        }
    }
}
function del_prod_img(imgid,type){ del_alertbox("Do you really want to delete this Image?", imgid,"del_image_db",type); }
function del_image_db(id,type){
    info = {imgid:id,action:"Delete_image",img_name:type}
    $.ajax({
        type:"POST",
        url:"<?php echo VENDORURL ?>product/ajaxdata.php",
        data:info,
        success:function(response){
            response=response.trim();
            if(response== "1"){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Image Deleted Successfully").delay(3000).fadeOut();
                $("#imgdata").load(  " #imgdata>*");
                $("#del_popup").modal("hide"); 
            } else if(response=="0"){
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error.. Try Again Later").delay(3000).fadeOut();
            }
        }
    });
}
function finish(){ location.href = '<? echo  ADMINURL ?>product/viewproduct.php '; }
</script>
</body>
</html>