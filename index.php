<?php include("includes/configuration.php"); include("classes/product.php"); include("visitor.php");
$prod = new Product(); $imgtype = "product"; include("getimgpath.php"); $trendingcount = $prod->getProductsGroupCount("trending_now=1"); $newarrivalcount = $prod->getProductsGroupCount("new_arrival=1");
$actual_link = ($_SERVER['HTTPS'] == 'on'?"https://":"http://").$_SERVER[HTTP_HOST]."".$_SERVER[REQUEST_URI]; ?>
<!DOCTYPE html>
<html lang="en">
<head><title>Home : <?php echo SITE_TITLE; ?></title><meta name="description" content="<?php echo METADESCRIPTION; ?>"><meta name="keywords" content="<?php echo METAKEYWORDS; ?>"><link rel="canonical" href="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" ><?php include "commoncss.php" ?>
<!--Open Graph / Facebook-->
<meta property="og:type" content="website">
<meta property="og:url" content="<?php echo $actual_link; ?>">
<meta property="og:title" content="Home : <?php echo SITE_TITLE; ?>">
<meta property="og:description" content="<?php echo METADESCRIPTION; ?>">
<meta property="og:image" content="<?=(OGIMAGE!=""?SITEURL."/img/ogimage/".OGIMAGE:SITEURL."/img/projectimage/".(LOGO != ""?LOGO:"default_logo.png")); ?>">
<!--Twitter-->
<meta property="twitter:card" content="<?=(OGIMAGE!=""?SITEURL."/img/ogimage/".OGIMAGE:SITEURL."/img/projectimage/".(LOGO != ""?LOGO:"default_logo.png")); ?>">
<meta property="twitter:url" content="<?php echo $actual_link; ?>">
<meta property="twitter:title" content="Home : <?php echo SITE_TITLE; ?>">
<meta property="twitter:description" content="<?php echo METADESCRIPTION; ?>">
<meta property="twitter:image" content="<?=(OGIMAGE!=""?SITEURL."/img/ogimage/".OGIMAGE:SITEURL."/img/projectimage/".(LOGO != ""?LOGO:"default_logo.png")); ?>">
<link rel="stylesheet" href="<?=SITEURL;?>/css/thumb-slider/owl.carousel.min.css" /></head>
<body>
<div class="main-wrap">
<?php $Notification_home = selectQuery(NOTIFICATION,"notificationico,notificationlink,notification","isActive ='1' and show_on_homepage= '1' and show_on_all = '0' ");
if(count($Notification_home)) { ?>
<div class="notification-toggle px-5 text-center">
    <div class="container">
        <div class="row">
            <div class="col-md-12 marquee"><div class="marq-notif"><img src="<?=SITEURL; ?>/img/projectimage/<?=$Notification_home[0]['notificationico']; ?>" alt="<?=$Notification_home[0]['notificationico']; ?>" width="20" height="20" class="mr-1"/><a href="<?=($Notification_home[0]['notificationlink']!=""?$Notification_home[0]['notificationlink']:SITEURL); ?>" target="_blank" class="pt-2 pb-2 d-inline-block" hreflang="en" ><?=$Notification_home[0]['notification']; ?></a></div></div>
        </div>
    </div>
    <span class="close-notif position-absolute d-inline-block"><i class="fa fa-times"></i></span>
</div>
<?php } ?>
<?php include "menu.php"; ?>
<? $slider = selectQuery(MAIN_SLIDER,"btn_link,img_name","1 order by priority ASC");
$sliderm = selectQuery(MAIN_SLIDER_MOBILE,"btn_link,img_name","1 order by priority ASC");
if(count($slider)||!count($sliderm)){
$sliderpath = getimgconfigpaths('main slider'); ?>
<div class="carousel slide home-banner <?=(count($sliderm)?"d-none":""); ?> d-md-block" data-ride="carousel">
    <div class="owl-carousel owl-theme owl-banner">
        <?php for($i=0;$i<count($slider);$i++){ ?>
        <div class="item hm-slider-item <?php if($i == "0"){ echo "active";} ?>"><a hreflang="en" href="<? if($slider[$i]['btn_link'] == ""){ echo "#";} else{ echo $slider[$i]['btn_link'];} ?>" target="_blank"><img src="<?=SITEURL.'/'.$sliderpath[0]['imgs_location'].'/'.$slider[$i]['img_name']; ?>" alt="Main Slider Image" class="img-fluid"></a></div>
        <?php } ?>
    </div>
</div>
<?php }
if(count($sliderm)){
$sliderpath = getimgconfigpaths('main slider mobile'); ?>
<div class="carousel slide home-banner d-md-none" data-ride="carousel">
    <div class="owl-carousel owl-theme owl-banner">
        <?php for($i=0;$i<count($sliderm);$i++){ ?>
        <div class="item hm-slider-item <?php if($i == "0"){ echo "active";} ?>"><a hreflang="en" href="<? if($sliderm[$i]['btn_link'] == ""){ echo "#";} else{ echo $sliderm[$i]['btn_link'];} ?>" target="_blank"><img src="<?=SITEURL.'/'.$sliderpath[0]['imgs_location'].'/'.$sliderm[$i]['img_name']; ?>" alt="Main Slider Image" class="img-fluid"></a></div>
        <?php } ?>
    </div>
</div>
<?php } ?>
    
<? if($newarrivalcount){
$productids = $prod->getProductsByGroup("p.new_arrival=1"); ?>
<div class="py-4 home-prod-carousel">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="hm-sec-head mb-4 position-relative text-center"><h2 class="mb-0 pb-3 hm-sec-font-size hm-sec-text-color cc-fw-6">NEW ARRIVAL</h2></div>
                <div class="owl-carousel owl-theme owl-newarrival row owl-row-zero-margin owl-vertically"><? for($i=0;$i<count($productids);$i++){ include("product-view.php"); } ?></div>
            </div>
        </div>
    </div>
</div>
<? } ?>
    
<?php $parent = selectQuery(PRODCAT,"cat_name,img_name,id,type","isActive='1' and img_name <> '' order by priority");
if(count($parent)){ ?>
<div class="shop-by-category py-4 cc-primary-back">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="hm-sec-head mb-3 text-center position-relative"><h2 class="mb-0 pb-3 hm-sec-font-size hm-sec-text-color cc-fw-6 text-white text-uppercase">Shop By Category</h2></div>
                <div class="owl-carousel owl-theme owl-shop-category row owl-row-zero-margin owl-vertically">
                    <?php $catpath = getimgconfigpaths('category'); 
                    for($p=0;$p<count($parent);$p++){ 
                    $parenttype = $parent[$p]['type']; $parentid=$parent[$p]['id']; ?>
                    <div class="item col-6 col-sm-4 col-md-4 col-lg-12 pr-0 px-lg-0 mb-3 mb-lg-0 text-center"><a href="<?=SITEURL;?>/<?=getUrl($parenttype,$parentid);?>" hreflang="en"><div class="categ-item position-relative shop-by-cat d-flex flex-wrap align-content-center rounded-circle hvr-border-zoom"><img src="<?=SITEURL.'/'.$catpath[0]['imgs_location'].'/'.$parent[$p]['img_name']; ?>" alt="<?php echo $parent[$p]['cat_name'] ?>" class="mw-100 m-auto rounded"></div>
                    <div class="h6 category-head text-center cc-fw-5 mb-0 mt-2 mt-lg-4 text-white text-uppercase"><?php echo $parent[$p]['cat_name'] ?></div></a></div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>
    
<?php $offcnts = selectQuery( OFFER, "offer_name,img,offer_link", "isActive='1' and showonhomepage ='1' order by priority " ); $offerpath = getimgconfigpaths('offer');
if(count( $offcnts ) != 0){ ?>
<div class="home-offer-sldier pt-4">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="owl-carousel owl-theme owl-offer row owl-row-zero-margin owl-vertically">
                    <?php for( $i = 0; $i < count( $offcnts ); $i++ ){?>
                    <div class="item col-12 col-sm-6 col-lg-12 pr-0 px-lg-0 mb-3 mb-sm-0">
                        <div class="offer-col position-relative">
                            <a href="<? if($offcnts[$i]['offer_link'] != ""){ echo $offcnts[$i]['offer_link'];} else { echo SITEURL; } ?>" target="_blank" hreflang="en" class="w-100 h-100 d-block d-flex flex-wrap align-content-center" aria-label="offer-slider">  
                            <img class="mw-100 mh-100 bg-light rounded" src="<?php echo SITEURL."/".$offerpath[0]['imgs_location']."/".$offcnts[$i]['img']; ?>" alt="<?=$offcnts[$i]['offer_name'];?>">
                            <span class="hover hover1"></span><span class="hover hover2"></span><span class="hover hover3"></span><span class="hover hover4"></span>
                            <!--<?php if( $offcnts[$i]['offer_name'] != ""){ ?>
                            <h4><?php if ( strlen( $offcnts[$i]['offer_name']) > 50){
                                $arr = str_split( $offcnts[$i]['offer_name']);
                                $str = "";
                                for ( $j = 0; $j < 55; $j++ ) {$str = $str . "" . $arr[$j];}
                                echo $str . "...";
                            } else{ echo $offcnts[$i]['offer_name']; }
                            ?></h4><?php } ?>-->
                            </a>  
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<?php $getstoryvideo = selectQuery(YOUTUBEVIDEO,"yvideo_link","yvideo_type='story'","isActive='1'"); 
if(count($getstoryvideo) != 0){ ?>
<div class="abt-youtube-video py-4 border-bottom">
    <div class="container">
        <div class="row">
            <?php for($ystoryvideo=0;$ystoryvideo<count($getstoryvideo);$ystoryvideo++){ ?>
            <div class="col-12">
            <iframe height="400" class="border-0 w-100" src="<?php echo $getstoryvideo[$ystoryvideo]['yvideo_link']; ?>" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
            <? } ?>
        </div>
    </div>
</div>
<? } ?>

<? if($trendingcount){
$productids = $prod->getProductsByGroup("p.trending_now=1"); ?>
<div class="home-prod-carousel py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="hm-sec-head mb-4 position-relative text-center"><h2 class="mb-0 pb-3 hm-sec-font-size hm-sec-text-color cc-fw-6">TRENDING NOW</h2></div>
                <div class="owl-carousel owl-theme owl-newarrival row owl-row-zero-margin owl-vertically">
                <? for($i=0;$i<count($productids);$i++){ ?>
                <? include("product-view.php"); ?>
                <? } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<? } ?>

<!--<? $getblog = selectQuery( BLOG, "id,title,category", "isActive='1' and showonhomepage ='1' " );
if( count($getblog) != 0){ ?>
<div class="tradebox cc-prod-list-sec">
    <div class="container">
        <h3 class="cc-margin-top-0 cc-margin-bottom-25">Blog</h3>
        <div class="row">
            <div class="col-md-12">
                <div class="owl-carousel owl-theme owlblog">
                    <?php for($i = 0; $i < count( $getblog); $i++){
                    $blogidimg = selectQuery(BLOGIMG, "img_name", "blogid='" . $getblog[$i]['id'] . "'" );
                    $cat_name = selectQuery(BLOGCAT,"category_name","cat_id='".$getblog[$i]['category']."'"); ?>
                    <div class="item">
                        <div class="blog-box card card-3">
                            <figure>
                                <a href="<?=SITEURL;?>/blog/<?=$cat_name[0]['category_name']; ?>/<?=$getblog[$i]['title'] . "-" . $getblog[$i]['id'];?>">
                                    <img class="img-responsive" src="<?=SITEURL;?>/<?php if ( $blogidimg[0]['img_name'] != "" ) {echo "img/blogimg/" . $blogidimg[0]['img_name'];} else {echo "img/projectimage/No_image_available.png";}?>"  alt="<?=$getblog[$i]['title'];?>" width="270" height="200">
                                </a>
                            </figure>
                            <div class="blog-body">
                                <h3 class="blog-title cc-margin-top-0 cc-font-thick-400" title="<?=$getblog[$i]['title'];?>"><?=$getblog[$i]['title'];?></h3>
                                <a href="<?=SITEURL;?>/blog/<?=$cat_name[0]['category_name']; ?>/<?=$getblog[$i]['title'] . "-" . $getblog[$i]['id'];?>">
                                    Read More <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<? } ?>-->
<?php $brands = selectQuery( BRAND, "target_link,brand_name,img", "isActive='1' order by priority " );
if(count( $brands ) != 0){ 
$brandpath = getimgconfigpaths('brand'); ?>
<div class="brand_slide py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="hm-sec-head mb-4 position-relative text-center"><h2 class="mb-0 pb-3 hm-sec-font-size hm-sec-text-color cc-fw-6 text-uppercase">Show Some Brand Love</h2></div>
                <div class="owl-carousel owl-theme owl-brand row owl-row-zero-margin owl-vertically">
                    <?php for($i = 0; $i < count($brands); $i++){ ?>
                    <div class="item col-6 col-sm-4 col-lg-12 pr-0 px-lg-0 mb-3 mb-lg-0">
                        <div class="product-thumb photo-grid border rounded p-3 text-center"><a hreflang="en" target="_blank" href = <?php if($brands[$i]['target_link'] != "" ){ echo $brands[$i]['target_link']; } else{ echo "#"; } ?>><div class="brand-thumb d-flex flex-wrap align-content-center"><img src="<?php echo SITEURL."/".$brandpath[0]['imgs_location']."/".$brands[$i]['img'];?>" alt="<? if(trim($brands[$i]['brand_name']) == ""){ echo "Brand"; } else{ echo $brands[$i]['brand_name']; } ?>" class="img-fluid m-auto" height="200"/></div></a> 
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php }?>
<?php $gettestimonials = selectQuery( TESTIMONIALS, "product_name,testimonials,client_name,img_name", "isActive='1'order by priority  " );
if(count($gettestimonials) != 0){ 
$testimonial_path = getimgconfigpaths('testimonials'); ?>
<div class="testinomial pt-4 pb-0 border-top">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <p class="m-0 small text-muted">TESTIMONIAL</p>
                <h2 class="mb-4 m-0 hm-sec-font-size hm-sec-text-color text-center">What Our Happy Customers Say</h2>
                <div class="owl-carousel owl-theme owl-testimonial">
                    <?php for($i = 0; $i < count( $gettestimonials ); $i++ ){ ?>
                    <div class="pb-3 item<?php if ( $i == 1 ) {echo "active";}?>">
                        <div class="card p-4 textm-item">
                            <div class="test-info mb-2">
                                <?php if(strlen($gettestimonials[$i]['testimonials']) > 180){
                                $arr = str_split( $gettestimonials[$i]['testimonials'] );
                                $str = "";
                                for($j = 0; $j < 145; $j++){ $str = $str . "" . $arr[$j]; } echo $str . "...";
                                } else{echo $gettestimonials[$i]['testimonials'];} ?>
                            </div>
                            <div class="test-user-thumb rounded-circle mb-2 mx-auto"><img class="img-fluid" src="<?php echo SITEURL."/".$testimonial_path[0]['imgs_location']."/".$gettestimonials[$i]['img_name']; ?>" alt="Testimonial-thumb" width="80" height="80"></div>
                            <?php if($gettestimonials[$i]['client_name']!= "" ){ ?>
                            <div class="h6 mb-1 text-primary" title="<?php echo $gettestimonials[$i]['client_name']; ?>">
                                <?php if(strlen($gettestimonials[$i]['client_name']) > 13){
                                $arr = str_split( $gettestimonials[$i]['client_name'] );
                                $str = "";
                                for($j = 0; $j < 15; $j++){$str = $str . "" . $arr[$j];}
                                echo $str . "...";
                                } else{echo $gettestimonials[$i]['client_name'];} ?>
                            </div>
                            <?php } ?>
                            <p class="small mb-0">
                                <?php if(strlen($gettestimonials[$i]['product_name']) > 33){
                                $arr = str_split($gettestimonials[$i]['product_name']);
                                $str = "";
                                for($j = 0; $j < 35; $j++){ $str = $str . "" . $arr[$j]; }
                                echo $str . "...";
                                } else{ echo $gettestimonials[$i]['product_name']; } ?>
                            </p>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<?php $getyoutuvevideo = selectQuery(YOUTUBEVIDEO,"yvideo_link","yvideo_type='video'","isActive='1'"); 
if(count($getyoutuvevideo) != 0){ ?>
<div class="home-youtube-video pt-4">
    <div class="container">
        <div class="row mr-0">
            <?php for($yvidcount=0;$yvidcount<count($getyoutuvevideo);$yvidcount++){ ?>
            <div class="col-sm-4 mb-3 pr-0">
                <iframe height="250" class="border-0 w-100" src="<?php echo $getyoutuvevideo[$yvidcount]['yvideo_link']; ?>" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
            <? } ?>
        </div>
    </div>
</div>
<? } ?>
<div class="client-trust-bottom py-4">
    <div class="container">
        <div class="row mr-0">
            <div class="col-sm-6 col-md-6 col-lg-4 mb-3 mb-lg-0">
                <div class="media border p-3 rounded">
                    <span class="sli-trust-icon rounded-circle bg-white border"><svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 44 44" fill="none"><g clip-path="url(#clip0)"><path d="M10 6L33.5 17.5" stroke="#000" stroke-width="2"></path><path d="M43 12.4545L19.6667 1L1 11.6909M43 12.4545V32.3091L24.3333 43M43 12.4545L24.3333 22.3818M24.3333 43L1 31.5455V11.6909M24.3333 43V22.3818M1 11.6909L24.3333 22.3818" stroke="#000" stroke-width="2"></path></g><defs><clipPath id="clip0"><rect width="44" height="44" fill="white"></rect></clipPath></defs></svg></span>
                    <div class="media-body pl-3">
                        <h4 class="cc-fw-5 h5">High-Quality Goods</h4>
                        <div>Enjoy top quality items for less</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-4 mb-3 mb-lg-0">
                <div class="media border p-3 rounded">
                    <span class="sli-trust-icon rounded-circle bg-white border"><svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 44 44" fill="none"><path d="M6.85085 2H1V0H8.60955L13.9746 30H36.8179L42.0322 8.54867L44 8.91079L38.4841 32H12.216L6.85085 2Z" fill="#000"></path><path d="M23.0173 4L24.4315 5.41293L20.8284 9.01273L32 9.01273V25H30V11.0109L20.8257 11.0109L24.4315 14.6134L23.0173 16.0263L17.1266 10.141L17.128 10.1397L17 10.0118L23.0173 4Z" fill="#000"></path><circle cx="34" cy="39" r="4" stroke="#000" stroke-width="2"></circle><circle cx="16" cy="39" r="4" stroke="#000" stroke-width="2"></circle></svg></span>
                    <div class="media-body pl-3">
                        <h4 class="cc-fw-5 h5">Express Shipping</h4>
                        <div>Fast & reliable delivery options</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-4 mb-3 mb-lg-0">
                <div class="media border p-3 rounded">
                    <span class="sli-trust-icon rounded-circle bg-white border"><svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 44 44" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M42 17V28C42 29.1046 41.1046 30 40 30V32C42.2091 32 44 30.2091 44 28V6C44 3.79086 42.2091 2 40 2H4C1.79086 2 0 3.79086 0 6V28C0 30.2091 1.79086 32 4 32H22V30H4C2.89543 30 2 29.1046 2 28V17H42ZM40 4H4C2.89543 4 2 4.89543 2 6V8H42V6C42 4.89543 41.1046 4 40 4ZM42 10H2V15H42V10Z" fill="#000"></path><path d="M6 23H16V25H6V23Z" fill="#000"></path><rect x="23" y="27" width="16" height="14" rx="1" stroke="#000" stroke-width="2"></rect><path d="M27 26C27 23.7909 28.7909 22 31 22C33.2091 22 35 23.7909 35 26V27H27V26Z" stroke="#000" stroke-width="2"></path><rect x="30" y="33" width="2" height="4" fill="#000"></rect></svg></span>
                    <div class="media-body pl-3">
                        <h4 class="cc-fw-5 h5">Secure Payments</h4>
                        <div>Multiple safe payment methods</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?php include "footer.php"; ?>
<script>siteurl = "<?php echo SITEURL; ?>";</script>
<script src="<?=SITEURL;?>/js/thumb-slider/owl.carousel.min.js"></script>
<script src="<?=SITEURL;?>/js/product.js"></script>
<script>
var owl = $('.owl-banner');
owl.owlCarousel({items:1, nav: false, dots:true, loop: true, autoplay:true, autoplayTimeout:4000, autoplayHoverPause:true });
var owl = $('.owl-testimonial');
owl.owlCarousel({loop: false,nav: true,margin: 30,dots:false,responsive: { 0: {items: 1},500: {items: 2},800: {items: 3},1000: {items: 4}}});    
$(document).ready(function(){
    if($(window).width() > 992 ){
    startCarousel();
    } else{
        $('.owl-vertically').removeClass('owl-carousel');
        $(".pro-thumb-col").each(function(){ $(this).wrap("<div class='col-6 col-md-4 pr-0 mb-3 mb-lg-0'></div>"); });
    }
});
$(window).resize(function(){ if($(window).width() > 992){ startCarousel(); } else{ stopCarousel(); } });
function startCarousel(){
    var owl = $('.owl-offer');
    owl.owlCarousel({loop: false,nav: false,margin: 20,dots:true,autoplay:true, autoplayTimeout:4000, autoplayHoverPause:true,responsive: { 0: {items: 1},600: {items: 2},960: {items: 2}}});
    var owl = $('.owl-newarrival');
    owl.owlCarousel({loop: false,nav: true,margin: 15,dots:false,responsive: { 0: {items: 2},600: {items: 3},960: {items: 5}}});    
    var owl = $('.owl-brand');
    owl.owlCarousel({loop: false,nav: true,margin: 20,dots:false,autoplay:true, autoplayTimeout:4000, autoplayHoverPause:true,responsive: { 0: {items: 2},600: {items: 3},960: {items: 5}}});
    var owl = $('.owl-shop-category');
    owl.owlCarousel({loop: false,nav: true,margin: 20,dots:false,responsive: { 0: {items: 2},600: {items: 3},960: {items: 6}}});
}
function stopCarousel(){
    var owl = $('.owl-vertically'); owl.trigger('destroy.owl.carousel'); owl.removeClass('owl-carousel');
}
    if($('.home-banner img').length == 1){
    $('.hm-slide-handler').hide();
}
</script>
</body>
</html> 