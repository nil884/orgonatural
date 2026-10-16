<? include("../../includes/configuration.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo SITE_TITLE; ?> : View Product</title>
    <? include "../commoncss.php"; ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/dataTable/dataTables.bootstrap4.min.css">
</head>
<body>
<div class="page-body-wrapper">
    <? include '../header.php'; ?>
    <? include '../sidebar.php'; ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center"><h2 class="card-head-title">Product List</h2></div>
                <div class="card-body">
                    <div id="tablereload">
                        <table class="table table-bordered product-data-table w-100" id="Vendor-product">
                            <thead><tr><th>#</th><th>Name</th><th>Company</th><th>Sub Category</th><th>Active/Deactive</th><th>Delete</th></thead>
                            <tbody>
                                <?php 
                                $getproduct = selectQuery(PRODINFO,"id,prod_name,prod_company,sub_cat,isApproved,isActive","vendor='".$get_vendor_details[0]['dealer_id']."' and parent_id = '0'  order by id desc");
                                for($j=0;$j<count($getproduct);$j++){ ?>
                                <tr>
                                    <td><?php echo $j+1 ?></td>
                                    <td><a href="view-details.php?prod_id=<?php echo base64_encode($getproduct[$j]['id']) ?>"><?php echo $getproduct[$j]['prod_name'];; ?></a></td>
                                    <td><?php if($getproduct[$j]['prod_company'] == ""){ echo "NA"; } else{ echo $getproduct[$j]['prod_company']; } ?></td>
                                    <td><?php $getsub=selectQuery(PRODCAT,"cat_name","id=".$getproduct[$j]['sub_cat']);echo $getsub[0]['cat_name']; ?></td>
                                    <td>
                                        <?php if($getproduct[$j]['isApproved'] == 1){ ?>
                                        <label class="switch btn btn-primary">
                                            <input type="checkbox" id="act_deact<?php echo $getproduct[$j]['id'] ?>" <?php if($getproduct[$j]['isActive'] == 1) {echo "checked"; } else{echo " ";}?> onchange="act_deact('<?php echo $getproduct[$j]['id'] ?>')"> 
                                            <div class="slider round"><span class="on">Active</span><span class="off">Deactive</span></div>
                                        </label>
                                        <?php } else{ echo "pending"; }?>
                                    </td>
                                    <td><button type="button" class="removeopt pro-attr-badge-action btn btn-danger btn-sm" onclick="del_product('<?php echo $getproduct[$j]['id'] ?>')"><i class="fa fa-trash"></i></button></td>
                                </tr>
                                <?php } ?>
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
loaddatatable();
function loaddatatable(){ $('.product-data-table').DataTable({ "scrollX": true }); }
function act_deact(v1){
    var requestedid = v1;
    var c = $("#act_deact"+requestedid+":checked").val();
    if(c=="on"){ status = 1; res="Activated";} else {status = 0; res="De-Activated"; }
    var info = {requestedid:requestedid,status:status,action:"active_deactive"};
    $.ajax({
        type:"POST",
        url:"ajaxdata.php",
        data:info,
        success:function(response){
            if(response==1){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Product " +res).delay(3000).fadeOut();
            } else{
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("ERROR..").delay(3000).fadeOut();
            }
        }
    });
}
function del_product(i){  msg = "Do you really want to delete this product?"; del_alertbox(msg, i,"del_product_db"); }
function del_product_db(id){
    info = {prod_id:id,action:"Delete_product"}
    $.ajax({
        type:"POST",
        url:"ajaxdata.php",
        data:info,
        success:function(response){
            response=response.trim();
            if(response== "1"){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Product Deleted Successfully").delay(3000).fadeOut();
                $("#del_popup").modal("hide"); 
                $("#tablereload").load(location.href + " #Vendor-product"); setTimeout(function(){ loaddatatable();}, 500);
            }
            else if(response=="0"){
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error.. Try Again Later").delay(3000).fadeOut();
            }
        }
    })
}    
</script>
</body>
</html>