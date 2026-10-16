<?php include("../../includes/configuration.php");
$getstaff = selectQuery(SUPPORTSTAFF,"*","acc_type!='Client' AND isdel='0' order by emp_name ASC"); ?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Manage Staffs Members</title>
    <?php include('../commoncss.php') ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/dataTable/dataTables.bootstrap4.min.css">
</head>
<body>
<div class="page-body-wrapper">
    <?php include('../header.php') ?>
    <?php include('../sidebar.php') ?>
      <div class="main-panel">
        <div class="dashbody">
            <?php include('supp-nav.php') ?>
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2"><div><h5 class="card-head-title">Manage Staffs Members</h5></div><div class="btn-actions-pane-right"><a href="addstaff.php" class="btn btn-primary btn-sm">Add New Staff</a></div></div>
                <div class="card-body">
                    <div class="table-responsive" id="tshufle">
                        <table class="mng-staff-manager table table-bordered">
                            <thead><tr><th>#</th><th>Staff Name</th><th>Username</th><th>Status</th><th>Group</th><th>Department</th><th>Created</th><th>Last Login</th></tr></thead>
                            <tbody>
                                <?php for($i=0;$i<count($getstaff);$i++){ ?>
                                <tr>
                                    <td class="td2"><?=$i+1; ?></td>
                                    <td><a href="editemp.php?emp=<?=base64_encode($getstaff[$i]['emp_id']) ?>"><?=$getstaff[$i]['emp_name']; ?></a></td>
                                    <td><?=($getstaff[$i]['username']!=""?$getstaff[$i]['username']:"[Not Defined]");?></td>
                                    <td><?php
                                    if($getstaff[$i]['isActive']==1){
                                        echo "<span class='text-success' style='font-weight:bold'>Active</span>";
                                    } else{ echo "<span class='text-danger'>Deactive</span>"; } ?>
                                    </td>
                                    <td><?php if($getstaff[$i]['emp_group']!=""&&$getstaff[$i]['emp_group']!="0"){
                                    $getstaffgroup=selectQuery(SUPPORTSTAFFGROUP,"group_name","group_id=".$getstaff[$i]['emp_group']);
                                    echo $getstaffgroup[0]['group_name'];
                                    } else{ echo "<span class='text-danger'>[Not Defined]</span>"; } ?></td>
                                    <td><?php if($getstaff[$i]['department']!=""&&$getstaff[$i]['department']!="0"){
                                    $getstaffdept=selectQuery(SUPPORTDEPT,"dept_name","dept_id=".$getstaff[$i]['department']);
                                    echo $getstaffdept[0]['dept_name']; } else{ echo "<span class='text-danger'>[Not Defined]</span>"; } ?></td>
                                    <td><?=$getstaff[$i]['added_on']; ?></td>
                                    <td><?=($getstaff[$i]['last_login']!=""?$getstaff[$i]['last_login']:"-"); ?></td>
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
<script src="<?php echo SITEURL; ?>/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function(){ $('.mng-staff-manager').DataTable(); });
</script>
</body>
</html>