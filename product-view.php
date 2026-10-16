<? $productData = $productids[$i]; $variations = $productData['variations']; if($productData['image']){ $img = SITEURL."/".$thumb2_path."/".$productData['image']; }else{ $img = SITEURL."/img/projectimage/product-default.png"; } 
  $keys=array_keys($productData['variations']); 
  $key0=$keys[0]; $key1=$keys[1]; $key2=$keys[2]; 
  $var0=$productData['variations'][$key0];$var1=$productData['variations'][$key1];$var2=$productData['variations'][$key2];
  $variationsKg = $productData['variations'][$key0]; $CurrvariationsKg = $productData['currentVariations'][$key0][0];
  $currentjson=json_encode($productData['currentVariations']);
  $alljson=json_encode($variations,true);
  
 // print_r($productData['currentVariations']);
    $random=rand();
?>
<div class="pro-thumb-col card h-100 text-center" id="prodbox<?=$productData['id'].$random; ?>">
    <div class="prod-pic-fig position-relative d-flex flex-wrap align-content-center pt-2 px-2 pt-md-3 px-md-3"><a href="<?=SITEURL;?>/<?=getUrl("Product",$productData['id']); ?>" hreflang="en"><img src="<?=$img;?>" alt="product-thumb" class="img-fluid" width="220" height="300"></a><?php if($productData['stock'] == 0){ echo "<div class='out-stock-flag position-absolute text-danger text-center h6 w-100 py-3 align-self-center'>Out of stock</div>"; } ?>                 
    <button type="button" class="btn position-absolute pdod-wish border bg-white rounded-circle text-center p-0 wishlist_btn_<?=$productData['id'] ?> <?php if((isset($item_array_id)) && (is_array($item_array_id)) && (in_array($productData['id'],$item_array_id))){echo "cart-active cc-primary-color";}else{ echo ""; } ?>" onclick="add_to_wishlist(<?=$productData['id'] ?>,1)" <?php if( (isset($item_array_id)) && (is_array($item_array_id)) && (in_array($productData['id'],$item_array_id) )){echo "disabled";} ?> data-toggle="tooltip" data-placement="top" title="Wishlist"><i class="fa fa-heart" aria-hidden="true"></i></button></div>
    <div class="prod-des-figcap p-2 p-md-3">        
        <h3 class="prod-name h6 mb-1 cc-fw-5"><a href="<?=SITEURL;?>/<?=getUrl("Product",$productData['id']); ?>" hreflang="en"><?=(strlen($productData['name'])>40?substr($productData['name'],0,40)."..":$productData['name']); ?></a></h3>
        <div class="prod-total-price">
            <span class="mr-2 pro-actual-price"><i class="fa fa-inr"></i><?=$productData['price']; ?></span><? if($productData['off']!=0){ ?><span class="mr-1 dist-price text-muted"><del><i class="fa fa-inr"></i><?=$productData['mrp']; ?></del></span><span class="dist-per cc-primary-color small d-none">(<?=$productData['off']; ?>%OFF)</span>
            <? } ?>
        </div>
        <div class="d-flex mt-2">
            <? /* if($variationsKg){?><select class="form-control form-control-sm px-1 prod-weight-drop"><?foreach($variationsKg as $key=>$value){?><option onclick="chngprod('prodbox<?=$productData['id'].$random; ?>','<?=base64_encode($productData['id']); ?>','<?=base64_encode($productData['parent']); ?>','<?=$key0; ?>','<?=$value; ?>')" <?=($CurrvariationsKg==$value?"selected":""); ?>><?=$value; ?></option><?} ?></select><? }else{?><select class="form-control form-control-sm px-1 prod-weight-drop " disabled> <option value="">1</option> </select><?}  */?>
            <?   if(count($variations)){?><select class="form-control form-control-sm px-1 prod-weight-drop" onchange="changeprod(this)" id="prodsel<?=$productData['id'].$random; ?>">
                <?php for($v=0;$v<count($var0);$v++){
                  $sb=array(); $sb[$key0]=array($var0[$v]); if(count($var1)){ $sb[$key1]=array($var1[$v]); } if(count($var2)){ $sb[$key1]=array($var2[$v]); } 
                  $sbjson=json_encode($sb); 
                 
                  if($currentjson==$sbjson){$select=true;}else{$select=false;}
                ?> <option data-box="prodbox<?=$productData['id'].$random; ?>" data-id="<?=base64_encode($productData['id']); ?>" data-parent="<?=base64_encode($productData['parent']); ?>" data-var="<?=base64_encode($sbjson); ?>"  <?=($select?"selected":""); ?>><?=$var0[$v].(count($var1)?" ".$var1[$v]:"").(count($var2)?" ".$var2[$v]:""); ?></option> <?} ?></select><? }else{?><select class="form-control form-control-sm px-1 prod-weight-drop " disabled> <option value="">1</option> </select><?}   ?>
            
            <?php if($productData['stock'] != 0 ){ ?> <button type="button" class="btn ml-2 btn-sm cart_btn_<?=$productData['id'] ?> <?php if(is_array($cart_item_array_id) && (in_array($productData['id'],$cart_item_array_id) )){echo " cart-active btn-primary";}else{ echo "btn-primary"; } ?>" onclick="add_to_cart(<?=$productData['id'] ?>,1)" <?php if( isset($cart_item_array_id) && is_array($cart_item_array_id) && (in_array($productData['id'],$cart_item_array_id) )){echo "disabled";} ?> data-toggle="tooltip" data-placement="top" title="Cart">Cart</button><?php } ?>
        </div>
       
    </div>
</div>