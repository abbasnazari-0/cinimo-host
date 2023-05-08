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
$sql = "SELECT tbl_video.id, tbl_video.title, tbl_video.imdb, tbl_video.tag, tbl_video.desc,tbl_video.thumbnail_1x  , COUNT(user_tag) AS view FROM tbl_video  LEFT JOIN tbl_view ON tbl_view.vid_tag = tbl_video.tag WHERE tbl_video.title LIKE '%$query%'   OR tbl_video.desc LIKE '%$query%'  GROUP BY tbl_video.id  ORDER BY view DESC LIMIT $count";


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

mysqli_close($conn);