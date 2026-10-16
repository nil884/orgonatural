<?php $Notification_home = selectQuery(NOTIFICATION,"notificationico,notificationlink,notification","isActive ='1' and myaccount = '1' and show_on_all = '0' ");
if(count($Notification_home)){ ?>
<div class="notification-toggle pt-2 pb-2 text-center">
    <div class="container"><div class="row"><div class="col-md-12 marquee"><div class="marq-notif"><img src="<?=SITEURL; ?>/img/projectimage/<?=$Notification_home[0]['notificationico']; ?>" alt="<?=$Notification_home[0]['notificationico']; ?>" width="15" class="mr-1"/><a href="<?=$Notification_home[0]['notificationlink']; ?>"> <?=$Notification_home[0]['notification']; ?></a></div></div></div></div><span class="close-notif position-absolute d-inline-block"><i class="fa fa-times"></i></span>
</div>
<?php } ?>