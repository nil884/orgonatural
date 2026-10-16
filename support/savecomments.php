<?php include('../includes/configuration.php');
    $supportmail=selectQuery(SUPPORTEMAIL,"*");
    $allowedimgtypes=$supportmail[0]['img_types'];
    $allowedapptypes=$supportmail[0]['application_types'];
    if($allowedimgtypes!=""&&$allowedapptypes!=""){
        $allowed= $allowedimgtypes.",".$allowedapptypes;
    } else if($allowedimgtypes!=""&&$allowedapptypes==""){
        $allowed= $allowedimgtypes;
    } else if($allowedimgtypes==""&&$allowedapptypes!=""){
        $allowed= $allowedapptypes;
    } else if($allowedimgtypes==""&&$allowedapptypes==""){
        $allowed= "";
    }
    $target = "../img/support_tkt_comments/";
    $filename1 = "";$filename2=""; $filename3="";$filename4="";$filename5="";$filename6="";
    $allowedExts = explode(",",$allowed);
    $staff = $_REQUEST['staff'];
    $requestid = $_REQUEST['requestid'];
    $comment = $_REQUEST['comment'];
    $closed = $_REQUEST['closed'];
    $reopen = $_REQUEST['reopen'];
    $dept = $_REQUEST['dept'];
    $posttype = $_REQUEST['posttype'];    /* Internal/Post Reply/Transfer Ticket/Reassign Ticket */
    $assignto = $_REQUEST['assignto'];
    $chngdept = $_REQUEST['chngdept'];
    $getdetails = selectQuery(CONTACT,"*","contact_request_id=".$requestid);
    $projectname = $getdetails[0]['project_name'];
    $defecttype = $getdetails[0]['defect_type'];
    $issuepanel = $getdetails[0]['issue_panel'];
    $issuedetail = $getdetails[0]['issue_detail'];
    $replicateissue = $getdetails[0]['issue_steps'];
    $getdeptmgr = selectQuery(SUPPORTSTAFF,"*","department=".$_SESSION['dept']." and isActive='1' and post='Manager'");
    $getdeptallstaff = selectQuery(SUPPORTSTAFF,"*","department=".$_SESSION['dept']." and isActive='1' and post='Staff'");
    $assignedstaff = selectQuery(SUPPORTSTAFF,"*","emp_id=".$getdetails[0]['assign_to']);    //get assigned staff details
    $currentstaff = selectQuery(SUPPORTSTAFF,"*","emp_id=".$_SESSION['staff']);            // get current login staff
    $assigneddept = selectQuery(SUPPORTDEPT,"*","dept_id=".$getdetails[0]['dept']);    //get assigned dept details
    $data=array(
    "ticket_id"=>$requestid,
    "comment_by"=>$staff,
    "comment"=>$comment,
    "comment_date"=>date("d/m/Y H:i:s")
    );
    if($closed==1){
        $data1['isClosed']="1";
        $data1['closedDate']=date("Y-m-d H:i:s");
        $data1['isAnswered']="0";
        $data1['isOverdue']="0";
        $data1['isOpen']="0";
        $extra="Ticket Closed";
        $data['extra']=$extra;
    } else if($reopen==1){
        $data1['isClosed']="0";
        $data1['reopenDate']=date("Y-m-d H:i:s");
        $data1['isAnswered']="0";
        $data1['isOverdue']="0";
        $data1['isOpen']="1";
        $extra="Ticket Re-open";
        $data['extra']=$extra;
    } else if($posttype=="Transfer Ticket") {
        if($getdetails[0]['isOpen']=='1'){
            $data1['isAnswered']="1";
            $data1['isOpen']="0";
        }
        $data1['dept']=$chngdept;
        $currentdept=selectQuery(SUPPORTDEPT,"*","dept_id=".$chngdept);
        $extra="Ticket Transfered From Department ".$assigneddept[0]['dept_name']." To ".$currentdept[0]['dept_name'];
        $data['extra']=$extra;
    } else if($posttype=="Reassign Ticket") {
        if($getdetails[0]['isOpen']=='1'){
            $data1['isAnswered']="1";
            $data1['isOpen']="0";
        }
        $data1['assign_to']=$assignto;
        $getdept = selectQuery(SUPPORTSTAFF,"*","emp_id='".$assignto."' ");
        $reassigndept = $getdept[0]['department'];
        /* $data1['dept']=$reassigndept; */
        $assignedto=selectQuery(SUPPORTSTAFF,"*","emp_id=".$assignto);
        $extra="Ticket Reassigned From ".$assignedstaff[0]['emp_name']." To ".$assignedto[0]['emp_name'];
        $data['extra']=$extra;
    } else if($posttype=="Internal") {
        if($getdetails[0]['isClosed']!="1"){
            $data1['isAnswered']="1";
            $data1['isOpen']="0";
        }
    } else if($posttype=="Post Reply") {
        if($getdetails[0]['isClosed']!="1"){
            $data1['isAnswered']="1";
            $data1['isOpen']="0";
        }
        $data['comment_type']="External";
    }
    $addresponse=insertQuery(SUPPORTCOMMENT,$data);
    if($addresponse){
        $getresponse=selectQuery(SUPPORTCOMMENT,"*","ticket_id<>'' order by comment_id desc LIMIT 1");
        if($_FILES['attachment']['tmp_name']!=""){
        $allowedimgformat=$allowedimgtypes;
        $allowedappformat=$allowedapptypes;
        $imgarr1 = explode(",",$allowedimgformat);
        $allarr = array();
        for($i = 0; $i < sizeOf($imgarr1); $i++) {
            if($imgarr1[$i]!=""){
                $imgstr="image/".$imgarr1[$i];
                array_push($allarr,$imgstr);
            }
        }
        $apparr1 = explode(",",$allowedappformat);
            for($i = 0; $i < sizeOf($apparr1); $i++) {
                if($apparr1[$i]!=""){
                $appstr="application/".$apparr1[$i];
                array_push($allarr,$appstr);
                if($apparr1[$i]=="zip"){
                    $appstrex="application/x-zip-compressed";
                    array_push($allarr,$appstrex);
                }
            }
        }
        $extension = end(explode(".", $_FILES["attachment"]["name"]));
            if(in_array($extension, $allowedExts)){   
                $photo=$requestid."_".$getresponse[0]['comment_id'].".".$extension;
                    if ($_FILES["attachment"]["error"] > 0){
                }
                else{
                move_uploaded_file($_FILES["attachment"]["tmp_name"],$target."/".$photo);
                $imgdata=array(
                'img_name'=>$photo,
                'response_id'=>$getresponse[0]['comment_id']
                );
                $addimg=insertQuery(SUPPORTIMG,$imgdata);
                $filename1=$target."/".$photo;
                }
            }
       }
      $updatestat=updateQuery(CONTACT,$data1,"contact_request_id=".$requestid);
      if($posttype=="Reassign Ticket"){
        $reassignedstaff = selectQuery(SUPPORTSTAFF,"*","emp_id=".$assignto);
        $reassigneddept = selectQuery(SUPPORTDEPT,"*","dept_id=".$reassignedstaff[0]['department']);
        $emailassign = $reassignedstaff[0]['emp_email'];
        $headersassign  = 'MIME-Version: 1.0' . "\r\n";
        $headersassign .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headersassign .= "From:".SITENAME."<".EMAIL_SENDER.">";
        $subjectassign = "Ticket Assigned To You - ".$requestid;
        $bodyassign = '<div style="width:600px;font-family:calibri;font-size:17px;padding:40px;border:1px solid #ccc;">
        <div style="margin-bottom: 20px;padding-bottom: 15px;">
        <img src="'.SITEURL.'/img/projectimage/logo.png" alt="" />
        </div>
        <p><b>Dear '.$reassignedstaff[0]['emp_name'].',</b></p>
        <p style="color:grey;">
        '.$currentstaff[0]['emp_name'].',  has assigned ticket #'.$requestid.' to you, with the following details     <br>
        </p>
        <p><b>The Referenced Ticket Details Are -</b></p>
        <table border="1" cellpadding="0" cellspacing="0" style="border:1px solid #607D8B;text-align:left;border-collapse: collapse;width:100%;">
        <tr>
                            <td style="padding:10px 12px;"><b>Ticket ID</b></td>
                            <td style="color:grey;padding:10px 12px;"> '.$requestid.' </td>
                        </tr>

                        <tr>
                            <td style="padding:10px 12px;"><b> Date Created</b></td>
                            <td style="color:grey;padding:10px 12px;">'.$getdetails[0]['date'].'</td>
                        </tr>
                         <tr>
                            <td style="padding:10px 12px;"><b> Name</b></td>
                            <td style="color:grey;padding:10px 12px;">'.$getdetails[0]['Name'].'</td>
                        </tr>
                         <tr>
                            <td style="padding:10px 12px;"><b> Email</b></td>
                            <td style="color:grey;padding:10px 12px;">'.$getdetails[0]['Email'].'</td>
                        </tr>
                         <tr>
                            <td style="padding:10px 12px;"><b>Mobile</b></td>
                            <td style="color:grey;padding:10px 12px;">'.$getdetails[0]['Telephone'].'</td>
                        </tr>
                         <tr>
                            <td style="padding:10px 12px;"><b> Comment</b></td>
                            <td style="color:grey;padding:10px 12px;">'.$getdetails[0]['Comment'].'</td>
                        </tr>
                        <tr>
                            <td style="padding:10px 12px;"><b>Date & Time</b></td>
                            <td style="color:grey;padding:10px 12px;">'.date("d/m/Y H:i:s").'</td>
                        </tr>

                        <tr>
                            <td style="padding:10px 12px;"> <b>Assigned Staff</b></td>
                            <td style="color:grey;padding:10px 12px;">'.$reassignedstaff[0]['emp_name'].'</td>
                        </tr>
                        <tr>
                            <td style="padding:10px 12px;"> <b>Department</b></td>
                            <td style="color:grey;padding:10px 12px;">'.$reassigneddept[0]['dept_name'].' </td>
                        </tr>
              </table>

            </div>';

             $sentmailassign = mail($emailassign , $subjectassign, $bodyassign, $headersassign);

      }

      if($posttype=="Transfer Ticket")
      {
             $transferstaff=selectQuery(SUPPORTSTAFF,"*","department=".$chngdept." adn isActive='1'");

            $transferdept=selectQuery(SUPPORTDEPT,"*","dept_id=".$chngdept);
             for($j=0;$j<count($transferstaff);$j++)
             {
            $emailtransfer =$transferstaff[$j]['emp_email'];
            $headertransfer  = 'MIME-Version: 1.0' . "\r\n";
            $headertransfer .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headertransfer .= "From:".SITENAME."<".EMAIL_SENDER.">";
            $subjecttransfer="Ticket Transfered - ".$requestid;
            $bodytransfer='<div style="width:600px;font-family:calibri;font-size:17px;padding:40px;border:1px solid #ccc;">
                    <div style="margin-bottom: 20px;padding-bottom: 15px;">
                    <img src="'.SITEURL.'/img/projectimage/logo.png" alt="" />
                    </div>

                        <p><b>Dear '.$transferstaff[$j]['emp_name'].',</b></p>
                        <p style="color:grey;">
                        '.$currentstaff[0]['emp_name'].',  has transfer ticket #'.$requestid.' to department '.$transferdept[0]['dept_name'].', with the following details     <br>
                        </p>

                        <p><b>The Referenced Ticket Details Are -</b></p>

                      <table border="1" cellpadding="0" cellspacing="0" style="border:1px solid #607D8B;text-align:left;border-collapse: collapse;width:100%;">
                                <tr>
                                    <td style="padding:10px 12px;"><b>Ticket ID</b></td>
                                     <td style="color:grey;padding:10px 12px;"> '.$requestid.' </td>
                                </tr>

                                <tr>
                                    <td style="padding:10px 12px;"> <b>Date Created</b></td>
                                    <td style="color:grey;padding:10px 12px;">'.$getdetails[0]['entry_date'].'</td>
                                </tr>

                      </table>

                    </div>';

             $sentmailtransfer = mail($emailsubtransfer, $subjecttransfer, $bodytransfer, $headertransfer);
         }
      }

     if($supportmail[0]['new_msg_alert']==1)
     {
        $caseid = $requestid;



        if($supportmail[0]['new_msg_alert_client']==1 && $posttype=="Post Reply")
        {
            $emailsub =$getdetails[0]['Email'];
            if($emailsub!="")
            {

            $subject="Response On - ".$requestid;

            $body='<div style="width:600px;font-family:calibri;font-size:17px;padding:40px;border:1px solid #ccc;">
                    <div style="margin-bottom: 20px;padding-bottom: 15px;">
                    <img src="'.SITEURL.'/img/projectimage/logo.png" alt="" />
                    </div>

                <p><b>Dear '.$getdetails[0]['Name'].',</b></p>
                <p style="color:grey;">'.$assignedstaff[0]['emp_name'].' from customer support team responded as below  </p>
                <div >'.$comment.' </div>
                <br>
                <div><b>Thanking  You.</b></div>
                <div style="color:grey;">With Regards,<br>
                 Team '.SITENAME.'
                </div>

            </div>';
             $sentmail = mail_multiattachment($filename1,$filename2, $filename3,$filename4,$filename5,$filename6, $emailsub, EMAIL_SENDER, SITENAME, EMAIL_SENDER, $subject, $body);

            }
         }

        if($supportmail[0]['new_msg_alert_admin']==1)
        {
             $emailadmin =EMAIL_SENDER;

            $subjectadmin="Response On - ".$requestid;

            $bodyadmin='<div style="width:600px;font-family:calibri;font-size:17px;padding:40px;border:1px solid #ccc;">
                    <div style="margin-bottom: 20px;padding-bottom: 15px;">
                    <img src="'.SITEURL.'/img/projectimage/logo.png" alt="" />
                    </div>

                                <p><b>Dear Admin,</b></p>
                                <p style="color:grey;">'.$currentstaff[0]['emp_name'].' from customer support team responded as below  </p>

                        <table border="1" cellpadding="0" cellspacing="0" style="border:1px solid #607D8B;text-align:left;border-collapse: collapse;width:100%;">
                                <tr>
                              <td style="padding:10px 12px;"><b>Case No</b></td>
                              <td style="color:grey;padding:10px 12px;">'.$caseid.'</td>
                            </tr>
                            <tr>
                              <td style="padding:10px 12px;"><b>Date & Time</b></td>
                              <td style="color:grey;padding:10px 12px;">'.date("d/m/Y H:i:s").'</td>
                            </tr>
                            <tr>
                            <td style="padding:10px 12px;"><b> Name</b></td>
                            <td style="color:grey;padding:10px 12px;">'.$getdetails[0]['Name'].'</td>
                        </tr>
                         <tr>
                            <td style="padding:10px 12px;"><b> Email</b></td>
                            <td style="color:grey;padding:10px 12px;">'.$getdetails[0]['Email'].'</td>
                        </tr>
                         <tr>
                            <td style="padding:10px 12px;"><b>Mobile</b></td>
                            <td style="color:grey;padding:10px 12px;">'.$getdetails[0]['Telephone'].'</td>
                        </tr>
                         <tr>
                            <td style="padding:10px 12px;"><b> Comment</b></td>
                            <td style="color:grey;padding:10px 12px;">'.$getdetails[0]['Comment'].'</td>
                        </tr>
                        </table>
                        <br>
                        <div> '.$comment.'</div>
                        <br>
                        <div><b>Thanking  You.</b></div>
                              <div style="color:grey;"> With Regards, <br>
                                  Team '.SITENAME.'
                                </div>

                    </div>';
            $sentmailadmin= mail_multiattachment($filename1,$filename2, $filename3,$filename4,$filename5,$filename6, $emailadmin, EMAIL_SENDER, SITENAME, EMAIL_SENDER, $subjectadmin, $bodyadmin);

        }

         if($supportmail[0]['new_msg_alert_mgr']==1)
        {
                  $emailmgr =$getdeptmgr[0]['emp_email'];

                    $subjectmgr="Response On - ".$requestid;

                    $bodymgr='<div style="width:600px;font-family:calibri;font-size:17px;padding:40px;border:1px solid #ccc;">
                    <div style="margin-bottom: 20px;padding-bottom: 15px;">
                    <img src="'.SITEURL.'/img/projectimage/logo.png" alt="" />
                    </div>
                    <p><b>Dear '.$getdeptmgr[0]['emp_name'].',</b></p>
                    <p style="color:grey;">'.$currentstaff[0]['emp_name'].' from customer support team responded as below </p>
                    <table border="1" cellpadding="0" cellspacing="0" style="border:1px solid #607D8B;text-align:left;border-collapse: collapse;width:100%;">
                                <tr>
                              <td style="padding:10px 12px;"><b>Case No</b></td>
                              <td style="color:grey;padding:10px 12px;">'.$caseid.'</td>
                            </tr>
                            <tr>
                              <td style="padding:10px 12px;"><b>Date & Time</b></td>
                              <td style="color:grey;padding:10px 12px;">'.date("d/m/Y H:i:s").'</td>
                            </tr>
                            <tr>
                            <td style="padding:10px 12px;"><b> Name</b></td>
                            <td style="color:grey;padding:10px 12px;">'.$getdetails[0]['Name'].'</td>
                        </tr>
                         <tr>
                            <td style="padding:10px 12px;"><b> Email</b></td>
                            <td style="color:grey;padding:10px 12px;">'.$getdetails[0]['Email'].'</td>
                        </tr>
                         <tr>
                            <td style="padding:10px 12px;"><b>Mobile</b></td>
                            <td style="color:grey;padding:10px 12px;">'.$getdetails[0]['Telephone'].'</td>
                        </tr>
                         <tr>
                            <td style="padding:10px 12px;"><b> Comment</b></td>
                            <td style="color:grey;padding:10px 12px;">'.$getdetails[0]['Comment'].'</td>
                        </tr>
                        </table>
                        <br>
                        <div>'.$comment.'</div>
                        <br>
                        <div><b>Thanking  You.</b></div>
                               <div style="color:grey;">
                                  With Regards,  <br>
                                  Team '.SITENAME.' </div>

                            </div>';
                     $sentmailmgr= mail_multiattachment($filename1,$filename2, $filename3,$filename4,$filename5,$filename6, $emailmgr, EMAIL_SENDER, SITENAME, EMAIL_SENDER, $subjectmgr, $bodymgr);

        }

        if($supportmail[0]['new_msg_alert_respondent']==1)
        {
                $emailresp =$currentstaff[0]['emp_email'];

                    $subjectresp="Response On - ".$requestid;

                    $bodyresp='<div style="width:600px;font-family:calibri;font-size:17px;padding:40px;border:1px solid #ccc;">
                    <div style="margin-bottom: 20px;padding-bottom: 15px;">
                    <img src="'.SITEURL.'/img/projectimage/logo.png" alt="" />
                    </div>
                    <p><b>Dear '.$currentstaff[0]['emp_name'].',</b></p>
                    <p style="color:grey;">'.$currentstaff[0]['emp_name'].' from customer support team responded as below  </p>

                    <table border="1" cellpadding="0" cellspacing="0" style="border:1px solid #607D8B;text-align:left;border-collapse: collapse;width:100%;">
                                <tr>
                              <td style="padding:10px 12px;"><b>Case No</b></td>
                              <td style="color:grey;padding:10px 12px;">'.$caseid.'</td>
                            </tr>
                            <tr>
                              <td style="padding:10px 12px;"><b>Date & Time</b></td>
                              <td style="color:grey;padding:10px 12px;">'.date("d/m/Y H:i:s").'</td>
                            </tr>
                            <tr>
                            <td style="padding:10px 12px;"><b> Name</b></td>
                            <td style="color:grey;padding:10px 12px;">'.$getdetails[0]['Name'].'</td>
                        </tr>
                         <tr>
                            <td style="padding:10px 12px;"><b> Email</b></td>
                            <td style="color:grey;padding:10px 12px;">'.$getdetails[0]['Email'].'</td>
                        </tr>
                         <tr>
                            <td style="padding:10px 12px;"><b>Mobile</b></td>
                            <td style="color:grey;padding:10px 12px;">'.$getdetails[0]['Telephone'].'</td>
                        </tr>
                         <tr>
                            <td style="padding:10px 12px;"><b> Comment</b></td>
                            <td style="color:grey;padding:10px 12px;">'.$getdetails[0]['Comment'].'</td>
                        </tr>
                        </table>
                        <br>
                        <div>'.$comment.'</div>
                        <br>
                        <div><b> Thanking  You.</b></div>
                        <div style="color:grey;">
                        With Regards, <br>
                        Team '.SITENAME.'</div>
                        </div>';
                    $sentmailresp= mail_multiattachment($filename1,$filename2, $filename3,$filename4,$filename5,$filename6, $emailresp, EMAIL_SENDER, SITENAME, EMAIL_SENDER, $subjectresp, $bodyresp);

        }
    }
      echo 1;
  }
  else
  {
      echo 0;
  }
?>