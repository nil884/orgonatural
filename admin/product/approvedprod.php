<? include("../../includes/configuration.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo SITENAME; ?> : Approved Product List</title>
    <? include "../commoncss.php"; ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/dataTable/dataTables.bootstrap4.min.css">
</head>
<body id="bodload">
<div class="page-body-wrapper" id="prod-list-col">
    <? include '../header.php'; ?>
    <? include '../sidebar.php'; ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center"><h5 class="card-head-title">Approved Product List</h5></div>
                <div class="card-body">
                    <div id="tablereload">
                        <table class="example table table-bordered product-data-table w-100" id="Vendor-product">
                            <thead><tr><th>#</th><th>Name</th><th>Company</th><th>Sub Category</th><th>Vendor</th><th>Active/Deactive</th><th>Show In</th><th>Delete</th></tr></thead>
                            <tbody id="loaddatatabel">
                                <?php $getproduct = selectQuery(PRODINFO,"id,prod_name,prod_company,vendor,sub_cat,hsn_code,isActive,new_arrival,trending_now,isApproved","isApproved  = '1' and parent_id = '0'   order by id desc ");
                                for($i=0;$i<count($getproduct);$i++){ ?>
                                <tr>
                                    <td><?php echo $i+1 ?></td>
                                    <td><a href="view-details.php?prod_id=<?php echo base64_encode($getproduct[$i]['id']) ?>"><?php echo $getproduct[$i]['prod_name'] ?></a></td>
                                    <td><?php if($getproduct[$i]['prod_company'] == "") { echo "NA"; } else { echo $getproduct[$i]['prod_company']; } ?></td>
                                    <td><?php $getsub=selectQuery(PRODCAT,"cat_name","id=".$getproduct[$i]['sub_cat']);echo $getsub[0]['cat_name']; ?></td>
                                    <td><?php $getseller = selectQuery(VENDOR,"*","dealer_id= '".$getproduct[$i]['vendor']."' "); echo $getseller[0]['nickname'] ?></td>
                                    <td>
                                        <label class="switch btn btn-primary">   
                                            <input type="checkbox" <?php if($getproduct[$i]['isActive'] == 1) {echo "checked"; } else{
                                            echo " "; } ?> id="act_deact<?php echo $getproduct[$i]['id'] ?>"  onchange="act_deact('<?php echo $getproduct[$i]['id'] ?>')"> 
                                            <span class="slider round"><span class="on">Active</span><span class="off">Deactive</span></span>
                                        </label> 
                                    </td>
                                    <td style="width:15%;"> 
                                    <div class="custom-control custom-checkbox custom-control-inline">
                                        <input type="checkbox" class="menu custom-control-input" id="trending_now<?php echo $getproduct[$i]['id'] ?>" <?php if($getproduct[$i]['trending_now'] == 1) {echo "checked"; } ?>  onchange="trending_now('<?php echo $getproduct[$i]['id'] ?>')" <?php if($getproduct[$i]['isApproved'] ==  0 || $getproduct[$i]['isActive'] ==  0){ echo "disabled"; } ?>> <label class="custom-control-label" for="trending_now<?php echo $getproduct[$i]['id'] ?>">Trending Now</label> 
                                        </div>
                                        <div>
                                        <div class="custom-control custom-checkbox custom-control-inline">
                                             <input type="checkbox" class="menu custom-control-input" id="new_arrival<?php echo $getproduct[$i]['id'] ?>" <?php if($getproduct[$i]['new_arrival'] == 1) {echo "checked"; } ?> onchange="new_arrival('<?php echo $getproduct[$i]['id'] ?>')" <?php if($getproduct[$i]['isApproved'] ==  0 || $getproduct[$i]['isActive'] ==  0){ echo  "disabled"; } ?>> <label class="custom-control-label mb-1" for="new_arrival<?php echo $getproduct[$i]['id'] ?>">New Arrival</label>
                                        </div>
                                            </div>                                        
                                    </td>
                                    <td><span class="removeopt pro-attr-badge-action btn btn-danger btn-sm" onclick="del_product('<?php echo $getproduct[$i]['id'] ?>' )"><i class="fa fa-trash"></i></span></td>
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
function del_product(i) {  
    msg = "Do you really want to delete this prodcut?";
    del_alertbox(msg, i,"del_product_db");
}
function del_product_db(id){
    info  = {prod_id:id,action:"Delete_product"}
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
    });
}

function act_deact(v1){
    var requestedid = v1;
    var c = $("#act_deact"+requestedid+":checked").val();
    if(c=="on"){ status = 1; res="activated";} else {status = 0; res="deactivated"; }
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
function new_arrival(v1){
	var requestedid = v1;
	var c = $("#new_arrival"+requestedid+":checked").val();
	if(c=="on"){ status = 1; res="Product is shown in new arrival list";} else {status = 0; res="Product is removed from  new arrival list"; }
	var info = {requestedid:requestedid,status:status,action:"new_arrival"};
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
function trending_now(v1){
	var requestedid = v1;
	var c = $("#trending_now"+requestedid+":checked").val();
	if(c=="on"){ status = 1; res="Product is shown in trending now list";} else {status = 0; res="Product is removed from   trending now list"; }
	var info={requestedid:requestedid,status:status,action:"trending_now"};
	$.ajax({
		type:"POST",
		url:"ajaxdata.php",
		data:info,
		success:function(response){
			if(response==1){
				$('.alert_msgs').fadeIn().removeClass("failactionmsg").addClass("successactionmsg").html(res).delay(3000).fadeOut();
			} else{
				$('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("ERROR..").delay(3000).fadeOut();
			}
		}
	});
}
</script>
</body>
</html>