<?php include("../includes/configuration.php"); ?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Support</title>
    <?php include 'commoncss.php';?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/dataTable/dataTables.bootstrap4.min.css">
</head>
<body>
<div class="page-body-wrapper">
    <?php include('menu.php');
    $totstaff=selectQuery(SUPPORTSTAFF,"*","acc_type<>'Client' order by emp_name ASC"); ?>
    <? include 'sidebar.php'; ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center">
                    <div><h5 class="card-head-title">Open Tickets <span class="badge badge-info"><?php echo count($getopen); ?></span></h5></div>
                </div>
                <div class="card-body">
                    <?php if($_SESSION['acc_type']=="Admin" || $_SESSION['acc_type']=="Staff"){?>
                    <?php include 'search.php'; } ?>
                    <div class="table-responsive">
                        <table class="display table table-striped" id="guest-grid">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Ticket #</th>
                                    <th>Client Details</th>
                                    <th>Status</th>
                                    <th>Assigned Staff</th>
                                    <th>Department</th>
                                    <th>Created On</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php';?>
<script src="<?php echo SITEURL; ?>/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function(){
    var dataTable = $('#guest-grid').DataTable({
        "processing": true,
        "bFilter": false,
        "paging": true,
        "serverSide": true,
        "searching": { "regex": false },
        "pageLength": 10,
        "lengthMenu": [ [10, 25, 50, 100,], [10, 25, 50, 100] ],
        "ajax":{
            url : "opnlead_grid_data.php",
            type : "post",
            data : {wherecond:"<?php echo $openwhere; ?>"},
            error: function(){
                $(".guest-grid-error").html("");
                $("#guest-grid").append('<tbody class="guest-grid-error text-muted"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                $("#guest-grid_processing").css("display","none");
            }
        }
    });
});
</script>
</body>
</html>