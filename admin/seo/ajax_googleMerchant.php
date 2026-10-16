<?php 
include "../../includes/configuration.php";
$merchant=$_POST['merchant']; $sampleemail=""; $content=$_POST['content'];

$merchantarr=array("merchantId"=>$merchant,"accountSampleUser"=>$sampleemail);
$json=json_encode($merchantarr);
file_put_contents('../../shopping-samples/content/merchant-info.json', $json);
file_put_contents('../../shopping-samples/content/service-account.json', $content);
echo 1;
?>