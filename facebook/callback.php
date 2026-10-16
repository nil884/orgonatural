<?php
include_once ("../includes/configuration.php");
require_once 'src/Facebook/autoload.php';

$fb = new Facebook\Facebook([
    'app_id' => FB_APPID,
    'app_secret' => FB_SECRETEKEY,
    'default_graph_version' => 'v2.12',
    'persistent_data_handler' => 'session'
 ]);

$helper = $fb->getRedirectLoginHelper();
$_SESSION['FBRLH_state']=$_GET['state'];
try {
  $accessToken = $helper->getAccessToken();
  $response = $fb->get('/me?fields=id,first_name,last_name,email,gender,locale,picture', $accessToken);
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

if (! isset($accessToken)) {
  if ($helper->getError()) {
    header('HTTP/1.0 401 Unauthorized');
    echo "Error: " . $helper->getError() . "\n";
    echo "Error Code: " . $helper->getErrorCode() . "\n";
    echo "Error Reason: " . $helper->getErrorReason() . "\n";
    echo "Error Description: " . $helper->getErrorDescription() . "\n";
  } else {
    header('HTTP/1.0 400 Bad Request');
    echo 'Bad request';
  }
  exit;
}

// Logged in
// The OAuth 2.0 client handler helps us manage access tokens
$oAuth2Client = $fb->getOAuth2Client();

// Get the access token metadata from /debug_token
$tokenMetadata = $oAuth2Client->debugToken($accessToken);

// Get user’s Facebook ID
/*$userId = $tokenMetadata->getField('user_id'); */
$user = $response->getGraphUser();
 
	$user_data =checkUser('facebook',$user['id'],$user['first_name'],$user['last_name'],$user['email'],$user['gender'],$user['locale'],$user['picture']['data']['url']);
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
if($_SESSION['setuser']!='setted'){
   $_SESSION['setuser']='setted';
   if($_SESSION['fbredirect']!=""){  $redirect= SITEURL."".$_SESSION['fbredirect'];   }
   else {  $redirect= SITEURL;  }
   echo '<script>window.location.href="'.$redirect.'";</script>';

 }
 else {  echo '<script>window.location.href="'.SITEURL.'";</script>'; }
?>