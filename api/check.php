<?php

session_start();

$rawPostData = file_get_contents("php://input");

$data = json_decode($rawPostData, true);

if (isset($data['requestToken'])) {

$hexData = $_SERVER['HTTP_X_PARSE_APP_DATA'];

$rT = hexdec($hexData);

}

$jsonString = '{

"result": {

"newApkUrl": "",

"newVersionName": "",

"shouldUpdateGooglePlayVersion": false,

"forceAutoUpdate": false,

"stagedRolloutDays": 0,

"isActivated": true,

"deviceName": "TV",

"account": "andy@hax.net",

"responseToken": 12345

}

}';

$dataArray = json_decode($jsonString, true);

$dataArray['result']['responseToken'] = $rT;

$newJsonString = json_encode($dataArray, JSON_PRETTY_PRINT);

header('Content-Type: application/json');

echo $newJsonString;

session_destroy();

?>

