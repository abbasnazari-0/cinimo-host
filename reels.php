<?php

require_once 'config.php';


// get video id
 if (!isset($_REQUEST['user_tag'] )) {
    die('No type specified.');
}
$user_tag = $_REQUEST['user_tag'];
$count = @($_REQUEST['count']);


// select reel table data
$sql = "SELECT tbl_reels.*,  COUNT(tbl_reels_view.id) AS view, COUNT(tbl_reels_like.id) AS reels_like , COUNT(reels_user_like.id) as user_liked FROM tbl_reels  LEFT JOIN tbl_reels_view ON tbl_reels_view.vid_tag = tbl_reels.tag LEFT JOIN tbl_reels_like  ON tbl_reels_like.reel_tag = tbl_reels.tag LEFT JOIN tbl_reels_like as reels_user_like ON reels_user_like.reel_tag = tbl_reels.tag AND reels_user_like.user_tag = '$user_tag' GROUP BY tbl_reels.id ORDER BY id, view DESC LIMIT $count";

// execute query
$result = mysqli_query($conn, $sql);
$data = array();

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
}


echo json_encode($data);
mysqli_close($conn);