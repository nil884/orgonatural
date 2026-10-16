<?php include("includes/configuration.php"); $getreq_details = selectQuery(CONTACT,"Name","contact_request_id = '".base64_decode($_REQUEST['reqid'])."' "); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>SMS Success : <?php echo SITE_TITLE; ?></title>
    <meta name="description" content="<?php echo METADESCRIPTION; ?>">
    <meta name="keywords" content="<?php echo METAKEYWORDS; ?>">
    <?php include "commoncss.php" ?>
    <!--Open Graph / Facebook-->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>">
    <meta property="og:title" content="Contact Thank You : <?php echo SITE_TITLE; ?>">
    <meta property="og:description" content="<?php echo METADESCRIPTION; ?>">
    <meta property="og:image" content="<?=(OGIMAGE!=""?SITEURL."/img/ogimage/".OGIMAGE:SITEURL."/img/projectimage/".(LOGO != ""?LOGO:"default_logo.png")); ?>"> 
    <!--Twitter-->
    <meta property="twitter:card" content="<?=(OGIMAGE!=""?SITEURL."/img/ogimage/".OGIMAGE:SITEURL."/img/projectimage/".(LOGO != ""?LOGO:"default_logo.png")); ?>">
    <meta property="twitter:url" content="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>">
    <meta property="twitter:title" content="Contact Thank You : <?php echo SITE_TITLE; ?>">
    <meta property="twitter:description" content="<?php echo METADESCRIPTION; ?>">
    <meta property="twitter:image" content="<?=(OGIMAGE!=""?SITEURL."/img/ogimage/".OGIMAGE:SITEURL."/img/projectimage/".(LOGO != ""?LOGO:"default_logo.png")); ?>">
    <link rel="canonical" href="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" >
</head>
<body class="bg-light">
<div class="main-wrap">     
    <?php include "menu.php" ?>
    <div class="content py-5">
        <div class="container mt-2"> 
            <div class="row">
                <div class="col-md-5 m-auto text-center">
                    <img src="<?=SITEURL; ?>/img/projectimage/enq-thank-you.png" alt="enq-thank-you" width="250" class="mb-4">
                    <h5 class="text-primary">Thank You, <?php echo $getreq_details[0]['Name']; ?></h5>
                    <h4 class="cc-fw-6">Please Check Your Email</h4>
                    <p class="lead mb-0 text-muted">We appreciate your showing interest in our services. One of our customer support executive will contact you shortly.</p>
                    <div class="pgredirectcount rounded-circle text-center cc-shadow-2 bg-white mt-3 cc-fw-6">10</div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include "footer.php" ?>
<script>
var counter = 10;
var interval = setInterval(function(){
    counter--;
    $(".pgredirectcount").html(counter);
    if (counter == 0) {
        clearInterval(interval);
        window.location.href = "<?php echo SITEURL; ?>";
    }
}, 1000);
</script>
</body>
</html>           