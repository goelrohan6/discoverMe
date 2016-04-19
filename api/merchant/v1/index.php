<?php
require '.././libs/Slim/Slim.php';
require_once 'dbHelper.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app = \Slim\Slim::getInstance();
$db = new dbHelper();

/**
 * Database Helper Function templates
 */
/*
select(table name, where clause as associative array)
insert(table name, data as associative array, mandatory column names as array)
update(table name, column names as associative array, where clause as associative array, required columns as array)
delete(table name, where clause as array)
*/



// categories
$app->get('/categories', function() {
    global $db;
    $rows = $db->select("categories","id,name",array());
    echoResponse(200, $rows);
});

// sub_categories
$app->get('/sub_categories', function() {
    global $db;
    $rows = $db->select("sub_categories","name,category_id",array());
    echoResponse(200, $rows);
});
// feedback
$app->get('/feedback', function() {
    global $db;
    $rows = $db->select("feedback","id,subject,content",array());
    echoResponse(200, $rows);
});
// feedback
$app->get('/feedback/:place_id', function($place_id) {
    global $db;
    $rows = $db->select("feedback","id,subject,content,userid",array('placeid'=>$place_id));
    echoResponse(200, $rows);
});

// sub_sub_categories
$app->get('/sub_sub_categories', function() {
    global $db;
    $rows = $db->select("sub_sub_categories","id,name,sub_category_id",array());
    echoResponse(200, $rows);
});

// sub_sub_category
$app->get('/sub_sub_category/:sub_category_id', function($sub_category_id) {
    global $db;
    $rows = $db->select("sub_sub_category","id,name,sub_category_id",array('sub_category_id'=>$sub_category_id));
    echoResponse(200, $rows);
});

// places
$app->get('/stores/:merchant_id', function($merchant_id) {
    global $db;
    $rows = $db->select("places","id,title,subcat,subtitle,longitude,latitude,radius,charge,locality,thumb,description,chain,rating,tags",array('merchant_id'=>$merchant_id));
    echoResponse(200, $rows);
});

$app->get('/places/:id', function($id) {
    global $db;
    $rows = $db->select("places","id,merchant_id,title,subcat,subtitle,longitude,latitude,charge,locality,thumb,description,chain,rating,tags",array('id'=>$id));
    echoResponse(200, $rows);
});

$app->post('/places', function() use ($app) {
    $data = json_decode($app->request->getBody());
    $mandatory = array('name');
    global $db;
    $rows = $db->insert("places", $data, $mandatory);
    if($rows["status"]=="success")
        $rows["message"] = "Member added successfully.";
    echoResponse(200, $rows);
});

$app->put('/places/:id', function($id) use ($app) {
    $data = json_decode($app->request->getBody());
    $condition = array('id'=>$id);
    $mandatory = array();
    global $db;
    $rows = $db->update("places", $data, $condition, $mandatory);
    if($rows["status"]=="success")
        $rows["message"] = "Member information updated successfully.";
    echoResponse(200, $rows);
});

$app->delete('/places/:id', function($id) {
    global $db;
    $rows = $db->delete("places", array('id'=>$id));
    if($rows["status"]=="success")
        $rows["message"] = "Member removed successfully.";
    echoResponse(200, $rows);
});

// timings
$app->get('/timings/:placeid', function($placeid) {
    global $db;
    $rows = $db->select("timings","id,placeid,day,start,end,break_start,break-end",array('placeid'=>$placeid));
    echoResponse(200, $rows);
});
$app->post('/timings', function() use ($app) {
    $data = json_decode($app->request->getBody());
    $mandatory = array('name');
    global $db;
    $rows = $db->insert("timings", $data, $mandatory);
    if($rows["status"]=="success")
        $rows["message"] = "Member added successfully.";
    echoResponse(200, $rows);
});

$app->put('/timings/:id', function($id) use ($app) {
    $data = json_decode($app->request->getBody());
    $condition = array('id'=>$id);
    $mandatory = array();
    global $db;
    $rows = $db->update("timings", $data, $condition, $mandatory);
    if($rows["status"]=="success")
        $rows["message"] = "Member information updated successfully.";
    echoResponse(200, $rows);
});

$app->delete('/timings/:id', function($id) {
    global $db;
    $rows = $db->delete("timings", array('id'=>$id));
    if($rows["status"]=="success")
        $rows["message"] = "Member removed successfully.";
    echoResponse(200, $rows);
});

// images
$app->get('/images/:placeid', function($placeid) {
    global $db;
    $rows = $db->select("images","id,placeid,image",array('placeid'=>$placeid));
    echoResponse(200, $rows);
});
$app->post('/images', function() use ($app) {
    $data = json_decode($app->request->getBody());
    $mandatory = array('image');
    global $db;
    $rows = $db->insert("images", $data, $mandatory);
    if($rows["status"]=="success")
        $rows["message"] = "Image added successfully.";
    echoResponse(200, $rows);
});

$app->put('/images/:id', function($id) use ($app) {
    $data = json_decode($app->request->getBody());
    $condition = array('id'=>$id);
    $mandatory = array();
    global $db;
    $rows = $db->update("images", $data, $condition, $mandatory);
    if($rows["status"]=="success")
        $rows["message"] = "Member information updated successfully.";
    echoResponse(200, $rows);
});

$app->delete('/images/:id', function($id) {
    global $db;
    $rows = $db->delete("images", array('id'=>$id));
    if($rows["status"]=="success")
        $rows["message"] = "Member removed successfully.";
    echoResponse(200, $rows);
});

// coupons
$app->get('/coupons/:placeid', function($placeid) {
    global $db;
    $rows = $db->select("coupons","id,title,placeid,type,cost,description,conditions",array('placeid'=>$placeid));
    echoResponse(200, $rows);
});
$app->post('/coupons', function() use ($app) {
    $data = json_decode($app->request->getBody());
    $mandatory = array('title');
    global $db;
    $rows = $db->insert("coupons", $data, $mandatory);
    if($rows["status"]=="success")
        $rows["message"] = "Member added successfully.";
    echoResponse(200, $rows);
});

$app->put('/coupons/:id', function($id) use ($app) {
    $data = json_decode($app->request->getBody());
    $condition = array('id'=>$id);
    $mandatory = array();
    global $db;
    $rows = $db->update("coupons", $data, $condition, $mandatory);
    if($rows["status"]=="success")
        $rows["message"] = "Member information updated successfully.";
    echoResponse(200, $rows);
});

$app->delete('/coupons/:id', function($id) {
    global $db;
    $rows = $db->delete("coupons", array('id'=>$id));
    if($rows["status"]=="success")
        $rows["message"] = "Member removed successfully.";
    echoResponse(200, $rows);
});
                // // deals
                // $app->get('/deals/:placeid', function($placeid) {
                //     global $db;
                //     $rows = $db->select("deals","id,placeid,title,type,description,timer,tags",array('placeid'=>$placeid));
                //     echoResponse(200, $rows);
                // });

// tags
$app->get('/tags', function() {
    global $db;
    $rows = $db->select("tags","id,title,value",array());
    echoResponse(200, $rows);
});

// add store item 
$app->post('/user_details', function() use ($app) {
    $data = json_decode($app->request->getBody());
    $mandatory = array('name');
    global $db;
    $rows = $db->insert("store_items", $data, $mandatory);
    if($rows["status"]=="success")
        $rows["message"] = "Store item added succesfully.";
     else
     	$rows["message"] = "Problem in saving item";
    echoResponse(200, $rows);
});




function echoResponse($status_code, $response) {
    global $app;
    $app->status($status_code);
    $app->contentType('application/json');
    echo json_encode($response,JSON_NUMERIC_CHECK);
}

$app->run();
?>