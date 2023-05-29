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



$sql = "SELECT ".$GLOBALS['table_video'].".id, ".$GLOBALS['table_video'].".title, ".$GLOBALS['table_video'].".imdb, ".$GLOBALS['table_video'].".tag, ".$GLOBALS['table_video'].".desc,".$GLOBALS['table_video'].".thumbnail_1x   FROM ".$GLOBALS['table_video']." WHERE id IN ('".str_replace(" ","",implode("','",$vid_ids))."') ";

// execute query
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    
    // output data of each row
    $data = array();
    while($row = mysqli_fetch_assoc($result)) {
       
        $data[] = $row;
    }
    $fData = array("title" => $title, "data" => $data);
    echo json_encode($fData);

}else{
    $data = array();
}