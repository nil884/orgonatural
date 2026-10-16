<?php include("../includes/configuration.php");
include("../classes/order.php"); require_once('../classes/user.php'); require_once('../classes/product.php');
$prod = new Product(); $user = new User(); $order = new Order($prod,$user);
if($action == "search"){
$search = $_REQUEST['search'];
if($search!=""){
    $getuser = selectQuery(BUYER,"*","(u_fname like '%".$search."%' || u_lname like '%".$search."%'  ||u_email like '%".$search."%'||u_mobile like '%".$search."%')");  
    $orderdata = $getdata=selectQuery(PURCH." as p JOIN ".ORDERSUB." as o on p.reference_order_sub_id=o.item_id  inner JOIN ".ORDER." as od on o.order_id= od.id","p.purchase_date,p.purchase_order_id,o.display_product_name,o.variation_on,o.variation_values,o.item_id, o.quantity,od.transaction_id,od.order_id,o.order_current_Status","p.purchase_order_id like '%".$search."%' || p.purchase_date like '%".$search."%'  order by p.pur_id DESC");
    if(count($getuser)){  
    $userid=$getuser[0]['u_id']; ?>
    <div class="table-responsive">
        <table class="usert table table-bordered mt-3 mb-0">
            <thead><tr><th>#</th><th>Name</th><th>Mobile No</th><th>Email</th><th>Last Login</th><th>Status</th></tr></thead>
            <tbody>
                <?php for($i=0;$i<count($getuser);$i++){ ?>
                <tr>
                    <td><?php echo $i+1; ?></td>
                    <td><a href="<?php echo ADMINURL; ?>buyer/buyersdetails.php?u_id=<?php echo base64_encode($getuser[$i]['u_id']); ?>" target="blank"><?php echo $getuser[$i]['u_fname'].' '.$getuser[$i]['u_lname']; ?></a></td>
                    <td><?php echo $getuser[$i]['u_mobile']; ?></td>
                    <td><?php if(($getuser[$i]['email_verified'] == 1) && ($getuser[$i]['isActive'] == 1)){?><span><?php echo $getuser[$i]['u_email']; ?></span> <?}else{ ?><span><?php echo $getuser[$i]['u_email']; ?></span> <? } ?></td>
                    <td><?php echo $getuser[$i]['last_login']; ?><?php if($getuser[$i]['last_login']!=""){?> <br><a href="<?php echo ADMINURL; ?>buyer/buyerlog.php?buyerid=<?php echo base64_encode($getuser[$i]['u_id']); ?>" target="_blank">More Details</a><?} else{ echo "-";} ?></td>
                    <td><?php if($getuser[$i]['isActive']==1){echo "<span class='badge badge-success'><i class='fa fa-check'></i> Active</span>";}else{echo "<span class='badge badge-danger'><i class='fa fa-close'></i> Deactive</span>";} ?></td>
                </tr>
                <? } ?>
            </tbody>
        </table>
    </div>
    <? } else if(count($orderdata)){ ?>
    <div class="table-responsive" >
        <table class="display table table-bordered neworder-table">
            <thead><tr><th>#</th><th>Order Date</th><th>Order Id</th> <th>Status</th><th>Sub-Order ID</th><th>Product Details</th><th>Quantity</th></tr></thead>
            <tbody>
                <?php for($j=0;$j<count($orderdata);$j++){
                $cnt = $j+1; ?>
                <tr>
                    <td><?=$cnt; ?></td> 
                    <td><?=date("d M Y h:i A",strtotime($orderdata[$j]['purchase_date'])) ?></td>
                    <td class="text-primary cc-cursor-pointer"><a href="<?php echo ADMINURL ?>order/order_details.php?transid=<?php echo $orderdata[$j]['transaction_id'] ?>"><?php echo $orderdata[$j]['order_id'] ?> </a></td> 
                    <td> <?php  echo $orderdata[$j]['order_current_Status'] ?> </td>
                    <td class="text-primary cc-cursor-pointer" onclick="get_item(<?php echo $orderdata[$j]['item_id'] ?>)"><a> <?=$orderdata[$j]['purchase_order_id']; ?></a></td>
                    <td><?=$orderdata[$j]['display_product_name']; ?><br>
                    <? $variationon = $orderdata[$j]['variation_on'];
                    if($variationon!=""){
                    $variationonarr = explode("|",$variationon); $variativaluearr = explode("|",$orderdata[$j]['variation_values']);
                    for($v=0;$v<count($variationonarr);$v++){ ?><span><?=$variationonarr[$v]; ?> : <?=$variativaluearr[$v]; ?></span><? } } ?>
                    </td>
                    <td><?=$orderdata[$j]['quantity'];?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
<?php } } }
if($action == "date_range_search"){
    $fromdt = date("Y-m-d 00:00:00", strtotime($_REQUEST['fromdt']));
    $todt = date("Y-m-d 23:59:59", strtotime($_REQUEST['todt']) ) ;
    $user_of_currant_month = selectQuery(BUYER,"count(u_id) as u_id","(reg_date between '".$fromdt."' and '".$todt."') ");
    $vendor_of_currant_month = selectQuery(VENDOR,"count(dealer_id) as dealer_id","(insdate between '".$fromdt."' and '".$todt."') ");
    $order_of_currant_month = selectQuery(PURCH,"count(pur_id) as pur_id","(purchase_date  between '".$fromdt."' and '".$todt."') ");
    $resdata = array("user" => $user_of_currant_month[0]['u_id'],"vendor" => $vendor_of_currant_month[0]['dealer_id'],"order" => $order_of_currant_month[0]['pur_id'] );
    echo json_encode($resdata); 
} ?>