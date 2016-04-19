<?php
 
// Path to move uploaded files
$target_path = "../uploads/";
 
// array for final json respone
$response = array();
 require_once '../config.php';

// getting server ip address
$server_ip = gethostbyname(gethostname());
 
// final file url that is being uploaded
$file_upload_url = 'https://' . $server_ip . '/' . $target_path;
 
$placeid = $_POST['placeid'];

 
if (isset($_FILES['image']['name'])) {
    $target_path = $target_path . basename($_FILES['image']['name']);
 
    $response['file_name'] = basename($_FILES['image']['name']);
 
    try {
        // Throws exception incase file is not being moved
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
            // make error flag true
            $response['error'] = true;
            $response['message'] = 'Could not move the file!';
        }
        // Create connection
        $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
        // Check connection
        if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
        }
        $sql = " INSERT INTO images (placeid,image) VALUES ('".$placeid."','http://android.brinjal.me/merchant/uploads/".$response['file_name']."'); ";

        if ($conn->query($sql) === TRUE) {

        // File successfully uploaded
        $response['message'] = "File uploaded succesfully!";
        $response['error'] = false;
        $response['file_path'] = $file_upload_url . basename($_FILES['image']['name']);

        } else {
            $response['error'] = true;
            $response['message'] = 'DB not updated'.$conn->error;
        }

        $conn->close();
    } catch (Exception $e) {
        // Exception occurred. Make error flag true
        $response['error'] = true;
        $response['message'] = $e->getMessage();
    }
} else {
    // File parameter is missing
    $response['error'] = true;
    $response['message'] = 'Not received any file!';
}
 
// Echo final json response to client
echo json_encode($response);
?>