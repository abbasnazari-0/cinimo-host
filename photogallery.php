<?php
// include config file
require_once 'config.php';

// get video id
 if (!isset($_REQUEST['gallery_id'])) {
    die('No query specified.');
}else{
    $gallery_id = $_REQUEST['gallery_id'];
}

// get video data
$sql = "SELECT * FROM tbl_gallery WHERE gallery_id = '$gallery_id'";


// execute query
$result = mysqli_query($conn, $sql);
 
if (mysqli_num_rows($result) > 0) {
    
    // output data of each row
    $data = array();
    while($row = mysqli_fetch_assoc($result)) {
        
        $data[] = $row['photo_id'];
    }
    echo json_encode($data);

} else {
    echo "0 results";
}

mysqli_close($conn);