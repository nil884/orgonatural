 <?php include("../includes/configuration.php"); ?>
<!doctype html>
<html lang='en'>
<head>
<meta charset='utf-8'>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo SITENAME; ?> Support</title>
<?php include 'commoncss.php';?>
</head>
<body class="mainbody">
<?php include('menu.php');
     $req=base64_decode($_REQUEST['req']);

      $ticketdetails=selectQuery(CONTACT,"*","internal_lead_id=".$req);
      $guestcount=selectQuery(GUEST,"guest_id","leads like '%".$req."%'");
      $comments=selectQuery(SUPPORTCOMMENT,"*","ticket_id=".$req." and comment_type='Internal' order by comment_id ASC");
      $externalcomments=selectQuery(SUPPORTCOMMENT,"*","ticket_id=".$req." and comment_type='External' order by comment_id ASC");
      $lastresponse=selectQuery(SUPPORTCOMMENT,"*","ticket_id=".$req." order by comment_id DESC LIMIT 1");
      $getdeptname=selectQuery(SUPPORTDEPT,"*","dept_id=".$ticketdetails[0]['dept']);
        $assignedstaff=selectQuery(SUPPORTSTAFF,"*","emp_id=".$ticketdetails[0]['assign_to']);
       $entry_by=selectQuery(SUPPORTSTAFF,"*","emp_id=".$ticketdetails[0]['entry_by']);

    $supportstaffgroup = selectQuery(SUPPORTSTAFFGROUP,"*");

       if($allowedimgtypes!=""&&$allowedapptypes!="")
       {
           $allowed= $allowedimgtypes.",".$allowedapptypes;
       }
       else if($allowedimgtypes!=""&&$allowedapptypes=="")
       {
           $allowed= $allowedimgtypes;
       }
       else if($allowedimgtypes==""&&$allowedapptypes!="")
       {
           $allowed= $allowedapptypes;
       }
        else if($allowedimgtypes==""&&$allowedapptypes=="")
       {
           $allowed= "";
       }
       $transfer=$loginstaff[0]['Transfer'];

     ?>

<div class="content">
     <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1  col-sm-12 col-xs-12">
              <div class="card">
                <div class="panel-body">
                <div class="pull-left reply"><h4>
                <?php

                    $getacctype = selectQuery(SUPPORTSTAFF,"*","emp_id='".$ticketdetails[0]['entry_by']."' ");

                    if($getacctype[0]['acc_type']=="Client")
                    {
                        echo $fontawesome ="<i class='fa fa-copyright' aria-hidden='true' style='color:#CC0066;'></i>";     //Copyright Symbol for Client
                    }
                    else
                    {
                        $fontawesome="&nbsp;&nbsp;&nbsp;";
                    }

                ?>

                Case # - <?php echo $req; ?> </h4>

                </div>
                <div class="pull-right">
                     <?php
                            if($loginstaff[0]['Delete1']=="1")
                            {
                             ?>
                               <button type="button" class="btn btnPrimary rightBtn " onclick="dellead('<?php echo $req; ?>')"> Delete Case </button>
                             <?
                            }
                            ?>
                    <?php  if($loginstaff[0]['Edit']=="1") {?>
                    <a href="lead_update.php?lead=<?php echo base64_encode($req); ?>" class="btn btnPrimary rightBtn" > EDIT </a>
                    <?php }?>
                    <button type="button" class="btn btnPrimary rightBtn" onclick="goBack()"> BACK </button>

                </div>
                 </div>
                 </div>
                <!-- <div class="card">
                <div class="panel-body">
                        <div class="row text-center">
                          <div class="col-md-3 col-sm-3 col-xs-6 stepIcon">
                          <?php
                            if(count($ticketdetails))
                            {
                                ?>
                                    <i class="fa fa-check-circle" aria-hidden="true"></i>
                                <?
                            }
                            else
                            {
                                ?>
                                   <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                <?
                            }

                           ?>
                           <br>
                            <?php
                            if($loginstaff[0]['Edit']=="1"){
                            ?>
                           <a href="lead_update.php?lead=<?php echo base64_encode($req); ?>" target="_blank">
                            <?
                          }  ?>Lead Entry
                           <?php
                            if($loginstaff[0]['Edit']=="1"){
                             ?> </a> <?
                            }
                            ?>
                          </div>
                          <div class="col-md-3 col-sm-3 col-xs-6 stepIcon">
                          <?php
                          if(count($guestcount))
                          {
                              ?>
                                  <i class="fa fa-check-circle" aria-hidden="true"></i>
                              <?
                          }
                          else
                          {
                              ?>
                                 <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                              <?
                          }
                          ?>
                         <br>
                          <a href="guestmasterindex.php?lead=<?php echo base64_encode($req); ?>" target="_blank">
                           Guest Master (<?php echo count($guestcount); ?>)
                           </a>

                         </div>
                          <div class="col-md-3 col-sm-3 col-xs-6 stepIcon"> <i class="fa fa-exclamation-circle" aria-hidden="true"></i> <br>    <a href="">Visa Team</a></div>
                          <div class="col-md-3 col-sm-3 col-xs-6 stepIcon"> <i class="fa fa-exclamation-circle" aria-hidden="true"></i> <br>   <a href="">Operation Team</a></div>
                        </div>
                    </div>
                  </div>-->
               </div>

            <div class="col-md-10 col-md-offset-1  col-sm-12 col-xs-12">
              <?php
                if($ticketdetails[0]['isOverdue']==1)
                {
                   ?>

                       <div class="overduebox alert alert-danger">
                        <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Lead Marked As Overdue
                     </div>


                   <?
                }
                 ?>
            <div class="card">
            <div class="panel-body">
            <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12">
                     <table class="table ticketStatus">
                        <tr>
                           <td class="detail borderTop0"><b>Client Name</b></td>
                           <td class="detail mute-text borderTop0"><?php if($ticketdetails[0]['client_name']){echo $ticketdetails[0]['client_name'];}else{ echo "-"; } ?></td>
                        </tr>

                        <tr>
                            <td class="detail " style="width:44%;"><b>Status</b></td>
                            <td class="detail mute-text ">
                            <?php
                                if($ticketdetails[0]['isOpen']=="1")
                                {
                                    echo "Open";
                                }
                                else if($ticketdetails[0]['isAnswered']=="1")
                                {
                                    echo "Answered";
                                }
                                 else if($ticketdetails[0]['isClosed']=="1")
                                {
                                    echo "Closed";
                                }
                                 else if($ticketdetails[0]['isConfirmed']=="1")
                                {
                                    echo "Confirm";
                                }
                                 else if($ticketdetails[0]['isTerminated']=="1")
                                {
                                    echo "Terminated";
                                }
                                else if($ticketdetails[0]['isReopen']=="1")
                                {
                                    echo "Reopen";
                                }
                            ?>
                            </td>
                         </tr>

                          <tr>
                              <td class="detail"><b>Created Date</b></td>
                            <td class="detail mute-text"><?php echo date('d/m/Y H:i:s', strtotime($ticketdetails[0]['entry_date'])); ?></td>
                          </tr>

                           <tr>
                              <td class="detail"><b>Last Response</b></td>
                            <td class="detail mute-text"><?php if($lastresponse[0]['comment_date']){echo $lastresponse[0]['comment_date'];}else{ echo "-"; } ?></td>
                          </tr>
                          <?php
                           if($ticketdetails[0]['isClosed']=="1")
                           {
                                ?>
                                <tr>
                                  <td class="detail"><b>Closed On</b></td>
                                <td class="detail mute-text"><?php echo date('d/m/Y H:i:s', strtotime($ticketdetails[0]['closedDate'])); ?></td>
                              </tr>
                                <?
                           }
                           ?>
                     </table>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                        <table class="table ticketStatus">
                            <tr>
                                <td class="detail borderTop0" style="width:44%;"><b>Project Name</b></td>
                                <td class="detail mute-text borderTop0"><?php if($ticketdetails[0]['project_name']!=""){ echo $ticketdetails[0]['project_name'];}else{ echo "Not Defined"; }  ?></td>
                            </tr>
                            <tr>
                               <td class="detail"><b>Defect Type</b></td>
                               <td class="detail mute-text"> <?php if($ticketdetails[0]['defect_type']!=""){ echo $ticketdetails[0]['defect_type'];}else{ echo "Not Defined"; } ?></td>
                            </tr>
                            <tr>
                                <td class="detail"><b>Issue In Panel</b></td>
                                <td class="detail mute-text"><?php if($ticketdetails[0]['issue_panel']!=""){ echo $ticketdetails[0]['issue_panel'];}else{ echo "Not Defined"; } ?></td>
                            </tr>
                            <?php
                                if($_SESSION['acc_type']=="Admin" || $_SESSION['acc_type']=="Staff")
                                {
                            ?>
                                <tr>
                                    <td class="detail"><b>Assigned Staff</b></td>
                                    <td class="detail mute-text"><?php echo $assignedstaff[0]['emp_name']; ?></td>
                                </tr>
                            <?}?>

                        </table>
                </div>
                </div>
                </div>
    </div>

    <div class="card ticket-thread">
        <div class="panel-heading"><div class="panel-title">Lead Thread</div></div>
            <div class="panel-body">
            <?php
                if($_SESSION['acc_type']=="Admin" || $_SESSION['acc_type']=="Staff")
                {
            ?>
                <div class="row">
                    <button class="btn btn-link" onclick="showthreads('internalthreads','externalthreads')"><b><i class="fa fa-sticky-note-o" aria-hidden="true"></i> Internal Communication (<?php echo count($comments); ?>)</b> </button>
                </div>

                <div class="clearfix"></div>
                <div class="internalthreads">

                    <!-- ************************************Internal comments start************************* -->
                        <?php
                      for($i=0;$i<count($comments);$i++)
                      {
                           $attachment=selectQuery(SUPPORTIMG,"*","response_id=".$comments[$i]['comment_id']);
                           if($comments[$i]['comment_by']!="client")
                          {
                              $commentby=selectQuery(SUPPORTSTAFF,"*","emp_id=".$comments[$i]['comment_by']);
                              $commenter=$commentby[0]['emp_name'];

                          }
                          else
                          {
                              $commenter= $ticketdetails[0]['Name'];
                          }
                        ?>

                <div class="list-view">
                       <?php
                          if($comments[$i]['comment_by']!="client")
                          {
                             ?>
                            <div class="bg-success">
                             <?
                          }
                          else
                          {
                            ?>
                                <div class="bg-info">
                            <?
                            }
                            ?>
                            <?php echo $comments[$i]['comment_date']." - ".$commenter. " - ".$comments[$i]['extra']; ?>
                                </div>
                            <?php

                                if(count($attachment))
                                {
                                ?>
                                    <div class="attachbox">
                                        <?php
                                          for($a=0;$a<count($attachment);$a++)
                                          {
                                              ?>
                                              <span><a href="<?php echo SITEURL; ?>/img/support_tkt_comments/<?php echo $attachment[$a]['img_name']; ?>" target="_blank">
                                               Attachment-<?php echo $a+1; ?>
                                              </a>
                                              </span>
                                              <?
                                          }
                                        ?>
                                         </div>
                                       <?
                                }
                                ?>
                                  <div class="commentbox">
                                    <?php echo $comments[$i]['comment']; ?>
                                  </div>
                            </div>
                        <?
                      }
                    ?>
                </div>
            <?php }?>
                <div class="clearfix"></div>
                    <div class="row">
                    <?php
                if($_SESSION['acc_type']!="Client")
                {
                ?>    <button class="btn btn-link" onclick="showthreads('externalthreads','internalthreads')"><b><i class="fa fa-sticky-note-o" aria-hidden="true"></i>  Client Communication (<?php echo count($externalcomments); ?>) </b></button><?php }?>

                <?php
                if($_SESSION['acc_type']=="Client")
                {
                ?>
                <!-- ************************************Lead details************************* -->
                    <div class="list-view">
                        <div class="bg-info">
                            <?php echo date('d/m/Y H:i:s', strtotime($ticketdetails[0]['entry_date']))." - ".$entry_by[0]['emp_name']; ?>  - Lead Created
                        </div>
                        <div class="commentbox">
                            <div class="row">
                                <div class="col-md-2 col-sm-3"><b>Project Name</b></div>
                                <div class="col-md-10 col-sm-9"><?php if($ticketdetails[0]['project_name']!=""){ echo $ticketdetails[0]['project_name'];}else{ echo "Not Defined"; }  ?></div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 col-sm-3"><b>Defect Type</b></div>
                                <div class="col-md-10 col-sm-9"><?php if($ticketdetails[0]['defect_type']!=""){ echo $ticketdetails[0]['defect_type'];}else{ echo "Not Defined"; } ?></div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 col-sm-3"><b>Issue In Panel</b></div>
                                <div class="col-md-10 col-sm-9"><?php if($ticketdetails[0]['issue_panel']!=""){ echo $ticketdetails[0]['issue_panel'];}else{ echo "Not Defined"; } ?></div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 col-sm-3"><b>Issue Detail</b></div>
                                <div class="col-md-10 col-sm-9"><?php if($ticketdetails[0]['issue_detail']!=""){ echo nl2br($ticketdetails[0]['issue_detail']);}else{ echo "Not Defined"; } ?></div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 col-sm-3"><b>Issue Steps</b></div>
                                <div class="col-md-10 col-sm-9"><?php if($ticketdetails[0]['issue_steps']!=""){ echo nl2br($ticketdetails[0]['issue_steps']); }else{ echo "Not Defined"; } ?></div>
                            </div>
                            <?php if($ticketdetails[0]['attachment1']!="" || $ticketdetails[0]['attachment2']!="" || $ticketdetails[0]['attachment3']!="" || $ticketdetails[0]['attachment4']!=""){?>
                            <div class="row">
                                <div class="col-md-2 col-sm-3"><b>Attachments</b></div>
                                <div class="col-md-10 col-sm-9">
                                    <div class="">
                                    <span><?php if($ticketdetails[0]['attachment1']!=""){?><a href="<?php echo SITEURL; ?>/img/attachments/<?php  echo $ticketdetails[0]['attachment1']; ?>" target="_blank">Attachment-1</a></span>,<?php }?>
                                    <span><?php if($ticketdetails[0]['attachment2']!=""){ ?><a href="<?php echo SITEURL; ?>/img/attachments/<?php echo $ticketdetails[0]['attachment2']; ?>" target="_blank">Attachment-2</a></span>,<?php }?>
                                    <span><?php if($ticketdetails[0]['attachment3']!=""){ ?><a href="<?php echo SITEURL; ?>/img/attachments/<?php echo $ticketdetails[0]['attachment3']; ?>" target="_blank">Attachment-3</a></span>,<?php }?>
                                    <span><?php if($ticketdetails[0]['attachment4']!=""){ ?><a href="<?php echo SITEURL; ?>/img/attachments/<?php  echo $ticketdetails[0]['attachment4']; ?>" target="_blank">Attachment-4</a></span><?php }?>
                                    </div>
                                </div>
                            </div>
                          <?php }?>

                        </div>
                    </div>
                <?php }?>


                    </div>
                    <div class="clearfix"></div>
                    <div class="externalthreads">


                                 <!-- ************************************Lead details************************* -->
                    <div class="list-view">
                        <div class="bg-info">
                            <?php echo date('d/m/Y H:i:s', strtotime($ticketdetails[0]['entry_date']))." - ".$entry_by[0]['emp_name']; ?>  - Lead Created
                        </div>
                        <div class="commentbox">
                            <div class="row">
                                <div class="col-md-2 col-sm-3"><b>Project Name</b></div>
                                <div class="col-md-10 col-sm-9"><?php if($ticketdetails[0]['project_name']!=""){ echo $ticketdetails[0]['project_name'];}else{ echo "Not Defined"; }  ?></div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 col-sm-3"><b>Defect Type</b></div>
                                <div class="col-md-10 col-sm-9"><?php if($ticketdetails[0]['defect_type']!=""){ echo $ticketdetails[0]['defect_type'];}else{ echo "Not Defined"; } ?></div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 col-sm-3"><b>Issue In Panel</b></div>
                                <div class="col-md-10 col-sm-9"><?php if($ticketdetails[0]['issue_panel']!=""){ echo $ticketdetails[0]['issue_panel'];}else{ echo "Not Defined"; } ?></div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 col-sm-3"><b>Issue Detail</b></div>
                                <div class="col-md-10 col-sm-9"><?php if($ticketdetails[0]['issue_detail']!=""){ echo $ticketdetails[0]['issue_detail'];}else{ echo "Not Defined"; } ?></div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 col-sm-3"><b>Issue Steps</b></div>
                                <div class="col-md-10 col-sm-9"><?php if($ticketdetails[0]['issue_steps']!=""){ echo nl2br($ticketdetails[0]['issue_steps']); }else{ echo "Not Defined"; } ?></div>
                            </div>
                            <?php if($ticketdetails[0]['attachment1']!="" || $ticketdetails[0]['attachment2']!="" || $ticketdetails[0]['attachment3']!="" || $ticketdetails[0]['attachment4']!=""){?>
                            <div class="row">
                                <div class="col-md-2 col-sm-3"><b>Attachments</b></div>
                                <div class="col-md-10 col-sm-9">
                                    <div class="">
                                    <span><?php if($ticketdetails[0]['attachment1']!=""){?><a href="<?php echo SITEURL; ?>/img/attachments/<?php  echo $ticketdetails[0]['attachment1']; ?>" target="_blank">Attachment-1</a></span>,<?php }?>
                                    <span><?php if($ticketdetails[0]['attachment2']!=""){ ?><a href="<?php echo SITEURL; ?>/img/attachments/<?php echo $ticketdetails[0]['attachment2']; ?>" target="_blank">Attachment-2</a></span>,<?php }?>
                                    <span><?php if($ticketdetails[0]['attachment3']!=""){ ?><a href="<?php echo SITEURL; ?>/img/attachments/<?php echo $ticketdetails[0]['attachment3']; ?>" target="_blank">Attachment-3</a></span>,<?php }?>
                                    <span><?php if($ticketdetails[0]['attachment4']!=""){ ?><a href="<?php echo SITEURL; ?>/img/attachments/<?php  echo $ticketdetails[0]['attachment4']; ?>" target="_blank">Attachment-4</a></span><?php }?>
                                    </div>
                                </div>
                            </div>
                          <?php }?>

                        </div>
                    </div>



                      <!-- ************************************External comments start************************* -->
                          <?php
                      for($i=0;$i<count($externalcomments);$i++)
                      {
                           $attachment=selectQuery(SUPPORTIMG,"*","response_id=".$externalcomments[$i]['comment_id']);
                           if($externalcomments[$i]['comment_by']!="client")
                          {
                              $commentby=selectQuery(SUPPORTSTAFF,"*","emp_id=".$externalcomments[$i]['comment_by']);
                              $commenter=$commentby[0]['emp_name'];

                          }
                          else
                          {
                              $commenter= $ticketdetails[0]['Name'];
                          }
                        ?>

                            <div class="list-view">
                                   <?php
                                      if($externalcomments[$i]['comment_by']!="client")
                                      {
                                         ?>
                                             <div class="bg-success">
                                         <?
                                      }
                                      else
                                      {
                                         ?>
                                             <div class="bg-info">
                                         <?
                                      }
                                    ?>
                                         <?php echo $externalcomments[$i]['comment_date']." - ".$commenter. " - ".$externalcomments[$i]['extra']; ?>
                                        </div>
                                   <?php

                                    if(count($attachment))
                                    {
                                       ?>
                                         <div class="attachbox">


                                                 <?php
                                                  for($a=0;$a<count($attachment);$a++)
                                                  {
                                                      ?>
                                                      <span><a href="<?php echo SITEURL; ?>/img/support_tkt_comments/<?php echo $attachment[$a]['img_name']; ?>" target="_blank">
                                                       Attachment-<?php echo $a+1; ?>
                                                      </a>
                                                      </span>
                                                      <?
                                                  }
                                                ?>


                                         </div>
                                       <?
                                    }
                                  ?>
                                  <div class="commentbox">
                                    <?php echo $externalcomments[$i]['comment']; ?>
                                  </div>
                            </div>
                        <?
                      }
                    ?>
                    </div>
                </div>
             </div>
     </div>
    <div class="col-md-10 col-md-offset-1  col-sm-12 col-xs-12">
     <div class="card">
        <div class="panel-heading"><div class="panel-title">Post Your Reply</div></div>
         <div class="panel-body">
             <div>
             <div class="clearfix"></div>
             <div class="errormsg col-md-12"></div>
             </div>
             <div>
               <form action="" name="reply" method="post" enctype="multipart/form-data">
              <div class="form-group">
            <?php
                if($_SESSION['acc_type']=="Client")
                {
            ?>
              <div class="hideoption" style="display:none;">
              <input type="radio" name="postoption" class="postoption" value="Internal" /> Internal Reply&nbsp;&nbsp;
              <input type="radio" name="postoption" class="postoption" value="Post Reply" checked/> Post Reply To Client&nbsp;&nbsp;
              </div>
            <?}
              else
              {
            ?>
              <input type="radio" name="postoption" class="postoption" value="Internal" checked/> Internal Reply&nbsp;&nbsp;
              <input type="radio" name="postoption" class="postoption" value="Post Reply" /> Post Reply To Client&nbsp;&nbsp;
            <?php } ?>
              <span <? if($transfer=="0"){ ?> style="display:none" <? } ?>><input type="radio" name="postoption" class="postoption" value="Transfer Ticket" /> Transfer Ticket</span>&nbsp;&nbsp;
              <span <? if($transfer=="0"){ ?> style="display:none" <? } ?>><input type="radio" name="postoption" class="postoption" value="Reassign Ticket"/> Reassign Ticket</span>
             </div>
              <div class="form-group transfertodept">
                <?php
                  $dept=selectQuery(SUPPORTDEPT,"*","isDel='0' and isActive='1' and dept_id<>".$ticketdetails[0]['dept']);
                ?>
                  Transfer To -
                  <select name="chngdept" class="chngdept">
                    <option value=""> - Transfer to department - </option>
                    <?php
                       for($i=0;$i<count($dept);$i++)
                       {
                          ?>
                           <option value="<?php echo $dept[$i]['dept_id']; ?>"><?php echo $dept[$i]['dept_name']; ?> </option>
                          <?
                       }
                    ?>
                  </select>
              </div>
                <div class="form-group reassigntkt">
                <?php
                  $staff=selectQuery(SUPPORTSTAFF,"*","acc_type='Admin' || acc_type='Staff' and isActive='1' order by emp_name ASC");
                ?>
                  Assign To -
                  <select name="assignto" class="assignto">
                    <option value=""> - Reassign - </option>
                     <?php
                       for($i=0;$i<count($staff);$i++)
                       {
                           if($assignedstaff[0]['emp_id']!=$staff[$i]['emp_id'])
                           {
                               ?>
                                    <option value="<?php echo $staff[$i]['emp_id']; ?>"><?php echo $staff[$i]['emp_name']; ?> </option>
                               <?
                           }
                          ?>

                          <?
                       }
                    ?>
                  </select>
              </div>

             <div class="form-group">
                    <input type="hidden" name="staff"  id="staff" value="<?php echo  $_SESSION['staff']; ?>"/>
                     <input type="hidden" name="requestid"  id="requestid" value="<?php echo $req; ?>"/>
                      <input type="hidden" name="allowedimgformat"  id="allowedimgformat" value="<?php echo $allowedimgtypes; ?>"/>
                       <input type="hidden" name="allowedappformat"  id="allowedappformat" value="<?php echo $allowedapptypes; ?>"/>
                       <input type="hidden" name="allowedmaxsize"  id="allowedmaxsize" value="<?php echo $attachmentmax; ?>"/>
                     <textarea class="summernote" id="editor1"  name="text_details" style="width:100%;min-height:200px !important"></textarea>
             </div>
              <div class="form-group">
              <span>Attach File (Allowed Type: <?php echo $allowed; ?> || Max File Size : <?php echo ($attachmentmax/1024)/1024; ?> MB)</span>
              <input type="file" name="attach" id="attach"/>
             </div>
             <?php
              if($ticketdetails[0]['isClosed']=="1")
               {
                      ?>
                        <div class="form-group" style="display:none">
                         <input type="checkbox" name="closetkt" class="closetkt"  <? if($loginstaff[0]['Close']=="0"){?> style="display:none"; <? } ?>/> <? if($loginstaff[0]['Close']=="1"){?> Close On Reply <?php } ?>
                     </div>
                      <?
               }
               else
               {
                   ?>
                     <div class="form-group" >
                         <input type="checkbox" name="closetkt" class="closetkt"  <? if($loginstaff[0]['Close']=="0"){?> style="display:none"; <? } ?>/> <? if($loginstaff[0]['Close']=="1"){?> Close On Reply <?php } ?>
                     </div>
                   <?
               }
             ?>

             <div class="form-group margin0">
                 <button type="button"  class="btn btnPrimary postreply" name="postreply">Post Reply</button>
                 <div class="loader"></div>
             </div>
        </form>
        </div>
        </div>
        </div>
        </div>
        </div>

</div>
</div>
</div>
</div>
<?php include('footer.php'); ?>

 <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.js"></script>
 <script src="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script>
  <script type="text/javascript" src="<?php echo SITEURL; ?>/js/bootstrap.min.js"></script>

      <link rel="stylesheet" href="../summernote/summernote.css">
      <script type="text/javascript" src="../summernote/summernote.js"></script>
       <script>
         $(function() {
          $('.summernote').summernote({
          //fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New', 'Merriweather'],
            height: 200
         });
      });
       </script>
     <script>  $('.summernote').val("");
     $('.summernote').val("");
     $('#attach').val("");
        $(document).ready(function(){
            $(".postreply").click(function(){

                var allowedimgformat=$("#allowedimgformat").val();
                var allowedappformat=$("#allowedappformat").val();
                var allowedmaxsize=$("#allowedmaxsize").val();
                 allowarr=[];
                  var imgarr1 = allowedimgformat.split(",");

                for(var i = 0; i < imgarr1.length; i++) {
                     if(imgarr1[i]!="")
                    {
                      var imgstr='image/'+imgarr1[i];
                      allowarr.push(imgstr);
                    }
                }
                var apparr1 = allowedappformat.split(",");

                for(var i = 0; i < apparr1.length; i++) {
                    if(apparr1[i]!="")
                    {
                       var appstr='application/'+apparr1[i];
                      allowarr.push(appstr);
                    }
                }
                var allowstr= allowarr.toString();



              var dept=$(".transfertodept option:selected").val();
               /* var staff=$(".reassigntkt option:selected").val();  */
                var assignto=$(".assignto option:selected").val();
                var chngdept=$(".chngdept option:selected").val();
                var sel= $(".postoption:checked").val();
              var staff=$("#staff").val();
              var requestid=$("#requestid").val();
              var textareaValue = $('.summernote').summernote('code');
              var cleanText = $(".summernote").summernote('code').replace(/<\/?[^>]+(>|$)/g, "");
              var isclose=$(".closetkt:checked").val();
              if($('#attach').val()!="")
              {
                    var attachment=$('#attach').prop('files')[0];
                    var attachmentsize = ($('#attach'))[0].files[0].size;
                    var attachmenttype = ($('#attach'))[0].files[0].type;
                    var attachmentname = ($('#attach'))[0].files[0].name;
              }
                /*  alert(attachmentname)        */
                     if(isclose=="on")
                     {
                        var closed=1;
                     }
                     else
                     {
                         var closed=0;
                     }

                    if(cleanText=="")
                    {
                         $(".errormsg").fadeIn().html("Please enter comment to submit your post").addClass("alert alert-danger").delay(3000).fadeOut();
                    }
                    else if(attachmentname!=""&&(attachmentsize>allowedmaxsize))
                    {

                         $(".errormsg").fadeIn().html("Attachment Size is not in limit").addClass("alert alert-danger").delay(3000).fadeOut();
                    }
                    else if($('#attach').val()!=""&&allowarr.indexOf(attachmenttype)==-1)
                    {

                         $(".errormsg").fadeIn().html("Invalid Attachment").addClass("alert alert-danger").delay(3000).fadeOut();
                    }
                    else if(sel=="Transfer Ticket" && chngdept=="")
                    {
                         $(".errormsg").fadeIn().html("Please Select Department To Transfer Lead").addClass("alert alert-danger").delay(3000).fadeOut();
                    }
                    else if(sel=="Reassign Ticket" && assignto=="")
                    {
                         $(".errormsg").fadeIn().html("Please Select Staff To Reassign Lead").addClass("alert alert-danger").delay(3000).fadeOut();
                    }
                    else
                    {
                        var load= '<?php echo LOADER; ?>';
                        var loader=' <img src="'+load+'" alt="" style="width:20%"/>  ';
                        $(".postreply").append(" "+loader);
                        $(".postreply").attr("disabled",true);

                        var form_data = new FormData();

                        form_data.append('staff', staff);
                        form_data.append('requestid', requestid);
                        form_data.append('comment', textareaValue);
                        form_data.append('closed', closed);
                        form_data.append('dept', dept);
                        form_data.append('posttype', sel);
                        form_data.append('assignto', assignto);
                        form_data.append('chngdept', chngdept);
                        form_data.append('attachment', attachment);

                        $.ajax({
                            type:"POST",
                            url:"savecomments.php",
                            data:form_data,
                            cache: false,
                            contentType: false,
                            processData: false,
                            success:function(response){
                                $(".postreply img").remove();
                                $(".postreply").attr("disabled",false);
                                if(response)
                                {
                                    if(response==1)
                                    {
                                        location.reload();
                                    }
                                    else
                                    {
                                        $(".loader").html('Error while insert.. try again later');
                                    }
                                }

                            }
                        });
                    }

            });
        });

        function showthreads(class1,class2)
        {
            $("."+class1).toggle();
          /*  $("."+class2).hide();  */
        }

        function dellead(leadid)
        {
            if(confirm("Are You Sure To Delete This Lead"))
            {
                info={leadid:leadid,action:"del_lead"}
                $.ajax({
                   type:"POST",
                    url:"leadentry.php",
                    data:info,
                    success:function(response){
                        if(response==1)
                        {
                            window.location="<?php echo SUPPORTURL; ?>home.php";
                        }
                        else
                        {
                            alert("Error In Delete")
                        }
                    }
                });
            }
        }
     </script>
     <script>
      $(".transfertodept").hide();
      $(".reassigntkt").hide();
               $(".postoption").on("change",function(){
                    $(".transfertodept").hide();
                      $(".reassigntkt").hide();
                 var sel= $(".postoption:checked").val();
                if(sel=="Transfer Ticket")
                {
                    $(".transfertodept").show();
                     $(".reassigntkt").val("");
                }
                else if(sel=="Reassign Ticket")
                {
                    $(".reassigntkt").show();
                    $(".transfertodept").val("");
                }
                else
                {
                      $(".transfertodept").hide();
                      $(".reassigntkt").hide();
                       $(".transfertodept").val("");
                        $(".reassigntkt").val("");
                }
               });

       </script>
</body>
</html>