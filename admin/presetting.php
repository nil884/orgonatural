<?php include("../includes/configuration.php");?>
<!doctype html>  
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Setting & Logs</title>
    <?php include('commoncss.php') ?>
</head>
<body>
<div class="page-body-wrapper">
    <? include 'header.php'; ?>
    <? include 'sidebar.php'; ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="row">
                <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xl-2">
                    <div class="card pressetting text-center border-0 shadow-sm">
                        <div class="card-body"><a href="<?php echo ADMINURL; ?>setting/viewsubadminlist.php"><span class="preseticon btn-danger"><i class="fa fa-users" aria-hidden="true"></i></span>Manage Subadmins</a></div>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xl-2">
                    <div class="card pressetting text-center border-0 shadow-sm">
                        <div class="card-body"><a href="<?php echo ADMINURL; ?>authenticateuser.php"><span class="preseticon btn-success"><i class="fa fa-user" aria-hidden="true"></i></span> Edit Profile</a></div>
                    </div>
                </div>
                <?php if($getusertype=='Admin') { ?>
                <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xl-2">
                    <div class="card pressetting text-center border-0 shadow-sm">
                        <div class="card-body"><a href="<?php echo ADMINURL; ?>settings.php"><span class="preseticon btn-warning"><i class="fa fa-check-circle" aria-hidden="true"></i></span> 2 Step Authentication</a></div>
                    </div>
                </div>
                <? } ?>
                <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xl-2">
                    <div class="card pressetting text-center border-0 shadow-sm">
                        <div class="card-body"><a href="<?php echo ADMINURL; ?>setting/allfailedlogins.php"><span class="preseticon btn-info"><i class="fa fa-sign-in"></i></span> All Failed Login List</a></div>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xl-2">
                    <div class="card pressetting text-center border-0 shadow-sm">
                        <div class="card-body"><a href="<?php echo ADMINURL; ?>sms_email_setting/sms.php"><span class="preseticon btn-primary"><i class="fa fa-comment"></i></span> SMS Setting</a></div>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xl-2">
                    <div class="card pressetting text-center border-0 shadow-sm">
                        <div class="card-body"><a href="<?php echo ADMINURL; ?>sms_email_setting/email.php"><span class="preseticon btn-secondary"><i class="fa fa-envelope"></i></span> Email Setting</a></div>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xl-2">
                    <div class="card pressetting text-center border-0 shadow-sm">
                        <div class="card-body"><a href="<?php echo ADMINURL; ?>config/backup.php"><span class="preseticon btn-warning"><i class="fa fa-cloud-download text-white"></i></span> Backup</a></div>
                    </div>
                </div>
            </div>
        </div>
        <?php include('footer.php');?>
    </div>
</div>
</body>
</html>