<?php
// include config file
require_once 'config.php';


// get video id
 if (!isset($_REQUEST['type'] )) {
    die('No type specified.');
}
$type = $_REQUEST['type'];

if($type == "get"){
    getComment($conn);
}else{
    submitComment($conn);
}

function submitComment($conn){
    $comment_message = $_REQUEST['comment_message'];
    $comment_user_tag = $_REQUEST['comment_user_tag'];
    $comment_vid_tag = $_REQUEST['comment_vid_tag'];
    

    
     $date = date('Y-m-d H:i:s');

    // insert comment data
    $sql = "INSERT INTO tbl_reels_comment (comment, user_tag, vid_tag, time) VALUES ('$comment_message', '$comment_user_tag', '$comment_vid_tag' , '$date')";

    // execute query
     
    // execute query
    if ($conn->query($sql) === TRUE) {
        echo "comment added successfully";
    } else {
        echo "Error: " ;
    }
  

}

function getComment($conn){


$video_tags = $_REQUEST['vid_tag'];
// get comment data
$sql = "SELECT * FROM tbl_reels_comment WHERE vid_tag =  '$video_tags'";

// execute query
$result = mysqli_query($conn, $sql);



if (mysqli_num_rows($result) > 0) {
    
    // output data of each row
    $data = array();
    while($row = mysqli_fetch_assoc($result)) {
        
        $data[] = $row;
    }
    echo json_encode($data);

} else {
    echo "[]";
}

mysqli_close($conn);

}