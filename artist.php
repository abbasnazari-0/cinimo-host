<?php
// include config file
require_once 'config.php';


// get video id
 if (!isset($_REQUEST['type'] )) {
    die('No type specified.');
}
$type = $_REQUEST['type'];

if($type == "get"){
    getArtist($conn);
}else{
    submitArtist($conn);
}


function submitArtist($conn){
    $artist_name = $_REQUEST['artist_name'];
    $artist_image = $_REQUEST['artist_image'];
    $artist_tag = randomString(10);


    // insert query 
    $sql = "INSERT INTO tbl_artist (artist_name, artist_pic, artist_tag) VALUES ('$artist_name', '$artist_image', '$artist_tag')";

    // run query
    if (mysqli_query($conn, $sql)) {
        echo "added";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
function getArtist($conn){
    $limit = @($_REQUEST['limit']);
    if(@$_REQUEST['name']){
        $name = $_REQUEST['name'];
        $sql = "SELECT artist_name, artist_pic, artist_tag FROM tbl_artist WHERE artist_name LIKE '%$name%' ORDER BY id DESC LIMIT $limit";
   }else{
       // query
       $sql = "SELECT artist_name, artist_pic, artist_tag FROM tbl_artist  ORDER BY id DESC LIMIT $limit";
   }


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

}


