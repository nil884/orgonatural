<?php include("../../includes/configuration.php");
    $action = $_REQUEST['action'];
    $onbehalfclient = $_REQUEST['onbehalfclient'];
    $caseid = $_REQUEST['caseid'];
    $assign = $_REQUEST['assign'];
    $name = $_REQUEST['name'];
    $tele = $_REQUEST['tele'];
    $cmnt = $_REQUEST['cmnt'];
    $email = $_REQUEST['email'];
    $source = $_REQUEST['tktSource'];
    $getadmininfo = selectQuery(SUPPORTSTAFF,"*","emp_id='".$_SESSION['staff']."' ");
    $getcreatoremail = $getadmininfo[0]['emp_email'];   //Creator email
    $getdept = selectQuery(SUPPORTSTAFF,"*","emp_id='".$assign."'");
    $dept = $getdept[0]['department'];
    $entry_by = $_SESSION['staff'];   // If Unchecked(Untick) on behalf of Client, Can Store Client, Staff or Admin ID
    if($onbehalfclient=="1"){
        $type="Internal";
    } else{
        $type="External";
    }
    $admin = selectQuery(SUPPORTSTAFF,"*","acc_type='Admin' and isActive='1' and isDel='0'");
    $supportmail = selectQuery(SUPPORTEMAIL,"*");
    $assigendstaff=selectQuery(SUPPORTSTAFF." as e LEFT JOIN ".SUPPORTDEPT." as d on e.department=d.dept_id LEFT JOIN ".SUPPORTSTAFFGROUP." as g on e.emp_group=g.group_id","*","e.emp_id=".$assign);
    $getdeptmgr=selectQuery(SUPPORTDEPT." as d LEFT JOIN ".SUPPORTSTAFF." as e on d.dept_mgr=e.emp_id","*","d.dept_id=".$assigendstaff[0]['department']." ");
    $time=$assigendstaff[0]['SLAtime'];
    if($time!="") {
        $a="+".$time." hours";
        $new_time = date("Y-m-d H:i:s",strtotime($a));
    } else{
        $new_time ="";
    }
    if($action=="newcase") {
        $data=array(
            "tkt_source"=>$source,
            "contact_request_id"=>$caseid,
            "request_type"=>$type,
            "entry_by"=>$entry_by,
            "Name"=>$name,
            "Email"=>$email,
            "Telephone"=>$tele,
            "Comment"=>$cmnt,
            "dept"=>$dept,
            "date"=>date("Y-m-d H:i:s"),
            "assign_to"=>$assign,
            "overdue_date"=>$new_time,
            "isOpen"=>'1'
        );
        $insertcase = insertQuery(CONTACT,$data);
        if($insertcase) {
            if($supportmail[0]['new_tkt_alert']==1) {
                if($_SESSION['acc_type']=="Admin" || $_SESSION['acc_type']=="Staff") {
                    if($supportmail[0]['new_tkt_alert_admin']=="1"&&count($admin)){
                            $adminemailarr=array();
                            for($a=0;$a<count($admin);$a++)
                            {
                                if($admin[$a]['emp_email']!="")
                                {
                                    array_push($adminemailarr,$admin[$a]['emp_email']);
                                }
                            }
                            $adminemails=implode(",",$adminemailarr);
                            $headers1  = 'MIME-Version: 1.0' . "\r\n";
                            $headers1 .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                            $headers1 .= "From:".SITENAME."<".EMAIL_SENDER.">";
                            $subject1="New Case- ".$caseid;

                            $body1='<div style="font-family:calibri">

                                <div style="margin: 0 auto;text-align: center"><img src="'.SITEURL.'/img/projectimage/logo.png" alt="" /></div>

                                <p>Dear Admin,<br><br>Following New Case is created  by '.$_SESSION['staffname'].' and assigned to - '.$assigendstaff[0]['emp_name'].'- </p>
                                <hr>
                                <table width="100%">
                                    <tr>
                                      <td style="width: 30%;padding:5px 0;"><b>Case No</b></td>
                                      <td style="color:grey;">'.$caseid.'</td>
                                    </tr>
                                    <tr>
                                      <td style="padding:5px 0;"><b>Date & Time</b></td>
                                      <td style="color:grey;">'.date("d/m/Y H:i:s").'</td>
                                    </tr>
                                     <tr>
                                      <td> <b>Name</b> </td>
                                      <td style="color:grey;">'.$name.'</td>
                                    </tr>
                                    <tr>
                                      <td><b>Email </b></td>
                                      <td style="color:grey;">'. $email.'</td>
                                    </tr>
                                    <tr>
                                      <td><b>Mobile Number</b></td>
                                      <td style="color:grey;">'. $tele.'</td>
                                    </tr>
                                     <tr>
                                      <td><b>Message</b></td>
                                      <td style="color:grey;">'.$cmnt.'</td>
                                    </tr>
                                </table>
                                <hr>
                                Please give quick follow up to this user<br>

                                </div>';

                            //$sentmail = mail($adminemails, $subject1, $body1, $headers1);
                            $sentmail = mail_multiattachment($fileattach1,$fileattach2, $fileattach3,$fileattach4,$fileattach5,$fileattach6, $adminemails, EMAIL_SENDER, SITENAME, EMAIL_SENDER, $subject1, $body1);


                        }


                    // ************************************send emails to to department manager*******************************************************************
                        if($supportmail[0]['new_tkt_alert_mgr']=='1'&&$getdeptmgr[0]['emp_email']!="")
                        {
                            $headersmgr  = 'MIME-Version: 1.0' . "\r\n";
                            $headersmgr .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                            $headersmgr .= "From:".SITENAME."<".EMAIL_SENDER.">";
                            $subjectmgr="New Case - ".$caseid;

                            $bodymgr='<hr> <div style="width: 135%;margin:0 auto">

                            <div style="margin: 0 auto;text-align: center"><img src="'.SITEURL.'/img/projectimage/logo.png" alt="" /></div>

                            <p>Dear Department Manager,<br><br>Following New Case is created  by '.$_SESSION['staffname'].' and assigned to- '.$assigendstaff[0]['emp_name'].'- <br>
                              <hr  style="border-style:groove;margin-bottom: 8px;color:#CECECE">
                            <table>
                                < <tr>
                                      <td style="width: 30%;padding:5px 0;"><b>Case No</b></td>
                                      <td style="color:grey;">'.$caseid.'</td>
                                    </tr>
                                    <tr>
                                      <td style="padding:5px 0;"><b>Date & Time</b></td>
                                      <td style="color:grey;">'.date("d/m/Y H:i:s").'</td>
                                    </tr>
                                     <tr>
                                      <td> <b>Name</b> </td>
                                      <td style="color:grey;">'.$name.'</td>
                                    </tr>
                                    <tr>
                                      <td><b>Email </b></td>
                                      <td style="color:grey;">'. $email.'</td>
                                    </tr>
                                    <tr>
                                      <td><b>Mobile Number</b></td>
                                      <td style="color:grey;">'. $tele.'</td>
                                    </tr>
                                     <tr>
                                      <td><b>Message</b></td>
                                      <td style="color:grey;">'.$cmnt.'</td>
                                    </tr>
                            </table>
                            <br><hr style="border-style:groove;color:#CECECE"><br>
                            Please give quick follow up to this user<br>
                            </p>
                            </div>';

                          // $sentmailmgr = mail($getdeptmgr[0]['emp_email'], $subjectmgr, $bodymgr, $headersmgr);
                           $sentmailmgr = mail_multiattachment($fileattach1,$fileattach2, $fileattach3,$fileattach4,$fileattach5,$fileattach6, $getdeptmgr[0]['emp_email'], EMAIL_SENDER, SITENAME, EMAIL_SENDER, $subjectmgr, $bodymgr);
                        }

                    //  ************************************send emails to to assigned staff*******************************************************************
                        if($supportmail[0]['new_tkt_alert_members']=='1')
                        {
                            $emailassignee =$assigendstaff[0]['emp_email'];
                            $headersstaff  = 'MIME-Version: 1.0' . "\r\n";
                            $headersstaff .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                            $headersstaff .= "From:".SITENAME."<".EMAIL_SENDER.">";
                            $subjectstaff="Case Created - ".$caseid;

                            $bodystaff='<hr> <div style="width: 135%;margin:0 auto">

                            <div style="margin: 0 auto;text-align: center"><img src="'.SITEURL.'/img/projectimage/logo.png" alt="" /></div>

                            <p>Dear '.$assigendstaff[0]['emp_name'].',<br><br>Following New Case is assinged to you  - <br>
                            <hr  style="border-style:groove;margin-bottom: 8px;color:#CECECE">
                            <table>
                               <tr>
                                      <td style="width: 30%;padding:5px 0;"><b>Case No</b></td>
                                      <td style="color:grey;">'.$caseid.'</td>
                                    </tr>
                                    <tr>
                                      <td style="padding:5px 0;"><b>Date & Time</b></td>
                                      <td style="color:grey;">'.date("d/m/Y H:i:s").'</td>
                                    </tr>
                                     <tr>
                                      <td> <b>Name</b> </td>
                                      <td style="color:grey;">'.$name.'</td>
                                    </tr>
                                    <tr>
                                      <td><b>Email </b></td>
                                      <td style="color:grey;">'. $email.'</td>
                                    </tr>
                                    <tr>
                                      <td><b>Mobile Number</b></td>
                                      <td style="color:grey;">'. $tele.'</td>
                                    </tr>
                                     <tr>
                                      <td><b>Message</b></td>
                                      <td style="color:grey;">'.$cmnt.'</td>
                                    </tr>
                            </table>
                            <br><hr style="border-style:groove;color:#CECECE"><br>
                            Please See complete details about Case and give quick follow up to this user<br>
                            </p>
                            </div>';

                           //$sentmailstaff = mail($emailassignee, $subjectstaff, $bodystaff, $headersstaff);
                            $sentmailstaff = mail_multiattachment($fileattach1,$fileattach2, $fileattach3,$fileattach4,$fileattach5,$fileattach6, $emailassignee, EMAIL_SENDER, SITENAME, EMAIL_SENDER, $subjectstaff, $bodystaff);
                        }


                    //  **********************Mail to creator ***************************************************************************************************************************
                        if($getcreatoremail!="")
                        {
                            $headerscreate  = 'MIME-Version: 1.0' . "\r\n";
                            $headerscreate .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                            $headerscreate .= "From:".SITENAME."<".EMAIL_SENDER.">";
                            $subjectcreate="Case Generated - ".$caseid;
                            $bodycreate='<hr> <div style="margin:0 auto">
                                <div style="margin: 0 auto;text-align: center"><img src="'.SITEURL.'/img/projectimage/logo.png" alt="" /></div>
                                <hr style="border-style:groove;color:#CECECE"">
                                <p>Dear '.$_SESSION['staffname'].',<br><br>Case created successfully and assigned to '.$assigendstaff[0]['emp_name'].'
                                </p><br>

                                 <table>
                                 <tr>
                                      <td style="width: 30%;padding:5px 0;"><b>Case No</b></td>
                                      <td style="color:grey;">'.$caseid.'</td>
                                    </tr>
                                    <tr>
                                      <td style="padding:5px 0;"><b>Date & Time</b></td>
                                      <td style="color:grey;">'.date("d/m/Y H:i:s").'</td>
                                    </tr>
                                     <tr>
                                      <td> <b>Name</b> </td>
                                      <td style="color:grey;">'.$name.'</td>
                                    </tr>
                                    <tr>
                                      <td><b>Email </b></td>
                                      <td style="color:grey;">'. $email.'</td>
                                    </tr>
                                    <tr>
                                      <td><b>Mobile Number</b></td>
                                      <td style="color:grey;">'. $tele.'</td>
                                    </tr>
                                     <tr>
                                      <td><b>Message</b></td>
                                      <td style="color:grey;">'.$cmnt.'</td>
                                    </tr>
                            </table>


                                <hr style="border-style:groove;color:#CECECE"">
                                </div>';

                            $sentmail1create = mail($getcreatoremail, $subjectcreate, $bodycreate, $headerscreate);
                        }



                    if($onbehalfclient=="onbehalfclient")
                    {
                    //  On Behalf of Client by Admin or Staff********************Mail to client ***************************************************************************************************************************
                        if($supportmail[0]['new_tkt_alert_client']=="1")
                        {
                            $headersclient  = 'MIME-Version: 1.0' . "\r\n";
                            $headersclient .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                            $headersclient .= "From:".SITENAME."<".EMAIL_SENDER.">";
                            $subjectclient="Ticket Created on Behalf of You- ".$caseid;
                            $bodyclient='<hr> <div style="margin:0 auto">
                            <div style="margin: 0 auto;text-align: center"><img src="'.SITEURL.'/img/projectimage/logo.png" alt="" /></div>
                            <hr style="border-style:groove;color:#CECECE"">
                            <p>Dear '.$clientname.',<br><br>Your request bearing  ID '.$caseid.' has been assigned to our Customer Happiness Manager for
                            further action and we will find a happiness solution to your query in a very short span of time.<br><br>
                            Thank you for your co-operation.<br> Please do let us know if you have any queries in this regard.
                            </p><br>

                              <table>
                                <tr>
                                  <td><b>Case No</b></td>
                                  <td>'.$caseid.'</td>
                                </tr>
                                <tr>
                                  <td><b>Date & Time</b></td>
                                  <td>'.date("d/m/Y H:i:s").'</td>
                                </tr>
                                  <tr>
                                  <td> <b>Name</b> </td>
                                  <td style="color:grey;">'.$name.'</td>
                                </tr>
                                <tr>
                                  <td><b>Email </b></td>
                                  <td style="color:grey;">'. $email.'</td>
                                </tr>
                                <tr>
                                  <td><b>Mobile Number</b></td>
                                  <td style="color:grey;">'. $tele.'</td>
                                </tr>
                                 <tr>
                                  <td><b>Message</b></td>
                                  <td style="color:grey;">'.$cmnt.'</td>
                                </tr>
                            </table>


                            <hr style="border-style:groove;color:#CECECE"">
                            </div>';

                            $sentmail1client = mail($clientemail, $subjectclient, $bodyclient, $headersclient);
                        }
                    }  // Close onBehalfClient


                }  // Close Admin or Staff



                if($_SESSION['acc_type']=="Client")
                {
                    // **************************** Email To Admin *******************************************************
                        if($supportmail[0]['new_tkt_alert_admin']=="1"&&count($admin))
                        {
                            $adminemailarr=array();
                            for($a=0;$a<count($admin);$a++)
                            {
                                if($admin[$a]['emp_email']!="")
                                {
                                    array_push($adminemailarr,$admin[$a]['emp_email']);
                                }
                            }
                            $adminemails=implode(",",$adminemailarr);
                            $headers1  = 'MIME-Version: 1.0' . "\r\n";
                            $headers1 .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                            $headers1 .= "From:".SITENAME."<".EMAIL_SENDER.">";
                            $subject1="New Case- ".$caseid;

                            $body1='<div style="font-family:calibri">

                                <div style="margin: 0 auto;text-align: center"><img src="'.SITEURL.'/img/projectimage/logo.png" alt="" /></div>

                                <p>Dear Admin,<br><br>Following New Case is created  by '.$_SESSION['staffname'].' and assigned to - '.$assigendstaff[0]['emp_name'].'- </p>
                                <hr>
                                <table width="100%">
                                    <tr>
                                      <td style="width: 30%;padding:5px 0;"><b>Case No</b></td>
                                      <td style="color:grey;">'.$caseid.'</td>
                                    </tr>
                                    <tr>
                                      <td style="padding:5px 0;"><b>Date & Time</b></td>
                                      <td style="color:grey;">'.date("d/m/Y H:i:s").'</td>
                                    </tr>
                                      <tr>
                                      <td> <b>Name</b> </td>
                                      <td style="color:grey;">'.$name.'</td>
                                    </tr>
                                    <tr>
                                      <td><b>Email </b></td>
                                      <td style="color:grey;">'. $email.'</td>
                                    </tr>
                                    <tr>
                                      <td><b>Mobile Number</b></td>
                                      <td style="color:grey;">'. $tele.'</td>
                                    </tr>
                                     <tr>
                                      <td><b>Message</b></td>
                                      <td style="color:grey;">'.$cmnt.'</td>
                                    </tr>
                                </table>
                                <hr>
                                Please give quick follow up to this user<br>

                                </div>';

                           // $sentmail = mail($adminemails, $subject1, $body1, $headers1);
                            $sentmail = mail_multiattachment($fileattach1,$fileattach2, $fileattach3,$fileattach4,$fileattach5,$fileattach6, $adminemails, EMAIL_SENDER, SITENAME, EMAIL_SENDER, $subject1, $body1);


                        }



                    //  ********************Mail to client ***************************************************************************************************************************
                        if($supportmail[0]['new_tkt_alert_client']=="1")
                        {
                            $headersclient  = 'MIME-Version: 1.0' . "\r\n";
                            $headersclient .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                            $headersclient .= "From:".SITENAME."<".EMAIL_SENDER.">";
                            $subjectclient="Request Generated - ".$caseid;
                            $bodyclient='<hr> <div style="margin:0 auto">
                            <div style="margin: 0 auto;text-align: center"><img src="'.SITEURL.'/img/projectimage/logo.png" alt="" /></div>
                            <hr style="border-style:groove;color:#CECECE"">
                            <p>Dear '.$clientname.',<br><br>Your request bearing  ID '.$caseid.' has been assigned to our Customer Happiness Manager for
                            further action and we will find a happiness solution to your query in a very short span of time.<br><br>
                            Thank you for your co-operation.<br> Please do let us know if you have any queries in this regard.
                            </p><br>
                            <hr style="border-style:groove;color:#CECECE"">
                            </div>';

                            $sentmail1client = mail($clientemail, $subjectclient, $bodyclient, $headersclient);
                        }
                } // Close Client

           

            }   //New Ticket Alert
            echo "1";
        }
        else
        {
            echo "0";
        }

    }  //Action



    if($action=="update_lead")
    {
        $reqid = $_REQUEST['leadno'];

        $overduedate = $_REQUEST['overduedate'];
        $Reason = $_REQUEST['Reason'];

        $getcreator = selectQuery(SUPPORTSTAFF,"*","emp_id='".$_SESSION['staff']."' ");
        $getcasedetail = selectQuery(CONTACT,"*","contact_request_id='".$reqid."' ");


        $empname = $getcreator[0]['emp_name'];
        $empemail = $getcreator[0]['emp_email'];

         $data=array(
        "Name"=>$name,
         "Email"=>$email,
         "Telephone"=>$tele,
         "Comment"=>$cmnt,
         "overdue_date"=>$overduedate,
         "update_reason"=>$Reason

        );

        $updatecase = updateQuery(CONTACT, $data, "contact_request_id='".$reqid."' ");


        if($updatecase)
        {
            $data=array(
                "ticket_id"=>$reqid,
                "comment_by"=>$_SESSION['staff'],
                "comment"=>$Reason,
                "comment_type"=>"Internal",
                "extra"=>"Ticket Updated",
                "comment_date"=>date("d/m/Y H:i:s")
              );
              $addresponse=insertQuery(SUPPORTCOMMENT,$data);

            if($supportmail[0]['new_tkt_alert']==1)
            {
                if($_SESSION['acc_type']=="Admin" || $_SESSION['acc_type']=="Staff")
                {
                    // **************************** Email To Admin *******************************************************
                    if($supportmail[0]['new_tkt_alert_admin']=="1"&&count($admin))
                    {

                        $adminemailarr=array();
                        for($a=0;$a<count($admin);$a++)
                        {
                            if($admin[$a]['emp_email']!="")
                            {
                                array_push($adminemailarr,$admin[$a]['emp_email']);
                            }
                        }
                        $adminemails=implode(",",$adminemailarr);

                        $headers1  = 'MIME-Version: 1.0' . "\r\n";
                        $headers1 .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                        $headers1 .= "From:".SITENAME."<".EMAIL_SENDER.">";
                        $subject1="Case Updated - ".$reqid;

                        $body1='<hr> <div style="width: 135%;margin:0 auto">

                        <div style="margin: 0 auto;text-align: center"><img src="'.SITEURL.'/img/projectimage/logo.png" alt="" /></div>

                        <p>Dear Admin,<br><br>Following Case is updated  by '.$_SESSION['staffname'].' <br>
                        <hr style="border-style:groove;margin-bottom: 8px;color:#CECECE">
                        <table>
                             <tr>
                              <td> <b>Name</b> </td>
                              <td style="color:grey;">'.$name.'</td>
                            </tr>
                            <tr>
                              <td><b>Email </b></td>
                              <td style="color:grey;">'. $email.'</td>
                            </tr>
                            <tr>
                              <td><b>Mobile Number</b></td>
                              <td style="color:grey;">'. $tele.'</td>
                            </tr>
                             <tr>
                              <td><b>Message</b></td>
                              <td style="color:grey;">'.$cmnt.'</td>
                            </tr>
                              <tr>
                              <td><b>Update Reason</b> </td>
                              <td>'.$Reason.'</td>
                            </tr>

            </table>
                        <br><hr style="border-style:groove;color:#CECECE"><br>
                        Please give quick follow up to this user<br>
                        </p>
                        </div>';

                        $sentmail = mail($adminemails, $subject1, $body1, $headers1);
                    }  // Check new_tkt_alert_admin and isadmin>1



                     //  **********************Mail to creator ***************************************************************************************************************************
                        if($empemail!="")
                        {
                            $headerscreate  = 'MIME-Version: 1.0' . "\r\n";
                            $headerscreate .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                            $headerscreate .= "From:".SITENAME."<".EMAIL_SENDER.">";
                            $subjectcreate="Ticket Updated - ".$reqid;
                            $bodycreate='<hr> <div margin:0 auto">
                                <div style="margin: 0 auto;text-align: center"><img src="'.SITEURL.'/img/projectimage/logo.png" alt="logo" /></div>
                                <hr style="border-style:groove;color:#CECECE"">
                                <p>Dear '.$empname.',<br><br>Ticket Updated successfully. With following reason,
                                </p>
                                <hr style="border-style:groove;margin-bottom: 8px;color:#CECECE">
                                    <p>'.$Reason.'</p>
                                <hr style="border-style:groove;color:#CECECE"">
                                </div>';

                            $sentmail1create = mail($empemail, $subjectcreate, $bodycreate, $headerscreate);
                        }

                        if($getdeptmgr[0]['emp_email']!="")
                        {
                                $headersmgr  = 'MIME-Version: 1.0' . "\r\n";
                                $headersmgr .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                                $headersmgr .= "From:".SITENAME."<".EMAIL_SENDER.">";
                                $subjectmgr="Ticket Updated - ".$reqid;

                                $bodymgr='<hr> <div style="width: 100%;margin:0 auto">

                                <div style="margin: 0 auto;text-align: center"><img src="'.SITEURL.'/img/projectimage/logo.png" alt="" /></div>

                                <p>Dear Department Manager,<br><br>Ticket is updated  by '.$_SESSION['staffname'].' with following comment - <br>
                                <hr  style="border-style:groove;margin-bottom: 8px;color:#CECECE">
                                    <p>'.$Reason.'</p>
                              <br><hr style="border-style:groove;color:#CECECE"><br>
                           </p>
                            </div>';

                           $sentmailmgr = mail($getdeptmgr[0]['emp_email'], $subjectmgr, $bodymgr, $headersmgr);

                        }
                }   // Check Admin Or Staff

            }  // Check new_tkt_alert
            echo "1";  
        } 

    }

  ?>