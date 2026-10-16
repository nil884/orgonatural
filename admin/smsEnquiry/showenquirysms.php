<?php include("../../includes/configuration.php"); ?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : SMS Enquiry</title>
    <?php include('../commoncss.php') ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/dataTable/dataTables.bootstrap4.min.css">
</head>
<body>
<div class="page-body-wrapper">
    <?php include('../header.php') ?>
    <?php include('../sidebar.php') ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">SMS Enquiry</h2></div></div>
                <div class="card-body">
                    <div class="msgs"></div>
                    <div class="todaysms">
                        <div class="table-responsive">
                            <table class="smsenquiry table table-bordered">
                                <thead><tr><th>#</th><th>User Name</th><th>Mobile No</th><th>Message</th><th>Date</th></tr></thead>
                                <tbody>
                                    <?php if(count($getsmsenquiry)){
                                    for($i=0;$i<count($getsmsenquiry);$i++){?>
                                    <tr>
                                        <td><?php echo $i+1; ?></td>
                                        <td><?php echo $getsmsenquiry[$i]['user_name']; ?></td>
                                        <td><?php echo $getsmsenquiry[$i]['mobile_no']; ?></td>
                                        <td>
                                            <?php $message = $getsmsenquiry[$i]['sms'];
                                            if(strlen($message)>10){
                                            $messagearr = str_split($message);
                                            $messagestr = "";
                                            for($j=0;$j<15;$j++){ $messagestr=$messagestr."".$messagearr[$j]; } ?>
                                            <a onclick="getcomm('<?php echo $message;?>');"> <?php echo $messagestr."..."; ?></a>
                                            <?php } else{ echo $message; } ?>
                                        </td>
                                        <td><?php echo $message = $getsmsenquiry[$i]['date'];?></td>
                                    </tr>
                                    <? } } else{ ?>
                                    <tr><td colspan="7" class="text-center">No SMS Sent Today</td></tr>
                                    <? } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include('../footer.php');?>
    </div>
</div>
<div class="modal fade" id="getcomment" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body"><div id="getc"></div></div>
            <div class="modal-footer">
            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo SITEURL; ?>/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script>
$('.smsenquiry').DataTable();
function formatDate(date){
    var d = new Date(date),
    month = '' + (d.getMonth() + 1),
    day = '' + d.getDate(),
    year = d.getFullYear();
    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;
    return [year, month, day].join('');
};
function getcomm(comment){
    $("#getcomment").modal('show');
    $("#getc").html(comment);
}
</script>
</body>
</html>