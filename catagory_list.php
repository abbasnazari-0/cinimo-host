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
    $sql = "SELECT ".$GLOBALS['table_video'].".id, ".$GLOBALS['table_video'].".title, ".$GLOBALS['table_video'].".imdb, ".$GLOBALS['table_video'].".tag, ".$GLOBALS['table_video'].".desc,".$GLOBALS['table_video'].".thumbnail_1x  , COUNT(user_tag) AS view FROM ".$GLOBALS['table_video']."  LEFT JOIN tbl_view ON tbl_view.vid_tag = ".$GLOBALS['table_video'].".tag WHERE ".$GLOBALS['table_video'].".id IN($values)  GROUP BY ".$GLOBALS['table_video'].".id  ORDER BY view DESC ";

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
        $sql = "INSERT INTO ".$GLOBALS['tbl_sub_cataogry']."  (`title`, `values`) VALUES ('$title','$values')";
    }else{
        $sql = "INSERT INTO ".$GLOBALS['tbl_main_catagory']." (`title`, `values`) VALUES ('$title','$values')";
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
        $sql = "UPDATE ".$GLOBALS['tbl_sub_cataogry']."  SET `title` = '$title', `values` =  '$values' WHERE `id` =  $id";
    }else{
        $sql = "UPDATE ".$GLOBALS['tbl_main_catagory']."  SET `title` = '$title', `values` =  '$values' WHERE `id` =  $id";
    }

    if($conn -> query($sql) == TRUE){
        echo "ok";
    }else{
        echo "nok";
    }


}
function getCatagories($conn){
    $sql  = 'SELECT '.$GLOBALS['tbl_main_catagory'].'.*, "sub" as type FROM '.$GLOBALS['tbl_main_catagory'].' WHERE title  NOT LIKE "tbl%"
    UNION 
    SELECT '.$GLOBALS['tbl_sub_cataogry'].'.*, "main" as type FROM '.$GLOBALS['tbl_sub_cataogry'];


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
        $sql = "DELETE   FROM ".$GLOBALS['tbl_main_catagory']."   WHERE id = '$id'";
    

    }else{
        $sql = "DELETE  FROM  ".$GLOBALS['tbl_sub_cataogry']." WHERE id = '$id'";
        

    }
   

    if ($conn->query($sql) === TRUE) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
  

}



mysqli_close($conn);