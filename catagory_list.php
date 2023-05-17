<?php
// include config file
require_once 'config.php';

// get video id
 if (!isset($_REQUEST['type'])) {
    die('No type specified.');
}else{
    $type = $_REQUEST['type'];
}





if($type == "delete_item"){
    deleteCatagories($conn);
}elseif($type == "get"){
    getCatagories($conn);
}elseif($type == "edit"){
    editCatagoriesTitle($conn);
}


function editCatagoriesTitle($conn){
    $title = $_REQUEST['title'];
    $tag = $_REQUEST['tag'];
    $sql ="UPDATE tbl_main_catagory 
    JOIN tbl_sub_catagory ON tbl_main_catagory.id = tbl_sub_catagory.id  
    SET  
    tbl_main_catagory.title = '$title',
    tbl_main_catagory.title = '$tag'
    WHERE tbl_main_catagory.tag = '$tag'";


    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }
  

}
function getCatagories($conn){
    $sql  = 'SELECT tbl_main_catagory.*, "main" as type FROM tbl_main_catagory WHERE title  NOT LIKE "tbl%"
    UNION 
    SELECT tbl_sub_catagory.*, "sub" as type FROM tbl_sub_catagory ';


    // execute query
    $result = mysqli_query($conn, $sql);
    $data = array();
    if (mysqli_num_rows($result) > 0) {
        while($sub_row = mysqli_fetch_assoc($result)) { 
            $data[] = $sub_row;
        }
        json_encode($data);
    }else{
        return json_encode([]);
    }
}
function deleteCatagories($conn){
    $tag = $_REQUEST['tag'];
    $sql = "DELETE tbl_main_catagory, tbl_sub_catagory
    FROM tbl_main_catagory 
    JOIN tbl_sub_catagory ON tbl_main_catagory.id = tbl_sub_catagory.id
    WHERE tbl_main_catagory.tag = '%$tag%'";

    if ($conn->query($sql) === TRUE) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
  

}