<? include("../../includes/configuration.php");
$getbuyer = selectQuery(BUYER,"u_fname,u_lname,email_verified,isActive,u_email,u_mobile,source,last_login,u_id"," u_id <> '' order by u_id DESC"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo SITENAME; ?> : Buyer Details</title>
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
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2"><h2 class="card-head-title">Buyer Details</h2><div class="btn-actions-pane-right"><a href="failedlog.php" class="btn btn-primary btn-sm">Failed Login Attempts</a></div></div>
                <div class="card-body">
                        <table class="buyer table table-bordered w-100" id="buyer-table">
                            <thead><tr><th>#</th><th>Name</th><th>Last Login</th><th>Active/Deactive</th></tr></thead>
                            <tbody>
                                <?php
                                for($i=0;$i<count($getbuyer);$i++){?>
                                <tr>
                                    <td><?=$i+1; ?></td>
                                    <td>
                                        <a href="buyersdetails.php?u_id=<?php echo base64_encode($getbuyer[$i]['u_id']); ?>"><?php echo $getbuyer[$i]['u_fname'].' '.$getbuyer[$i]['u_lname'] ?>
                                        <div><div class="<?php if(($getbuyer[$i]['email_verified'] == 1) && ($getbuyer[$i]['isActive'] == 1)){ echo "text-success"; } else{ echo "text-danger"; } ?>"><?php echo $getbuyer[$i]['u_email']; ?></div></a>

                                        <? if(($getbuyer[$i]['email_verified'] == 1) && ($getbuyer[$i]['isActive'] == 1)) echo "<div class='badge badge-success'><i class='fa fa-check'></i> Verified</div>"; else echo "<div class='badge badge-danger'><i class='fa fa-close'></i> Not-Verified</div>"; ?>
                                        </div>
                                        <div class="mt-1">Contact No : <?php echo $getbuyer[$i]['u_mobile']; ?></div>
                                    </td>
                                    <td>
                                        <?php if($getbuyer[$i]['source'] != ""){ echo ucfirst($getbuyer[$i]['source']); } else { echo "Website"; } echo"<br>"; ?>

                                        <?php if($getbuyer[$i]['last_login']!=""&&$getbuyer[$i]['last_login']!="0000-00-00 00:00:00"){ echo date("d-m-Y H:i:s",strtotime($getbuyer[$i]['last_login'])) ; ?> <br><a href="<?php echo ADMINURL; ?>buyer/buyerlog.php?buyerid=<?php echo base64_encode($getbuyer[$i]['u_id']); ?>" >More Details</a><? } ?>
                                    </td>
                                    <td>
                                       <label class="switch btn btn-primary">
                                        <input type="checkbox" class="tg" data-id="<?php echo $getbuyer[$i]['u_id']; ?>" id="checkbox_<?php echo $getbuyer[$i]['u_id']; ?>" name="checkbox_<?php echo $getbuyer[$i]['u_id']; ?>" <?php if($getbuyer[$i]['isActive']==1){echo "checked";}else{echo " ";} ?> onchange="tgchng('<?php echo $getbuyer[$i]['u_id']; ?>','checkbox_<?php echo $getbuyer[$i]['u_id']; ?>')">
                                        <span class="slider round"><span class="on">Active</span><span class="off">Deactive</span></span>
                                      </label>
                                  
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table> 
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
<script src="<?php echo SITEURL; ?>/js/datepicker/moment.js"></script>
<script src="<?php echo SITEURL; ?>/js/datepicker/bootstrap-datetimepicker.min.js"></script>
<script>
function tgchng(v1,v2){
    var uid = v1; var getid = v2; var c = $("#"+getid+":checked").val();
    if(c=="on"){ status = 1;  msg = "activated"; } else{ status = 0; msg = "deactivated"; }
    var info = {uid:uid,status:status,action:"statuschnage"}
    $.ajax({
        type:"POST",
        url:"ajaxdata.php",
        data:info,
        success:function(response){
            if(response==1){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Buyer "+msg+" successfully").delay(3000).fadeOut();
            } else{
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error.. Try again later").delay(3000).fadeOut();
            }
        }
    });
}
</script>
<script>
var table = $("#buyer-table").DataTable({
    columnDefs: [{
        "render": function (data, type, row) {
            var expinpstatus = (type === 'export' ? ($(data).find('input[type="checkbox"]').prop("checked")===true ? 'Active' : 'Deactive') : data);
            return expinpstatus;
        },
        "targets": [3]
    }],
    "scrollX": true,
    buttons: [{
        extend: 'excelHtml5',
        exportOptions: {
            orthogonal: 'export'
        }
    }]
});
table.buttons().container().appendTo('.dataTables_wrapper .col-md-6:eq(0) .dataTables_length');
$(".dt-buttons").addClass("ml-1");
</script>
</body>
</html>