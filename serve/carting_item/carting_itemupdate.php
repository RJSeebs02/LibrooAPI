<?php
// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');
}

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    exit(0);
}

// get database connection
include_once '../../config/connect.php';
include_once '../../classes/cart_item.php';

$database = new Database();
$db = $database->getConnection();

$cart_item = new CartItem($db);

// get posted json data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if (!empty($_GET['book_id']) && !empty($_GET['user_username']) && !empty($_GET['product_quantity'])) {
    $user_id = $cart_item->getUserIdByUsername($_GET['user_username']);
    $cart_id = $cart_item->getCartIdByUserId($user_id);

    $cart_item->cart_id = $cart_id;
    $cart_item->book_id = $_GET['book_id'];
    $cart_item->product_quantity = $_GET['product_quantity'];

    // update the product quantity
    if ($cart_item->update()) {
        http_response_code(200);
        echo json_encode(array("message" => "Quantity updated."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to update quantity."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Incomplete data."));
}
?>
