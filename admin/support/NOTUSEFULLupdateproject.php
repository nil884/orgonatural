<!doctype html>
<?php
    include("../../includes/configuration.php");
    $empid = base64_decode($_REQUEST['empid']);

    $getclient1 = selectQuery(SUPPORTSTAFF,"*","emp_id='".$empid."' ");

      $getclient=selectQuery(SUPPORTCLIENT,"*","isActive='1'");
      $getproject=selectQuery(PROJECTLIST,"*","status='1' order by proj_name ASC");

?>
<html lang='en'>
<head>
    <title><?php echo SITE_TITLE; ?> : Add Project</title>
    <meta charset='utf-8'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="<?php echo METAKEYWORDS; ?>"  >
    <meta name="description" content ="<?php echo METADESCRIPTION; ?>">
    <?php include('../commoncss.php') ?>
    <link rel="stylesheet" href="../../css/bootstrap-table.css" />
    <link rel="stylesheet" href="../../css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="../../css/buttons.dataTables.min.css" />
    <link rel="stylesheet" type="text/css" href="../../css/responsive.dataTables.min.css" />
    </head>
<body>
<div class="dashcontainer">
      <?php include('../header.php') ?>

    <?php include('../sidebar.php') ?>

    <div class="right_col">
            <div class="dashbody">
            <div class="card">
            <div class="panel-body">
            <div class="panel-heading">
                <div class="panel-title pull-left">Update Project</div>
                <div class="pull-right"><button class="btn btn-default pull-right btn-sm" onclick="goBack()"> Back</button>  </div>
            </div>
            <div class="msgs"></div>
             <form class="form-horizontal col-md-12">
             <div class="row">
                 <div class="form-group">
                    <label class="col-md-2">Client Name<span style="color:red;">*</span></label>
                    <div class="col-md-4">
                       <input type="text" name="clientname" id="clientname" onblur="firstcapital('clientname')" onkeyup="checkchar(event,'clientname')" class="form-control clientname text-capitalize" maxlength="40" value="<?php echo $getclient1[0]['emp_name'];?>" />
                       <input type="hidden" name="deptid" id="deptid" class="deptid" value="<?php echo $getclient1[0]['projects']; ?>"/>
                    </div>
                 </div>
                 <div class="form-group">
                    <label class="col-md-2">Project<span style="color:red;">*</span></label>
                    <div class="col-md-4">
                        <!--<div class="form-group">-->
                            <select class="form-control" id="project" onchange="checkproject();">
                                <option value="">Select</option>
                                <option value="ADD">Add New Project</option>
                                <?php

                                for($i=0;$i<count($getproject);$i++)
                                {
                                ?>
                                   <option value="<?php echo $getproject[$i]['proj_name']; ?>"><?php echo $getproject[$i]['proj_name']; ?></option>
                                <?}
                                ?>
                            </select>
                        <!--</div> -->

                            <div class="documentlist">
                                <input type="hidden" name="projlist" id="projlist" value="<?php echo $getclient1[0]['projects'];?>">
                                <?php
                                    $projects = $getclient1[0]['projects'];
                                    $arr = array();
                                    $arr = explode(",",$projects);
                                    for($j=0;$j<count($arr);$j++)
                                    {
                                ?>
                                    <div class="replace">
                                        <div class="form-group"><label style=""><?php echo $arr[$j];?></label>
                                        <?php if($getclient1[0]['projects']!=""){?>
                                        <button type="button" class="btn btn-default" onclick="deleteproj('<?php echo $arr[$j]; ?>','<?php echo $getclient1[0]['emp_id'];?>');"><i class="fa fa-times"></i></button>
                                        <?php }?>
                                        <span id=""></span></div>
                                    </div>
                                <?php }?>

                            </div>
                    </div>
                    <div class="col-md-2"><button type="button" class="btn btnPrimary" onclick="addproj('project')">AddToList</button></div>
                    <div id="showproj">
                        <div class="col-md-2">
                             <input type="text" name="projectname" id="projectname" class="form-control"/>
                        </div>
                         <div class="col-md-1"><button type="button" class="btn btn-Primary" onclick="checkaddproj();">AddProject</button></div>
                    </div>
                 </div>
                 <div class="form-group">
                    <label class="col-md-2">Email<span style="color:red;">*</span></label>
                    <div class="col-md-4">
                      <input type="text" name="clientemail" id="clientemail" class="form-control clientemail" autocomplete="off" value="" maxlength="70"  onblur="checkemail('clientemail');" value="<?php echo $getclient1[0]['emp_email'];?>">
                         <input type="hidden" name="isemail" class="form-control isemail" />
                    </div>
                 </div>
                 <div class="form-group">
                    <label class="col-md-2">Mobile No</label>
                    <div class="col-md-4">
                      <input type="text" name="mobile" id="mobile" onkeyup="numbercheck('mobile')" class="form-control" maxlength="10" value="<?php echo $getclient1[0]['mobile'];?>">
                    </div>
                 </div>
                 <div class="form-group">
                    <label class="col-md-2">Username<span style="color:red;">*</span></label>
                    <div class="col-md-4">
                      <input type="text" name="username" id="username"  class="form-control username" maxlength="20" value="<?php echo $getclient1[0]['username'];?>" />
                    </div>
                 </div>
                 <div class="form-group">
                    <label class="col-md-2">Set Password<span style="color:red;">*</span></label>
                    <div class="col-md-4">
                      <input type="password" name="clientpwd" class="form-control clientpwd" maxlength="20" value="" autocomplete="off" value="<?php echo $getclient1[0]['password'];?>"/>
                    </div>
                 </div>

                  <div class="form-group col-md-12">
                    <label class="col-md-2"></label>
                    <div class="col-md-4">
                      <button type="button" id="submit" class="btn btn-primary"> Update </button>
                      <button type="button" id="cancel" class="btn btn-danger" onclick="goBack()"> Cancel </button>
                    </div>
                 </div>
                 </div>
                        </form>
                    </div>
                </div>
         </div>
         <?php include('../footer.php');?>
    </div>
</div>
<script src="../../js/bootstrap-switch.js"></script>
<script type="text/javascript" src="../../js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="../../js/dataTables.responsive.min.js"></script>
<script type="text/javascript" src="../../js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="../../js/buttons.html5.min.js"></script>
<script src="../../js/bootstrap-toggle.min.js"></script>

<script>
    $(".clientemail").val("");
    $(".clientpwd").val("");
function goBack() {
    window.history.back();
}
function checkchar(event,id)
{
    var key = event.which || event.keyCode;
    var inp=$("#"+id).val();
    if (key >= 65 && key <= 90 ||
        // Backspace and Tab and Enter and shift
            key == 8 || key == 9 || key == 13 ||key == 16 ||
        // Space and Home and End
            key == 32 ||key == 35 || key == 36 ||
        // Del and Ins
            key == 46 || key == 45)
        {
            $("#"+id).val(inp);
        }
        else
        {
            var newstr = inp.slice(0, -1);
            $("#"+id).val(newstr);
        }
}

function deleteproj(projname,empid)
{
    var action = "delproj";
    $.ajax({
        type : "post",
        url : "addclientdata.php",
        data : {projname:projname, empid:empid, action:action},
        success : function(res){
            /*alert(res);*/
            if(res==1)
            {
               location.reload();
            }
            else
            {
                $(".replace").html(res);
            }
        }
    });
}

$("#showproj").hide();
function checkproject()
{
    var project = $("#project option:selected").val();
    if(project=="")
    {
        $('.msgs').fadeIn();
        $('.msgs').addClass("alert alert-danger");
        $('.msgs').html("Please Select Project Name");
        $('.msgs').delay(5000).fadeOut();
    }
    if(project=="ADD")
    {
        $("#showproj").show();
    }
}
function checkaddproj()
{
    var projectname = $("#projectname").val();
   /* var client_id = $("#clientid").val(); */
    if(projectname=="")
    {
        $('.msgs').fadeIn();
        $('.msgs').addClass("alert alert-danger");
        $('.msgs').html("Please Enter Project Name");
        $('.msgs').delay(5000).fadeOut();
    }
    else
    {

        $("#projbtn").html("Adding...");
        var infodata={projectname:projectname,action:"addproject"};
        $.ajax({
                type:"POST",
                url:"addclientdata.php",
                data:infodata,
                success:function(response){

                    if(response==2)
                    {
                        $('.msgs').fadeIn();
                        $('.msgs').addClass("alert alert-success");
                        $('.msgs').html("Project Added Successfully!!!");
                        $('.msgs').delay(5000).fadeOut();
                        location.reload();
                    }
                    else
                    {
                        $('.msgs').fadeIn();
                        $('.msgs').addClass("alert alert-success");
                        $('.msgs').html("Project Not Added");
                        $('.msgs').delay(5000).fadeOut();

                    }
                }
            });
    }
}

function addproj(id)
{
    var projoption=$("#"+id+" option:selected").val();
    var projlist=$("#projlist").val();
    if(projlist!="")
    {
        var projarr=projlist.split(",");
    }
    else
    {
       var projarr=[];
    }

     if(projoption!="")
     {
        classname = projoption.replace(/\s/g, '');
        var removefunction="removeproj('"+classname+"div','"+projoption+"');";

        /*var str=' <div class="form-group '+classname+'div"><div class="form-inline"><div class="form-group" style="width:100%;"><label style="">'+projoption+'</label> <button type="button" class="btn btn-default" style="" onclick="'+removefunction+'"><i class="fa fa-times"></i></button><span id="'+classname+'error"></span></div></div></div>';*/
        var str=' <div class="form-group '+classname+'div"><label style="">'+projoption+'</label> <button type="button" class="btn btn-default" style="" onclick="'+removefunction+'"><i class="fa fa-times"></i></button><span id="'+classname+'error"></span></div>';
        $(".documentlist").append(str);
        /*$("#"+id+" option:contains('"+projoption+"')").attr("disabled","disabled");*/
        $("#"+id+" option:contains('"+projoption+"')").remove();
        projarr.push(projoption);
        projstr=projarr.toString();

        $("#projlist").val(projstr);
     }
}
function removeproj(divclass,proj_name)
{
    $(".documentlist ."+divclass).remove();
    var projlist=$("#projlist").val();
    var newarr=[];
    var projarr=projlist.split(",");
    for(i=0;i<projarr.length;i++)
    {
        if(projarr[i]==proj_name)
        {
            $("#project option[value='"+proj_name+"']").attr("disabled",false);
        }
        else
        {
            newarr.push(projarr[i]);
        }
    }
    projstr=newarr.toString();
    $("#projlist").val(projstr);
 }


function checkemail(inpid)
{
    var email = $("#"+inpid).val();
    var checktype="Emailcheck";
    var patt = new RegExp(/^[a-zA-Z0-9._\-]+@[a-zA-Z.\-]+\.[a-zA-Z]{1,4}$/);
    var res = patt.test(email);
    if(res)
    {
        $("#"+inpid).css("border","1px solid #c3c3c3");
        $("#submit").prop("disabled",false);
        var info={email:email,checktype:checktype};
        $.ajax({
            type:"POST",
            url:"checkavalability.php",
            data:info,
            success:function(response){
                if(response==1)
                {

                }
                if(response==0)
                {
                    $('.msgs').fadeIn();
                    $('.msgs').addClass("alert alert-danger");
                    $('.msgs').html("Email Already Exists");
                    $('.msgs').delay(5000).fadeOut();
                    $(".isemail").val("0");
                }
            }
        });
    }
    else
    {
        $("#"+inpid).css("border","1px solid red");
        $("#submit").prop("disabled",true);
    }
}

$(document).ready(function() {

    $("#submit").on("click",function() {
        var clientname =$(".clientname").val();
        var projlist =$("#projlist").val();
        var clientemail= $(".clientemail").val();
        var mobile= $("#mobile").val();
        var username=$(".username").val();
        var clientpwd= $(".clientpwd").val();
        var empid = <?php echo $empid;?>;

        /*alert(clientname+projlist+clientemail+mobile+username+clientpwd);*/

        /*if ((clientname == "") || (projlist == "") || (project=="ADD") || (clientemail == "") || (username=="")||  (clientpwd == ""))
        {
            $('.msgs').fadeIn();
            $('.msgs').addClass("alert alert-danger");
            $('.msgs').html("Please Fill All Required Fields");
            $('.msgs').delay(5000).fadeOut();
        }

        else
        {*/
            $("#submit").prop("disabled",true);
            var infodata={empid:empid,clientname:clientname,project:projlist,clientemail:clientemail,mobile:mobile,username:username,clientpwd:clientpwd,action:"updateproject"};
            $.ajax({
                type:"POST",
                url:"addclientdata.php",
                data:infodata,
                success:function(response){
                /*alert(response); */
                    if(response==2)
                    {
                        $('.msgs').fadeIn();
                        $('.msgs').addClass("alert alert-success");
                        $('.msgs').html("Project Updated Successfully!!!");
                        $('.msgs').delay(5000).fadeOut();
                        location.reload();
                    }
                    else
                    {
                        $('.msgs').fadeIn();
                        $('.msgs').addClass("alert alert-danger");
                        $('.msgs').html("Not Updated");
                        $('.msgs').delay(5000).fadeOut();
                    }
                }
            });
        /*}*/
    });
});

function numbercheck(id)
{
   var inp=$("#"+id).val();

   if(isNaN(inp))
   {
       var newstr = inp.slice(0, -1);
       $("#"+id).val(newstr);
   }
}
</script>
</body>
</html>