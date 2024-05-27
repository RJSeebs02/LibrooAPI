<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/carting_item.php';

$database = new Database();
$db = $database->getConnection();

$carting_item = new CartingItem($db);

$data = json_decode(file_get_contents("php://input"));

if (
    !empty($data->user_username) &&
    !empty($data->book_ids)
) {
    $user_id = $cart_item->getUserIdByUsername($_GET['user_username']);
    $cart_id = $cart_item->getCartIdByUserId($user_id);   

    $cart_item->cart_id = $cart_id;
    foreach ($data->book_ids as $book_id) {
        $carting_item->book_id = $book_id;
        if (!$carting_item->delete()) {
            echo json_encode(array("message" => "Unable to delete carting item."));
            return;
        }
    }

    echo json_encode(array("message" => "Carting item was deleted."));
} else {
    echo json_encode(array("message" => "Incomplete data."));
}
?>
