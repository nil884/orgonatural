<?
class User{
    public function __construct(){}

    public function getUserDetails($data,$id){
       $udetail = selectQuery(BUYER, $data, "u_id=" . $id);
       return $udetail;
    }
    public function getShippingAddress($id){
      $udetail = selectQuery(ADDRESS, "id,address_type,address_name,mobile_number,address,landmark,city,state,country,pincode,is_default", "user_id=" . $id." order by is_default DESC");
       return $udetail;
    }

      public function getShippingDetails($id){
      $udetail = selectQuery(ADDRESS, "id,address_type,address_name,mobile_number,address,landmark,city,state,country,pincode,is_default", "id=" . $id);
       return $udetail;
    }

    public function checkDuplicateShippingAddress($user,$fullname,$mobile,$pincode,$address,$location,$city,$state){
      $udetail = selectQuery(ADDRESS, "id", "user_id=" . $user." and address_name='".ucwords($fullname)."'  and mobile_number='".$mobile."'  and address='".$address."'  and landmark='".$location."'  and city='".$city."'  and state='".$state."'  and pincode='".$pincode."'");
      if(count($udetail)){ return $udetail[0]['id'];}else{return 0;}

    }

    public function addShippingAddress($user,$fullname,$mobile,$pincode,$address,$location,$city,$state,$country,$adress_type){
       
        $data=array("user_id"=>$user,"address_name"=>ucwords($fullname),"mobile_number"=>$mobile,"address"=>$address,"landmark"=>$location,"city"=>$city,"state"=>$state,"pincode"=>$pincode,"address_type" => $adress_type , "country" => $country );
        
      $udetail = insertQuery(ADDRESS, $data);
       return $udetail;
    }

    public function getReview($userid){
        $getdata=selectQuery(REVIEW." as r JOIN ".PRODINFO." as p on r.prod_id=p.id" ,"p.id,p.prod_name,r.prod_id,r.review_id,r.review_title,r.review,r.rate,r.review_date,r.isApproved","r.user_id=".$userid."  order by r.review_id DESC  ");
         return $getdata;
      }
}
?>