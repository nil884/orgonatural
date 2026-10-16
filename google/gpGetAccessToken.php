<?php
include_once ("../includes/configuration.php");
include_once 'gpconfig.php';

 if(isset($_REQUEST['code'])){
    $gClient->authenticate($_REQUEST['code']);
    $_SESSION['token'] = $gClient->getAccessToken();
}

    if (isset($_SESSION['token'])){ $gClient->setAccessToken($_SESSION['token']);  }

    if ($gClient->getAccessToken()){
    	$userProfile = $google_oauthV2->userinfo->get();
      
        $gUser=checkUser('google',$userProfile['id'],$userProfile['given_name'],$userProfile['family_name'],$userProfile['email'],$userProfile['gender'],$userProfile['locale'],$userProfile['link'],$userProfile['picture']);
        $_SESSION['google_data'] = $userProfile;

        if($_SESSION['reguser']!=""){

                if($_SESSION['wishitems'] != "") { $item_array_id= array_column($_SESSION['wishitems'] ,"prod_id" );}
                if($_SESSION['shopping_cart'] != ""){ $cart_item_array_id= array_column($_SESSION['shopping_cart'] ,"prod_id" );}


                $cartdet1 = $_SESSION['shopping_cart'];
                if(count($cartdet1)){
                    for($i=0;$i<count($cartdet1);$i++){
                        $data=array(
                        'user_id'=>$_SESSION['reguser'],'type'=> 'CART' , 'prod_id'=> $cartdet1[$i]['prod_id'],'quantity'=> $cartdet1[$i]['quantity'],'insert_date'=> date("Y-m-d H:i:s"));
                        $checkincart =  selectQuery(CART,"count(cart_id) as total_cart_id","user_id='".$_SESSION['reguser']."' and type ='CART' and prod_id = '".$cartdet1[$i]['prod_id']."' ");


                        if($checkincart[0]['total_cart_id'] == 0){
                         insertQuery(CART,$data);
                        }
                    }
                    unset($_SESSION['shopping_cart']);
                }

                $get_cart = selectQuery(CART,"prod_id,quantity","user_id=".$_SESSION['reguser']." and type='CART' ");
                $wisharrayl = array();
                if(count($get_cart)) {
                    for($z=0;$z<count($get_cart);$z++){
                        //if(!in_array($get_cart[$z]['prod_id'] ,$cart_item_array_id )){
                            $count= count($_SESSION['shopping_cart']);
                            $item_array=array('prod_id'=>$get_cart[$z]['prod_id'],'quantity'=>$get_cart[$z]['quantity']);
                            $_SESSION['shopping_cart'][$count] = $item_array;
                        //}
                    }
                }

                $wishdetails = $_SESSION['wishitems'];
                if(count($wishdetails)){
                    for($i=0;$i<count($wishdetails);$i++){
                        $data=array(
                        'user_id'=>$_SESSION['reguser'],'type'=> 'WISHLIST' , 'prod_id'=> $wishdetails[$i]['prod_id'],'quantity'=> $wishdetails[$i]['quantity'],'insert_date'=> date("Y-m-d H:i:s"));
                        $checkinwishlist =  selectQuery(CART,"count(cart_id) as total_cart_id","user_id='".$_SESSION['reguser']."'  and type ='WISHLIST' and prod_id = '".$wishdetails[$i]['prod_id']."' ");
                        if($checkinwishlist[0]['total_cart_id'] == 0){
                            $insert=insertQuery(CART,$data);
                        }
                    }
                    unset($_SESSION['wishitems']);
                }

                $get_wishlist = selectQuery(CART,"prod_id,quantity","user_id=".$_SESSION['reguser']." and type='WISHLIST' ");
                if(count($get_wishlist)) {
                    for($z=0;$z<count($get_wishlist);$z++){
                       // if(!in_array($get_wishlist[$z]['prod_id'] ,$item_array_id )){
                            $count= count($_SESSION['wishitems']);
                            $item_array=array('prod_id'=>$get_wishlist[$z]['prod_id'],'quantity'=>$get_wishlist[$z]['quantity']);
                            $_SESSION['wishitems'][$count] = $item_array;

                        //}
                    }
                }
        }

        echo '<script>window.location.href="'.SITEURL.'";</script>';
    	$_SESSION['token'] = $gClient->getAccessToken();
        if($_SESSION['setuser']!='setted'){
           $_SESSION['setuser']='setted';
           echo '<script>window.location.href="'.SITEURL.'";</script>';
         }

    } else { $authUrl = $gClient->createAuthUrl(); }

    if(isset($authUrl)) { echo '<script>window.location.href="'.SITEURL.'";</script>'; }
?>