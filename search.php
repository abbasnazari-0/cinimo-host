<?php
// include config file
require_once 'config.php';

// get video id
 if (!isset($_REQUEST['query']) || !isset($_REQUEST['count'])) {
    die('No query specified.');
}else{
    $query = $_REQUEST['query'];
}

 $count = $_REQUEST['count'];

// get video data
$sql = "SELECT ".$GLOBALS['table_video'].".id, ".$GLOBALS['table_video'].".title, ".$GLOBALS['table_video'].".imdb, ".$GLOBALS['table_video'].".tag, ".$GLOBALS['table_video'].".desc,".$GLOBALS['table_video'].".thumbnail_1x  , COUNT(user_tag) AS view FROM ".$GLOBALS['table_video']."  LEFT JOIN tbl_view ON tbl_view.vid_tag = ".$GLOBALS['table_video'].".tag COLLATE utf8mb4_0900_ai_ci  WHERE ".$GLOBALS['table_video'].".title LIKE '%$query%'   OR ".$GLOBALS['table_video'].".desc LIKE '%$query%'  GROUP BY ".$GLOBALS['table_video'].".id  ORDER BY id DESC LIMIT $count";



// execute query
$result = mysqli_query($conn, $sql);


if (mysqli_num_rows($result) > 0) {
    
    // output data of each row
    $data = array();
    while($row = mysqli_fetch_assoc($result)) {
        // if($row['title'] == null){
        //     die("[]");
        // }
        $data[] = $row;
    }
    echo json_encode($data);

} else {
    echo "[]";
}


// prevent empty query
if($query != ""){
    // insert query to tbl_search_logs (title, user_tag, ip)
    $sql = "INSERT INTO tbl_search_logs (title, user_tag, ip) VALUES ('$query', '', '$visitor')";
    mysqli_query($conn, $sql);
}


mysqli_close($conn);