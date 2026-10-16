<? require 'includes/configuration.php';
ini_set("display_errors",1);
/*if(! rmdir("img/favicon")) {
  print_r(error_get_last());
}*/
$folder_path = "img/favicon";
// List of name of files inside
// specified folder
$files = glob($folder_path.'/*');
// Deleting all the files in the list
foreach($files as $file) {
    if(is_file($file))
        // Delete the given file
        unlink($file);
}
 ;
/*require 'favicon-generator/vendor/autoload.php';
ini_set("display_errors",1);
$fav = new FaviconGenerator('img/projectimage/favicon.png');
$fav->setCompression(FaviconGenerator::COMPRESSION_VERYHIGH);
$fav->setConfig(array(
    'apple-background'    => FaviconGenerator::COLOR_BLUE,
    'apple-margin'        => 15,
    'android-background'  => FaviconGenerator::COLOR_GREEN,
    'android-margin'      => 15,
    'android-name'        => 'easierdesk',
    'android-url'         => 'http://easierdesk.com',
    'android-orientation' => FaviconGenerator::ANDROID_PORTRAIT,
    'ms-background'       => FaviconGenerator::COLOR_TEAL,
));
echo $fav->createAllAndGetHtml();*/
?>