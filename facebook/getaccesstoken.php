<?php
include_once ("../includes/configuration.php");

require_once 'src/Facebook/autoload.php';
$redirect=base64_decode($_REQUEST['fb']);
$_SESSION['fbredirect']= $redirect;

$fb = new Facebook\Facebook([
  'app_id' => FB_APPID,
  'app_secret' =>  FB_SECRETEKEY,
  'default_graph_version' => 'v2.4',
]);

$helper = $fb->getRedirectLoginHelper();
$permissions = ['email'];

$loginUrl = $helper->getLoginUrl(SITEURL."/".FB_CALLBACKURL, $permissions);


echo "<script>window.location='".$loginUrl."'</script>";
?>