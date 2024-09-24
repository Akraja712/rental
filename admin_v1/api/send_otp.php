<?php

$url = 'https://api.authkey.io/request?authkey=76c9746f3ac2f7c3&mobile=6374279757&country_code=91&sid=9214&otp=243582&company=A1 Ads';

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($curl);

if ($response === false) {
    // Error handling
    echo "Error: " . curl_error($curl);
} else {
    // Process the response
    echo $response;
}

curl_close($curl);
?>