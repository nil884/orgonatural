<?php include "../../includes/configuration.php";
    if ($action == "aboutus_data") {
        $data = array("about_us" => addslashes($aboutus_data),"about_us_data"=>$data_count);
        $pdate = updateQuery(STATIC_PAGE, $data, "id= 1");
        if ($pdate) { echo 1; }
        else { echo 0; }
    } if($action == "terms_condition"){
        $data = array("terms_condition" => addslashes($terms_condition),"terms_condition_data"=>$data_count);
        $pdate = updateQuery(STATIC_PAGE, $data, "id= 1");
        if($pdate){ echo 1; }
        else{ echo 0; }
    } if($action == "privacy_policy"){
        $data = array("privacy_policy" => addslashes($privacy_policy),"privacy_policy_data"=>$data_count);
        $pdate = updateQuery(STATIC_PAGE, $data, "id= 1");
        if($pdate){ echo 1; }
        else{ echo 0; }
    } if($action == "Faq"){
        $data = array("Faq" => addslashes($Faq),"faq_data"=>$data_count);
        $pdate = updateQuery(STATIC_PAGE, $data, "id= 1");
        if($pdate){ echo 1; }
        else{ echo 0; }
    } if($action == "cancellation_refund"){
        $data = array("cancellation_refund" => addslashes($cancellation_refund),"cancellation_refund_data"=>$data_count);
        $pdate = updateQuery(STATIC_PAGE, $data, "id= 1");
        if($pdate){ echo 1; }
        else{ echo 0; }
    }
     if($action == "vendor_terms_condition"){
        $data = array("vendor_terms_condition" => addslashes($terms_condition),"vendor_terms_condition_data"=>$data_count);
        $pdate = updateQuery(STATIC_PAGE, $data, "id= 1");
        if($pdate){ echo 1; }
        else{ echo 0; }
    } 
    if($action == "international_shipping_data"){
        $data = array("international_ship" => addslashes($shipping_data),"international_ship_data"=>$data_count);
        $pdate = updateQuery(STATIC_PAGE, $data, "id= 1");
        if($pdate){ echo 1; }
        else{ echo 0; }
    }
?>