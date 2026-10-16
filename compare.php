<?php 
    include("includes/configuration.php");
    include("classes/product.php");
    $imgtype = "product";
    include("getimgpath.php");
    $prod = new Product();
    $product_array = $_SESSION['compare'];
    $subcat = $_SESSION['subcat'];
    $getProductDetails = $prod->getProductFullDetails($product_array[0]);
    $templateData = $getProductDetails['templateData'];
    $highlight = $templateData['highlight'];
    $actual_link = ($_SERVER['HTTPS'] == 'on'?"https://":"http://").$_SERVER[HTTP_HOST]."".$_SERVER[REQUEST_URI];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Product Compare : <?php echo SITE_TITLE; ?></title>
    <meta name="description" content="<?php echo METADESCRIPTION; ?>">
    <meta name="keywords" content="<?php echo METAKEYWORDS; ?>">
    <!--Open Graph / Facebook-->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>">
    <meta property="og:title" content="Product Compare : <?php echo SITE_TITLE; ?>">
    <meta property="og:description" content="<?php echo METADESCRIPTION; ?>">
    <meta property="og:image" content="<?=(OGIMAGE!=""?SITEURL."/img/ogimage/".OGIMAGE:SITEURL."/img/projectimage/".(LOGO != ""?LOGO:"default_logo.png")); ?>"> 
    <!--Twitter-->
    <meta property="twitter:card" content="<?=(OGIMAGE!=""?SITEURL."/img/ogimage/".OGIMAGE:SITEURL."/img/projectimage/".(LOGO != ""?LOGO:"default_logo.png")); ?>">
    <meta property="twitter:url" content="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>">
    <meta property="twitter:title" content="Product Compare : <?php echo SITE_TITLE; ?>">
    <meta property="twitter:description" content="<?php echo METADESCRIPTION; ?>">
    <meta property="twitter:image" content="<?=(OGIMAGE!=""?SITEURL."/img/ogimage/".OGIMAGE:SITEURL."/img/projectimage/".(LOGO != ""?LOGO:"default_logo.png")); ?>">
    <link rel="canonical" href="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" >
    <?php include "commoncss.php" ?>
    <link rel="stylesheet" href="<?=SITEURL;?>/css/thumb-slider/owl.carousel.min.css" />
</head>
<body>
<div class="main-wrap">     
    <?php include "menu.php" ?>
    <div class="content item-compare-sec">
        <div class="container"> 
            <h2 class="mb-3 h5">COMPARE PRODUCTS</h2>
            <?php if( isset($_SESSION['compare']) && is_array($_SESSION['compare']) && count($_SESSION['compare'])) { ?>
            <div class="row">
                <div class="col-md-4 col-lg-3 col-xl-2 pr-0 d-none d-md-block">
                    <div class="prouct-compare-head border border-right-0">
                        <div class="sch-compare-details compare-title com-sch-name compare-item-thumb py-2 px-3 border-bottom">Product Image</div>
                        <div class="copare-item-name sch-compare-details compare-title com-sch-name py-2 px-3 border-bottom">Product Name</div>
                        <div class="sch-compare-details compare-title com-sch-name py-2 px-3 border-bottom">Price</div>
                        <?php unset($templateData['highlight']);
                        ksort($templateData);
                        foreach($templateData as $key=>$value){?>
                        <div class="copare-td-head py-2 px-3 border-bottom bg-light"><h6 class="m-0 text-primary"><?=$key;?></h6></div>
                        <? foreach($value as $key1=> $value1){
                        // if($value1!=""){ ?><div class="copare-spec-td sch-compare-details compare-title com-sch-name py-2 px-3 border-bottom"><?=getOriginalName($key1);?></div> <? /* } */    } ?>
                        <? } ?>
                    </div>
                </div> 
                <div class="col-md-8 col-lg-9 col-xl-10 pl-0">
                    <div class="owl-carousel owl-theme prouct-compare border">
                        <?php if(count($product_array)){ for($i=0;$i<count($product_array);$i++){ 
                        $getProductDetails = $prod->getProductFullDetails($product_array[$i]);
                        $templateData = $getProductDetails['templateData'];
                        $images = $getProductDetails['image'];
                        if($images[0]['img_name']){
                            $img = SITEURL."/".$thumb2_path."/".$images[0]['img_name'];
                        }else{
                            $img = SITEURL."/img/projectimage/product-default.png";
                        } ?>
                        <div class="item">
                            <div class="sch-compare-details">
                                <button type="button" class="btn-danger btn-sm p-0 border-0 rounded-circle position-absolute cc-cursor-pointer m-2 compare-del-btn">
                                    <label class="compare-close cc-cursor-pointer">
                                    <input type="checkbox" id="compare_<?php echo $product_array[$i] ?>"  onchange="add_to_compare(<?=$product_array[$i] ?>)" <?php if(in_array($product_array[$i],$_SESSION['compare'])) { echo "checked";} ?>><i class="fa fa-close" aria-hidden="true"></i></label>
                                </button>
                                <div class="compare-item-thumb p-3 border-bottom border-right text-center">
                                    <img src="<?=$img;?>" alt="compare-item-thumb" class="mh-100 mw-100">
                                </div>
                            </div>
                            <div class="copare-item-name sch-compare-details py-2 px-3 border-bottom border-right"><a href="<?=SITEURL;?>/<?=getUrl("Product",$product_array[$i]); ?>"  hreflang="en"><?php echo $getProductDetails['name']; ?></a></div>
                            <div class="copare-td sch-compare-details py-2 px-3 border-bottom border-right"> 
                            <span class="mr-1"><i class="fa fa-inr"></i><?=$getProductDetails['price']; ?></span>
                            <? if($getProductDetails['off']!=0){ ?>
                            <span class="dist-price text-muted"><del><i class="fa fa-inr"></i><?=$getProductDetails['mrp']; ?></del></span>
                            <span class="dist-per text-danger small">(<?=$getProductDetails['off']; ?>%OFF)</span>
                            <? } ?></div>
                            <? unset($templateData['highlight']);
                            ksort($templateData);
                            foreach($templateData as $key=>$value){?>
                            <div class="copare-td-head py-2 px-3 border-bottom bg-light"></div>
                            <div>
                                <? foreach($value as $key1=> $value1){
                                /* if($value1!=""){ */ ?> <div class="copare-spec-td py-2 px-3 border-bottom border-right"><? if($value1 == "") { echo "NA"; } else { echo $value1; } ?></div> <? /* } */ } ?>
                            </div>
                            <? } ?>
                        </div>
                        <?php }      
                        $productfilters = $prod->getProductsByGroup("sub_cat = '".$subcat."'");
                        $all_prod_id = array_column($productfilters ,"id" ); 
                        $diff_array = array_diff($all_prod_id, $_SESSION['compare']);
                        $diff_array = array_values($diff_array); 
                        if(count($diff_array)) { ?> 
                        <div class="item p-3">
                            <div class="custom-select mb-3 position-relative">
                            <select class="form-control form-control-sm mb-3 d-none" id="productforcompare">
                                <option value="">Choose a product</option>
                                <?php for($i=0;$i<count($diff_array);$i++) {
                                $getProductDetails = $prod->getShortDetails($diff_array[$i]); ?>
                                <option value="<?php echo $diff_array[$i]; ?>"><?php echo $getProductDetails[0]['prod_name']  ?></option>
                                <?php } ?>
                            </select>
                            </div>
                            <input type="button" class="btn btn-primary btn-sm btn-block" value="Add for Comparison" onclick="addmore()"> 
                        </div>
                        <?php } }?>
                    </div>
                </div>
            </div>
            <?php } else { echo "No product found for comparison"; }?>
        </div>
    </div>
</div>
<?php include "footer.php" ?> 
<script src="<?=SITEURL;?>/js/thumb-slider/owl.carousel.min.js"></script>
<script src="<?=SITEURL;?>/js/product.js"></script>
<script>
function addmore(){
    var productforcompare =  $("#productforcompare option:selected").val();
    if(productforcompare != ""){
        add_to_compare(productforcompare,"add_to_compare");
    }
}
var owl = $('.prouct-compare');
owl.owlCarousel({
    loop: false,
    nav: true,
    margin: 0,
    dots:false,
    responsive: { 0: {items: 1},500: {items: 2},994: {items: 3},1200: {items: 4}}
});
var h3height = 0;
$('.copare-item-name').each(function() {
    if(h3height < $(this).outerHeight()){
        h3height = $(this).outerHeight();
    };
});
$('.copare-item-name').height(h3height);
var specHeight = 0;
$('.copare-spec-td').each(function() {
    if(specHeight < $(this).outerHeight()){
        specHeight = $(this).outerHeight();
    };
});
var specHeight = 0;
$('.copare-spec-td').each(function() {
    if(specHeight < $(this).outerHeight()){
        specHeight = $(this).outerHeight();
    };
});
$('.copare-spec-td').height(specHeight - 15);
var comheadHeight = 0;
$('.copare-td-head').each(function() {
    if(comheadHeight < $(this).outerHeight()){
        comheadHeight = $(this).outerHeight();
    };
});
$('.copare-td-head').height(comheadHeight - 15);
</script>
<script>
var x, i, j, selElmnt, a, b, c;
x = document.getElementsByClassName("custom-select");
for (i = 0; i < x.length; i++) {
  selElmnt = x[i].getElementsByTagName("select")[0];
  a = document.createElement("DIV");
  a.setAttribute("class", "select-selected");
  a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
  x[i].appendChild(a);
  b = document.createElement("DIV");
  b.setAttribute("class", "select-items border select-hide");
  for (j = 1; j < selElmnt.length; j++) {
    c = document.createElement("DIV");
    c.innerHTML = selElmnt.options[j].innerHTML;
    c.addEventListener("click", function(e) {
        var y, i, k, s, h;
        s = this.parentNode.parentNode.getElementsByTagName("select")[0];
        h = this.parentNode.previousSibling;
        for (i = 0; i < s.length; i++) {
          if (s.options[i].innerHTML == this.innerHTML) {
            s.selectedIndex = i;
            h.innerHTML = this.innerHTML;
            y = this.parentNode.getElementsByClassName("same-as-selected");
            for (k = 0; k < y.length; k++) {
              y[k].removeAttribute("class");
            }
            this.setAttribute("class", "same-as-selected");
            break;
          }
        }
        h.click();
    });
    b.appendChild(c);
  }
  x[i].appendChild(b);
  a.addEventListener("click", function(e) {
      e.stopPropagation();
      closeAllSelect(this);
      this.nextSibling.classList.toggle("select-hide");
      this.classList.toggle("select-arrow-active");
    });
}
function closeAllSelect(elmnt) {
  var x, y, i, arrNo = [];
  x = document.getElementsByClassName("select-items");
  y = document.getElementsByClassName("select-selected");
  for (i = 0; i < y.length; i++) {
    if (elmnt == y[i]) {
      arrNo.push(i)
    } else {
      y[i].classList.remove("select-arrow-active");
    }
  }
  for (i = 0; i < x.length; i++) {
    if (arrNo.indexOf(i)) {
      x[i].classList.add("select-hide");
    }
  }
}
document.addEventListener("click", closeAllSelect);
</script>
</body>
</html>      