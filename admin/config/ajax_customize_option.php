<?php include "../../includes/configuration.php";

$bodyfont = $_POST['bodyfont'];
$bodyfontarr = explode('"',$bodyfont);
$bodyfontVars = json_decode($_POST['bodyfontVars']);
$bodyfontSubsets = json_decode($_POST['bodyfontSubsets']);
$font1str = $bodyfontarr[1].":".implode(",",$bodyfontVars);

$bodyfontsize=json_decode($_POST['bodyfontsize']);$bodyfontSubs=json_decode($_POST['bodyfontSubs']);

$result = array_merge($bodyfontSubsets, $headfontSubsets);
$finalsubsets = array_unique($result);
$fonturl="@import url('https://fonts.googleapis.com/css?family=".$bodyfontarr[1].":".implode(",",$bodyfontsize)."&display=swap&subset=".implode(",",$bodyfontSubs)."');";
//$fonturl="@import url('https://fonts.googleapis.com/css?family=".$bodyfontarr[1].":".implode(",",$bodyfontVars)."&display=swap&subset=".implode(",",$bodyfontSubsets)."');";
//$fonturl = "@import url('https://fonts.googleapis.com/css?family=".$font1str.($font2str!=''?'|'.$font2str:'')."&display=swap&subset=".implode(",",$finalsubsets)."');";
$headcontbackcolor = $_POST['headcontbackcolor'];
$headcontlinkcolor = $_POST['headcontlinkcolor'];
$headcontlinkcolorhover = $_POST['headcontlinkcolorhover'];
$bfontSize = $_POST['bfontSize'];
$footerH4 = $_POST['footerHeadFont'];
$custPriBtn = $_POST['custPriBtn'];
$custPriBtnval = $_POST['custPriBtnval'];
$custHeaderBack = $_POST['custHeaderBack'];
$custHeaderLinkColor = $_POST['custHeaderLinkColor'];
$custHeadLinkColorhover = $_POST['custHeadLinkColorhover'];
$headbadgecolor = $_POST['headbadgecolor'];
$custNavBack = $_POST['custNavBack'];
$custNavLinkColor = $_POST['custNavLinkColor'];
$custNavLinkColorhover = $_POST['custNavLinkColorhover'];
$custNavLinkBackhover = $_POST['custNavLinkBackhover'];
$navtextbold = $_POST['navtextbold'];
$secondBtn = $_POST['secondBtn'];
$secondBtnHover = $_POST['secondBtnHover'];
$secondBtnBorder = $_POST['secondBtnBorder'];
$secondBtnBorderHover = $_POST['secondBtnBorderHover'];
$custFooterBack = $_POST['custFooterBack'];
$custPrimaryColor = $_POST['custPrimaryColor'];
$alpgtitcolor = $_POST['alpgtitcolor'];
$custSecondaryStyle = $_POST['custSecondaryStyle']; 
$custFootHeadColor = $_POST['custFootHeadColor'];
$custFooterLinkHover = $_POST['custFooterLinkHover'];
$footerHeadingThickness = $_POST['footerHeadingThickness'];
$custFooterTextColor = $_POST['custFooterTextColor'];
$custPrimaryBtnHover = $_POST['custPrimaryBtnHover'];
$bodybgcolor = $_POST['bodybgcolor'];
$bodyprimtext = $_POST['bodyprimtext'];
$invprtextcolor = $_POST['invprtextcolor'];
$invsectextcolor = $_POST['invsectextcolor'];
$invtabhead = $_POST['invtabhead'];
$hmsecfontsize = $_POST['hmsecfontsize'];
$bodysecondtext = $_POST['bodysecondtext'];
$hmsecfontcolor = $_POST['hmsecfontcolor'];
$alertsuc = $_POST['alertsuc'];
$alertdan = $_POST['alertdan'];
$alertinfo = $_POST['alertinfo'];
$alertwarn = $_POST['alertwarn'];
$alertsuctxt = $_POST['alertsuctxt'];
$alertdantxt = $_POST['alertdantxt'];
$alertinfotxt = $_POST['alertinfotxt'];
$alertwarntxt = $_POST['alertwarntxt'];
$notifbaccolor = $_POST['notifbaccolor'];
$notiftextcolor = $_POST['notiftextcolor'];
$notiftextsize = $_POST['notiftextsize'];

$fbbackcolor = $_POST['fbbackcolor'];
$fbtextcolor = $_POST['fbtextcolor'];
$fbbackhovercolor = $_POST['fbbackhovercolor'];
$fbtexthovercolor = $_POST['fbtexthovercolor'];

$linkdinbackcolor = $_POST['linkdinbackcolor'];
$linkdintextcolor = $_POST['linkdintextcolor'];
$linkdinbackhovercolor = $_POST['linkdinbackhovercolor'];
$linkdintexthovercolor = $_POST['linkdintexthovercolor'];

$pinterbackcolor = $_POST['pinterbackcolor'];
$pintertextcolor = $_POST['pintertextcolor'];
$pinterbackhovercolor = $_POST['pinterbackhovercolor'];
$pintertexthovercolor = $_POST['pintertexthovercolor'];

$instabackcolor = $_POST['instabackcolor'];
$instatextcolor = $_POST['instatextcolor'];
$instabackhovercolor = $_POST['instabackhovercolor'];
$instatexthovercolor = $_POST['instatexthovercolor'];

$youtubebackcolor = $_POST['youtubebackcolor'];
$youtubetextcolor = $_POST['youtubetextcolor'];
$youtubebackhovercolor = $_POST['youtubebackhovercolor'];
$youtubetexthovercolor = $_POST['youtubetexthovercolor'];

$twitbackcolor = $_POST['twitbackcolor'];
$twittextcolor = $_POST['twittextcolor'];
$twitbackhovercolor = $_POST['twitbackhovercolor'];
$twittexthovercolor = $_POST['twittexthovercolor'];

include "../../css/dynamic-style.php";
file_put_contents('../../css/dynamic-style.css', $cssstr);
/* if(file_exists('../../css/dynamic-style.css')){
   $str=file_get_contents('../../css/dynamic-style.css');
} */
?>