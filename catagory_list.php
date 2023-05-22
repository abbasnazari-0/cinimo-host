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
}elseif($type == "add"){
    addCatagoryLayout($conn);   
}elseif($type == "getvalues"){
    getValuesAttached($conn);   
}
 
function getValuesAttached($conn){
    $values = $_REQUEST['values'];
    $sql = "SELECT tbl_video.id, tbl_video.title, tbl_video.imdb, tbl_video.tag, tbl_video.desc,tbl_video.thumbnail_1x  , COUNT(user_tag) AS view FROM tbl_video  LEFT JOIN tbl_view ON tbl_view.vid_tag = tbl_video.tag WHERE tbl_video.id IN($values)  GROUP BY tbl_video.id  ORDER BY view DESC ";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $data = array();
        while($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        echo json_encode($data);
    }else{
        return "[]";
    }

}
function addCatagoryLayout($conn){

    $title = $_REQUEST['title'];
    $values = $_REQUEST['values'];

    if($_REQUEST['mtype'] == "sub"){
        $sql = "INSERT INTO tbl_sub_catagory  (`title`, `values`) VALUES ('$title','$values')";
    }else{
        $sql = "INSERT INTO tbl_main_catagory (`title`, `values`) VALUES ('$title','$values')";
    }

    if($conn -> query($sql) == TRUE){
        echo "ok";
    }else{
        echo "nok";
    }

}

function editCatagoriesTitle($conn){
  
    $title = $_REQUEST['title'];
    $values = $_REQUEST['values'];
    $id = $_REQUEST['id'];
    if($_REQUEST['mtype'] == "main"){
        $sql = "UPDATE tbl_sub_catagory  SET `title` = '$title', `values` =  '$values' WHERE `id` =  $id";
    }else{
        $sql = "UPDATE tbl_main_catagory  SET `title` = '$title', `values` =  '$values' WHERE `id` =  $id";
    }

    if($conn -> query($sql) == TRUE){
        echo "ok";
    }else{
        echo "nok";
    }


}
function getCatagories($conn){
    $sql  = 'SELECT tbl_main_catagory.*, "sub" as type FROM tbl_main_catagory WHERE title  NOT LIKE "tbl%"
    UNION 
    SELECT tbl_sub_catagory.*, "main" as type FROM tbl_sub_catagory ';


    // execute query
    $result = mysqli_query($conn, $sql);
   
    if (mysqli_num_rows($result) > 0) {
        $data = array();
        while($row = mysqli_fetch_assoc($result)) { 
            $data[] = $row;
        }
        echo json_encode($data);
    }else{
        return json_encode([]);
    }
}
function deleteCatagories($conn){

    $id = $_REQUEST['id'];
    if($_REQUEST['mtype'] == "sub"){
        $sql = "DELETE   FROM tbl_main_catagory   WHERE id = '$id'";
    

    }else{
        $sql = "DELETE  FROM  tbl_sub_catagory WHERE id = '$id'";
        

    }
   

    if ($conn->query($sql) === TRUE) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
  

}



mysqli_close($conn);