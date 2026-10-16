<?php include ("../includes/configuration.php");
require_once('../classes/product.php');
$prod = new Product();
 ?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <title><?php echo SITENAME; ?> : Live Cart</title>
    <?php include('commoncss.php'); ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/dataTable/dataTables.bootstrap4.min.css">

</head>
<body>
<div class="page-body-wrapper">
    <?php include ('header.php'); include ('sidebar.php');
   $livecartdata = selectQuery(CART." as c JOIN ".BUYER." as b on c.user_id=b.u_id JOIN ".PRODINFO." as p on c.prod_id=p.id ","p.id,p.prod_name,b.u_id,b.u_fname,b.u_lname,c.quantity,c.insert_date","c.type='CART'  ORDER BY c.insert_date DESC");
    $getconfingdetails = json_decode(getimgconfig('product'));

    $img_location = $getconfingdetails[0]->imgs_location; // Access Object data
    $img_location = $getconfingdetails[0]->thumb3_path;
   ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card mb-0">
                 <div class="card-header sec-card-head justify-content-between align-items-center py-2"><div><h2 class="card-head-title mb-2 mb-sm-0">Live Cart</h2></div><div class="btn-actions-pane-right"><a class="btn btn-secondary btn-sm" href="home.php"> Back</a></div></div>
                <div class="card-body">
                    <div class="table-responsive">
                    <table class="table table-bordered mb-0 example w-100">
                    <thead><tr><th>Product</th><th>User</th><th>Product Name</th><th>Qty</th><th>Price</th><th>Added On</th></tr></thead>
                    <tbody>
                    <? for($i=0;$i<count($livecartdata);$i++){
                    $row=$livecartdata[$i]; $price=$prod->getProductFullDetails($row['id']); $img=$prod->getProductImageForDisplay($row['id']); 
                    if($img[0]['img_name']){$img=SITEURL."/".$img_location."/".$img[0]['img_name'];}else{$img = SITEURL."/img/projectimage/product-default.png";} ?>
                    <tr><td class="text-center"><img src="<? echo $img; ?>" alt="pro-upload-img" class="rounded" height="80"/></td><td><a href="<?=ADMINURL; ?>buyer/buyersdetails.php?u_id=<?=base64_encode($row['u_id']); ?>" target="_blank"><?=$row['u_fname']." ".$row['u_lname']; ?></a></td><td><a href="<?=ADMINURL; ?>product/view-details.php?prod_id=<?=base64_encode($row['id']); ?>" target="_blank"> <?=$row['prod_name']; ?></a></td><td><?=$row['quantity']; ?></td><td><?=$price['price']; ?></td><td><?=date("d M Y",strtotime($row['insert_date'])); ?></td></tr>
                    <?} ?>
                    </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
        <?php include 'footer.php'; ?>
    </div>
</div>
<script src="<?php echo SITEURL; ?>/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script>
$('.example').DataTable({ "scrollX": true });
</script>
</body>
</html>