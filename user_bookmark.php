<?php
// include config file
require_once 'config.php';
// get type
if (!isset($_REQUEST['type'] )) {
    die('No type specified.');
}
$type = $_REQUEST['type'];

if($type == "remove"){
    removeBookmark($conn);
}else if($type == "get"){
    getUserBookmark($conn);
}else{
    submitBookmark($conn);
}

function getUserBookmark($conn){
    $user_tag = $_REQUEST['user_tag'];
    // get comment data
    $sql = "SELECT tbl_video.id, tbl_video.title, tbl_video.imdb, tbl_video.tag, tbl_video.desc,tbl_video.thumbnail_1x  , COUNT(tbl_view.id) AS view FROM tbl_video INNER JOIN tbl_user_bookmark ON tbl_video.tag = tbl_user_bookmark.vid_tag LEFT JOIN tbl_view ON tbl_view.vid_tag = tbl_video.tag WHERE tbl_user_bookmark.user_tag = '$user_tag' GROUP BY tbl_video.id";
    
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
}
function removeBookmark($conn){
 $vid_tag = $_REQUEST['vid_tag'];
 $user_tag = $_REQUEST['user_tag'];

 //  remove user bookmark
$sql = "DELETE FROM tbl_user_bookmark WHERE vid_tag = '$vid_tag' AND user_tag = '$user_tag'";


// execute query
if ($conn->query($sql) === TRUE) {
    echo "bookmark removed successfully";
} else {
    echo "Error: " ;
}


}
function submitBookmark($conn){
    $vid_tag = $_REQUEST['vid_tag'];
    $user_tag = $_REQUEST['user_tag'];

    // insert comment data
    $sql = "INSERT IGNORE INTO tbl_user_bookmark (vid_tag, user_tag) VALUES ('$vid_tag', '$user_tag')";

    // execute query
    if ($conn->query($sql) === TRUE) {
        echo "bookmark added successfully";
    } else {
        echo "Error: " ;
    }
  
}