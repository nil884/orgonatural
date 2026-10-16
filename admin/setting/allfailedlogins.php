<?php include("../../includes/configuration.php");
    $tab1data = selectQuery(BUYERLOG,"*","login_attempt='failed' order by log_id DESC LIMIT 30");
    $tab2data = selectQuery(VENDORLOG,"*","login_attempt='failed' order by log_id DESC LIMIT 30");
    $tab3data = selectQuery(ADMINLOG,"*","login_attempt='failed' order by log_id DESC  LIMIT 30");
    $tab1datacnt = selectQuery(BUYERLOG,"*","login_attempt='failed' order by log_id DESC ");
    $tab2datacnt = selectQuery(VENDORLOG,"*","login_attempt='failed' order by log_id DESC ");
    $tab3datacnt = selectQuery(ADMINLOG,"*","login_attempt='failed' order by log_id DESC ");
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <title><?php echo SITENAME; ?> : All Failed Login</title>
    <?php include('../commoncss.php') ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/dataTable/dataTables.bootstrap4.min.css">
</head>
<body>
<div class="page-body-wrapper">
    <? include '../header.php'; ?>
    <? include '../sidebar.php'; ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2">
                    <div><h5 class="card-head-title">All Failed Logins</h5></div>
                    <div class="btn-actions-pane-right"><button class="btn btn-secondary btn-sm" onclick="goBack()">Back</button></div>
                </div>
                <div class="card-body">
                    <div class="msgs"></div>
                    <div id="tabs">
                        <ul class="nav nav-tabs mb-3">
                            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#home">Buyer Failed Login (<?php echo count($tab1datacnt); ?>)</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#unpaid">Vendor Failed Login (<?php echo count($tab2datacnt); ?>)</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#delivered">Admin Failed Login (<?php echo count($tab3datacnt); ?>)</a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="home" class="tab-pane active">
                                <h5 class="mb-3 h6">Buyer Failed Logins (Recent 30 Records)</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered failedlogin">
                                        <thead><tr><th>#</th><th>Username</th><th>Attempted Password</th><th>Time</th><th>Device Type</th><th>Browser</th><th>IP Address</th></tr></thead>
                                        <tbody>
                                            <?php for($i=0;$i<count($tab1data);$i++){ ?>
                                            <tr>
                                                <td><?php echo $i+1; ?></td>
                                                <td><?php echo $tab1data[$i]['username']; ?></td>
                                                <td><?php echo $tab1data[$i]['password']; ?></td>
                                                <td>
                                                <?php echo $tab1data[$i]['login_time']."<br>";
                                                $date2 = str_replace("/","-",$tab1data[$i]['login_time']);
                                                $from_time = strtotime($date2);
                                                $to_time1 = date("d-m-Y H:i:s");
                                                $to_time = strtotime($to_time1);
                                                $all = round(($to_time - $from_time) / 60);
                                                $d = floor($all / 1440);
                                                $h = floor(($all - $d * 1440) / 60);
                                                $m = $all - ($d * 1440) - ($h * 60);
                                                if($d>0){ echo " (".$d." day ".$h.":".$m." min ago)"; }
                                                else{ echo " (".$h.":".$m." min ago)"; } ?>
                                                </td>
                                                <td><?php  if($tab1data[$i]['device_type'] == "") { echo "NA"; } else { echo $tab1data[$i]['device_type']; } ?></td>
                                                <td><?php echo $tab1data[$i]['browser_name']; ?></td>
                                                <td><?php echo $tab1data[$i]['ip_address']; ?></td>
                                            </tr>
                                            <? } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div id="unpaid" class="tab-pane">
                                <h5 class="mb-3 h6">Vendor Failed Logings (Recent 30 Records)</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered failedlogin">
                                        <thead><tr><th>#</th><th>Username</th><th>Attempted Password</th><th>Time</th><th>Device Type</th><th>Browser</th><th>IP Address</th></tr></thead>
                                        <tbody>
                                            <?php for($i=0;$i<count($tab2data);$i++){ ?>
                                            <tr>
                                                <td><?php echo $i+1; ?></td>
                                                <td><?php echo $tab2data[$i]['username']; ?></td>
                                                <td><?php echo $tab2data[$i]['password']; ?></td>
                                                <td>
                                                <?php echo $tab2data[$i]['login_time']."<br>";
                                                $date2 = str_replace("/","-",$tab2data[$i]['login_time']);
                                                $from_time = strtotime($date2);
                                                $to_time1 = date("d-m-Y H:i:s");
                                                $to_time = strtotime($to_time1);
                                                $all = round(($to_time - $from_time) / 60);
                                                $d = floor ($all / 1440);
                                                $h = floor (($all - $d * 1440) / 60);
                                                $m = $all - ($d * 1440) - ($h * 60);
                                                if($d>0){ echo " (".$d." day ".$h.":".$m." min ago)"; }
                                                else{ echo " (".$h.":".$m." min ago)"; } ?>
                                                </td>
                                                <td><?php if($tab2data[$i]['device_type'] == ""){echo "NA"; } else{ echo $tab2data[$i]['device_type']; } ?></td>
                                                <td><?php echo $tab2data[$i]['browser_name']; ?></td>
                                                <td><?php echo $tab2data[$i]['ip_address']; ?></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div id="delivered" class="tab-pane">
                                <h5 class="mb-3 h6">Admin Failed Logings (Recent 30 Records)</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered failedlogin">
                                        <thead><tr><th>#</th><th>Username</th><th>Attempted Password</th><th>Time</th><th>Device Type</th><th>Browser</th><th>IP Address</th></tr></thead>
                                        <tbody>
                                            <?php for($i=0;$i<count($tab3data);$i++){ ?>
                                            <tr>
                                                <td><?php echo $i+1; ?></td>
                                                <td><?php echo $tab3data[$i]['username']; ?></td>
                                                <td><?php echo $tab3data[$i]['password']; ?></td>
                                                <td>
                                                <?php echo $tab3data[$i]['login_time']."<br>";
                                                $date2 = str_replace("/","-",$tab3data[$i]['login_time']);
                                                $from_time = strtotime($date2);
                                                $to_time1 = date("d-m-Y H:i:s");
                                                $to_time = strtotime($to_time1);
                                                $all = round(($to_time - $from_time) / 60);
                                                $d = floor($all / 1440);
                                                $h = floor(($all - $d * 1440) / 60);
                                                $m = $all - ($d * 1440) - ($h * 60);
                                                if($d>0){ echo " (".$d." day ".$h.":".$m." min ago)"; }
                                                else{ echo " (".$h.":".$m." min ago)"; } ?>
                                                </td>
                                                <td><?php if($tab3data[$i]['device_type'] == ""){ echo "NA"; }else{ echo $tab3data[$i]['device_type'];} ?></td>
                                                <td><?php echo $tab3data[$i]['browser_name']; ?></td>
                                                <td><?php echo $tab3data[$i]['ip_address']; ?></td>
                                            </tr>
                                            <? } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include '../footer.php';?>
    </div>
</div>
<script src="<?php echo SITEURL; ?>/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script>$(document).ready(function(){$('.failedlogin').DataTable();});</script>
</body>
</html>