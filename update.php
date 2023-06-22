<?php
// include config file
require_once 'config.php';
// get type
if (!isset($_REQUEST['type'] )) {
    die('No type specified.');
}
$type = $_REQUEST['type'];
$verion = $_REQUEST['version'];

if($type == "get"){
    getUpdate($verion);
}




function getUpdate($verion){
    $last_version_code = 24;


    if($last_version_code > $verion){
        echo json_encode(array("data"=> "update available!", "link" => "https://play.google.com/store/apps/details?id=com.arianadeveloper.world.unmovie"));
    }else{
        echo json_encode(array("data"=> "no update available!", "link" => ""));
    }
}