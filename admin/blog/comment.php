<?php include ("../../includes/configuration.php");
$reviewdet = selectQuery(BLOGCMNT,"*","isApproved='0'");
$approvedcommentcnt = selectQuery(BLOGCMNT,"count(comment_id) as cmtcnt","isApproved='1'");
?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Blog Comment</title>
    <?php include('../commoncss.php'); ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/dataTable/dataTables.bootstrap4.min.css">
</head>
<body>
<div class="page-body-wrapper">
    <?php include ('../header.php'); include ('../sidebar.php'); ?> 
    <div class="main-panel">
        <div class="dashbody">
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2"><div><h2 class="card-head-title mb-2 mb-lg-0">Blog Comments - Pending For Approval</h2></div><div class="btn-actions-pane-right"><a href="<?php echo ADMINURL; ?>blog/comment_approved.php" class="btn btn-primary btn-sm">Approved Comment (<?php echo $approvedcommentcnt[0]['cmtcnt'];?>)</a></div></div>
                <div class="card-body" id="blogcmtcol">
                    <div class="msgs"></div>
                    <div class="tshufle" id="blogcmtreload">
                        <table class="pendblog display table table-bordered w-100">
                            <thead><tr><th>#</th><th>Blog</th><th>Comment</th><th>Status</th><th class="del-btn-tr">Delete</th></tr></thead>
                            <tbody>
                                <?php for($i=0;$i<count($reviewdet);$i++){
                                $product = selectQuery(BLOG,"title","id=".$reviewdet[$i]['blog_id']); ?>
                                <tr>
                                    <td class="row-index-tr"><?php echo $i+1; ?></td>
                                    <td><? echo $product[0]['title'];?></td>
                                    <td><div style="width:500px;"><?php $message = $reviewdet[$i]['comment'];
                                    /*if(strlen($message)>15){ ?> <a class="cc-cursor-pointer" onclick="getcomm('<?php echo addslashes($message);?>');"><?php echo substr($message,0,30).".."; ?></a> <?php }*/ echo $message; ?></div>
                                    <div class="mt-2 mb-1"><b>Posted By</b> : <span class="mute-text"><?=$reviewdet[$i]['user_name']; ?></span></div><div><b>Posted On</b> : <span class="mute-text"><?=date("d M Y h:i a", strtotime($reviewdet[$i]['comment_date']));?></span></div>
                                    </td> 
                                    <td><label class="switch btn btn-primary"><input type="checkbox" class="tg0" data-id="<?php echo $reviewdet[$i]['comment_id']; ?>" id="checkbox0_<?php echo $reviewdet[$i]['comment_id']; ?>" name="checkbox0_<?php echo $reviewdet[$i]['comment_id']; ?>" <? if($reviewdet[$i]['isApproved']==1){echo "checked";}else{echo "";} ?> onchange="tg0chng('<?php echo $reviewdet[$i]['comment_id']; ?>','checkbox0_<?php echo $reviewdet[$i]['comment_id']; ?>');"><span class="slider round"><span class="on">Active</span><span class="off">Deactive</span></span></label></td>
                                    <td class="del-btn-tr"><button onclick="del('<?php echo $reviewdet[$i]['comment_id']; ?>')" class="btn btn-danger btn-sm deletebtn"><i class="fa fa-trash-o"></i></button></td>
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
<div class="modal fade" id="getcomment" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body"><div id="getc"></div></div>
            <div class="modal-footer"><button type="button" class="btn btn-danger btn-sm px-3" data-dismiss="modal">Close</button></div>
        </div>
    </div>
</div>
<script src="<?php echo SITEURL; ?>/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script>
function loaddatatable(){ $('.pendblog').DataTable({"scrollX": true}); };
loaddatatable();
function tg0chng(v1,v2){
    var pid = v1;
    var getid = v2;
    var c = $("#"+getid+":checked").val();
    if(c=="on"){     
        var info = {pid:pid,status:"1",action:"commentaction"};
        $.ajax({
            type:"POST",
            url:"ajaxdata.php",
            data:info,
            success:function(response){
                if(response==1){
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Comment is  approved").delay(5000).fadeOut();
                } else{                     $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("ERROR..").delay(5000).fadeOut();
                }
            }
        });
    } else{
        var info = {pid:pid,status:"0",action:"commentaction"};
        $.ajax({
            type:"POST",
            url:"ajaxdata.php",
            data:info,
            success:function(response){
                if(response==1){ $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Comment is disapproved").delay(5000).fadeOut();
                } else {           $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("ERROR..").delay(5000).fadeOut();
                }
            }
        });
    }
}
function del(i){
    msg = "Do you really want to delete this comment?";
    del_alertbox(msg, i,"del_comment");
} 
function del_comment(id){
    var comment_id = id; $("#del_popup").modal("hide");
    if(id!=""){
        var info = {comment_id:comment_id,action:"deletecomment"};
        $.ajax({
            type:"POST",
            url:"ajaxdata.php",
            data:info,
            success:function(response){
                if(response==1){
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Comment deleted successfully").delay(3000).fadeOut();
                    $("#blogcmtcol").load(location.href + " #blogcmtreload"); setTimeout(function(){ loaddatatable();}, 500);
                } else{             $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("ERROR..").delay(3000).fadeOut();
                }
            }
        });
    }
}
function getcomm(comment){ $("#getcomment").modal('show'); $("#getc").html(comment); }
</script>
</body>
</html>