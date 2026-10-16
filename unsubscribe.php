<?php 
include("includes/configuration.php");
$sub_id = $_REQUEST['sub_id'];
$decodeid = base64_decode($sub_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>UnSubscribe : <?=SITE_TITLE; ?></title>
    <meta name="description" content="<?=METADESCRIPTION; ?>">
    <meta name="keyword" content="<?=METAKEYWORDS; ?>">
    <link rel="canonical" href="<?="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" >
    <?php include "commoncss.php" ?>
</head>
<body class="user-log-back">
<div class="main-wrap">
    <?php include "menu.php" ?>
    <div class="container"> 
        <div class="content"> 
            <div class="row">
                <div class="col-sm-10 offset-sm-1 col-md-6 offset-md-3 col-xl-4 offset-xl-4">
                    <div class="card border-0 cc-shadow-2 p-4 unsuscribe mt-2">
                        <h5>Unsubscribe to our droolworthy Newsletter</h5> 
                        <div class="customer-login">
                            <p class="lead text-muted mb-4">Do you really want to unsubscribe?</p>
                            <div class="msg"></div>
                            <form class="dialog-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="subconfirm" method="POST">
                                <input type="hidden" id="sub_id1" value="<?php echo $decodeid; ?>"/>
                                <input type="button" value="Ok" class="btn btn-primary" id="ok" name="ok"  onclick="confim()">
                                <input type="button" value="Cancel" class="btn btn-danger" id="cancel">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include "footer.php"; ?>
<script>
$("#cancel").click(function(){
    var x = "<?php echo SITEURL; ?>";
    window.location.assign(x);
});
function confim(){
    sub_id1 = $("#sub_id1").val();
    var info = {sub_id1: sub_id1, action:"unsubscribe"}
    $.ajax({
        type : "POST",
        url : "<?=SITEURL; ?>/ajax/common_ajax.php",
        data : info,
        success : function(response) {
            response = $.trim(response);
            $("#ok").prop('disabled',false);
            if(response == 1){
                $(".msg").fadeIn().html("Your are successfully unsubscribed from our newsletter").addClass("alert alert-success").delay(2000).fadeOut();
                setTimeout("window.location.href='index.php' ",7000);
            }
            else if(response == 1){
              $(".msg").fadeIn().html("Some Problem Occurs").addClass("alert alert-danger").addClass("alert alert-success").delay(2000).fadeOut();
            }
        }
    });
}  
</script> 
</body>
</html>