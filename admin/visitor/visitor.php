<?php include("../../includes/configuration.php");
    $get_todays_visit_list = selectQuery(VISITORLIST,"ip,device,browser,details,date","date like '%".date('Y-m-d')."%' order by id DESC");
    $Applecnt = 0;
    $Chromecnt = 0;
    $Netscapecnt = 0;
    $Explorercnt = 0;
    $Edgecnt = 0;
    $Firefoxcnt = 0;
    $Operaecnt = 0;
    $uncnt=0;$icecnt = 0;
    $Windows7cnt = 0;
    $Windows10cnt = 0;
    $androidcnt = 0;
    $blackberrycnt = 0;
    $ioscnt = 0;
    $windowsmobcnt = 0;
    $linuxcnt = 0;
    for($i=0;$i<count($get_todays_visit_list);$i++){
        $browser = $get_todays_visit_list[$i]['browser'];
        $device = $get_todays_visit_list[$i]['device'];
        if($browser=='Apple Safari'){ $Applecnt++; }
        else if($browser=='Google Chrome'){ $Chromecnt++; }
        else if($browser=='Internet Explorer'){ $Explorercnt++; }
        else if($browser=='Microsoft Edge'){ $Edgecnt++; }
        else if($browser=='Mozilla Firefox'){ $Firefoxcnt++; }
        else if($browser=='Netscape'){ $Netscapecnt++; }
        else if($browser=='Opera'){ $Operaecnt++; }
        else if($browser=='Iceweasel'){ $icecnt++; }
        else if($browser=='NA'||$browser==''){ $uncnt++; }
        if($device=='Windows 7'){ $Windows7cnt++; }
        else if($device=='Windows 10'){ $Windows10cnt++; }
        else if($device=='android'){ $androidcnt++; }
        else if($device=='blackberry'){ $blackberrycnt++; }
        else if($device=='Windows Phone'){ $windowsmobcnt++; }
        else if($device=='Linux'){ $linuxcnt++; }
        else if($device=='iphone'||$device=='ipad'){ $ioscnt++; }
    }
?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Visitor List</title>
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
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Search Visitor Report</h2></div></div>
                <form id="report" action="generate_visitor_reportvisitor_report.php">
                    <div class="card-body pb-1">
                        <div class="row"> 
                            <div class="col-6 col-sm-4 col-md-4 col-lg-3 mb-3">
                                <label>From Date</label>
                                <input type="text" name="from_date" placeholder="From Date" class="form-control" id="from_date">
                            </div>
                            <div class="col-6 col-sm-4 col-md-4 col-lg-3 mb-3">
                                <label>To Date</label>
                                <input type="text" name="to_date" placeholder="To Date" class="form-control" id="to_date">
                            </div>                            
                        </div>
                    </div>
                    <div class="card-footer py-2 text-right"><button type="button" class="btn btn-primary generate"  onclick="generatereport()">Download Report</button></div>
                </form>
            </div>
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Todays Visitor List</h2></div></div>
                <div class="card-body">                    				
                    <div class="row"> 
                        <div class="col-md-6 col-sm-6 col-xs-12 stat1">
                            <h6 class="mt-2 mb-3 cc-font-weight-5">Browser Specific Hits</h6>
                            <div class="table-responsive">
                                <table class="work table table-bordered">
                                    <tr><td class="py-2">Apple Safari </td><td class="py-2"><?php echo $Applecnt; ?></td></tr>
                                    <tr><td class="py-2">Google Chrome</td><td class="py-2"><?php echo $Chromecnt; ?></td></tr>
                                    <tr><td class="py-2"> Internet Explorer </td><td class="py-2"><?php echo $Explorercnt; ?></td></tr>
                                    <tr><td class="py-2">Microsoft Edge</td><td class="py-2"><?php echo $Edgecnt; ?></td></tr>
                                    <tr><td class="py-2">Mozilla Firefox</td><td class="py-2"><?php echo $Firefoxcnt; ?></td></tr>
                                    <tr><td class="py-2">Netscape</td><td class="py-2"><?php echo $Netscapecnt; ?></td></tr>
                                    <tr><td class="py-2">Opera</td><td class="py-2"><?php echo $Operaecnt; ?></td></tr>
                                    <tr><td class="py-2"> Iceweasel - <span class="text-danger">(May Be Browser From Kali Linux)</span></td><td class="py-2"><?php echo $icecnt; ?></td></tr>
                                    <tr><td class="py-2">Un-identified</td><td class="py-2"><?php echo $uncnt; ?></td></tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12 stat1" >
                            <h6 class="mt-2 mb-3 cc-font-weight-5">OS Specific Hits</h6>
                            <div class="table-responsive">
                                <table class="work table table-bordered">
                                    <tr><td class="py-2">Windows 7</td><td class="py-2"><?php echo $Windows7cnt; ?></td></tr>
                                    <tr><td class="py-2">Windows 10</td><td class="py-2"><?php echo $Windows10cnt; ?></td></tr>
                                    <tr><td class="py-2">Android</td><td class="py-2"><?php echo $androidcnt; ?></td></tr>
                                    <tr><td class="py-2">IOS</td><td class="py-2"><?php echo $ioscnt; ?></td></tr>
                                    <tr><td class="py-2">Linux</td><td class="py-2"><?php echo $linuxcnt; ?></td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Today's Visitor History</h2></div></div>
                <div class="card-body">
                    <div class="msgs"></div>
                    <div class="todaysms">
                        <table class="example table table-bordered w-100">
                            <thead><tr><th>#</th><th>IP Address</th><th>Device</th><th>Browser</th><th>Details</th><th>Time</th></tr></thead>
                            <tbody>
                                <?php if(count($get_todays_visit_list)){
                                for($i=0;$i<count($get_todays_visit_list);$i++){ ?>
                                <tr>
                                    <td><?php echo $i+1; ?></td>
                                    <td><?php echo $get_todays_visit_list[$i]['ip']; ?></td>
                                    <td><?php if($get_todays_visit_list[$i]['device'] == "") { echo "NA";} else { echo $get_todays_visit_list[$i]['device']; }?></td>
                                    <td><?php echo $get_todays_visit_list[$i]['browser']; ?></td>
                                    <td><?php echo $get_todays_visit_list[$i]['details']; ?></td>
                                    <td><?php echo  date("d-m-Y h:i:s", strtotime($get_todays_visit_list[$i]['date'])); ?></td>
                                </tr>
                                <? } } else{ ?>
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
<script src="<?php echo SITEURL; ?>/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/datepicker/moment.js"></script>
<script src="<?php echo SITEURL; ?>/js/datepicker/bootstrap-datetimepicker.min.js"></script>
<script>
$('.example').DataTable({ "scrollX": true });
$("#from_date").datetimepicker({ format: 'DD-MM-YYYY', maxDate: moment(), });
$("#to_date").datetimepicker({ format: 'DD-MM-YYYY', maxDate: moment(), });
function generatereport(){
    var date1 = $("#from_date").val();
    var date2 = $("#to_date").val();
    var d1 = date1.split("-");
    var d2 = d1[2] + "" + d1[1] + "" + d1[0];
    var d3 = date2.split("-");
    var d4 = d3[2] + "" + d3[1] + "" + d3[0];
    var diff = d4 - d2;
    if (date1 == "" || date2 == ""){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter from and to dates").delay(3000).fadeOut();
    } else if (diff < 0) {
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("From date may not be greater than To date").delay(3000).fadeOut();
    } else { $("#report").submit() }
}
</script>
</body>
</html>