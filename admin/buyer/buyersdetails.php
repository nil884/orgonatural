<?php require_once("../../includes/configuration.php");
require_once('../../classes/product.php');
require_once('../../getimgpath.php');
$prod = new Product();
$userid = base64_decode($_REQUEST['u_id']);
$getuser = selectQuery(BUYER,"*"," u_id='".$userid."'");
$getaddress = selectQuery(ADDRESS,"*"," user_id='".$userid."'");
$reviewdet = selectQuery(REVIEW,"*","user_id=".$userid);
$livecartdata = selectQuery(CART,"*","user_id=".$userid." and type='CART'  ORDER BY cart_id DESC");
$logins=selectQuery(BUYERLOG,"login_time","user_id=".$userid." and login_attempt='success' order by log_id DESC LIMIT 1");
//$orderdata=selectQuery(PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id  inner JOIN ".ORDER." as od on o.order_id= od.id","p.purchase_date,p.purchase_order_id,o.display_product_name,o.variation_on,o.variation_values,o.item_id, o.quantity,od.transaction_id,od.order_id,o.vendor_name","od.user_id='".$userid."'"); 


?>

<!doctype html>
<html lang="en">
<head>
    <title><?php echo SITENAME; ?> : Buyers Details</title>
    <?php include('../commoncss.php') ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/dataTable/dataTables.bootstrap4.min.css">
</head>
<body>
<div class="page-body-wrapper">
    <? include '../header.php'; ?>
    <? include '../sidebar.php'; ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2"><div><h5 class="card-head-title"><?php echo $getuser[0]['u_fname'].' '.$getuser[0]['u_lname']; ?></h5></div><div class="btn-actions-pane-right"><button class="btn btn-secondary btn-sm" onclick="goBack()">Back</button></div></div>
                <div class="card-body">
                    <div class="row">
                    <div class="col-sm-2 col-md-3 col-lg-2 mr-0 mb-3">
                        <? if($getuser[0]['profile_pic']!="")
                        echo "<img src='".SITEURL."/img/buyer_profile/".$getuser[0]['profile_pic']."' class='img-fluid' width='120' alt='propic'/>";
                        else
                        echo "<img src='".SITEURL."/img/projectimage/user-icon.png' width='120' class='img-fluid' alt='propic'/>"; ?>
                    </div>
                    <div class="col-sm-10 col-md-9 col-lg-10">
                        <div class="row mb-3">
                            <div class="col-sm-4 col-md-4 col-lg-3 col-xl-2"><b>Member Since</b></div>
                            <div class="col-sm-8 col-md-8 col-lg-8 col-xl-10"><?php echo date("d-m-Y" ,strtotime($getuser[0]['reg_date'])); ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 col-md-4 col-lg-3 col-xl-2"><b>Email</b></div>
                            <div class="col-sm-8 col-md-8 col-lg-8 col-xl-10  <?php if($getuser[0]['email_verified']=="1") { echo "text-success";} else { echo "text-danger";} ?>"><?php echo $getuser[0]['u_email']; ?> <? if($getuser[0]['email_verified']=="1") echo "<span class='badge badge-success ml-1'><i class='fa fa-check'></i> Verified</span>"; else echo "<span class='badge badge-danger ml-1'><i class='fa fa-close'></i> Not-Verified</span>"; ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 col-md-4 col-lg-3 col-xl-2"><b>Mobile</b></div>
                            <div class="col-sm-8 col-md-8 col-lg-8 col-xl-10"><?php echo $getuser[0]['u_mobile']; ?></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4 col-md-4 col-lg-3 col-xl-2"><b>Last Login</b></div>
                            <div class="col-sm-8 col-md-8 col-lg-8 col-xl-10"><a href="buyerlog.php?buyerid=<?php echo base64_encode($userid); ?>" ><?php echo (count($logins)?date('d M Y h:i a',strtotime($logins[0]['login_time'])):"Not Logged-in Yet"); ?></a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header sec-card-head justify-content-between align-items-center"><div><h2 class="card-head-title">Shipping Address Details</h2></div></div>
            <div>
                <? if(count($getaddress) >0){
                for($i=0;$i<count($getaddress);$i++){ ?>
                <div class="p-3 border-bottom">
                    <i class="fa fa-map-marker mr-1"></i><a class="cc-cursor-pointer text-primary" onclick="show_hiddenaddr('<? echo $i; ?>')" id='hiddentitle<? echo $i; ?>'><? echo $getaddress[$i]['address']; ?></a>
                    <div id='hiddenaddr<? echo $i; ?>' class="d-none">
                        <div><? echo $getaddress[$i]['address_type']; ?></div>
                        <h6><b><? echo $getaddress[$i]['address_name']; ?></b></h6>
                        <span class="h6"><? echo $getaddress[$i]['address']; ?> <? echo $getaddress[$i]['landmark']; ?>, <? echo $getaddress[$i]['city']; ?> - <? echo $getaddress[$i]['pincode']; ?>, <? echo $getaddress[$i]['state']; ?></span>
                    </div>
                </div>
                <?php } } else{ echo "<div class='card-body'>No Address Found</div>"; } ?>
            </div>
        </div>
        <div class="card">
            <a class="card-header sec-card-head justify-content-between align-items-center cc-cursor-pointer" data-toggle="collapse" href="#collapsbankData"><h2 class="card-head-title text-dark">Bank Details</h2></a>
            <div class="card-body collapse" id="collapsbankData">
                <div class="row">
                    <div class="col-md-6 col-lg-6">
                        <div class="row mb-3">
                            <div class="col-sm-4 col-md-12 col-lg-5 col-xl-4"><b>Bank Name</b></div>
                            <div class="col-sm-8 col-md-12 col-lg-7 col-xl-8"><?php if($getuser[0]['bank_name'] == ""){ echo " NA"; } else{ echo $getuser[0]['bank_name']; }; ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 col-md-12 col-lg-5 col-xl-4"><b>Account Name</b></div>
                            <div class="col-sm-8 col-md-12 col-lg-7 col-xl-8"><?php if($getuser[0]['account_name'] == "") { echo " NA"; } else { echo $getuser[0]['account_name']; } ; ?></div>
                        </div>
                        <div class="row mb-sm-3 mb-md-0">
                            <div class="col-sm-4 col-md-12 col-lg-5 col-xl-4"><b>Account Number</b></div>
                            <div class="col-sm-8 col-md-12 col-lg-7 col-xl-8"><?php if($getuser[0]['account_number'] == "" || $getuser[0]['account_number'] == 0){ echo " NA"; } else{ echo $getuser[0]['account_number']; } ; ?></div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6">
                        <div class="row mb-3">
                            <div class="col-sm-4 col-md-12 col-lg-5 col-xl-4"><b>IFSC code</b></div>
                            <div class="col-sm-8 col-md-12 col-lg-7 col-xl-8"><?php if($getuser[0]['ifsc_code'] == ""){ echo " NA";}else{ echo  $getuser[0]['ifsc_code']; } ; ?></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4 col-md-12 col-lg-5 col-xl-4"><b>UPI Id</b></div>
                            <div class="col-sm-8 col-md-12 col-lg-7 col-xl-8"><?php if($getuser[0]['Upi_id'] == ""){ echo " NA"; }else {echo $getuser[0]['Upi_id']; } ; ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="data-box">
            <div class="prodedit">
                <div class="card">
                    <a class="card-header sec-card-head justify-content-between align-items-center cc-cursor-pointer" data-toggle="collapse" href="#collapslivecart"><h2 class="card-head-title text-dark">Live Cart (<?=count($livecartdata) ?>)</h2></a>
                    <div class="card-body collapse <?=(count($livecartdata)?"show":""); ?>" id="collapslivecart">
                        <div class="table-responsive">
                            <table class="display livecart table table-bordered mb-0">
                                <thead><tr><th>#</th><th> Product</th><th>Quantity</th><th>Amount</th><th>Added on</th></tr></thead>
                                <tbody>
                                    <?
                                    for($i=0;$i<count($livecartdata);$i++){
                                       
                                    $productdata = $prod->getProductFullDetails($livecartdata[$i]['prod_id']);
                                    if(count($productdata['name'])){  ?>
                                      <tr>
                                        <td><? echo $i+1;?></td>
                                        <td><? echo $productdata['name']; ?></td>
                                        <td><? echo $livecartdata[$i]['quantity'];?></td>
                                        <td><? echo $productdata['price']; ?></td>
                                        <td><? echo date("d-m-Y",strtotime($livecartdata[$i]['insert_date']));?></td>
                                    </tr>
                                        <? } 
                                    }  ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <a class="card-header sec-card-head justify-content-between align-items-center cc-cursor-pointer" data-toggle="collapse" href="#collapsproreview"><h2 class="card-head-title text-dark">Reviews (<?php echo count($reviewdet); ?>)</h2></a>
                    <div class="card-body collapse" id="collapsproreview">
                        <div class="table-responsive">
                            <table class="display rev-table table table-bordered mb-0">
                                <thead><tr><th>#</th><th>Product</th><th>Product Name</th><th>Review Title</th><th>Reviews</th><th>Reviewed On</th><th>Status</th></tr></thead>
                                <tbody>
                                   <?

                                    for($i=0;$i<count($reviewdet);$i++){
                                    $productdata = $prod->getShortDetails($reviewdet[$i]['prod_id']);
                                    $prodimg = $prod->getProductImageForDisplay($reviewdet[$i]['prod_id']);
                                    $thumb2_path = getimgconfigpaths('product');
                                    if($prodimg){
                                        $img= SITEURL."/".$thumb2_path[0]['thumb2_path']."/".$prodimg[0]['img_name'];
                                    }else{
                                        $img= SITEURL."/img/projectimage/product-default.png";
                                    } ?>
                                    <tr>
                                        <td><? echo $i+1;?></td>
                                        <td><img src="<?=$img;?>" alt="pro-review-thumb" class="img-fluid" width="100"></td>
                                        <td><? echo $productdata[0]['prod_name'];?></td>
                                        <td><? echo $reviewdet[$i]['review_title'];?></td>
                                        <td style="text-align: left;"><? echo $reviewdet[$i]['review'];?></td>
                                        <td><? echo date("d-m-Y h:i" , strtotime($reviewdet[$i]['review_date'])) ;?></td>
                                        <td><?php if($reviewdet[$i]['isApproved']  == 1){echo "Approved";} else { echo "Not Approved"; }   ?> </td>
                                    </tr>
                                    <? }  ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <? $orderdata=selectQuery(ORDER." as od LEFT JOIN ".ORDERSUB." as o on od.id=o.order_id ","od.order_date,od.transaction_id,od.order_id,od.payable_amount,o.order_current_Status","od.user_id='".$userid."' AND od.payment_status<>'Initiated' group by o.order_id order by od.id DESC");  ?>                        
                <div class="card mb-0">
                    <a class="card-header sec-card-head justify-content-between align-items-center cc-cursor-pointer" data-toggle="collapse" href="#collapsproorder"><h2 class="card-head-title text-dark">Order (<?php echo count($orderdata) ?>)</h2></a>
                    <div class="card-body collapse" id="collapsproorder">
                        <div class="table-responsive">
                            <table class="display table table-bordered neworder-table">
                                    <thead><tr><th>#</th><th>Order Date</th> <th> Order Id </th><th>Amount</th>  <th>Stauts</th> </tr></thead>
                                    <tbody>
                                        <?php

                                        for($j=0;$j<count($orderdata);$j++){
                                        $cnt=$j+1; $row=$orderdata[$j]; ?>
                                        <tr>
                                            <td><?=$cnt; ?></td>
                                            <td><?=date("d M Y h:i A",strtotime($row['order_date'])) ?></td>
                                            <td class="text-primary cc-cursor-pointer"> <a href="<?php echo ADMINURL ?>order/order_details.php?transid=<? echo $row['transaction_id']; ?>"> <?php echo $row['order_id'] ?></a> </td>
                                            
                                            <td><?php  echo $row['payable_amount'] ?></td>
                                           <td><?=$row['order_current_Status']; ?></td>
                                           
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-hidden="false" >
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel1">Address in Detail</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" ><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>
    <?php include '../footer.php';?>
    <?php include '../order/order_common.php'; ?>
    </div>
</div>
<script src="<?php echo SITEURL; ?>/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script>  $('.livecart,.neworder-table,.rev-table').DataTable();

function show_hiddenaddr(i){
    $(".modal-body").html($("#hiddenaddr"+i).html());
    $("#myModal2").modal("show");
}

</script>
</body>
</html>