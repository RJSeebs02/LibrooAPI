<?php
// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
    // Should do a better check to see if the origin is allowed
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);    
}
 
// get database connection
include_once '../../config/connect.php';
 
// instantiate product object
include_once '../../classes/cart.php';
 
$database = new Database();
$db = $database->getConnection();
 
$cart = new Cart($db);
 
// get posted json data
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if (!empty($data->user_username)) {
    // Retrieve user_id from username
    $user_id = $cart->getUserIdByUsername($data->user_username);
    
    // Check if user_id was found
    if ($user_id !== null) {
        // set cart property value
        $cart->user_id = $user_id;
 
        // create the cart
        if ($cart->create()) {
            // set response code - 201 created
            http_response_code(201);
 
            // tell the user
            echo json_encode(array("message" => "Cart was created."));
        } else {
            // set response code - 503 service unavailable
            http_response_code(503);
 
            // tell the user
            echo json_encode(array("message" => "Unable to create cart."));
        }
    } else {
        // set response code - 400 bad request
        http_response_code(400);
 
        // tell the user
        echo json_encode(array("message" => "Unable to create cart. User not found."));
    }
} else {
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("message" => "Unable to create cart. Data is incomplete."));
}
?>
