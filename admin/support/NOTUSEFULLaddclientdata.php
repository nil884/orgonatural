<?php
     include("../../includes/configuration.php");

        $empid =  $_REQUEST['empid'];
        $action = $_REQUEST['action'];
        $clientname=$_REQUEST['clientname'];
        $project=$_REQUEST['project'];
        $clientemail=$_REQUEST['clientemail'];
        $mobile=$_REQUEST['mobile'];
        $username=$_REQUEST['username'];
        $clientpwd1=$_REQUEST['clientpwd'];
        $clientpwd=md5($clientpwd1);
        $acctype = "Client";
        $client_id=$_REQUEST['client_id'];
        $projectname=$_REQUEST['projectname'];

        $projname=$_REQUEST['projname']; // Project name to delete from list


//DELETE CLIENT
if($action=="del_client")
{
    $clientid=$_REQUEST['clientid'];
    alert($clientid);
     /*$data=array(
         "isDel"=>"1",
         "isActive"=>"0"
         );
      $del = updateQuery(SUPPORTSTAFF,$data,"emp_id=".$clientid);
      if($del)
      {
          echo 1;
      }
      else
      {
          echo 0;
      }*/
}


        if($action=="addclient")
        {

            $data=array(
             "emp_name"=>$clientname,
             "projects"=>$project,
             "emp_email"=>$clientemail,
             "mobile"=>$mobile,
             'username'=>$username,
             'password'=>$clientpwd,
             'emp_group'=>CLIENTGROUPID,
             'department'=>CLIENTDEPTID,
             "acc_type"=>$acctype,
             'added_on'=>date('d/m/Y H:i:s'),
             "isActive"=>'1'
            );

            $addclient = insertQuery(SUPPORTSTAFF,$data);
            if($addclient)
            {
               /* $headers1  = 'MIME-Version: 1.0' . "\r\n";
                $headers1 .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                $headers1 .= "From:".SITENAME."<".EMAIL_SENDER.">";
                $subject="Account Created";

                $body='<hr style="margin-bottom:50px"> <div style="width: 70%;margin:0 auto">

                <div style="margin: 0 auto;text-align: center"><img src="'.SITEURL.'/img/projectimage/logo.png" alt="" /></div>

                <p>Dear '.$clientname.',<br><br>Your account created by system admin  – <br>
                    You can access panel using below login credentials
                      <hr  style="border-style:groove;margin-bottom: 8px;color:#CECECE">

                   <table>

                    <tr>
                      <td>Username</td>
                      <td>:</td>
                      <td>'.$username.'</td>
                    </tr>
                     <tr>
                      <td>Password</td>
                      <td>:</td>
                      <td>'.$clientpwd1.'</td>
                    </tr>
                </table>

                  <br><hr style="border-style:groove;color:#CECECE"><br>
                 <a href="'.SITEURL.'/support">Click Here to Login</a><br>

               </p>
                </div>';

                 $sentmail = mail($clientemail, $subject, $body, $headers1); */
             echo 1;
        }
        else
        {
            echo 0;
        }

    }

//UPDATE PROJECT
    if($action=="updateproject")
    {
        $data=array(
            "emp_name"=>$clientname,
            "projects"=>$project,
            "emp_email"=>$clientemail,
            "mobile"=>$mobile,
            'username'=>$username,
            'password'=>$clientpwd,
        );

            $update = updateQuery(SUPPORTSTAFF,$data,"emp_id='".$empid."' ");
            if($update)
            {
                echo "2"; //Updated
            }
            else
            {
                echo "3"; //Not Updated
            }
    }







//ADD PROJECT
    if($action=="addproject")
    {
        $data1=array(
            "proj_name"=>$projectname,
            "status"=>"1"
           /* "client_id"=>$client_id*/
        );

        $addproject = insertQuery(PROJECTLIST,$data1);
        if($addproject)
        {
            echo "2";  //Success
        }
        else
        {
            echo "3";
        }
    }

// DELETE ONE PRJECT FROM LIST
    if($action=="delproj")
    {

        $getproj = selectQuery(SUPPORTSTAFF,"*","emp_id='".$empid."' ");
        $projects = $getproj[0]['projects'];
        $newarr = array();
        $arr = explode(",",$projects);

        for($i=0;$i<count($arr);$i++)
        {
            if($arr[$i]==$projname)
            {

            }
            else
            {
                array_push($newarr,$arr[$i]);
            }
        }
        $newstr=implode(",",$newarr);

        $data = array(
            "projects"=>$newstr,
        );

        $del = updateQuery(SUPPORTSTAFF,$data,"emp_id='".$empid."' ");
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