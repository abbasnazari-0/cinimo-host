<?php
// include config file
require_once 'config.php';

if (!isset($_REQUEST['type'])) {
    die('No type specified.');
}else{
    $type = $_REQUEST['type'];
}

if($type == "submit"){
    submitVideo($conn);
}else{
    getVideo($conn);
}


function getVideo($conn){
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

}

function submitVideo($conn){
    $imdb = $_REQUEST['imdb'];
    $title = $_REQUEST['title'];
    $desc = $_REQUEST['description'];
    $artistTags = $_REQUEST['artistTags'];
    $gallery = $_REQUEST['galleryTags'];
    $videoList = $_REQUEST['videoList'];
    $videoTags = $_REQUEST['videoTags'];


    { // Gallery And Thumbnail Data
        $thumbnail_1x  = "";
        $thumbnail_2x  = "";
        // gallery ID
        $gallery_id = randomString(15);
        
        // conver $gallery to array
        $gallery = json_decode($gallery, true);
    
        // for each gallery image insert into tbl_gallery

        $gallery_list = [];
        foreach ($gallery as $key => $value) {
            if($thumbnail_1x == ""){ 
                $thumbnail_1x = $value;
                // remove item from $gallery
                unset($gallery[$key]);
                continue;

            }
            if($thumbnail_2x == "") {
                $thumbnail_2x = $value;
                unset($gallery[$key]);    
                continue;
            }
            $gallery_list[] = $value;
        }

        // check galler list length
        if(count($gallery_list) > 0){
            // insert gallery tags to tbl_gallery
            $gallery_sql = "INSERT INTO tbl_gallery (photo_id, gallery_id) VALUES ('".implode("', '$gallery_id'),('", $gallery_list)."', '$gallery_id') ;";
            
        }
    }
    
    $quaility_id = randomString(15);
    { //videoList 
        // [{"quality":"480","value":"fdghfdhdg"},{"quality":"720","value":"hdhghgfhfghfg"}]
        $video_list = json_decode($videoList, true);
        $quality_list = [];
        $value_list = [];
        foreach ($video_list as $key => $value) {
            // get $value value
            $key = ("quality_" . $value['quality']);
            $quality_list[] = "quality_".$value['quality'];
            $value_list[] = "'".$value['value']."'";

            
        }
        // convert array to string
        $quality_list = implode(", ", $quality_list);
        $value_list = implode(", ", $value_list);
        // insert video quality to tbl_video_quality and value_list should in "" in separated by comma

        $video_quality_sql = "INSERT INTO tbl_video_quality ($quality_list, quality_id) VALUES ( $value_list, '$quaility_id') ;";
    }


    $videoTag = randomString(15);
    // insert video data to tbl_video
    $video_sql = "INSERT INTO tbl_video (`title`, `imdb`, `tag`, `desc`, `artist_tags`, `thumbnail_1x`, `thumbnail_2x` ,`qualities_id`, `gallery_id`, `video_tags`) VALUES ('$title', '$imdb','$videoTag',  '$desc', '$artistTags', '$thumbnail_1x', '$thumbnail_2x', '$quaility_id', '$gallery_id', '$videoTags');";

    $multiple_insert_query = $video_quality_sql . $gallery_sql . $video_sql;


    // run  multiple insert query
    if (mysqli_multi_query($conn, $multiple_insert_query)) {
        echo "added successfully";
    } else {
        echo "Error: " . $multiple_insert_query . "<br>" . mysqli_error($conn);
    }



    
}



// close connection
mysqli_close($conn);
