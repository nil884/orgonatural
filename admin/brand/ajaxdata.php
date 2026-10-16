<?php include ("../../includes/configuration.php");
    include("../../cropimg/create-thumbnail.php");
    include("../../cropimg/commonfunctions.php");
    if($action=="upload_image"){
    $imgs_location = $_POST['imgs_location'];
    $url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    $path = $_SERVER['HTTP_HOST']."/".$imgs_location;
    $target_path = getRelativePath($url,$path)."/";
    $crop_enabled = $_POST['crop_enabled'];  $thumbnail_required=$_POST['thumbnail_required'];  $img_extension = $_POST['img_extension'];
    $default_image_width = $_POST['default_image_width']; $default_image_height = $_POST['default_image_height'];
    $thumb1width = $_POST['thumb1_width']; $thumb2width = $_POST['thumb2_width']; $thumb3width = $_POST['thumb3_width'];
    $thumb4width = $_POST['thumb4_width']; $thumb5width = $_POST['thumb5_width'];
    $thumb1path = $_POST['thumb1_path']; $thumb2path = $_POST['thumb2_path']; $thumb3path = $_POST['thumb3_path'];
    $thumb4path = $_POST['thumb4_path']; $thumb5path = $_POST['thumb5_path'];  $webp_quality = $_POST['webp_quality'];
    if(isset($_FILES['avatar']) and !$_FILES['avatar']['error']){
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $_FILES['avatar']['tmp_name']);
    if($img_extension=="jpg"){$ext="jpeg";}else if($img_extension=="png"){$ext="png";}else{
        if($mime=="image/png"){$ext="png";}elseif($mime=="image/jpg"){$ext="jpeg";}elseif($mime=="image/jpeg"){$ext="jpeg";}
    } try{
        $fn = "brand-".rand(100,999);
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
            $data0=array("img"=>$fname,"inserted_date" => date("Y-m-d H:is:") );
            insertQuery(BRAND,$data0);
            if($thumbnail_required){
               if($thumb1width){  $thumb1store= getthumbnailpath($url,$imgs_location,$thumb1path); createThumbnail($target_path.$fname, $thumb1store.$fname, $thumb1width);}
               if($thumb2width){  $thumb2store= getthumbnailpath($url,$imgs_location,$thumb2path);createThumbnail($target_path.$fname, $thumb2store.$fname, $thumb2width);}
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

if($action == "Delete_brand"){
    $imgid = $_POST['imgid'];
    $del_img = deleteQuery(BRAND,'br_id="'.$brandid.'"');
    if($del_img){
        deleteimg('brand',$_REQUEST['img_name'] );
        echo "1";
    } else{echo "0";}
}

if ($action == "active_deactive"){
    $id = $_REQUEST['pid'];
    $setval = $_REQUEST['status'];
    $data = array('isActive' => $setval);
    $que = updateQuery(BRAND, $data, "br_id=" . $requestedid);
    if($que){echo 1; } else{echo 0;}
}

if($action == "get_form_detail"){
$getbrand=selectQuery(BRAND,"brand_name,target_link,br_id,brand_type","br_id=".$_REQUEST['brandid']); ?>
    <form id="all_form_data">
        <div class="modal-body pb-0">
            <div class="msgs12"></div>
            <div class="form-group radiobtn">
                <p>Create Links For The Brand Based On</p>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" class="custom-control-input" value="company" checked="checked" id="brandbscom" name="link_type" onchange='clear_field()' <? if($getbrand[0]['brand_type'] == "company") { echo 'checked';} ?>>
                    <label class="custom-control-label" for="brandbscom">Company</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" class="custom-control-input" value="vendor" id="brandbsven" name="link_type" onchange='clear_field()' <? if($getbrand[0]['brand_type'] == "vendor" ){ echo 'checked';} ?>>
                    <label class="custom-control-label" for="brandbsven">Vendor</label>
                </div>
            </div>
            <div class="form-group">
                <input type="text" name="brand_name" id="brand_name" class="brand_name form-control text-capitalize" maxlength="20" placeholder="Enter Company Name Or Vendor Profile ID" value="<?php echo $getbrand[0]['brand_name']; ?>"/>
            </div>
            <div class="form-group">
                <div class="generatedlink"><?php if($getbrand[0]['target_link'] != ""){ ?><div class='divlink'>Preview Product Url : <a href='<?php echo $getbrand[0]['target_link']; ?>' target='_blank'><?php echo $getbrand[0]['target_link']; ?></a></div><?php } ?></div>                
                <input type="hidden" name="brandid" id="brandid" required value="<?php echo $getbrand[0]['br_id']; ?>"/>
            </div>
        </div>
        <div class="chnfun modal-footer py-2 text-right"><input type="button" name="Preview link" id="prelink" class="btn btn-primary ml-auto" value="Generate Link And Save" onclick="link()"/><?php if($getbrand[0]['target_link'] != "") { ?><button class="remove_url btn btn-danger cc-cursor-pointer" onclick="remove()">Remove URL</button><?php } ?></div>
    </form>
<?php }

/*
if($action == "updatebranddata"){
    if($brand_type == "company"){ $targetlink = SITEURL."/companyproduct/".createurltitle($_REQUEST['brand_name'])   ;}  else if($brand_type == "vendor") { $targetlink = SITEURL."/vendorproduct/".createurltitle($_REQUEST['brand_name']) ; }
    $data=array(
    "brand_type" => $brand_type,
    "brand_name"=> addslashes($_REQUEST['brand_name']),
    "target_link"=> $targetlink );
    $check_if_exist = selectQuery(BRAND,"count(br_id) as total_brand ","brand_name='".addslashes($brand_name)."' and  brand_type = '".$brand_type."' and br_id <> '".$_REQUEST['brandid']."' ");
    if($check_if_exist[0]['total_brand']  == 0 ) {   
        $getbrand=updateQuery(BRAND,$data,"br_id=".$_REQUEST['brandid']);
        if($getbrand){
            echo 1;
        } else{ echo 0; }
    } else{ echo 2; }  
}*/

if($action == "generate_link"){
    if($brand_type == "company"){ $targetlink = SITEURL."/companyproduct/".createurltitle($_REQUEST['brand_name']);}
    else if($brand_type == "vendor"){ $targetlink = SITEURL."/vendorproduct/".createurltitle($_REQUEST['brand_name']);}
    $data = array("brand_type" => $brand_type, "brand_name"=> ucfirst(addslashes($_REQUEST['brand_name'])), "target_link"=> $targetlink);
    $check_if_exist = selectQuery(BRAND,"count(br_id) as total_brand ","brand_name='".addslashes($brand_name)."' and  brand_type = '".$brand_type."' and br_id <> '".$_REQUEST['brandid']."' ");
    if($check_if_exist[0]['total_brand']  == 0 ){   
        $getbrand = updateQuery(BRAND,$data,"br_id=".$_REQUEST['brandid']);
        if($getbrand){ echo $targetlink; } else{ 0; }
    } else { echo "2"; }   
}

if($action == "Remove_link"){
    $data = array("target_link"=> "",);
    $getbrand = updateQuery(BRAND,$data,"br_id=".$_REQUEST['brandid']);
    if($getbrand){ echo 1; } else{ echo 0; }
} 


if($action == "priority"){
    $str = $_REQUEST['str'];
    $image_ids = explode(",", $str);
    if ($str != ""){
        for ($i = 0; $i < count($image_ids); $i++){
        $data = array('priority' => $i + 1);
            $update = updateQuery(BRAND, $data, "br_id=" .$image_ids[$i]);
            if($update){ echo 1; } else{ echo 0; }
        }
    } 
}


?> 