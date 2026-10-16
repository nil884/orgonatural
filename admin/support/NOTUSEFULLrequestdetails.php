
     <?php
        include("../../includes/configuration.php");


     $req=base64_decode($_REQUEST['req']);

       $adminid=selectQuery(SUPPORTSTAFF,"*","post='Admin'");
      $ticketdetails=selectQuery(CONTACT,"*","contact_request_id=".$req);
      if($ticketdetails[0]['dept']!="")
      {
            $assigneddept=selectQuery(SUPPORTDEPT,"*","dept_id=".$ticketdetails[0]['dept']);
            $departmentname=$assigneddept[0]['dept_name'];
      }
       if($ticketdetails[0]['assigned_staff']!="")
      {
            $assignedstaff=selectQuery(SUPPORTSTAFF,"*","emp_id=".$ticketdetails[0]['assigned_staff']);
            $staffname=$assignedstaff[0]['emp_name'];
      }
     $comments=selectQuery(SUPPORTCOMMENT,"*","ticket_id=".$req." order by comment_id ASC");
       $lastresponse=selectQuery(SUPPORTCOMMENT,"*","ticket_id=".$req." order by comment_id DESC LIMIT 1");

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
     ?>
     <!doctype html>
    <html>
    <head>
        <title><?php echo SITE_TITLE; ?> : Support Requests - <?php echo $getrequest[0]['dept_name']; ?></title>
        <meta charset='utf-8'>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="<?php echo METAKEYWORDS; ?>"  >
        <meta name="description" content ="<?php echo METADESCRIPTION; ?>">
        <?php include('../commoncss.php') ?>
        <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/bootstrap-datetimepicker.css" />
        <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/bootstrap-table.css" />
        <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/couponcustom.css" />
        <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/jquery.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo SITEURL; ?>/css/buttons.dataTables.min.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo SITEURL; ?>/css/responsive.dataTables.min.css" />
        <link rel="stylesheet" href="<?php echo SITEURL; ?>/css/bootstrap-toggle.min.css" />
      <style>
         .detail
         {

         }
         .commentheadclient
         {
             background:#f7f57b ;
             font-weight:bold;
             padding-top:5px;
             padding-bottom:5px;
         }
         .commentheadstaff
         {
             background:#98fcff;
             font-weight:bold;
             padding-top:5px;
             padding-bottom:5px;
         }

         .commentbox
         {
             border:1px solid #ADADAD;
             padding-top:5px;
             padding-bottom:5px;
         }
         .attachbox
         {
              padding-top:5px;
             padding-bottom:5px;
             background:#E3E3E3
         }
         .reply
         {
             font-weight:bold;
             color:#2677CC;
         }
          .midline
          {
                display: block;
                height: 1px;
                border: 0;
                border-top: 2px solid #4967C1;
                margin-top:10px;
                margin-bottom:10px;
          }
          .cmt
          {
              margin-top:10px
          }
          .navbar
          {
              margin-bottom:0px;
          }
          .tr0
          {
              width:15%;
              font-size:12px;
              font-weight:bold;
          }
         .tr1
         {
            width:30%;
             font-size:12px;
         }
        #chngdue
        {
            border: none;
            background:none;
            color:#187ECC;
        }
       .duebox
       {
           margin-top: 15px;
           border:1px solid #4A4A4A
       }
       .outerduebox
       {
           display: none;
       }
        .duemsg
        {
            color:red;
            text-align:center;
        }
       .tktbox
        {
           margin-top: 15px;
           border:1px solid #696969
        }
        table td
        {
            border-top:none !important;
        }
          .overduebox
         {
             color:red;
             background:#FDEDC1;
             font-weight:bold;
             padding-top:5px;
             padding-bottom:5px;
         }
        .fa-exclamation-triangle
        {
            color: #CC921C;
        }
      </style>
      </head>

<body>

<div class="dashcontainer">
     <?php include('../header.php') ?>

    <?php include('../sidebar.php') ?>


<div class="right_col">
        <div class="dashbody">
        <div class="card">
        <div class="panel-body">

     <div class="col-md-12 col-xs-12 col-sm-12" style="margin-top:10px;margin-bottom:80px;">
          <div class="col-md-12 col-sm-12 col-xs-12 p0">
            <div class="col-md-10 col-sm-10 col-xs-10 reply"><h4>Ticket - #<?php echo $req; ?> (<?php echo $ticketdetails[0]['status']; ?>)</h4></div>
            <div class="col-md-2 col-sm-2 col-xs-2 text-right"><button type="button" class="btn btn-primary" onclick="goBack()"> BACK </button></div>
             </div>
             <div class="col-md-12  col-sm-12 col-xs-12 tktbox">
            <div class="col-md-12 col-sm-12 col-xs-12" style="margin-top: 15px;">
             <table class="table">
                 <tr>
                      <td class="detail tr0">Status</td>
                         <td class="detail tr1"><?php echo $ticketdetails[0]['status']; ?></td>

                      <td class="detail tr0">Client</td>
                    <td class="detail tr1"><?php echo $ticketdetails[0]['Name']; ?></td>
                 </tr>
                  <tr>
                        <td class="detail tr0">Created On</td>
                         <td class="detail tr1"><?php echo $ticketdetails[0]['date']; ?></td>
                      <td class="detail tr0">Email</td>
                      <td class="detail tr1"> <?php echo $ticketdetails[0]['Email']; ?></td>

                  </tr>
                  <tr>
                         <td class="detail tr0">Due Date</td>
                         <td class="detail tr1">
                            <?php
                           /* $d1=$ticketdetails[0]['overdue_date'];
                              $due=date("d/m/Y H:i:s",strtotime($d1))*/
                              $originalDate = $ticketdetails[0]['overdue_date'];
                            $newDate = date("d/m/Y H:i:s", strtotime($originalDate));
                            echo $newDate;
                            ?>
                             &nbsp;&nbsp;(<button type="button" id="chngdue">Change Due Date</button>)
                            </td>
                       <td class="detail tr0">Mobile</td>
                      <td class="detail tr1"><?php echo $ticketdetails[0]['Telephone']; ?></td>
                  </tr>
                   <tr>
                      <td class="detail tr0"><b>Last Response</b></td>
                    <td class="detail tr1"  ><?php echo $lastresponse[0]['comment_date']; ?></td>
                    <td></td>
                    <td></td>
                  </tr>
             </table>
             </div>
              <div class="col-md-12 col-sm-12 col-xs-12 outerduebox">
                  <div class="col-md-12 col-sm-12 col-xs-12 duebox">
                   <div class="form-group" style="margin-bottom: 60px; margin-top: 10px;">
                      <label class="col-md-3 col-md-offset-1 control-label text-left lb1 p0">Select New Due Date</label>
                          <div class="col-md-4">
                                <input type='text' class="form-control" id='datetimepicker1' />
                        </div>
                         <div class="col-md-4">
                            <button type="button" id="setdue" class="btn btn-primary">SET</button>
                             <button type="button" id="canceldue" class="btn btn-primary">CANCEL</button>
                         </div>
          			   <!--	<input type="hidden" id="dtp_input2" value="" /><br/>          -->
                  </div>
                  <div class="clearfix"></div>
                  <div class="col-md-12 col-sm-12 col-xs-12 duemsg"></div>
                   </div>
              </div>

              <div class="col-md-12 col-sm-12 col-xs-12" style="margin-top: 15px;">
             <table class="table">
                 <tr>
                    <td class="detail tr0">Department</td>
                    <td class="detail tr1"><?php if($departmentname){echo $departmentname;}else{echo "-";} ?></td>
                    <td class="detail tr0">Assigned Staff</td>
                    <td class="detail tr1"><?php if($staffname){echo $staffname;}else{echo "-";} ?></td>
                 </tr>

             </table>

             </div>
               <?php
                if($ticketdetails[0]['isOverdue']==1)
                {
                   ?>
                   <div class="col-md-12">
                       <div class="col-md-12  overduebox">
                        <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>  Ticket Marked As Overdue
                     </div>
                   </div>

                   <?
                }
                 ?>
              <div class="col-md-12 col-sm-12 col-xs-12">
              <hr class="midline">
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12" style="margin-top: 15px;">
               <div class="col-md-12 col-sm-12 col-xs-12 p0 reply">
                TICKET THREAD -
               </div>
                     <div class="col-md-12 col-sm-12 col-xs-12 p0 cmt">
                          <div class="col-md-12 col-sm-12 col-xs-12 commentheadclient">
                            <?php echo $ticketdetails[0]['date']." - ".$ticketdetails[0]['Name']; ?>
                          </div>
                          <div class="col-md-12 col-sm-12 col-xs-12 commentbox">
                            <?php echo $ticketdetails[0]['Comment']; ?>
                          </div>
                    </div>
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
                            <div class="col-md-12 col-sm-12 col-xs-12 p0 cmt">
                                   <?php
                              if($comments[$i]['comment_by']!="client")
                              {
                                 ?>
                                     <div class="col-md-12 col-sm-12 col-xs-12 commentheadstaff">
                                 <?
                              }
                              else
                              {
                                 ?>
                                     <div class="col-md-12 col-sm-12 col-xs-12 commentheadclient">
                                 <?
                              }
                            ?>
                                    <?php echo $comments[$i]['comment_date']." - ".$commenter; ?>
                                  </div>
                                   <?php
                                    if(count($attachment))
                                    {
                                       ?>
                                         <div class="col-md-12 col-sm-12 col-xs-12 attachbox">


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
                                  <div class="col-md-12 col-sm-12 col-xs-12 commentbox">
                                    <?php echo $comments[$i]['comment']; ?>
                                  </div>
                            </div>
                        <?
                      }
                    ?>
             </div>
              <div class="col-md-12 col-sm-12 col-xs-12" style="margin-top:10px">
              <hr class="midline">
              </div>
             <div class="col-md-12 col-sm-12 col-xs-12 reply" style="margin-top: 15px;">
                 POST YOUR REPLY -
             </div>
              <div class="col-md-12 col-sm-12 col-xs-12 reply" style="margin-top: 15px;">
              <input type="radio" name="postoption" class="postoption" value="Post Reply" checked/> Post Reply&nbsp;&nbsp;
              <input type="radio" name="postoption" class="postoption" value="Transfer Ticket" /> Transfer Ticket&nbsp;&nbsp;
              <input type="radio" name="postoption" class="postoption" value="Reassign Ticket" /> Reassign Ticket
             </div>
              <div class="col-md-12 col-sm-12 col-xs-12 transfertodept" style="margin-top: 15px;">
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
                <div class="col-md-12 col-sm-12 col-xs-12 reassigntkt" style="margin-top: 15px;">
                <?php
                  $staff=selectQuery(SUPPORTSTAFF,"*","department=".$ticketdetails[0]['dept']);
                ?>
                  Assign To -
                  <select name="assignto" class="assignto">
                    <option value=""> - Reassign - </option>
                     <?php
                       for($i=0;$i<count($staff);$i++)
                       {
                           if($_SESSION['staff']!=$staff[$i]['emp_id'])
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

             <div class="col-md-12 col-sm-12 col-xs-12" style="margin-top: 15px;">
                    <input type="hidden" name="staff"  id="staff" value="<?php echo  $adminid[0]['emp_id']; ?>"/>
                     <input type="hidden" name="requestid"  id="requestid" value="<?php echo $req; ?>"/>
                     <input type="hidden" name="allowedimgformat"  id="allowedimgformat" value="<?php echo $allowedimgtypes; ?>"/>
                       <input type="hidden" name="allowedappformat"  id="allowedappformat" value="<?php echo $allowedapptypes; ?>"/>
                       <input type="hidden" name="allowedmaxsize"  id="allowedmaxsize" value="<?php echo $attachmentmax; ?>"/>
                     <textarea class="summernote" id="editor1"  name="text_details" style="width:100%;min-height:200px !important"></textarea>
             </div>
              <div class="col-md-12 col-sm-12 col-xs-12" style="margin-top: 10px;">
                <span>Attach File (Allowed Type:<?php echo $allowed; ?> || Max File Size : <?php echo ($attachmentmax/1024)/1024; ?> MB)</span>
              <input type="file" name="attach" id="attach"/>
             </div>
             <?php
               if($ticketdetails[0]['status']=="Closed")
               {
                      ?>
                        <div class="col-md-12 col-sm-12 col-xs-12" style="display:none">
                         <input type="checkbox" name="closetkt" class="closetkt"/> Close On Reply
                     </div>
                      <?
               }
               else
               {
                   ?>
                     <div class="col-md-12 col-sm-12 col-xs-12" >
                         <input type="checkbox" name="closetkt" class="closetkt"/> Close On Reply
                     </div>
                   <?
               }
             ?>

             <div class="col-md-2 col-sm-2 col-xs-2" style="margin-top:10px">
                 <button type="button"  class="btn btn-primary postreply" name="postreply">POST REPLY</button>
             </div>
               <div class="col-md-10 col-sm-10 col-xs-10 loader" style="margin-top:10px">
               </div>
            <div class="col-md-12 col-sm-12 col-xs-12 msgs text-center" style="margin-top:10px">
            </div>
     </div>

  </div>
           </div>

</div>

</div>
</div>
<?php include('../footer.php');?>
  </div>

  </div>
        <script type="text/javascript" src="<?php echo SITEURL; ?>/jquery-1.11.1.min.js"></script>
     <script src="<?php echo SITEURL; ?>/js/jquery.js" type="text/javascript"></script>
    <script src="<?php echo SITEURL; ?>/js/scriptadminmenu.js"></script>
 <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.js"></script>
 <script src="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script>
      <link rel="stylesheet" href="../summernote/summernote.css">
      <script type="text/javascript" src="../summernote/summernote.js"></script>
       <script type="text/javascript" src="<?php echo SITEURL; ?>/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
        <script type="text/javascript">
        function goBack() {
                window.history.back();
            }
            $(function () {
                var currentdate = new Date();
                 var datetime =   currentdate.getFullYear()+ "/"
                      +(currentdate.getMonth()+1) + "/"
                      +currentdate.getDate();
                $('#datetimepicker1').datetimepicker({
                    icons: {
                    time: "fa fa-clock-o",
                    date: "fa fa-calendar",
                    up: "fa fa-arrow-up",
                    down: "fa fa-arrow-down"
                },
                   daysOfWeekDisabled: [0],
                   startDate:currentdate,
                   showTodayButton:true
                });
            });

         $(function() {
          $('.summernote').summernote({
          //fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New', 'Merriweather'],
            height: 200
         });
      });
       $('.summernote').val("");
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
                 $(".loader").html("Please enter comment to submit your post").css({"color":"red","text-align":"center"}).fadeout(5000);
            }
          else if(attachmentname!=""&&(attachmentsize>allowedmaxsize))
                    {
                         $(".loader").fadeIn().html("Attachment Size is not in limit").css({"color":"red","text-align":"center"}).delay(5000).fadeOut();
                    }

                    else if($('#attach').val()!=""&&allowarr.indexOf(attachmenttype)==-1)
                    {

                         $(".loader").fadeIn().html("Invalid Attachment").css({"color":"red","text-align":"center"}).delay(5000).fadeOut();
                    }
            else
            {
                 var load= '<?php echo LOADER; ?>';
                var loader=' <img src="'+load+'" alt="" />  ';
                  $(".loader").fadeIn().html(loader);
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
               /* var info={staff:staff,requestid:requestid,comment:textareaValue,closed:closed,dept:dept,posttype:sel,assignto:assignto,chngdept:chngdept};  */
                $.ajax({
                    type:"POST",
                    url:"savecomments.php",
                    data:form_data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success:function(response){
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
                          else
                          {
                               $(".loader").html('Error while sent request');
                          }

                    }
              });
            }
          });
       });

     $("#datetimepicker1").val("");
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

               $("#chngdue").click(function(){
                  $(".outerduebox").toggle();
               })
               $("#canceldue").click(function(){
                  $(".outerduebox").hide();
               });
               $("#setdue").click(function(){
                 var newdue= $("#datetimepicker1").val();
                 var requestid=$("#requestid").val();

                if(newdue=="")
                {

                     $(".duemsg").html('Please set due date').fadeout(2000);
                }
                else
                {
                    var info={requestid:requestid,newdue:newdue};
                     $.ajax({
                        type:"POST",
                        url:"changeduedate.php",
                        data:info,
                        success:function(response){
                           if(response)
                              {
                                  if(response==1)
                                  {
                                      location.reload();
                                  }
                                  else
                                  {
                                      $(".duemsg").html('Error while insert.. try again later');
                                  }
                              }
                              else
                              {
                                   $(".duemsg").html('Error while sent request');
                              }

                        }
                  });
                }
               })

              $("#menu26").addClass('active') ;
              $("#menu26").children("ul").show();
             /*  $("#menu26submenu2").css("color","red");   */
              </script>

  </body>
</html>