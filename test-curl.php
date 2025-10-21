<?php
putenv('CURL_CA_BUNDLE=' . __DIR__ . '/certs/cacert.pem');

$ch = curl_init('https://www.googleapis.com/oauth2/v4/token');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$result = curl_exec($ch);

if(curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
} else {
    echo 'OK';
}
curl_close($ch);
