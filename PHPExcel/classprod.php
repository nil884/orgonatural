<?
class classprod{
   function exvariation1($values){ return explode("^",$values);}
   function exvariation2($values){ return explode("|",$values);}

    function save_image($inPath,$outPath,$configdetails,$prodname){
      $filename=basename($inPath);
      $conf0=json_decode($configdetails,true);
      $conf= $conf0[0];

       $thumbnail_required=$conf['thumbnail_required'];
      $default_image_width=$conf['default_image_width']; $default_image_height=$conf['default_image_height'];
       $thumb1width=$conf['thumb1_width']; $thumb2width=$conf['thumb2_width']; $thumb3width=$conf['thumb3_width'];
       $thumb4width=$conf['thumb4_width'];   $thumb5width=$conf['thumb5_width'];
        $thumb1path=$conf['thumb1_path']; $thumb2path=$conf['thumb2_path']; $thumb3path=$conf['thumb3_path'];
       $thumb4path=$conf['thumb4_path'];   $thumb5path=$conf['thumb5_path'];
       $imgloc=$conf['imgs_location']; $img_extension =$conf['img_extension'];   $webp_quality =$conf['webp_img_quality_percent'];

       $url=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $path=$_SERVER['HTTP_HOST']."/".$imgloc;

        $target_path=getRelativePath($url,$path)."/";

        $mime = image_type_to_mime_type(exif_imagetype($inPath));
        if($img_extension=="jpg"){$ext="jpeg";}else if($img_extension=="png"){$ext="png";}else{
          if($mime=="image/png"){$ext="png";}elseif($mime=="image/jpg"){$ext="jpeg";}elseif($mime=="image/jpeg"){$ext="jpeg";}
        }

      $fn =$prodname."-".rand(100,999);
       $fnameo =$fn.".".$ext;
     $filepath=$target_path.$fnameo;

      $result=  file_put_contents($filepath, file_get_contents($inPath));

      if($result){
          if($img_extension=="webp"){

            $fname = $fn.'.webp';
            if(createwebp($target_path,$fnameo,$fname,$webp_quality)){ //if webp image created then delete old image
                unlink($target_path.$fnameo);
            }else{ $fname=  $fnameo;}
        }else{ $fname=  $fnameo; }
      if($thumbnail_required){
            if($thumb1width){  $thumb1store= getthumbnailpath($url,$imgloc,$thumb1path);createThumbnail($target_path.$fname, $thumb1store.$fname, $thumb1width);}
            if($thumb2width){  $thumb2store= getthumbnailpath($url,$imgloc,$thumb2path);createThumbnail($target_path.$fname, $thumb2store.$fname, $thumb2width);}
            if($thumb3width){ $thumb3store= getthumbnailpath($url,$imgloc,$thumb3path); createThumbnail($target_path.$fname, $thumb3store.$fname, $thumb3width); }
            if($thumb4width){ $thumb4store= getthumbnailpath($url,$imgloc,$thumb4path);createThumbnail($target_path.$fname, $thumb4store.$fname, $thumb4width);}
            if($thumb5width){ $thumb5store= getthumbnailpath($url,$imgloc,$thumb5path);createThumbnail($target_path.$fname, $thumb5store.$fname, $thumb5width); }
            }
      return $fname;
      }
      else { return "";  }
    }
}
?>