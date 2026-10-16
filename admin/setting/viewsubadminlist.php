<?php include("../../includes/configuration.php");
$getsubadmin = selectQuery(ADMIN,"u_mob,u_email,u_id,username,isActive","utype='subadmin'"); ?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : SubAdmin List</title>
    <?php include('../commoncss.php') ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/dataTable/dataTables.bootstrap4.min.css">
</head>
<body>
<div class="page-body-wrapper">
    <?php include('../header.php') ?>
    <?php include('../sidebar.php') ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2"><div><h5 class="card-head-title mr-3 mr-sm-0 mb-2 mb-sm-0">SubAdmin List</h5></div><div class="btn-actions-pane-right"><a href="setting.php" class="btn btn-primary btn-sm mr-1">Add New Subadmin</a><a href="<?php echo ADMINURL;?>presetting.php" class="btn btn-secondary btn-sm">Back</a></div></div>
                <div class="card-body">
                    <div class="msgs"></div>
                    <table class="subadmin table table-bordered w-100" id="subadminlist">
                        <thead><tr><th>#</th><th>Username</th><th>Mobile No</th><th>Email ID</th><th>View Login</th><th>Active / Deactive</th><th class="text-center">Delete</th></tr></thead>
                        <tbody>
                            <?php for($i=0;$i<count($getsubadmin);$i++){?>
                            <tr>
                                <td class="td2 row-index-tr"><?php echo $i+1; ?></td>
                                <td><a href="<?php echo ADMINURL;?>setting/setting.php?id=<?php echo base64_encode($getsubadmin[$i]['u_id']); ?>"><?php echo $getsubadmin[$i]['username']; ?></a></td>
                                <td><?php echo $getsubadmin[$i]['u_mob']?></td>
                                <td><?php echo $getsubadmin[$i]['u_email']?></td>
                                <td><a href="<?php echo ADMINURL; ?>setting/subadminlastlog.php?id=<?php echo base64_encode($getsubadmin[$i]['u_id']) ?>">View last login</a></td>
                                <td> <div class="custom-control custom-checkbox mb-3"><input type="checkbox" class="custom-control-input" id="act_deact<?php echo $getsubadmin[$i]['u_id'] ?>" <?php if($getsubadmin[$i]['isActive'] == 1) {echo "checked"; } ?> onchange="act_deact('<?php echo $getsubadmin[$i]['u_id'] ?>')"  ><label class="custom-control-label" for="act_deact<?php echo $getsubadmin[$i]['u_id'] ?>"></label></div></td>
                                <td class="text-center del-btn-tr"><button type="button" onclick="del('<?php echo $getsubadmin[$i]['u_id']; ?>')" class="deletebtn btn btn-danger btn-sm"><i class="fa fa-trash-o"></i></button></td>
                            </tr>
                            <? } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php include('../footer.php');?>
    </div>
</div>
<script src="<?php echo SITEURL; ?>/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function(){$('.subadmin').DataTable({ "scrollX": true });})
function act_deact(v1){
    var requestedid = v1;
    var c = $("#act_deact"+requestedid+":checked").val();
    if(c=="on"){ status = 1; res="activated";} else{status = 0; res="deactivated"; }
    var info = {requestedid:requestedid,status:status,action:"active_deactive"};
    $.ajax({
        type:"POST",
        url:"ajaxdata.php",
        data:info,
        success:function(response){
            if(response==1){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Subadmin " +res).delay(3000).fadeOut(); $("#subadminlist").load( " #subadminlist");
            }
            else{
            $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("ERROR..").delay(3000).fadeOut();
            }
        }
    });
}
function del(id){ del_alertbox("Do you really want to delete this Subadmin?", id,"del_subadmin_db"); }
function del_subadmin_db(id,type){
    info = {subadmin_id:id,action:"Delete_subadmin"}
    $.ajax({
        type:"POST",
        url:"ajaxdata.php",
        data:info,
        success:function(response){
            response = response.trim();
            if(response == "1"){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Attribute deleted successfully").delay(3000).fadeOut(); $("#del_popup").modal("hide"); $( "#subadminlist").load(" #subadminlist" );
            } else if(response=="0"){
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error.. Try again later").delay(3000).fadeOut();
            }
        }
    })
}
</script>
</body>
</html>