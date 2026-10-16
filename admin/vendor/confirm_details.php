<!DOCTYPE HTML>
<?php include("../../includes/configuration.php");
$seller = base64_decode($_REQUEST['vendor']);
$plan = base64_decode($_REQUEST['plan']);
$sellerdata = selectQuery(VENDOR,"*" ,"dealer_id=".$seller);
$plandata = selectQuery(VENDORPLANSELECTED,"*","sel_id=".$plan);
$planduration = selectQuery(VENDORPLAN,"*","plan_name= '".$plandata[0]['plan']."'"); ?>
<html lang="en">
<head>
    <title><?php echo SITENAME; ?> : Vendor Profile</title>
    <?php include('../commoncss.php') ?>
</head>
<body>
<div class="page-body-wrapper">
    <? include '../header.php'; ?>
    <? include '../sidebar.php'; ?>
    <div class="main-panel">
        <div class="dashbody"></div>
        <?php include '../footer.php' ?>
    </div>
</div>
</body>
</html>