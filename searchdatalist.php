<? include("includes/configuration.php");
    include("classes/product.php");
    
   
    $prod = new Product();
    $search = addslashes($_REQUEST['search']);
    $str = "";//and prod_company like '%".$search."%' 
    if($str==""){$str=" (prod_name like '%".$search."%' or prod_company like '%".$search."%'  or seo_keywords  like '%".$search."%' ) ";}
    $where = $str; 
    $productids = $prod->getProductsByGroup($where,0,10);
// $prod_count = $prod->getProductsGroupCount($where);
    if(count($productids) > 0){
    for($i=0;$i<count($productids);$i++){
        $var_str = "";
       
    $productData=$productids[$i];
    $getProductDetails = $prod->getProductFullDetails($productData['id']);
    $variations = $productData['variations'];  
    foreach($variations as $key=>$value){ 
      $var_str = $var_str." ".implode(", ",array_unique($value));
      } 
    $variation = !empty($search)?highlightWords($var_str, $search):$var_str;
    $keyword = !empty($search)?highlightWords($getProductDetails['seo_keywords'], $search):$getProductDetails['seo_keywords'];
    $sub_keword = strstr($keyword, $search);
   ?>
    <div class="ser-list" onclick="getsrchselect('<?=addslashes($productData['name']);?>')"><?php echo highlightWords($productData['name'],$search) ?><?php echo $variation  ?>
   <?php  if($sub_keword != "") {  echo "found in keyword-".highlightWords($sub_keword,$search ); } ?>    
    </div>
    <?php };
}


else {
    echo "<div class='p-3'>Product not found </div>";
    
}
?>