<?php
class Books {
    private $conn;
    private $table_name = "books";

    public $book_id;
    public $book_title;
    public $book_genre;
    public $book_user;
    public $book_location;
    public $book_buyprice;
    public $book_rentprice;
    public $book_image;
    public $book_rentdue;
    public $book_rentduration;
    public $book_description;
    public $book_user_image;
    public $book_condition;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $check_query = "SELECT book_id FROM " . $this->table_name . " WHERE book_title = ?";
        $check_stmt = $this->conn->prepare($check_query);
        $check_stmt->bindParam(1, $this->user_username);
        $check_stmt->execute();
        if ($check_stmt->rowCount() > 0) {
            return false;
        }

        $query = "INSERT INTO " . $this->table_name . " SET user_username = :user_username, user_password = :user_password";
        $stmt = $this->conn->prepare($query);
        $this->user_username = htmlspecialchars(strip_tags($this->user_username));
        $this->user_password = htmlspecialchars(strip_tags($this->user_password));
        $stmt->bindParam(":user_username", $this->user_username);
        $stmt->bindParam(":user_password", $this->user_password);
        return $stmt->execute();
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY book_id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE book_id = :book_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':book_id', $this->book_id);
        $stmt->execute();
        return $stmt;
    }
}
?>
