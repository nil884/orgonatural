<? include("../../includes/configuration.php");
    $couponId = base64_decode($_GET['coupon_id']);
    $getCoupon = selectQuery(COUPON,"*","couponId=".$couponId);
    $cpCode = $getCoupon[0]['couponCode'];
    $appliedOn = $getCoupon[0]['applicableOn'];
    $ids = $getCoupon[0]['applicableId'];
    if($appliedOn=="Selected Products"){
        $getdata=selectQuery(PRODINFO." as p LEFT JOIN ".PRODCAT." as pc on p.parent_cat=pc.id LEFT JOIN ".PRODCAT." as mc on p.master_cat=mc.id LEFT JOIN ".PRODCAT." as sc  on p.sub_cat=sc.id","pc.cat_name as parent,mc.cat_name as master,sc.cat_name as sub,p.id as pid,p.prod_name","p.id IN(".$ids.")");
    }else{
        $getdata=selectQuery(PRODCAT." as sc LEFT JOIN ".PRODCAT." as mc on sc.parent_id=mc.id LEFT JOIN ".PRODCAT." as pc on mc.parent_id=pc.id","pc.cat_name as parent,mc.cat_name as master,sc.cat_name as sub","sc.id IN(".$ids.")");
    }
   
    $idarr = explode(",",$ids);
    $jsonarr = json_encode($idarr,true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo SITENAME; ?> :  Coupons</title>
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
                <div class="card-header sec-card-head justify-content-between align-items-center"><h5 class="card-head-title">Coupon Code - <?=$cpCode; ?></h5></div>
                <div class="card-body">
                <table class="example table table-bordered active-coupon">
                    <thead><tr><th>#</th><th>Parent Category</th><th>Master Category</th><th>Subcategory</th><th class="<?=($appliedOn=='Selected Products'?'':'d-none'); ?>">Product</th></tr></thead>
                    <tbody>
                        <?php for($i=0;$i<count($getdata);$i++){ ?>
                        <tr>
                            <td><?=$i+1; ?></td>
                            <td><?=$getdata[$i]['parent']; ?></td>
                            <td><?=$getdata[$i]['master']; ?></td>
                            <td><?=$getdata[$i]['sub']; ?></td>
                            <td class="<?=($appliedOn=='Selected Products'?'':'d-none'); ?>"> <a href="<?=ADMINURL ?>product/view-details.php?prod_id=<?=base64_encode($getdata[$i]['pid']); ?>" target="_blank" ><?=$getdata[$i]['prod_name']; ?></a> </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                </div>    
          </div>  
      
        </div>
        <? include "../footer.php"; ?>
    </div>
</div>
<script src="<?php echo SITEURL; ?>/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script>$('.active-coupon').DataTable();</script>
</body>
</html>