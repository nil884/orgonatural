<?php if($_SESSION['seller']==""){
    echo '<script>window.location="'.VENDORURL.'index.php";</script>';
} else {
    $vendor = $_SESSION['seller'];
    $get_vendor_details = selectQuery(VENDOR,"*","dealer_id=".$vendor);
    if($get_vendor_details[0]['isComplete']=='0'||$get_vendor_details[0]['isApproved']=='0'||$get_vendor_details[0]['isActive']=='0'||$get_vendor_details[0]['plan_status']=='Expired'||(($get_vendor_details[0]['plan_status']=='Initialize'||$get_vendor_details[0]['plan_status']=='Active')&&$get_vendor_details[0]['payment_status']!='Received')){
        if(strpos( $_SERVER['REQUEST_URI'] , 'home') !== false ||strpos($_SERVER['REQUEST_URI'] , 'planpayment') !== false||strpos($_SERVER['REQUEST_URI'] , 'confirm_details') !== false||strpos( $_SERVER['REQUEST_URI'] , 'failure.php') !== false){
        }
        else{ echo '<script>window.location="'.VENDORURL.'home.php";</script>'; }
    }
} ?>   
<header>
    <nav class="navbar top-nav">
        <div id="hmenu" class="menubtn"><i class="fa fa-bars"></i></div> 
        <div class="logo"><a href="<?=SITEURL ?>" target="_blank"><img src="<?=SITEURL; ?>/img/projectimage/logo.png" alt="Logo" class="img-fluid logo"/></a></div>
        <ul class="nav member-log-nav">
            <li class="nav-link"><i class="fa fa-clock-o mr-1" aria-hidden="true"></i> Disbursement Cycle Days <?php echo $get_vendor_details[0]['disbursement_cycle_days'] ?></li>
            <li class="nav-item dropdown">
                <a class="nav-link text-dark dropdown-toggle pr-0" href="#" id="navbardrop" data-toggle="dropdown"><i class="fa fa-user mr-1"></i> <?=ucfirst($get_vendor_details[0]['nickname'])?></a>
                <div class="dropdown-menu"><a class="dropdown-item" href="<?=VENDORURL ?>logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a></div>
            </li>
        </ul>
    </nav>
</header>