<?php
// include config file
require_once 'config.php';

// get video id
 if (!isset($_REQUEST['video_tags'])) {
    die('No video_tags specified.');
}else{
    $video_tags = $_REQUEST['video_tags'];
    if (strpos($video_tags, ',') !== false) {
        $video_tags = explode(',', $video_tags);
        $video_tags = str_replace("[", "", $video_tags);
        $video_tags = str_replace("]", "", $video_tags);
    }else{
       die('No video_tags specified.');
    }
}

if(!isset($_REQUEST['video_select_tag'])){
    die('No video_select_tag specified.');
}
$video_select = $_REQUEST['video_select_tag'];


// get tag id in sql
$sql = "SELECT * FROM tbl_vid_tags WHERE tag IN ('".implode("','", $video_tags)."')";



// execute query
$result = mysqli_query($conn, $sql);
 
if (mysqli_num_rows($result) > 0) {
    
    // output data of each row
    $data = array();
    while($row = mysqli_fetch_assoc($result)) {
        
        $data[] = $row["id"];
    }
} else {
    $data = array();
}




//now should get all video id with tag id
// AND tbl_video.tag <> '$video_select'
$sql = "SELECT tbl_video.id, tbl_video.title, tbl_video.imdb, tbl_video.tag, tbl_video.desc,tbl_video.thumbnail_1x  , COUNT(user_tag) AS view FROM tbl_video LEFT JOIN tbl_view ON tbl_view.vid_tag = tbl_video.tag WHERE video_tags LIKE '%".implode("%' OR video_tags LIKE '%", $data)."%'  GROUP BY tbl_video.id  LIMIT 10";

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
    $data = array();
}






