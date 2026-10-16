<?php 
include "../includes/configuration.php";
include "../classes/product.php";
include "../googleads-shopping-samples/php/ProductsSample.php";
$merchant=file_get_contents("../shopping-samples/content/merchant-info.json");

$merchArr=json_decode($merchant,true);
$cpro=new Product();
$product=new ProductsSample();
if($merchArr['merchantId']!=""){

$prodlist=selectQuery(PRODINFO,"*","isActive='1' and isApproved='1' AND ((parent_id=0 AND variation_available='0') OR parent_id<>0) ");
echo "<br>Adding ".count($prodlist)." products<br>";
$final=0;
for($i=0;$i<count($prodlist);$i++){
  $row=$prodlist[$i];
  $getid=$row['id'];
  echo "<br>Adding product ".$getid." <br>";
  $prodimg=$cpro->getProductImageForDisplay($getid);
  $price= $cpro->getProductPrice($getid);
  $parent_id=$row['parent_id']; $variation_available=$row['variation_available'];
 /*  if(($parent_id==0&&$variation_available==0)||$parent_id!=0){ */
  
  if($row['parent_id']!=0){ $parentsta=selectQuery(PRODINFO,"isActive","id=".$row['parent_id']); $isActive= $parentsta[0]['isActive']; }else{ $isActive=1; }
  $catdata=selectQuery(PRODCAT,"template,isActive","id=".$row['sub_cat']);
  $mcatdata=selectQuery(PRODCAT,"isActive","id=".$row['master_cat']);
  $pcatdata=selectQuery(PRODCAT,"isActive","id=".$row['parent_cat']);
  if($isActive==1&&$pcatdata[0]['isActive']=='1'&&$mcatdata[0]['isActive']=='1'&&$catdata[0]['isActive']=='1'){
 
  $tempdata=selectQuery($catdata[0]['template'],"highlight","prod_id=".$getid);
  if(count($prodimg)){$img=$prodimg[0]['img_name'];}else{$img="";}
  $variant1=($row['variant_name1']!=""?getOriginalName($row['variant_name1']):"");
  $variant2=($row['variant_name2']!=""?getOriginalName($row['variant_name2']):"");
  $variant3=($row['variant_name3']!=""?getOriginalName($row['variant_name3']):"");
  $prod=array(
        "id"=>$getid,
        "title"=>$row['prod_name'],
        "description"=>(strlen($tempdata[0]['highlight'])>5000?substr(strip_tags($tempdata[0]['highlight']),0,4999):$tempdata[0]['highlight']),
        "brand"=>$row['prod_company'],
        "sku"=>$row['sku'],
        "mpn"=>$row['hsn_code'],
        "produrl"=>SITEURL."/".getUrl("Product",$getid),
        "img"=>SITEURL."/img/productimg/".$img,
        "price"=>sprintf("%0.2f",$price['priceWithoutGst'],2),
        "availability"=>($price['stock']>0?"in stock":"out of stock")
    );
    if($parent_id!=0){ $prod['item_group_id']="prod".$parent_id; }
    if($variant1!=""){
      if($variant1=="Colour"||$variant1=="Color"||$variant1=="colour"||$variant1=="color"){ $prod['color']=$row['variant_value1']; }
      if($variant1=="size"||$variant1=="Size"){ $prod['size']=$row['variant_value1']; }
      if($variant1=="material"||$variant1=="Material"){ $prod['material']=$row['variant_value1']; }
      if($variant1=="pattern"||$variant1=="Pattern"){ $prod['pattern']=$row['variant_value1']; }
    }
    if($variant2!=""){
      if($variant2=="Colour"||$variant2=="Color"||$variant2=="colour"||$variant2=="color"){ $prod['color']=$row['variant_value2']; }
      if($variant2=="size"||$variant2=="Size"){ $prod['size']=$row['variant_value2']; }
      if($variant2=="material"||$variant2=="Material"){ $prod['material']=$row['variant_value2']; }
      if($variant2=="pattern"||$variant2=="Pattern"){ $prod['pattern']=$row['variant_value2']; }
    }
    if($variant3!=""){
      if($variant3=="Colour"||$variant3=="Color"||$variant3=="colour"||$variant3=="color"){ $prod['color']=$row['variant_value3']; }
      if($variant3=="size"||$variant3=="Size"){ $prod['size']=$row['variant_value3']; }
      if($variant3=="material"||$variant3=="Material"){ $prod['material']=$row['variant_value3']; }
      if($variant3=="pattern"||$variant3=="Pattern"){ $prod['pattern']=$row['variant_value3']; }
    }
    $product->createProduct($prod); 
    $final=$final+1;
/* }  */
  }
}
echo "<br>Total ".$final." products added";


}
?>