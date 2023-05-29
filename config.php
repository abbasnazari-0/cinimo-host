<?php

// connect to mysql
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cinimo";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}



// Change character set to utf8
mysqli_set_charset($conn,"utf8mb4");


// function that create string of random characters
function randomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$visitor =  $_SERVER['REMOTE_ADDR'];
// echo  ipData($user_ip);



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


$ipData = json_decode($server_output, true);
if(!key_exists("country", $ipData) ||  $ipData['country'] != "IR" ){
  $GLOBALS['COUNTRY'] = "KA";
  $GLOBALS['table_video'] = "tbl_video_fake";
  $GLOBALS['tbl_sub_cataogry'] = "tbl_sub_cataogry_fake";
  $GLOBALS['tbl_main_catagory'] = "tbl_main_catagory_fake";
}else{
  $GLOBALS['COUNTRY'] = "IR";
  $GLOBALS['table_video'] = "tbl_video";
  $GLOBALS['tbl_sub_cataogry'] = "tbl_sub_cataogry";
  $GLOBALS['tbl_main_catagory'] = "tbl_main_catagory";

}
