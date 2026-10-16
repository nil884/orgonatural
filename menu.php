<div class="progress"><div class="prog-overlap bg-primame-highlight"></div><div class="indeterminate bg-primame-highlight"></div></div>
<?php $loguser="";
if( isset($_SESSION['reguser'])){
    $loguser = $_SESSION['reguser'];
    $getbuyer_details=selectQuery(BUYER,"*","u_id=".$loguser);
    $logUsername = $getbuyer_details[0]['u_fname'];
    $logUserMail = $getbuyer_details[0]['u_email'];
}
if( isset($_SESSION['wishitems'])){ $item_array_id= array_column($_SESSION['wishitems'] ,"prod_id" );} else{ $_SESSION['wishitems']= array();  }
if(isset($_SESSION['shopping_cart'])){ $cart_item_array_id= array_column($_SESSION['shopping_cart'] ,"prod_id" );} else { $_SESSION['shopping_cart']= array(); }
if($_SERVER['REQUEST_URI']!="/checkout"){ unset($_SESSION['userPincode']); unset($_SESSION['shippingAdrId']); }
$Notification1 = selectQuery(NOTIFICATION,"notificationico,notificationlink,notification"," isActive ='1' and show_on_all = '1' and show_on_homepage ='0' and myaccount ='0' ");
if(count($Notification1)) { ?>
<div class="notification-toggle px-5 text-center"><div class="container"><div class="row"><div class="col-md-12 marquee">
    <div class="marq-notif"><img src="<?=SITEURL; ?>/img/projectimage/<?=$Notification1[0]['notificationico']; ?>" alt="<?=$Notification1[0]['notificationico']; ?>" width="20" height="20" class="mr-1"/><a href="<?=($Notification1[0]['notificationlink']!=""?$Notification1[0]['notificationlink']:SITEURL); ?>" target="_blank" class="pt-2 pb-2 d-inline-block" hreflang="en"><?=$Notification1[0]['notification']; ?></a></div></div></div></div><span class="close-notif position-absolute d-inline-block"><i class="fa fa-times"></i></span>
</div>
<?php } ?>
<div class="head-contact position-relative">
    <div class="container">
        <div class="row">
            <div class="col-md-1 col-lg-4 col-xl-4 d-flex align-items-center"><span class=" d-none d-lg-block">Welcome To <?php echo SITENAME; ?> Online Store</span></div>
            <div class="col-md-11 col-lg-8 col-xl-8">
                <ul class="list-unstyled mb-0 float-right">
                    <?php $arr_ph = explode(",",CONTACTUSNO); if(CONTACTUSNO != ""){ ?><li class="d-none d-sm-inline-block head-mob-num"><i class='fa fa-phone mr-1 align-middle' aria-hidden='true'></i>
                        <? foreach($arr_ph as $contactNumber){
                        echo "<a href='tel:$contactNumber' target='_blank'>" . $contactNumber . "</a>";
                    } ?>
                    </li><?php } ?>
                    <?php if(EMAIL_FOOTER != ""){ ?> <li><a href="mailto:<?=EMAIL_FOOTER; ?>" target="_blank"><i class="fa fa-envelope mr-1" aria-hidden="true"></i><?=EMAIL_FOOTER; ?></a></li><?php } ?>
                    <!--<li><a href="<?=VENDORURL;?>" target="_blank" hreflang="en"><i class="fa fa-user mr-1" aria-hidden="true"></i>Vendor Login</a></li> -->
                    <li class="d-none d-sm-inline-block"><a href="<?=SITEURL;?>/contact" hreflang="en"><i class="fa fa-map-marker mr-1" aria-hidden="true"></i> Contact</a></li>
                </ul>                    
            </div>
        </div>
    </div>
</div>
<header class="sticky-top">
    <div class="inner-header">
        <div class="container">
            <div class="row">
                <div class="col-5 col-sm-4 col-md-3 col-lg-3 order-lg-1 d-flex align-items-center">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar" aria-label="navbar-toggle"><svg version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 384.97 384.97" xml:space="preserve" width="20" height="20"><g><g id="Menu"><path d="M12.03,84.212h360.909c6.641,0,12.03-5.39,12.03-12.03c0-6.641-5.39-12.03-12.03-12.03H12.03C5.39,60.152,0,65.541,0,72.182C0,78.823,5.39,84.212,12.03,84.212z"></path><path d="M372.939,180.455H12.03c-6.641,0-12.03,5.39-12.03,12.03s5.39,12.03,12.03,12.03h360.909c6.641,0,12.03-5.39,12.03-12.03S379.58,180.455,372.939,180.455z"></path><path d="M372.939,300.758H12.03c-6.641,0-12.03,5.39-12.03,12.03c0,6.641,5.39,12.03,12.03,12.03h360.909c6.641,0,12.03-5.39,12.03-12.03C384.97,306.147,379.58,300.758,372.939,300.758z"></path></g><g></g><g></g><g></g><g></g><g></g><g></g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg></button>
                    <div class="logo-flex"><h1 class="h6 mb-0" title="<?=SITE_TITLE ?>"><a href="<?=SITEURL ?>" hreflang="en"><img src="<?=SITEURL; ?>/img/projectimage/<?=(LOGO != ""?LOGO:"default_logo.png"); ?>" alt="<?=SITE_TITLE ?>" class="img-fluid logo"/></a></h1></div>
                </div>            
                <div class="col-7 col-sm-8 col-md-9 col-lg-4 order-lg-3 align-self-center user-cont log-member pl-0">
                    <ul class="list-unstyled mb-0"> 
                        <li><a href="<?php echo SITEURL ?>/wishview" hreflang="en"><svg xmlns="http://www.w3.org/2000/svg" height="27px" viewBox="0 0 24 24" width="27px"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M16.5 3c-1.74 0-3.41.81-4.5 2.09C10.91 3.81 9.24 3 7.5 3 4.42 3 2 5.42 2 8.5c0 3.78 3.4 6.86 8.55 11.54L12 21.35l1.45-1.32C18.6 15.36 22 12.28 22 8.5 22 5.42 19.58 3 16.5 3zm-4.4 15.55l-.1.1-.1-.1C7.14 14.24 4 11.39 4 8.5 4 6.5 5.5 5 7.5 5c1.54 0 3.04.99 3.57 2.36h1.87C13.46 5.99 14.96 5 16.5 5c2 0 3.5 1.5 3.5 3.5 0 2.89-3.14 5.74-7.9 10.05z"/></svg> <span class="d-none d-md-inline-block">Wishlist</span> <span class="mem-cart-count-badge head-sec-back d-inline-block text-center rounded-circle" id="wishlist-count"><?php echo count($_SESSION['wishitems']);?></span></a></li>
                        <li><a class="rounded-circle" href="<?php echo SITEURL ?>/cartview" hreflang="en"><svg xmlns="http://www.w3.org/2000/svg" height="27px" viewBox="0 0 24 24" width="27px"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M22 9h-4.79l-4.38-6.56c-.19-.28-.51-.42-.83-.42s-.64.14-.83.43L6.79 9H2c-.55 0-1 .45-1 1 0 .09.01.18.04.27l2.54 9.27c.23.84 1 1.46 1.92 1.46h13c.92 0 1.69-.62 1.93-1.46l2.54-9.27L23 10c0-.55-.45-1-1-1zM12 4.8L14.8 9H9.2L12 4.8zM18.5 19l-12.99.01L3.31 11H20.7l-2.2 8zM12 13c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg> <span class="d-none d-md-inline-block">Cart</span> <span class="mem-cart-count-badge head-sec-back d-inline-block text-center rounded-circle" id="cart-count"><?php echo count($_SESSION['shopping_cart']); ?></span></a></li>
                        <li><?php if($loguser==""){ ?><a href="<?=SITEURL; ?>/login" hreflang="en" aria-label="User-login"><svg xmlns="http://www.w3.org/2000/svg" height="27px" viewBox="0 0 24 24" width="27px"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zM7.07 18.28c.43-.9 3.05-1.78 4.93-1.78s4.51.88 4.93 1.78C15.57 19.36 13.86 20 12 20s-3.57-.64-4.93-1.72zm11.29-1.45c-1.43-1.74-4.9-2.33-6.36-2.33s-4.93.59-6.36 2.33C4.62 15.49 4 13.82 4 12c0-4.41 3.59-8 8-8s8 3.59 8 8c0 1.82-.62 3.49-1.64 4.83zM12 6c-1.94 0-3.5 1.56-3.5 3.5S10.06 13 12 13s3.5-1.56 3.5-3.5S13.94 6 12 6zm0 5c-.83 0-1.5-.67-1.5-1.5S11.17 8 12 8s1.5.67 1.5 1.5S12.83 11 12 11z"/></svg> <span class="d-none d-md-inline-block">Login</span></a><?php } ?></li>
                        <?php if($loguser!=""){ ?>
                        <li class="dropdown">  
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#" hreflang="en"><svg xmlns="http://www.w3.org/2000/svg" height="27px" viewBox="0 0 24 24" width="27px"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zM7.07 18.28c.43-.9 3.05-1.78 4.93-1.78s4.51.88 4.93 1.78C15.57 19.36 13.86 20 12 20s-3.57-.64-4.93-1.72zm11.29-1.45c-1.43-1.74-4.9-2.33-6.36-2.33s-4.93.59-6.36 2.33C4.62 15.49 4 13.82 4 12c0-4.41 3.59-8 8-8s8 3.59 8 8c0 1.82-.62 3.49-1.64 4.83zM12 6c-1.94 0-3.5 1.56-3.5 3.5S10.06 13 12 13s3.5-1.56 3.5-3.5S13.94 6 12 6zm0 5c-.83 0-1.5-.67-1.5-1.5S11.17 8 12 8s1.5.67 1.5 1.5S12.83 11 12 11z"/></svg> <span class="d-none d-sm-inline-block loger-name ml-1"><? if($loguser!=""){ echo (trim($logUsername)!=""?$logUsername:$logUserMail); } ?></span><span class="mem-cart-count-badge head-sec-back d-inline-block text-center rounded-circle d-inline-block d-sm-none"><?=($logUsername!=""?substr($logUsername,0,1):""); ?></span></a>
                            <ul class="dropdown-menu border-0 cc-shadow-2">
                                <a class="dropdown-item" href="<?=SITEURL ?>/account/" hreflang="en">My Account</a>
                                <a class="dropdown-item" href="<?php echo SITEURL; ?>/account/bankdetails" hreflang="en">Bank Details</a>
                                <a class="dropdown-item" href="<?php echo SITEURL; ?>/account/taxation" hreflang="en">TAX Details</a>
                                <a class="dropdown-item" href="<?php echo SITEURL; ?>/account/myreviews" hreflang="en">Product Review</a>
                                <a class="dropdown-item" href="<?php echo SITEURL; ?>/account/myorders" hreflang="en">Recent Order</a>
                                <a class="dropdown-item" href="<?php echo SITEURL; ?>/account/myaddresses" hreflang="en">Order Address</a>
                                <div class="dropdown-divider my-1"></div>
                                <a class="dropdown-item" href="<?=SITEURL ?>/logout" hreflang="en">Logout</a>
                            </ul>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="col-12 col-md-12 col-lg-5 order-lg-2 align-self-center">
                    <div class="search-product position-relative">
                        <form  action="<?=SITEURL; ?>/search"  id="searchform"  method="post" >
                            <div class="input-group cc-shadow-1">
                                <input type="text" class="form-control border-0" id="search_box" name="search" placeholder="Search Products" autocomplete="off" aria-label="Search">
                                <div class="input-group-append"><span class="input-group-text border-0 px-3 search1 bg-white"><i class="fa fa-search"></i></span></div>
                            </div>
                        </form> 
                        <div class="ser-result rounded-sm shadow border position-absolute bg-white w-100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<nav class="navbar navbar-expand-sm mega-menu cat-nav p-0">
    <div class="back-category p-3 d-block d-lg-none shadow cc-primary-back col-md-12 cc-cursor-pointer text-white mb-2 cc-fw-6"><i class="fa fa-arrow-left mr-2" aria-hidden="true"></i> ALL CATEGORIES</div>
    <div class="container position-relative">
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link home-link" href="<?=SITEURL;?>" hreflang="en" aria-label="Home">HOME</a></li>
            <?php $parent = selectQuery(PRODCAT,"cat_name,id,type","parent_id=0 and isActive='1' order by priority");
            for($p=0;$p<count($parent);$p++){
            $parentname = $parent[$p]['cat_name']; $parenttype=$parent[$p]['type']; $parentid=$parent[$p]['id']; ?>
            <li class="nav-item">
                <a class="dropdown-toggle" hreflang="en" href='<?=SITEURL;?>/<?=getUrl($parenttype,$parentid);?>'><?=$parentname; ?></a><span class="caret"></span>
                <ul class="mega-drop-menu p-3 shadow list-unstyled rounded-bottom">
                    <?php $prod_cat=selectQuery(PRODCAT,"cat_name,id,type","parent_id=".$parentid." and isActive='1' order by priority");
                    $menu1="";$menu2="";$menu3="";$menu4="";$menu5="";
                    for($i=0;$i<count($prod_cat);$i++){
                        $masterid = $prod_cat[$i]['id'];$mastername = $prod_cat[$i]['cat_name'];$mastertype = $prod_cat[$i]['type'];       
                        $str='<div class="catblock"><div class="menu-cat-head mb-1"><a class="cc-primary-color" href="'.SITEURL.'/'.getUrl($mastertype,$masterid).'" hreflang="en">'.$mastername.'</a></div>';
                        $que1=selectQuery(PRODCAT,"cat_name,id,type","parent_id=".$masterid." and isActive='1'  order by priority");
                        if(count($que1)){
                            $str.= '<ul class="list-unstyled text-muted mb-2">';
                            for($j=0;$j<count($que1);$j++){
                                $subid = $que1[$j]['id'];$subname = $que1[$j]['cat_name'];$subtype = $que1[$j]['type'];
                                $str.= '<li><a href="'.SITEURL.'/'.getUrl($subtype,$subid).'" class="point1" hreflang="en">'.$subname.'</a></li>';
                            }
                            $str.='</ul>';
                        }
                        $str.='</div>';
                        $strarr=array(strlen($menu1),strlen($menu2),strlen($menu3),strlen($menu4),strlen($menu5));
                        $minlength=(min($strarr));
                        if($minlength==strlen($menu1)){ $menu1.= $str; }
                        else if($minlength==strlen($menu2)){ $menu2.= $str;}
                        else if($minlength==strlen($menu3)){ $menu3.= $str;}
                        else if($minlength==strlen($menu4)){ $menu4.= $str;}
                        else if($minlength==strlen($menu5)){$menu5.= $str;}
                    } ?>
                    <li class="menu-cat-col <?=($menu1==""?'d-none':''); ?>"><?=$menu1; ?></li>
                    <li class="menu-cat-col <?=($menu2==""?'d-none':''); ?>"><?=$menu2; ?></li>
                    <li class="menu-cat-col <?=($menu3==""?'d-none':''); ?>"><?=$menu3; ?></li>
                    <li class="menu-cat-col <?=($menu4==""?'d-none':''); ?>"><?=$menu4; ?></li>
                    <li class="menu-cat-col <?=($menu5==""?'d-none':''); ?>"><?=$menu5; ?></li>
                </ul>
            </li>
            <? } $offcnts = selectQuery( OFFER, "offer_name,img,offer_link", "isActive='1'  " ) ;
            if(count($offcnts)){ ?>
            <li class="nav-item"><a href="<?php echo SITEURL ?>/offer" hreflang="en">Offers</a></li>
            <?php } ?>
        </ul>
    </div>
</nav> 
