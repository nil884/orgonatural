<?php include("includes/configuration.php"); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Forget Password : <?php echo SITE_TITLE; ?></title>
    <meta name="description" content="<?php echo METADESCRIPTION; ?>">
    <meta name="keywords" content="<?php echo METAKEYWORDS; ?>">
    <!--Open Graph / Facebook-->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>">
    <meta property="og:title" content="Forget Password : <?php echo SITE_TITLE; ?>">
    <meta property="og:description" content="<?php echo METADESCRIPTION; ?>">
    <meta property="og:image" content="<?=(OGIMAGE!=""?SITEURL."/img/ogimage/".OGIMAGE:SITEURL."/img/projectimage/".(LOGO != ""?LOGO:"default_logo.png")); ?>"> 
    <!--Twitter-->
    <meta property="twitter:card" content="<?=(OGIMAGE!=""?SITEURL."/img/ogimage/".OGIMAGE:SITEURL."/img/projectimage/".(LOGO != ""?LOGO:"default_logo.png")); ?>">
    <meta property="twitter:url" content="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>">
    <meta property="twitter:title" content="Forget Password : <?php echo SITE_TITLE; ?>">
    <meta property="twitter:description" content="<?php echo METADESCRIPTION; ?>">
    <meta property="twitter:image" content="<?=(OGIMAGE!=""?SITEURL."/img/ogimage/".OGIMAGE:SITEURL."/img/projectimage/".(LOGO != ""?LOGO:"default_logo.png")); ?>">
    <link rel="canonical" href="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" >
    <?php include "commoncss.php" ?>
</head>
<body class="user-log-back">
<div class="main-wrap">     
    <?php include "menu.php" ?>
    <div class="container"> 
        <div class="row">
            <div class="col-md-10 offset-md-1 memb-login-body">
                <div class="row justify-content-center">
                    <div class="col-md-5">
                        <div class="card border-0 cc-shadow-2 new-user-log">
                            <h5 class="mb-3">Reset Password</h5>
                            <p class="text-muted">Provide us the email id of your account and we will send you an email with instructions to reset your password.</p>
                            <form action="/action_page.php" class="needs-validation" novalidate>
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-envelope" aria-hidden="true"></i></span>
                                        </div>
                                        <input type="email" class="form-control border-left-0" placeholder="Enter Email Id" required>
                                    </div>
                                </div>                                
                                <button type="button" class="btn btn-primary btn-block mb-3">Submit</button>
                                <div>New Member? <a href="#">Create Account</a></div>
                            </form> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include "footer.php" ?>
</body>
</html> 