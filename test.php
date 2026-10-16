<?php
$ch = curl_init("https://api.razorpay.com");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);

if ($response === false) {
    echo curl_error($ch);
} else {
    echo $response;
}
curl_close($ch);
