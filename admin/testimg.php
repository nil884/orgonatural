<?
function thumbnailImage($imagePath)
{
    $imagick = new \Imagick(realpath($imagePath));
    $imagick->setbackgroundcolor('rgb(64, 64, 64)');
    $imagick->thumbnailImage(400, 200, true, true);
    header("Content-Type: image/jpg");
    echo $imagick->getImageBlob();
}
 
?>
>