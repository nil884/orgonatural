<?php include("includes/configuration.php");
    include("classes/product.php");
    $urltitle = $_GET['urltitle'];
    $prod = new Product();
    $prodid = $prod->getProductIdFromURL($urltitle);
    $status=selectQuery(PRODINFO,"isActive","id=".$prodid);
    $pstatus=$status[0]['isActive'];
    if(isset($_SESSION['recently_view'])){ $recently_view_array = $_SESSION['recently_view'];} else{
        $recently_view_array = array();}
    if(!in_array($prodid,$recently_view_array )){
        array_push($recently_view_array,$prodid);
        $_SESSION['recently_view'] = $recently_view_array;
    }
    $getProductDetails = $prod->getProductFullDetails($prodid);
    $images = $getProductDetails['image'];
    $variations = $getProductDetails['variations'];
    $stock = $getProductDetails['stock'];  $price = $getProductDetails['price'];
    $currentVariartions = $getProductDetails['currentVariartions'];
    $templateData = $getProductDetails['templateData'];
    $highlight = $templateData['highlight'];
    $vendor = $getProductDetails['vendor'];   
    $parent_cat = $getProductDetails['parent_cat'];
    $master_cat = $getProductDetails['master_cat'];
    $sub_cat = $getProductDetails['sub_cat'];
    $company = $getProductDetails['company'];
    $getparent_id = $prod->getParentProd($getProductDetails['name'],$getProductDetails['vendorId']);
    $actual_link = ($_SERVER['HTTPS'] == 'on'?"https://":"http://").$_SERVER[HTTP_HOST]."".$_SERVER[REQUEST_URI];
    //SELECT id FROM product_info WHERE prod_name='Women Solid Cotton Blend Flared Kurti' and vendor='4' and parent_id='0'
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?=($getProductDetails['seo_title']!=""?$getProductDetails['seo_title']." : ".SITE_TITLE:$getProductDetails['name']." : ".SITE_TITLE); ?></title>
    <meta name="description" content="<?=($getProductDetails['seo_description']!=""?$getProductDetails['seo_description']:METADESCRIPTION); ?>">
    <meta name="keywords" content="<?=($getProductDetails['seo_keywords']!=""?$getProductDetails['seo_keywords']:METAKEYWORDS); ?>">
    <?php include "commoncss.php"; ?>
    <link rel="canonical" href="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>">
    <!--Open Graph / Facebook-->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo $actual_link; ?>">
    <meta property="og:title" content="<?=($getProductDetails['seo_title']!=""?$getProductDetails['seo_title']." : ".SITE_TITLE:$getProductDetails['name']." : ".SITE_TITLE); ?>">
    <meta property="og:description" content="<?=($getProductDetails['seo_description']!=""?$getProductDetails['seo_description']:METADESCRIPTION); ?>">
    <meta property="og:image" content="<?=SITEURL; ?>/img/productimg/<?=$images[0]['img_name'];?>">
    <!--Twitter-->
    <meta property="twitter:card" content="<?=SITEURL; ?>/img/productimg/<?=$images[0]['img_name'];?>">
    <meta property="twitter:url" content="<?php echo $actual_link; ?>">
    <meta property="twitter:title" content="<?=($getProductDetails['seo_title']!=""?$getProductDetails['seo_title']." : ".SITE_TITLE:$getProductDetails['name']." : ".SITE_TITLE); ?>">
    <meta property="twitter:description" content="<?=($getProductDetails['seo_description']!=""?$getProductDetails['seo_description']:METADESCRIPTION); ?>">
    <meta property="twitter:image" content="<?=SITEURL; ?>/img/productimg/<?=$images[0]['img_name'];?>">
    <link rel="stylesheet" href="<?=SITEURL;?>/css/imgzoom/glasscase.min.css" >
    <link rel="stylesheet" href="<?=SITEURL;?>/css/thumb-slider/owl.carousel.min.css" />
    <link rel="stylesheet" href="<?=SITEURL;?>/css/datepicker/bootstrap-datetimepicker.min.css">
</head>
<body class="bg-light">
<div class="main-wrap">     
    <?php include "menu.php" ?>
    <div class="container">
        <div class="row">
        <div class="col-md-12"><ol class="breadcrumb pl-0 mb-2"><li class="breadcrumb-item"><a href="<?=SITEURL; ?>" hreflang="en">Home</a></li><li class="breadcrumb-item"><a href="<?=SITEURL.'/'.getUrl("Parent",$getProductDetails['parent_cat_id'])?>" class="point1" hreflang="en"><?=$parent_cat ?></a></li><li class="breadcrumb-item"><a href="<?=SITEURL.'/'.getUrl("Master",$getProductDetails['master_cat_id'])?>" class="point1" hreflang="en"><?=$master_cat ?></a></li><li class="breadcrumb-item"><a href="<?=SITEURL.'/'.getUrl("Sub",$getProductDetails['sub_cat_id']) ?>" class="point1" hreflang="en"><?=$sub_cat ?></a></li><li class="breadcrumb-item active"><?=$getProductDetails['name']; ?></li></ol></div>  
        </div>
    </div>
    <div class="container">
         <div class="card p-4 mb-3 border-0 cc-shadow-2">
            <div class="row">
                <div class="col-md-5 col-lg-6 zoom-item-thumb mb-3 mb-md-0">
                    <div>
                        <input type="hidden" name="ctl00$cphBody$-VIEWxSTATE" id="zoom-effect-inp" value="NDczOzU7MzA=" />
                        <ul id='girlstop1' class='gc-start'>
                            <?php for($i=0;$i<count($images);$i++){ ?>
                            <li><img src='<?=SITEURL; ?>/img/productimg/<?=$images[$i]['img_name'];?>' class="img-responsive" data-gc-caption="Caption text" alt="image Thumbnail" width="500" height="622">
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                <div class="col-md-7 col-lg-6">
                    <div class="singal-prod-info">
                    <h1 class="singal-prod-title"><?=$getProductDetails['name']; ?></h1> 
                    <div class="rev-rate-below-prod-name cc-font-size-13">
                        <ul class="list-unstyled">
                            <!--<li class="review-count ratingcnt text-primary cc-cursor-pointer">5 Ratings</li>-->
                            <li class="review-count text-primary cc-cursor-pointer"><?=$prod->getReviewCount($getparent_id); ?> Reviews</li>
                            <li class="border-right-0 text-primary">Product By - <?=$vendor;?></li>
                        </ul>
                    </div>
                    <?
                    $wp_message=$getconfigdetails[0]['wp_message'];
                    $wpno=($getconfigdetails[0]['wp_number']!=""?$getconfigdetails[0]['wp_phone_code']:"").$getconfigdetails[0]['wp_number'];
                    $wpbutton=str_replace("{{PRODUCT}}",$getProductDetails['name'],$getconfigdetails[0]['wp_button_name']);
                    $msg=str_replace("{{PRODUCT}}",$getProductDetails['name'],$wp_message);
                    $msg=str_replace("{{URL}}","https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],$msg);
                    if($wpno!=""){?>
                       <a href="https://api.whatsapp.com/send?phone=<? echo $wpno; ?>&text=<? echo urlencode($msg); ?>" target="_blank" class="whatsapp-chat-btn rounded d-inline-block"><i class="fa fa-whatsapp" aria-hidden="true"></i> <? echo $wpbutton; ?></a>
                    <?}  ?>
                    <form method="POST" action="<?=SITEURL;?>/checkout">
                        <div class="prod-total-price d-flex align-items-center">
                            <span class="mr-2 mb-0 pr-del-fin-price h3 cc-fw-4"><i class="fa fa-inr"></i><span id="change_price"> <?=$price; ?></span></span>
                            <? if($getProductDetails['off']!=0){ ?>
                            <span class="mr-2 dist-price text-muted"><del><i class="fa fa-inr"></i><span id="off_change_price"><?=$getProductDetails['mrp']; ?></span></del></span>
                            <span class="dist-per badge-success rounded px-1">(<?=$getProductDetails['off']; ?>%OFF)</span>
                            <? } ?>
                            <input type="hidden" name="purchase_price" id="purchase_price" value="<?=$price; ?>">
                            <input type="hidden" name="off_price" id="off_price" value="<?=$getProductDetails['mrp']; ?>">
                        </div>
                        <div class="mb-3"><span class="mr-1"><?="(Including ".$getProductDetails['tax']."% GST)"; ?></span></div>
                        <input type="hidden" name="action" value="Buy">
                        <input type="hidden" name="productid" value="<?=$prodid; ?>">
                        <input type="hidden" name="price"  id="price" value="<?=$getProductDetails['withoutGst']; ?>">
                        <? if(count($variations)){
                        $varcount = 0;
                        foreach($variations as $key=>$value){
                        $valarr = array_values(array_unique($value)); ?>
                        <div class="product-option-list row mb-1">
                            <div class="col-3 col-sm-3 col-md-2"><label class="mt-1"><?=$key; ?></label></div>
                            <div class="col-9 col-sm-9 col-md-10 pl-0">
                                <ul class="list-unstyled mb-0">
                                    <?php

                                    for($z=0;$z<count($valarr);$z++){ ?>
                                    <li><input type="radio" class="form-control varient-input var-attr-<?=$varcount;?>" value="<?=$valarr[$z]; ?>" id="first_attribute_<?=$varcount;?><?=$z; ?>"  <?=(trim($currentVariartions[$varcount])== trim($valarr[$z])?"checked":"");?> onchange="variationchange('<?=count($variations);?>')" name="pro-var-attr-<?=$varcount;?>" data-id = "<?=$valarr[$z]; ?>">
                                    <label for="first_attribute_<?=$varcount;?><?=$z; ?>" class="varient-option rounded">
                                    <?=$valarr[$z]; ?></label></li>
                                    <? } ?>
                                </ul>
                            </div>
                        </div>
                        <? $varcount++; } } ?>
                        <div class="qtybox row">
                            <div class="col-3 col-md-2"><label class="pt-1">Quantity</label></div>
                            <div class="setQty col-9 col-md-9 mb-3 pl-0"><input type="number" name="quantity" class ="quantity_no" id="selected_quant_product-<?=$prodid; ?>" min="1" max="<?=($stock<5?$stock:5); ?>" value="<?=($stock>=1?1:0); ?>" autocomplete="off" readonly></div>                            
                        </div>
                        <?=($pstatus==0?'<div class="variationerror alert alert-danger py-2 mb-2 d-inline-block">This product is no longer available</div>':($stock==0?'<div class="variationerror alert alert-danger py-2 mb-2 d-inline-block">Product is out of stock</div>':'')); ?>
                        <div class="buytocart row mb-3 <?=($stock==0||$pstatus==0?'d-none':''); ?> ">
                            <div class="col-md-12"><div id="maincontainer"></div></div>
                            <?php if($loguser ==""){ ?>
                            <div class="buy col-6 col-md-6 col-lg-4 pr-0"><a id="buynow1" class="btn btn-primary btn-block btn-lg" href="<?=SITEURL ?>/login" hreflang="en">Buy Now</a></div>
                            <?php } else{ ?>
                            <div class="buy col-6 col-md-6 col-lg-4 pr-0"><button id="buynow1" type="submit" class="btn btn-primary btn-block btn-lg"><?=($stock>=1?"Buy Now":"Out Of Stock"); ?></button></div>
                            <?php } ?>
                            <div class="col-6 col-md-6 col-lg-4"><button type="button" class="btn btn-primary btn-block cart-btn btn-lg cart_btn_<?=$prodid; ?> <?php if(isset($cart_item_array_id)  && (is_array($cart_item_array_id)) && (in_array($prodid,$cart_item_array_id))){echo " cart-active";} ?>" onclick="add_to_cart('<?=$prodid;?>','')" <?=(isset($cart_item_array_id) && is_array($cart_item_array_id) &&(in_array($prodid,$cart_item_array_id ))?" disabled":""); ?>><?= ( isset($cart_item_array_id) && is_array($cart_item_array_id) && in_array($prodid,$cart_item_array_id )?" Added To Cart":"Add To Cart");?></button></div>
                        </div>
                    </form>
                    <div class="sing-item-compare mb-2 mb-sm-2 <?=($pstatus==0?'d-none':''); ?>">
                        <button class="head-sec-back btn thumb-hov-btn p-0 rounded-circle <?=( isset($item_array_id) && (is_array($item_array_id)) && (in_array($prodid,$item_array_id) )?"cc-second-back cart-active":"btn-default"); ?> wishlist_btn_<?=$prodid; ?>" onclick="add_to_wishlist('<?=$prodid;?>','')" <?=(isset($item_array_id) && (is_array($item_array_id)) && (in_array($prodid,$item_array_id))?"disabled":""); ?> data-toggle="tooltip" data-placement="top" title="Wishlist"><i class="fa fa-heart" aria-hidden="true"></i></button><span class="d-inline-block d-md-none ml-1 mr-3">Wishlist</span>
                        <span class="btn head-sec-back rounded-circle thumb-hov-btn text-center p-0 cc-cursor-pointer <?php if(isset($_SESSION['compare']) && (is_array($_SESSION['compare'])) && (in_array($prodid,$_SESSION['compare']))){ echo "cc-second-back"; } else{ echo "btn-default"; } ?>" data-toggle="tooltip" data-placement="top" title="Compare">
                        <label class="mb-0 cc-cursor-pointer"><input type="checkbox" class="compare_<?php echo $prodid ?>" onchange="add_to_compare(<?=$prodid ?>)" value="<?php if(isset($_SESSION['compare']) && (is_array($_SESSION['compare'])) && (in_array($prodid,$_SESSION['compare']) )){ echo "1"; } else { echo "0"; } ?>"> <i class="fa fa-random" aria-hidden="true"></i></label>
                        </span> <span class="d-inline-block d-md-none ml-1">Compare</span>
                    </div> 
                    <div class="row chec-del-avai">
                        <div class="col-sm-12"><label class="mt-1 cc-font-size-13">Delivery Option</label></div>
                        <div class="col-md-12 col-lg-7">
                            <div class="input-group mb-1">
                                <input type="text" class="form-control border-right-0 pincode" placeholder="Enter Pincode" maxlength="6"  id="pincode"  onkeyup="numbercheck('pincode')">
                                <div class="input-group-append border-primary"><span class="input-group-text rounded p-0 border-0"><button class="btn btn-primary" onclick="checkdelivery('<?=$prodid; ?>','<?=$price; ?>')">Check</button></span></div> 
                            </div>
                        </div>
                    </div>
                    <p class="text-muted small mb-2">Please enter PIN code to check delivery availability</p>
                    <div class="deliverydetails"></div>
                    <div class="mb-2 <?=($pstatus==0?'d-none':''); ?>"><button type="button" class="popup-text colorw1 btn btn-primary" data-toggle="modal" data-target="#bulk-order">Bulk / Wholesale Enquiry</button></div>
                    <div class="pt-4 row mx-0 text-dark"><div class="short-fea fea-guarantee-1 mr-2 mr-lg-3 d-flex mb-2 mb-lg-0"><div><img src="<?=SITEURL; ?>/img/projectimage/easy-exchange.webp" width="37" class="img-fluid mr-2" alt="easy-exchange"></div><div><span class="cc-fw-6 d-block">EASY</span> EXCHANGE</div></div><div class="short-fea fea-guarantee-3 mr-2 mr-lg-3 d-flex mb-2 mb-lg-0"><div><img src="<?=SITEURL; ?>/img/projectimage/fast-refund.webp" width="37" class="img-fluid mr-2" alt="fast-refund"></div><div><span class="cc-fw-6 d-block">FAST</span> REFUND</div></div><div class="short-fea fea-guarantee-2 mr-2 mr-sm-2 mr-md-0 mr-lg-3 d-flex mb-2 mb-lg-0"><div><img src="<?=SITEURL; ?>/img/projectimage/secured-payment.webp" width="37" class="img-fluid mr-2" alt="secured-payment"></div><div><span class="cc-fw-6 d-block">SECURED</span> PAYMENT</div></div><div class="short-fea fea-guarantee-4 d-flex mb-lg-0"><div><img src="<?=SITEURL; ?>/img/projectimage/fraud-protection.webp" width="37" class="img-fluid mr-2" alt="fraud-protection"></div><div><span class="cc-fw-6 d-block">FRAUD</span> PROTECTION</div></div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="card border-0 cc-shadow-2 mb-3">
            <div class="product-specs-tab">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="mb-0 nav-item <?=($highlight==''?'d-none':''); ?>"><a class="border-0 nav-link <?=($highlight==''?'':'active'); ?>" data-toggle="tab" href="#description">Description</a></li>
                    <li class="mb-0 nav-item"><a class=" border-0 nav-link <?=($highlight==''?'active':''); ?>" data-toggle="tab" href="#specifications">Specifications</a></li>
                    <li class="mb-0 nav-item"><a class="revire-tab-link border-0 nav-link" data-toggle="tab" href="#reviews">Reviews</a></li>
                </ul>
            </div>
            <div class="prod-heightlight-point">
                <div class="tab-content position-relative">
                    <div id="description" class="position-relative pro-detail-info tab-pane <?=($highlight==''?'fade':'active'); ?> p-3">
                        <h2 class="mb-3 h5">Description</h2>
                        <div><?=$highlight; ?></div>
                    </div>
                    <div id="specifications" class="position-relative pro-detail-info pro-sepc-table tab-pane <?=($highlight==''?'active':'fade'); ?> p-3">
                        <h2 class="mb-3 h5">Specifications</h2>
                        <div class="row">
                            <? unset($templateData['highlight']);
                            ksort($templateData);
                            foreach($templateData as $key=>$value){?>
                            <div class="col-md-6">
                                <div><h6><?=$key;?></h6></div>
                                <table class="table mb-2">
                                    <? foreach($value as $key1=> $value1){
                                    if($value1!=""){ ?> <tr><td class="py-2 pl-0 item-spe-head"><?=getOriginalName($key1);?> </td><td class="py-2"><?=$value1;?></td></tr> <? } } ?>
                                </table>
                            </div>
                            <? } ?>
                        </div>
                    </div>
                    <div id="reviews" class="position-relative pro-detail-info tab-pane fade p-3 cust-pro-review">
                        <div class="row">
                            <div class="col-md-10"><h2 class="mb-1 h5">Customer Review</h2></div>
                            <div class="col-md-2 text-right">
                                <? if($loguser){?>
                                <button type="button" class="btn btn-primary pl-3 pr-3 <?=($prod->checkUserReviewForProd($getparent_id,$loguser)==0?'':'');?>" data-toggle="modal" data-target="#addreview">Review</button>
                                <? } else{ ?> <a class="btn btn-primary pl-3 pr-3" href="<?=SITEURL ?>/login" hreflang="en">Review</a><? } ?>
                            </div>
                        </div>
                        <? $reviews=$prod->getReview($getparent_id,0,REVIEWCNTPAGE);
                        for($i=0;$i<count($reviews);$i++){
                        $rate = $reviews[$i]['rate']; ?>
                        <div class="media border-bottom pt-3 pb-3">
                            <span class="cust-rev-thumb rounded-circle cc-primary-back text-white mr-3 text-center"><?=substr($reviews[$i]['u_fname'],0,1); ?></span>
                            <div class="media-body">
                                <div class="row m-0">
                                    <div class="mb-2 text-muted cc-font-size-13 mr-2">
                                        <span class="mr-2"><i class="fa fa-user"></i> By <?=$reviews[$i]['u_fname']; ?></span>
                                        <span><i class="fa fa-calendar"></i> <?=date("d M Y",strtotime($reviews[$i]['review_date']));?></span>
                                    </div>
                                    <div class="rating-group review-star-count">
                                        <label aria-label="0 stars" class="rating-label <?=($rate>=0.0?'rating-icon-star':''); ?>" for="rating2-0"></label>
                                        <label aria-label="0.5 stars" class="rating-label rating-label-half" for="rating2-05"><i class="rating-icon <?=($rate>=0.5?'rating-icon-star':''); ?> fa fa-star-half"></i></label>
                                        <label aria-label="1 star" class="rating-label" for="rating2-10"><i class="rating-icon <?=($rate>=1.0?'rating-icon-star':''); ?> fa fa-star"></i></label>
                                        <label aria-label="1.5 stars" class="rating-label rating-label-half" for="rating2-15"><i class="rating-icon <?=($rate>=1.5?'rating-icon-star':''); ?> fa fa-star-half"></i></label>
                                        <label aria-label="2 stars" class="rating-label" for="rating2-20"><i class="rating-icon <?=($rate>=2.0?'rating-icon-star':''); ?> fa fa-star"></i></label>
                                        <label aria-label="2.5 stars" class="rating-label rating-label-half" for="rating2-25"><i class="rating-icon <?=($rate>=2.5?'rating-icon-star':''); ?> fa fa-star-half"></i></label>
                                        <label aria-label="3 stars" class="rating-label" for="rating2-30"><i class="rating-icon <?=($rate>=3.0?'rating-icon-star':''); ?> fa fa-star"></i></label>
                                        <label aria-label="3.5 stars" class="rating-label rating-label-half" for="rating2-35"><i class="rating-icon <?=($rate>=3.5?'rating-icon-star':''); ?> fa fa-star-half"></i></label>
                                        <label aria-label="4 stars" class="rating-label" for="rating2-40"><i class="rating-icon <?=($rate>=4.0?'rating-icon-star':''); ?> fa fa-star"></i></label>
                                        <label aria-label="4.5 stars" class="rating-label rating-label-half" for="rating2-45"><i class="rating-icon <?=($rate>=4.5?'rating-icon-star':''); ?> fa fa-star-half"></i></label>
                                        <label aria-label="5 stars" class="rating-label" for="rating2-50"><i class="rating-icon <?=($rate==5.0?'rating-icon-star':''); ?> fa fa-star"></i></label>
                                    </div>                                
                                </div>
                                <h6><?=$reviews[$i]['review_title']; ?></h6>                            
                                <div class="text-dark"><?=$reviews[$i]['review']; ?></div>
                            </div>
                        </div>
                        <? } ?>
                        <div class="text-muted py-3">
                            <? echo $review_count= $prod->getReviewCount($getparent_id); ?> Reviews 
                            <?php if($review_count > REVIEWCNTPAGE ){ echo "<a href='".SITEURL."/review/".base64_encode($prodid)."'  hreflang='en'>View All </a>"; } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
    $recently_view_prod = $_SESSION['recently_view']; 
    if (($key = array_search($prodid, $recently_view_prod)) !== false){ unset($recently_view_prod[$key]); $recently_view_prod = array_values($recently_view_prod); }
    if(count($recently_view_prod)){ ?>
    <div class="pb-4 recet-view-product">
        <div class="row">
            <div class="col-md-12">
                <div class="card p-3 border-0 cc-shadow-2">
                    <h5 class="mb-4">Recently Viewed Product</h5>
                    <div class="recent-view-col">
                     <div class="owl-carousel owl-theme owl-cust-also-bought">
                        <?php $imgtype = "product";
                        include("getimgpath.php");
                        for($i=0;$i<count($recently_view_prod);$i++){  
                        if($i == 10){ break; }
                        $getid=$recently_view_prod[$i];
                        $getshortdetails = $prod->getProductFullDetails($getid);
                        $variations = $getshortdetails['variations']; 
                        $currentvar = $getshortdetails['currentVariartions'];
                        $variationarr = array();  $varcnt=0;
                        foreach($variations as $key=>$val){ $variationarr[$key]= $currentvar[$varcnt];  $varcnt++;  }
                        //$prodname=($getshortdetails[0]['parent_id']==0?$getshortdetails[0]['prod_name']:$prod->getParentName($wishdetail[$i]['prod_id']));
                        $prodname = $getshortdetails['name'];
                        $images=$getshortdetails['image'];
                        if(count($images)){  $img= SITEURL."/".$thumb2_path."/".$images[0]['img_name'];}
                        else{ $img= SITEURL."/img/projectimage/product-default.png";  } ?>
                        <div class="pro-thumb-col border rounded text-center">
                            <div class="prod-pic-fig position-relative d-flex flex-wrap align-content-center">
                                <a href="<?=SITEURL;?>/<?=getUrl("Product",$getid); ?>" target="_blank" hreflang="en"><img src="<?=$img;?>" alt="product-thumb"></a>
                                <? if($getshortdetails['stock'] == 0){ echo "<div class='out-stock-flag position-absolute text-danger text-center h6 w-100 py-3 align-self-center'>Out of stock</div>"; } ?>
                                <button type="button" class="position-absolute p-0 btn btn-sm pdod-wish border rounded-circle text-center bg-white wishlist_btn_<?=$getid ?> <?php if(isset($item_array_id) && (is_array($item_array_id)) && (in_array($getid,$item_array_id) )){echo "cart-active";}else{ echo "text-primary"; } ?>" onclick="add_to_wishlist(<?=$getid ?>,1)" <?php if(isset($item_array_id) && (is_array($item_array_id)) && (in_array($getid,$item_array_id) )){echo "disabled";} ?> data-toggle="tooltip" data-placement="top" title="Wishlist"><i class="fa fa-heart" aria-hidden="true"></i></button>
                            </div>
                            <div class="prod-des-figcap p-3">        
                                <h5 class="prod-name cc-fw-5"><a hreflang="en"  href="<?=SITEURL;?>/<?=getUrl("Product",$getid); ?>"><?=(strlen($prodname)>30?substr($prodname,0,30)."..":$prodname); ?></a></h5>
                                <div class="prod-total-price">
                                    <span class="mr-1 pro-actual-price"><i class="fa fa-inr"></i><?=$getshortdetails['price']; ?></span>
                                    <? if($getshortdetails['off']!=0){ ?>
                                    <span class="mr-1 dist-price text-muted"><del><i class="fa fa-inr"></i><?=$getshortdetails['mrp']; ?></del></span>
                                    <span class="mr-1 dist-per text-danger small">(<?=$getshortdetails['off']; ?>%OFF)</span>
                                    <? } ?>
                                </div>
                                <div class="d-flex mt-2">
                                    <select class="form-control form-control-sm">
                                        <option>1Kg</option>
                                        <option>2Kg</option>
                                        <option>3Kg</option>
                                    </select>
                                    <?php if($getshortdetails['stock'] != 0 ){ ?> <button type="button" class="btn btn-sm ml-2 cart_btn_<?=$getid ?> <?php if( isset($cart_item_array_id) && is_array($cart_item_array_id) && (in_array($getid,$cart_item_array_id) )){echo " cart-active";}else{ echo "btn-primary"; } ?>" onclick="add_to_cart(<?= $getid ?>,1)" <?php if( isset($cart_item_array_id) && is_array($cart_item_array_id) && (in_array($getid,$cart_item_array_id) )){echo "disabled";} ?> data-toggle="tooltip" data-placement="top" title="Cart"> Cart</button><?php } ?>
                                </div>
                                <!--<div class="prod-varient pt-3">
                                    <div class="prod-over-action mb-2">
                                        <span class="pro-compare p-0 d-none d-md-inline-block btn btn-sm btn-primary thumb-hov-btn text-center rounded-circle cc-cursor-pointer <?php if( isset($_SESSION['compare']) && (is_array($_SESSION['compare'])) && (in_array($getid,$_SESSION['compare']))){ echo "disabled cart-active"; }else{ echo "btn-primary"; } ?>" data-toggle="tooltip" data-placement="top" title="Compare">
                                            <label class="mb-0 cc-cursor-pointer"><input type="checkbox" id="compare_<?php echo $getid ?>" onchange="add_to_compare(<?=$getid ?>)" <?php if( isset($_SESSION['compare'])  && (is_array($_SESSION['compare'])) && (in_array($getid,$_SESSION['compare']))){ echo "checked   disabled"; } ?>> <i class="fa fa-random" aria-hidden="true"></i></label>
                                        </span>
                                    </div>
                                    <? foreach($variations as $key=>$value){ ?>
                                    <div class="d-none d-md-block"><?=$key;?> : <?=implode(",",array_unique($value));?></div>
                                    <? } ?>
                                </div>-->
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
</div>
<div class="modal" id="addreview">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header pl-4 pr-4">
                <h4 class="modal-title">Customer Reviews</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body pl-4 pt-3 pr-4 pb-4">
                <p class="lead mb-1">Write your review..We value your opinion</p>
                <form action="#" id="formReview">
                    <div class="form-group row mb-1">
                        <input type="hidden" class="productid" value="<?=$prodid;?>"/>
                        <input type="hidden" class="userid" value="<?=$loguser;?>"/>
                        <label class="col-sm-12 col-form-label">Review Title</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control reviewTitle" placeholder="Review Title" required>
                            <div class="invalid-tooltip">Please Enter Review Title</div>
                        </div>
                    </div>
                    <div class="form-group row mb-1">
                        <label class="col-sm-12 col-form-label">Review Comment</label>
                        <div class="col-sm-12">
                            <textarea class="form-control reviewComment" placeholder="Review Comment" rows="5" required></textarea>
                            <div class="invalid-tooltip">Please Enter Your Comment</div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-12 col-form-label">Rating</label>
                        <div class="col-sm-12">
                            <div id="half-stars-example">
                                <div class="rating-group">
                                    <input class="rating-input rating-input-none" checked name="rating2" id="rating2-0" value="0" type="radio">
                                    <label aria-label="0 stars" class="rating-label" for="rating2-0"></label>
                                    <label aria-label="0.5 stars" class="rating-label rating-label-half" for="rating2-05"><i class="rating-icon rating-icon-star fa fa-star-half"></i></label>
                                    <input class="rating-input" name="rating2" id="rating2-05" value="0.5" type="radio">
                                    <label aria-label="1 star" class="rating-label" for="rating2-10"><i class="rating-icon rating-icon-star fa fa-star"></i></label>
                                    <input class="rating-input" name="rating2" id="rating2-10" value="1" type="radio">
                                    <label aria-label="1.5 stars" class="rating-label rating-label-half" for="rating2-15"><i class="rating-icon rating-icon-star fa fa-star-half"></i></label>
                                    <input class="rating-input" name="rating2" id="rating2-15" value="1.5" type="radio">
                                    <label aria-label="2 stars" class="rating-label" for="rating2-20"><i class="rating-icon rating-icon-star fa fa-star"></i></label>
                                    <input class="rating-input" name="rating2" id="rating2-20" value="2" type="radio">
                                    <label aria-label="2.5 stars" class="rating-label rating-label-half" for="rating2-25"><i class="rating-icon rating-icon-star fa fa-star-half"></i></label>
                                    <input class="rating-input" name="rating2" id="rating2-25" value="2.5" type="radio" >
                                    <label aria-label="3 stars" class="rating-label" for="rating2-30"><i class="rating-icon rating-icon-star fa fa-star"></i></label>
                                    <input class="rating-input" name="rating2" id="rating2-30" value="3" type="radio">
                                    <label aria-label="3.5 stars" class="rating-label rating-label-half" for="rating2-35"><i class="rating-icon rating-icon-star fa fa-star-half"></i></label>
                                    <input class="rating-input" name="rating2" id="rating2-35" value="3.5" type="radio">
                                    <label aria-label="4 stars" class="rating-label" for="rating2-40"><i class="rating-icon rating-icon-star fa fa-star"></i></label>
                                    <input class="rating-input" name="rating2" id="rating2-40" value="4" type="radio">
                                    <label aria-label="4.5 stars" class="rating-label rating-label-half" for="rating2-45">
                                        <i class="rating-icon rating-icon-star fa fa-star-half"></i></label>
                                    <input class="rating-input" name="rating2" id="rating2-45" value="4.5" type="radio">
                                    <label aria-label="5 stars" class="rating-label" for="rating2-50"><i class="rating-icon rating-icon-star fa fa-star"></i></label>
                                    <input class="rating-input" name="rating2" id="rating2-50" value="5" type="radio">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="rewiw_msgs1"></div>

                    <div>
                        <button type="button" class="btn btn-primary reviewbtn">Submit</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form> 
            </div>
        </div>
    </div>
</div>
<div id="bulk-order" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div id="hide12"><h5 id="productname" class="m-0 modal-title">Bulk / Wholesale Enquiry Form</h5></div>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <h5 class="mb-3"><?=$getProductDetails['name']; ?></h5>
                <div class="alert alert-info p-2">This is just a bulk / wholesale enquiry form and it will not generate any order.</div>
                <div class="msgs1"></div>
                <form name="bulk_order_form" class="dialog-form"  id="bulk_order_form">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group mb-2">
                                <label class="cc-mandatary-field">First Name</label>
                                <input type="text" name="bulk_name" id="bulk_name1" class="form-control" placeholder="Your First Name" maxlength="30" required />
                           <div class="invalid-tooltip">Please Enter Your First Name</div>     
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group mb-2">
                                <label class="cc-mandatary-field">Last Name</label>
                                <input type="text" name="bulk_name2" id="bulk_name2" placeholder=" Your Last Name" class="form-control loginp" maxlength="30"  required >
                                <div class="invalid-tooltip">Please Enter Your Last Name</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group mb-2">
                                <label class="cc-mandatary-field">Email </label>
                                <input type="text" name="bulk_email" id="bulk_email" placeholder="Email ID" class="form-control loginp" maxlength="50" onblur="mailchk('bulk_email');" required >
                                <div class="invalid-tooltip">Please Enter Your Email</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group mb-2">
                                <label class="cc-mandatary-field">Mobile Number</label>
                                <input type="text" name="bulk_contactNop" id="bulk_contactNop" class="form-control"  maxlength="10" placeholder="Your Mobile Number" autocomplete="off" onkeyup="mobnumbercheck('bulk_contactNop');"  required />
                                <div class="invalid-tooltip">Please Enter Mobile Number</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group mb-2">
                                <label class="cc-mandatary-field">No of Quantity </label>
                                <input type="text" value="5" placeholder="No of Quantity Require" class="form-control" id="bulk_qty" autocomplete="off" onkeypress='return restrictAlphabets(event)' maxlength="5" required>
                                <div class="invalid-tooltip">Please Enter Quntity</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group mb-2">
                                <label>Expected Date </label>
                                <input type="text" class="datetimepicker1 form-control" id="bulk_datetimepicker1" placeholder="Enter Expected Date" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Delivery Address</label>
                                <input type="text" name="bulk_Adress" id="bulk_Adress" class="form-control" placeholder=" Your Delivery Address" maxlength="200" />
                            </div>
                        </div>
                    </div>
                    <div class="row"><div class="col-sm-4"><button type="button" name="create" id="bulk_order_submit" class="btn btn-primary">Submit</button></div></div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
<?php include "footer.php"; ?>
<script src="<?=SITEURL ?>/js/imgzoom/modernizr.custom.js"></script>
<script src="<?=SITEURL ?>/js/imgzoom/jquery.glasscase.min.js"></script>
<script src="<?=SITEURL ?>/js/canvasjs.min.js"></script>
<script src="<?=SITEURL;?>/js/thumb-slider/owl.carousel.min.js"></script>
<script src="<?=SITEURL;?>/js/product.js"></script>
<script src="<?=SITEURL;?>/js/datepicker/moment.js"></script>
<script src="<?=SITEURL;?>/js/datepicker/bootstrap-datetimepicker.min.js"></script>
<script>
$("#bulk_datetimepicker1").datetimepicker({ format: 'DD-MM-YYYY', minDate:moment(),})
$("#bulk_order_submit").click(function(event){
    //Fetch form to apply custom Bootstrap validation
    var form = $("#bulk_order_form")
    if (form[0].checkValidity() === false) {
        event.preventDefault()
        event.stopPropagation()
    }else{ bulk_order();}
    form.addClass('was-validated');
});     
function bulk_order(){
    var name1 = $("#bulk_name1").val();
    var name2 = $("#bulk_name2").val();
    var contactNop = $("#bulk_contactNop").val();
    var email = $("#bulk_email").val();
    var Adress = $("#bulk_Adress").val();
    var qty = $("#bulk_qty").val();
    var date = $("#bulk_datetimepicker1").val();
    var product_id = '<?php echo $prodid ?>';
    var url = "<?php echo SITEURL; ?>";
    if(name1 == ""){ $('.msgs1').fadeIn().html("Please Enter First Name").addClass("alert alert-danger").delay(5000).fadeOut(); }
    else if(name2 == ""){ $('.msgs1').fadeIn().html("Please Enter Last Name").addClass("alert alert-danger").delay(5000).fadeOut(); }
    else if(email == ""){ $('.msgs1').fadeIn().html("Please Enter Email").addClass("alert alert-danger").delay(5000).fadeOut(); }
    else if(contactNop == ""){ $('.msgs1').fadeIn().html("Please Enter Contact Details ").addClass("alert alert-danger").delay(5000).fadeOut(); }
    else if(qty == ""){ $('.msgs1').fadeIn().html("Please Enter Quantity").addClass("alert alert-danger").delay(5000).fadeOut(); }
    else if(qty < 5){ $('.msgs1').fadeIn().html("Minimun Quantity Should Be Greater Than 5").addClass("alert alert-danger").delay(5000).fadeOut(); }
    else{
        $("#bulk_order_submit").html("Loading..");
        var info1 = {name1:name1,name2:name2,contactNop:contactNop,Adress:Adress,qty:qty,date:date,product_id:product_id,email:email,action:"bulk_order"};
        $.ajax({
            type: "POST",
            url: "<?php echo SITEURL ?>/ajax/product_ajax.php",
            data: info1,
            success: function(response){
                $("#bulk_order_submit").html("Submit");
                if(response == 1){
                    $('.msgs1').fadeIn().html("Thank You For Your Enquiry. We Will Contact You Soon").removeClass("alert alert-danger").addClass("alert alert-success").delay(5000).fadeOut();
                    setTimeout(function(){ $("#bulk-order").modal('hide') }, 5000);
                    $("#bulk_order_submit").attr('disabled',false);
                    $("#bulk_order_form").removeClass('was-validated');
                    $("#bulk_order_form").trigger('reset');
                }
                else{
                    $('.msgs1').fadeIn().html("Please Try After Some Time").addClass("alert alert-danger").delay(5000).fadeOut();
                }
            }
        });
    }   
}
var siteurl = "<?=SITEURL;?>";
(function($){
    $.fn.spinner = function(){
        this.each(function(){
            var el = $(this);
            el.wrap('<span class="spinner"></span>');
            el.before('<span class="sub rounded-left">-</span>');
            el.after('<span class="add rounded-right">+</span>');
            el.parent().on('click', '.sub', function(){
                if(el.val() > parseInt(el.attr('min')))
                el.val( function(i, oldval) { return --oldval; });
                purchase_price = $("#purchase_price").val();
                off_change_price = $("#off_price").val();
                quant = $(".quantity_no").val();
                purchase_price_quant = purchase_price * quant; 
                off_change_price_quant  = off_change_price * quant; 
                $("#change_price").html(purchase_price_quant);
                $("#off_change_price").html(off_change_price_quant);
            });
            el.parent().on('click', '.add', function(){
                if(el.val() < parseInt(el.attr('max')))
                el.val( function(i, oldval) { return ++oldval; });
                purchase_price = $("#purchase_price").val();
                off_change_price = $("#off_price").val();
                quant = $(".quantity_no").val();
                purchase_price_quant = purchase_price * quant; 
                off_change_price_quant  = off_change_price * quant; 
                $("#change_price").html(purchase_price_quant);
                $("#off_change_price").html(off_change_price_quant);
            });
        });
    };
})(jQuery);
$('input[type=number]').spinner();
$(document).ready(function(){
    if($(window).width() < 500){
        $("#girlstop1").glassCase({
            'widthDisplay': 800,'heightDisplay': 800,
            'isSlowZoom': false,'isSlowLens': false,
            'capZType': 'out','thumbsPosition': 'bottom',
            'autoInnerZoom': false, 'isHoverShowThumbs': true,
            'colorIcons': '#fff', //'colorActiveThumb': '#000',
            'zoomHeight':600,'zoomWidth': 580,
            'isZoomDiffWH': true, 'isShowAlwaysIcons': true,
            'isZCapEnabled': false, 'isZoomEnabled':false,
        }); 
    } else{
        $("#girlstop1").glassCase({
            'widthDisplay': 700,'heightDisplay': 500,
            'isSlowZoom': true,'isSlowLens': true,
            'capZType': 'out','thumbsPosition': 'left',
            'autoInnerZoom': false, 'isHoverShowThumbs': true,
            'colorIcons': '#fff', //'colorActiveThumb': '#000',
            'zoomHeight':600,'zoomWidth': 580,
            'isZoomDiffWH': true, 'isShowAlwaysIcons': true,
            'isZCapEnabled': false, 'thumbsMargin' : 15,
        });    
    }
    $(".gc-display-container").addClass("mt-0");
});
var owl = $('.owl-also-bought');
owl.owlCarousel({
    loop: false,nav: true,margin: 30,dots:false,
    responsive: { 0: {items: 2},600: {items: 2},960: {items: 5}}
});
function variationchange(totalvariation){
    arr = [];
    for(i=0;i<totalvariation;i++){
        var variation=$(".var-attr-"+i+":checked").val();
        if(typeof(variation)!="undefined") arr.push(variation)
    }
    if(totalvariation==arr.length){
        variations = JSON.stringify(arr);
        $.ajax({
            type: "POST",
            url: siteurl+"/ajax/product_ajax.php",
            data: {prodid:"<?=$prodid;?>",variations:variations,action:"getvariationURL"},
            success: function(html) { if(html!=""){window.location=siteurl+"/"+html}else{ $(".variationerror").html("Note : Product Not Available with selected combination"); $(".buytocart").addClass("d-none")} }
        });
    }
}
$(".reviewbtn").click(function(event){
    //Fetch form to apply custom Bootstrap validation
    var form = $("#formReview")
    if (form[0].checkValidity() === false){
        event.preventDefault()
        event.stopPropagation()
    }else{addReview();}
    form.addClass('was-validated');
});
function addReview(){
    main_prod_id = '<?php echo $getparent_id; ?>'; productid = $(".productid").val(); userid=$(".userid").val(); reviewTitle=$(".reviewTitle").val(); reviewComment = $(".reviewComment").val();
    rating = $("input[name='rating2']:checked").val();
    $.ajax({
        type: "POST",
        url: siteurl+"/ajax/product_ajax.php",
        data: {main_prod_id:main_prod_id,productid:productid,userid:userid,reviewTitle:reviewTitle,reviewComment:reviewComment,rating:rating,action:"addReview"},
        success: function(html){
            if(html==1){
                $('.rewiw_msgs1').fadeIn().html("Review Submited successfully , Your Review will get published once its approved by admin").removeClass("alert alert-danger").addClass("alert alert-success").delay(5000).fadeOut();
            setTimeout(function(){  $("#addreview").modal('hide') }, 5000);
            }else{
                $(".rewiw_msgs1").addClass("text-danger").html("Technical Issue.. Please Try After Some Time");
            }
        }
    });
}
function checkdelivery(prodid,price){
    pincode=$(".pincode").val();
    if(pincode!=""){
         $.ajax({
            type: "POST",
            url: siteurl+"/ajax/product_ajax.php",
            data: {prodid:prodid,price:price,pincode:pincode,action:"checkpincode"},
            success: function(resr){ $(".deliverydetails").html(resr) }
        });
    }
}
var owl = $('.owl-cust-also-bought');
owl.owlCarousel({ loop: false, nav: true, margin: 20, dots:false, responsive: { 0: {items: 2},600: {items: 3},960: {items: 5}} });
/*function add_to_compare(){
    $( ".sing-item-compare").load(".sing-item-compare");
    var comparecheck = $(".thumb-hov-btn input");
    $(comparecheck).parents(".btn").addClass("btn-primary border-0").attr("disabled", "disabled");
    if($(comparecheck).is(':checked')){
        alert("Ddfdf")
        $(comparecheck).parents(".btn").addClass("btn-primary border-0");  
    } else{
        $(comparecheck).parents(".btn").removeClass("btn-primary border-0");     
    }
}*/
$(".review-count").click(function(){
    $(".tab-pane, .product-specs-tab li a").removeClass("active");
    $(".revire-tab-link").addClass("active");
    $(".cust-pro-review").addClass("active").removeClass("fade");
    $('html, body').animate({
        scrollTop: $(".product-specs-tab").offset().top
    }, 1000);
});
var owl = $('.owl-banner');
owl.owlCarousel({
    items:1,
    nav: true,
    dots:false,
    loop: true
});
function restrictAlphabets(e){
    //48-57 - (0-9)Numbers
    var x = e.which || e.keycode;
    if((x >= 48 && x <= 57)){ return true; } else{ return false; }
 }
</script>
</body>
</html> 