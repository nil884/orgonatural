<?php 

const IMAGE_HANDLERS = [
    IMAGETYPE_JPEG => ['load' => 'imagecreatefromjpeg', 'save' => 'imagejpeg','quality' => 100],
    IMAGETYPE_PNG => ['load' => 'imagecreatefrompng','save' => 'imagepng','quality' => 0],
    IMAGETYPE_GIF => ['load' => 'imagecreatefromgif', 'save' => 'imagegif']
];
function createThumbnail($src,$dest,$targetWidth=null, $targetHeight = null)
{
    $type = exif_imagetype($src);
    if(!$type || !IMAGE_HANDLERS[$type]){  return null; }
     $image = call_user_func(IMAGE_HANDLERS[$type]['load'], $src);
    //no image found at supplied location -> exit
    if (!$image){  return null; }
    $width = imagesx($image);
    $height = imagesy($image);

    if ($targetHeight == null){
            //get width to height ratio
            $ratio = $width / $height;
            //if is portrait
            //use ratio to scale height to fit in square
            if($width > $height){ $targetHeight = floor($targetWidth / $ratio); }
            //if is landscape
            //use ratio to scale width to fit in square
            else{
                $targetHeight = $targetWidth;
                $targetWidth = floor($targetWidth * $ratio);
            }
        }
      if($targetWidth == null ) {

        //get width to height ratio
            $ratio = $width / $height;
            //if is portrait
            //use ratio to scale height to fit in square
            if($width > $height){ //echo "in this  loop"; 
            $targetWidth = floor($targetHeight  * $ratio); 
            }
            //if is landscape
            //use ratio to scale width to fit in square
            else{
               // echo "in else loop";
                $targetWidth =  floor($targetHeight * $ratio);
                
            }
      }  
        //echo "height".$targetHeight."<br> Width".$targetWidth;
    $imagick = new \Imagick(realpath($src));
    $imagick->setbackgroundcolor('rgb(64, 64, 64)');
    $imagick->thumbnailImage($targetWidth,$targetHeight, true, true);
    //header("Content-Type: image/jpg");
    $file =  $imagick->getImageBlob();
    //echo $file;echo $dest;
    file_put_contents($dest, $file);  
}


?>