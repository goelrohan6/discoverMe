<?php

class dbHelper {
        
    private $conn;

    function __construct() {
        require_once '../include/dbConnect.php';

        $db = new dbConnect();
        $this->conn = $db->connect();
    }

    /**
     * Fetching single record
     */
    public function getOneRecord($query) {
        $r = $this->conn->query($query.' LIMIT 1') or die($this->conn->error.__LINE__);
        return $result = $r->fetch_assoc();    
    }

    /**
     * Fetching single record
     */
    public function getAllRecord($query) {
        $r = $this->conn->query($query) or die($this->conn->error.__LINE__);
        $response = array();

        if(count($r)<=0){
                $response["error"] = "True";
                $response["message"] = "No data found.";
            }else{
                $response["error"] = "False";
                $response["message"] = "Data selected from database";
            }
                $response["data"] = $r;

        return $response;    
    }

    /**
     * Creating new record
     */
    public function insertIntoTable($obj, $column_names, $table_name) {
        
        $c = (array) $obj;
        $keys = array_keys($c);
        $columns = '';
        $values = '';
        foreach($column_names as $desired_key){ // Check the obj received. If blank insert blank into the array.
           if(!in_array($desired_key, $keys)) {
                $$desired_key = '';
            }else{
                $$desired_key = $c[$desired_key];
            }
            $columns = $columns.$desired_key.',';
            $values = $values."'".$$desired_key."',";
        }
        $query = "INSERT INTO ".$table_name."(".trim($columns,',').") VALUES(".trim($values,',').")";
        $r = $this->conn->query($query) or die($this->conn->error.__LINE__);
        if ($r) {
            $new_row_id = $this->conn->insert_id;
            return $new_row_id;
            } else {
            return NULL;
        }
    }

    function echoResponse($status_code, $response) {
        global $app;
        $app->status($status_code);
        $app->contentType('application/json');
        echo json_encode($response,JSON_NUMERIC_CHECK);
    }

    /**
     * Generating random Unique MD5 String for user Api key
     */
    public function generateApiKey() {
        return md5(uniqid(rand(), true));
    }

    /**
     * Checking for duplicate user by email address
     * @param String $email email to check in db
     * @return boolean
     */
    public function isUserExists($email ) {
        $stmt = $this->conn->prepare("SELECT id from merchants WHERE email  = ?");
        $stmt->bind_param("s", $email );
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }
 
    /**
     * Fetching user by email
     * @param String $email User email id
     */
    public function getUserByEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM merchants WHERE email = ?");
        $stmt->bind_param("s", $email);
        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            return $user;
        } else {
            return NULL;
        }
    }

}
?>