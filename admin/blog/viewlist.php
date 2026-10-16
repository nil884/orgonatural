<?php include ("../../includes/configuration.php"); ?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Blog List</title>
    <?php include('../commoncss.php'); ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/dataTable/dataTables.bootstrap4.min.css">
</head>
<body>
<div class="page-body-wrapper">
    <?php include ('../header.php'); include ('../sidebar.php');
    $getblog = selectQuery(BLOG,"id,title,isActive","1");  ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2"><div><h2 class="card-head-title">Blog</h2></div><div class="btn-actions-pane-right"><a href='<?=ADMINURL; ?>blog/' class="btn btn-primary btn-sm generate">Add New Blog</a></div></div>
                <div class="card-body">
                  <div class="tshufle" id="blogList">
                       <table class="table table-bordered bloglist w-100" id="blogreloadlist">
                            <thead><tr><th class="row-index-tr">#</th><th>Title</th><!--<th>Show On Home page</th>--><th>Active / Deactive</th><th>Delete</th></tr></thead>
                            <tbody class="mute-secondary" id="tdata">
                                <?php for($i=0;$i<count($getblog);$i++){
                                $blogid = $getblog[$i]['id']; $name=$getblog[$i]['title']; $active=$getblog[$i]['isActive']; ?>
                                <tr>
                                    <td class="td2"><?=$i+1; ?></td>
                                    <td><a href="<?=ADMINURL ?>blog/viewbasic.php?blogid=<?=base64_encode($blogid); ?>"><?=$name; ?></a></td>
                                    <td>
                                        <label class="switch btn btn-primary"><input type="checkbox" class="tg" data-toggle="toggle" data-id="<?=$blogid; ?>" id="checkbox_<?=$blogid; ?>" name="checkbox_<?=$blogid; ?>" <?=($active==1?'checked':''); ?> <?=($active==0 &&$activecnt>=2?'disabled':''); ?> onchange="tg2chng('<?=$blogid; ?>','checkbox_<?=$blogid; ?>');"><span class="slider round"><span class="on">Active</span><span class="off">Deactive</span></span></label>
                                    </td>
                                    <td class="del-btn-tr"><button type="button" onclick="del('<?=$blogid; ?>')" class="deletebtn btn btn-danger btn-sm"><i class="fa fa-trash-o"></i></button></td>
                                </tr>
                                <? } ?>
                            </tbody>
                       </table>
                    </div>
                </div>
            </div>
        </div>
        <?php include '../footer.php'; ?>
    </div>
</div>
<script src="<?php echo SITEURL; ?>/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script>
loaddatatable()
function loaddatatable(){ $('.bloglist').DataTable({"scrollX": true}); };
function tg2chng(v1,v2){
    var pid = v1; var getid = v2;
    var c = $("#" + getid + ":checked").val();
    if (c == "on"){
        var info = { pid: pid,status: "1", action: "statuschng"};
        $.ajax({
            type: "POST",
            url: "ajaxdata.php",
            data: info,
            success: function(response){
                if(response == 1) {
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Blog activated successfully").delay(1000).fadeOut();
                } else { $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("ERROR..").delay(1000).fadeOut();
                }
            }
        });
    } else {
        var info = {pid: pid,status: "0",action: "statuschng" };
        $.ajax({
            type: "POST",
            url: "ajaxdata.php",
            data: info,
            success: function(response){
                if (response == 1){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Blog deactivated successfully").delay(5000).fadeOut();
                } else{ $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("ERROR..").delay(1000).fadeOut();
                }
            }
        });
    }
}
    
function del(i){ msg = "Do you really want to delete this blog?"; del_alertbox(msg, i,"del_blog"); }
    
function del_blog(id){
    var uid = id; var info = {pid: uid, action: "delblog" };
    $.ajax({
        type: "POST",
        url: "ajaxdata.php",
        data: info,
        success: function(response){
            if (response == 1){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html('Blog deleted successfully').delay(1000).fadeOut();
                $("#blogList").load(location.href + " #blogreloadlist"); setTimeout(function(){ loaddatatable();}, 500);
                $("#del_popup").modal("hide"); 
            } else{
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html('Please try after some time').delay(1000).fadeOut();
            }
        }
    });
}

function tg1chng(v1,v2){
    var pid = v1; var getid = v2; var c = $("#"+getid+":checked").val();
    if(c=="on"){ status= "1"; msg= "Blog shown on homepage" } else{ status = "0"; msg= "Blog disabled from homepage" } 
    var info = {action: "showonhome",pid:pid,status:"1"};
    $.ajax({
        type:"POST",
        url:"ajaxdata.php",
        data:info,
        success:function(response){
            if(response==1){
            $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html('Blog shown on homepage').delay(1000).fadeOut();
            } else if(response==0){     $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("ERROR..").delay(1000).fadeOut(); }
            else if(response==2){
                $("#"+getid).removeAttr('checked');
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Blog is deactive. Please active it first to show on homepage").delay(1000).fadeOut();
            }
        }
    });
} 
</script>
</body>
</html>