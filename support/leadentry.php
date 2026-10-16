<?php include("../includes/configuration.php");
    $supportmail = selectQuery(SUPPORTEMAIL, "*");
    $action=$_REQUEST['action'];
    if($action=="add_lead"){
    $reqid = $_REQUEST['leadno'];
    $fname = $_REQUEST['firstame'];
    $mname = $_REQUEST['middleame'];
    $lname = $_REQUEST['lastame'];
    $tele = $_REQUEST['mobile'];
    $email = $_REQUEST['email'];
    $dateoftravelex = $_REQUEST['dateoftravel'];
    if($dateoftravelex!=""){
        $dateoftravel= date('Y-m-d', strtotime($dateoftravelex));
    }
    else{
        $dateoftravel= "";
    }
    $refferedby = $_REQUEST['refferedby'];
    $adult = $_REQUEST['adult'];
    $kids = $_REQUEST['kids'];
    $tourtype = $_REQUEST['tourtype'];
    $destiarray=array();
    $country1 = $_REQUEST['country1'];
    $city1 = $_REQUEST['city1'];
    $stay1 = $_REQUEST['stay1'];
    if($country1!=""||$city1!=""||$stay1!=""){
        $dest1=$country1."-".$city1."-".$stay1;
        array_push($destiarray,$dest1);
    }
    $country2 = $_REQUEST['country2'];
    $city2 = $_REQUEST['city2'];
    $stay2 = $_REQUEST['stay2'];
    if($country2!=""||$city2!=""||$stay2!=""){
        $dest2=$country2."-".$city2."-".$stay2;
        array_push($destiarray,$dest2);
    }
    $country3 = $_REQUEST['country3'];
    $city3 = $_REQUEST['city3'];
    $stay3 = $_REQUEST['stay3'];
    if($country3=""||$city3!=""||$stay3!=""){
        $dest3=$country3."-".$city3."-".$stay3;
        array_push($destiarray,$dest3);
    }
    $country4 = $_REQUEST['country4'];
    $city4 = $_REQUEST['city4'];
    $stay4 = $_REQUEST['stay4'];
    if($country4!=""||$city4!=""||$stay4!=""){
        $dest4=$country4."-".$city4."-".$stay4;
        array_push($destiarray,$dest4);
    }

        $country5 = $_REQUEST['country5'];
        $city5 = $_REQUEST['city5'];
        $stay5 = $_REQUEST['stay5'];
        if($country5!=""||$city5!=""||$stay5!="")
        {
          $dest5=$country5."-".$city5."-".$stay5;
          array_push($destiarray,$dest5);
        }
         if(sizeof($destiarray))
         {
             $destination=implode(",",$destiarray);
         }
         else
         {
            $destination="";
         }
        $cmnt = $_REQUEST['comments'];
        $assign = $_REQUEST['assign'];

         $getstaff=selectQuery(SUPPORTSTAFF,"*","department=".$_SESSION['dept']." end emp_name='".$_SESSION['staffname']."' and isDel='0'");
          $assigendstaff=selectQuery(SUPPORTSTAFF,"*","emp_id=".$assign);
           $assigenddept=selectQuery(SUPPORTDEPT,"*","dept_id=".$_SESSION['dept']);
            $time=$assigenddept[0]['SLAtime'];
               if($time!="")
               {
                   $a="+".$time." hours";

                   $new_time = date("Y-m-d H:i:s",strtotime($a));
               }
               else
               {
                   $new_time ="";
               }
        if (isset($fname)&&isset($tele)) {

                      $data=array(
                         "internal_lead_id"=>$reqid,
                         "entry_by"=>$_SESSION['staffname'],
                         "entry_date"=>date("Y-m-d H:i:s"),
                         "guest_fname"=>ucfirst($fname),
                         "guest_mname"=>$mname,
                         "guest_lname"=>ucfirst($lname),
                         "guest_mobile"=>$tele,
                         "guest_email"=>$email,
                         "date_of_travel"=>$dateoftravel,
                         "reffered_by"=>$refferedby,
                         "no_of_adults"=>$adult,
                         "no_of_kids"=>$kids,
                         "tour_type"=>$tourtype,
                         "destinations"=>$destination,
                         "comments"=>$cmnt,
                         "dept"=>$_SESSION['dept'],
                         "assign_to"=>$assign,
                         "isOpen"=>'1',
                         "overdue_date"=>$new_time
                      );

                          $last=insertQuery(CONTACT,$data);
                          if($last)
                          {
                             if($supportmail[0]['new_tkt_alert']==1)
                             {
                                $emailsub =$email;

                                if($supportmail[0]['new_tkt_alert_admin']==1)
                                {
                                    $headers1  = 'MIME-Version: 1.0' . "\r\n";
                                    $headers1 .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                                    $headers1 .= "From:".SITENAME."<".EMAIL_SENDER.">";
                                    $subject1="New Lead- ".$reqid;

                                       $body1='<hr style="margin-bottom:50px"> <div style="width: 70%;margin:0 auto">

                                    <div style="margin: 0 auto;text-align: center"><img src="'.SITEURL.'/img/projectimage/logo.png" alt="" /></div>

                                    <p>Dear Admin,<br><br>Following new lead is created  by '.$_SESSION['staffname'].' and assigned to- '.$assigendstaff[0]['emp_name'].'- <br>
                                          <hr  style="border-style:groove;margin-bottom: 8px;color:#CECECE">

                                       <table>
                                        <tr>
                                          <td>Lead No</td>
                                          <td>:</td>
                                          <td>'.$reqid.'</td>
                                        </tr>

                                        <tr>
                                          <td>Date & Time</td>
                                          <td>:</td>
                                          <td>'.date("d/m/Y H:i:s").'</td>
                                        </tr>
                                        <tr>
                                          <td> Guest Name </td>
                                          <td>:</td>
                                          <td>'.$fname." ".$lname.'</td>
                                        </tr>

                                        <tr>
                                          <td>Mobile Number</td>
                                          <td>:</td>
                                          <td>'. $tele.'</td>
                                        </tr>

         							 </table>

                                      <br><hr style="border-style:groove;color:#CECECE"><br>
                                      Please give quick follow up to this user<br>

                                   </p>
                                    </div>';

                                     $sentmail = mail(EMAIL_SENDER, $subject1, $body1, $headers1);

                                  }

                                  /* ************************************send emails to dept manager and staff******************************************************************* */


                                       //to assigned
                                       if($supportmail[0]['new_tkt_alert_members']==1)
                                       {
                                         $emailassignee =$assigendstaff[0]['emp_email'];
                                         $headersmgr  = 'MIME-Version: 1.0' . "\r\n";
                                         $headersmgr .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                                          $headersmgr .= "From:".SITENAME."<".EMAIL_SENDER.">";
                                         $subjectmgr="Lead Assigned - ".$reqid;

                                       $bodymgr='<hr style="margin-bottom:50px"> <div style="width: 70%;margin:0 auto">

                                    <div style="margin: 0 auto;text-align: center"><img src="'.SITEURL.'/img/projectimage/logo.png" alt="" /></div>

                                    <p>Dear '.$assigendstaff[0]['emp_name'].',<br><br>Following new lead  is assinged to you  - <br>
                                          <hr  style="border-style:groove;margin-bottom: 8px;color:#CECECE">

                                       <table>
                                        <tr>
                                          <td>Lead No</td>
                                          <td>:</td>
                                          <td>'.$reqid.'</td>
                                        </tr>

                                        <tr>
                                          <td>Date & Time</td>
                                          <td>:</td>
                                          <td>'.date("d/m/Y H:i:s").'</td>
                                        </tr>
                                        <tr>
                                          <td>Guest Name </td>
                                          <td>:</td>
                                          <td>'.$fname." ".$lname.'</td>
                                        </tr>

                                          <td>Mobile Number</td>
                                          <td>:</td>
                                          <td>'. $tele.'</td>
                                        </tr>
                                         <tr>
                                          <td>Message</td>
                                          <td>:</td>
                                          <td>'.$cmnt.'</td>
                                        </tr>

         							 </table>

                                      <br><hr style="border-style:groove;color:#CECECE"><br>
                                      Please See complete details about lead and give quick follow up to this user<br>

                                   </p>
                                    </div>';

                                     $sentmailmgr = mail($emailassignee, $subjectmgr, $bodymgr, $headersmgr);
                                    }




                               if($supportmail[0]['new_tkt_alert_client']==1)
                               {
                                 /* *********************************************************************************************************************************************** */

                                 $headersclient  = 'MIME-Version: 1.0' . "\r\n";
                                 $headersclient .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                                  $headersclient .= "From:".SITENAME."<".EMAIL_SENDER.">";
                                   $subjectclient="Reqest generated - ".$reqid;
                                    $bodyclient='<hr style="margin-bottom:50px"> <div style="width: 70%;margin:0 auto">
                                    <div style="margin: 0 auto;text-align: center"><img src="'.SITEURL.'/img/projectimage/logo.png" alt="" /></div>
                                    <hr style="border-style:groove;color:#CECECE"">
                                    <p>Dear '.$name.',<br><br>Your request bearing  ID '.$reqid.' has been assigned to our Customer Happiness Manager for
                                    further action and we will find a happyness solution to your query in a very short span of time.<br><br>
                                    Thank you for your co-operation.<br> Please do let us know if you have any queries in this regard.
                                   </p><br>
                                   <hr style="border-style:groove;color:#CECECE"">
                                    </div>';

                                      $sentmail1client = mail($emailsub, $subjectclient, $bodyclient, $headersclient);
                                  }
                                 }
                                      echo 1;
                               }
                             else
                             {
                               echo 0;
                             }

             }

        else {

          echo "0";

        }
}



if($action=="update_lead")
{
      $reqid = $_REQUEST['leadno'];
        $fname = $_REQUEST['firstame'];
        $mname = $_REQUEST['middleame'];
        $lname = $_REQUEST['lastame'];
        $tele = $_REQUEST['mobile'];
        $email = $_REQUEST['email'];
        $dateoftravelex = $_REQUEST['dateoftravel'];
        if($dateoftravelex!="")
        {
          $dateoftravel= date('Y-m-d', strtotime($dateoftravelex));
        }
        else
        {
          $dateoftravel="";
        }


         $overdueex = $_REQUEST['overdue'];
        if($overdueex!="")
        {
             $overdue= date('Y-m-d H:i:s', strtotime($overdueex));
        }
        else
        {
              $overdue= "";
        }


        $refferedby = $_REQUEST['refferedby'];
        $adult = $_REQUEST['adult'];
        $kids = $_REQUEST['kids'];
        $tourtype = $_REQUEST['tourtype'];

        $destiarray=array();
        $country1 = $_REQUEST['country1'];
        $city1 = $_REQUEST['city1'];
        $stay1 = $_REQUEST['stay1'];
        if($country1!=""||$city1!=""||$stay1!="")
        {
          $dest1=$country1."-".$city1."-".$stay1;
          array_push($destiarray,$dest1);
        }

        $country2 = $_REQUEST['country2'];
        $city2 = $_REQUEST['city2'];
        $stay2 = $_REQUEST['stay2'];
         if($country2!=""||$city2!=""||$stay2!="")
        {
          $dest2=$country2."-".$city2."-".$stay2;
          array_push($destiarray,$dest2);
        }

        $country3 = $_REQUEST['country3'];
        $city3 = $_REQUEST['city3'];
        $stay3 = $_REQUEST['stay3'];
        if($country3=""||$city3!=""||$stay3!="")
        {
          $dest3=$country3."-".$city3."-".$stay3;
          array_push($destiarray,$dest3);
        }

        $country4 = $_REQUEST['country4'];
        $city4 = $_REQUEST['city4'];
        $stay4 = $_REQUEST['stay4'];
        if($country4!=""||$city4!=""||$stay4!="")
        {
          $dest4=$country4."-".$city4."-".$stay4;
          array_push($destiarray,$dest4);
        }

        $country5 = $_REQUEST['country5'];
        $city5 = $_REQUEST['city5'];
        $stay5 = $_REQUEST['stay5'];
        if($country5!=""||$city5!=""||$stay5!="")
        {
          $dest5=$country5."-".$city5."-".$stay5;
          array_push($destiarray,$dest5);
        }
         if(sizeof($destiarray))
         {
             $destination=implode(",",$destiarray);
         }
         else
         {
            $destination="";
         }
        $cmnt = $_REQUEST['comments'];
        $assign = $_REQUEST['assign'];
        $Reason = $_REQUEST['Reason'];
        $loginstaf = $_REQUEST['loginstaf'];

         $getstaff=selectQuery(SUPPORTSTAFF,"*","department=".$_SESSION['dept']." end emp_name='".$_SESSION['staffname']."' and isDel='0'");
          $assigendstaff=selectQuery(SUPPORTSTAFF,"*","emp_id=".$assign);
                  if (isset($fname)&&isset($tele)) {

                      $data=array(
                         "guest_fname"=>$fname,
                         "guest_mname"=>$mname,
                         "guest_lname"=>$lname,
                         "guest_mobile"=>$tele,
                         "guest_email"=>$email,
                         "date_of_travel"=>$dateoftravel,
                         "reffered_by"=>$refferedby,
                         "no_of_adults"=>$adult,
                         "no_of_kids"=>$kids,
                         "tour_type"=>$tourtype,
                         "destinations"=>$destination,
                         "comments"=>$cmnt,
                         "overdue_date"=>$overdue,
                          "isOverdue"=>"0",  
                         "reason" => $Reason,
                         "editedby" => $loginstaf
                      );


                           $last = updateQuery(CONTACT, $data, "internal_lead_id=" . $reqid);
                          if($last)
                          {
                              echo 1;
                          }
                          else
                          {
                              echo 0;
                          }

                          }
                       else
                       {
                           echo 0;
                       }
}

if($action=="del_lead")
{
    $lead=$_REQUEST['leadid'];
     $data=array(
         "isDel"=>"1",
         );
      $del = updateQuery(CONTACT, $data, "contact_request_id='".$lead."'");
      if($del)
      {
          echo "1";
      }
      else
      {
          echo "0";
      }
}
?>