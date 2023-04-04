<?php
// include config file
require_once 'config.php';
// get type
if (!isset($_REQUEST['type'] )) {
    die('No type specified.');
}
$type = $_REQUEST['type'];

if($type == "remove"){
    removeFavorite($conn);
}else{
    submitFavorite($conn);
}

function removeFavorite($conn){
 $vid_tag = $_REQUEST['vid_tag'];
 $user_tag = $_REQUEST['user_tag'];

//  remove user liked
$sql = "DELETE FROM tbl_user_like WHERE vid_tag = '$vid_tag' AND user_tag = '$user_tag'";


    // execute query
    if ($conn->query($sql) === TRUE) {
        echo "Favorite removed successfully";
    } else {
        echo "Error: " ;
    }

}
function submitFavorite($conn){
    $vid_tag = $_REQUEST['vid_tag'];
    $user_tag = $_REQUEST['user_tag'];

    // insert favorite data if not exist
    $sql = "INSERT IGNORE INTO tbl_user_like (vid_tag, user_tag) VALUES ('$vid_tag', '$user_tag')";
    
    

    // execute query
    if ($conn->query($sql) === TRUE) {
        echo "Favorite added successfully";
    } else {
        echo "Error: " ;
    }
  
}