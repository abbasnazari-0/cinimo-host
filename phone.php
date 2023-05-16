<?php

require_once 'config.php';

if (!isset($_REQUEST['type'])) {
    die('No type specified.');
}else{
    $type = $_REQUEST['type'];
}


if($type == "submit"){
    submitNumber($conn);
}else{
    validateCode($conn);
}


function submitNumber($conn){

    if(!isset($_REQUEST['phone']) || empty($_REQUEST['phone'])){
        echo json_encode(array("status" => "error", "message" => "Phone number is required."));
        exit();
    }
    if(!validatePhone($_REQUEST['phone'])){
        echo json_encode(array("status" => "error", "message" => "Invalid phone number."));
        exit();
    }

    // first check last code sent to user
    $sql = "SELECT * FROM tbl_otp WHERE phone = '".$_REQUEST['phone']."' ORDER BY id DESC LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $last_code = $row['code'];
        $last_time = $row['lastSent'];
        $last_id = $row['id'];
        $time_attempt = $row['timeAttempt'];

        // fist check if last code is valid
        {
            $time = time();
            $time_diff = $time - $last_time;
            if($time_diff < 60){
                $time_left = 60 - $time_diff;
                echo json_encode(array("status" => "error", "message" => "Please wait ".$time_left." seconds before sending another code."));
                exit();
            }else{
                // check if user has reached maximum attempts and 5 minutes has passed
                if($time_attempt >= 3){
                    $time_diff = $time - $last_time;
                    if($time_diff < 300){
                        $time_left = 300 - $time_diff;
                        echo json_encode(array("status" => "error", "message" => "You have reached maximum attempts. Please try again after ".$time_left." seconds."));
                        exit();
                    }else{
                        // reset time attempt
                        $time_attempt = 0;
                    }
                }
                // send code by sms api and save code in database
                $code = rand(100000, 999999);
                $date = date('Y-m-d H:i:s');
            
                if(sendCode($_REQUEST['phone'],$code)){
                    $time_attempt = $time_attempt + 1;
                    $sql = "UPDATE tbl_otp SET code = '$code', lastSent = '$time', timeAttempt = '".($time_attempt)."' WHERE id = '$last_id'";
                
                    if ($conn->query($sql) === TRUE) {
                        echo json_encode(array("status" => "success", "message" => "Code sent successfully."));
                    } else {
                        echo json_encode(array("status" => "error", "message" => "Error sending code."));
                    }  
                }else{
                    echo json_encode(array("status" => "error", "message" => "Error sending code."));
                }
            }
            
        }        
    }else{
        // Send code by sms api and save code in database
        $code = rand(100000, 999999);
        $date = time();
        

        if(sendCode($_REQUEST['phone'],$code)){
            $sql = "INSERT INTO tbl_otp (phone, code, lastSent, timeAttempt) VALUES ('".$_REQUEST['phone']."', '$code', '$date', '1')";
        
            if ($conn->query($sql) === TRUE) {
            echo json_encode(array("status" => "success", "message" => "Code sent successfully."));
            } else {
                echo json_encode(array("status" => "error", "message" => "Error sending code."));
            }  
        }else{
            echo json_encode(array("status" => "error", "message" => "Error sending code."));
        }
    }




}

// create function to validate phone iranian number
function validatePhone($phone){
    $phone = str_replace(' ', '', $phone);
    $phone = str_replace('-', '', $phone);
    $phone = str_replace('(', '', $phone);
    $phone = str_replace(')', '', $phone);
    $phone = str_replace('+', '', $phone);
    if (preg_match('/^(\+98|0098|98|0)?9\d{9}$/', $phone)) {
        return true;
    }
    return false;
}

function validateCode($conn){
    // verify code
    $code  = $_REQUEST['code'];
    $phone = $_REQUEST['phone'];

    $sql = "SELECT * FROM tbl_otp WHERE phone = '$phone' AND code = '$code' ORDER BY id DESC LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_assoc($result);
        $last_time = $row['lastSent'];
        $time = time();
        $time_diff = $time - $last_time;
        if($time_diff < 60){
            echo json_encode(array("status" => "success", "message" => "Code verified successfully."));
        }else{
            echo json_encode(array("status" => "error", "message" => "Code expired."));
        }
    }else{
        echo json_encode(array("status" => "error", "message" => "Invalid code."));
    }

}

// send sms by url api to sms.ir
function sendCode($phone,$code){
  
      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.sms.ir/v1/send/verify',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
        "mobile": "'.$phone.'",
        "templateId": 685360,
        "parameters": [
          {
            "name": "code",
            "value": "'.$code.'"
          }
        ]
      }',
        CURLOPT_HTTPHEADER => array(
          'Content-Type: application/json',
          'Accept: text/plain',
          'x-api-key: QO5KtoFwZyxsjOQe5iW7PA4seg2AxdaX6beZUtJuTQmBcQwQAlnROtb6UBhNpkG0'
        ),
      ));

      $response = curl_exec($curl);

      curl_close($curl);
      
    //   {"status":1,"message":"موفق","data":{"messageId":7957730,"cost":1.20}}
    
  
     $res = json_decode($response, true);
    if($res['status'] == 1){
        return true;
    }
    return false;
    
    
}
