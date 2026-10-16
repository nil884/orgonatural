<?php include("includes/configuration.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>404 : <?=SITE_TITLE ?></title>
    <meta name="description" content="<?php echo METADESCRIPTION; ?>">
    <meta name="keywords" content="<?php echo METAKEYWORDS; ?>">
    <link rel="canonical" href="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" >
    <?php include "commoncss.php"; ?>
</head>
<body>
<div class="main-wrap">
    <?php include "menu.php" ?>
    <div class="container py-5 mt-0 mt-md-5">
        <div class="row">
            <div class="col-md-10 offset-md-1 col-lg-8 offset-lg-2 page-not-found">
                <div class="row d-flex align-items-center">
                    <div class="col-md-4 text-center">
                        <img src="<?=SITEURL; ?>/img/projectimage/page-not-found.png" alt="page-not-found" class="img-fluid mb-2" width="230"/>
                    </div>
                    <div class="col-md-8 pg-not-fnd-text pl-md-5">
                        <h4>Page Not Found</h4>
                        <p class="lead text-muted">The link you clicked may be broken or the page may have been removed or renamed.</p>
                        <a href="<?=SITEURL ?>" class="btn btn-primary btn-lg px-3">Go Back To Home Page</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include "footer.php"; ?>
</body>
</html>