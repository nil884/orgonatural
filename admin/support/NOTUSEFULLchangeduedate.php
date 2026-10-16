<?php
    include("../../includes/configuration.php");
     $requestid=$_REQUEST['requestid'];
       $due=$_REQUEST['newdue'].":00";
       $data=array(
        "overdue_date"=>$due,
        "isOverdue"=>"0"
       );
         $ticketdetails=updateQuery(CONTACT,$data,"contact_request_id='".$requestid."'");
       if($ticketdetails)
       {
           echo 1;
       }
       else
       {
           echo 0;
       }
  ?>