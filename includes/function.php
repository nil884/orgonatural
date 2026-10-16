<?php
function sendsms($to,$message,$data1,$data2,$templateId=null){
    $getconfigdetails=selectQuery(CONFIG,"*","id= 1");
    $m=$message;
    $message = @urlencode($m);
    $mob=$to;
    $key=$data1;
    $sender=$data2;
    $format="PHP";
    $template=($templateId!=""?"&template_id=".$templateId:"");
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "".$getconfigdetails[0]['sms_url']."/v3/?method=sms&api_key=$key&to=$mob&sender=$sender&message=$message&format=$format".$template);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

function getwebfont(){
    $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/webfonts/v1/webfonts?key=AIzaSyCLtGU1Qm6-Q1nSn9yN3mTZITUmbSqcWbg");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
    return json_decode($output,true);
}

function password_encrypt($pwd){ return md5($pwd);}

function convertemailstr($replacearray,$str){
    $convertedstr=preg_replace_callback('~\%\$(.*?)\%~si',
       function($match) use ($replacearray){
              return str_replace($match[0], isset($replacearray[$match[1]]) ? $replacearray[$match[1]] : $match[0], $match[0]);
          },$str);
    return $convertedstr;
}


function convertsmsstr($replacearray,$str){
    $convertedstr=preg_replace_callback('~\%\$(.*?)\%~si',
       function($match) use ($replacearray){
              return str_replace($match[0], isset($replacearray[$match[1]]) ? $replacearray[$match[1]] : $match[0], $match[0]);
          },$str);
    return $convertedstr;
}

function mail_multiattachment($filename1,$filename2, $filename3,$filename4,$filename5,$filename6, $mailto, $from_mail, $from_name, $replyto, $subject, $message) {
$body = '';
$headers = '';
$arr=array();
 if($filename1!=""){array_push($arr,$filename1);}
if($filename2!=""){array_push($arr,$filename2);}
if($filename3!=""){array_push($arr,$filename3);}
if($filename4!=""){ array_push($arr,$filename4);}
if($filename5!=""){array_push($arr,$filename5);}
if($filename6!=""){array_push($arr,$filename6);}
$separator = md5(time());
 /* print_r($arr);
  exit();*/

$headers = "From: ".$from_name."<".$from_mail.">" . PHP_EOL;
$headers .= "Reply-To: ".$replyto . PHP_EOL;
$headers .= "MIME-Version: 1.0".PHP_EOL;
$headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . PHP_EOL . PHP_EOL;
$body .= "Content-Transfer-Encoding: 7bit" . PHP_EOL;
$body .= "This is a MIME encoded message." . PHP_EOL;
$body .= "--" . $separator . PHP_EOL;
$body .= "Content-Type: text/html; charset=\"iso-8859-1\"" . PHP_EOL;
$body .= "Content-Transfer-Encoding: 8bit" . PHP_EOL . PHP_EOL;
$body .= $message . PHP_EOL;
 $body .= "--" . $separator . PHP_EOL;
 for($i=0;$i<sizeOf($arr);$i++){
     $file = $arr[$i];
     $encoded_content = chunk_split(base64_encode(file_get_contents($file)));
    $body .= "--" . $separator . PHP_EOL;
   	$body .="Content-Type: $file_type; name=\"$file\"\r\n";
    $body .="Content-Disposition: attachment; filename=\"$file\"\r\n";
    $body .="Content-Transfer-Encoding: base64\r\n";
   	$body .="X-Attachment-Id: ".rand(1000,99999)."\r\n\r\n";
	$body .= $encoded_content;
 }

if (mail($mailto, $subject, $body, $headers)){}
}

 function getleadno($no){
     if($no==0){  $leadno="000001";}
     else{ $leadno=$no+1;}
     if(strlen($leadno)==1){ $newlead="00000".$leadno;}
      if(strlen($leadno)==2){ $newlead="0000".$leadno; }
      if(strlen($leadno)==3){ $newlead="000".$leadno; }
     if(strlen($leadno)==4){$newlead="00".$leadno;}
     if(strlen($leadno)==5){$newlead="0".$leadno;}
     if(strlen($leadno)==6){  $newlead=$leadno;}
     return $newlead;
}

///Not in use
function encodenumber($num){
 // $randnumber =str_pad(rand(0,999), 3, "0", STR_PAD_LEFT);
 $randnumber= substr($num*999,0,3);
   return $randnumber.$num;
}
function decodenumber($num){
  $randnumber = substr($num,3);
   return $randnumber;
}
//not in use

function replace_nonletter($text){
  // replace non letter or digits by -
  $text = preg_replace('~[^\pL\d]+~u', '_', $text);
  // transliterate
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
  // remove unwanted characters
  $text = preg_replace('~[^-\w]+~', '', $text);
  // trim
  $text = trim($text, '_');
  // remove duplicate -
  $text = preg_replace('~-+~', '_', $text);
  // lowercase
  $text = strtolower($text);
  return $text;
 }

 function createurltitle($text){
  // replace non letter or digits by -
  $text = preg_replace('~[^\pL\d]+~u', '-', $text);
  // transliterate
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
  // remove unwanted characters
  $text = preg_replace('~[^-\w]+~', '', $text);
  // trim
  $text = trim($text, '-');
  // remove duplicate -
  $text = preg_replace('~-+~', '-', $text);
  // lowercase
  $text = strtolower($text);
  return $text;
 }

 function cssparse($file){
    $css = file_get_contents($file);
    preg_match_all( '/(?ims)([a-z0-9\s\,\>\.\:#_\-@]+)\{([^\}]*)\}/', $css, $arr);
    $result = array();
    foreach ($arr[0] as $i => $x){
        $selector = trim($arr[1][$i]);
        $rules = explode(';', trim($arr[2][$i]));
        $rules_arr = array();
        foreach ($rules as $strRule){
            if (!empty($strRule)){
                $rule = explode(":", $strRule);
                $rules_arr[trim($rule[0])] = trim($rule[1]);
            }
        }

        $selectors = explode('<br>', trim($selector));
        foreach ($selectors as $strSel){
            $result[$strSel] = $rules_arr;
        }
    }
    return $result;
}
function getimgconfig($imgtype){
     $getimageconfigs=selectQuery(IMGCONFIG,"*","img_type='".$imgtype."'");
    $configs=json_encode($getimageconfigs,true);
    return $configs;
}

function getimgconfigpaths($imgtype){
     $getimageconfigs=selectQuery(IMGCONFIG,"imgs_location,thumb1_path,thumb2_path,thumb3_path,thumb4_path,thumb5_path,max_image_size,crop_width,crop_height,default_image_width,default_image_height, 	crop_enabled  ,max_image_count","img_type='".$imgtype."'");
    $configs=$getimageconfigs;
    return $configs;
}

function getRelativePath($from, $to){
// some compatibility fixes for Windows paths
$from = is_dir($from) ? rtrim($from, '\/') . '/' : $from;
$to   = is_dir($to)   ? rtrim($to, '\/') . '/'   : $to;
$from = str_replace('\\', '/', $from);
$to   = str_replace('\\', '/', $to);

$from     = explode('/', $from);
$to       = explode('/', $to);
$relPath  = $to;

foreach($from as $depth => $dir) {
    // find first non-matching dir
    if($dir === $to[$depth]) {
        // ignore this directory
        array_shift($relPath);
    } else {
        // get number of remaining dirs to $from
        $remaining = count($from) - $depth;
        if($remaining > 1) {
            // add traversals up to first matching dir
            $padLength = (count($relPath) + $remaining - 1) * -1;
            $relPath = array_pad($relPath, $padLength, '..');
            break;
        } else {
            $relPath[0] = './' . $relPath[0];
        }
    }
}
return implode('/', $relPath);
}
function getOriginalName( $fldname ){
  $getdef = selectQuery(PRODATTR, "attr_name", "attr_for_template='".$fldname."'" );
    return $getdef[0]['attr_name'];
}


function getproductOriginalName($id){
    $getprodorignal_name = selectQuery(PRODINFO, "prod_name", "id='".$id."'" );
      return $getprodorignal_name[0]['prod_name'];
  }
  
function getAttributeCat($fldname){
  $getdef   = selectQuery(PRODATTR, "parent_id ", "attr_for_template='".$fldname."'" );
  $getdef1   = selectQuery(PRODATTR, "attr_name ", "attr_id='".$getdef[0]['parent_id']."'" );
    return $getdef1[0]['attr_name'];
}
function getcaturl($id){
    $getcategory=selectQuery(PRODCAT,"url_title","id=".$id);
    return $getcategory[0]['url_title'];
}


function getUrl($type,$id){
   if($type=="Parent"){
         return getcaturl($id);
   }
   if($type=="Master"){
      $getcategory=selectQuery(PRODCAT,"url_title,parent_id","id=".$id);
      return getcaturl($getcategory[0]['parent_id'])."/".$getcategory[0]['url_title'];
   }
   if($type=="Sub"){
      $getcategory=selectQuery(PRODCAT,"url_title,parent_id","id=".$id);
      $getparent=selectQuery(PRODCAT,"parent_id","id=".$getcategory[0]['parent_id']);
      return getcaturl($getparent[0]['parent_id'])."/".getcaturl($getcategory[0]['parent_id'])."/".$getcategory[0]['url_title'];
   }
   if($type=="Product"){
      $getprod=selectQuery(PRODINFO,"parent_cat,master_cat,sub_cat,url_title","id=".$id);
       return getcaturl($getprod[0]['parent_cat'])."/".getcaturl($getprod[0]['master_cat'])."/".getcaturl($getprod[0]['sub_cat'])."/".$getprod[0]['url_title'];
   }
}


	function checkUser($oauth_provider,$oauth_uid,$fname,$lname,$email,$gender,$locale,$picture){
        if (!empty($_SERVER["HTTP_CLIENT_IP"])){  $ip = $_SERVER["HTTP_CLIENT_IP"]; }
        elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){ $ip = $_SERVER["HTTP_X_FORWARDED_FOR"]; }
        else{
            $ip = $_SERVER["REMOTE_ADDR"]; } $agent = $_SERVER['HTTP_USER_AGENT'];$browser = 'NA'; $device = '';
            if((preg_match('/MSIE/i',$agent)||preg_match('/Trident/i',$agent)||(preg_match('/Trident/i',$agent)&&stristr($agent,'Windows Phone'))) && !preg_match('/Opera/i',$agent)){  $browser = 'Internet Explorer'; $ub = "MSIE";}
            elseif(preg_match('/Windows NT 10/i',$agent) && preg_match('/Edge/i',$agent)){ $browser = 'Microsoft Edge';  $ub = "Edge"; }
            elseif(preg_match('/Firefox/i',$agent)){ $browser = 'Mozilla Firefox';   $ub = "Firefox";}
            elseif(preg_match('/Chrome/i',$agent)){ $browser = 'Google Chrome'; $ub = "Chrome"; }
            elseif(preg_match('/Safari/i',$agent)){ $browser = 'Apple Safari'; $ub = "Safari";}
            elseif(preg_match('/Opera/i',$agent)){ $browser = 'Opera';$ub = "Opera";}
            elseif(preg_match('/Netscape/i',$agent)){ $browser = 'Netscape'; $ub = "Netscape";}

           if( stristr($agent,'ipad') ) { $device = "ipad";}
           else if((stristr($agent,'iphone') || strstr($agent,'iphone'))&&stristr($agent,'Windows Phone')=== FALSE) {$device = "iphone";}
            else if( stristr($agent,'Windows Phone') ) { $device = "Windows Phone"; }
            else if( stristr($agent,'blackberry') ) {$device = "blackberry";}
            else if( stristr($agent,'android') ) { $device = "android";}
            else if( stristr($agent,'Windows NT 10.0') ) {$device = "Windows 10";}
             else if( stristr($agent,'Windows NT 6.1') ) {$device = "Windows 7";}

	   //	$prev_query =selectQuery(SOC_USERS,"*","oauth_provider = '".$oauth_provider."' AND oauth_uid = '".$oauth_uid."'");
           $data=array(
                'oauth_provider'=>$oauth_provider,'oauth_uid'=> $oauth_uid,'fname'=>$fname,'lname'=>$lname,
                 'email'=>$email,'gender'=>$gender, 'locale'=>$locale,'picture'=>$picture,'modified'=>date("Y-m-d H:i:s"),
             );
            $update =insertQuery(SOC_USERS,$data);
            $getuser111=selectQuery(BUYER,"u_id","u_email = '".$email."'");
             if(count($getuser111)){
                $userid=$getuser111[0]['u_id'];  $_SESSION['reguser']=$userid;

                if($_SESSION['setuser']!="setted"){$_SESSION['setuser']= 'notsetted';  }

                    $data1=array('source'=>$oauth_provider,'last_login'=>date("Y-m-d H:i:s"));
                    updateQuery(BUYER,$data1,"u_email = '".$email."'");
                    $checklastlogin= selectQuery(BUYERLOG,"login_time","user_id=".$userid." order by log_id desc limit 1");
                    $lastlogin=$checklastlogin[0]['login_time'];
                    $timearr=explode(" ",$lastlogin);
                    $datearr1= explode("-",$timearr[0]);$timearr1= explode(":",$timearr[1]);
                    $datestr=$datearr1[0].$datearr1[1].$datearr1[2].$timearr1[0].$timearr1[1].$timearr1[2];
                    $datenow=date("YmdHis");
                    $datediff= $datenow-$datestr;
                     if($datediff>200){
                        $datalog=array(
                        'user_id'=>$userid,'login_source'=>$oauth_provider,'username'=>$email,
                        'password'=>"",'ip_address'=>$ip, 'browser_name'=>$browser,
                        'device_type'=>$device,'details'=>$agent,
                        'login_time'=>date('Y-m-d H:i:s'), 'login_attempt'=>'success'
                        );
                       insertQuery(BUYERLOG,$datalog);
                    }
             }
             else {
                $data1=array(
                  'u_fname'=>$fname,'u_lname'=>$lname,
                  'u_gender'=>$gender,'u_email'=>$email,
                  'email_verified'=>'1','isActive'=>'1',
                   'source'=>$oauth_provider,'reg_date'=>date('Y-m-d H:i:s'),
                );
                $insert1 =insertQuery(BUYER,$data1);

                if($insert1){

                     $_SESSION['reguser']=$insert1;

                       if($_SESSION['setuser']!="setted"){ $_SESSION['setuser']= 'notsetted'; }

                      $data5=array( 'source'=>$oauth_provider,'last_login'=>date("Y-m-d H:i:s") );
                       updateQuery(BUYER,$data5,"u_id = ".$insert1);
                       $checklastlogin= selectQuery(USERLOG,"login_time","user_id=".$insert1." order by log_id desc limit 1");
                        $lastlogin=$checklastlogin[0]['login_time'];
                        $timearr=explode(" ",$lastlogin);
                        $datearr1= explode("-",$timearr[0]);$timearr1= explode(":",$timearr[1]);
                        $datestr=$datearr1[0].$datearr1[1].$datearr1[2].$timearr1[0].$timearr1[1].$timearr1[2];
                         $datenow=date("YmdHis");
                         $datediff= $datenow-$datestr;
                         if($datediff>200){
                            $datalog=array(
                            'user_id'=>$insert1, 'login_source'=>$oauth_provider,'username'=>$email,
                            'password'=>"",'ip_address'=>$ip,
                            'browser_name'=>$browser,'device_type'=>$device,'details'=>$agent,'login_time'=>date('Y-m-d H:i:s'),'login_attempt'=>'success'
                            );
                            insertQuery(BUYERLOG,$datalog);
                        }
                }
             }


		$query =selectQuery(SOC_USERS,"*","oauth_provider = '".$oauth_provider."' AND oauth_uid = '".$oauth_uid."'");

	  	return $query;
    }
    
    function highlightWords($text, $word){
        $text = preg_replace('#'. preg_quote($word) .'#i', '<strong>\\0</strong>', $text);
        return $text;
    }


?>