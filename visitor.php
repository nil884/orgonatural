<?php
$hitfrom = "";
if ( !empty( $_SERVER["HTTP_CLIENT_IP"] ) ) {
        //check for ip from share internet
        $ip = $_SERVER["HTTP_CLIENT_IP"];
    } elseif ( !empty( $_SERVER["HTTP_X_FORWARDED_FOR"] ) ) {
        // Check for the Proxy User
        $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    } else {
        $ip = $_SERVER["REMOTE_ADDR"];
    }
    /* $agent = $_SERVER['HTTP_USER_AGENT'];  */
    if ( !isset( $_SERVER['HTTP_USER_AGENT'] ) ) {$agent = "none";} else { $agent = $_SERVER['HTTP_USER_AGENT'];}
    $browser = 'NA';
    if (  ( preg_match( '/MSIE/i', $agent ) || preg_match( '/Trident/i', $agent ) || ( preg_match( '/Trident/i', $agent ) && stristr( $agent, 'Windows Phone' ) ) ) && !preg_match( '/Opera/i', $agent ) ) {
        $browser = 'Internet Explorer';
        $ub      = "MSIE";
    } elseif ( preg_match( '/Windows NT 10/i', $agent ) && preg_match( '/Edge/i', $agent ) ) {
        $browser = 'Microsoft Edge';
        $ub      = "Edge";
    } elseif ( preg_match( '/Firefox/i', $agent ) && !preg_match( '/Iceweasel/i', $agent ) ) {
        $browser = 'Mozilla Firefox';
        $ub      = "Firefox";
    } elseif ( preg_match( '/Chrome/i', $agent ) ) {
        $browser = 'Google Chrome';
        $ub      = "Chrome";
    } elseif ( preg_match( '/Safari/i', $agent ) ) {
        $browser = 'Apple Safari';
        $ub      = "Safari";
    } elseif ( preg_match( '/Opera/i', $agent ) ) {
        $browser = 'Opera';
        $ub      = "Opera";
    } elseif ( preg_match( '/Netscape/i', $agent ) ) {
        $browser = 'Netscape';
        $ub      = "Netscape";
    } elseif ( preg_match( '/Iceweasel/i', $agent ) && preg_match( '/Firefox/i', $agent ) ) {
        $browser = 'Iceweasel';
        $ub      = "Iceweasel";
    }
    $device = '';
    if ( stristr( $agent, 'ipad' ) ) {
        $device = "ipad";
    } else if (  ( stristr( $agent, 'iphone' ) || strstr( $agent, 'iphone' ) ) && stristr( $agent, 'Windows Phone' ) === false ) {
        $device = "iphone";
    } else if ( stristr( $agent, 'Windows Phone' ) ) {
        $device = "Windows Phone";
    } else if ( stristr( $agent, 'blackberry' ) ) {
        $device = "blackberry";
    } else if ( stristr( $agent, 'android' ) ) {
        $device = "android";
    } else if ( stristr( $agent, 'Windows NT 10.0' ) ) {
        $device = "Windows 10";
    } else if ( stristr( $agent, 'Windows NT 6.1' ) ) {
        $device = "Windows 7";
    } else if ( preg_match( '/linux/i', $agent ) ) {
        $device = "Linux";
    }
    
    //echo  "Agent-".$_SERVER['HTTP_USER_AGENT']."  Devoce".$device;
    //$hitfrom      = $_SERVER['HTTP_REFERER'];
    $cookie_name  = 'visit';
    $cookie_value = 'visit';
    setcookie( $cookie_name, $cookie_value, time() + 600 );
    // include "includes/configuration.php";
    if ( !isset( $_COOKIE[$cookie_name] ) ) {
        /* print 'Cookie with name "' . $cookie_name . '" does not exist...';*/
        $datalog = array(
            'ip' => $ip,
            'device' => $device,
            'browser' => $browser,
            'details' => $agent,
            'date' => date( 'Y-m-d H:i:s' ),
        );
        $s = insertQuery(VISITORLIST, $datalog );
    }