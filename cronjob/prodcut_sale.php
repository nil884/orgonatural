<? include("../includes/configuration.php"); 


//if  vendor sale date is started and admin have not added price then vedor sale price will become final price 
$get_sale_started_inv = selectQuery(PRODINFO,"*"," CURRENT_TIMESTAMP between vendor_sale_start_date and vendor_sale_end_date and admin_price = 0 and vendor_sale_price <>0 ");

for($i=0;$i<count($get_sale_started_inv);$i++){
   $getsale_price =  selectQuery(PRODINFO,"vendor_sale_price","id='".$get_sale_started_inv[$i]['id']."'");
        $data = array(
            'final_price' => $getsale_price[0]['vendor_sale_price'],
        );
        $update_table = updateQuery(PRODINFO, $data, "id =" . $get_sale_started_inv[$i]['id']);    
    
}



//if  vendor sale date is ended and admin have not added price then vedor regular price will become final price 
$get_sale_end_inv = selectQuery(PRODINFO,"*"," CURRENT_TIMESTAMP >= vendor_sale_end_date  and vendor_sale_end_date <> '0000-00-00 00:00:00' and admin_price = 0 ");
for($i=0;$i<count($get_sale_end_inv);$i++){
$getregular_price =  selectQuery(PRODINFO,"mrp","id='".$get_sale_end_inv[$i]['id']."'");
$data = array(
    'final_price' => $getregular_price[0]['mrp'],
   
);
$update_table = updateQuery(PRODINFO, $data, "id =" . $get_sale_end_inv[$i]['id']);   

}
?>