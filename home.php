<?php
// include config file
require_once 'config.php';

// get video id
 if (!isset($_REQUEST['user_tag'])) {
    die('No user_tag specified.');
}else{
    $user_tag = $_REQUEST['user_tag'];
}



//  get home catagory
$sql = "SELECT * FROM ". $GLOBALS['tbl_main_catagory'];

// execute query
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    
    // output data of each row
    $data = array();
    while($row = mysqli_fetch_assoc($result)) {
        // check title that start with "tbl_"
        if (strpos($row["title"], 'tbl_') !== false) {
            //     "values": "2,3,4,5",
            // now get all video id in id
            // convert value string to array
            $row["values"] = explode(",", $row["values"]);
            
            $sub_sql = "SELECT * FROM  ".$row["title"] ." WHERE id IN ('".str_replace(" ","",implode("','", $row['values']))."')";


            // remove values from array
       

             // execute query
  
            $result2 = mysqli_query($conn, $sub_sql);
                if (mysqli_num_rows($result2) > 0) {
                    
                    // output data of each row
                    $sub_data = array();
                    while($sub_row = mysqli_fetch_assoc($result2)) {
                        unset($sub_row["id"]);
                        // $sub_row["val"] =  getVideosById($conn, $sub_row['values']);

                        // check if values is  array
        if(!is_array($sub_row['values'])){
            // convert values string to array
            $sub_row["values"] = explode(",", $sub_row["values"]);
        }

                        $sub_data[] = $sub_row;
                    }
                    $row['data'] = $sub_data;
                } else {
                    $row['data'] = "empty";
                }

            // echo "dd";
            
            // $row["title"] = str_replace("tbl_", "", $row["title"]);
            // $row["title"] = str_replace("_", " ", $row["title"]);
            // $row["title"] = ucwords($row["title"]);
            
            
        // check if values is in array

        // if(array_key_exists("values", $row['values'])){      
            // getVideosById($conn, $row['values']);
            
        // }
        }
       
        // check if values is  array
        if(!is_array($row['values'])){
            // convert values string to array
            $row["values"] = explode(",", $row["values"]);
        }
       
        unset($row["id"]);
   

        $data[] = $row;
    }
    echo json_encode($data);

} else {
    echo "0 results";
}

function getVideosById($conn, $values){
    //convert value string to array
    $values = explode(",", $values);

    $sql = "SELECT * FROM ".$GLOBALS['table_video']." WHERE id IN ('".str_replace(" ","",implode("','", $values))."')";

    $result = mysqli_query($conn, $sql);
    $data = array();

    if (mysqli_num_rows($result) > 0) {
        
        // output data of each row
   
        while($sub_row = mysqli_fetch_assoc($result)) {
          
            $data[] = $sub_row;
        }
        
    } else {
        $data = array();
        
    }
    return $data;

}

