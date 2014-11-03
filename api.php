<?php
    header("Content-type: application/json");
    
    function grab_image($url, $saveto){
        $ch = curl_init ($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
        $result = curl_exec($ch);
        // Check if there were any errors
        if($result === false)
        {
            $response = array(
                    'Status' => 'Failed',
                    'Reason' => 'Failed to get image. Check if the URL is valid.'
                    );
            echo json_encode($response);
            die();
        }
        // Check if the response was NULL to save I/O resources instead of writing empty files...
        elseif(strlen(trim($result)) == 0){
            $response = array(
                    'Status' => 'Failed',
                    'Reason' => 'No response. Check if the URL is valid.'
                    );
            echo json_encode($response);
            die();
        }
        
        // Make the magic happen
        curl_close ($ch);
        if(!file_exists($saveto)){
            $fp = fopen($saveto,'x');
            fwrite($fp, $result);
            fclose($fp);
        }
        
    }
    
    $requested_image = (string) $_POST["URL"];
    $file_name = (string) $_POST["NAME"];
    $key = (string) $_POST["KEY"];
    $image_path = '../movie_posters';
    $API_Key = "2f521c8b-be19-4f65-fba2-a1591b11ec2a";
    
    if(!($key == $API_Key)){
        $response = array(
                    'Status' => 'Failed',
                    'Reason' => 'Invalid API key'
                    );
        echo json_encode($response);
    }
    elseif(empty($file_name)){
        $response = array(
                    'Status' => 'Failed',
                    'Reason' => 'Empty file name'
                    );
        echo json_encode($response);
    }
    elseif(empty($requested_image)){
        $response = array(
                    'Status' => 'Failed',
                    'Reason' => 'No url sent'
                    );
        echo json_encode($response);
    }
    else{
        grab_image($requested_image, $image_path."/".$file_name);
        $response = array(
                    'Status' => 'Success',
                    'link' => "/movie_posters/".$file_name
                    );
        echo json_encode($response);
    }

?>