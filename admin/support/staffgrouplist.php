<?php
     include("../../includes/configuration.php");
     $getstaffgrp=selectQuery(SUPPORTSTAFFGROUP,"*","isdel='0'");
?>
<!doctype html>
<html lang='en'>
<head>
    <title>Customer Helpdesk - Manage Staff Groups</title>
    <meta charset='utf-8'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="<?php echo METAKEYWORDS; ?>"  >
    <meta name="description" content ="<?php echo METADESCRIPTION; ?>">
    <?php include('../commoncss.php') ?>
    <link rel="stylesheet" href="<?=SITEURL; ?>/css/dataTable/dataTables.bootstrap4.min.css">
</head>
<body>
<div class="page-body-wrapper">
    <?php include('../header.php') ?>
    <?php include('../sidebar.php') ?>
      <div class="main-panel">
        <div class="dashbody">
            <?php include('supp-nav.php') ?>
            <div class="card">

                <div class="card-header sec-card-head justify-content-between align-items-center">
                    <div><h5 class="card-head-title">Manage Staff Groups</h5></div>
                    <div class="btn-actions-pane-right"><a href="addstaffgroup.php" class="btn btn-primary btn-sm"> Add New Staff Group</a>  </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive" id="tshufle">
                        <table class="example display table table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Group Name</th>
                                <th>Active / Deactive</th>
                                <th class="text-center">Delete</th>
                             </tr>
                            </thead>
                            <tbody class="text-muted">
                                <?php for($i=0;$i<count($getstaffgrp);$i++){
                                $groupid=$getstaffgrp[$i]['group_id'];$name=$getstaffgrp[$i]['group_name'];  $active= $getstaffgrp[$i]['group_status'];
                                ?>
                                <tr>
                                    <td class="td2"><?=$i+1; ?></td>
                                    <td><a href="viewstaffgrodetail.php?gid=<?=base64_encode($groupid) ?>"><?=$name; ?></a></td>
                                    <td>
                                    <label class="switch btn btn-primary">
                                            <input type="checkbox" class="tg" data-toggle="toggle" data-id="<?=$groupid; ?>" id="checkbox_<?=$groupid; ?>" name="checkbox_<?=$groupid; ?>" <?=($active==1?"checked":""); ?> >
                                            <span class="slider round"><span class="on">Active</span><span class="off">Deactive</span></span>
                                        </label>
                                   </td>
                                    <td ><a onclick="del('<?=$groupid; ?>')" class="deletebtn btn btn-danger btn-sm"><i class="fa fa-trash-o"></i></a></td>
                                </tr>
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

<script src="<?=SITEURL; ?>/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?=SITEURL; ?>/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        $('.example').DataTable({  "lengthMenu": [10, 25, 50, 75, 100]});
        $(".tg").on("change", function() {
            var uid = $(this).attr('data-id');
            var getid = $(this).attr('id');
            var c = $("#" + getid + ":checked").val();
            var action = "statusupdate";
            if (c == "on") {
                var info = {uid: uid,status: "1",action: action };
                $.ajax({
                    type: "POST",
                    url: "staffgroupdata.php",
                    data: info,
                    success: function(response) {
                        if (response == 1) {
                           $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Group Activated Successfully").delay(1000).fadeOut();
                        } else {
                             $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("ERROR..").delay(1000).fadeOut();
                        }
                    }
                });
            } else {
                var info = {uid: uid,status: "0",action: action };
                $.ajax({
                    type: "POST",url: "staffgroupdata.php",data: info,
                    success: function(response) {
                        if (response == 1) {
                             $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Group Deactivated Successfully").delay(1000).fadeOut();
                        } else {
                            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("ERROR..").delay(1000).fadeOut();
                        }
                    }
                });
            }
        });
    })
     function del(i){
          msg= "Do u really want to delete this Group? "+
          "Info : Deleting group will affect the staff members belonging to the department and group access, so please redefine the department access";
            del_alertbox(msg, i,"del_group");
        }
    function del_group(id) {
        var uid = id;  var action = "del"
         var info = {uid: id,action: action };
                $.ajax({
                    type: "POST",url: "staffgroupdata.php",  data: info,
                    success: function(response) {
                        if (response == 1) {
                           $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Staff Group Deleted Successfully").delay(1000).fadeOut();
                     $("#tshufle").load(  " #tshufle");
                        } else {
                            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("ERROR..").delay(1000).fadeOut();
                        }
                    }
                });

    }
</script>
</body>
</html>