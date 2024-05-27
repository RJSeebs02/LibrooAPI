<?php
// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    exit(0);
}

include_once '../../config/connect.php';
include_once '../../classes/books.php';

$database = new Database();
$db = $database->getConnection();
$book = new Books($db);

$book_id = isset($_GET['book_id']) ? $_GET['book_id'] : null;

if ($book_id !== null) {
    $book_id = intval($book_id);
    $book->book_id = $book_id;
    $stmt = $book->readOne();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        extract($row);

        $book_item = array(
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
        );

        http_response_code(200);
        echo json_encode($book_item);

        if (json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(500);
            echo json_encode(array("message" => "Error encoding JSON: " . json_last_error_msg()));
            exit;
        }
    } else {
        http_response_code(404);
        echo json_encode(array("message" => "No book found."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Invalid book ID."));
}
?>