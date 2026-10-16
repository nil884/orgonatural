<?
include "../includes/configuration.php";

$before10min=date("Y-m-d H:i:s",strtotime('-30 minutes'));

$getBlocked=selectQuery(BLOCK,"prod_id,quantity,block_id,transaction","blocktime<'".date("Y-m-d H:i:s",strtotime($before10min))."'");
for($i=0;$i<count($getBlocked);$i++){
    $prodid=$getBlocked[$i]['prod_id'];    $quantity=$getBlocked[$i]['quantity']; $block_id=$getBlocked[$i]['block_id'];  $transaction=$getBlocked[$i]['transaction'];
    $getProdBlock=selectQuery(PRODINFO,"blocked","id=".$prodid);
    $updateblock=$getProdBlock[0]['blocked']-$quantity;
    $data=array("blocked"=>$updateblock);
    updateQuery(PRODINFO,$data,"id=".$prodid);
   // deleteQuery(ORDER,"transaction_id='".$transaction."'"); 
     deleteQuery(BLOCK,"block_id=".$block_id);
}
?>