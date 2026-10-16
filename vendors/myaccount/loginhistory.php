<?php include("../../includes/configuration.php");?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Login Attempts</title>
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
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Latest 10 Login Attempts</h2></div></div>
                 <div class="card-body">
                    <?php $getvendorlog=selectQuery(VENDORLOG,"*","log_id<>'' and vendor_id  ='".$_SESSION['seller']."' order by log_id DESC LIMIT 10"); ?>
                    <table class="last-login logt table table-bordered w-100">
                        <thead><tr><th>#</th><th>Last Login Date</th><th>Login Time</th><th>Login Before</th><th> IP Address</th></tr></thead>
                        <tbody>
                            <? for($i=0;$i<count($getvendorlog);$i++) { ?>
                            <tr>
                            <td><?php echo $i+1; ?></td>
                            <!-- <td><?php echo $getvendorlog[$i]['username'] ?></td>  -->
                            <td><?php $date = explode(" ", $getvendorlog[$i]['login_time']);
                            echo $date[0]; ?></td>
                            <td><?php $date = explode(" ", $getvendorlog[$i]['login_time']);
                            echo $date[1];?></td>
                            <td>
                                <?php $date = explode(" ", $getvendorlog[$i]['login_time']);
                                $date2= str_replace("/","-",$getvendorlog[$i]['login_time']);
                                $from_time = strtotime($date2);
                                $to_time1 = date("d-m-Y H:i:s");
                                $to_time = strtotime($to_time1);
                                $all = round(($to_time - $from_time) / 60);
                                $d = floor ($all / 1440);
                                $h = floor (($all - $d * 1440) / 60);
                                $m = $all - ($d * 1440) - ($h * 60);
                                if($d>0){
                                    echo " (".$d." day ".$h.":".$m." min ago)";
                                }else{
                                    echo " (".$h.":".$m." min ago)";
                                }?>
                            </td>
                            <td><?php echo $getvendorlog[$i]['ip_address']; ?></td>
                            </tr>
                            <? } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php include('../footer.php'); ?>
    </div>
</div>
<script src="<?php echo SITEURL; ?>/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script>$('.last-login').DataTable({ "scrollX": true });</script>
</body>
</html>
