<?php include ("../../includes/configuration.php");
$approvedcommentcnt = selectQuery(BLOGCMNT,"*","isApproved='1'"); ?>
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
                <div class="card-header sec-card-head justify-content-between align-items-center py-2">
                    <div><h2 class="card-head-title">Approved Blog Comments</h2></div>
                    <div class="btn-actions-pane-right"><button class="btn btn-secondary btn-sm" onclick="window.history.back()">Back</button></div>
                </div>
                <div class="card-body" id="apprblogcmtcol">
                    <div class="msgs"></div>
                    <div class="table-responsive tshufle" id="apprblogcmtreload">
                        <table class="appredblog display table table-bordered w-100">
                            <thead><tr><th>#</th><th>Blog</th><th>Comment</th><th style="width: 13%;">Status</th><th>Delete</th></tr></thead>
                            <tbody>
                                <?php for($i=0;$i<count($approvedcommentcnt);$i++){
                                $product = selectQuery(BLOG,"title","id=".$approvedcommentcnt[$i]['blog_id']);?>
                                <tr>
                                    <td class="row-index-tr"><?php echo $i+1; ?></td>
                                    <td><? echo $product[0]['title'];?></td>
                                    <td><div style="width:500px;">
                                    <?php $message = $approvedcommentcnt[$i]['comment'];
                                    /*if(strlen($message)>15){ ?>
                                    <a class="cc-cursor-pointer" onclick="getcomm('<?php echo $message;?>');"> <?php echo substr($message,0,15).".."; ?></a><?php }*/ echo $message; ?></div>
                                    <div class="mt-2 mb-1"><b>Posted By</b> : <span class="mute-text"><?=$approvedcommentcnt[$i]['user_name']; ?></span></div><div><b>Posted On</b> : <span class="mute-text"><?=date("d M Y h:i a", strtotime($approvedcommentcnt[$i]['comment_date']));?></span></div>
                                    </td>
                                    <td>
                                        <label class="switch btn btn-primary">
                                            <input type="checkbox" class="tg0" data-id="<?php echo $approvedcommentcnt[$i]['comment_id']; ?>" id="checkbox0_<?php echo $approvedcommentcnt[$i]['comment_id']; ?>" name="checkbox0_<?php echo $approvedcommentcnt[$i]['comment_id']; ?>" <? if($approvedcommentcnt[$i]['isApproved']==1){echo "checked";}else{echo "";} ?> onchange="tg0chng('<?php echo $approvedcommentcnt[$i]['comment_id']; ?>','checkbox0_<?php echo $approvedcommentcnt[$i]['comment_id']; ?>');">
                                            <span class="slider round"><span class="on">Active</span><span class="off">Deactive</span></span>
                                        </label>
                                    </td>
                                    <td class="del-btn-tr"><button type="button" onclick="del('<?php echo $approvedcommentcnt[$i]['comment_id']; ?>')" class="btn btn-danger btn-sm deletebtn"><i class="fa fa-trash-o"></i></button></td>
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
            <div class="modal-footer"><button type="button" class="btn btn-danger px-3" data-dismiss="modal">Close</button></div>
        </div>
    </div>
</div>
<script src="<?php echo SITEURL; ?>/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script>
function loaddatatable(){ $('.appredblog').DataTable({"scrollX": true}); };
loaddatatable();  
$(".buttons-excel").addClass("ml-1"); $(".dataTables_scroll").addClass("mb-2");
function tg0chng(v1,v2){
    var pid = v1; var getid = v2; var c = $("#"+getid+":checked").val();
    if(c=="on"){     
        var info = {pid:pid,status:"1",action:"commentaction"};
        $.ajax({
            type:"POST",
            url:"ajaxdata.php",
            data:info,
            success:function(response){
                if(response==1){
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Comment is  approved").delay(5000).fadeOut();
                } else{ $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("ERROR..").delay(5000).fadeOut();
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
                if(response==1){
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Comment is disapproved").delay(5000).fadeOut();
                } else{     $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("ERROR..").delay(5000).fadeOut();
                }
            }
        });
    }
}
function del(i){ msg = "Do you really want to delete this comment?"; del_alertbox(msg, i,"del_comment"); }
function del_comment(id){
    var comment_id = id;
    if(id!=""){
        var info = {comment_id:comment_id,action:"deletecomment"};
        $.ajax({
            type : "POST",
            url : "ajaxdata.php",
            data : info,
            success : function(response){
                if(response==1){
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Comment deleted successfully").delay(5000).fadeOut();
                    $("#apprblogcmtcol").load(location.href + " #apprblogcmtreload"); setTimeout(function(){ loaddatatable();}, 500);
                } else{     $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("ERROR..").delay(5000).fadeOut();
                }
            }
        });
    }
}
function getcomm(comment){ $("#getcomment").modal('show'); $("#getc").html(comment); }
$(".nav-list-1 .dropdownMenu").slideToggle();
$(".nav-list-1 .dropdownMenu .nav-list-4 a").addClass("menuactive");
</script>
</body>
</html>