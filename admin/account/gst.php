<? include("../../includes/configuration.php");
include("../../classes/order.php"); require_once('../../classes/user.php'); require_once('../../classes/product.php');
$prod = new Product(); $user = new User(); $order = new Order($prod,$user); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo SITE_TITLE; ?> : GST Report</title>
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
                <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">GST Report</h2></div></div>
                <div class="card-body pb-0">
                    <div class="row">
                        <div class="col-sm-6 col-md-4 col-lg-3 col-xl-3 mb-3">
                            <label>From Date</label>
                            <div class="input-group"><input type="text" placeholder="From" name="fromdt" class="form-control fromdt border-right-0" autocomplete="off" id="fromdt"><div class="input-group-append"><span class="input-group-text bg-white"><i class="fa fa-calendar"></i></span></div></div>
                        </div>
                        <div class="col-sm-6 col-md-4 col-lg-3 col-xl-3 mb-3">
                            <label>To Date</label>
                            <div class="input-group"><input type="text" placeholder="To" name="todt" class="form-control todt border-right-0" autocomplete="off" id="todt"><div class="input-group-append"><span class="input-group-text bg-white"><i class="fa fa-calendar"></i></span></div></div>
                        </div>
                        <div class="col-sm-6 col-md-4 col-lg-6 col-xl-3 mb-0 mb-sm-3">
                            <label>Report Type</label>
                            <div class="py-2">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="report" class="report custom-control-input" id="gst-sal-rep" value="Sale" checked><label class="custom-control-label" for="gst-sal-rep">Sale</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="report" class="report custom-control-input" id="gst-pur-rep" value="Purchase"> <label class="custom-control-label" for="gst-pur-rep">Purchase</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-12 col-lg-12 col-xl-3 mb-3 d-flex align-items-end"><button type="button" class="btn btn-primary generate" onclick="generateReport()">Generate Report</button></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 col-md-5 col-lg-5 col-xl-3 reg-update-dash cc-display-none">
                    <div class="dash-update-tiles rounded bg-success mb-3 p-3 text-white position-relative ">
                        <span class="fa fa-file-text position-absolute bg-white text-success"></span>
                        <div class="dash-update-body">
                            <div class="h5" id="total_order"></div>
                            <small>Total</small>
                            <h6 class="mb-0">Orders</h6>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-5 col-lg-5 col-xl-3 reg-update-dash cc-display-none">
                    <div class="dash-update-tiles rounded bg-primary mb-3 p-3 text-white position-relative ">
                        <span class="fa fa-inr position-absolute bg-white text-success"></span>
                        <div class="dash-update-body">
                            <div class="h5" id="total_amount"></div>
                            <small>Total</small>
                            <h6 class="mb-0">Amount</h6>
                        </div>
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
   fromdt = $(".fromdt").val(); todt = $(".todt").val(); report = $(".report:checked").val();
   if(fromdt!=""&&todt!=""&&report!=""){
       $(".generate").attr("disabled",true).html("Generating...")
        $(".reg-update-dash").addClass('cc-display-none');
       $(".reportData").html("<div class='text-center card card-body h6'>Generating Report.. Please Wait</div>");
        var info = {fromdt:fromdt,todt:todt,report:report,action:"generateGSTReport"};
        $.ajax({ 
            type:"POST",
            url:"ajaxdata.php",
            data:info,
            success:function(response){ 
                $(".generate").attr("disabled",false).html("Generate")
                resdata = JSON.parse(response);
                if(resdata!=""){
                    document.title = report+" Report "+fromdt+" To "+todt
                    str="<div class='card mb-0'><div class='card-header sec-card-head justify-content-between align-items-center py-2'><h5 class='card-head-title'>"+report+" Report "+fromdt+" To "+todt+" </h5><div class='btn-actions-pane-right'></div></div><div class='card-body'><table class='table table-bordered data-table w-100'><thead>";
                    var headers = resdata['headers']; var records=resdata['data'];
                    var total_count=resdata['total_count'];
                    var total_amount=resdata['total_amount'];
                    str+="<th>#</th>";
                    for(i=0;i<headers.length;i++){ str+="<th>"+headers[i]+"</th>"; }
                    str+="</thead><tbody>";
                    for(i=0;i<records.length;i++){
                        subarr=records[i];  str+="<tr>";
                        cnt = i+1;
                        str+="<td>"+cnt+"</td>"; 
                        for(j=0;j<subarr.length;j++){ str+="<td>"+subarr[j]+"</td>"; }
                        str+="</tr>";
                    }
                    str+="</tbody></table></div></div>";
                    $(".reg-update-dash").removeClass('cc-display-none');
                    $(".reportData").html(str);
                    $("#total_order").html(total_count);
                    $("#total_amount").html(total_amount);
                    loaddatatable();
                } else{
                    $(".reportData").html("<div class='text-center card card-body h6'>No record found</div>");
                }
            },error:function(response){
                $(".generate").attr("disabled",false).html("Generate");
                $(".reportData").html("<div class='text-center card card-body h6'>Error..</div>");    
            }
        });
   }else{
       $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Select all fiels to generate Report").delay(3000).fadeOut();
   }
}
</script>
</body>
</html>