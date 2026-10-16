<?
include "includes/configuration.php";
include("cropimg/create-thumbnail.php");
include("cropimg/commonfunctions.php");
ini_set('max_execution_time', '0');
ini_set('display_errors', '1');
$type="brand";      // slider product category brand    offer
switch($type){
    case "slider":
        /* ***********************************************main slider *********************************************************************************/
        $configdetails=getimgconfig('main slider');
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
            $getsliderimg = selectQuery(MAIN_SLIDER,"img_id,img_name");
            for($i=0;$i<count($getsliderimg);$i++){
                $inPath=$target_path.$getsliderimg[$i]['img_name'];
                $mime = image_type_to_mime_type(exif_imagetype($inPath));
                 $fnain=explode(".",$getsliderimg[$i]['img_name']);
                $ext=$fnain[1];

                if($img_extension=="webp"&&$ext!="webp"){
                   $file = basename($inPath);         // $file is set to "index.php"
                  $fna=explode(".",$file);
                  $fn =$fna[0];
                   $fnameo =$fn.".".$ext;
                    $filepath=$target_path.$fnameo;

                  $fname = $fn.'.webp';
                  echo $fnameo." - ". $fname."<br>";
                    if(createwebp($target_path,$fnameo,$fname,$webp_quality)){ //if webp image created then delete old image
                        unlink($target_path.$fnameo);
                         if($thumbnail_required){
                            if($thumb1width){  $thumb1store= getthumbnailpath($url,$imgloc,$thumb1path);createThumbnail($target_path.$fname, $thumb1store.$fname, $thumb1width);   unlink($thumb1store.$fnameo);     }
                            if($thumb2width){  $thumb2store= getthumbnailpath($url,$imgloc,$thumb2path);createThumbnail($target_path.$fname, $thumb2store.$fname, $thumb2width); unlink($thumb2store.$fnameo); }
                            if($thumb3width){ $thumb3store= getthumbnailpath($url,$imgloc,$thumb3path); createThumbnail($target_path.$fname, $thumb3store.$fname, $thumb3width);  unlink($thumb3store.$fnameo); }
                            if($thumb4width){ $thumb4store= getthumbnailpath($url,$imgloc,$thumb4path);createThumbnail($target_path.$fname, $thumb4store.$fname, $thumb4width); unlink($thumb4store.$fnameo); }
                            if($thumb5width){ $thumb5store= getthumbnailpath($url,$imgloc,$thumb5path);createThumbnail($target_path.$fname, $thumb5store.$fname, $thumb5width); unlink($thumb5store.$fnameo);  }
                            }
                        echo $getsliderimg[$i]['img_name']." converted to ".$fname."<br>";
                         $data= array("img_name"=>$fname);
                         updateQuery(MAIN_SLIDER,$data,"img_id=".$getsliderimg[$i]['img_id']);
                    }else{ $fname=  $fnameo; echo $getsliderimg[$i]['img_name']." not converted<br>";}

                }
            }
    break;

    case "product":
          /************************************************************************ product images******************************************************* */
   $configdetails=getimgconfig('product');
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
    $getsliderimg = selectQuery(PRODIMG,"id,img_name");
    for($i=0;$i<count($getsliderimg);$i++){
        $inPath=$target_path.$getsliderimg[$i]['img_name'];
        $mime = image_type_to_mime_type(exif_imagetype($inPath));
         $fnain=explode(".",$getsliderimg[$i]['img_name']);
        $ext=$fnain[1];
       echo $i." - " .$getsliderimg[$i]['id']." - ".$ext."<br>";

        if($img_extension=="webp"&&$ext!="webp"){
          $file = basename($inPath);
          $fna=explode(".",$file);
          $fn =$fna[0];
           $fnameo =$fn.".".$ext;
            $filepath=$target_path.$fnameo;
           echo $filepath;
          $fname = $fn.'.webp';

            if(createwebp($target_path,$fnameo,$fname,$webp_quality)){
                unlink($target_path.$fnameo);
                 if($thumbnail_required){
                    if($thumb1width){  $thumb1store= getthumbnailpath($url,$imgloc,$thumb1path);createThumbnail($target_path.$fname, $thumb1store.$fname, $thumb1width);   unlink($thumb1store.$fnameo);     }
                    if($thumb2width){  $thumb2store= getthumbnailpath($url,$imgloc,$thumb2path);createThumbnail($target_path.$fname, $thumb2store.$fname, $thumb2width); unlink($thumb2store.$fnameo); }
                    if($thumb3width){ $thumb3store= getthumbnailpath($url,$imgloc,$thumb3path); createThumbnail($target_path.$fname, $thumb3store.$fname, $thumb3width);  unlink($thumb3store.$fnameo); }
                    if($thumb4width){ $thumb4store= getthumbnailpath($url,$imgloc,$thumb4path);createThumbnail($target_path.$fname, $thumb4store.$fname, $thumb4width); unlink($thumb4store.$fnameo); }
                    if($thumb5width){ $thumb5store= getthumbnailpath($url,$imgloc,$thumb5path);createThumbnail($target_path.$fname, $thumb5store.$fname, $thumb5width); unlink($thumb5store.$fnameo);  }
                    }
                echo $getsliderimg[$i]['img_name']." converted to ".$fname."<br>";
                 $data= array("img_name"=>$fname);
                 updateQuery(PRODIMG,$data,"id=".$getsliderimg[$i]['id']);
            }else{ $fname=  $fnameo; echo $getsliderimg[$i]['img_name']." not converted<br>";}

        }
    }
    break;

    case "category":
        /************************************************************************ product images******************************************************* */
   $configdetails=getimgconfig('category');
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
    $getsliderimg = selectQuery(PRODCAT,"id,img_name","img_name <>''");
    for($i=0;$i<count($getsliderimg);$i++){
        $inPath=$target_path.$getsliderimg[$i]['img_name'];
        $mime = image_type_to_mime_type(exif_imagetype($inPath));
         $fnain=explode(".",$getsliderimg[$i]['img_name']);
        $ext=$fnain[1];
       echo $i." - " .$getsliderimg[$i]['id']." - ".$ext."<br>";

        if($img_extension=="webp"&&$ext!="webp"){
          $file = basename($inPath);
          $fna=explode(".",$file);
          $fn =$fna[0];
           $fnameo =$fn.".".$ext;
            $filepath=$target_path.$fnameo;

          $fname = $fn.'.webp';

            if(createwebp($target_path,$fnameo,$fname,$webp_quality)){
                unlink($target_path.$fnameo);
                 if($thumbnail_required){
                    if($thumb1width){  $thumb1store= getthumbnailpath($url,$imgloc,$thumb1path);createThumbnail($target_path.$fname, $thumb1store.$fname, $thumb1width);   unlink($thumb1store.$fnameo);     }
                    if($thumb2width){  $thumb2store= getthumbnailpath($url,$imgloc,$thumb2path);createThumbnail($target_path.$fname, $thumb2store.$fname, $thumb2width); unlink($thumb2store.$fnameo); }
                    if($thumb3width){ $thumb3store= getthumbnailpath($url,$imgloc,$thumb3path); createThumbnail($target_path.$fname, $thumb3store.$fname, $thumb3width);  unlink($thumb3store.$fnameo); }
                    if($thumb4width){ $thumb4store= getthumbnailpath($url,$imgloc,$thumb4path);createThumbnail($target_path.$fname, $thumb4store.$fname, $thumb4width); unlink($thumb4store.$fnameo); }
                    if($thumb5width){ $thumb5store= getthumbnailpath($url,$imgloc,$thumb5path);createThumbnail($target_path.$fname, $thumb5store.$fname, $thumb5width); unlink($thumb5store.$fnameo);  }
                    }
                echo $getsliderimg[$i]['img_name']." converted to ".$fname."<br>";
                 $data= array("img_name"=>$fname);
                 updateQuery(PRODCAT,$data,"id=".$getsliderimg[$i]['id']);
            }else{ $fname=  $fnameo; echo $getsliderimg[$i]['img_name']." not converted<br>";}

        }
    }
    break;

    case "brand":
        /************************************************************************ product images******************************************************* */
   $configdetails=getimgconfig('brand');
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
    $getsliderimg = selectQuery(BRAND,"br_id,img","img <>''");
    for($i=0;$i<count($getsliderimg);$i++){
        $inPath=$target_path.$getsliderimg[$i]['img'];
        $mime = image_type_to_mime_type(exif_imagetype($inPath));
         $fnain=explode(".",$getsliderimg[$i]['img']);
        $ext=$fnain[1];
       echo $i." - " .$getsliderimg[$i]['br_id']." - ".$ext."<br>";

        if($img_extension=="webp"&&$ext!="webp"){
          $file = basename($inPath);
          $fna=explode(".",$file);
          $fn =$fna[0];
           $fnameo =$fn.".".$ext;
            $filepath=$target_path.$fnameo;

          $fname = $fn.'.webp';

            if(createwebp($target_path,$fnameo,$fname,$webp_quality)){
                unlink($target_path.$fnameo);
                 if($thumbnail_required){
                    if($thumb1width){  $thumb1store= getthumbnailpath($url,$imgloc,$thumb1path);createThumbnail($target_path.$fname, $thumb1store.$fname, $thumb1width);   unlink($thumb1store.$fnameo);     }
                    if($thumb2width){  $thumb2store= getthumbnailpath($url,$imgloc,$thumb2path);createThumbnail($target_path.$fname, $thumb2store.$fname, $thumb2width); unlink($thumb2store.$fnameo); }
                    if($thumb3width){ $thumb3store= getthumbnailpath($url,$imgloc,$thumb3path); createThumbnail($target_path.$fname, $thumb3store.$fname, $thumb3width);  unlink($thumb3store.$fnameo); }
                    if($thumb4width){ $thumb4store= getthumbnailpath($url,$imgloc,$thumb4path);createThumbnail($target_path.$fname, $thumb4store.$fname, $thumb4width); unlink($thumb4store.$fnameo); }
                    if($thumb5width){ $thumb5store= getthumbnailpath($url,$imgloc,$thumb5path);createThumbnail($target_path.$fname, $thumb5store.$fname, $thumb5width); unlink($thumb5store.$fnameo);  }
                    }
                echo $getsliderimg[$i]['img']." converted to ".$fname."<br>";
                 $data= array("img"=>$fname);
                 updateQuery(BRAND,$data,"br_id=".$getsliderimg[$i]['br_id']);
            }else{ $fname=  $fnameo; echo $getsliderimg[$i]['img']." not converted<br>";}

        }
    }
    break;

       case "offer":
        /************************************************************************ product images******************************************************* */
   $configdetails=getimgconfig('offer');
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
    $getsliderimg = selectQuery(OFFER,"offer_id,img","img <>''");
    for($i=0;$i<count($getsliderimg);$i++){
        $inPath=$target_path.$getsliderimg[$i]['img'];
        $mime = image_type_to_mime_type(exif_imagetype($inPath));
         $fnain=explode(".",$getsliderimg[$i]['img']);
        $ext=$fnain[1];
       echo $i." - " .$getsliderimg[$i]['offer_id']." - ".$ext."<br>";

        if($img_extension=="webp"&&$ext!="webp"){
          $file = basename($inPath);
          $fna=explode(".",$file);
          $fn =$fna[0];
           $fnameo =$fn.".".$ext;
            $filepath=$target_path.$fnameo;

          $fname = $fn.'.webp';

            if(createwebp($target_path,$fnameo,$fname,$webp_quality)){
                unlink($target_path.$fnameo);
                 if($thumbnail_required){
                    if($thumb1width){  $thumb1store= getthumbnailpath($url,$imgloc,$thumb1path);createThumbnail($target_path.$fname, $thumb1store.$fname, $thumb1width);   unlink($thumb1store.$fnameo);     }
                    if($thumb2width){  $thumb2store= getthumbnailpath($url,$imgloc,$thumb2path);createThumbnail($target_path.$fname, $thumb2store.$fname, $thumb2width); unlink($thumb2store.$fnameo); }
                    if($thumb3width){ $thumb3store= getthumbnailpath($url,$imgloc,$thumb3path); createThumbnail($target_path.$fname, $thumb3store.$fname, $thumb3width);  unlink($thumb3store.$fnameo); }
                    if($thumb4width){ $thumb4store= getthumbnailpath($url,$imgloc,$thumb4path);createThumbnail($target_path.$fname, $thumb4store.$fname, $thumb4width); unlink($thumb4store.$fnameo); }
                    if($thumb5width){ $thumb5store= getthumbnailpath($url,$imgloc,$thumb5path);createThumbnail($target_path.$fname, $thumb5store.$fname, $thumb5width); unlink($thumb5store.$fnameo);  }
                    }
                echo $getsliderimg[$i]['img']." converted to ".$fname."<br>";
                 $data= array("img"=>$fname);
                 updateQuery(OFFER,$data,"offer_id=".$getsliderimg[$i]['offer_id']);
            }else{ $fname=  $fnameo; echo $getsliderimg[$i]['img']." not converted<br>";}

        }
    }
    break;
}



?>