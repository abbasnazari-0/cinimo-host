<?php
// include config file
require_once 'config.php';

// get video id
 if (!isset($_REQUEST['type'])) {
    die('No type specified.');
}else{
    $type = $_REQUEST['type'];
}





if($type == "attach_video"){
    attach($conn);
}elseif($type == "detach_video"){
    dettach($conn);
}

function attach($conn){
    $tag = $_REQUEST['tag'];
    $attach_value = $_REQUEST['attach_value'];
    $sql = "
    UPDATE ".$GLOBALS['tbl_main_catagory']." 
    SET `values` = CONCAT(`values`, ',$attach_value')
    WHERE tag = '$tag'
";
if ($conn->query($sql) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "";
}
}

function dettach($conn){
    $tag = $_REQUEST['tag'];
    $attach_value = $_REQUEST['attach_value'];
    $sql = "
 UPDATE ".$GLOBALS['tbl_main_catagory']."  
 SET `values` = TRIM(',' FROM 
   REPLACE(`values`, '$attach_value,', '') 
 )
 WHERE tag = '$tag'
 
";
if ($conn->query($sql) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "";
}
}

