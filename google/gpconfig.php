<?php
include_once 'src/Google_Client.php';
include_once 'src/contrib/Google_Oauth2Service.php';


$clientId = GP_CLIENTID;
$clientSecret = GP_SECRETEKEY;
$redirectURL = SITEURL."/".GP_CALLBACKURL;

//Call Google API
$gClient = new Google_Client();
$gClient->setApplicationName(GP_appname);
$gClient->setClientId($clientId);
$gClient->setClientSecret($clientSecret);
$gClient->setRedirectUri($redirectURL);
$gClient->offline = FALSE;

$gClient->setScopes('https://www.googleapis.com/auth/userinfo.email');
$google_oauthV2 = new Google_Oauth2Service($gClient);
?>