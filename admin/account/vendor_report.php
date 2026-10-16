<? include("../../includes/configuration.php");
include("../../classes/order.php"); require_once('../../classes/user.php'); require_once('../../classes/product.php');
$prod = new Product(); $user = new User(); $order = new Order($prod,$user); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo SITE_TITLE; ?> : Vendor Report</title>
    <? include "../commoncss.php"; ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/dataTable/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/datepicker/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/dataTable/dataTable.buttons.min.css" />
</head>
<body>
<div class="page-body-wrapper">
    <? include '../header.php'; ?>
    <? include '../sidebar.php'; ?>
    <div class="main-panel">
        <div class="dashbody">
           <div class="card edit_template">
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Vendor Registrations Report</h2></div></div>
                <div class="card-body pb-0">
                    <div class="row">
                        <div class="col-sm-4 col-md-6 col-lg-4 col-xl-3 mb-3">
                            <label>From Date</label>
                            <div class="input-group"><input type="text" placeholder="From" name="fromdt" class="form-control fromdt border-right-0" autocomplete="off" id="fromdt">
                            <div class="input-group-append"><span class="input-group-text bg-white"><i class="fa fa-calendar"></i></span></div></div>
                        </div>
                        <div class="col-sm-4 col-md-6 col-lg-4 col-xl-3 mb-3">
                            <label>To Date</label>
                            <div class="input-group"><input type="text" placeholder="To" name="todt" class="form-control todt border-right-0" autocomplete="off" id="todt"><div class="input-group-append"><span class="input-group-text bg-white"><i class="fa fa-calendar"></i></span></div></div>
                        </div>
                        <div class="col-sm-4 col-md-12 col-lg-4 col-xl-3 mb-3 d-flex align-items-end"><button type="button" class="btn btn-primary generate" onclick="generateReport()">Generate Report</button></div>
                    </div>
                </div>
            </div>
            <div class="reportData"></div>
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
<script src="<?php echo SITEURL; ?>/js/datepicker/moment.js"></script>
<script src="<?php echo SITEURL; ?>/js/datepicker/bootstrap-datetimepicker.min.js"></script>
<script>
$('#fromdt').datetimepicker({
    ignoreReadonly: true, maxDate:moment(), format: 'DD-MM-YYYY', disabledTimeIntervals:false
}).on("dp.change", function(e){ $("#todt").data("DateTimePicker").minDate(e.date); });
$('#todt').datetimepicker({ ignoreReadonly: true, maxDate:moment(), format: 'DD-MM-YYYY', disabledTimeIntervals:false });
function loaddatatable(){
    var table = $('.data-table').DataTable({ "scrollX": true, buttons: ['excel'] });
    table.buttons().container().appendTo('.btn-actions-pane-right');
}
function generateReport(){
   fromdt = $(".fromdt").val(); todt = $(".todt").val(); 
   if(fromdt!=""&&todt!=""){
       $(".generate").attr("disabled",true).html("Generating...")
       $(".reportData").html("<div class='text-center card card-body h6'>Generating Report.. Please Wait</div>");
        var info = {fromdt:fromdt,todt:todt,action:"generatevendor_report"};
        $.ajax({ 
            type:"POST",
            url:"ajaxdata.php",
            data:info,
            success:function(response){
                $(".generate").attr("disabled",false).html("Generate")
                resdata = response;
                if(resdata!=""){
                    $(".reportData").html(resdata);
                    loaddatatable();
                } else{
                    $(".reportData").html("<div class='text-center card card-body h6'>No Record Found</div>");
                }
            },error:function(response){
                $(".generate").attr("disabled",false).html("Generate");
                $(".reportData").html("<div class='text-center card card-body h6'>Error..</div>");    
            }
        });
   } else{
       $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Select all fiels to generate Report").delay(3000).fadeOut();
   }
}
</script>
</body>
</html>
