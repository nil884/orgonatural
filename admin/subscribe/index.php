<?php include("../../includes/configuration.php");
$getsubscribe_user=selectQuery(SUBSCRIBE,"*"); ?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Subscribed Users</title>
    <?php include('../commoncss.php') ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/dataTable/dataTables.bootstrap4.min.css">
</head>
<body>
<div class="page-body-wrapper">
    <?php include('../header.php') ?>
    <?php include('../sidebar.php') ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2"><div><h2 class="card-head-title">Subscribed Users - All List</h2></div><div class="btn-actions-pane-right"></div></div>
                <div class="card-body">
                    <div class="msgs"></div>
                    <table class="subscribe-user table table-bordered w-100">
                        <thead><tr><th>#</th><th>Email ID</th><th>Subscribe / Unsubscribe</th><th>Subscription Date</th><th>Unsubscribe Date</th></tr></thead>
                        <tbody>
                            <?php for($i=0;$i<count($getsubscribe_user);$i++){ ?>
                            <tr>
                                <td class="td2"><?php echo $i+1; ?></td>
                                <td><?php echo $getsubscribe_user[$i]['email']; ?></td>
                                <td class="td1">
                                <?php if($getsubscribe_user[$i]['subscribe'] == 1)
                                echo "Subscribe";
                                else
                                echo  "Unsubscribe";
                                ?>
                                </td>
                                <td><?php echo date("d-m-Y",strtotime($getsubscribe_user[$i]['date'])) ?></td>
                                <td><?php  if($getsubscribe_user[$i]['unsubscribedate'] == "") { echo "NA";} else { echo date("d-m-Y",strtotime($getsubscribe_user[$i]['unsubscribedate'])); } ?></td>
                            </tr>
                            <? } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php include('../footer.php');?>
    </div>
</div>
<script src="<?php echo SITEURL; ?>/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/dataTables.buttons.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/jszip.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/buttons.html5.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/buttons.colVis.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/buttons.bootstrap4.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/datepicker/moment.js"></script>
<script src="<?php echo SITEURL; ?>/js/datepicker/bootstrap-datetimepicker.min.js"></script>
<script>
$(document).ready(function(){
    var table = $('.subscribe-user').DataTable({
        "scrollX": true, buttons: ['excel']
    });
    table.buttons().container().appendTo('.btn-actions-pane-right');
});
</script>
</body>
</html>