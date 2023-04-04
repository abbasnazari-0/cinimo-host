<?php
// include config file
require_once 'config.php';

// get video id
 if (!isset($_REQUEST['user_tag'])) {
    die('No user tags specified.');
}


$ip = $_SERVER['REMOTE_ADDR'];
$device_name = $_REQUEST['device_name'];
$device_id = $_REQUEST['device_id'];
$user_tag = $_REQUEST['user_tag'];


// insert user data if not exists
$sql = "INSERT IGNORE INTO tbl_user (ip, device_name, device_id, user_tag) VALUES ('$ip', '$device_name', '$device_id', '$user_tag')";



// execute query
if ($conn->query($sql) === TRUE) {
    echo "user added successfully";
  } else {
    echo "Error: " ;
  }
  
