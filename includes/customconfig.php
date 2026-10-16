<?php
$getconfigdetails = selectQuery(CONFIG, "*", "id= 1");

define("SITE_TITLE", $getconfigdetails[0]['seo_title']); // Title of website
define("SITENAME", $getconfigdetails[0]['site_name']); // Name of website used in emails
define("LOGO", $getconfigdetails[0]['logo']);
define("OGIMAGE", $getconfigdetails[0]['og_image']);
define("SMS_SYSTEM", $getconfigdetails[0]['sms_enable']);
define("WORKINGKEY", $getconfigdetails[0]['sms_working_key']); // Standard variable for SMS API Key Declaration
define("SMS_SENDER", $getconfigdetails[0]['sms_sender']);
define("SMSSITENAME", $getconfigdetails[0]['sms_site_name']);

define("EMAIL_FOOTER", $getconfigdetails[0]['footer_email']);
define("EMAIL_REG", $getconfigdetails[0]['registration_email']); //used for all type of regsitration and vendor payment
define("EMAIL_SENDER", $getconfigdetails[0]['sender_email']);
define("EMAIL_CONTACT", $getconfigdetails[0]['contact_email']); //used for  enquiry and contact
define("MAIN_ADMIN", $getconfigdetails[0]['admin_email']); // used for subscription,requiremt mail
define("EMAIL_ORDER", $getconfigdetails[0]['order_email']);

define("ENQ_CONACT", $getconfigdetails[0]['enquiry_contact']);
define("CONTACTUSNO", $getconfigdetails[0]['contactus_no']);
define("ADMINCONTACT", $getconfigdetails[0]['Main_admin_contact']); // used for wishlist quotaton,order
define("MAP", $getconfigdetails[0]['map']);

define("VENDOR_PLAN_PAYMENT_MODE", "Online"); // Accept payments of multiseller via Offline / Online methodes
define("VENDOR_PLAN_INVSTART", $getconfigdetails[0]['seller_inv']);

define('METADESCRIPTION', $getconfigdetails[0]['seo_description']);
define('METAKEYWORDS', $getconfigdetails[0]['seo_keywords']);
define('FONTFAMILY', "https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap");

define("PAYU_ENABLE", $getconfigdetails[0]['payu_enable']);
define("PAYURL", $getconfigdetails[0]['payu_url']); // Standard variable for PayUMoney URL Declaration
if (PAYURL == "https://secure.payu.in") {
    define("MARCHANTKEY", $getconfigdetails[0]['live_payu_merchant_key']); // Standard variable for PayUMoney Merchant Key Declaration
    define("SALT", $getconfigdetails[0]['live_payu_salt_key']); // Standard variable for PayUMoney Salt Key Declaration
} else {
    define("MARCHANTKEY", $getconfigdetails[0]['test_payu_merchant_key']); // Standard variable for PayUMoney Merchant Key Declaration
    define("SALT", $getconfigdetails[0]['test_payu_salt_key']); // Standard variable for PayUMoney Salt Key Declaration
}
define("PAYUHEADER", $getconfigdetails[0]['payu_auth_header']);

define("EASEENV", $getconfigdetails[0]['ease_environment']);
define("EASE_ENABLE", $getconfigdetails[0]['ease_enable']);

if (EASEENV == "prod") {
    define("EASEMARCHANTID", $getconfigdetails[0]['live_ease_merchant_key']);
    define("EASESALT", $getconfigdetails[0]['live_ease_salt_key']);
} else {
    define("EASEMARCHANTID", $getconfigdetails[0]['test_ease_merchant_key']);
    define("EASESALT", $getconfigdetails[0]['test_ease_salt_key']);
}
define("INSTAENV", $getconfigdetails[0]['instamojo_environment']);
define("INSTA_ENABLE", $getconfigdetails[0]['instamojo_enable']);
if(INSTAENV == "prod"){
    define("INSTAAPIKEY", $getconfigdetails[0]['instamojo_liveApiKey']);
    define("INSTATOKEN", $getconfigdetails[0]['instamojo_liveAuthToken']);
    define("INSTAURL", $getconfigdetails[0]['instamojo_liveUrl']);
}
else{
    define("INSTAAPIKEY", $getconfigdetails[0]['instamojo_testApiKey']);
    define("INSTATOKEN", $getconfigdetails[0]['instamojo_testAuthToken']);
    define("INSTAURL", $getconfigdetails[0]['instamojo_testUrl']);
}

define("RAZORENV", $getconfigdetails[0]['razorpay_environment']);
define("RAZOR_ENABLE", $getconfigdetails[0]['razorpay_enable']);
if(RAZORENV == "prod"){
    define("RAZORAPIKEY", $getconfigdetails[0]['razorpay_liveApiKey']);
    define("RAZORSECRETE", $getconfigdetails[0]['razorpay_liveSecrete']);
}
else{
     define("RAZORAPIKEY", $getconfigdetails[0]['razorpay_testApiKey']);
    define("RAZORSECRETE", $getconfigdetails[0]['razorpay_testSecrete']);
}
$displayCurrency = 'INR';
//******************************* Social media footer icon links ****************************//
define("FBLINK", $getconfigdetails[0]['fb_link']);
define("INSTAGRAMLINK", $getconfigdetails[0]['instagram_link']);
define("LINKEDIN", $getconfigdetails[0]['linkedIn_link']);
define("PINTEREST", $getconfigdetails[0]['Pinterest_link']);
define("YOUTUBELINK", $getconfigdetails[0]['youtube_link']);
define("TWITTERLINK", $getconfigdetails[0]['twitter_link']);
define("PLAYSTORELINK", $getconfigdetails[0]['playstore_link']);
//*******************************************************************************************//

define("ADDRESSDETAIL", $getconfigdetails[0]['address']);

define("UPLOADERR", array(
    0 => "There is no error, the file uploaded with success",
    1 => "The uploaded file exceeds the upload_max_filesize directive in php.ini",
    2 => "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
    3 => "The uploaded file was only partially uploaded",
    4 => "No file was uploaded",
    6 => "Missing a temporary folder"));

define("SERVICE_AVAILABLE", 'NO');
define("VENDORPRIORITY", "ON");
date_default_timezone_set($getconfigdetails[0]['default_timezone']);
define("PRODCNTPAGE", 40); //used for pagination
define("REVIEWCNTPAGE", 8); //used for pagination
define("BLOGCNTPAGE", 8); //used for pagination
/* -------------------------------------------------------------------- */
define("SOCIALLOGIN", "ON");
define("FB_APPID", $getconfigdetails[0]['fb_appId']);
define("FB_SECRETEKEY", $getconfigdetails[0]['fb_secretKey']);
define("FB_CALLBACKURL", $getconfigdetails[0]['fb_callbackURL']);

/* GP+ App Details */
define("GP_appname", $getconfigdetails[0]['gp_appname']);
define("GP_CLIENTID", $getconfigdetails[0]['gp_clientId']);
define("GP_SECRETEKEY", $getconfigdetails[0]['gp_secretKey']);
define("GP_CALLBACKURL", $getconfigdetails[0]['gp_callbackURL']);

define("SHIPBY", $getconfigdetails[0]['shippingBy']); //SELF/SHIPROCKET
define("SHIPUSER", $getconfigdetails[0]['shippingPanel_username']);
define("SHIPPWD", $getconfigdetails[0]['shippingPanel_password']);

define("DATAGOVAPIKEY", "579b464db66ec23bdd000001bf14c528f0fb4137644b299ed132f9f5");

define("COMPAIRLIMIT", "5");
define("VENDOR_DISBURSMENT_DAYS", 7);
define("TAXTYPE", "GST"); //GST or VAT

define("GSTNO", $getconfigdetails[0]['gst_no']);
define("VATNO", $getconfigdetails[0]['vat_no']);
define("STOCK_ALERT", 5);
define("PLANHSNCODE", "998314");
?>
