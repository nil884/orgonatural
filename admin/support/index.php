<?php
     include("../../includes/configuration.php");
     $getdept=selectQuery(SUPPORTDEPT,"dept_id,dept_name,dept_mgr,isActive","isdel='0'");
?>
<!doctype html>
<html lang='en'>
<head>
    <title>Customer Helpdesk Configuration</title>
    <meta charset='utf-8'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="<?=METAKEYWORDS; ?>"  >
    <meta name="description" content ="<?=METADESCRIPTION; ?>">
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
               <div><h5 class="card-head-title">Customer Helpdesk</h5></div>
            </div>
            <div class="card-body">
                <div class="table-responsive tshufle">
                    <table class="example display table table-striped">
                        <thead>
                            <tr><th>#</th><th>Department Name</th><th>Department Manager </th><th>Active / Deactive</th><th >Delete</th></tr>
                        </thead>
                        <tbody class="text-muted">
                            <?php
                               for($i=0;$i<count($getdept);$i++){
                               $deptid=$getdept[$i]['dept_id']; $deptmgr=$getdept[$i]['dept_mgr']; $name=$getdept[$i]['dept_name'];$active=$getdept[$i]['isActive'];
                               ?>
                               <tr>
                                    <td class="td2"><?=$i+1; ?></td>
                                    <td><a href="viewdept.php?deptid=<?=base64_encode($deptid) ?>"><?=$name; ?></a></td>
                                    <td><?php
                                    if($deptmgr!=""){
                                           $dept_mgr=selectQuery(SUPPORTSTAFF,"emp_name","emp_id=".$deptmgr." and isActive='1' and isDel='0'");
                                           echo $dept_mgr[0]['emp_name'];
                                    }
                                    else{ echo "[Not Defined]"; }
                                     ?>
                                    </td>
                                    <td>
                                      <label class="switch btn btn-primary">
                                            <input type="checkbox" class="tg" data-toggle="toggle" data-id="<?=$deptid; ?>" id="checkbox_<?=$deptid; ?>" name="checkbox_<?=$deptid; ?>" <?=($active==1?"checked":""); ?> >
                                            <span class="slider round"><span class="on">Active</span><span class="off">Deactive</span></span>
                                        </label>
                                    </td>
                                    <td ><a onclick="del('<?=$deptid; ?>')" class="deletebtn btn btn-danger btn-sm"><i class="fa fa-trash-o"></i></a></td>
                               </tr>
                            <?  } ?>
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
        $('.example').DataTable({ "lengthMenu": [10, 25, 50, 75, 100]});

        $(".tg").on("change", function() {
            var uid = $(this).attr('data-id');
            var getid = $(this).attr('id');
            var c = $("#" + getid + ":checked").val();
            if (c == "on") {
                var info = { uid: uid, status: "1"};
                $.ajax({
                    type: "POST",url: "statuschng.php", data: info,
                    success: function(response) {
                        if (response == 1) {
                             $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Department Activated Successfully").delay(1000).fadeOut();
                        } else {
                            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("ERROR..").delay(1000).fadeOut();
                             $("#tshufle").load(  " #tshufle");
                        }
                    }
                });
            } else {
                var info = {  uid: uid,status: "0" };
                $.ajax({
                    type: "POST",url: "statuschng.php",data: info,
                    success: function(response) {
                        if (response == 1) {
                            $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Department Deactivated Successfully").delay(1000).fadeOut();
                        } else {
                           $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("ERROR..").delay(1000).fadeOut();
                            $("#tshufle").load(  " #tshufle");
                        }
                    }
                });
            }
        });
    });
     function del(i){
          msg= "Do u really want to delete this Department? "+
          "Info : Deleting department will affect the staff members belonging to the department and group access, so please redefine the department access for such members";
            del_alertbox(msg, i,"del_dept");
        }
    function del_dept(id) {
        var info = { uid: id };
        $.ajax({
            type: "POST", url: "del.php",data: info,
            success: function(response) {
                if (response == 1) {
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Department Deleted Successfully").delay(1000).fadeOut();
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