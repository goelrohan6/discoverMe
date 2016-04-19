<?php
    require_once 'dbHelper.php';
    require '.././libs/Slim/Slim.php';
 
    \Slim\Slim::registerAutoloader();
 
    $app = new \Slim\Slim();
 
    // User id from db - Global Variable
    $user_id = NULL;

    /**
    * Verifying required params posted or not
    */
    function verifyRequiredParams($required_fields) {
        $error = false;
        $error_fields = "";
        $request_params = array();
        $request_params = $_REQUEST;
        // Handling PUT request params
        if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
            $app = \Slim\Slim::getInstance();
            parse_str($app->request()->getBody(), $request_params);
        }   
        foreach ($required_fields as $field) {
            if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
                $error = true;
                $error_fields .= $field . ', ';
            }
        }
 
        if ($error) {
            // Required field(s) are missing or empty
            // echo error json and stop the app
            $response = array();
            $app = \Slim\Slim::getInstance();
            $response["error"] = true;
            $response["message"] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
            echoRespnse(400, $response);
            $app->stop();
        }
    }

    /**
    * Validating email address
    */
    function validateEmail($email) {
        $app = \Slim\Slim::getInstance();
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response["error"] = true;
            $response["message"] = 'Email address is not valid';
            echoRespnse(400, $response);
            $app->stop();
        }
    }
 
    /**
    * Echoing json response to client
    * @param String $status_code Http response code
    * @param Int $response Json response
    */
    function echoRespnse($status_code, $response) {
        $app = \Slim\Slim::getInstance();
        // Http response code
        $app->status($status_code);
 
        // setting response content type to json
        $app->contentType('application/json');
 
        echo json_encode($response);
    }
 

    $app->post('/register', function() use ($app) {
        
        // check for required params
        verifyRequiredParams(array('name', 'email', 'password'));
 
        $response = array();
        require_once 'PassHash.php';
 
        // reading post params
        $name = $app->request->post('name');
        $email = $app->request->post('email');
        $password_raw = $app->request->post('password');
 
        // validating email address
        validateEmail($email);
 
        $db = new dbHelper();
        echo "string1";

        // First check if user already existed in db
        if (!$db->isUserExists($email)) {
            // Generating password hash
        echo "string2";
            $password = PassHash::hash($password_raw);
            
            $obj= array(
                'name'=>$name,
                'email' =>$email,
                'password' =>$password);

            json_encode($obj);

            $column_names = array('name','email','password');
            // insert query
            $result=$db->insertIntoTable($obj,$column_names,"merchants");
 
            // Check for successful insertion
            if ($result) {
            
                $getResult = $db-> getOneRecord("select id from merchants where email='$email'");
                // User successfully inserted
                    $response["error"] = false;
                    $response["message"] = "Registration successful!";
                    if ($getResult) {
                        $response["id"] = $getResult['id'];
                    }
                    echoRespnse(200, $response);
            } else {
                // Failed to create user
                $response["error"] = true;
                $response["message"] = "Oops! An error occurred while registereing";
                echoRespnse(200, $response);
            }
        } else {
            // User with same email already existed in the db
            $response["error"] = true;
            $response["message"] = "Sorry, this email  already existed";
            echoRespnse(200, $response);
        }
    });
    
    
    /**
 * User Login
 * url - /login
 * method - POST
 * params - email, password
 */
        $app->post('/login', function() use ($app) {
            // check for required params
            verifyRequiredParams(array('email', 'password'));
            
            require_once 'PassHash.php';
            // reading post params
            $email = $app->request()->post('email');
            $password = $app->request()->post('password');
            $response = array();
 
            $db = new dbHelper();

            $user = $db->getOneRecord("select id,name,email,password from merchants where email='$email'");

            if($user){
                if(PassHash::check_password($user['password'],$password)){
                    $response["error"] = false;
                    $response["message"] = 'Login successful!';
                    $response['user']['id'] = $user['id'];
                    $response['user']['name'] = $user['name'];
                    $response['user']['email'] = $user['email'];
                   
                }else{
                    // user credentials are wrong
                    $response['error'] = true;
                    $response['message'] = 'Login failed. Incorrect credentials';    
                }
            }else{
                // unknown error occurred
                $response['error'] = true;
                $response['message'] = "Invalid credentials. Try again.";
            }
 
            echoRespnse(200, $response);
        });

    $app->run();

?>