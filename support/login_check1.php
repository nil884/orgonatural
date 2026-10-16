<?php
 include("../includes/configuration.php");

    if (!empty($_SERVER["HTTP_CLIENT_IP"]))
    {
        $ip = $_SERVER["HTTP_CLIENT_IP"];
    }
    elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
    {
        // Check for the Proxy User
        $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    }
    else
    {
        $ip = $_SERVER["REMOTE_ADDR"];
    }
        $agent = $_SERVER['HTTP_USER_AGENT'];
        $browser = 'NA';
        if((preg_match('/MSIE/i',$agent)||preg_match('/Trident/i',$agent)||(preg_match('/Trident/i',$agent)&&stristr($_SERVER['HTTP_USER_AGENT'],'Windows Phone'))) && !preg_match('/Opera/i',$agent))
        {
            $browser = 'Internet Explorer';
            $ub = "MSIE";
        }
        elseif(preg_match('/Windows NT 10/i',$agent) && preg_match('/Edge/i',$agent)){
            $browser = 'Microsoft Edge';
            $ub = "Edge";
        }
        elseif(preg_match('/Firefox/i',$agent))
        {
            $browser = 'Mozilla Firefox';
            $ub = "Firefox";
        }
        elseif(preg_match('/Chrome/i',$agent))
        {
            $browser = 'Google Chrome';
            $ub = "Chrome";
        }
        elseif(preg_match('/Safari/i',$agent))
        {
            $browser = 'Apple Safari';
            $ub = "Safari";
        }
        elseif(preg_match('/Opera/i',$agent))
        {
            $browser = 'Opera';
            $ub = "Opera";
        }
        elseif(preg_match('/Netscape/i',$agent))
        {
            $browser = 'Netscape';
            $ub = "Netscape";
        }

        $device = '';

		if( stristr($_SERVER['HTTP_USER_AGENT'],'ipad') ) {
			$device = "ipad";
		}  else if((stristr($_SERVER['HTTP_USER_AGENT'],'iphone') || strstr($_SERVER['HTTP_USER_AGENT'],'iphone'))&&stristr($_SERVER['HTTP_USER_AGENT'],'Windows Phone')=== FALSE) {
			$device = "iphone";
		}
        else if( stristr($_SERVER['HTTP_USER_AGENT'],'Windows Phone') ) {
          	$device = "Windows Phone";
        }
        else if( stristr($_SERVER['HTTP_USER_AGENT'],'blackberry') ) {
			$device = "blackberry";
		} else if( stristr($_SERVER['HTTP_USER_AGENT'],'android') ) {
			$device = "android";
		}
        else if( stristr($_SERVER['HTTP_USER_AGENT'],'Windows NT 10.0') ) {
          	$device = "Windows 10";
        }
         else if( stristr($_SERVER['HTTP_USER_AGENT'],'Windows NT 6.1') ) {
          	$device = "Windows 7";
        }

        $uname=$_POST['username'];
        $pwd=$_POST['logkey'];

        if($uname=="" || $pwd=="")
        {
            echo 1;
        }
        else
        {

        $getuser1=selectQuery(SUPPORTSTAFF,"*","username='".$uname."' and emp_pwd='".md5($pwd)."' and isActive='1'");

        if(count($getuser1)==1)
        {
                $data=array(
                 "last_login"=>date("d-m-Y H:i:s")
                );
                 $updateuser=updateQuery(SUPPORTSTAFF,$data,"emp_id=".$getuser1[0]['emp_id']);

                $_SESSION['staff']=$getuser1[0]['emp_id'];
                $_SESSION['dept']=$getuser1[0]['department'];
                $_SESSION['staffname']=$getuser1[0]['emp_name'];
                $_SESSION['acc_type']=$getuser1[0]['acc_type'];
                 $_SESSION['uname']=$getuser1[0]['username'];
               /* if($getuser1[0]['acc_type']=="Admin")
                {*/
                     $getadmin=selectQuery(USER,"*","username='".$uname."' and password='".md5($pwd)."'");
                     if(count($getadmin))
                     {
                            $_SESSION['user']=$getadmin[0]['u_id'];
                            $_SESSION['adminsess']=$getadmin[0]['u_id'];
                            $_SESSION['adminuname']=$getadmin[0]['username'];
                     }

                    $_SESSION['last_login_timestamp'] = time();
              /*  }    */
            /*    $_SESSION['last_login_timestamp'] = time();       */
                $s=base64_encode($getuser1[0]['department']);


                    $datalog=array(
                      'username'=>$uname,
                      'password'=>$pwd,
                      'ip_address'=>$ip,
                      'browser_name'=>$browser,
                      'device_type'=>$device,
                      'details'=>$agent,
                      'login_time'=>date('d/m/Y H:i:s'),
                      'login_Attempt'=>'success'
                    );
                    $sinsert=insertQuery(STAFFLOG,$datalog);

                     echo SITEURL."/support/home.php?s=".$s;

        }
        else
        {
            echo 0;
        }

      }

?>