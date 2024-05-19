<?php
class Books{
 
    // database connection and table name
    private $conn;
    private $table_name = "books";
 
    // object properties
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
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // create user
    function create() {
        // Query to check if the username already exists
        $check_query = "SELECT book_id FROM " . $this->table_name . " WHERE book_title = ?";
        $check_stmt = $this->conn->prepare($check_query);
        $check_stmt->bindParam(1, $this->user_username);
        $check_stmt->execute();
        
        // If the username already exists, return false
        if ($check_stmt->rowCount() > 0) {
            return false; // Username already exists
        }
        
        // Query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    user_username = :user_username, user_password = :user_password";
        
        // Prepare query
        $stmt = $this->conn->prepare($query);
        
        // Sanitize
        $this->user_username = htmlspecialchars(strip_tags($this->user_username));
        $this->user_password = htmlspecialchars(strip_tags($this->user_password));
        
        // Bind values
        $stmt->bindParam(":user_username", $this->user_username);
        $stmt->bindParam(":user_password", $this->user_password);
        
        // Execute query
        if ($stmt->execute()) {
            return true; // Record inserted successfully
        }
        
        return false; // Error in execution
    }

    // read products
    function read(){
 
        // select all query
        $query = "SELECT * FROM
        " . $this->table_name . "  ORDER BY
                    book_id DESC";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }
}
?>