<? include("../../includes/configuration.php"); 
$getproduct = selectQuery(PRODINFO,"id,prod_name,prod_company,vendor,hsn_code,hsn_code,vendor","isApproved = '0' and parent_id = '0' order by id desc"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo SITENAME; ?> : Pending Product</title>
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
                <div class="card-header sec-card-head justify-content-between align-items-center py-2">
                    <h5 class="card-head-title mb-2 mb-sm-0">Pending Product List</h5>
                    <?php if(count($getproduct)) { ?>      
                    <input type="button" id="approve_all" value="Approve All" class="btn btn-primary btn-sm px-3">
                    <?php } ?>
                </div>
                <div class="card-body"> 
                    <div id="tablereload">
                        <table class="table table-bordered product-data-table w-100" id="Vendor-product">
                            <thead><tr><th>#</th><th>Name</th><th>Company</th><th>Sub Category</th><th>Vendor</th><th>Approve</th> <th>Delete</th></thead>
                            <tbody>
                                <?php for($i=0;$i<count($getproduct);$i++){ ?>
                                <tr>
                                    <td><?php echo $i+1 ?></td>
                                    <td><a href="view-details.php?prod_id=<?php echo base64_encode($getproduct[$i]['id']) ?>"><?php echo $getproduct[$i]['prod_name'] ?></a></td>
                                    <td><?php if($getproduct[$i]['prod_company'] == ""){ echo "NA"; } else{ echo $getproduct[$i]['prod_company']; } ?></td>
                                    <td><?php $getsub=selectQuery(PRODCAT,"cat_name","id=".$getproduct[$i]['sub_cat']);echo $getsub[0]['cat_name']; ?></td>
                                    <td><?php $getseller = selectQuery(VENDOR,"*","dealer_id= '".$getproduct[$i]['vendor']."' ");
                                    echo $getseller[0]['nickname'] ?></td>
                                    <td>
                                        <label class="switch btn btn-primary">   
                                            <input type="checkbox" id="approve<?php echo $getproduct[$i]['id'] ?>" <?php if($getproduct[$i]['isApproved'] == 1){echo "checked"; } else{ echo " "; } ?> onchange="approve('<?php echo $getproduct[$i]['id'] ?>')"> 
                                            <span class="slider round"><span class="on">Active</span><span class="off">Deactive</span></span>
                                        </label> 
                                    </td>
                                    <td class="del-btn-tr"><span class="removeopt pro-attr-badge-action btn btn-danger" onclick="del_product('<?php echo $getproduct[$i]['id'] ?>')"><i class="fa fa-trash"></i></span></td>
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
function loaddatatable(){$('.product-data-table').DataTable({ "scrollX": true });}
function approve(v1){
    var requestedid = v1;
    var c = $("#approve"+requestedid+":checked").val();
    if(c=="on"){ status = 1; res="approveded";} else {status = 0; res="not approved"; }
    var info = {requestedid:requestedid,status:status,action:"approve"};
    $.ajax({
        type:"POST",
        url:"ajaxdata.php",
        data:info,
        success:function(response){
            if(response==1){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Product " +res).delay(3000).fadeOut();
               $("#tablereload").load(location.href + " #Vendor-product"); setTimeout(function(){ loaddatatable();}, 500);
            } else{
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("ERROR..").delay(3000).fadeOut();
            }
        }
    });
}
$("#approve_all").click(function(){
    var info = {action:"approve_all"};
    $.ajax({
        type:"POST",
        url:"ajaxdata.php",
        data:info,
        success:function(response){
            if(response==1){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("All product  approved successfully" ).delay(3000).fadeOut();
                location.reload();
            } else{
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("ERROR..").delay(3000).fadeOut();
            }
        }
    });
});
function del_product(i){  
    msg = "Do you really want to delete this product?";
    del_alertbox(msg, i,"del_product_db");
}

function del_product_db(id) {
    info = {prod_id:id,action:"Delete_product"}
    $.ajax({
        type:"POST",
        url:"ajaxdata.php",
        data:info,
        success:function(response){
        response=response.trim();
            if(response== "1"){
                $('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html("Product deleted successfully").delay(3000).fadeOut();
                $("#del_popup").modal("hide"); 
                $("#tablereload").load(location.href + " #Vendor-product"); setTimeout(function(){ loaddatatable();}, 500);

            }
            else if(response=="0"){
                $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Error.. Try again later").delay(3000).fadeOut();
            }
        }
    })
}   
</script>   
</body>
</html>