<?php
$visitor = $_SERVER['REMOTE_ADDR'];




$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,"http://newapp.abbasnazari.com/ip.php");
curl_setopt($ch, CURLOPT_POST, 1);

// In real life you should use something like:
 curl_setopt($ch, CURLOPT_POSTFIELDS, 
          http_build_query(array('ip' => $visitor)));

// Receive server response ...
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec($ch);

curl_close($ch);

print($server_output);
