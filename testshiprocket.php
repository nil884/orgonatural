<?
ini_set("display_errors",1);
include("includes/configuration.php");
include "classes/shiprocket.php";
/*$username=SHIPUSER; $pasword=SHIPPWD;
$ship=new shiprocket($username,$pasword);
 $token=$ship->authenticate();
 $pickups=$ship->getPickups($token);

 $data= array("pickup_postcode"=>"411037",
"delivery_postcode"=>"411027",
"cod"=>1,
"declared_value"=>399,
"weight"=>"1",
"is_return"=>0);

$rate=$ship->getServiceability($token,$data);
$recoment=$rate['data']['shiprocket_recommended_courier_id'];
$avail=$rate['data']['available_courier_companies'];
$id = array_search($recoment, array_column($avail, 'courier_company_id'));

echo "<pre>";
print_r($avail[$id]);
echo "</pre>";*/
 $date=date("Y-m-d H:i:s");   $reqno=strtotime($date);  echo $reqno;
?>