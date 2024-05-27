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

if ($user_username) {
    $user_id = $cart_item->getUserIdByUsername($user_username);

    if ($user_id) {
        $cart_item->user_id = $user_id;
        $stmt = $cart_item->read();
        $num = $stmt->rowCount();

        if ($num > 0) {
            $cart_item_arr = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);

                $cart_item_item = array(
                    "book_id" => $book_id,
                    "book_title" => $book_title,
                    "book_genre" => $book_genre,
                    "book_user" => $book_user,
                    "book_location" => $book_location,
                    "book_buyprice" => $book_buyprice,
                    "book_rentprice" => $book_rentprice,
                    "book_image" => $book_image,
                    "book_rentdue" => $book_rentdue,
                    "book_rentduration" => $book_rentduration,
                    "book_description" => $book_description,
                    "book_user_image" => $book_user_image,
                    "book_condition" => $book_condition,
                    "product_quantity" => $product_quantity
                );

                array_push($cart_item_arr, $cart_item_item);
            }

            // set response code - 200 OK
            http_response_code(200);

            // show products data in json format
            echo json_encode($cart_item_arr);
        } else {
            // set response code - 404 Not found
            http_response_code(404);
            echo json_encode(array("message" => "No cart items found."));
        }
    } else {
        // set response code - 404 Not found
        http_response_code(404);
        echo json_encode(array("message" => "User not found."));
    }
} else {
    // set response code - 400 Bad Request
    http_response_code(400);
    echo json_encode(array("message" => "Username is required."));
}
?>
