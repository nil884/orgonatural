<?php include("../includes/configuration.php");
$s=selectQuery(ADMINLOG,"*","log_id<>'' order by log_id DESC LIMIT 30");
?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Admin Login Details</title>
    <?php include('commoncss.php') ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/dataTable/dataTables.bootstrap4.min.css">
</head>
<body>
<div class="page-body-wrapper">
    <?php include('header.php') ?>
    <?php include('sidebar.php') ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Admin Login Details</h2></div></div>
                <div class="card-body">
                    <table class="table table-bordered lastlogin w-100">
                        <thead><tr><th>#</th><th>Username</th><th>Login Time</th><th>Device Type</th><th>Browser</th><th>IP Address</th></tr></thead>
                        <tbody>
                            <? for($i=0;$i<count($s);$i++){ ?>
                            <tr>
                                <td><?php echo $i+1; ?></td>
                                <td><?php echo $s[$i]['username'] ?></td>
                                <td>
                                    <?php echo $s[$i]['login_time']."<br>";
                                    $date2 = str_replace("/","-",$s[$i]['login_time']);
                                    $from_time = strtotime($date2);
                                    $to_time1 = date("d-m-Y H:i:s");
                                    $to_time = strtotime($to_time1);
                                    $all = round(($to_time - $from_time) / 60);
                                    $d = floor ($all / 1440);
                                    $h = floor (($all - $d * 1440) / 60);
                                    $m = $all - ($d * 1440) - ($h * 60);
                                    if($d>0){ echo " <small class='text-muted'>(".$d." day ".$h.":".$m." min ago)</small>"; } else{ echo " <small class='text-muted'>(".$h.":".$m." min ago)</small>"; } ?>
                                </td>
                                <td><?php if ($s[$i]['device_type'] == "") { echo "NA"; } else { echo $s[$i]['device_type']; } ?></td>
                                <td><?php echo $s[$i]['browser_name']; ?></td>
                                <td><?php echo $s[$i]['ip_address']; ?></td>
                            </tr>
                            <? } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php include 'footer.php'?>
    </div>
</div>
<script src="<?php echo SITEURL; ?>/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script>$(document).ready(function(){$('.lastlogin').DataTable({ "scrollX": true });});</script>
</body>
</html>