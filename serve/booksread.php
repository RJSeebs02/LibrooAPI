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
 
// database connection will be here
// include database and object files
include_once '../config/connect.php';
include_once '../classes/books.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$book = new Books($db);
 
// read products will be here
// query products
$stmt = $book->read();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    // products array
    $book_arr=array();
    //$seat_arr["records"]=array();
 
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $book_item=array(
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
 
        //array_push($seat_arr["records"], $seat_item);
        array_push($book_arr, $book_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show products data in json format
    echo json_encode($book_arr);
}else{
 
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no products found
    echo json_encode(
        array("message" => "No books found.")
    );
}
// no products found will be here