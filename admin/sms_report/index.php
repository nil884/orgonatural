<?php include("../../includes/configuration.php");
$getsms = selectQuery(SMS,"date,msg_type,user_name,mobile_no,message,status","1 order by sms_id DESC");
$distcat = selectQuery(SMS,"distinct msg_type","1 order by msg_type ASC"); ?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : SMS Report</title>
    <?php include('../commoncss.php') ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/dataTable/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/datepicker/bootstrap-datetimepicker.min.css"/>
</head>
<body>
<div class="page-body-wrapper">
    <?php include('../header.php') ?>
    <?php include('../sidebar.php') ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2">
                    <div><h2 class="card-head-title">SMS Enquiry</h2></div>
                    <div class="btn-actions-pane-right"><button type="button" id="refresh" class="refresh btn btn-primary btn-sm"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh Status</button></div>
                </div>
                <div class="card-body">
                   <div class="mb-3 d-inline-block disburs-tot-amt dis-amt-count bg-primary text-white rounded py-1 px-2">
                        <?php $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, "http://api-alerts.solutionsinfini.com/v3/?method=account.credits&api_key=".WORKINGKEY."&format=PHP");
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        $output = curl_exec($ch);
                        curl_close($ch);
                        $arr=(unserialize($output));
                        $credit=floor($arr['data']['credits']);?>
                        Available SMS Credits - <span <?php if($credit<=200){echo 'red';} ?>><?php echo $credit; ?></span>
                    </div>
                    <form id="report" action="generatesmsreport.php">
                        <div class="row">
                            <div class="col-sm-4 col-lg-3 form-group">
                                <select name="smstype" id="smstype" class="form-control form-control-sm">
                                    <option value="All">All</option>
                                    <?php for($i=0;$i<count($distcat);$i++) { ?>
                                    <option value="<?php echo $distcat[$i]['msg_type']; ?>"><?php echo $distcat[$i]['msg_type']; ?></option>
                                    <? } ?>
                                </select>
                            </div>
                            <div class="col-sm-4 col-lg-3 form-group"><input type="text" name="pd" placeholder="From Date" class="form-control" id="smsfrom"></div>
                            <div class="col-sm-4 col-lg-3 form-group"><input type="text" name="pd1" placeholder="To Date" class="form-control" id="smsto"></div>
                            <div class="col-sm-4 col-lg-2 form-group"><button type="button" class="btn btn-primary generate"  onclick="generatereport()">Download Report</button></div>
                        </div>
                    </form>
                    <div class="msgs"></div>
                    <div class="todaysms">
                        <table class="sms-report table table-bordered">
                            <thead><tr><th>#</th><th>Date</th><th>SMS Type</th><th>User Name</th><th>Mobile No</th><th>SMS</th><th>Status</th></tr></thead>
                            <tbody>
                                <?php if(count($getsms)){
                                for($i=0;$i<count($getsms);$i++){ ?>
                                <tr>
                                    <td><?php echo $i+1; ?></td>
                                    <td><?php echo $getsms[$i]['date']; ?></td>
                                    <td><?php echo $getsms[$i]['msg_type']; ?></td>
                                    <td><?php echo $getsms[$i]['user_name']; ?></td>
                                    <td><?php echo $getsms[$i]['mobile_no']; ?></td>
                                    <td><?php echo $getsms[$i]['message']; ?></td>
                                    <td><?php echo $getsms[$i]['status']; ?></td>
                                </tr>
                                <? } } else { ?>
                                <tr><td colspan="7" class="text-center">No SMS sent today</td></tr>
                                <? } ?>
                            </tbody>
                        </table>
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
<script src="<?php echo SITEURL; ?>/js/datepicker/moment.js"></script>
<script src="<?php echo SITEURL; ?>/js/datepicker/bootstrap-datetimepicker.min.js"></script>
<script>
$('.sms-report').DataTable({ "scrollX": true });
$("#smsfrom").datetimepicker({
    format: 'DD-MM-YYYY',
    maxDate: moment(),
});
$("#smsto").datetimepicker({
    format: 'DD-MM-YYYY',
    maxDate: moment(),
});
function generatereport() {
    var date1 = $("#smsfrom").val();
    var date2 = $("#smsto").val();
    var d1 = date1.split("-");
    var d2 = d1[2] + "" + d1[1] + "" + d1[0];
    var d3 = date2.split("-");
    var d4 = d3[2] + "" + d3[1] + "" + d3[0];
    var diff = d4 - d2;
    if (date1 == "" || date2 == ""){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter from and to dates").delay(3000).fadeOut();
    } else if (diff < 0){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("From date may not be greater than To date").delay(3000).fadeOut();
    } else {
        $("#report").submit()
    }
}
$(document).ready(function() {
    $("#refresh").click(function() {
         $("#refresh").html("Refreshing...").attr('disabled',true);
        $.ajax({
            url: "ajaxdata.php",
            data:{action: "sms_status"},
            success: function() {
                $("#refresh").html("<i class='fa fa-refresh' aria-hidden='true'></i> Refresh Status").attr('disabled',false);
            }
        });
    });
});
</script>
</body>
</html>