<?php include ("../../includes/configuration.php");
if($action == "Update_mail"){
$data = array('subject' => $subject,'body' => addslashes($body) );
$upate_mail = updateQuery(EMAILTEMPLATE, $data, "type= '".$type."' and mail_to = '".$mailto."'  ");
    if ($upate_mail) {  echo 1; } else { echo 0; }
}

if($action == "addSMSTemplateId"){
    $smsid=$_POST['smsid'];$templateid=$_POST['templateid'];
$data = array('templateId' => $templateid);
$upate_sms = updateQuery(SMSTEMPLATE, $data, "id=".$smsid);
    if ($upate_sms) {  echo 1; } else { echo 0; }
}
?>