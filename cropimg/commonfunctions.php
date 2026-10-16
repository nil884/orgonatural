<? function getthumbnailpath($currUrl,$storepath,$thumbexpin){
    $path = $_SERVER['HTTP_HOST']."/".($thumbexpin!=""?$thumbexpin:$imgs_location);
    return getRelativePath($currUrl,$path)."/";
}
function deleteimg($type,$imgname){
    $getconfingdetails = json_decode(getimgconfig($type));
    $imgs_location = $getconfingdetails[0]->imgs_location; 
    $thumb1path = $getconfingdetails[0]->thumb1_path; 
    $thumb2path = $getconfingdetails[0]->thumb2_path; 
    $thumb3path = $getconfingdetails[0]->thumb3_path;
    $thumb4path = $getconfingdetails[0]->thumb4_path;   
    $thumb5path = $getconfingdetails[0]->thumb5_path;
    $url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    $path = $_SERVER['HTTP_HOST']."/".$imgs_location;
    $target_path = getRelativePath($url,$_SERVER['HTTP_HOST']."/".$imgs_location)."/";
    $target_path1 = getRelativePath($url,$_SERVER['HTTP_HOST']."/".$thumb1path)."/";
    $target_path2 = getRelativePath($url,$_SERVER['HTTP_HOST']."/".$thumb2path)."/";
    $target_path3 = getRelativePath($url,$_SERVER['HTTP_HOST']."/".$thumb3path)."/";
    $target_path4 = getRelativePath($url,$_SERVER['HTTP_HOST']."/".$thumb4path)."/";
    $target_path5 = getRelativePath($url,$_SERVER['HTTP_HOST']."/".$thumb5path)."/";
    unlink($target_path.$imgname);
    if(file_exists($target_path1.$imgname)){ unlink($target_path1.$imgname); } 
    if(file_exists($target_path2.$imgname)){ unlink($target_path2.$imgname); } 
    if(file_exists($target_path3.$imgname)){ unlink($target_path3.$imgname); } 
    if(file_exists($target_path4.$imgname)){ unlink($target_path4.$imgname); } 
    if(file_exists($target_path5.$imgname)){ unlink($target_path5.$imgname); } 
}

function createwebp($path,$image,$newname,$quality){
    $dir = $path;
    $name = $image;
    $newName = $newname;
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
     $mime = finfo_file($finfo, $dir.$name);
    if($mime=="image/png"){$img = imagecreatefrompng($dir . $name);}elseif($mime=="image/jpg"||$mime=="image/jpeg"){$img = imagecreatefromjpeg($dir . $name);}elseif($mime=="image/webp"){$img = imagecreatefromwebp($dir . $name);}
    imagepalettetotruecolor($img);
    imagealphablending($img, true);
    imagesavealpha($img, true);
    imagewebp($img, $dir . $newName, $quality);
    imagedestroy($img);
    if(file_exists($dir . $newName))
        return 1;
    else
        return 0;    
}
?>