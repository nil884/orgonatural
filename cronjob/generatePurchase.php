<?
include "../includes/configuration.php";
    $getOrder=selectQuery(ORDER,"id,order_id,order_date","id=275");
    for($i=0;$i<count($getOrder);$i++){
      $row=$getOrder[$i]; $rowid=$row['id'];$order_id=$row['order_id'];
      $chkord=selectQuery(PURCH,"pur_id","reference_order_id=".$order_id);
      if(!count($chkord)){
           $getdata=selectQuery(ORDERSUB,"*","order_id=".$rowid);
             for($j=0;$j<count($getdata);$j++){
            $row1=$getdata[$j]; $idcnt=$j+1;
            $subordid= $order_id."-".$idcnt;
              $productid=$row1['product_id']; $quantity=$row1['quantity']; $vendorid=$row1['vendor'];
              /* create purchase order */
                $purchasedata=array("reference_order_id"=>$rowid,"reference_order_sub_id"=>$row1['item_id'],"purchase_order_id"=>$subordid,"purchase_date"=>$row['order_date'],"purchase_from_vendor"=>$vendorid);
                $chkord=selectQuery(PURCH,"pur_id","reference_order_id=".$rowid." AND reference_order_sub_id=".$row1['item_id']);
                if(!count($chkord)){insertQuery(PURCH,$purchasedata);}
            }

      }
    }
?>