<?php include ("../../includes/configuration.php");
include("../../cropimg/create-thumbnail.php");
include("../../cropimg/commonfunctions.php");
if($action=="upload image"){
    $imgs_location = $_POST['imgs_location'];
    $url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    $path = $_SERVER['HTTP_HOST']."/".$imgs_location;
    $target_path = getRelativePath($url,$path)."/";
    $crop_enabled = $_POST['crop_enabled'];  $thumbnail_required=$_POST['thumbnail_required']; $img_extension = $_POST['img_extension']; $webp_quality = $_POST['webp_quality'];
    $default_image_width = $_POST['default_image_width']; $default_image_height = $_POST['default_image_height'];
    $thumb1width = $_POST['thumb1_width']; $thumb2width = $_POST['thumb2_width']; $thumb3width=$_POST['thumb3_width'];
    $thumb4width = $_POST['thumb4_width']; $thumb5width = $_POST['thumb5_width'];
    $thumb1path = $_POST['thumb1_path']; $thumb2path = $_POST['thumb2_path']; $thumb3path = $_POST['thumb3_path'];
    $thumb4path = $_POST['thumb4_path']; $thumb5path = $_POST['thumb5_path'];
    if(isset($_FILES['avatar']) and !$_FILES['avatar']['error']){
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $_FILES['avatar']['tmp_name']);
    if($img_extension=="jpg"){$ext="jpeg";}
    else if($img_extension=="png"){$ext="png";}
    else{
        if($mime=="image/png"){$ext="png";}elseif($mime=="image/jpg"){$ext="jpeg";}elseif($mime=="image/jpeg"){$ext="jpeg";}
    }
    try{
        $fn="mainslider-".rand(100,999);
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
            $data0=array("img_name"=>$fname,"inserted_date" => date('Y-m-d H:i:s') );
            insertQuery(MAIN_SLIDER_MOBILE,$data0);
            if($thumbnail_required){
               if($thumb1width){ $thumb1store = getthumbnailpath($url,$imgs_location,$thumb1path); createThumbnail($target_path.$fname, $thumb1store.$fname, $thumb1width);}
               if($thumb2width){ $thumb2store = getthumbnailpath($url,$imgs_location,$thumb2path);createThumbnail($target_path.$fname, $thumb2store.$fname, $thumb2width);}
               if($thumb3width){ $thumb3store = getthumbnailpath($url,$imgs_location,$thumb3path); createThumbnail($target_path.$fname, $thumb3store.$fname, $thumb3width); }
               if($thumb4width){ $thumb4store = getthumbnailpath($url,$imgs_location,$thumb4path);createThumbnail($target_path.$fname, $thumb4store.$fname, $thumb4width);}
               if($thumb5width){ $thumb5store = getthumbnailpath($url,$imgs_location,$thumb5path);createThumbnail($target_path.$fname, $thumb5store.$fname, $thumb5width); }
            }
           echo "Upload Success";
        }
    }catch(exception $e){ echo 'Upload Failed :File did not upload :' . $e->getMessage(); }
    }else if(!isset($_FILES['avatar'])){
        echo "Upload Image data blank : ". UPLOADERR[$_FILES['avatar']['error']];
    }else{ echo "Upload Failed : ". UPLOADERR[$_FILES['avatar']['error']]; }
}

if($action == "Delete_image"){
    $imgid = $_POST['imgid'];
    $del_img =  deleteQuery(MAIN_SLIDER_MOBILE,'img_id="'.$imgid.'"');
    if($del_img){
        deleteimg('main slider mobile',$_REQUEST['img_name'] );
        echo "1";
    } else{echo "0";}
}
if($action == "priority_image"){
    $str = $_REQUEST['str'];
    $image_ids = explode(",", $str);
    if ($str != ""){
        for ($i = 0; $i < count($image_ids); $i++){
        $data = array('priority' => $i + 1);
            $update = updateQuery(MAIN_SLIDER_MOBILE, $data, "img_id=" .$image_ids[$i]);
            if($update){ echo 1; } else{ echo 0; }
        }
    } 
}
if($action == "update_data"){
    $data = array("btn_link"=>$_REQUEST['btn_link']);
    $getuser = updateQuery(MAIN_SLIDER_MOBILE,$data,"img_id=".$_REQUEST['img_id']);
    if($getuser){ echo 1; } else{ echo 0; }
}