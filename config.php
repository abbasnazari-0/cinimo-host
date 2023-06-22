<?php

// connect to mysql
$servername = "localhost";
$username = "root";
$password = "nazari@0794";
$dbname = "cinimo";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}



// Change character set to utf8
mysqli_set_charset($conn,"utf8mb4");


header("Access-Control-Allow-Origin: https://cinimo.app");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");


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


// check if excit in database 
$sql = "SELECT * FROM tbl_ip_cach WHERE ip = '$visitor'";

$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) == 0){
 

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
    $GLOBALS['COUNTRY'] = $ipData['country'];
    $GLOBALS['table_video'] = "tbl_video_fake";
    $GLOBALS['tbl_sub_cataogry'] = "tbl_sub_catagory_fake";
    $GLOBALS['tbl_main_catagory'] = "tbl_main_catagory_fake";
    $GLOBALS['tbl_reels'] = "tbl_reels_fake";
  }else{
    $GLOBALS['COUNTRY'] = "IR";
    $GLOBALS['table_video'] = "tbl_video";
    $GLOBALS['tbl_sub_cataogry'] = "tbl_sub_catagory";
    $GLOBALS['tbl_main_catagory'] = "tbl_main_catagory";
    $GLOBALS['tbl_reels'] = "tbl_reels";
  }

  
  // $sql = "INSERT IGNORE INTO tbl_ip_cach (ip, country, `last_visit`) VALUES ('$visitor', '".$GLOBALS['COUNTRY']."', 'NOW()')";
  // isert if not  exist ip  in ip column
  $time = time();
  $sql = "INSERT INTO tbl_ip_cach (ip, country, `last_visit`) SELECT * FROM (SELECT '$visitor', '".$GLOBALS['COUNTRY']."', '$time') AS tmp WHERE NOT EXISTS (SELECT ip FROM tbl_ip_cach WHERE ip = '$visitor') LIMIT 1";



  mysqli_query($conn, $sql);
  if (mysqli_query($conn, $sql)) {
  } else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  }

}else{

  while($row = mysqli_fetch_assoc($result)) {
    if($row['country'] != "IR"){
      $GLOBALS['COUNTRY'] = "KA";
      $GLOBALS['table_video'] = "tbl_video_fake";
      $GLOBALS['tbl_sub_cataogry'] = "tbl_sub_catagory_fake";
      $GLOBALS['tbl_main_catagory'] = "tbl_main_catagory_fake";
      $GLOBALS['tbl_reels'] = "tbl_reels_fake";
    }else{
      $GLOBALS['COUNTRY'] = "IR";
      $GLOBALS['table_video'] = "tbl_video";
      $GLOBALS['tbl_sub_cataogry'] = "tbl_sub_catagory";
      $GLOBALS['tbl_main_catagory'] = "tbl_main_catagory";
      $GLOBALS['tbl_reels'] = "tbl_reels";
    }
  }

  $time = time();

  $sql = "UPDATE tbl_ip_cach SET last_visit = '$time' WHERE ip = '$visitor'";
  mysqli_query($conn, $sql);
  if (mysqli_query($conn, $sql)) {
  } else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  }
}

// get country code
