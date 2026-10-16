<?php include("includes/configuration.php");
include("classes/product.php");
$prod = new Product();
$segment1 = trim($_GET['prodid'],"/");
$prodid = base64_decode($segment1);
 
$getProductDetails = $prod->getProductFullDetails($prodid);
$images = $getProductDetails['image'];
$getparent_id =  $prod->getParentProd($getProductDetails['name'],$getProductDetails['vendorId']);
$where = $getparent_id;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Review : <?php echo SITE_TITLE; ?></title>
    <meta name="description" content="<?php echo METADESCRIPTION; ?>">
    <meta name="keywords" content="<?php echo METAKEYWORDS; ?>">
    <!--Open Graph / Facebook-->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>">
    <meta property="og:title" content="Review : <?php echo SITE_TITLE; ?>">
    <meta property="og:description" content="<?php echo METADESCRIPTION; ?>">
    <meta property="og:image" content="<?=(OGIMAGE!=""?SITEURL."/img/ogimage/".OGIMAGE:SITEURL."/img/projectimage/".(LOGO != ""?LOGO:"default_logo.png")); ?>"> 
    <!--Twitter-->
    <meta property="twitter:card" content="<?=(OGIMAGE!=""?SITEURL."/img/ogimage/".OGIMAGE:SITEURL."/img/projectimage/".(LOGO != ""?LOGO:"default_logo.png")); ?>">
    <meta property="twitter:url" content="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>">
    <meta property="twitter:title" content="Review : <?php echo SITE_TITLE; ?>">
    <meta property="twitter:description" content="<?php echo METADESCRIPTION; ?>">
    <meta property="twitter:image" content="<?=(OGIMAGE!=""?SITEURL."/img/ogimage/".OGIMAGE:SITEURL."/img/projectimage/".(LOGO != ""?LOGO:"default_logo.png")); ?>">
    <?php include "commoncss.php" ?>
</head>
<body>
<div class="main-wrap">     
    <?php include "menu.php" ?>
    <div class="py-4">
        <div class="container">
            <div class="row">
                <div class="col-sm-4 col-md-3 col-lg-2 mb-3">
                <div class="load-rev-thumb text-center mb-3">
                    <img src='<?=SITEURL; ?>/img/productimg/<?=$images[0]['img_name'];?>' data-gc-caption="Caption text" alt="image Thumbnail">
                </div>
                <h6 class="cc-fw-5"><?php  echo $getProductDetails['name']; ?></h6>
                </div>
                <div class="col-sm-8 col-md-9 col-lg-10 border-left">
                    <h5 class="mb-2">Review of <span class="cc-primary-color"><?php echo $getProductDetails['name']; ?></span></h5>
                    <div class="products-rev-load"></div>
                </div>        
            </div>
        </div>
    </div>
</div>
<?php include "footer.php" ?> 
<script>
var siteurl = "<?=SITEURL; ?>"
var where = "<?=$where ?>";
displayprod(siteurl,where,1)
function displayprod(siteurl,where, pageNum) {
    $.ajax({
        type: "POST",
        url: siteurl+"/ajax/review_ajax.php",
        data: {where:where,pagenum:pageNum},
        cache: false,beforeSend: function() {},
        success: function(html) { $(".products-rev-load").html(html);  totalitem=$(".total-request").html();    $(".itmtocount").html(totalitem);  
        }
    });
}
</script>   
</body>
</html>