<?php include("../includes/configuration.php");?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>International Shipping : <?php echo SITE_TITLE; ?></title>
    <meta name="description" content="<?php echo METADESCRIPTION; ?>">
    <meta name="keywords" content="<?php echo METAKEYWORDS; ?>">
      <link rel="canonical" href="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" >
    <?php include "../commoncss.php" ?>
</head>
<body>
<div class="main-wrap">
    <?php include "../menu.php" ?>
    <div class="container pt-3"><div class="row"><div class="col-12"><ul class="breadcrumb p-0 mb-0"><li class="breadcrumb-item"><a href="<?=SITEURL;?>" hreflang="en">Home</a></li><li class="breadcrumb-item active">International Shipping</li></ul></div></div></div>
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-12 static-page-section">
                    <h1 class="stat-pg-main-title mb-3 h4">International Shipping</h1>
                    <? $getdetails=selectQuery(STATIC_PAGE,"international_ship","id= 1");
                    echo $getdetails[0]['international_ship']; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include "../footer.php" ?>
</body>
</html>