<?php
// include config file
require_once 'config.php';
// get type
if (!isset($_REQUEST['type'] )) {
    die('No type specified.');
}
$type = $_REQUEST['type'];

if($type == "video"){
    addVideoView($conn);
}else{
    addReelsView($conn);
}

function addVideoView($conn){
    $vide_tag = $_REQUEST['vide_tag'];
    $user_tag = $_REQUEST['user_tag'];

    $time = time();

    $sql  = "INSERT INTO tbl_view (`vid_tag`, `user_tag`, `time`) VALUES ('$vide_tag','$user_tag','$time')";

    if ($conn->query($sql) === TRUE) {
        echo "added!";
      } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
      }
      
}

function  addReelsView($conn){}

$conn->close();