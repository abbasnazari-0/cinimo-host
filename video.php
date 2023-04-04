<?php
// include config file
require_once 'config.php';

// get video id
 if (!isset($_REQUEST['tag'])) {
    die('No video tag specified.');
}else{
    $tag = $_REQUEST['tag'];
}

$user_tag = $_REQUEST['user_tag'];
// get video data
$sql = "SELECT tbl_video.id,tbl_video.title, tbl_video.imdb,tbl_video.tag, tbl_video.desc, tbl_video.artist_tags, tbl_video.thumbnail_1x, tbl_video.thumbnail_2x, tbl_video.qualities_id,tbl_video.gallery_id,tbl_video.video_tags  ,tbl_video_quality.quality_1080,tbl_video_quality.quality_1440, tbl_video_quality.quality_2160, tbl_video_quality.quality_240, tbl_video_quality.quality_360, tbl_video_quality.quality_4320, tbl_video_quality.quality_480, tbl_video_quality.quality_720 , COUNT(tbl_view.user_tag) AS view, COUNT(tbl_user_like.id)  AS user_liked, COUNT(tbl_user_bookmark.id) AS user_bookmarked FROM tbl_video INNER JOIN  tbl_video_quality ON tbl_video.qualities_id = tbl_video_quality.quality_id  LEFT JOIN tbl_view ON tbl_view.vid_tag = tbl_video.tag LEFT JOIN tbl_user_like ON tbl_user_like.vid_tag = tbl_video.tag AND tbl_user_like.user_tag = '$user_tag' LEFT JOIN tbl_user_bookmark ON tbl_user_bookmark.vid_tag = tbl_video.tag AND tbl_user_bookmark.user_tag = '$user_tag' WHERE tag = '$tag' LIMIT 1";

// execute query
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
  // output data of each row
  
  while($row = mysqli_fetch_assoc($result)) {
    // remove key if value is null
    // foreach ($row as $key => $value) {
    //   if ($value == null) {
    //     unset($row[$key]);
    //   }
    // }
    {// get video tag from $row['video_tags'] that is list of number
        $video_tags = $row['video_tags'];
        // remove [ and ] from string
        $video_tags = str_replace('[', '', $video_tags);
        $video_tags = str_replace(']', '', $video_tags);
    
        $video_tags = explode(',', $video_tags);
        // for each video tag get video data
        $tag_data = array();
        $tag_sql = "SELECT tag FROM tbl_vid_tags WHERE id IN ('".str_replace(" ","",implode("','", $video_tags))."')";
        $tag_result = mysqli_query($conn, $tag_sql); 
        if (mysqli_num_rows($tag_result) > 0) {
            while($video_row = mysqli_fetch_assoc($tag_result)) {
                $tag_data[] = $video_row['tag'];
            }
        }
     }
     $row['tag_data'] = $tag_data;
      unset($row['video_tags']);
        
    


    {// get artist by $row['artist_tags'] that is json
        $artist_tags = $row['artist_tags'];
        // remove [ and ] from string
        $artist_tags = str_replace('[', '', $artist_tags);
        $artist_tags = str_replace(']', '', $artist_tags);
    
        $artist_tags = explode(',', $artist_tags);
        // for each artist tag get artist data
        $artist_data = array();
        $artist_sql = "SELECT * FROM tbl_artist WHERE artist_tag IN ('".str_replace(" ","",implode("','", $artist_tags))."')";
        $artist_result = mysqli_query($conn, $artist_sql); 
        if (mysqli_num_rows($artist_result) > 0) {
            while($artist_row = mysqli_fetch_assoc($artist_result)) {
                $artist_data[] = $artist_row;
            }
        }
     }
    
    $row['artist_data'] = $artist_data;
    unset($row['artist_tags']);

    $data = $row;
    
  }
//   echo ($artist_sql);
    echo json_encode($data);
} else {
  echo "0 results";
}

// close connection
mysqli_close($conn);
