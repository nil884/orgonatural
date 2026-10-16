<? include("../../includes/configuration.php");
include("../../classes/order.php"); require_once('../../classes/user.php'); require_once('../../classes/product.php');
$prod = new Product(); $user = new User(); $order = new Order($prod,$user); 
 if($_GET){
    $from= date("Y-m-d",strtotime($_GET['from_date']))." 00:00:00";
    $to= date("Y-m-d",strtotime($_GET['to_date']))." 23:59:59";     
    $wherestr=" AND (o.order_date between '".$from."' AND '".$to."')";
}else{
    $wherestr="";  
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo SITENAME; ?> : Itemwise Live Orders</title>
    <? include "../commoncss.php"; ?>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/dataTable/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/datepicker/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/dataTable/dataTable.buttons.min.css" /> 
</head>
<body>
<div class="page-body-wrapper">
    <? include '../header.php'; ?>
    <? include '../sidebar.php'; 
      $items=selectQuery(ORDERSUB." as s JOIN ".ORDER." as o on s.order_id=o.id","o.order_id,o.order_date,s.product_id,s.product_name,s.product_image,SUM(s.quantity) as qty,s.variation_on,s.variation_values","(s.order_current_Status='Generated' OR s.order_current_Status='New') ".$wherestr." group by s.product_id order by s.product_name ASC"); 
    ?>
    <div class="main-panel">
        <div class="dashbody">
            <div class="card mb-0">
                <div class="card-header sec-card-head justify-content-between align-items-center py-2"><div><h2 class="card-head-title">Itemwise Orders <?=($_GET?" (".date('d M Y',strtotime($from)).' To '.date('d M Y',strtotime($to)).")":""); ?></h2></div>
                <div class="prof-rep-excel"><button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModalLong">By Date</button> </div>
            </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="display table table-bordered neworder-table">
                            <thead><tr><th>#</th><th>Product</th><th>Parent Category</th><th>Master Category</th><th>Sub Category</th><th>Weight/Piece</th><th>Total Quantity</th><th>Total Weight/Piece</th></tr></thead>
                            <tbody>
                                <?php 
                                if(count($items)){
                                for($j=0;$j<count($items);$j++){  $row=$items[$j]; 
                               /*  $prodinfo=$prod->getTemplateData($row['product_id']);
                                $weight=$prodinfo['Weight']; $piece=$prodinfo['Piece']; */
                                $gettemplate=selectQuery(PRODINFO." as p LEFT JOIN ".PRODCAT." as sc on p.sub_cat=sc.id LEFT JOIN ".PRODCAT." as mc on p.master_cat=mc.id LEFT JOIN ".PRODCAT." as pc on p.parent_cat=pc.id","sc.template,sc.cat_name as subcat,mc.cat_name as mastercat,pc.cat_name as parentcat","p.id=".$row['product_id']);
                                $template=$gettemplate[0]['template'];
                                $gettemplatedata=selectQuery($template,"*","prod_id=".$row['product_id']);
                                $retval=showQuery($template);
                                $tablcol=array();
                                $weight=""; $piece=""; $bunch=""; $weightby="";
                                for($i=0;$i<count($retval);$i++){ array_push($tablcol,$retval[$i][0]);   }
                                for($i=3;$i<count($tablcol);$i++){
                                    $attribute=$tablcol[$i];  $attrname=getOriginalName($attribute);
                                    if($attrname=="Weight"){$weight=$gettemplatedata[0][$attribute]; $weightby=$weight;
                                    }else if($attrname=="Piece"){$piece=$gettemplatedata[0][$attribute]; $weightby=$piece; }
                                    else if($attrname=="Bunch"){$bunch=$gettemplatedata[0][$attribute]; $weightby=$bunch; }
                                }
                                ?>
                                <tr>
                                   
                                    <td><?=$j+1; ?></td>
                                    <td><a href="<?=SITEURL;?>/<?=getUrl("Product",$row['product_id']); ?>" hreflang="en" target="_blank"><?=$row['product_name']; ?> <?=(!$template?"(Product Deleted)":""); ?> </a></td>
                                    <td><?=$gettemplate[0]['parentcat']; ?></td>
                                    <td><?=$gettemplate[0]['mastercat']; ?></td>
                                    <td><?=$gettemplate[0]['subcat']; ?></td>
                                    <td><?php
                                    if($weight){ echo $weight; }
                                    if($piece){ echo $piece; }
                                    if($bunch){ echo $bunch; }
                                    ?></td>
                                   <td> <?=$row['qty']; ?></td> 
                                    <td>
                                     <? $war=explode(" ",$weightby);  $val=$war[0]; $valby=$war[1];
                                     echo ($val?$val*$row['qty']." ".$valby:$row['qty']);
                                     ?>   
                                    </td>
                                </tr>
                                <?php }
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
       <? include "../footer.php"; ?>
    </div>
</div>
<div class="modal" tabindex="-1" id="exampleModalLong">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Get Order Products</h5> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
            <div class="modal-body">
                <form id="report" method="GET" action="itemwise_orders.php">
                    <div class="row"> 
                        <div class="col-12 mb-2">
                            <label>From Date</label>
                            <input type="text" name="from_date" placeholder="From Date" class="form-control" id="from_date">
                        </div>
                        <div class="col-12">
                            <label>To Date</label>
                            <input type="text" name="to_date" placeholder="To Date" class="form-control" id="to_date">
                        </div>                            
                    </div>
                    <div class="mt-3 text-right"><button type="button" class="btn btn-primary generate"  onclick="generatereport()">Show</button></div>
                </form>
            </div>

        </div>
    </div>
</div>
<?php include 'order_common.php'; ?>
<script src="<?php echo SITEURL; ?>/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/dataTables.buttons.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/jszip.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/buttons.html5.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/buttons.colVis.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/dataTable/buttons.bootstrap4.min.js"></script>
<script src="<?php echo SITEURL; ?>/js/datepicker/moment.js"></script>
<script src="<?php echo SITEURL; ?>/js/datepicker/bootstrap-datetimepicker.min.js"></script>
<script>
var table = $('.neworder-table').DataTable({ buttons: ['excel'] });
table.buttons().container().appendTo('.prof-rep-excel');
    
$("#from_date").datetimepicker({ format: 'DD-MM-YYYY', maxDate: moment(), });
$("#to_date").datetimepicker({ format: 'DD-MM-YYYY', maxDate: moment(), });
function generatereport(){
    var date1 = $("#from_date").val();
    var date2 = $("#to_date").val();
    var d1 = date1.split("-");
    var d2 = d1[2] + "" + d1[1] + "" + d1[0];
    var d3 = date2.split("-");
    var d4 = d3[2] + "" + d3[1] + "" + d3[0];
    var diff = d4 - d2;
    if (date1 == "" || date2 == ""){
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("Please enter from and to dates").delay(3000).fadeOut();
    } else if (diff < 0) {
        $('.alert_msgs').fadeIn().removeClass("successactionmsg").addClass("failactionmsg").html("From date may not be greater than To date").delay(3000).fadeOut();
    } else { $("#report").submit() }
}
</script>
</body>
</html>