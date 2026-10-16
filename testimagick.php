<?
function thumbnailImage($imagePath)
{
    $imagick = new \Imagick(realpath($imagePath));
    $imagick->setbackgroundcolor('rgb(64, 64, 64)');
    $imagick->thumbnailImage(400, 200, true, true);
    header("Content-Type: image/jpg");
    echo $imagick->getImageBlob();
}

//echo thumbnailImage("img/categoryimage/yellow-550.jpeg");


function brightnessContrastImage($imagePath, $brightness, $contrast, $channel)
{
    $imagick = new \Imagick(realpath($imagePath));
    $imagick->brightnessContrastImage($brightness, $contrast, $channel);
    header("Content-Type: image/jpg");
    echo $imagick->getImageBlob();
}

 echo brightnessContrastImage("img/categoryimage/yellow-550.jpeg",'100','100','All')
?>