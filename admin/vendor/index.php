<? include("../../includes/configuration.php");
   require_once "../../classes/shiprocket.php";

    $username=SHIPUSER; $pasword=SHIPPWD;
    $ship=new shiprocket($username,$pasword);

    $token=$ship->authenticate();
$getvendor = selectQuery(VENDOR,"dealer_id,nickname,dealer_name,personalcontactno,email,isComplete,payment_status,isApproved,isActive,auto_approve_product,updatesviewbyadmin,updatedon","isApproved='0' order by dealer_id DESC");
$getvendor_approved = selectQuery(VENDOR,"dealer_id,nickname,dealer_name,personalcontactno,email,isComplete,payment_status,isApproved,isActive,auto_approve_product,updatesviewbyadmin,updatedon,bulk_import","isApproved='1' and isActive='1' order by priority Asc");
$getvendor_deactivated = selectQuery(VENDOR,"dealer_id,nickname,dealer_name,personalcontactno,email,isComplete,payment_status,isApproved,isActive,auto_approve_product,updatesviewbyadmin,updatedon","isApproved='1' and isActive='0' order by dealer_id DESC"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo SITENAME; ?> : Manage Vendor</title>
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
            <div class="card border-bottom-0 mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center border-bottom-0"><h2 class="card-head-title">Vendor Details</h2></div>
                <div id="accordion" class="accordion">
                    <div class="card mb-0 rounded-0 border-left-0 border-right-0">
                        <div class="cc-cursor-pointer p-0"><a class="card-title cc-font-weight-6 card-header d-block" data-toggle="collapse" href="#collapseOne">Vendor Approval Pending List</a></div>
                        <div id="collapseOne" class="card-body collapse show vend-app-pend-coll" data-parent="#accordion">
                            <div id="Pending-Vendor">
                                <table class="table table-bordered ven-data-table w-100"  id="pending_vendor_table">
                                    <thead><tr><th>#</th><th>Name (Profile ID)</th><th>Contact</th><th>Profile</th><th>Payment</th><? if($username!=""&&$pasword!=""){?><th>Shiprocket Pickup Address ID</th><?} ?><th>Approved?</th><th>Delete</th></tr></thead>
                                    <tbody>
                                        <?php for($i=0;$i<count($getvendor);$i++){ 
                                             $getcurrplan = selectQuery(VENDORPLANSELECTED,"payment_status,plan_to","sel_id=".$getvendor[$i]['plan']);
                                             $planstatus=((count($getcurrplan)&&($getcurrplan[0]['plan_to']&&date("Y-m-d",strtotime($getcurrplan[0]['plan_to']))<date("Y-m-d")))||$getvendor[$i]['payment_status']!="Received"?"Expired":"Active"); ?>
                                        <tr>
                                            <td><?php echo $i+1; ?> <?php if($getvendor[$i]['updatesviewbyadmin']=="Pending"){?> <abbr title="Profile Updated on <?php echo $getvendor[$i]['updatedon']; ?>"><i class="fa fa-exclamation-circle" aria-hidden="true" style="color:red"></i></abbr><? } ?></td>
                                            <td style="<?=($planstatus=='Expired'?'color:red':''); ?>"><a href="editvendor.php?vendor=<?php echo base64_encode($getvendor[$i]['dealer_id']); ?>"><?php echo $getvendor[$i]['dealer_name']; ?></a><br> (<?php if($getvendor[$i]['nickname']!="")echo $getvendor[$i]['nickname'];else echo "Not Defined" ?>)
                                            <?=($planstatus=='Expired'?'<br>(Expired)':''); ?></td>
                                            <td>Mobile : <?php echo $getvendor[$i]['personalcontactno']; ?><br>Email : <?php echo $getvendor[$i]['email']; ?></td>
                                            <td><?php if($getvendor[$i]['isComplete']==1){?><span class="badge badge-success"><i class="fa fa-check"></i> Complete</span><? } else{ ?><span class="badge badge-danger"><i class="fa fa-times" aria-hidden="true"></i>
                                            In-complete</span><? } ?></td>
                                            <td><? if($getvendor[$i]['payment_status']=="Received"){?> <span class="badge badge-success"><i class="fa fa-check"></i> Received</span> <? }else{?> <span class="badge badge-danger"><i class="fa fa-times" aria-hidden="true"></i> Pending</span> <?}?></td>
                                              <? if($username!=""&&$pasword!=""){ ?><td>
                                            <?
                                                 $vendor="v".$getvendor[$i]['dealer_id'];
                                                  $pickups=$ship->getPickups($token);
                                                     $pickupid = array_search($vendor, array_column($pickups, 'pickup_location'));
                                                 if(!$pickupid){ ?> <button type="button" class="btn btn-xs btn-primary" onclick="setpickup('<?=$getvendor[$i]['dealer_id'];?>')">Set Pickup Address</button> <? }else{echo $vendor;  }
                                                     $pickupdetails=$pickups[$pickupid];
                                            ?></td>
                                            <? } ?>
                                            <td><label class="switch btn btn-primary"><input type="checkbox" class="tg" data-toggle="toggle" data-on="Approved" data-off="Pending" data-id="<?php echo $getvendor[$i]['dealer_id']; ?>" id="checkbox0_<?php echo $getvendor[$i]['dealer_id']; ?>" data-width="100" data-height="30" name="checkbox0_<?php echo $getvendor[$i]['dealer_id']; ?>" <? if($getvendor[$i]['isApproved']==1){echo "checked";}else{echo "";} ?> onchange="tg0chng('<?php echo $getvendor[$i]['dealer_id']; ?>','checkbox0_<?php echo $getvendor[$i]['dealer_id']; ?>','loader0_<?php echo $getvendor[$i]['dealer_id']; ?>');"><span class="slider round"><span class="on">Active</span><span class="off">Deactive</span></span></label><span class="loader0_<?php echo $getvendor[$i]['dealer_id']; ?>"></span></td>
                                            <td><button type="button" onclick="del_vendor('<?php echo $getvendor[$i]['dealer_id']; ?>')" class="deletebtn btn btn-danger btn-sm"><i class="fa fa-trash-o"></i></button></td>
                                        </tr>
                                        <? } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="cc-cursor-pointer"><a class="card-title cc-font-weight-6 card-header d-block collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">Approved Vendor List</a></div>
                        <div id="collapseTwo" class="card-body collapse appr-vend-coll" data-parent="#accordion">
                            <div id="approved-Vendor">
                                <table class="table table-bordered ven-data-table w-100" id="approve_vendor_sort">
                                    <thead><tr><th>#</th><th>Name (Profile ID)</th><th>Contact</th><th>Profile</th><th>Payment</th><? if($username!=""&&$pasword!=""){?><th>Shiprocket Pickup Address ID</th><?} ?><th>Active</th><th>Auto Approve Product</th>
                                       <th>Bulk Import</th> <th>Delete</th></tr>
                                    </thead>
                                    <tbody>
                                        <?php for($i=0;$i<count($getvendor_approved);$i++){ 
                                             $getcurrplan = selectQuery(VENDORPLANSELECTED,"payment_status,plan_to","sel_id=".$getvendor_approved[$i]['plan']);
                                             $planstatus=((count($getcurrplan)&&($getcurrplan[0]['plan_to']&&date("Y-m-d",strtotime($getcurrplan[0]['plan_to']))<date("Y-m-d")))||$getvendor_approved[$i]['payment_status']!="Received"?"Expired":"Active"); ?>
                                            <tr class="approved_vendor_list" data-id="<?php echo $getvendor_approved[$i]['dealer_id']; ?>">
                                            <td><?php echo $i+1; ?> <?php if($getvendor_approved[$i]['updatesviewbyadmin']=="Pending"){?> <abbr title="Profile Updated on <?php echo $getvendor_approved[$i]['updatedon']; ?>"><i class="fa fa-exclamation-circle" aria-hidden="true" style="color:red"></i></abbr><? } ?></td>
                                            <td style="<?=($planstatus=='Expired'?'color:red':''); ?>"><a href="editvendor.php?vendor=<?php echo base64_encode($getvendor_approved[$i]['dealer_id']); ?>"><?php echo $getvendor_approved[$i]['dealer_name']; ?></a><br> (<?php if($getvendor_approved[$i]['nickname']!="")echo $getvendor_approved[$i]['nickname'];else echo "Not Defined" ?>)
                                            <?=($planstatus=='Expired'?'<br>(Expired)':''); ?></td>
                                            <td>Mobile : <?php echo $getvendor_approved[$i]['personalcontactno']; ?><br>Email : <?php echo $getvendor_approved[$i]['email']; ?></td>
                                            <td><?php if($getvendor_approved[$i]['isComplete']==1){?><span class="badge badge-success"><i class="fa fa-check"></i> Complete</span><? } else{ ?><span class="badge badge-danger"><i class="fa fa-times" aria-hidden="true"></i> In-complete</span><? } ?></td>
                                            <td><? if($getvendor_approved[$i]['payment_status']=="Received"){?> <span class="badge badge-success"><i class="fa fa-check"></i> Received</span> <? }else{?> <span class="badge badge-danger"><i class="fa fa-times" aria-hidden="true"></i> Pending</span> <?}?></td>
                                          <? if($username!=""&&$pasword!=""){ ?><td>
                                            <?
                                                 $vendor="v".$getvendor_approved[$i]['dealer_id'];
                                                  $pickups=$ship->getPickups($token);
                                                     $pickupid = array_search($vendor, array_column($pickups, 'pickup_location'));
                                                 if(!$pickupid){ ?> <button type="button" class="btn btn-xs btn-primary" onclick="setpickup('<?=$getvendor_approved[$i]['dealer_id'];?>')">Set Pickup Address</button> <? }else{echo $vendor;  }
                                                     $pickupdetails=$pickups[$pickupid];
                                            ?></td>
                                            <? } ?>
                                            <td><label class="switch btn btn-primary"><input type="checkbox" class="tg0" data-toggle="toggle" data-on="Active" data-off="De-activate" data-id="<?php echo $getvendor_approved[$i]['dealer_id']; ?>" data-width="100" data-height="30" id="checkbox0_<?php echo $getvendor_approved[$i]['dealer_id']; ?>" name="checkbox0_<?php echo $getvendor_approved[$i]['dealer_id']; ?>" <? if($getvendor_approved[$i]['isActive']==1){echo "checked";}else{echo "";} ?> onchange="tg1chng('<?php echo $getvendor_approved[$i]['dealer_id']; ?>','checkbox0_<?php echo $getvendor_approved[$i]['dealer_id']; ?>');"><span class="slider round"><span class="on">Active</span><span class="off">Deactive</span></span></label></td>
                                            <td><div class="custom-control custom-checkbox mb-3"><input type="checkbox" class="tg1 custom-control-input" data-toggle="toggle" data-on="On" data-off="Off" data-id="<?php echo $getvendor_approved[$i]['dealer_id']; ?>" data-width="100" data-height="30" id="checkbox1_<?php echo $getvendor_approved[$i]['dealer_id']; ?>" name="checkbox0_<?php echo $getvendor_approved[$i]['dealer_id']; ?>" <? if($getvendor_approved[$i]['auto_approve_product'] == 1){echo "checked";} ?> onchange="auto_approve('<?php echo $getvendor_approved[$i]['dealer_id']; ?>','checkbox1_<?php echo $getvendor_approved[$i]['dealer_id']; ?>');"> <label class="custom-control-label" for="checkbox1_<?php echo $getvendor_approved[$i]['dealer_id']; ?>"></label></div></td>
                                            <td><div class="custom-control custom-checkbox mb-3"><input type="checkbox" class="tg1 custom-control-input" data-toggle="toggle" data-on="On" data-off="Off" data-id="<?php echo $getvendor_approved[$i]['dealer_id']; ?>" data-width="100" data-height="30" id="checkbox2_<?php echo $getvendor_approved[$i]['dealer_id']; ?>" name="checkbox2_<?php echo $getvendor_approved[$i]['dealer_id']; ?>" <? if($getvendor_approved[$i]['bulk_import'] == 1){echo "checked";} ?> onchange="set_bulkimport('<?php echo $getvendor_approved[$i]['dealer_id']; ?>','checkbox2_<?php echo $getvendor_approved[$i]['dealer_id']; ?>');"> <label class="custom-control-label" for="checkbox2_<?php echo $getvendor_approved[$i]['dealer_id']; ?>"></label></div></td>

                                            <td><button type="button" onclick="del_vendor('<?php echo $getvendor_approved[$i]['dealer_id']; ?>')" class="deletebtn btn btn-danger btn-sm"><i class="fa fa-trash-o"></i></button></td>
                                        </tr>
                                        <? } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="cc-cursor-pointer"><a class="card-title cc-font-weight-6 card-header d-block collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree"> Deactivated Vendor List </a></div>
                        <div id="collapseThree" class="collapse card-body deact-vend-coll" data-parent="#accordion">
                            <div id="deactivated-Vendor">
                                <table class="table table-bordered ven-data-table w-100" id="deactivated-Vendor-table">
                                    <thead><tr><th>#</th><th>Name (Profile ID)</th><th>Contact</th><th>Profile</th><th>Payment</th><? if($username!=""&&$pasword!=""){?><th>Shiprocket Pickup Address ID</th><?} ?><th>Active</th><th>Delete</th></tr></thead>
                                    <tbody>
                                        <?php for($i=0;$i<count($getvendor_deactivated);$i++){ 
                                            $getcurrplan = selectQuery(VENDORPLANSELECTED,"payment_status,plan_to","sel_id=".$getvendor_deactivated[$i]['plan']);
                                            $planstatus=((count($getcurrplan)&&($getcurrplan[0]['plan_to']&&date("Y-m-d",strtotime($getcurrplan[0]['plan_to']))<date("Y-m-d")))||$getvendor_deactivated[$i]['payment_status']!="Received"?"Expired":"Active"); ?>
                                        <tr>
                                            <td><?php echo $i+1;?> <?php if($getvendor_deactivated[$i]['updatesviewbyadmin']=="Pending"){?> <abbr title="Profile Updated on <?php echo $getvendor_deactivated[$i]['updatedon']; ?>"><i class="fa fa-exclamation-circle" aria-hidden="true" style="color:red"></i></abbr><? } ?></td>
                                            <td style="<?=($planstatus=='Expired'?'color:red':''); ?>"><a href="editvendor.php?vendor=<?php echo base64_encode($getvendor_deactivated[$i]['dealer_id']); ?>"><?php echo $getvendor_deactivated[$i]['dealer_name']; ?></a><br> (<?php if($getvendor_deactivated[$i]['nickname']!="")echo $getvendor_deactivated[$i]['nickname'];else echo "Not Defined" ?>)
                                            <?=($planstatus=='Expired'?'<br>(Expired)':''); ?></td>
                                            <td>Mobile : <?php echo $getvendor_deactivated[$i]['personalcontactno']; ?><br>Email : <?php echo $getvendor_deactivated[$i]['email']; ?></td>
                                            <td><?php if($getvendor_deactivated[$i]['isComplete']==1){?><span class="badge badge-success"><i class="fa fa-check"></i> Complete</span><? } else { ?><span class="badge badge-success"><i class="fa fa-times" aria-hidden="true"></i> In-complete</span><? } ?></td>
                                            <td><? if($getvendor_deactivated[$i]['payment_status']=="Received"){?> <span class="badge badge-success"><i class="fa fa-check"></i> Received</span> <? }else{?> <span class="badge badge-danger"><i class="fa fa-times" aria-hidden="true"></i> Pending</span> <?}?></td>
                                             <? if($username!=""&&$pasword!=""){ ?><td>
                                            <?
                                                 $vendor="v".$getvendor_deactivated[$i]['dealer_id'];
                                                  $pickups=$ship->getPickups($token);
                                                     $pickupid = array_search($vendor, array_column($pickups, 'pickup_location'));
                                                 if(!$pickupid){ ?> <button type="button" class="btn btn-xs btn-primary" onclick="setpickup('<?=$getvendor_deactivated[$i]['dealer_id'];?>')">Set Pickup Address</button> <? }else{echo $vendor;  }
                                                     $pickupdetails=$pickups[$pickupid];
                                            ?></td>
                                            <? } ?>
                                            <td><label class="switch btn btn-primary"><input type="checkbox" class="tg0" data-toggle="toggle" data-on="Active" data-off="De-activate" data-id="<?php echo $getvendor_deactivated[$i]['dealer_id']; ?>" data-width="100" data-height="30" id="checkbox0_<?php echo $getvendor_deactivated[$i]['dealer_id']; ?>" name="checkbox0_<?php echo $getvendor_deactivated[$i]['dealer_id']; ?>" <? if($getvendor_deactivated[$i]['isActive']==1){echo "checked";}else{echo "";} ?> onchange="tg1chng('<?php echo $getvendor_deactivated[$i]['dealer_id']; ?>','checkbox0_<?php echo $getvendor_deactivated[$i]['dealer_id']; ?>');"><span class="slider round"><span class="on">Active</span><span class="off">Deactive</span></span></label></td>
                                            <td><button type="button" onclick="del_vendor('<?php echo $getvendor_deactivated[$i]['dealer_id']; ?>')" class="deletebtn btn btn-danger btn-sm"><i class="fa fa-trash-o"></i></button></td>
                                        </tr>
                                        <? } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
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
<script src="<?php echo SITEURL; ?>/js/datepicker/moment.js"></script>
<script src="<?php echo SITEURL; ?>/js/datepicker/bootstrap-datetimepicker.min.js"></script>
<script>
$(document).ready(function(){
$('.collapse').on('shown.bs.collapse', function(){
    $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
});
var pendingvendor = $("#pending_vendor_table").DataTable({
    "scrollX": true, columnDefs: [{
        "render": function (data, type, row){
            var expinpstatus = (type === 'export' ? ($(data).find('input[type="checkbox"]').prop("checked")===true ? 'Active' : 'Deactive') : data);
            return expinpstatus;
        },
        "targets": [5]
    }],
    buttons: [{
        extend: 'excelHtml5',
        exportOptions: {
            columns: [1,2,3,4,5],
            orthogonal: 'export'
        }
    }]
});
pendingvendor.buttons().container().appendTo('.vend-app-pend-coll .dataTables_wrapper .col-md-6:eq(0) .dataTables_length');
    
var approvevendor = $("#approve_vendor_sort").DataTable({
    "scrollX": true, columnDefs: [{
        "render": function (data, type, row) {
            var appvendexpinpstatus = (type === 'export' ? ($(data).find('input[type="checkbox"]').prop("checked")===true ? 'Active' : 'Deactive') : data);
            return appvendexpinpstatus;
        },
        "targets": [5,6]
    }],
    buttons: [{
        extend: 'excelHtml5',
        exportOptions: {
            columns: [1,2,3,4,5,6],
            orthogonal: 'export'
        }
    }]
});
approvevendor.buttons().container().appendTo('.appr-vend-coll .dataTables_wrapper .col-md-6:eq(0) .dataTables_length');
    
var deactivatedvendor = $("#deactivated-Vendor-table").DataTable({
    "scrollX": true, columnDefs: [{
        "render": function (data, type, row) {
            var deaveninpstatus = (type === 'export' ? ($(data).find('input[type="checkbox"]').prop("checked")===true ? 'Active' : 'Deactive') : data);
            return deaveninpstatus;
        },
        "targets": [5]
    }],
    buttons: [{
        extend: 'excelHtml5',
        exportOptions: {
            columns: [1,2,3,4,5],
            orthogonal: 'export'
        }
    }]
});
deactivatedvendor.buttons().container().appendTo('.deact-vend-coll .dataTables_wrapper .col-md-6:eq(0) .dataTables_length');

$(".buttons-excel").addClass("ml-1");
});
function del_vendor(i){ del_alertbox("Do you really want to delete this vendor?", i,"del_vendor_db"); }
function del_vendor_db(id,type){
    info = {vendor_id:id,action:"Delete_vednor"}
    $.ajax({
        type:"POST",
        url:"ajaxdata.php",
        data:info,
        success:function(response){
            response=response.trim();
            if(response== "1"){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Vendor deleted successfully").delay(3000).fadeOut();
                $("#del_popup").modal("hide"); 
                $("#Pending-Vendor").load( " #Pending-Vendor");
                setTimeout(function(){ $('.ven-data-table').DataTable(); }, 1000);
            }
            else if(response=="0"){
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error.. Try again later").delay(3000).fadeOut();
            }
        }
    })
}
function tg0chng(v1, v2, v3){
    var pid = v1; var getid = v2;
    var c = $("#" + getid + ":checked").val();
    if(c == "on") { status = 1; msg = "approved"; } else{ status = 0; msg = "not approved"; }
    var info = {pid: pid,status: "1",action:"approve_vendor"};
    $.ajax({
        type: "POST",
        url: "ajaxdata.php",
        data: info,
        success: function(response){
            if(response == 1){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Vendor "+msg).delay(3000).fadeOut();
            } else{
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error.. Try again later").delay(3000).fadeOut();
            }
        }
    });
}
function tg1chng(v1,v2){
    var pid = v1; var getid = v2;
    var c = $("#"+getid+":checked").val();
    if(c=="on"){ status = 1; msg="activated"; } else{  status = 0; msg="Deactivated"; }
    var info = {pid:pid,status:status,action:"Vendor_status"};
    $.ajax({
        type:"POST",
        url:"ajaxdata.php",
        data:info,
        success:function(response){
            if(response==1){    
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Vendor "+msg+ " successfully").delay(3000).fadeOut();     
                $("#approved-Vendor").load(" #approved-Vendor");
                $("#deactivated-Vendor").load(" #deactivated-Vendor");
                setTimeout(function(){ $('.ven-data-table').DataTable(); }, 1000);
            } else{    
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error.. Try again later").delay(3000).fadeOut();
            }
        }
    });
}
function auto_approve(v1,v2){
    var pid = v1; var getid = v2;
    var c = $("#"+getid+":checked").val();
    if(c=="on"){status= 1; msg= "This vendor product will get auto-approved automatically"; } else { status= 0; msg = "This vendor product will not get auto-approved automatically"} 
    var info = {pid:pid,status:status,action:"Vendor_auto_approve_product"};
    $.ajax({
        type:"POST",
        url:"ajaxdata.php",
        data:info,
        success:function(response){
            if(response==1){    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html(msg).delay(3000).fadeOut();     
            } else{    
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error.. Try again later").delay(3000).fadeOut();
            }
        }
    });
}



function set_bulkimport(v1,v2){
    var pid = v1; var getid = v2;
    var c = $("#"+getid+":checked").val();
    if(c=="on"){status= 1; msg= "Bulk Import is activated for this vendor"; } else { status= 0; msg = "Bulk Import is De-activated for this vendor"} 
    var info = {pid:pid,status:status,action:"bulk_Import"};
    $.ajax({
        type:"POST",
        url:"ajaxdata.php",
        data:info,
        success:function(response){
            if(response==1){    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html(msg).delay(3000).fadeOut();
            } else{
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error.. Try again later").delay(3000).fadeOut();
            }
        }
    });
}

function setpickup(vendorid){
   var info = {vendor:vendorid,action:"add_pickup"};
    $.ajax({
        type:"POST",
        url:"ajaxdata.php",
        data:info,
        success:function(response){
         if(response==1){
               $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Pickup Address Added").delay(3000).fadeOut();
              setTimeout(function(){ $('.ven-data-table').DataTable(); }, 1000);
         }else{  $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html(response).delay(3000).fadeOut();  }
        }
    });
}
</script>
</body>
</html>