<?php include("../includes/configuration.php");
if($action=="updateProfile"){
    $data = array("u_fname"=>ucfirst($userproffname),"u_lname"=>($userproflname!=""?ucfirst($userproflname):""),"u_email"=>$userprofemail,"u_mobile"=>$userprofmob);
    $update = updateQuery(BUYER,$data,"u_id=".$userprofid);
    if($update)
    echo 1;
    else
    echo 0;
}
if($action == "forgetpassword"){
    $email1 = $_REQUEST['email1'];
    $user = selectQuery(BUYER,"*","u_email='".$email1."'  and isActive='1' and email_verified ='1' ");
    if(count($user)){
        $userid = $user[0]['u_id'];$enocodid =base64_encode( $userid);
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= "From:".SITENAME."<".EMAIL_SENDER.">";
        $user_email = selectQuery(EMAILTEMPLATE,"*","type='Reset Password' and  mail_to= 'Buyer' "); 
        $replacement_array = array('siteurl' => SITEURL, 'sitename' => SITENAME,'smssitename' => SMSSITENAME,'userid'=> base64_encode($userid),);
        $subject_buyer = convertemailstr($replacement_array,$user_email[0]['subject']);
        $body_buyer = convertemailstr($replacement_array,$user_email[0]['body']);
        $sentmail = mail ( $email1, $subject_buyer, $body_buyer, $headers);
        if($sentmail){ echo "1";} else{ echo "0"; }
    } else{ echo "0"; }
}
if($action == "recover_password"){
    $uid = $_REQUEST['uid'] ;
    $password = $_REQUEST['password'];
    $encrypt = password_encrypt($password);
    $password1 = $_REQUEST['password1'];
    $userdata = selectQuery(BUYER,"*","u_id=".$uid);
    $data = array('password'=> $encrypt,);
    $update = updateQuery(BUYER,$data,"u_id=".$uid);
    if($update)
    echo "2";
    else
    echo "3";
} ?>