<?php if($_SESSION['staff']==""||$_SESSION['dept']==""||$_SESSION['staffname']==""){
    echo '<script> window.location="'.SUPPORTURL.'/logout.php";</script>';
}
$loginstaff = selectQuery(SUPPORTSTAFF." as s LEFT JOIN ".SUPPORTDEPT." as d on s.department=d.dept_id LEFT JOIN ".SUPPORTSTAFFGROUP." as g on s.emp_group=g.group_id","*","s.emp_id=".$_SESSION['staff']);
$menusupportsetting = selectQuery(SUPPORTEMAIL,"*");
$staffdept = $loginstaff[0]['department'];
$staffgroup = $loginstaff[0]['emp_group'];
$accesstodept = $loginstaff[0]['access_to_dept'];
$accesstoadminpanel = $loginstaff[0]['admin_panel_access'];
/* $accesstodept=CLIENTDEPTID;*/
if($loginstaff[0]['group_name']!='Admins'&&$loginstaff[0]['group_name']!='Client'){
    if($menusupportsetting[0]['department_issolation']=='0'){
        if($accesstodept!=""){ $wheredept=" and dept IN (".$accesstodept.")"; }
        else{$wheredept=" and dept IN ('')"; }
    }
    else{$wheredept=" and entry_by=".$_SESSION['staff']; }
}
else{
    if($loginstaff[0]['group_name']=='Client'){ $wheredept=" and entry_by=".$_SESSION['staff']; }
    else{ $wheredept=""; }
}
$getdept = selectQuery(SUPPORTDEPT,"*","dept_id=".$loginstaff[0]['department']);
$deptstaff = selectQuery(SUPPORTSTAFF,"*","department=".$loginstaff[0]['department']." and isActive='1' and isDel='0'");
$allstaff = selectQuery(SUPPORTSTAFF,"*","isActive='1' and isDel='0' order by emp_name ASC");
$attachmentmax = $menusupportsetting[0]['attachment_size'];
$allowedimgtypes = $menusupportsetting[0]['img_types'];
$allowedapptypes = $menusupportsetting[0]['application_types'];
$menuallowimgarr1 = explode(",",$allowedimgtypes);
if($staffdept=="0"||$staffgroup=="0"){
    $error="Dear ".$loginstaff[0]['username'].",<br> You dont have Staff Group OR Department Access.<br> For more details, please contact system administrator";
}
else{
    if($_SESSION['acc_type']=="Admin" || $_SESSION['acc_type']=="Staff"){$openwhere="isOpen='1' and isClosed='0' and isDel='0' ".$wheredept;}
    else{ $openwhere="isClosed='0' and isDel='0' ".$wheredept;}
    $getopen = selectQuery(CONTACT,"*",$openwhere);
    $getans = selectQuery(CONTACT,"*","isAnswered='1' and isDel='0'".$wheredept);
    $answeredwhere = "isAnswered='1' and isDel='0' ".$wheredept;
    $getmytkt = selectQuery(CONTACT,"*","assign_to='".$_SESSION['staff']."'  and isDel='0' and isClosed='0' ".$wheredept);  
    $mytktwhere = "assign_to='".$_SESSION['staff']."' and isDel='0' and isClosed='0' ".$wheredept;
    $getclosed = selectQuery(CONTACT,"*","isClosed='1' and isDel='0'".$wheredept);
    $closewhere = "isClosed='1' and isDel='0' ".$wheredept;
    $getoverdue = selectQuery(CONTACT,"*","isOverdue='1' and isDel='0'".$wheredept);
    $overduewhere = "isOverdue='1' and isDel='0' ".$wheredept;
} ?>

<header class="main-head">
    <nav class="navbar">
        <div id="hmenu" class="menubtn"><i class="fa fa-bars"></i></div> 
        <div class="logo">
            <a href="<?php echo SITEURL ?>" target="_blank"><img src="<?php echo SITEURL; ?>/img/projectimage/logo.png" alt="Logo" class="img-fluid logo"/></a>
        </div>
        <ul class="nav justify-content-end top-head-right">
            <li class="d-none d-sm-block"><?php if($accesstoadminpanel=="1"){ ?> <a class="nav-link pr-1" href="<?php echo ADMINURL; ?>home.php">Admin Panel</a><? } ?></li>
            <li class="nav-item dropdown">
                <a href="#" class="dropdown-toggle nav-link pr-1" id="menu1" data-toggle="dropdown"><i class="fa fa-user mr-1" aria-hidden="true"></i><span><?php echo $loginstaff[0]['username']; ?></span> <span class="caret"></span>
                </a>
                <div class="dropdown-menu">
                    <?php if($accesstoadminpanel!="1"){ ?>
                    <a class="dropdown-item logout" href="<?php echo SITEURL ;?>/support/settings.php"><i class="fa fa-lock" aria-hidden="true"></i> Settings</a>
                    <?php } ?>
                    <a class="dropdown-item logout" href="<?php echo SITEURL ;?>/support/logout.php"><i class="fa fa-lock" aria-hidden="true"></i> Logout</a>
                </div>
            </li>
        </ul>
    </nav>
</header>