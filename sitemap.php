<?php include("includes/configuration.php");
    include("classes/product.php");
    $prod = new Product();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sitemap : <?php echo SITE_TITLE; ?></title>
    <meta name="description" content="<?php echo METADESCRIPTION; ?>">
    <meta name="keywords" content="<?php echo METAKEYWORDS; ?>">
    <link rel="canonical" href="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" >
    <?php include "commoncss.php";
    $getstaticpagedetails=selectQuery(STATIC_PAGE,"cancellation_refund_data,privacy_policy_data,terms_condition_data,faq_data,about_us_data,international_ship_data","id= 1"); ?>
</head>
<body>
<div class="main-wrap">     
    <?php include "menu.php" ?>
    <div class="container pt-3"><div class="row"><div class="col-12"><ul class="breadcrumb p-0 mb-0"><li class="breadcrumb-item"><a href="<?=SITEURL;?>">Home</a></li><li class="breadcrumb-item active">Sitemap</li></ul></div></div></div>
    <div class="content pt-3">
        <div class="container"> 
            <div class="row">
                <div class="col-md-12 sitemap-tree">
                    <h1 class="mb-3 h5">Sitemap</h1>
                    <div id="accordion" class="border-bottom">
                    <div class='Parent py-2 px-3 border-left border-top border-right sitmap-parent cc-cursor-pointer' data-toggle='collapse' data-target='#collapse".$p."'><a href="<?=SITEURL; ?>" class='d-inline-block' target='_blank' hreflang="en">Home</a></div>
                    <div class='Parent py-2 px-3 border-left border-top border-right sitmap-parent cc-cursor-pointer' data-toggle='collapse' data-target='#collapse".$p."'><a href="<?=SITEURL; ?>/login" class='d-inline-block' target='_blank' hreflang="en">Login</a></div>
                    <div class='Parent py-2 px-3 border-left border-top border-right sitmap-parent cc-cursor-pointer' data-toggle='collapse' data-target='#collapse".$p."'><a href="<?=SITEURL; ?>/register" class='d-inline-block' target='_blank' hreflang="en">Create Account</a></div>
                    <?php if($getstaticpagedetails[0]['about_us_data'] == 1){ ?>
                    <div class='Parent py-2 px-3 border-left border-top border-right sitmap-parent cc-cursor-pointer' data-toggle='collapse'><a href="<?=SITEURL; ?>/about-us" class='d-inline-block' target='_blank' hreflang="en">About Us</a></div>
                    <?php } ?>
                    <div class='Parent py-2 px-3 border-left border-top border-right sitmap-parent cc-cursor-pointer' data-toggle='collapse'><a href="<?=SITEURL; ?>/contact" class='d-inline-block' target='_blank' hreflang="en">Contact</a></div>
                   
                    <div class='Parent py-2 px-3 border-left border-top border-right sitmap-parent cc-cursor-pointer' data-toggle='collapse' data-target='#collapsecustser'><i class='fa fa-plus pr-2'></i><a>Customer Services</a></div>
                    <div id='collapsecustser' class='collapse p-2 border sitmap-list-body' data-parent='#accordion'>
                            
                                <ul class="sitmap-prod-body list-unstyled row pl-4 ml-0 mr-0">
                               <? if($getstaticpagedetails[0]['cancellation_refund_data'] == 1){ ?>
                                <li class='col-md-12 col-lg-12'><a href="<?=SITEURL; ?>/cancellation-return" class='d-inline-block' target='_blank' hreflang="en"><i class='fa fa-chevron-right'></i> Cancellation & Returns</a></li>
                                <?php } 
                                if($getstaticpagedetails[0]['privacy_policy_data'] == 1){ ?>
                                <li class='col-md-12 col-lg-12'><a href="<?=SITEURL; ?>/privacy-policy" class='d-inline-block' target='_blank' hreflang="en"><i class='fa fa-chevron-right'></i> Privacy Policy</a></li>
                                <?}
                                if($getstaticpagedetails[0]['terms_condition_data'] == 1){ ?>
                                    <li class='col-md-12 col-lg-12'><a href="<?=SITEURL; ?>/terms-condition" class='d-inline-block' target='_blank' hreflang="en"><i class='fa fa-chevron-right'></i> Terms & Conditions</a></li>
                                <?}
                                if($getstaticpagedetails[0]['faq_data'] == 1){ ?>
                                    <li class='col-md-12 col-lg-12'><a href="<?=SITEURL; ?>/faq" class='d-inline-block' target='_blank' hreflang="en"><i class='fa fa-chevron-right'></i> FAQ</a></li>
                                <?}
                                if($getstaticpagedetails[0]['international_ship_data'] == 1){ ?>
                                    <li class='col-md-12 col-lg-12'><a href="<?=SITEURL; ?>/international-shipping" class='d-inline-block' target='_blank' hreflang="en"><i class='fa fa-chevron-right'></i> International Shipping</a></li>
                                <?}    ?>
                            </ul>
                            
                    </div>
                    
                 
                    <div class='Parent py-2 px-3 border-left border-top border-right sitmap-parent cc-cursor-pointer' data-toggle='collapse' data-target='#collapsesocial'><i class='fa fa-plus pr-2'></i><a>Social Media Links</a></div>
                    <div id='collapsesocial' class='collapse p-2 border sitmap-list-body' data-parent='#accordion'>
                            
                                <ul class="sitmap-prod-body list-unstyled row pl-4 ml-0 mr-0">
                            <? if(FBLINK != ""){ ?>
                                <li class='col-md-12 col-lg-12'><a href="<?=FBLINK; ?>" class='d-inline-block' target='_blank' hreflang="en"><i class='fa fa-chevron-right'></i> Facebook</a></li>
                                <?php } 
                                if(LINKEDIN != ""){ ?>
                                <li class='col-md-12 col-lg-12'><a href="<?=LINKEDIN; ?>" class='d-inline-block' target='_blank' hreflang="en"><i class='fa fa-chevron-right'></i> Linkedin</a></li>
                                <?}
                                if(PINTEREST != ""){ ?>
                                    <li class='col-md-12 col-lg-12'><a href="<?=PINTEREST; ?>" class='d-inline-block' target='_blank' hreflang="en"><i class='fa fa-chevron-right'></i> Pinterest</a></li>
                                <?}
                                if(INSTAGRAMLINK != ""){ ?>
                                    <li class='col-md-12 col-lg-12'><a href="<?=INSTAGRAMLINK; ?>" class='d-inline-block' target='_blank' hreflang="en"><i class='fa fa-chevron-right'></i> Instagram</a></li>
                                <?}
                                if(YOUTUBELINK != ""){ ?>
                                    <li class='col-md-12 col-lg-12'><a href="<?=YOUTUBELINK; ?>" class='d-inline-block' target='_blank' hreflang="en"><i class='fa fa-chevron-right'></i> Youtube</a></li>
                                <?}
                                if(TWITTERLINK != ""){ ?>
                                    <li class='col-md-12 col-lg-12'><a href="<?=TWITTERLINK; ?>" class='d-inline-block' target='_blank' hreflang="en"><i class='fa fa-chevron-right'></i> Twitter</a></li>
                                <?}
                                if(PLAYSTORELINK != ""){ ?>
                                    <li class='col-md-12 col-lg-12'><a href="<?=PLAYSTORELINK; ?>" class='d-inline-block' target='_blank' hreflang="en"><i class='fa fa-chevron-right'></i> Playstore App Link</a></li>
                                <?}
                                ?>
                            </ul>
                            
                    </div>
                    
                    
                    <?php $offcnts = selectQuery(OFFER, "offer_name", "isActive='1'" ) ;
                    if(count($offcnts)){ ?>
                    <div class='Parent py-2 px-3 border-left border-top border-right sitmap-parent cc-cursor-pointer' data-toggle='collapse'><a href="<?=SITEURL; ?>/offer" class='d-inline-block' target='_blank' hreflang="en">Offers</a></div>
                    <?php } ?>
                    <?php $parent = selectQuery(PRODCAT,"cat_name,id,type","parent_id=0 and isActive='1' order by priority");
                    for($p=0;$p<count($parent);$p++){
                    $parentname = $parent[$p]['cat_name']; $parenttype=$parent[$p]['type']; $parentid=$parent[$p]['id'];
                    echo "<div class='Parent py-2 px-3 border-left border-top border-right sitmap-parent cc-cursor-pointer' data-toggle='collapse' data-target='#collapse".$p."'><i class='fa fa-plus pr-2'></i><a>".$parentname."</a></div>
                    <div id='collapse".$p."' class='collapse p-2 border sitmap-list-body' data-parent='#accordion'>
                    <a class='mb-2 d-inline-block' href='".SITEURL."/".getUrl($parenttype,$parentid)."' target='_blank' hreflang='en'>".$parentname."</a>";
                    $prod_cat=selectQuery(PRODCAT,"cat_name,id,type","parent_id=".$parentid." and isActive='1'");
                    for($i=0;$i<count($prod_cat);$i++){
                    $masterid = $prod_cat[$i]['id'];$mastername = $prod_cat[$i]['cat_name'];$mastertype = $prod_cat[$i]['type'];    
                    echo "<div class='master py-2 border cc-cursor-pointer' data-toggle='collapse' data-target='#collapsemaster".$i."_".$p." '><i class='fa fa-plus pl-3 pr-2'></i>".$mastername."</div>
                    <div class='subcat collapse border-bottom border-right border-left p-2 bg-light' id='collapsemaster".$i."_".$p."' data-parent='#collapse".$p."'>";
                        echo "<div class='master pb-2 mb-1'><a  href='".SITEURL."/".getUrl($mastertype,$masterid)."' target='_blank' hreflang='en'>".$mastername."</a></div>";
                        $que1=selectQuery(PRODCAT,"cat_name,id,type","parent_id=".$masterid." and isActive='1'");
                        for($j=0;$j<count($que1);$j++){ ?>
                        <? $subid = $que1[$j]['id'];$subname = $que1[$j]['cat_name'];$subtype = $que1[$j]['type'];
                        echo "<div class='subcat-link mb-2 pl-4'><a href='".SITEURL."/".getUrl($subtype,$subid)."' target='_blank' class='cc-primary-color' hreflang='en'>".$subname."</a></div>";
                        $where = 'sub_cat='.$subid;
                        $productids=$prod->getProductsByGroup($where); ?>
                        <ul class="sitmap-prod-body list-unstyled row pl-4 ml-0 mr-0">
                            <? if(is_array($productids)){
                            for($k=0;$k<count($productids);$k++){
                            $productData=$productids[$k];
                            echo "<li class='col-md-6 col-lg-4'><a class='py-1 d-inline-block'  href='".SITEURL."/".getUrl("Product",$productData['id'])."' target='_blank' hreflang='en'><i class='fa fa-chevron-right'></i>".$productData['name']."</a></li>";
                            // if(strlen($productData['name'])>20?substr($productData['name'],0,20)."..":$productData['name']);
                            } } ?>
                        </ul>
                        <? } ?>
                        </div>
                        <?php } ?></div><? } ?> <?
                        //blog link
                        $getblogc = selectQuery(BLOG,"count(id) as blogcount","isActive = '1'");
                        if($getblogc[0]['blogcount']){ ?>
                        <div class='Parent py-2 px-3 border-left border-top border-right sitmap-parent cc-cursor-pointer' data-toggle='collapse' data-target='#collapseblg'><i class='fa fa-plus pr-2'></i><a>Blog</a></div>
                        <div id='collapseblg' class='collapse p-2 border sitmap-list-body' data-parent='#accordion'>
                            <? $blogcat=selectQuery(BLOGCAT,"url_title,category_name,cat_id","isActive='1' order by category_name ASC");
                            for($i=0;$i<count($blogcat);$i++){
                            $getblog=selectQuery(BLOG,"url_title,title","isActive='1' and category= ".$blogcat[$i]['cat_id'] );
                            if(count($getblog)){ ?>
                            <div class=' py-2 border cc-cursor-pointer' data-toggle='collapse' data-target='#collapsemasterblg<?=$i; ?>'><i class='fa fa-plus pl-3 pr-2'></i><?=$blogcat[$i]['category_name']; ?></div>
                            <div class='collapse border-bottom border-right border-left p-2 bg-light' id='collapsemasterblg<?=$i; ?>' data-parent='#collapseblg'>
                                <div class='master pb-2 mb-1'><a href='<?=SITEURL."/bloglist/".$blogcat[$i]['url_title'];?>' target='_blank' hreflang="en"><?=$blogcat[$i]['category_name']; ?></a></div>
                                <ul class="sitmap-prod-body list-unstyled row pl-4 ml-0 mr-0">
                                    <? for($k=0;$k<count($getblog);$k++){ ?>
                                    <li class='col-md-6 col-lg-4'><a class='py-1 d-inline-block' href='<?=SITEURL."/blog/".$getblog[$k]['url_title'];?>' target='_blank' hreflang="en"><i class='fa fa-chevron-right'></i><?=$getblog[$k]['title'] ?></a></li>
                                    <? } ?>
                                </ul>
                            </div>
                            <? } } ?>
                        </div>
                        <? } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include "footer.php" ?> 
</body>
</html>