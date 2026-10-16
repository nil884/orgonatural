<?php include("../includes/configuration.php");
$actual_link = ($_SERVER['HTTPS'] == 'on'?"https://":"http://").$_SERVER[HTTP_HOST]."".$_SERVER[REQUEST_URI]; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>About Us : <?php echo SITE_TITLE; ?></title>
    <meta name="description" content="<?php echo METADESCRIPTION; ?>">
    <meta name="keywords" content="<?php echo METAKEYWORDS; ?>">
    <!--Open Graph / Facebook-->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>">
    <meta property="og:title" content="About Us : <?php echo SITE_TITLE; ?>">
    <meta property="og:description" content="<?php echo METADESCRIPTION; ?>">
    <meta property="og:image" content="<?=(OGIMAGE!=""?SITEURL."/img/ogimage/".OGIMAGE:SITEURL."/img/projectimage/".(LOGO != ""?LOGO:"default_logo.png")); ?>"> 
    <!--Twitter-->
    <meta property="twitter:card" content="<?=(OGIMAGE!=""?SITEURL."/img/ogimage/".OGIMAGE:SITEURL."/img/projectimage/".(LOGO != ""?LOGO:"default_logo.png")); ?>">
    <meta property="twitter:url" content="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>">
    <meta property="twitter:title" content="About Us : <?php echo SITE_TITLE; ?>">
    <meta property="twitter:description" content="<?php echo METADESCRIPTION; ?>">
    <meta property="twitter:image" content="<?=(OGIMAGE!=""?SITEURL."/img/ogimage/".OGIMAGE:SITEURL."/img/projectimage/".(LOGO != ""?LOGO:"default_logo.png")); ?>">
    <link rel="canonical" href="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" >
    <?php include "../commoncss.php" ?>
</head>
<body>
<div class="main-wrap">     
    <?php include "../menu.php" ?>
    <div class="container pt-3"><div class="row"><div class="col-12"><ul class="breadcrumb p-0 mb-0"><li class="breadcrumb-item"><a href="<?=SITEURL;?>" hreflang="en">Home</a></li><li class="breadcrumb-item active">About Us</li></ul></div></div></div>
    <div class="content">
        <div class="container"> 
            <div class="row">
                <div class="col-12 static-page-section">
                    <h1 class="stat-pg-main-title mb-3 h4">About Us</h1>
                    <? $getdetails=selectQuery(STATIC_PAGE,"about_us","id= 1");
                    echo $getdetails[0]['about_us']; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include "../footer.php" ?> 
</body>
</html>