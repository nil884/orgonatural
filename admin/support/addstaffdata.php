<?php
     include("../../includes/configuration.php");

        $dept=$_REQUEST['dept'];
        $group=$_REQUEST['group'];
        $empname=$_REQUEST['empname'];
        $username=$_REQUEST['username'];
        $empemail=$_REQUEST['empemail'];
        $emppwd=$_REQUEST['emppwd'];
        $dept=$_REQUEST['dept'];
        $acctype=$_REQUEST['acctype'];
        $adminacceess=$_REQUEST['adminacceess'];

      if($empname!=""&&$empemail!=""&&$emppwd!=""&&$dept!=""&&$username!=""&&$group!="")
      {
            $getemp=selectQuery(SUPPORTSTAFF,"*","isDel='0' and emp_email='".$empemail."'");
            if(count($getemp))
            {
                echo "2";
            }
            else
            {
                $data=array(
                 "emp_name"=>$empname,
                 "emp_email"=>$empemail,
                 "username"=>$username,
                 'emp_pwd'=>md5($emppwd),
                 'emp_group'=>$group,
                 'department'=> $dept,
                 'acc_type'=>$acctype,
                 'admin_panel_access'=>$adminacceess,
                 'added_on'=>date('d/m/Y H:i:s'),
                 "isActive"=>'1'
               );

             $pdate=insertQuery(SUPPORTSTAFF,$data);
             if($pdate)
             {
                if($adminacceess=="1")
                {
                    $getadmin=selectQuery(USER,"*","u_id=1");
                    $accesstomenu=$getadmin[0]['allocatemenu'];
                    $adminadata=array(
                     "utype"=>"Admin",
                     "u_name"=>"Admin",
                     "u_mob"=>"",
                     "u_email"=>$empemail,
                     "username"=>$username,
                     "password"=>md5($emppwd),
                     "secrete_pin"=>"0",
                     "allocatemenu"=>$accesstomenu,
                     "isActive"=>"1"
                    );
                    $getadmin=insertQuery(USER,$adminadata);
                }

                 $headers1  = 'MIME-Version: 1.0' . "\r\n";
                $headers1 .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                $headers1 .= "From:".SITENAME."<".EMAIL_SENDER.">";
                $subject="Account Created";

                $body='<hr style="margin-bottom:50px"> <div style="width: 70%;margin:0 auto">

                <div style="margin: 0 auto;text-align: center"><img src="'.SITEURL.'/img/projectimage/logo.png" alt="" /></div>

                <p>Dear '.$empname.',<br><br>Your account created by system admin  � <br>
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
                      <td>'.$emppwd.'</td>
                    </tr>
                </table>

                  <br><hr style="border-style:groove;color:#CECECE"><br>
                 <a href="'.SITEURL.'/support">Click Here to Login</a><br>

               </p>
                </div>';

                $sentmail = mail($empemail, $subject, $body, $headers1);
                echo "1";
             }
             else
             {
                 echo "0";
             }
            }
      }
      else
      {
          echo "0";
      }

?>