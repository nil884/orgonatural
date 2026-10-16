<? include("../../includes/configuration.php");

$running = selectQuery(COUPON,"couponId,couponCode,validFrom,validTill,status","validFrom<='".date('Y-m-d H:i:s')."' AND validTill>='".date('Y-m-d H:i:s')."' order by  couponId DESC");
$upcoming = selectQuery(COUPON,"couponId,couponCode,validFrom,validTill,status","validFrom>='".date('Y-m-d H:i:s')."' AND validTill>='".date('Y-m-d H:i:s')."' order by  couponId DESC");
$expired = selectQuery(COUPON,"couponId,couponCode,validFrom,validTill,status","validFrom<='".date('Y-m-d H:i:s')."' AND validTill<='".date('Y-m-d H:i:s')."' order by  couponId DESC"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo SITENAME; ?> : Coupons</title>
    <? include "../commoncss.php"; ?>
    <link rel="stylesheet" href="<?=SITEURL; ?>/css/dataTable/dataTables.bootstrap4.min.css">
</head>
<body>
<div class="page-body-wrapper">
    <? include '../header.php'; ?>
    <? include '../sidebar.php'; ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2"><h5 class="card-head-title">Coupons</h5><div class="btn-actions-pane-right"><a href="<?php echo ADMINURL; ?>coupons/createcoupon.php" class="btn btn-primary btn-sm">Create New Coupon</a> </div>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" id="nav-active-tab" data-toggle="tab" href="#nav-active" role="tab" aria-controls="nav-active" aria-selected="true">Running (<?php  echo count($running) ?>)</a></li>
                        <li class="nav-item"><a class="nav-link" id="nav-deactive-tab" data-toggle="tab" href="#nav-deactive" role="tab" aria-controls="nav-deactive" aria-selected="false">Upcoming (<?php  echo count($upcoming) ?>)</a></li>
                        <li class="nav-item"><a class="nav-link" id="nav-expired-tab" data-toggle="tab" href="#nav-expired" role="tab" aria-controls="nav-expired" aria-selected="false">Expired (<?php  echo count($expired) ?>)</a></li>
                    </ul>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-active" role="tabpanel" aria-labelledby="nav-active-tab">
                            <div class="table-responsive" id="active-coupon">
                                <table class="example table table-bordered active-coupon">
                                <thead><tr><th>#</th><th>Coupon Code</th><th>Validty</th><th>Status</th><th>Delete</th></tr></thead>
                                <tbody>
                                    <?php for($i=0;$i<count($running);$i++){ ?>
                                    <tr>
                                        <td class="row-index-tr"><?=$i+1 ?></td>
                                        <td><a href="view-details.php?coupon_id=<?=base64_encode($running[$i]['couponId']); ?>"><?=$running[$i]['couponCode']; ?></a></td>
                                        <td><?=date("d M Y h:i a",strtotime($running[$i]['validFrom'])); ?> To <?=date("d M Y h:i a",strtotime($running[$i]['validTill'])); ?></td>
                                        <td>
                                        <? if(date("Y-m-d H:i:s",strtotime($running[$i]['validFrom']))<=date("Y-m-d H:i:s")&&date("Y-m-d H:i:s",strtotime($running[$i]['validTill']))>=date("Y-m-d H:i:s")){ $status="Running"; }
                                        else if(date("Y-m-d H:i:s",strtotime($running[$i]['validFrom']))>=date("Y-m-d H:i:s")&&date("Y-m-d H:i:s",strtotime($running[$i]['validTill']))>=date("Y-m-d H:i:s")){ $status="Upcoming"; }
                                        else if(date("Y-m-d H:i:s",strtotime($running[$i]['validFrom']))<=date("Y-m-d H:i:s")&&date("Y-m-d H:i:s",strtotime($running[$i]['validTill']))<=date("Y-m-d H:i:s")){ $status="Expired"; }
                                        echo $status; ?>
                                        </td>
                                        <td><span class="removeopt pro-attr-badge-action btn btn-danger btn-sm" onclick="del_coupon('<?=$running[$i]['couponId']; ?>' )"><i class="fa fa-trash"></i></span>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-deactive" role="tabpanel" aria-labelledby="nav-deactive-tab">
                            <div class="table-responsive" id="deactive-coupon">
                            <table class="example table table-bordered deactive-coupon">
                                <thead><tr><th>#</th><th>Coupon Code</th><th>Validty</th><th>Status</th><th>Delete</th></tr></thead>
                                <tbody>
                                    <?php for($i=0;$i<count($upcoming);$i++){ ?>
                                    <tr>
                                        <td class="row-index-tr"><?=$i+1 ?></td>
                                        <td><a href="view-details.php?coupon_id=<?=base64_encode($upcoming[$i]['couponId']); ?>"><?=$upcoming[$i]['couponCode']; ?></a></td>
                                        <td><?=date("d M Y h:i a",strtotime($upcoming[$i]['validFrom'])); ?> To <?=date("d M Y h:i a",strtotime($upcoming[$i]['validTill'])); ?></td>
                                        <td>
                                        <? if(date("Y-m-d H:i:s",strtotime($upcoming[$i]['validFrom']))<=date("Y-m-d H:i:s")&&date("Y-m-d H:i:s",strtotime($upcoming[$i]['validTill']))>=date("Y-m-d H:i:s")){ $status="Running"; }
                                        else if(date("Y-m-d H:i:s",strtotime($upcoming[$i]['validFrom']))>=date("Y-m-d H:i:s")&&date("Y-m-d H:i:s",strtotime($upcoming[$i]['validTill']))>=date("Y-m-d H:i:s")){ $status="Upcoming"; }
                                        else if(date("Y-m-d H:i:s",strtotime($upcoming[$i]['validFrom']))<=date("Y-m-d H:i:s")&&date("Y-m-d H:i:s",strtotime($upcoming[$i]['validTill']))<=date("Y-m-d H:i:s")){ $status="Expired"; }
                                        echo $status; ?>
                                        </td>
                                        <td><span class="removeopt pro-attr-badge-action btn btn-danger btn-sm" onclick="del_coupon('<?=$upcoming[$i]['couponId']; ?>' )"><i class="fa fa-trash"></i></span></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-expired" role="tabpanel" aria-labelledby="nav-expired-tab">
                            <div class="table-responsive" id="expired-coupon">
                                <table class="example table table-bordered expired-coupon">
                                    <thead><tr><th>#</th><th>Coupon Code</th><th>Validty</th><th>Status</th><th>Delete</th></tr></thead>
                                    <tbody>
                                        <?php for($i=0;$i<count($expired);$i++){ ?>
                                        <tr>
                                            <td><?=$i+1 ?></td>
                                            <td><a href="view-details.php?coupon_id=<?=base64_encode($expired[$i]['couponId']); ?>"><?=$expired[$i]['couponCode']; ?></a></td>
                                            <td><?=date("d M Y h:i a",strtotime($expired[$i]['validFrom'])); ?> To <?=date("d M Y h:i a",strtotime($expired[$i]['validTill'])); ?></td>
                                            <td>
                                            <? if(date("Y-m-d H:i:s",strtotime($expired[$i]['validFrom']))<=date("Y-m-d H:i:s")&&date("Y-m-d H:i:s",strtotime($expired[$i]['validTill']))>=date("Y-m-d H:i:s")){ $status="Running"; }
                                            else if(date("Y-m-d H:i:s",strtotime($expired[$i]['validFrom']))>=date("Y-m-d H:i:s")&&date("Y-m-d H:i:s",strtotime($expired[$i]['validTill']))>=date("Y-m-d H:i:s")){ $status="Upcoming"; }
                                            else if(date("Y-m-d H:i:s",strtotime($expired[$i]['validFrom']))<=date("Y-m-d H:i:s")&&date("Y-m-d H:i:s",strtotime($expired[$i]['validTill']))<=date("Y-m-d H:i:s")){ $status="Expired"; }
                                            echo $status; ?>
                                            </td>
                                            <td><span class="removeopt pro-attr-badge-action btn btn-danger btn-sm" onclick="del_coupon('<?=$expired[$i]['couponId']; ?>' )"><i class="fa fa-trash"></i></span></td>
                                        </tr>
                                        <?php  } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
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
datatable();
function datatable(){$('.all-coupon,.active-coupon,.deactive-coupon,.expired-coupon').DataTable(); }
function del_coupon(id){
    msg = "Do you really want to delete this coupon?"; del_alertbox(msg, id,"del_coupon_db");
}
function del_coupon_db(id){
    info = {couponId:id,action:"delete_coupon"}
    $.ajax({
        type:"POST",
        url:"ajaxdata.php",
        data:info,
        success:function(response){
            response=response.trim();
            if(response== "1"){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Coupon deleted successfully").delay(3000).fadeOut();
                $("#del_popup").modal("hide");
                $("#nav-tabContent").load(location.href + " #nav-tabContent");  setTimeout(function(){  datatable(); },500)
            } else if(response=="0"){
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error.. Try again later").delay(3000).fadeOut();
            }
        }
    })
}
</script>
</body>
</html>