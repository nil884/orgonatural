<?php 
include "../includes/configuration.php";
include "../classes/product.php";
include "../googleads-shopping-samples/php/ProductstatusesSample.php";
$merchant=file_get_contents("../shopping-samples/content/merchant-info.json");

$prodlist=selectQuery(PRODINFO,"count(id) as act","isActive='1' and isApproved='1' AND ((parent_id=0 AND variation_available='0') OR parent_id<>0)");
$prodlist1=selectQuery(PRODINFO,"count(id) as act","(isActive='0' OR isApproved='0') AND ((parent_id=0 AND variation_available='0') OR parent_id<>0)");

$product=new ProductstatusesSample();
$product->listProductstatuses();

$statusarr=array("activeSku"=>$prodlist[0]['act'],"deactiveSku"=>$prodlist1[0]['act'],"approved"=>ProductstatusesSample::$valid,"disapproved"=>ProductstatusesSample::$invalid);
$json=json_encode($statusarr);
file_put_contents('../shopping-samples/status.json', $json);
?>