<?php
// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    }

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    }

    exit(0);
}

// include database and object files
include_once '../../config/connect.php';
include_once '../../classes/cart_item.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

// initialize object
$cart_item = new CartItem($db);

$user_username = isset($_GET['user_username']) ? $_GET['user_username'] : null;
$book_id = isset($_GET['book_id']) ? $_GET['book_id'] : null;
$product_quantity = isset($_GET['product_quantity']) ? $_GET['product_quantity'] : 1;

if ($user_username && $book_id) {
    $user_id = $cart_item->getUserIdByUsername($user_username);

    if ($user_id) {
        // Check if the book already exists in the cart
        $cart_item->user_id = $user_id;
        $cart_item->book_id = $book_id;
        $stmt = $cart_item->readSingle();
        $num = $stmt->rowCount();

        if ($num > 0) {
            // If the book already exists, update the quantity
            $cart_item->user_id = $user_id;
            $cart_item->book_id = $book_id;
            $cart_item->product_quantity = $product_quantity;
            if ($cart_item->update()) {
                http_response_code(200);
                echo json_encode(array("message" => "Quantity updated in cart."));
            } else {
                // Unable to update quantity
                http_response_code(503);
                echo json_encode(array("message" => "Unable to update quantity in cart."));
            }
        } else {
            // If the book does not exist, add it to the cart
            $cart_item->user_id = $user_id;
            $cart_item->book_id = $book_id;
            $cart_item->product_quantity = $product_quantity;
            if ($cart_item->create()) {
                http_response_code(201);
                echo json_encode(array("message" => "Added to cart."));
            } else {
                // Unable to add to cart
                http_response_code(503);
                echo json_encode(array("message" => "Unable to add to cart."));
            }
        }
    } else {
        // User not found
        http_response_code(404);
        echo json_encode(array("message" => "User not found."));
    }
} else {
    // Bad request
    http_response_code(400);
    echo json_encode(array("message" => "Username and book ID are required."));
}
?>
