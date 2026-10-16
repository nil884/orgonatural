<?php include("../../includes/configuration.php");
include("../../classes/product.php");
include("../../classes/user.php");
$imgtype="product";
include("../../getimgpath.php");
$prod = new Product();
$user = new User();
$reviewdet = selectQuery(REVIEW,"*","isApproved='0'");
$approvedreviewcnt = selectQuery(REVIEW,"count(review_id) as reviewcnt","isApproved='1'  "); ?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Product Review</title>
    <?php include('../commoncss.php')?>
    <link rel="stylesheet" href="<?=SITEURL; ?>/css/dataTable/dataTables.bootstrap4.min.css">
</head>
<body>
<div class="page-body-wrapper">
    <? include '../header.php'; ?>
    <? include '../sidebar.php'; ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2">
                    <div><h5 class="card-head-title mb-2 mb-sm-0">Pending Product Review For Approval - All List</h5></div>
                    <div class="btn-actions-pane-right"><a href="<?=ADMINURL; ?>review/approved.php" class="btn btn-primary btn-sm">Approved Product Review (<?=$approvedreviewcnt[0]['reviewcnt']; ?>)</a></div>
                </div>
                <div class="card-body">
                    <div class="tshufle">
                        <table class="display table table-bordered pendreview w-100">
                            <thead><tr><th>#</th><th style="80px;">Items</th><th>Review Details</th><th style="width: 13%;">Status</th> <th>Delete</th> </tr></thead>
                            <tbody>
                                <?php for($i=0;$i<count($reviewdet);$i++){
                                $udetail = $user->getUserDetails("u_fname,u_lname",$reviewdet[$i]['user_id']);
                                $reviewid = $reviewdet[$i]['review_id'];  $active=$reviewdet[$i]['isApproved'];
                                $getproddetails = $prod->getShortDetails($reviewdet[$i]['prod_id']);
                                $prodimg = $prod->getProductImageForDisplay($reviewdet[$i]['prod_id']);
                                $prodid = $reviewdet[$i]['prod_id']; ?>
                                <tr>
                                    <td class="td2"><?=$i+1; ?></td>
                                    <td class="td1"><img src='<?=SITEURL;?>/<?=$thumb2_path;?>/<?=$prodimg[0]['img_name'];?>' alt='img' height="100"></td>
                                    <td>
                                        <div>
                                        <h6><? if($getproddetails[0]['parent_id']==0){ $name=$getproddetails[0]['prod_name']; }else{ $name=$prod->getParentName($prodid); }
                                        echo $name;
                                        ?></h6>
                                        <b>By</b> <span class="mute-text"><?=$udetail[0]['u_fname']."  ".$udetail[0]['u_lname'];?></span>
                                        </div>
                                        <div class="text-info prod-review-cmt"><?=date("d M Y h:i a",strtotime($reviewdet[$i]['review_date']));?></div>
                                        <div><b><?=$reviewdet[$i]['review_title'];?></b></div>
                                        <div class="mute-text"><?=$reviewdet[$i]['review'];?></div>
                                    </td>
                                    <td>
                                        <label class="switch btn btn-primary">
                                            <input type="checkbox" class="tg0" data-toggle="toggle" data-id="<?=$reviewid; ?>" id="checkbox_<?=$reviewid; ?>" name="checkbox_<?=$reviewid; ?>" <?=($active==1?'checked':''); ?> onchange="tg0chng('<?=$reviewid; ?>','checkbox_<?=$reviewid; ?>');">
                                            <span class="slider round"><span class="on">Approved</span><span class="off">Pending</span></span>
                                        </label>
                                    </td>
                                    <td class="td1"><button type="button" onclick="del('<?=$reviewid; ?>')" class="deletebtn btn btn-danger btn-sm"><i class="fa fa-trash-o"></i></button> </td>
                                </tr>
                                <? } ?>
                            </tbody>
                       </table>
                    </div>
                </div>
            </div>
        </div>
        <?php include '../footer.php'?>
    </div>
</div>
<script src="<?=SITEURL; ?>/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?=SITEURL; ?>/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function() {
    $('.pendreview').DataTable({ "scrollX": true, "lengthMenu": [10, 25, 50, 75, 100]});
});
function tg0chng(v1,v2){
    var pid = v1; var getid = v2;
    var c = $("#"+getid+":checked").val();
    if(c=="on"){     
        var info={pid:pid,status:"1",action:"statusReview"};
        $.ajax({
            type:"POST",
            url:"ajax.php",
            data:info,
            success:function(response){
                if(response==1){
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Review approved").delay(1000).fadeOut();
                    $(".tshufle").load(  " .tshufle");
                } else{
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("ERROR..").delay(1000).fadeOut();
                }
            }
        });
    } else{
        var info={pid:pid,status:"0",action:"statusReview"};
        $.ajax({
            type:"POST",url:"ajax.php",data:info,
            success:function(response){
                if(response==1){
                    $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Review disapproved").delay(5000).fadeOut();
                    $(".tshufle").load(  " .tshufle");
                } else{
                    $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("ERROR..").delay(1000).fadeOut();
                }
            }
        });
    }
}
function del(i){
    msg = "Do you really want to delete this review?";
    del_alertbox(msg, i,"del_review");
}
function del_review(id){
    var reviewid = id;
    var info={reviewid:reviewid,action:"deleteReview"};
    $.ajax({
        type:"POST",
        url:"ajax.php",
        data:info,
        success:function(response){
            if(response==1){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html('Review deleted successfully').delay(1000).fadeOut();
                $(".tshufle").load(  " .tshufle");
                $("#del_popup").modal("hide");
            }
            else{
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("ERROR..").delay(1000).fadeOut();
            }
        }
    });
}
</script>
</body>
</html>
