<?php if($_SESSION['admin']==""){ echo '<script>window.location="'.ADMINURL.'index.php";</script>'; } else{
    $get_admin_details = selectQuery(ADMIN,"*","u_id=".$_SESSION['admin']);
    $allocated_menu = explode(",",$get_admin_details[0]['allocatemenu']);
} 
$files="classes/surunapi.php";
$currentfile = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$repath = $_SERVER['HTTP_HOST']."/".$files;
require_once getRelativePath($currentfile, $repath);
$surun = new surun(); 
//$featurelog = $surun->getNewFeature(SITEURL); 
//$changelog = $surun->getNewChange(SITEURL);
$featurelog = ''; 
$changelog = '';
$filesp = "classes/product.php";
$repathp = $_SERVER['HTTP_HOST']."/".$filesp;
require_once getRelativePath($currentfile, $repathp);
$headprod = new Product(); 
$allprodcount = $headprod->getSkuCount(); ?>
<header><nav class="navbar bakendnav top-nav"><div id="hmenu" class="menubtn"><i class="fa fa-bars"></i></div><div class="logo"><a href="<?php echo SITEURL ?>" target="_blank"><img src="<?php echo SITEURL; ?>/img/projectimage/logo.png" alt="Logo" class="img-fluid logo"/></a></div><ul class="nav justify-content-md-end d-flex align-items-center member-log-nav"><li class="nav-item"><!--<a href="<?=ADMINURL; ?>features.php" class="nav-link pr-1 pl-0"><i class="fa fa-gift"></i> <span class="text-primary">Features <?=($featurelog!=""?"<span class='badge badge-danger'>".$featurelog."</span>" :"") ?></span></a></li><li class="nav-item"><a href="<?=ADMINURL; ?>changelog.php" class="nav-link pr-1"><i class="fa fa-bell"></i> <span class="text-primary">Change Log <?=($changelog!=""?"<span class='badge badge-danger'>".$changelog."</span>" :"") ?></span></a></li>--><li class="nav-item dropdown"><a class="nav-link dropdown-toggle pr-0 text-dark" href="#" id="navbardrop" data-toggle="dropdown"><i class="fa fa-user mr-1" aria-hidden="true"></i><span class="d-none d-md-inline-block"><?php echo ucfirst($get_admin_details[0]['username']) ?></span><span class="usershortpic bg-primary text-white d-inline-block d-md-none text-center cc-font-weight-6 text-uppercase"><?php echo substr($get_admin_details[0]['username'],0,1); ?></span></a><div class="dropdown-menu"><a class="dropdown-item py-2"><i class="fa fa-cube mr-1" aria-hidden="true"></i> Total SKU/Products - <span class="badge badge-danger"><?=$allprodcount; ?></span></a><?php if($get_admin_details[0]['utype'] == "Admin"){ ?><a class="dropdown-item py-2" href="<?php echo ADMINURL ?>lastlogin.php"><i class="fa fa-key mr-1"></i> Last 30 Login Details</a><?php if(SMS_SYSTEM=="ON"){ ?> <a class="dropdown-item" href="<?php echo ADMINURL ?>sms_report"> <? $ch = curl_init(); curl_setopt($ch, CURLOPT_URL, "http://api-alerts.solutionsinfini.com/v3/?method=account.credits&api_key=".WORKINGKEY."&format=PHP"); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); $output = curl_exec($ch); curl_close($ch); $arrout = (unserialize($output)); $credit = floor($arrout['data']['credits']); echo "<i class='fa fa-comments mr-1'></i> SMS Balance : ".$credit; ?></a><? } ?><a class="dropdown-item py-2" href="<?php echo ADMINURL ?>presetting.php"><i class="fa fa-cog mr-1"></i> Setting & Logs</a><?php } ?><a class="dropdown-item py-2" href="<?php echo ADMINURL ?>logout.php"><i class="fa fa-sign-out mr-1" aria-hidden="true"></i> Logout</a></div></li></ul></nav></header>