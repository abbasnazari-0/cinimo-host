<?php
// include config file
require_once 'config.php';

// get video id
 if (!isset($_REQUEST['vid_ids'])) {
    die('No vid_ids specified.');
}else{
    $vid_ids = $_REQUEST['vid_ids'];
}
$title = $_REQUEST['title'];

$vid_ids = explode(",", $vid_ids);
// remove [  and  ]
$vid_ids = str_replace("[", "", $vid_ids);
$vid_ids = str_replace("]", "", $vid_ids);



$sql = "SELECT tbl_video.id, tbl_video.title, tbl_video.imdb, tbl_video.tag, tbl_video.desc,tbl_video.thumbnail_1x   FROM tbl_video WHERE id IN ('".str_replace(" ","",implode("','",$vid_ids))."') ";

// execute query
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    
    // output data of each row
    $data = array();
    while($row = mysqli_fetch_assoc($result)) {
       
        $data[] = array("title"=>$title,"data"=>$row);
    }
    echo json_encode($data);

}else{
    $data = array();
}