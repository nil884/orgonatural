<!doctype html>

<?php
     include("../../includes/configuration.php");
     $reqid=base64_decode($_REQUEST['reqid']);
     $getrequest=selectQuery(CONTACT." as c LEFT JOIN ".SUPPORTDEPT." as d on c.dept=d.dept_id","*","c.contact_request_id=".$reqid);

?>

<html lang=''>

<head>

 <title><?php echo SITE_TITLE; ?> : Support Requests - <?php echo $getrequest[0]['contact_request_id']; ?></title>

   <meta charset='utf-8'>

   <meta http-equiv="X-UA-Compatible" content="IE=edge">

   <meta name="viewport" content="width=device-width, initial-scale=1">
   <meta name="keywords" content="<?php echo METAKEYWORDS; ?>"  >
<meta name="description" content ="<?php echo METADESCRIPTION; ?>">
  
   <link rel="stylesheet" href="../../css/bootstrap-datetimepicker.css" />
   <link rel="stylesheet" href="../../css/bootstrap-table.css" />
    <link rel="stylesheet" href="../../css/couponcustom.css" />
      <link rel="stylesheet" href="../../css/jquery.dataTables.min.css">
       <link rel="stylesheet" type="text/css" href="../../css/buttons.dataTables.min.css" />
     <link rel="stylesheet" type="text/css" href="../../css/responsive.dataTables.min.css" />
    <link rel="stylesheet" href="../../css/bootstrap-toggle.min.css" />
     <link rel="shortcut icon" type="image/x-icon" href="<?php echo SITEURL; ?>/img/projectimage/favicon1.png">

    <style>
        .td1
        {
          width: 10% !important;
        }
        .td2
        {
           width: 6% !important;
        }
         .msgs
         {
           color: white;
          margin-top: 10px;
          padding: 10px;
          border-radius: 15px;
          text-align:center;
         }
    </style>

</head>

<body>

<div class="col-md-2 p0">

<?php include('../cssmenu.php') ?>

</div>

<div class="col-md-10 p0">

     <?php include('../header.php') ?>

        <div id="main">

        <div class="col-md-11 titlemsg mlr5" >

            <div class="headmsg">

                <div class="col-md-10"> Support Request - <?php echo $getrequest[0]['contact_request_id']; ?></div>

                <div class="col-md-2 text-right"></div>

            </div>
               <div class="col-md-12 col-sm-12 col-xs-12 text-right" style="margin-top: 10px;">
                <button class="btn btn-primary"  onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"> Back</i></button>
               </div>
            <div class="col-md-6 col-md-offset-3 msgs"></div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                  <b><h4>Request Details</h4> </b>
            </div>
             <div class="col-md-2 col-sm-6 col-xs-6">
                  <b> Request By - </b>
             </div>
             <div class="col-md-4 col-sm-6 col-xs-6">
                  <?php echo $getrequest[0]['Name']; ?>
             </div>
              <div class="col-md-2 col-sm-6 col-xs-6">
                  <b>Email -</b>
             </div>
             <div class="col-md-4 col-sm-6 col-xs-6">
                  <?php echo $getrequest[0]['Email']; ?>
             </div>
              <div class="col-md-2 col-sm-6 col-xs-6">
                  <b>Contact No -</b>
             </div>
             <div class="col-md-4 col-sm-6 col-xs-6">
                  <?php echo $getrequest[0]['Telephone']; ?>
             </div>
              <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="col-md-2 col-sm-2 col-xs-6 p0">
                  <b>Comment -</b>
             </div>
             <div class="col-md-10 col-sm-10 col-xs-6 p0">
                  <?php echo $getrequest[0]['Comment']; ?>
             </div>
             </div>
              <div class="col-md-12 col-sm-12 col-xs-12">
                   <hr>
              </div>
        </div>

    </div>

</div>
      <?php
  include('../footer.php');
?>
     <script src="../../js/jquery.js" type="text/javascript"></script>
   <script src="../../js/scriptadminmenu.js"></script>
      <script type="text/javascript" src="../../js/jquery-1.8.3.min.js" charset="UTF-8"></script>
          <script type="text/javascript" src="../../js/bootstrap.min.js"></script>

          <script type="text/javascript" src="../../js/bootstrap-table.js"></script>
            <script type="text/javascript" src="../../js/restrict.js"></script>
          <script src="../../js/bootstrap-switch.js"></script>
          <script src="../../js/bootstrap-toggle.min.js"></script>
          <script type="text/javascript" src="../../js/jquery.dataTables.min.js"></script>
             <script type="text/javascript" src="../../js/dataTables.responsive.min.js"></script>
              <script type="text/javascript" src="../../js/dataTables.buttons.min.js"></script>
              <script type="text/javascript" src="../../js/buttons.html5.min.js"></script>
               <script type="text/javascript" src="../../js/buttons.flash.min.js"></script>
               <script type="text/javascript" src="../../js/data_table_pdf.js"></script>
               <script type="text/javascript" src="../../js/data_table_jszip.js"></script>
             <script type="text/javascript" src="../../js/buttons.print.min.js"></script>
              <script type="text/javascript" src="../../js/data_table_vfsfont.js"></script>
               <script type="text/javascript" src="../../js/buttons.colVis.min.js"></script>
               <script>
               $(document).ready(function(){
                          $('.example').DataTable( {
                 "lengthMenu": [ 10, 25, 50, 75, 100 ],

            } );


             $(".tg").on("change",function() {
                   var uid=$(this).attr('data-id');
                   var getid=$(this).attr('id');
                     var c=$("#"+getid+":checked").val();
                   if(c=="on")
                   {     var info={uid:uid,status:"1"};
                          $.ajax({
                                type:"POST",
                                url:"statuschng.php",
                                data:info,
                                success:function(response){
                                    if(response==1)
                                    {     $('.msgs').fadeIn();
                                          $('.msgs').css("background","green");
                                          $('.msgs').html("Subadmin Activated Successfully");
                                          $('.msgs').delay(5000).fadeOut();
                                    }
                                    else
                                    {    $('.msgs').fadeIn();
                                         $('.msgs').css("background","red");
                                          $('.msgs').html("ERROR..");
                                          $('.msgs').delay(5000).fadeOut();
                                    }
                                }
                          });
                   }
              	  else
                  {
                        var info={uid:uid,status:"0"};
                          $.ajax({
                                type:"POST",
                                url:"statuschng.php",
                                data:info,
                                success:function(response){
                                    if(response==1)
                                    {     $('.msgs').fadeIn();
                                          $('.msgs').css("background","green");
                                          $('.msgs').html("Subadmin Deactivated Successfully");
                                          $('.msgs').delay(5000).fadeOut();
                                    }
                                    else
                                    {     $('.msgs').fadeIn();
                                         $('.msgs').css("background","red");
                                          $('.msgs').html("ERROR..");
                                          $('.msgs').delay(5000).fadeOut();
                                    }
                                }
                          });
                  }
      	    });

               })


                   function del(id)
            {      var uid=id;
                       if(id!="")
                       {
                         if(confirm("Are You Sure To Delete This SUBADMIN? "))
                         {
                               var info={uid:uid};
                                  $.ajax({
                                        type:"POST",
                                        url:"del.php",
                                        data:info,
                                        success:function(response){
                                           if(response==1)
                                              {
                                                $('.msgs').fadeIn();
                                                  $('.msgs').css("background","green");
                                                  $('.msgs').html("Subadmin Deleted Successfully");
                                                  $('.msgs').delay(5000).fadeOut();
                                                location.reload();
                                              }
                                              else
                                              {
                                               $('.msgs').fadeIn();
                                               $('.msgs').css("background","red");
                                                $('.msgs').html("ERROR..");
                                                $('.msgs').delay(5000).fadeOut();
                                              }
                                        }
                                  });

                         }
                         else
                         {

                         }
                       }
            }
               </script>
               <script>
              $("#menu26").addClass('active') ;
              $("#menu26").children("ul").show();
             /*  $("#menu26submenu2").css("color","red");   */
              </script>
</body>

</html>

