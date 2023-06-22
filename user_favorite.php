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

}else if($type == "get"){
    getUserFavorite($conn);
}else{
    submitFavorite($conn);
}


function getUserFavorite($conn){
    $user_tag = $_REQUEST['user_tag'];
    // get comment data
    $sql = "SELECT ".$GLOBALS['table_video'].".id, ".$GLOBALS['table_video'].".title, ".$GLOBALS['table_video'].".imdb, ".$GLOBALS['table_video'].".tag, ".$GLOBALS['table_video'].".desc,".$GLOBALS['table_video'].".thumbnail_1x  , COUNT(tbl_view.id) AS view FROM ".$GLOBALS['table_video']." INNER JOIN tbl_user_like ON ".$GLOBALS['table_video'].".tag = tbl_user_like.vid_tag LEFT JOIN tbl_view ON tbl_view.vid_tag = ".$GLOBALS['table_video'].".tag WHERE tbl_user_like.user_tag = '$user_tag' GROUP BY ".$GLOBALS['table_video'].".id ORDER BY RAND()";
    
    // die($sql);
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