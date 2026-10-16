<?php include("../../includes/configuration.php");
    include("../../classes/product.php");
    include("../../classes/user.php");
    $imgtype = "product";
    include("../../getimgpath.php");
    $prod = new Product();
    $user = new User();
    $reviewdet = selectQuery(REVIEW,"prod_id,main_prod_id","isApproved='1' group by main_prod_id");
?>
<!doctype html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Approved Product Reviews</title>
    <?php include('../commoncss.php') ?>
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
                    <div><h2 class="card-head-title">Approved Product Review - All List</h2></div>
                    <div class="btn-actions-pane-right"><button class="btn btn-secondary btn-sm" onclick="goBack()"> Back</button></div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered apporvereview w-100">
                            <thead><tr><th>#</th> <th>Product</th><th>Product Name</th><th>Review Count</th></tr></thead>
                            <tbody>
                                <?php for($i=0;$i<count($reviewdet);$i++){
                                $udetail = $user->getUserDetails("u_fname,u_lname",$reviewdet[$i]['user_id']);
                                $reviewid = $reviewdet[$i]['review_id'];  $active=$reviewdet[$i]['isApproved'];
                                $prodid = $reviewdet[$i]['prod_id'];
                                $getproddetails = $prod->getShortDetails($prodid);
                                $prodimg = $prod->getProductImageForDisplay($prodid); ?>
                                <tr>
                                <td class="td2"><?=$i+1; ?></td>
                                <td class="td1 mute-text"><img  src='<?=SITEURL;?>/<?=$thumb2_path;?>/<?=$prodimg[0]['img_name'];?>' alt='appr-rev-thumb' width="80"></td>
                                <td><a href="javascript:void(0);" onclick="showmod('<?=$reviewdet[$i]['main_prod_id']; ?>')"> <? if($getproddetails[0]['parent_id']==0){ $name=$getproddetails[0]['prod_name']; }else{ $name=$prod->getParentName($prodid); }
                                echo $name; ?>
                                </a></td>
                                <td>
                                    <?php $reviewcount=selectQuery(REVIEW,"count(review_id) as prodreview","main_prod_id=".$reviewdet[$i]['main_prod_id']." and isApproved='1'" );
                                    echo $reviewcount[0]['prodreview']; ?>
                                </td>
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
<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" >
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">&nbsp;</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
<script src="<?=SITEURL; ?>/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?=SITEURL; ?>/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function() {
    $('.apporvereview').DataTable({ "scrollX": true });
});
function del(i){
    msg = "Do u really want to delete this review?";
    del_alertbox(msg, i,"del_review");
}
function del(id,prodid){
    var reviewid = id;
    var info = {reviewid:reviewid,action:"deleteReview"};
    $.ajax({
        type:"POST",
        url:"ajax.php",
        data:info,
        success:function(response){
            if(response==1){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html('Review deleted successfully').delay(1000).fadeOut();
                showmod(prodid);       
                $("#del_popup").modal("hide");
            } else{
                $('.updated').fadeIn().addClass("alert alert-danger").html("ERROR..").delay(3000).fadeOut();
            }
        }
    });
}
function showmod(reviewid){
    $.ajax({
        type:"POST",
        url:"viewallreview.php",
        data:{reviewid:reviewid},
        success:function(response){
            $("#myModalLabel").html("Review")
            $(".modal-body").html(response);
            $("#myModal1").modal("show");
        }
    });
}
</script>
</body>
</html>