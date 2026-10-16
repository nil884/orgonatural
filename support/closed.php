<?php include("../includes/configuration.php"); ?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Closed Tickets</title>
    <?php include 'commoncss.php';?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/dataTable/dataTables.bootstrap4.min.css">
</head>
<body>
<div class="page-body-wrapper">
    <?php include('menu.php');?>
    <? include 'sidebar.php'; ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center">
                <div><h5 class="card-head-title">Closed Tickets <span class="badge badge-primary"><?php echo count($getclosed); ?></span></h5></div>
                </div>
                <div class="card-body">
                    <?php include 'search.php'; ?>
                    <div class="table-responsive">
                        <table class="display table table-striped" id="guest-grid">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Ticket #</th>
                                    <th>Client Details</th>
                                    <th>Status</th>
                                    <th>Assigned To</th>
                                    <th>Closed On</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php include('footer.php'); ?>
    </div>
</div>
<script src="<?php echo SITEURL; ?>/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function() {
    var dataTable = $('#guest-grid').DataTable({
        "processing" : true,
        "bFilter" : false,
        "paging" : true,
        "serverSide" : true,
        "searching" : { "regex": false },
        "pageLength" : 10,
        "lengthMenu" : [ [10, 25, 50, 100,], [10, 25, 50, 100] ],
        "ajax":{
            url : "closelead_grid_data.php",
            type : "post",
            data : {wherecond:"<?php echo $closewhere; ?>"},
            error : function(){
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