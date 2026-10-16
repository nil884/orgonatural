<? include("../../includes/configuration.php");
$notification = selectQuery(NOTIFICATION,"*"," 1"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo SITENAME; ?> : Notification</title>
    <? include "../commoncss.php"; ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/dataTable/dataTables.bootstrap4.min.css">
</head>
<body>
<div class="page-body-wrapper">
    <? include '../header.php'; ?>
    <? include '../sidebar.php'; ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center"><h2 class="card-head-title">Notification Banner</h2></div>
                <div class="card-body">
                    <div class="notificaAlert alert alert-info mb-0">
                        <h6><b>Important :</b></h6>
                        <ul class="mb-0 pl-3">
                            <li class="mb-1">Notifications banner can be displayed at following three places -
                                <ul><li class="mb-1">Website`s Main Home Page</li><li class="mb-1">End Customer's My Account Page</li><li class="mb-1">All Available Pages (including all pages in website and all pages in end customer`s account)</li></ul>
                            </li>
                            <li class="mb-1">Only one notification on each page can be made active at a time</li>
                            <li>Special exception is made to display two different notifications for Home page and Customer's My Account Page</li>
                        </ul>
                    </div>
                </div>  
            </div> 
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center"><h5 class="card-head-title">Notification Banner</h5></div>                
                <div class="row">
                    <div class="col-md-12 notificationIcon">
                        <div class="card-body">
                            <div id="msgs"></div>
                            <form>
                                <div>
                                    <label class="mb-2">Select Notification Icon</label>
                                    <div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" checked="checked" name="notificationico"  class="notificationico custom-control-input" value="notification_icon1.png" id="notif-1"/>
                                            <label class="custom-control-label" for="notif-1"><img src="<?php echo SITEURL; ?>/img/projectimage/notification_icon1.png" alt="notif-1" width="17"/></label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" name="notificationico" class="notificationico custom-control-input" value="notification_icon2.png" id="notif-2"/>
                                            <label class="custom-control-label" for="notif-2"><img src="<?php echo SITEURL; ?>/img/projectimage/notification_icon2.png" alt="notif-2" width="17"/></label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" name="notificationico" class="notificationico custom-control-input" value="notification_icon3.png" id="notif-3"/>
                                            <label class="custom-control-label" for="notif-3"><img src="<?php echo SITEURL; ?>/img/projectimage/notification_icon3.png" alt="notif-3" width="17"/></label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" name="notificationico" class="notificationico custom-control-input" value="notification_icon4.png" id="notif-4"/>
                                            <label class="custom-control-label" for="notif-4"><img src="<?php echo SITEURL; ?>/img/projectimage/notification_icon4.png" alt="notif-4" width="17"/></label>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card-body py-0">
                            <div class="row">
                                <div class="col-12 form-group mb-2"><input type="text" placeholder="Enter Notification Text Max 600 Character Limit" class="form-control text-capitalize mb-1" id="notification" maxlength="600"></div>
                                <div class="col-12 form-group"><input type="text" placeholder="Enter Notification link Max 600 Character Limit" class="form-control" id="notificationlink" maxlength="600"></div>
                            </div>
                        </div>
                        <div class="card-footer py-2 text-right"><input type="button" value="Submit" class="btn btn-primary text-right" name="notification" id="notificationsubmit"></div>
                    </div>
                </div>
            </div>
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center"><h5 class="card-head-title">Notification List</h5></div>
                <div class="card-body">
                    <div id="notiflist-col">
                        <table class="example table table-bordered w-100" id="notification_table">
                            <thead><tr><th>#</th><th>Description</th><th>Status</th><th>Show On Home Page</th><th>Show In My Account</th><th>Show On All Pages</th><th>Delete</th></tr></thead>
                            <tbody>
                                <?php for($i=0;$i<count($notification);$i++){ ?>
                                <tr>
                                    <td><?php echo $i+1; ?></td>
                                    <td><?php echo $notification[$i]['notification']; ?></td>
                                    <td> 
                                        <label class="switch btn btn-primary">
                                            <input type="checkbox" data-id="<?php echo $notification[$i]['n_id']; ?>" data-width="100" data-height="30" id="checkbox0_<?php echo $notification[$i]['n_id']; ?>" name="checkbox0_<?php echo $notification[$i]['n_id']; ?>" <? if($notification[$i]['isActive']==1){echo "checked";}else{echo "";} ?> onchange="active('<?php echo $notification[$i]['n_id']; ?>','checkbox0_<?php echo $notification[$i]['n_id']; ?>','checkbox1_<?php echo $notification[$i]['n_id']; ?>','checkbox2_<?php echo $notification[$i]['n_id']; ?>','checkbox3_<?php echo $notification[$i]['n_id']; ?>');"> 
                                            <span class="slider round"><span class="on">Active</span><span class="off">Deactive</span></span>
                                        </label>
                                    </td>
                                    <td>
                                        <label class="switch btn btn-primary">
                                            <input type="checkbox" data-id="<?php echo $notification[$i]['n_id']; ?>" id="checkbox1_<?php echo $notification[$i]['n_id']; ?>" name="checkbox_<?php echo $notification[$i]['n_id']; ?>" <? if($notification[$i]['show_on_homepage']==1){echo "checked";}else{echo "";} ?> onchange="show_on_home('<?php echo $notification[$i]['n_id']; ?>','checkbox1_<?php echo $notification[$i]['n_id']; ?>');">
                                            <span class="slider round"><span class="on">Active</span><span class="off">Deactive</span></span>
                                        </label>
                                    </td>
                                    <td>
                                        <label class="switch btn btn-primary">
                                            <input type="checkbox" data-id="<?php echo $notification[$i]['n_id']; ?>" id="checkbox2_<?php echo $notification[$i]['n_id']; ?>" name="checkbox_<?php echo $notification[$i]['n_id']; ?>" <? if($notification[$i]['myaccount']==1){echo "checked";}else{echo "";} ?> onchange="show_in_account('<?php echo $notification[$i]['n_id']; ?>','checkbox2_<?php echo $notification[$i]['n_id']; ?>');">
                                            <span class="slider round"><span class="on">Active</span><span class="off">Deactive</span></span>
                                        </label>
                                    </td>
                                    <td>
                                        <label class="switch btn btn-primary">
                                            <input type="checkbox" data-id="<?php echo $notification[$i]['n_id']; ?>" id="checkbox3_<?php echo $notification[$i]['n_id']; ?>" name="checkbox_<?php echo $notification[$i]['n_id']; ?>" <? if($notification[$i]['show_on_all']==1){echo "checked";}else{echo "";} ?> onchange="show_on_all('<?php echo $notification[$i]['n_id']; ?>','checkbox3_<?php echo $notification[$i]['n_id']; ?>');"> 
                                            <span class="slider round"><span class="on">Active</span><span class="off">Deactive</span></span>
                                        </label>
                                    </td>
                                    <td><button type="button" onclick="del('<?php echo $notification[$i]['n_id']; ?>')" class="btn btn-danger btn-sm deletebtn"><i class="fa fa-trash-o"></i></button></td>
                                </tr>
                                <? } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <? include "../footer.php"; ?> 
    </div>    
</div> 
<script src="<?php echo SITEURL; ?>/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script>
function loaddatatable(){$('.example').DataTable({"scrollX": true});}
loaddatatable();
function del(i){
    msg = "Do you really want to delete this Notification";
    del_alertbox(msg, i,"del_notification");
}
function del_notification(id){
    var n_id = id;
    var info = {n_id: n_id, action: "del_notification" };
    $.ajax({
        type: "POST",
        url: "ajaxdata.php",
        data: info,
        success: function(response){
            if (response == 1) {
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html('Notification deleted successfully').delay(1000).fadeOut();
                $("#notiflist-col").load(location.href + " #notification_table"); setTimeout(function(){ loaddatatable();}, 500);
                $("#del_popup").modal('hide');
            } else {
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html('Please try after some time').delay(1000).fadeOut();
            }
        }
    });
} 
$("#notificationsubmit").click(function(){
    var notification = $("#notification").val();
    var notificationlink = $("#notificationlink").val();
    var ico = $(".notificationico:checked").val();
    if (notification != ""){
    var info = {
        notification: notification,
        notificationlink: notificationlink,
        ico: ico,
        action:"add_notification",
    };
    $.ajax({
        type : "POST",
        url : "ajaxdata.php", 
        data : info,
        success : function(response){
            if (response == 1) {
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Notification added successfully").delay(3000).fadeOut();
                //$("#notification , #notificationlink").val("");
                $("#notiflist-col").load(location.href + " #notification_table"); setTimeout(function(){ loaddatatable();}, 500);
            } else if(response == 2){
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Notification with same description allready exist").delay(3000).fadeOut();
            } else {
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error.. Try again later").delay(3000).fadeOut();
            }
        }
    });
    }else{
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter notification").delay(3000).fadeOut();
    }
})
function active(v1,v2){
    var uid = v1;
    var getid = v2;
    var c = $("#"+getid+":checked").val();
    if(c=="on"){ status = 1;  msg = "activated"; } else{ status = 0; msg = "deactivated"; }
    var info = {uid:uid,status:status,action:"statuschnage"}
    $.ajax({
        type : "POST",
        url : "ajaxdata.php",
        data : info,
        success : function(response){
            $("#notiflist-col").load(location.href + " #notification_table"); setTimeout(function(){ loaddatatable();}, 500);
            if(response==1){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Notification "+msg+" successfully").delay(3000).fadeOut();
            } else{
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error.. Try again later").delay(3000).fadeOut();
            }
        }
    });
}
function show_on_home(v1,v2){
    var uid = v1;
    var getid = v2;
    var c = $("#"+getid+":checked").val();
    if(c=="on"){ status = 1;  msg = "is enabled on home page"; } else{ status = 0; msg = "is disabled on home page"; }
    var info = {uid:uid,status:status,action:"show_on_home"}
    $.ajax({
        type : "POST",
        url : "ajaxdata.php",
        data : info,
        success : function(response){
            response = $.trim(response);
            $("#notiflist-col").load(location.href + " #notification_table"); setTimeout(function(){ loaddatatable();}, 500);
            if(response==1){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Notification "+msg+" successfully").delay(3000).fadeOut();
            } else if(response == 2){
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please activate notification first").delay(3000).fadeOut();
            } else if(response == 3){
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Already one notification is shown one home page").delay(3000).fadeOut();
            } else{
              $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error.. Try again later").delay(3000).fadeOut();
            }
        }
    });
}
function show_in_account(v1,v2){
    var uid = v1;
    var getid = v2;
    var c = $("#"+getid+":checked").val();
    if(c=="on"){  status = 1;  msg = "is enabled in  account panel"; } else{ status = 0; msg = "is disabled from  account panel"; }
    var info = {uid:uid,status:status,action:"show_in_account"}
    $.ajax({
        type : "POST",
        url : "ajaxdata.php",
        data : info,
        success : function(response){
            response = $.trim(response);
            $("#notiflist-col").load(location.href + " #notification_table"); setTimeout(function(){ loaddatatable();}, 500);
            if(response==1){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Notification "+msg+" successfully").delay(3000).fadeOut();
            } else if(response == 2){
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please activate notification first").delay(3000).fadeOut();
            } else if(response == 3){
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Already one notification is shown in account panel").delay(3000).fadeOut();
            } else{
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error.. try again later").delay(3000).fadeOut();
            }
        }
    });
}   
function show_on_all(v1,v2){
    var nid = v1; var getid = v2; var c = $("#"+getid+":checked").val();
    if (c == "on"){ status = 1;msg= "displayed on all pages"; } else{ status = 0;msg="disabled from all pages"; }
    var info = { nid: nid, status: status, action:"ALL"};
    $.ajax({
        type: "POST",
        url: "ajaxdata.php",
        data: info,
        success: function(response){
            $("#notiflist-col").load(location.href + " #notification_table"); setTimeout(function(){ loaddatatable();}, 500);
            if(response == 1){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Notification "+msg+" successfully").delay(3000).fadeOut();
            } else if (response == 0){
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error.. Try again later").delay(3000).fadeOut();
            } else if (response == 2){
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please activate notification first").delay(3000).fadeOut();
            } else if (response == 3){
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Already one notification is shown").delay(3000).fadeOut();
            }
        }
    }); 
}
</script>   
</body>
</html>