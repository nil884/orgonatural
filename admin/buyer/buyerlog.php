<? include("../../includes/configuration.php");
$buyer_log = selectQuery(BUYERLOG,"*","user_id=".base64_decode($_REQUEST['buyerid'])." order by log_id DESC LIMIT 30");
$buyer_log_tot = selectQuery(BUYERLOG,"count(log_id)  as total_count","user_id=".base64_decode($_REQUEST['buyerid'])."");
$getbuyer = selectQuery(BUYER,"u_fname,u_lname"," u_id = '".base64_decode($_REQUEST['buyerid'])."' "); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo SITENAME; ?> : Buyer Log</title>
    <? include "../commoncss.php"; ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/dataTable/dataTables.bootstrap4.min.css">
     <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/dataTable/dataTable.buttons.min.css" />
</head>
<body>
<div class="page-body-wrapper">
    <? include '../header.php'; ?>
    <? include '../sidebar.php'; ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2"><div><h2 class="card-head-title">Last 30 Buyer Login Details of <?php echo $getbuyer[0]['u_fname'].' '.$getbuyer[0]['u_lname'] ?></h2></div><div class="btn-actions-pane-right"><button onclick="goBack()" class="btn btn-secondary btn-sm">Back</button></div></div>
                <div class="card-body">
                    <span class="mb-3 d-inline-block disburs-tot-amt dis-amt-count bg-primary text-white rounded py-1 px-2">Total Login Attempts : <?php echo $buyer_log_tot[0]['total_count'] ?></span>
                    <div class="table-responsive">
                        <table class="buyerlog table table-bordered">
                            <thead><tr><th>#</th><th>Login Time</th><th>Login Source</th><th>Device Type</th><th>Browser</th><th> IP Address </th></tr></thead>
                            <tbody>
                                <? for($i=0;$i<count($buyer_log);$i++) { ?>
                                <tr>
                                <td><?php echo $i+1; ?></td>
                                <td>
                                    <?php echo date("d-m-Y", strtotime($buyer_log[$i]['login_time'])) ;
                                    $from_time = strtotime($buyer_log[$i]['login_time']);
                                    $to_time = strtotime(date("d-m-Y H:i:s"));
                                    $all = round(($to_time - $from_time) / 60);
                                    $d = floor($all / 1440);
                                    $h = floor(($all - $d * 1440) / 60);
                                    $m = $all - ($d * 1440) - ($h * 60);
                                    if($d>0){ echo " (".$d." day ".$h.":".$m." min ago)"; }
                                    else{ echo " (".$h.":".$m." min ago)"; } ?>
                                </td>
                                <td>
                                    <?php if($buyer_log[$i]['login_source']) { echo ucfirst($buyer_log[$i]['login_source']); } else{ echo "Website"; } ?>
                                </td>
                                <td><?php if($buyer_log[$i]['device_type'] == ""){ echo "NA"; } else{ echo $buyer_log[$i]['device_type']; }?></td>
                                <td><?php echo $buyer_log[$i]['browser_name']; ?></td>
                                <td><?php echo $buyer_log[$i]['ip_address']; ?></td>
                                </tr>
                                <? } ?>
                            </tbody>
                        </table>
                    </div> 
                </div>  
            </div>  
        </div>  
        <? include "../footer.php"; ?>
    </div>  
 </div>
 <script src="<?php echo SITEURL; ?>/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/dataTables.buttons.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/jszip.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/buttons.html5.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/buttons.colVis.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/buttons.bootstrap4.min.js"></script>
<script>
    //loaddatatable()
    var table = $('.buyerlog').DataTable({ buttons: ['excel'] });
    table.buttons().container().appendTo('.dataTables_wrapper .col-md-6:eq(0) .dataTables_length');
    $(".buttons-excel").addClass("ml-1");
</script>
</body>
</html>
 