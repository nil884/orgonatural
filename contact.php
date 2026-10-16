<?php include("includes/configuration.php");
$actual_link = ($_SERVER['HTTPS'] == 'on'?"https://":"http://").$_SERVER[HTTP_HOST]."".$_SERVER[REQUEST_URI]; ?>
<!doctype html>
<html itemscope itemtype="http://schema.org/WebPage" lang="en">
<head>
    <title>Contact Us : <?php echo SITE_TITLE; ?></title>
    <meta name="keywords" content="<?php echo METAKEYWORDS; ?>">
    <meta name="description" content ="<?php echo METADESCRIPTION; ?>">  
    <link rel="canonical" href="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>">  
    <!--Open Graph / Facebook-->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo $actual_link; ?>">
    <meta property="og:title" content="Contact Us : <?php echo SITE_TITLE; ?>">
    <meta property="og:description" content="<?php echo METADESCRIPTION; ?>">
    <meta property="og:image" content="<?=(OGIMAGE!=""?SITEURL."/img/ogimage/".OGIMAGE:SITEURL."/img/projectimage/".(LOGO != ""?LOGO:"default_logo.png")); ?>">
    <!--Twitter-->
    <meta property="twitter:card" content="<?=(OGIMAGE!=""?SITEURL."/img/ogimage/".OGIMAGE:SITEURL."/img/projectimage/".(LOGO != ""?LOGO:"default_logo.png")); ?>">
    <meta property="twitter:url" content="<?php echo $actual_link; ?>">
    <meta property="twitter:title" content="Contact Us : <?php echo SITE_TITLE; ?>">
    <meta property="twitter:description" content="<?php echo METADESCRIPTION; ?>">
    <meta property="twitter:image" content="<?=(OGIMAGE!=""?SITEURL."/img/ogimage/".OGIMAGE:SITEURL."/img/projectimage/".(LOGO != ""?LOGO:"default_logo.png")); ?>">
    <?php include 'commoncss.php' ?>
    <link rel="stylesheet" href="css/captchacss.css" />    
</head>
<body>
<div class="main-wrap">
    <?php include 'menu.php' ?>
    <div class="pt-3"><div class="container"><div class="row"><div class="col-12"><ul class="breadcrumb p-0 mb-0"><li class="breadcrumb-item"><a href="<?=SITEURL;?>" hreflang="en">Home</a></li><li class="breadcrumb-item active">Contact</li></ul> </div></div></div></div>
    <div class="content pt-4">
        <div class="container">
            <div class="row">
                <div class="contactInfo col-12">
                    <h1 class="mb-3 h4">Need Help?</h1>
                    <div class="row">
                        <div class="col-sm-12 col-md-6 col-lg-5 col-xl-4" itemscope itemtype="http://schema.org/Organization">
                            <div class="card p-4 mb-4">                                
                                <?php if(CONTACTUSNO!=""){ ?>
                                <div class="conn-col mb-3 position-relative">
                                    <i class="fa fa-phone text-primary position-absolute" aria-hidden="true"></i>
                                    <h2 class="cont-head mb-1 h6">Lets Talk</h2>
                                    <div class="addressDeails text-muted"><a href="tel:<?=CONTACTUSNO; ?>" target="_blank"><?php echo CONTACTUSNO; ?></a></div>
                                </div>
                                <? }
                                if(EMAIL_FOOTER!=""){ ?>
                                <div class="conn-col mb-3 position-relative">
                                    <i class="fa fa-envelope text-primary position-absolute" aria-hidden="true"></i>
                                    <h2 class="cont-head mb-1 h6">General Support</h2>
                                    <div class="addressDeails text-muted"><a href="mailto:<?=EMAIL_FOOTER; ?>" target="_blank"><? echo EMAIL_FOOTER;?></a></div>
                                </div>
                                <? }
                                if(ADDRESSDETAIL!=""){ ?>
                                <div class="conn-col mb-1 position-relative">
                                    <i class="fa fa-map-marker text-primary position-absolute" aria-hidden="true"></i>
                                    <h2 class="cont-head mb-1 h6">Address</h2>
                                    <address class="addressDeails text-muted mb-0" itemprop="address"><?php echo ADDRESSDETAIL; ?>.</address>
                                </div>
                                <? } ?>
                                <hr>
                                <div class="socialLink">
                                    <div class="mb-3 h6">You can also find us here</div>
                                    <ul class="list-unstyled mb-0">
                                        <?php if(FBLINK != ""){ ?><li><a hreflang="en" href="<?=FBLINK; ?>" target="_blank" class="d-inline-block soc-view-link soc-fb-link cc-cursor-pointer"><i class="fa fa-facebook"></i></a></li><?php } ?>
                                        <?php if(LINKEDIN != ""){?><li><a hreflang="en" href="<?=LINKEDIN; ?>" target="_blank" class="d-inline-block soc-view-link soc-link-link cc-cursor-pointer"><i class="fa fa-linkedin"></i></a></li><?php } ?>
                                        <?php if(PINTEREST != ""){ ?><li><a hreflang="en" href="<?=PINTEREST; ?>" target="_blank" class="d-inline-block soc-view-link soc-pinter-link cc-cursor-pointer"><i class="fa fa-pinterest-p"></i></a></li><?php } ?>
                                        <?php if(INSTAGRAMLINK != ""){ ?><li><a hreflang="en" href="<?=INSTAGRAMLINK; ?>" target="_blank" class="d-inline-block soc-view-link soc-insta-link cc-cursor-pointer"><i class="fa fa-instagram"></i></a></li><?php } ?>
                                        <?php if(YOUTUBELINK != ""){ ?><li><a  hreflang="en" href="<?=YOUTUBELINK; ?>" target="_blank" class="d-inline-block soc-view-link soc-youtube-link cc-cursor-pointer"><i class="fa fa-youtube-play"></i></a></li><?php } ?>
                                        <?php if(TWITTERLINK != ""){ ?><li><a hreflang="en" href="<?=TWITTERLINK; ?>" target="_blank" class="d-inline-block soc-view-link soc-twit-link cc-cursor-pointer"><i class="fa fa-twitter"></i></a></li><?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-7 col-xl-8 contformbody">
                            <div class="msgs"></div>
                            <div class="text-center"><?php if(isset($msg)){?><?php echo $msg;?><?php } ?></div>
                            <form class="contactform" name="contactform" method="post" id="contact_form">
                                <div class="row">
                                    <div class="col-sm-12 col-md-12">
                                        <div class="form-group mb-2">
                                            <label class="cc-mandatary-field">Full Name</label>
                                            <input type="text" maxlength="50" minlength="3" class="form-control form-control-lg text-capitalize" placeholder="Your Name" id="c_name1" required />
                                            <div class="invalid-tooltip">Please Enter Name</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 col-md-12 col-lg-6">
                                        <div class="form-group mb-2">
                                            <label class="cc-mandatary-field">Email ID</label>
                                            <input type="text" class="form-control form-control-lg" maxlength="70" minlength="7" placeholder="Email Address" id="c_email" onblur="mailchk('c_email')" required/>
                                            <div class="invalid-tooltip">Please Enter Email Address</div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-12 col-lg-6">
                                        <div class="form-group mb-2">
                                            <label class="cc-mandatary-field">Contact No</label>
                                            <input type="text" class="form-control form-control-lg" name="tele" id="c_tele" onkeyup="mobnumbercheck('c_tele')" placeholder="Mobile Number" maxlength="10" required />
                                            <div class="invalid-tooltip">Please Enter Valid Contact No</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <label class="cc-mandatary-field">Comment</label>
                                            <textarea name="c_cmnt" placeholder="Your Comment" class="form-control contact-comment form-control-lg" id="c_cmnt" cols="7" rows="3" required></textarea>
                                            <div class="invalid-tooltip">Please Enter Comment</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-4 col-md-4 col-lg-4 col-xl-3">
                                        <div class="form-group">
                                            <img src="<? echo SITEURL; ?>/captcha.php?rand=<?php echo rand();?>" id='captchaimg' alt="captcha" class="img-fluid" width="120" height="40">
                                        </div>
                                    </div>
                                    <div class="col-8 col-sm-5 col-md-5 col-lg-4">
                                        <div class="form-group">
                                            <input id="captcha_code" name="captcha_code" placeholder="Enter Code"  type="text" autocomplete="off" class="form-control form-control-lg" required>
                                            <div class="invalid-tooltip">Please Enter Captcha code</div>
                                        </div>
                                    </div>
                                    <div class="col-4 col-sm-3 col-md-2 col-lg-2">
                                        <a href='javascript: refreshCaptcha();' class="btn btn-secondary btn-lg"><i class="fa fa-refresh size" aria-hidden="true"></i></a>
                                    </div>
                                </div>
                                <button type="button" name="submit" value="Submit" class="btn btn-primary send" id="submit" onclick="validate();">Send Message</button>
                                <button type="reset" name="Reset" value="Reset" class="btn btn-secondary">Reset</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="map"><?php if(MAP != "") { ?><?php echo MAP; ?><? } ?></div>
</div>
<?php include 'footer.php' ?>
<script>
function refreshCaptcha(){
    var img = document.images['captchaimg'];
    img.src = img.src.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;
}
function validate(){
    var form = $("#contact_form");
    if (form[0].checkValidity() === false) {event.preventDefault(); event.stopPropagation();}
    form.addClass('was-validated');
    var name = $("#c_name1").val(); var email = $("#c_email").val(); var tele = $("#c_tele").val(); var cmnt = $("#c_cmnt").val(); var captcha_code = document.contactform.captcha_code.value;
    if ((name != "") && (email  != "") && (tele  != "") && (cmnt  != "") && (captcha_code != "")){ $("#submit").val('Sending..').html('Submitting...'); $("#submit").attr('disabled', 'disabled'); $.ajax({ url: '<?php echo SITEURL; ?>/ajax/contact_ajax.php', type: 'POST', data: { name: name, email:email, mobile:tele, message:cmnt, captcha_code:captcha_code, action:"contact_request_new", },
    success: function(response){ $("#submit").val('Send'); $("#submit").html("Submit");
    if(response){ if(response==2){$('.msgs').fadeIn().addClass("alert alert-danger").html("Please Try Again Later").delay(2000).fadeOut();} if(response==3){$('.msgs').fadeIn().addClass("alert alert-danger").html("Captcha Not Match").delay(3000).fadeOut(); } else {form.removeClass('was-validated');$('.msgs').fadeIn().addClass("alert alert-success").html("Thank you for contacting us, our customer happiness manager will contact you soon.").delay(3000).fadeOut();$("#contact_form")[0].reset();
    setInterval(function(){ location.href = "<?php echo SITEURL; ?>/contactsuccess?reqid="+response; }, 3000);
    } } } }); } }
    $(document).ready(function(){$('[data-toggle="tooltip"]').tooltip(); });  
</script>
</body>
</html>