<?php include("includes/configuration.php");
    include("classes/product.php");
    $urltitle = $_GET['urltitle'];
    $prod = new Product();
    $categorydetails = $prod->getcategoryDetails($urltitle);
    if(!count($categorydetails)){ header("Location:404.php"); }
    $status=selectQuery(PRODCAT,"isActive","id=".$categorydetails[0]['id']);
    $pstatus=$status[0]['isActive'];
    $where = 'sub_cat='.$categorydetails[0]['id'];
    $getProductDetails = $prod->getProductFullDetails($prodid);
    $master_cat = $categorydetails[0]['parent_id'];
    $master_cat_name = $prod->getCategoryName($master_cat);
    $productfilters = $prod->getProductsFilters("sub_cat",$categorydetails[0]['id']);
    $pagetitle = $categorydetails[0]['page_title'];
    $template = $categorydetails[0]['template'];
    $parent_cat = $getProductDetails['parent_cat'];
    $master_cat = $getProductDetails['master_cat'];
    $sub_cat = $getProductDetails['sub_cat'];
    $bread = $prod-> getBreadcrumb("sub_cat",$categorydetails[0]['id']);
    $actual_link = ($_SERVER['HTTPS'] == 'on'?"https://":"http://").$_SERVER[HTTP_HOST]."".$_SERVER[REQUEST_URI];
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?=($pagetitle!=""?$pagetitle." : ".SITE_TITLE:$bread['parent']." - ".$bread['master']." - ".$bread['sub']." : ".SITE_TITLE); ?></title>
    <meta name="description" content="<?=($categorydetails[0]['metadescription']!=""?$categorydetails[0]['metadescription']:METADESCRIPTION); ?>">
    <meta name="keyword" content="<?=($categorydetails[0]['keywords']!=""?$categorydetails[0]['keywords']:METAKEYWORDS); ?>">
    <?php include "commoncss.php"; ?>
    <link rel="canonical" href="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" >
    <!--Open Graph / Facebook-->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo $actual_link; ?>">
    <meta property="og:title" content="<?=($pagetitle!=""?$pagetitle." : ".SITE_TITLE:$bread['parent']." - ".$bread['master']." - ".$bread['sub']." : ".SITE_TITLE); ?>">
    <meta property="og:description" content="<?=($categorydetails[0]['metadescription']!=""?$categorydetails[0]['metadescription']:METADESCRIPTION); ?>">
    <meta property="og:image" content="<?=(OGIMAGE!=""?SITEURL."/img/ogimage/".OGIMAGE:SITEURL."/img/projectimage/".(LOGO != ""?LOGO:"default_logo.png")); ?>">
    <!--Twitter-->
    <meta property="twitter:card" content="<?=(OGIMAGE!=""?SITEURL."/img/ogimage/".OGIMAGE:SITEURL."/img/projectimage/".(LOGO != ""?LOGO:"default_logo.png")); ?>">
    <meta property="twitter:url" content="<?php echo $actual_link; ?>">
    <meta property="twitter:title" content="<?=($pagetitle!=""?$pagetitle." : ".SITE_TITLE:$bread['parent']." - ".$bread['master']." - ".$bread['sub']." : ".SITE_TITLE); ?>">
    <meta property="twitter:description" content="<?=($categorydetails[0]['metadescription']!=""?$categorydetails[0]['metadescription']:METADESCRIPTION); ?>">
    <meta property="twitter:image" content="<?=(OGIMAGE!=""?SITEURL."/img/ogimage/".OGIMAGE:SITEURL."/img/projectimage/".(LOGO != ""?LOGO:"default_logo.png")); ?>">
    <link rel="stylesheet" href="<?=SITEURL; ?>/css/jquery-ui.css">
</head>
<body>
<div class="main-wrap">
    <?php include "menu.php";
    if($pstatus==1){?>
    <div class="colaps-filter-btn d-block d-md-none text-center bg-light border-top"><div class="row"><div class="col-6 p-3 border-right cc-cursor-pointer sort-item-btn"><i class="fa fa-sort-amount-desc" aria-hidden="true"></i> SORT</div><div class="col-6 p-3 cc-cursor-pointer fil-item-btn"><i class="fa fa-filter" aria-hidden="true"></i> FILTER</div></div></div>
    <div class="container"><ol class="breadcrumb mb-0 pl-0 pb-0 mb-3"><li class="breadcrumb-item"><a href="<?=SITEURL; ?>"  hreflang="en">Home</a></li><li class="breadcrumb-item"><a href="<?=SITEURL.'/'.getUrl("Parent",$bread['parent_id'])?>" class="point1" hreflang="en"><?=$bread['parent']; ?></a></li><li class="breadcrumb-item"><a href="<?=SITEURL.'/'.getUrl("Master",$bread['master_id'])?>" class="point1" hreflang="en"><?=$bread['master']; ?></a></li><li class="breadcrumb-item active"><?=$bread['sub']; ?></li></ol></div>
    <div class="container pb-4">
        <div class="mb-2 mb-md-0"><h1 class="d-inline-block mb-2 h5 cc-fw-5"><?=$categorydetails[0]['cat_name']; ?></h1><span class="text-muted ml-1">(<span class="itmtocount"></span>)</span></div>
        <div class="sortnav mb-4"><ul class="list-unstyled mb-0 text-muted"><li class="p-0">Sort By :</li><li class="sortby sort1 cc-cursor-pointer selected text-primary" data-val="id DESC" onclick="sortprod('id DESC','sort1')">Newest First</li><li class="sort2 sortby cc-cursor-pointer" data-val="final_price ASC" onclick="sortprod('final_price ASC','sort2')">Price Low To High</li><li class="sort3 sortby cc-cursor-pointer" data-val="final_price DESC" onclick="sortprod('final_price DESC','sort3')">Price High To Low</li></ul></div>
        <div class="row">
            <div class="col-md-4 col-lg-3 filter-aside">
                <div class="card p-3">
                    <div class="row"><div class="back-filter p-3 d-block d-sm-none shadow bg-primary col-md-12 cc-cursor-pointer text-white mb-2 cc-fw-6"><i class="fa fa-arrow-left mr-2" aria-hidden="true"></i> Filters</div></div>
                    <h5>Filter</h5>
                    <div id="accordion">
                        <input type="hidden" class="totalfilters" value="<?=count($productfilters); ?>"/>
                        <? $filtercnt = 0;
                        foreach($productfilters as $key=>$val){
                        if(count($val)){ $oriname=($key!="company"&&$key!="price"?getOriginalName($key):$key);  ?>
                        <div class="filter-coll-list">
                            <div class="filter-head">
                                <a class="card-link d-block py-2 border-bottom text-uppercase position-relative" data-toggle="collapse" href="#collapse<?=$filtercnt; ?>"><?=ucwords($oriname);?></a>
                            </div>
                            <div id="collapse<?=$filtercnt; ?>" class="collapse show" data-parent="#accordion">
                                <div class="card-body pl-0 pb-1 pt-3 pr-0">
                                    <div class="filter-cat-body mb-3">
                                    <input type="hidden" class="sort<?=$filtercnt;?>name" value="<?=$key; ?>"/>
                                    <? if($key!="price"){
                                    for($i=0;$i<count($val);$i++){ ?>
                                    <div class="custom-control custom-checkbox mb-2 cc-cursor-pointer">
                                        <input type="checkbox" name="sort<?=$filtercnt;?>" class="custom-control-input sort<?=$filtercnt;?>" id="<?=$key.$i ?>" value="<?=$val[$i]; ?>" onclick="displayprod('<?=SITEURL ?>','<?=$where;?>','1')">
                                        <label class="custom-control-label cc-cursor-pointer" for="<?=$key.$i ?>"><?  if($val[$i] != "") { echo $val[$i]; }  else { echo "Other"; }; ?></label>
                                    </div>
                                    <? } }else{ ?>
                                    <input type="text" id="amount" readonly class="border-0 p-0 mb-3">
                                    <input type='hidden' value='<?=$val[1];?>' id='maxlimit'>
                                    <input type="hidden" id="minamount" value='<?=$val[0];?>'>
                                    <input type="hidden" id="maxamount" value='<?=$val[1];?>'>
                                    <div itemscope itemtype="http://schema.org/Offer">
                                    <div id="slider-range"></div>
                                    </div>
                                    <? } ?>
                                </div>
                                </div>
                            </div>
                        </div>
                        <? }
                        $filtercnt++;
                        } ?>
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-lg-9 pl-0 pl-md-3">
                <div class="row products pro-list-view-row mr-0"></div>
            </div>
        </div>
    </div>
    <?}else{?>
        <div class="container pb-4"> <div class="alert alert-danger text-center"><h5> Sorry, This Page Is Currently Not Available.</h5></div> </div>    
    <?} ?>
</div>
<?php include "footer.php"; ?>
<script src="<?=SITEURL;?>/js/product.js"></script>
<script src ="<?=SITEURL; ?>/js/jquery-ui.min.js"></script>
<script>
var siteurl = "<?=SITEURL; ?>"
var where = "<?=$where; ?>";
var template = "<?=$template; ?>";
displayprod(siteurl,where, 1);
$(function(){
    $("#slider-range").slider({
        range: true,  min: 0, max: $("#maxlimit").val(),
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
$(".sort-item-btn, .model-popup").click(function(){
    $(".sortnav").slideToggle();
    $("body").toggleClass("sort-collapsed");
});
$(document).ready(function(){ $('[data-toggle="tooltip"]').tooltip(); });
$(document).ready(function(){
    $("#amount").parents(".filter-cat-body").removeClass("filter-cat-body").removeAttr("style");
});
</script>
</body>
</html>