<?php
include("../../includes/configuration.php");
include("../../cropimg/create-thumbnail.php");
include("../../cropimg/commonfunctions.php");
    
if($action=="upload image"){
    $imgs_location=$_POST['imgs_location'];
    $url=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    $path=$_SERVER['HTTP_HOST']."/".$imgs_location;

    $target_path=getRelativePath($url,$path)."/";
    $get_name =  selectQuery(PRODINFO,"prod_name ","id = '".$prod_id."'");

    $crop_enabled=$_POST['crop_enabled'];  $thumbnail_required=$_POST['thumbnail_required'];  $img_extension=$_POST['img_extension'];
    $default_image_width=$_POST['default_image_width']; $default_image_height=$_POST['default_image_height'];
       $thumb1width=$_POST['thumb1_width']; $thumb2width=$_POST['thumb2_width']; $thumb3width=$_POST['thumb3_width'];
       $thumb4width=$_POST['thumb4_width'];   $thumb5width=$_POST['thumb5_width'];
        $thumb1path=$_POST['thumb1_path']; $thumb2path=$_POST['thumb2_path']; $thumb3path=$_POST['thumb3_path'];
       $thumb4path=$_POST['thumb4_path'];   $thumb5path=$_POST['thumb5_path'];
    if(isset($_FILES['avatar']) and !$_FILES['avatar']['error']){
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES['avatar']['tmp_name']);
        if($img_extension=="jpg"){$ext="jpeg";}else if($img_extension=="png"){$ext="png";}else{
           if($mime=="image/png"){$ext="png";}elseif($mime=="image/jpg"){$ext="jpeg";}elseif($mime=="image/jpeg"){$ext="jpeg";}
        }

    try{
       $fname = replace_nonletter($get_name[0]['prod_name'])."-".rand(100,999).".".$ext;
        if(!move_uploaded_file($_FILES['avatar']['tmp_name'], $target_path.$fname)) {
            throw new Exception($_FILES["avatar"]["error"]);
        }else{

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
            $data0=array("img_name"=>$fname,"prod_id" => $prod_id );
            insertQuery(PRODIMG,$data0);

            if($thumbnail_required){
               if($thumb1width){  $thumb1store= getthumbnailpath($url,$imgs_location,$thumb1path); createThumbnail($target_path.$fname, $thumb1store.$fname, $thumb1width);}
               if($thumb2width){  $thumb2store= getthumbnailpath($url,$imgs_location,$thumb2path);createThumbnail($target_path.$fname, $thumb2store.$fname, $thumb2width);}
               if($thumb3width){ $thumb3store= getthumbnailpath($url,$imgs_location,$thumb3path); createThumbnail($target_path.$fname, $thumb3store.$fname, $thumb3width); }
               if($thumb4width){ $thumb4store= getthumbnailpath($url,$imgs_location,$thumb4path);createThumbnail($target_path.$fname, $thumb4store.$fname, $thumb4width);}
               if($thumb5width){ $thumb5store= getthumbnailpath($url,$imgs_location,$thumb5path);createThumbnail($target_path.$fname, $thumb5store.$fname, $thumb5width); }
            }
           echo "Upload Success";
        }
    }catch(exception $e){  echo 'Upload Failed :File did not upload: ' . $e->getMessage(); }
    }else if(!isset($_FILES['avatar'])){
         echo "Upload Image data blank : ". UPLOADERR[$_FILES['avatar']['error']];

    }else{ echo "Upload Failed : ". UPLOADERR[$_FILES['avatar']['error']];   }
}



if($action == "Delete_image"){

  $del_img =  deleteQuery(PRODIMG,'id="'.$imgid.'"');
    if($del_img){
        $checkcount = selectQuery(PRODIMG,"count(id) as  allid ,prod_id ","img_name  = '".$_REQUEST['img_name']."'");


        if($checkcount[0]['allid'] == 0) {  deleteimg('product',$_REQUEST['img_name'] ); }
        if($checkcount[0]['allid'] == 1) {
            $data= array( 'apply_to_all' => 0 );
            $update_table = updateQuery(PRODIMG, $data, "prod_id = '". $checkcount[0]['prod_id']."' ");
         }
         echo "1";
        }


    else {
        echo "0";
    }


}

if($action == "applyto_all"){
  $getall_inv  =  selectQuery(PRODINFO,"*","parent_id  = '".$parent_id."'   and   id <> '".$mainprod."'");
   for($i=0;$i<count($getall_inv);$i++){
       $data = array(
           'prod_id' => $getall_inv[$i]['id'],
           'img_name' => $img_name,
           'apply_to_all' => 1,
       );
       insertQuery(PRODIMG,$data);
       
    
   }
   $array2 = array(
    'apply_to_all' => 1,
   );
   $update_table = updateQuery(PRODIMG, $array2, "prod_id = '". $mainprod."' and img_name= '".$img_name."' ");
   if( $update_table){
       echo "1";
   }
   else {
       echo "0";
   }
}
?>