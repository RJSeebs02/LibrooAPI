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
include_once '../../classes/cart_item.php';
 
$database = new Database();
$db = $database->getConnection();
 
$cart_item = new CartItem($db);
 
// get posted json data
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if(
    !empty($_GET['book_id']) &&
    !empty($_GET['user_username']) &&
    !empty($_GET['product_quantity'])
){

    $user_id = $cart_item->getUserIdByUsername($_GET['user_username']);
    $cart_id = $cart_item->getCartIdByUserId($user_id);
 
    // set product property values
    $cart_item->cart_id = $cart_id;
    $cart_item->book_id = $_GET['book_id'];
    $cart_item->product_quantity = $_GET['product_quantity'];
 
    // create the product
    if($cart_item->create()){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("message" => "Added to Cart."));
    }
 
    // if unable to create the product, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
        echo json_encode(array("message" => "Unable to add to cart."));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("message" => "Unable to add to cart. Data is incomplete."));
}
?>