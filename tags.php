<?php

require_once 'config.php';

$type = $_REQUEST['type'];

if($type == "add"){
    AddTags($conn);
}else{
    getTags($conn);
}

function AddTags($conn){
    $video_tags = $_REQUEST['tags'];

    $video_tags = str_replace('[', '', $video_tags);
    $video_tags = str_replace(']', '', $video_tags);

    $video_tags = explode(',', $video_tags);
    
    
    // insert query
    // "INSERT INTO tbl_tags (tags) VALUES ()()()";
    $sql = "INSERT INTO tbl_vid_tags (tag) VALUES ('".implode("'),('", $video_tags)."') ;";

    // run query
    if (mysqli_query($conn, $sql)) {
        echo "added";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

}

function getTags($conn){
    // select query 
    $sql = "SELECT * FROM tbl_vid_tags";


    // execute query
    $result = mysqli_query($conn, $sql);
    $data = array();
    // execute query
    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }


    echo json_encode($data);
}
