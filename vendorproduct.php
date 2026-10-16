<?php 
include("includes/configuration.php");
include("classes/product.php");
$segment1 = trim($_GET['name'],"/");
$get_vendor_details =selectQuery(VENDOR,"dealer_id","nickname= '".$segment1."'");
$prod = new Product();
$where = 'vendor='.$get_vendor_details[0]['dealer_id'];
$productfilters = $prod->getProductsFilters("vendor",$get_vendor_details[0]['dealer_id']);
$actual_link = ($_SERVER['HTTPS'] == 'on'?"https://":"http://").$_SERVER[HTTP_HOST]."".$_SERVER[REQUEST_URI]; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Vendor Product : <?=SITE_TITLE; ?></title>
    <meta name="description" content="<?=METADESCRIPTION; ?>">
    <meta name="keyword" content="<?=METAKEYWORDS; ?>">
    <!--Open Graph / Facebook-->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>">
    <meta property="og:title" content="Vendor Product : <?=SITE_TITLE; ?>">
    <meta property="og:description" content="<?php echo METADESCRIPTION; ?>">
    <meta property="og:image" content="<?=(OGIMAGE!=""?SITEURL."/img/ogimage/".OGIMAGE:SITEURL."/img/projectimage/".(LOGO != ""?LOGO:"default_logo.png")); ?>"> 
    <!--Twitter-->
    <meta property="twitter:card" content="<?=(OGIMAGE!=""?SITEURL."/img/ogimage/".OGIMAGE:SITEURL."/img/projectimage/".(LOGO != ""?LOGO:"default_logo.png")); ?>">
    <meta property="twitter:url" content="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>">
    <meta property="twitter:title" content="Vendor Product : <?=SITE_TITLE; ?>">
    <meta property="twitter:description" content="<?php echo METADESCRIPTION; ?>">
    <meta property="twitter:image" content="<?=(OGIMAGE!=""?SITEURL."/img/ogimage/".OGIMAGE:SITEURL."/img/projectimage/".(LOGO != ""?LOGO:"default_logo.png")); ?>">
    <link rel="canonical" href="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" >
    <?php include "commoncss.php"; ?>
    <link rel="stylesheet" href="<?=SITEURL; ?>/css/jquery-ui.css">
</head>
<body>
<div class="main-wrap">
    <?php include "menu.php" ?>
    <div class="container pt-4 pb-4">        
        <div class="row">
            <div class="col-md-3 filter-aside">
                <div class="row">
                    <div class="back-filter p-3 d-block d-sm-none shadow bg-primary col-md-12 cc-cursor-pointer text-white mb-2 cc-fw-6">
                        <i class="fa fa-arrow-left mr-2" aria-hidden="true"></i> Filters
                    </div>
                </div>
                <div id="accordion">
                    <input type="hidden" class="totalfilters" value="<?=count($productfilters); ?>"/>
                    <? $filtercnt=0;
                    foreach($productfilters as $key=>$val){
                    if(count($val)){  $oriname=($key!="company"&&$key!="price"?getOriginalName($key):$key); ?>
                    <div class="filter-coll-list">
                        <div class="filter-head">
                            <a class="card-link d-block py-2 border-bottom text-uppercase position-relative" data-toggle="collapse" href="#collapse<?=$filtercnt; ?>"><?=ucwords($oriname);?></a>
                        </div>
                        <div id="collapse<?=$filtercnt; ?>" class="collapse show" data-parent="#accordion">
                            <div class="card-body pl-0 pb-1 pt-3">
                                <input type="hidden" class="sort<?=$filtercnt;?>name" value="<?=$key; ?>"/>
                                <? if($key!="price"){
                                for($i=0;$i<count($val);$i++){ ?>
                                <div class="custom-control custom-checkbox mb-2 cc-cursor-pointer">
                                    <input type="checkbox" name="sort<?=$filtercnt;?>" class="custom-control-input sort<?=$filtercnt;?>" id="<?=$key.$i ?>" value="<?=$val[$i]; ?>" onclick="displayprod('<?=SITEURL ?>','<?=$where;?>','1')">
                                    <label class="custom-control-label cc-cursor-pointer" for="<?=$key.$i ?>"><?=$val[$i]; ?></label>
                                </div>
                                <? } }else{ ?>
                                <input type="text" id="amount" readonly class="border-0 p-0 mb-3">
                                <input type="hidden" id="minamount" value='<?=$val[0];?>'>
                                <input type="hidden" id="maxamount" value='<?=$val[1];?>'>
                                <input type='hidden' value='<?=$val[1];?>' id='maxlimit'>
                                <div itemscope itemtype="http://schema.org/Offer">
                                <div id="slider-range" itemprop="price"></div>
                                </div>
                                <? } ?>
                            </div>
                        </div>
                    </div>
                    <? }
                    $filtercnt++; } ?>
                </div>
            </div>
            <div class="col-md-9">
                <div>
                <h5 class="d-inline-block mb-1">Displaying Product of Vendor <?=$segment1; ?></h5><span class="text-muted ml-1">(<span class="itmtocount"></span> )</span>
                </div>
                <div class="sortnav mb-3">
                    <ul class="list-unstyled mb-0">
                        <li class="p-0">Sort By :</li>
                        <li class="sortby sort1 cc-cursor-pointer selected text-primary" data-val="id DESC" onclick="sortprod('id DESC','sort1')">Newest First</li>
                        <li class="sort2 sortby cc-cursor-pointer" data-val="final_price ASC" onclick="sortprod('final_price ASC','sort2')">Price Low To High</li>
                        <li class="sort3 sortby cc-cursor-pointer" data-val="final_price DESC" onclick="sortprod('final_price DESC','sort3')">Price High To Low</li>
                    </ul>
                </div>
                <div class="row products" >
                 
                 </div>
            </div>
        </div>
    </div>
</div>
<div class="colaps-filter-btn d-block d-sm-none text-center position-fixed shadow">
    <div class="row">
        <div class="col-6 p-3 border-right cc-fw-6 cc-cursor-pointer sort-item-btn"><i class="fa fa-sort-amount-desc" aria-hidden="true"></i> Sort</div>
        <div class="col-6 p-3 cc-fw-6 cc-cursor-pointer fil-item-btn"><i class="fa fa-filter" aria-hidden="true"></i> Filter</div>
    </div>
</div>
<?php include "footer.php"; ?>
<script src="<?=SITEURL;?>/js/product.js"></script>
<script src ="<?php echo SITEURL; ?>/js/jquery-ui.min.js"></script>
<script src="<?=SITEURL;?>/js/product.js"></script>
<script>
var siteurl = "<?=SITEURL; ?>"
var where = "<?=$where; ?>";
var template = "";
displayprod(siteurl,where, 1);
$(function(){
    $("#slider-range").slider({
        range: true,
        min: 0,
        max: $("#maxlimit").val(),
        values: [ 0, $("#maxlimit").val() ],
        slide: function( event, ui ) {
            $("#amount").val( "Rs " + ui.values[ 0 ] + " - Rs " + ui.values[ 1 ] );
            $("#minamount").val(ui.values[ 0 ]);$("#maxamount").val(ui.values[ 1 ]);
        }
    });
    $("#amount").val( "Rs " + $("#slider-range").slider("values", 0) +
    " - Rs " + $( "#slider-range" ).slider( "values", 1 ));
});
$(".fil-item-btn, .back-filter").click(function(){
    $(".filter-aside").slideToggle();
    $("body").toggleClass("filter-collapsed");
});  
$(".sort-item-btn").click(function(){
    $(".sortnav").slideToggle();
    $("body").toggleClass("sort-collapsed");
});
</script>
</body>
</html>