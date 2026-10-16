<?php include ("../../includes/configuration.php");
include("../../cropimg/create-thumbnail.php");
include("../../cropimg/commonfunctions.php");
if($action=="upload image"){
    $imgs_location = $_POST['imgs_location'];
    $url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    $path = $_SERVER['HTTP_HOST']."/".$imgs_location;
    $target_path = getRelativePath($url,$path)."/";
    $crop_enabled = $_POST['crop_enabled'];  $thumbnail_required = $_POST['thumbnail_required'];  $img_extension = $_POST['img_extension'];
    $default_image_width = $_POST['default_image_width']; $default_image_height=$_POST['default_image_height'];
    $thumb1width = $_POST['thumb1_width']; $thumb2width = $_POST['thumb2_width']; $thumb3width = $_POST['thumb3_width'];
    $thumb4width = $_POST['thumb4_width']; $thumb5width = $_POST['thumb5_width'];
    $thumb1path = $_POST['thumb1_path']; $thumb2path = $_POST['thumb2_path']; $thumb3path = $_POST['thumb3_path'];
    $thumb4path = $_POST['thumb4_path']; $thumb5path = $_POST['thumb5_path'];  $webp_quality = $_POST['webp_quality'];
    if(isset($_FILES['avatar']) and !$_FILES['avatar']['error']){
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $_FILES['avatar']['tmp_name']);
    if($img_extension=="jpg"){$ext="jpeg";}else if($img_extension=="png"){$ext="png";}else{
        if($mime=="image/png"){$ext="png";}elseif($mime=="image/jpg"){$ext="jpeg";}elseif($mime=="image/jpeg"){$ext="jpeg";}
    }
    try{
        $fn ="testimonial-".rand(100,999);
        $fnameo =$fn.".".$ext;
        if(!move_uploaded_file($_FILES['avatar']['tmp_name'], $target_path.$fnameo)){
            throw new Exception($_FILES["avatar"]["error"]);
        }else{
            if($img_extension=="webp"){

                 $fname = $fn.'.webp';
                if(createwebp($target_path,$fnameo,$fname,$webp_quality)){ //if webp image created then delete old image
                    unlink($target_path.$fnameo);
                }else{ $fname=  $fnameo;}
            }else{ $fname=  $fnameo; }
            if($crop_enabled==0){
                list($width, $height, $type, $attr) = getimagesize($target_path.$fname);
                if($default_image_width<$width){
                $dest0=$target_path.$fname;
                if($width>$height)
                    createThumbnail($target_path.$fname, $dest0, $default_image_width);
                else if($width<$height)
                    createThumbnail($target_path.$fname, $dest0, "", $default_image_height);
                else
                    createThumbnail($target_path.$fname, $dest0, $default_image_width);
                }
            }
            $data0=array("img_name"=>$fname,);
            insertQuery(TESTIMONIALS,$data0);
            if($thumbnail_required){
               if($thumb1width){ $thumb1store= getthumbnailpath($url,$imgs_location,$thumb1path); createThumbnail($target_path.$fname, $thumb1store.$fname, $thumb1width);}
               if($thumb2width){ $thumb2store= getthumbnailpath($url,$imgs_location,$thumb2path);createThumbnail($target_path.$fname, $thumb2store.$fname, $thumb2width);}
               if($thumb3width){ $thumb3store= getthumbnailpath($url,$imgs_location,$thumb3path); createThumbnail($target_path.$fname, $thumb3store.$fname, $thumb3width); }
               if($thumb4width){ $thumb4store= getthumbnailpath($url,$imgs_location,$thumb4path);createThumbnail($target_path.$fname, $thumb4store.$fname, $thumb4width);}
               if($thumb5width){ $thumb5store= getthumbnailpath($url,$imgs_location,$thumb5path);createThumbnail($target_path.$fname, $thumb5store.$fname, $thumb5width); }
            }
           echo "Upload Success";
        }
    }catch(exception $e){  echo 'Upload Failed :File did not upload :' . $e->getMessage(); }
    }else if(!isset($_FILES['avatar'])){
        echo "Upload Image data blank : ". UPLOADERR[$_FILES['avatar']['error']];
    }else{ echo "Upload Failed : ". UPLOADERR[$_FILES['avatar']['error']]; }
}
if($action == "Delete_image"){
    $imgid = $_POST['imgid'];
    $del_img = deleteQuery(TESTIMONIALS,'id="'.$imgid.'"');
    if($del_img){
        deleteimg('testimonials',$_REQUEST['img_name'] );
        echo "1";
    } else{echo "0";}
}
if($action == "active_deactive"){
    $requestid = $_REQUEST['requestedid'];
    $data = array('isActive' => $status);
    $upategetsubcat = updateQuery(TESTIMONIALS, $data, "id=" . $requestid);
    if ($upategetsubcat){ echo 1; } else{ echo 0; }
}
if($action == "getdetails"){
    $gettestimonials = selectQuery(TESTIMONIALS,"*","id='".$_REQUEST['img_id']."' ");
    ?>
    <div class="popupmsg mx-3 mt-3 mb-0"></div>
    <form>
        <div class="modal-body">
            <div class="form-group"><label class="cc-mandatary-field">Client Name</label><input type="text" name="client_name" id="client_name" class="brand_name form-control text-capitalize" maxlength="50" placeholder="Enter Client Name" value="<?php echo $gettestimonials[0]['client_name']; ?>"/></div>
            <div class="form-group"><label>Company</label><input type="text" name="prod_name" id="prod_name" class="brand_name form-control text-capitalize" maxlength="50" placeholder="Enter Product Name" value="<?php echo $gettestimonials[0]['product_name']; ?>"/></div>
            <div class="form-group mb-0"><label class="cc-mandatary-field">Testimonials</label><textarea class="form-control" id="testimonials" name="testimonials" placeholder="Enter Testimonials Description (Max.500 Characters)" cols="40" rows="5" maxlength="500"><?php echo $gettestimonials[0]['testimonials']; ?></textarea>
            <div class="mt-2">You have <span class="charlimitmessage">200</span> of 200 characters Remaining</div></div>
            <div><div class="inputurl"></div><input type="hidden" name="img_id" id="img_id" required value="<?php echo $gettestimonials[0]['id']; ?>"/></div>
        </div>
        <div class="modal-footer text-right py-2"><div class="chnfun col-md-12 px-0"><input type="button" name="Savedata" id="savedata" class="btn btn-primary" value="Save" onclick="updatedata()" /></div>
        </div>
    </form>
<?php }
if($action == "update_client_detail"){
    $data = array("client_name" => ucwords($_REQUEST['client_name']), "product_name" => ucwords(addslashes($_REQUEST['prod_name'])), "testimonials" => addslashes($_REQUEST['testimonials']));
  
    $update=updateQuery(TESTIMONIALS,$data,"id='".$_REQUEST['img_id']."' ");
    if($update){ echo "1"; } else{ echo "0"; }
} 

if($action == "priority"){
    $str = $_REQUEST['str'];
    $image_ids = explode(",", $str);
    if ($str != ""){
        for ($i = 0; $i < count($image_ids); $i++){
        $data = array('priority' => $i + 1);
            $update = updateQuery(TESTIMONIALS, $data, "id=" .$image_ids[$i]);
            if($update){ echo 1; } else{ echo 0; }
        }
    } 
}
?>
