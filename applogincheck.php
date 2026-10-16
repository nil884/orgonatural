<?php include "includes/configuration.php";

$_GET['user']=file_get_contents("php://input");
//file_put_contents("outputfilecheck.txt","\n".date("d M Y H:i:s")."    Session ".session_id()."\n",FILE_APPEND);
//$json=file_get_contents("php://input");

//$check=($_SESSION['reguser']!=""?"session varified":"Session not getting");
//file_put_contents("outputfilecheck.txt",date('d M Y H:i:s')." -  ".$check."\n",FILE_APPEND);
if($_GET['user']){
    //file_put_contents("outputfilecheck.txt",date('d M Y H:i:s')." -  Get parameter received trying to create session again\n",FILE_APPEND);  
    $_SESSION['reguser']=$_GET['user'];
    if($_SESSION['reguser']){
        $get_cart = selectQuery(CART,"prod_id,quantity","user_id=".$_SESSION['reguser']." and type='CART' ");
        if(count($get_cart)) {
            for($z=0;$z<count($get_cart);$z++){
                //if(!in_array($get_cart[$z]['prod_id'] ,$cart_item_array_id )){
                    $count= count($_SESSION['shopping_cart']);
                    $item_array=array('prod_id'=>$get_cart[$z]['prod_id'],'quantity'=>$get_cart[$z]['quantity']);
                    $_SESSION['shopping_cart'][$count] = $item_array;
                //}
            }
        }
        $get_wishlist = selectQuery(CART,"prod_id,quantity","user_id=".$_SESSION['reguser']." and type='WISHLIST' ");
        if(count($get_wishlist)) {
            for($z=0;$z<count($get_wishlist);$z++){
             
                    $count= count($_SESSION['wishitems']);
                    $item_array=array('prod_id'=>$get_wishlist[$z]['prod_id'],'quantity'=>$get_wishlist[$z]['quantity']);
                    $_SESSION['wishitems'][$count] = $item_array;
                
            }
        }
       // file_put_contents("outputfilecheck.txt",date('d M Y H:i:s')." -   Session created again redirecting to main page...\n",FILE_APPEND);    
        header("Location:index.php");
    }
}
?>